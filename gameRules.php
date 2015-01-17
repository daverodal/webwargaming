<?php
// gameRules.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

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
    public $turnChange;
    public $phaseClicks;
    public $phaseClickNames;
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
            $this->legacyExchangeRule = true;
            $this->combatModeType = COMBAT_SETUP_MODE;
            $this->gameHasCombatResolutionMode = true;
            $this->trayX = 0;
            $this->trayY = 0;
            $this->deleteCount = 0;
            $this->attackingForceId = BLUE_FORCE;
            $this->defendingForceId = RED_FORCE;
            $this->interactions = array();
            $this->phaseClicks = array();

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

    function processEvent($event, $id, $hexagon, $click)
    {

        /* @var Hexagon $hexagon */
        global $phase_name, $event_name, $mode_name;

        $now = time();
        $interaction = new StdClass();

        $interaction->event = $event;
        $interaction->id = $id;
        $interaction->hexagon = $hexagon;
        $interaction->time = $now;
//        $eventname = $event_name[$event];
//        $modename = $mode_name[$this->mode];
//        $phasename = $phase_name[$this->phase];

        /* @var $battle Battle */
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
//        $mapData = MapData::getInstance();

        //TODO Ugly Ugly Ugly Ugly
        $mapData->specialHexesChanges = new stdClass();
        $mapData->specialHexesVictory = new stdClass();
        //TODO Ugly Ugly Ugly Ugly

        $this->flashMessages = array();
        $this->turnChange = false;

        switch ($this->mode) {

            case REPLACING_MODE:
                switch ($event) {

                    case SELECT_MAP_EVENT:
                        if ($this->replacementsAvail <= 0) {
                            break;
                        }
                        if ($this->currentReplacement !== false) {
                            $hexpart = new Hexpart();
                            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
                            $terrain = $this->moveRules->terrain;
                            $canReplace = false;

                            if (($terrain->terrainIs($hexpart, "newrichmond") || $terrain->terrainIs($hexpart, "town")) && $mapData->specialHexes->{$hexagon->getName()} == $this->attackingForceId) {
                                $canReplace = true;
                            } else {
                                if ($this->attackingForceId == BLUE_FORCE && $terrain->terrainIs($hexpart, "westedge")) {
                                    $canReplace = true;
                                } else if ($this->attackingForceId == RED_FORCE && $terrain->terrainIs($hexpart, "eastedge")) {
                                    $canReplace = true;
                                }
                            }
                            if ($canReplace) {
                                if ($this->force->getEliminated($this->currentReplacement, $hexagon) !== false) {
                                    $this->moveRules->stopReplacing($id);

                                    $this->currentReplacement = false;
                                    $this->replacementsAvail--;
                                }
                            }
                        }
                        break;
                    case SELECT_COUNTER_EVENT:
                        if ($this->replacementsAvail <= 0) {
                            break;
                        }
                        if (strpos($id, "Hex")) {
                            $matchId = array();
                            preg_match("/^[^H]*/", $id, $matchId);
                            $matchHex = array();
                            preg_match("/Hex(.*)/", $id, $matchHex);
                            $id = $matchId[0];
                            $hexagon = new Hexagon($matchHex[1]);

                            if ($this->force->getEliminated($this->currentReplacement, $hexagon) !== false) {
                                $this->moveRules->stopReplacing($id);
                                $this->currentReplacement = false;
                                $this->replacementsAvail--;
                            }
                        }
                        if ($this->force->attackingForceId == $this->force->units[$id]->forceId) {
                            /* @var Unit $unit */
                            $unit = $this->force->getUnit($id);
                            if ($unit->setStatus(STATUS_ELIMINATED)) {
                                $this->currentReplacement = false;
                                $this->moveRules->stopReplacing($id);
                                break;
                            }

                            if ($this->force->units[$id]->status == STATUS_ELIMINATED) {
                                if ($this->currentReplacement !== false && $this->currentReplacement != $id) {
                                    /* @var Unit $unit */
                                    $unit = $this->force->getUnit($this->currentReplacement);
                                    $unit->setStatus(STATUS_ELIMINATED);
                                }
//                                $this->force->units[$id]->status = STATUS_CAN_REPLACE;
                                $this->currentReplacement = $id;
                                $this->moveRules->startReplacing($id);
                                break;
                            }
                            if ($this->force->units[$id]->status != STATUS_CAN_REPLACE && $this->force->units[$id]->status != STATUS_CAN_REINFORCE && $this->force->replace($id)) {
                                $this->replacementsAvail--;
                                if ($this->currentReplacement !== false) {
                                    $this->force->units[$this->currentReplacement]->status = STATUS_ELIMINATED;
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

                    case SELECT_MAP_EVENT:
                        return 0;
                    case SELECT_COUNTER_EVENT:
                        if (strpos($id, "Hex")) {
                            $matchId = array();
                            preg_match("/^[^H]*/", $id, $matchId);
                            $matchHex = array();
                            preg_match("/Hex(.*)/", $id, $matchHex);
                            $id = $matchId[0];
                            $hexagon = $matchHex[1];
                            $event = SELECT_MAP_EVENT;
                        }
                        $this->moveRules->moveUnit($event, $id, $hexagon, $this->turn);
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

            case MOVING_MODE:

                switch ($event) {

                    case SELECT_MAP_EVENT:
                        return 0;
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
                                    if($this->moveRules->exitUnit($unit->id)){
                                        return;
                                    }
                                }
                            }


//                            $this->force->units[$this->moveRules->movingUnitId]->forceMarch = $this->force->units[$this->moveRules->movingUnitId]->forceMarch^1;
                        }
                    case SELECT_COUNTER_EVENT:
                        if (strpos($id, "Hex")) {
                            $matchId = array();
                            preg_match("/^[^H]*/", $id, $matchId);
                            $matchHex = array();
                            preg_match("/Hex(.*)/", $id, $matchHex);
                            $id = $matchId[0];
                            $hexagon = $matchHex[1];
                            $event = SELECT_MAP_EVENT;
                        }
                        if ($id === false) {
                            return false;
                        }
//                    if($this->phase == BLUE_PANZER_PHASE){/* Love that oo design */
//                        if($event == SELECT_COUNTER_EVENT && $this->force->getUnitMaximumMoveAmount($id) != 6){
//                            break;
//                        }
//                    }
                        $this->moveRules->railMove = false;
//                    if($this->phase == RED_RAILROAD_PHASE){/* Love that oo design */
//                        $this->moveRules->railMove = true;
//                    }
                        $ret = $this->moveRules->moveUnit($event, $id, $hexagon, $this->turn);
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
                            if (!$this->force->requiredCombats()) {
                                $this->combatRules->combatResolutionMode();
                                $this->mode = COMBAT_RESOLUTION_MODE;
                                $this->force->clearRequiredCombats();
                                $this->force->recoverUnits($this->phase, $this->moveRules, $this->mode);
                                $this->phaseClicks[] = $click + 1;
                                $this->phaseClickNames[] = "Combat Resolution ";
                            } else {
                                $this->flashMessages[] = "Required Combats Remain";
                            }
                        } else {
                            $this->mode = COMBAT_SETUP_MODE;
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
                        break;

                    case SELECT_BUTTON_EVENT:
                        if ($this->force->moreCombatToResolve() == false) {
                            $this->combatRules->cleanUp();
                            $this->selectNextPhase($click);
                        }
                        break;
                }
                break;

            case FIRE_COMBAT_SETUP_MODE:

                switch ($event) {

                    case SELECT_COUNTER_EVENT:
                        $this->combatRules->setupFireCombat($id);

                        break;

                    case SELECT_BUTTON_EVENT:
                        $this->combatRules->undoDefendersWithoutAttackers();
                        if ($this->gameHasCombatResolutionMode == true) {
                            $this->mode = COMBAT_RESOLUTION_MODE;
                        } else {
                            $this->mode = COMBAT_SETUP_MODE;
                        }
                        break;
                }
                break;

            case FIRE_COMBAT_RESOLUTION_MODE:

                switch ($event) {

                    case SELECT_COUNTER_EVENT:

                        $this->combatRules->resolveFireCombat($id);
                        if ($this->force->unitsAreBeingEliminated() == true) {
                            $this->force->removeEliminatingUnits();
                        }

                        if ($this->force->unitsAreRetreating() == true) {
                            $this->force->clearRetreatHexagonList();
                            $this->mode = RETREATING_MODE;
                        } else { // check if advancing after eliminated unit
                            if ($this->force->unitsAreAdvancing() == true) {
                                $this->mode = ADVANCING_MODE;
                            }
                        }
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase($click);
                        break;
                }
                break;

            case RETREATING_MODE:

                switch ($event) {

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                        $this->moveRules->retreatUnit($event, $id, $hexagon);
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
                        if (strpos($id, "Hex")) {
                            $matchId = array();
                            preg_match("/^[^H]*/", $id, $matchId);
                            $matchHex = array();
                            preg_match("/Hex(.*)/", $id, $matchHex);
                            $id = $matchId[0];
                            $hexagon = new Hexagon($matchHex[1]);
                            $event = SELECT_MAP_EVENT;
                        }
                        $this->moveRules->advanceUnit($event, $id, $hexagon);

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

                        if ($this->force->setStatus($id, STATUS_EXCHANGED)) {
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
        if ($this->moveRules->anyUnitIsMoving) {
            $this->moveRules->stopMove($this->force->units[$this->moveRules->movingUnitId]);
        }
        if ($this->force->moreCombatToResolve() == false && $this->moveRules->anyUnitIsMoving == false) {
            /* @var Battle $battle */
            $battle = Battle::getBattle();
            $victory = $battle->victory;
            for ($i = 0; $i < count($this->phaseChanges); $i++) {
                if ($this->phaseChanges[$i]->currentPhase == $this->phase) {
                    $this->phase = $this->phaseChanges[$i]->nextPhase;
                    $this->mode = $this->phaseChanges[$i]->nextMode;
                    $this->replacementsAvail = false;
                    $this->phaseClicks[] = $click + 1;
                    $this->phaseClickNames[] = $phase_name[$this->phase];
                    if ($this->phaseChanges[$i]->phaseWillIncrementTurn == true) {
                        $this->incrementTurn();
                    }
                    if ($this->attackingForceId != $this->phaseChanges[$i]->nextAttackerId) {
                        $battle = Battle::getBattle();
                        $players = $battle->players;
                        if ($players[1] != $players[2]) {
                            Battle::pokePlayer($players[$this->phaseChanges[$i]->nextAttackerId]);
                        }
                        $victory->playerTurnChange($this->phaseChanges[$i]->nextAttackerId);
                        $this->turnChange = true;
                    }
                    $this->attackingForceId = $this->phaseChanges[$i]->nextAttackerId;
                    $victory->phaseChange();
                    $this->defendingForceId = $this->phaseChanges[$i]->nextDefenderId;

                    $this->force->setAttackingForceId($this->attackingForceId);

                    $this->force->recoverUnits($this->phase, $this->moveRules, $this->mode);

                    if ($this->turn > $this->maxTurn) {
                        $victory->gameOver();
                        $this->flashMessages[] = "@gameover";
                    }


                    return true;;
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
        $theUnits = $this->force->units;
        foreach ($theUnits as $id => $unit) {

            if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $this->turn && $unit->hexagon->parent != "deployBox") {
//                $theUnits[$id]->status = STATUS_ELIMINATED;
//                $theUnits[$id]->hexagon->parent = "deployBox";
            }
        }
    }

    function getInfo()
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