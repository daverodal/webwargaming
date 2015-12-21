<?php
// gameRules.js

// Copyright (c) 2009-2011 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */


class PhaseChange
{

    public $currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn;

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        }
    }
    function set($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn)
    {
        $this->currentPhase = $currentPhase;
        $this->nextPhase = $nextPhase;
        $this->nextMode = $nextMode;
        $this->nextAttackerId = $nextAttackerId;
        $this->nextDefenderId = $nextDefenderId;
        $this->phaseWillIncrementTurn = $phaseWillIncrementTurn;
    }
}

class GameRules
{
    // class references
    /* @var MoveRules $moveRules */
    public $moveRules;
    /* @var CombatRules */
    public $combatRules;
    /* @var Force */
    public $force;
    /* @var PhaseChange */
    public $phaseChanges;
    public $flashMessages;

    public $turn;
    public $maxTurn;
    public $phase;
    public $mode;
    public $combatModeType;
    public $gameHasCombatResolutionMode;
    public $attackingForceId;
    public $defendingForceId;
    public $deleteCount;
    public $interactions;
    public $replacementsAvail;
    public $currentReplacement;
    public $phaseClicks;
    public $phaseClickNames;
    public $playTurnClicks;
    public $legacyExchangeRule;

    function save()
    {
        $data = new StdClass();
        foreach ($this as $k => $v) {
            if (is_object($v)) {
                continue;
            }
            $data->$k = $v;
        }
        return $data;
    }

    function __construct($MoveRules, $CombatRules, $Force, $display, $data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "phaseChanges") {
                    $this->phaseChanges = array();
                    foreach ($v as $phaseChange) {
                        $this->phaseChanges[] = new PhaseChange($phaseChange);
                    }
                    continue;
                }
                $this->$k = $v;
            }
            $this->moveRules = $MoveRules;
            $this->combatRules = $CombatRules;
            $this->force = $Force;
            $this->display = $display;
        } else {
            $this->moveRules = $MoveRules;
            $this->combatRules = $CombatRules;
            $this->force = $Force;
            $this->phaseChanges = array();
            $this->currentReplacement = false;

            $this->turn = 1;
            $this->legacyExchangeRule = false;
            $this->combatModeType = COMBAT_SETUP_MODE;
            $this->gameHasCombatResolutionMode = true;
            $this->trayX = 0;
            $this->trayY = 0;
            $this->deleteCount = 0;
            $this->attackingForceId = BLUE_FORCE;
            $this->defendingForceId = RED_FORCE;
            $this->interactions = array();
            $this->phaseClicks = array();
            $this->playTurnClicks = array();

            $this->force->setAttackingForceId($this->attackingForceId);
        }
        if($this->legacyExchangeRule !== false){
            $this->legacyExchangeRule = true;
        }
    }

    function setMaxTurn($max_Turn)
    {

        $this->maxTurn = $max_Turn;
    }

    function setInitialPhaseMode($phase, $mode)
    {
        global $phase_name;
        $this->phase = $phase;
        $this->mode = $mode;
        $this->phaseClickNames[] = $phase_name[$phase];

    }
    function addPhaseChange($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn)
    {

        $phaseChange = new PhaseChange();
        $phaseChange->set($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn);
        array_push($this->phaseChanges, $phaseChange);
    }

    function processEvent($event, $id, $location, $click)
    {

        /* @var Hexagon $location */

        $now = time();
        $interaction = new StdClass();

        $interaction->event = $event;
        $interaction->id = $id;
        $interaction->hexagon = $location;
        $interaction->time = $now;

        /* @var $battle Battle */
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;

        //TODO Ugly Ugly Ugly Ugly
        $mapData->specialHexesChanges = new stdClass();
        $mapData->specialHexesVictory = new stdClass();
        //TODO Ugly Ugly Ugly Ugly

        $this->flashMessages = array();



        if($event === SELECT_ALT_COUNTER_EVENT){
            if($location !== null){
                $this->flashMessages[] = "@hex ".$location;
            }else{
                $hex = $battle->force->units[$id]->hexagon;
                if($hex->parent == "gameImages"){
                    $this->flashMessages[] = "@hex ".$hex->name;
                }
            }
            return true;
        }

        switch ($this->mode) {

            case REPLACING_MODE:
                switch ($event) {

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        if ($this->replacementsAvail <= 0) {
                            break;
                        }

                        if($this->currentReplacement !== false && $location){
                            $unit = $this->force->getUnit($this->currentReplacement);

                            if ($unit->getReplacing($location) !== false) {
                                $this->moveRules->stopReplacing($id);
                                $this->currentReplacement = false;
                                $this->replacementsAvail--;
                            }
                        }


                        if ($this->force->attackingForceId == $this->force->units[$id]->forceId) {
                            /* @var Unit $unit */
                            $unit = $this->force->getUnit($id);
                            if ($unit->setStatus(STATUS_CAN_REPLACE)) {
                                $this->currentReplacement = false;
                                $this->moveRules->stopReplacing($id);
                                break;
                            }

                            if ($this->force->units[$id]->status == STATUS_CAN_REPLACE) {
                                if ($this->currentReplacement !== false && $this->currentReplacement != $id) {
                                    /* @var Unit $unit */
                                    $unit = $this->force->getUnit($this->currentReplacement);
                                    $unit->setStatus(STATUS_CAN_REPLACE);
                                }
//                                $this->force->units[$id]->status = STATUS_CAN_REPLACE;
                                $this->currentReplacement = $id;
                                $this->moveRules->startReplacing($id);
                                break;
                            }
                            if (isset($this->force->landForce) && $this->force->landForce && $this->force->units[$id]->status != STATUS_REPLACING && $this->force->units[$id]->status != STATUS_CAN_REINFORCE && $this->force->replace($id)) {
                                $this->replacementsAvail--;
                                if ($this->currentReplacement !== false) {
                                    $this->force->units[$this->currentReplacement]->status = STATUS_REPLACED;
                                    $this->moveRules->stopReplacing($id);

                                    $this->currentReplacement = false;
                                }
                            }
                        }
                        break;

                    case SELECT_BUTTON_EVENT:
                        if ($this->selectNextPhase($click)) {
                            $this->replacementsAvail = false;
                        }
                        break;
                }
                break;
            case DEPLOY_MODE:
                switch ($event) {
                    case KEYPRESS_EVENT:
                        $c = chr($id);

                        if($c == 'i' || $c == 'I'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $unit->enterImproved(true);
                        }

                        if($c == 'u' || $c == 'U'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $unit->exitImproved(true);
                        }
                        if($c == 's' || $c == 'S'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);


                            if($unit->split() === false){
                                return false;
                            }
                        }
                        if($c == 'c' || $c == 'C'){
                            $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                            $ret = $this->force->findSimilarInHex($unit);

                            if(is_array($ret) && count($ret) > 0){
                                if($unit->combine($ret[0]) === false){
                                    return false;
                                }
                            }else{
                                return false;

                            }
                        }
                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:



                    return $this->moveRules->moveUnit($event, $id, $location, $this->turn);
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase($click);
                        break;
                }
                break;

            case DISPLAY_MODE:
                if ($event == SELECT_BUTTON_EVENT) {
                    $this->display->next();
                    if (!$this->display->currentMessage) {
                        $this->selectNextPhase($click);
                    }
                }
                break;
            case COMBINING_MODE:

                switch ($event) {

                    case KEYPRESS_EVENT:
                        if ($this->moveRules->anyUnitIsMoving) {
                            $c = chr($id);

                            if($c == 'c' || $c == 'C'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                $ret = $this->force->findSimilarInHex($unit);

                                if(is_array($ret) && count($ret) > 0){
                                    if($unit->combine($ret[0]) === false){
                                        return false;
                                    }else{
                                        $this->moveRules->stopMove($unit, true);
                                        return true;
                                    }
                                }else{
                                    return false;

                                }
                            }

                        }
                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        if ($id === false) {
                            return false;
                        }

                        $this->moveRules->railMove = false;

                        $ret = $this->moveRules->selectUnit($event, $id, $location, $this->turn);
                        return $ret;
                        break;

                    case SELECT_BUTTON_EVENT:

//                        $this->force->getCombine();

                        return $this->selectNextPhase($click);
                        break;
                }
                break;
            case MOVING_MODE:

                switch ($event) {

                    case KEYPRESS_EVENT:
                        if ($this->moveRules->anyUnitIsMoving) {
                            $c = chr($id);
                            if ($c == 'm' || $c == 'M') {
                                /* @var Unit $unit */
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);
                                if (!$unit->unitHasNotMoved()) {
                                    return false;
                                }
                                if ($unit->forceMarch === true) {
                                    $unit->forceMarch = false;
                                } else {
                                    $unit->forceMarch = true;
                                }
                            }

                            if($c == 'x' || $c == 'X'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                if ($unit->hexagon->parent == "gameImages") {
                                    $this->moveRules->exitUnit($unit->id);
                                    return;

                                }
                            }

                            if($c == 'i' || $c == 'I'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);
                                $ret = $unit->enterImproved();
                                if($ret) {
                                    $hexName = $unit->hexagon->name;
                                    if ($unit->isImproved) {
                                        $battle->mapData->specialHexesVictory->$hexName = "IP!";
                                    } else {
                                        $battle->mapData->specialHexesVictory->$hexName = "No IP";
                                    }
                                }
                                return $ret;
                            }

                            if($c == 'u' || $c == 'U'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                return $unit->exitImproved();
                            }

                            if($id == 37){
                                if(method_exists($this->moveRules, 'turnLeft')){
                                    $ret = $this->moveRules->turnLeft();
                                    return $ret;
                                }
                            }


                            if($id == 39){
                                if(method_exists($this->moveRules, 'turnRight')){
                                    $ret = $this->moveRules->turnRight();
                                    return $ret;
                                }
                            }

                            if($c == 's' || $c == 'S'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);


                                if($unit->split() === false){
                                    return false;
                                }
                            }
                            if($c == 'c' || $c == 'C'){
                                $unit = $this->force->getUnit($this->moveRules->movingUnitId);

                                $ret = $this->force->findSimilarInHex($unit);

                                if(is_array($ret) && count($ret) > 0){
                                    if($unit->combine($ret[0]) === false){
                                        return false;
                                    }
                                }else{
                                    return false;

                                }
                            }

                        }
                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        if ($id === false) {
                            return false;
                        }

                        $this->moveRules->railMove = false;

                        $ret = $this->moveRules->moveUnit($event, $id, $location, $this->turn);
                        return $ret;
                        break;

                    case SELECT_BUTTON_EVENT:

                        $numCombines = 0;
                        if(method_exists($this->force, 'getCombine')){
                            $numCombines = $this->force->getCombine();
                        }

                        $ret =  $this->selectNextPhase($click);
                        if($ret === true && $this->mode === COMBINING_MODE && $numCombines === 0){
                            $this->flashMessages[] = "No Combines Possible. Skipping to Next Phase.";
                            $ret =  $this->selectNextPhase($click);
                        }
                        return $ret;
                        break;
                }
                break;


            case SPEED_MODE:

                switch ($event) {

                    case KEYPRESS_EVENT:
                        if ($this->moveRules->anyUnitIsMoving) {
                            $c = chr($id);

                            if($id == 37){
                                if(method_exists($this->moveRules, 'slower')){
                                    $ret = $this->moveRules->slower();
                                    return $ret;
                                }
                            }


                            if($id == 39){
                                if(method_exists($this->moveRules, 'faster')){
                                    $ret = $this->moveRules->faster();
                                    return $ret;
                                }
                            }

                        }
                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        if ($id === false) {
                            return false;
                        }

                        $ret = $this->moveRules->selectUnit($event, $id, $location, $this->turn);
                        return $ret;
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase($click);
                        break;
                }
                break;

            case COMBAT_SETUP_MODE:
                $shift = false;
                switch ($event) {

                    case KEYPRESS_EVENT:
                        $c = chr($id);
                        if ($c == 'd' || $c == 'D') {
                            $this->combatRules->useDetermined();
                        }
                        if ($c == 'c' || $c == 'C') {
                            $this->combatRules->clearCurrentCombat();
                        }
                        break;

                    /** @noinspection PhpMissingBreakStatementInspection */
                    case SELECT_SHIFT_COUNTER_EVENT:
                        $shift = true;
                    /* fall through */
                    case SELECT_COUNTER_EVENT:
                        $this->combatRules->setupCombat($id, $shift);

                        break;
                    case COMBAT_PIN_EVENT:
                        $this->combatRules->pinCombat($id);
                        break;

                    case SELECT_BUTTON_EVENT:
                        $this->combatRules->undoDefendersWithoutAttackers();
                        if ($this->gameHasCombatResolutionMode == true) {
                            if (!(isset($this->force->landForce) && $this->force->landForce && $this->force->requiredCombats())) {
                                $this->combatRules->combatResolutionMode();
                                $this->mode = COMBAT_RESOLUTION_MODE;
                                if(isset($this->force->landForce) && $this->force->landForce){
                                    $this->force->clearRequiredCombats();
                                }
                                $this->force->recoverUnits($this->phase, $this->moveRules, $this->mode);
                                $this->phaseClicks[] = $click + 1;
                                $this->phaseClickNames[] = "Combat Resolution ";
                            } else {
                                $this->flashMessages[] = "Required Combats Remain";
                            }
                        } else {
                            $this->selectNextPhase($click);
                            if($this->phase == BLUE_COMBAT_RES_PHASE || $this->phase == RED_COMBAT_RES_PHASE){
                                $this->combatRules->combatResolutionMode();
                                $defender = $this->force->defendingForceId;
                                $attacker = $this->force->attackingForceId;
                                if($this->combatRules->combatsToResolve) {
                                    foreach ($this->combatRules->combatsToResolve as $key => $val) {
                                        if ($this->force->units[$key]->forceId === $defender) {
                                            $this->force->defendingForceId = $defender;
                                            $this->force->attackingForceId = $attacker;
                                        } else {
                                            $this->force->defendingForceId = $attacker;
                                            $this->force->attackingForceId = $defender;
                                        }
                                        $interaction->dieRoll = $this->combatRules->resolveCombat($key);
                                    }
                                    $this->force->defendingForceId = $defender;
                                    $this->force->attackingForceId = $attacker;
                                }
                            }
                        }
                        break;
                    default:
                        return 0;
                }
                break;

            case COMBAT_RESOLUTION_MODE:

                switch ($event) {

                    case SELECT_COUNTER_EVENT:

                        $interaction->dieRoll = $this->combatRules->resolveCombat($id);
                        if ($this->force->unitsAreBeingEliminated() == true) {
                            $this->force->removeEliminatingUnits();
                        }
                        if(isset($this->force->landForce) && $this->force->landForce === true) {
                            if ($this->force->unitsAreExchanging() == true) {
                                $this->mode = EXCHANGING_MODE;
                            }

                            if ($this->force->unitsAreAttackerLosing() == true) {
                                $this->mode = ATTACKER_LOSING_MODE;
                            }

                            if ($this->force->unitsAreRetreating() == true) {
                                $this->force->clearRetreatHexagonList();
                                $this->mode = RETREATING_MODE;
                            } else { // check if advancing after eliminated unit
                                if ($this->force->unitsAreAdvancing() == true) {
                                    $this->mode = ADVANCING_MODE;
                                }
                            }
                        }
                        break;

                    case SELECT_BUTTON_EVENT:
                        if ($this->force->moreCombatToResolve() == false) {
                            $this->combatRules->cleanUp();
                            $this->selectNextPhase($click);
                        }
                        break;
                }
                break;


            case RETREATING_MODE:

                switch ($event) {

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        $this->moveRules->retreatUnit($event, $id, $location);
                        if ($this->force->unitsAreRetreating() == false) {
                            if ($this->force->unitsAreExchanging() == true) {
                                $this->mode = EXCHANGING_MODE;
                            } else {
                                if ($this->force->unitsAreAdvancing() == true) {
                                    $this->mode = ADVANCING_MODE;
                                } else { // melee
                                    if ($this->combatModeType == COMBAT_SETUP_MODE) {
                                        if ($this->gameHasCombatResolutionMode == true) {
                                            $this->mode = COMBAT_RESOLUTION_MODE;
                                        } else {
                                            $this->mode = COMBAT_SETUP_MODE;
                                        }
                                    } else { // fire
                                        if ($this->gameHasCombatResolutionMode == true) {
                                            $this->mode = FIRE_COMBAT_RESOLUTION_MODE;
                                        } else {
                                            $this->mode = FIRE_COMBAT_SETUP_MODE;
                                        }
                                    }
                                }
                            }
                        }
                        break;
                }
                break;

            case ADVANCING_MODE:

                switch ($event) {

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        $this->moveRules->advanceUnit($event, $id, $location);

                        if ($this->force->unitsAreAdvancing() == false) { // melee
                            if ($this->combatModeType == COMBAT_SETUP_MODE) {
                                if ($this->gameHasCombatResolutionMode == true) {
                                    $this->mode = COMBAT_RESOLUTION_MODE;
                                } else {
                                    $this->mode = COMBAT_SETUP_MODE;
                                }
                            } else {
                                if ($this->gameHasCombatResolutionMode == true) {
                                    $this->mode = FIRE_COMBAT_RESOLUTION_MODE;
                                } else {
                                    $this->mode = FIRE_COMBAT_SETUP_MODE;
                                }
                            }
                        }
                        break;
                }
                break;
            case EXCHANGING_MODE:
            case ATTACKER_LOSING_MODE:

                switch ($event) {

                    case SELECT_COUNTER_EVENT:

                        $unit = $this->force->getUnit($id);
                        if ($unit->setStatus(STATUS_EXCHANGED)) {
                            $this->force->exchangeUnit($unit);
                            if ($this->force->unitsAreBeingEliminated() == true) {
                                $this->force->removeEliminatingUnits();
                            }
                            if($this->legacyExchangeRule || $this->force->getExchangeAmount() <= 0 || $this->combatRules->noMoreAttackers()) {
                                if ($this->force->exchangingAreAdvancing() == true && $this->mode == EXCHANGING_MODE) { // melee
                                    $this->mode = ADVANCING_MODE;
                                } else {
                                    $this->mode = COMBAT_RESOLUTION_MODE;
                                }
                            }
                        }
                }
                break;
        }

//        $this->interactions[] = $interaction;

        return true;

    }

    function selectNextPhase($click)
    {
        global $phase_name;

        if($this->mode == MOVING_MODE && $this->moveRules->movesLeft()){
            return false;
        }
        if ($this->moveRules->anyUnitIsMoving) {
            $this->moveRules->stopMove($this->force->units[$this->moveRules->movingUnitId]);
        }
        if ((($this->gameHasCombatResolutionMode  == false) ||  ($this->force->moreCombatToResolve() == false)) && $this->moveRules->anyUnitIsMoving == false) {
            /* @var Battle $battle */
            $battle = Battle::getBattle();
            $victory = $battle->victory;

            for ($i = 0; $i < count($this->phaseChanges); $i++) {
                if ($this->phaseChanges[$i]->currentPhase == $this->phase) {
                    $this->phase = $this->phaseChanges[$i]->nextPhase;
//                    $prevMode = $this->mode;
                    $this->mode = $this->phaseChanges[$i]->nextMode;
//                    if($this->gameHasCombatResolutionMode === false && $this->mode == COMBAT_RESOLUTION_MODE && $prevMode == COMBAT_SETUP_MODE){
//                        $this->combatRules->combatsToResolve = $this->combatRules->combats;
//                    }

                    $this->replacementsAvail = false;
                    $this->phaseClicks[] = $click + 1;
                    $this->phaseClickNames[] = $phase_name[$this->phase];

                    if ($this->attackingForceId != $this->phaseChanges[$i]->nextAttackerId) {
                        $battle = Battle::getBattle();
                        $players = $battle->players;
                        $this->playTurnClicks[] = $click + 1;
                        if ($players[1] != $players[2]) {
                            Battle::pokePlayer($players[$this->phaseChanges[$i]->nextAttackerId]);
                        }
                        $victory->playerTurnChange($this->phaseChanges[$i]->nextAttackerId);
                    }
                    if ($this->phaseChanges[$i]->phaseWillIncrementTurn == true) {
                        $this->incrementTurn();
                    }
                    $this->attackingForceId = $this->phaseChanges[$i]->nextAttackerId;
                    $this->defendingForceId = $this->phaseChanges[$i]->nextDefenderId;

                    $this->force->setAttackingForceId($this->attackingForceId, $this->defendingForceId);

                    $victory->phaseChange();
                    $this->force->recoverUnits($this->phase, $this->moveRules, $this->mode);

                    if ($this->turn > $this->maxTurn) {
                        $victory->gameEnded();
                        $this->flashMessages[] = "@gameover";
                    }


                    return true;
                }
            }
        }
        return false;
    }

    function incrementTurn()
    {
        $this->turn++;
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $victory->incrementTurn();
    }

    function xxxgetInfo()
    {

        //	var info;
        global $phase_name, $force_name, $mode_name;
        $info = "turn: " . $this->turn;
        $info .= " " . $phase_name[$this->phase];
        $info .= " ( " . $force_name[$this->force->getVictorId()];
        if ($this->turn < $this->maxTurn) {
            $info .= " is winning )";
        } else {
            $info .= " wins! )";
        }
        $info .= "<br />&nbsp; " . $mode_name[$this->mode];
        $info .= "<br />last force to occupy Marysville wins";

        return $info;
    }
}
