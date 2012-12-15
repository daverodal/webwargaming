<?php
// gameRules.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

class PhaseChange {

    public $currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn;

    function __construct($data = null)
    {
        if($data){
            foreach($data as $k => $v){
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

class GameRules {
    // class references
    /* @var MoveRules $moveRules */
    public $moveRules;
    /* @var CombatRules */
    public $combatRules;
    /* @var Force */
    public $force;
    /* @var PhaseChange */
    public $phaseChanges;

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

    function __construct($MoveRules, $CombatRules, $Force, $data = null)
    {
        if($data){
            foreach($data as $k => $v){
                if($k == "phaseChanges"){
                    $this->phaseChanges = array();
                    foreach($v as $phaseChange){
                        $this->phaseChanges[] = new PhaseChange($phaseChange);
                    }
                    continue;
                }
                $this->$k = $v;
            }
            $this->moveRules = $MoveRules;
            $this->combatRules = $CombatRules;
            $this->force = $Force;

        }else{
        $this->moveRules = $MoveRules;
        $this->combatRules = $CombatRules;
        $this->force = $Force;
        $this->phaseChanges = array();

        $this->turn = 1;
        $this->phase = BLUE_DEPLOY_PHASE;
        $this->mode = DEPLOY_MODE;
        $this->combatModeType = COMBAT_SETUP_MODE;
        $this->gameHasCombatResolutionMode = true;
        $this->trayX = 0;
        $this->trayY = 0;
        $this->deleteCount = 0;
        $this->attackingForceId = BLUE_FORCE;
        $this->defendingForceId = RED_FORCE;
        $this->interactions = array();

        $this->force->setAttackingForceId($this->attackingForceId);
        }
    }

    function setMaxTurn($max_Turn)
    {

        $this->maxTurn = $max_Turn;
    }

    function addPhaseChange($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn)
    {

        $phaseChange = new PhaseChange();
        $phaseChange->set($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn);
        array_push($this->phaseChanges, $phaseChange);
    }

    function processEvent($event, $id, $hexagon)
    {
        global $phase_name, $event_name, $mode_name;

        $now = time();
        $interaction = new StdClass();
        
        $interaction->event = $event;
        $interaction->id = $id;
        $interaction->hexagon = $hexagon;
        $interaction->time = $now;
        $eventname = $event_name[$event];
        $modename = $mode_name[$this->mode];
        $phasename = $phase_name[$this->phase];

        switch ($this->mode) {

            case REPLACING_MODE:
              switch ($event) {

                    case SELECT_MAP_EVENT:
                        if($this->replacementsAvail <= 0){
                            break;
                        }
                        if($this->currentReplacement !== false){
                        echo "mapRepeelace ".$hexagon->name;
                        $hexpart = new Hexpart();
                        $hexpart->setXYwithNameAndType($hexagon->name,HEXAGON_CENTER);
                        $terrain = $this->moveRules->terrain;
                        echo "Terrain";
                        if($terrain->terrainIs($hexpart, "newrichmond") || $terrain->terrainIs($hexpart, "town") || $terrain->terrainIs($hexpart, "fortified") || $terrain->terrainIs($hexpart, "eastedge")){
                            echo "terrain Is";
                            if($this->force->getEliminated($this->currentReplacement, $hexagon) !== false){
                                echo "Got";
                                $this->currentReplacement = false;
                                $this->replacementsAvail--;
                            }
                        }
                        }
                        break;
                    case SELECT_COUNTER_EVENT:
                        if($this->replacementsAvail <= 0){
                            break;
                        }
                        var_dump($this->force->units[$id]);
                        if($this->force->attackingForceId == $this->force->units[$id]->forceId){
                        if($this->force->units[$id]->status == STATUS_ELIMINATED ){
                            $this->force->units[$id]->status = STATUS_CAN_REPLACE;
                            $this->currentReplacement = $id;
                            break;
                        }
                        echo "replace $id";
                         if($this->force->units[$id]->status != STATUS_CAN_REPLACE && $this->force->units[$id]->status != STATUS_CAN_REINFORCE && $this->force->replace( $id)){
                            $this->replacementsAvail--;
                        }
                        }
                        break;

                    case SELECT_BUTTON_EVENT:
                        $this->replacementsAvail = false;
                        $this->selectNextPhase();
                        break;
                }
                break;
            case DEPLOY_MODE:
                switch ($event) {

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                         $this->moveRules->moveUnit($event, $id, $hexagon, $this->turn);
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase();
                        break;
                }
                break;

            case MOVING_MODE:

                switch ($event) {

                    case SELECT_MAP_EVENT:
                    case SELECT_COUNTER_EVENT:
                    if(strpos($id,"Hex")){
                        $matchId = array();
                        preg_match("/^[^H]*/",$id,$matchId);
                        $matchHex = array();
                        preg_match("/Hex(.*)/",$id,$matchHex);
                        $id = $matchId[0];
                        $hexagon = new Hexagon($matchHex[1]);
                        $event = SELECT_MAP_EVENT;
                    }
//                    if($this->phase == BLUE_PANZER_PHASE){/* Love that oo design */
//                        if($event == SELECT_COUNTER_EVENT && $this->force->getUnitMaximumMoveAmount($id) != 6){
//                            break;
//                        }
//                    }
                    $this->moveRules->railMove = false;
                    if($this->phase == RED_RAILROAD_PHASE){/* Love that oo design */
                        $this->moveRules->railMove = true;
                    }

                       $this->moveRules->moveUnit($event, $id, $hexagon, $this->turn);
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase();
                        break;
                }
                break;


            case COMBAT_SETUP_MODE:
                       $shift = false;
                switch ($event) {

                    case SELECT_SHIFT_COUNTER_EVENT:
                        $shift = true;
                        /* fall through */
                    case SELECT_COUNTER_EVENT:
                        $this->combatRules->setupCombat($id, $shift);

                        break;

                    case SELECT_BUTTON_EVENT:
                        $this->combatRules->undoDefendersWithoutAttackers();
                        if ($this->gameHasCombatResolutionMode == true) {
                            $this->mode = COMBAT_RESOLUTION_MODE;
                            $this->force->recoverUnits($this->phase,$this->moveRules,$this->mode);
                        } else {
                            $this->mode = COMBAT_SETUP_MODE;
                        }
                        break;
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
                        }
                        else { // check if advancing after eliminated unit
                            if ($this->force->unitsAreAdvancing() == true) {
                                $this->mode = ADVANCING_MODE;
                            }
                        }
                        break;

                    case SELECT_BUTTON_EVENT:
                        if($this->force->moreCombatToResolve() == false){
                            $this->combatRules->cleanUp();
                            $this->selectNextPhase();
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
                        $this->force->undoDefendersWithoutAttackers();
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
                        }
                        else { // check if advancing after eliminated unit
                            if ($this->force->unitsAreAdvancing() == true) {
                                $this->mode = ADVANCING_MODE;
                            }
                        }
                        break;

                    case SELECT_BUTTON_EVENT:

                        $this->selectNextPhase();
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
                            }else{
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

                echo "EXGHANGINGMODE";
                switch ($event) {

                    case SELECT_COUNTER_EVENT:

                        echo "the Counter";
                        if ($this->force->setStatus($id, STATUS_EXCHANGED)) {
                            if ($this->force->unitsAreBeingEliminated() == true) {
                                $this->force->removeEliminatingUnits();
                            }
                            if ($this->force->exchangingAreAdvancing() == true && $this->mode == EXCHANGING_MODE) { // melee
                                $this->mode = ADVANCING_MODE;
                            } else {
                                $this->mode = COMBAT_RESOLUTION_MODE;
                            }
                        }
                }
                break;
        }

//        $this->interactions[] = $interaction;
        // see who occupies city
//        $this->force->checkVictoryConditions();
        if ($this->force->isForceEliminated() == true) {
            $this->mode = GAME_OVER_MODE;
            $this->phase = GAME_OVER_PHASE;
        }

    }

    function selectNextPhase()
    {
echo "Teyr next phaes";
        if ($this->force->moreCombatToResolve() == false && $this->moveRules->anyUnitIsMoving == false) {
echo "Past tehe fi";
            for ($i = 0; $i < count($this->phaseChanges); $i++) {

                if ($this->phaseChanges[$i]->currentPhase == $this->phase) {
                    $this->phase = $this->phaseChanges[$i]->nextPhase;
                    $this->mode = $this->phaseChanges[$i]->nextMode;
                    $this->attackingForceId = $this->phaseChanges[$i]->nextAttackerId;
                    $this->defendingForceId = $this->phaseChanges[$i]->nextDefenderId;

                    if ($this->phaseChanges[$i]->phaseWillIncrementTurn == true) {
                        $this->incrementTurn();
                    }

                    $this->force->setAttackingForceId($this->attackingForceId);
                    $this->force->recoverUnits($this->phase,$this->moveRules, $this->mode);

                    $this->replacementsAvail = false;
                    if($this->phase  == BLUE_REPLACEMENT_PHASE){
                        $this->replacementsAvail = 1;
                    }
                    if($this->phase  == RED_REPLACEMENT_PHASE){
                        $this->replacementsAvail = 10;
                    }
                    if ($this->turn > $this->maxTurn) {
                        $this->mode = GAME_OVER_MODE;
                        $this->phase = GAME_OVER_PHASE;
                    }
                    break;
                }
            }
        }
    }

    function incrementTurn()
    {
        $this->turn++;
        if($this->turn == 2){
            $this->force->units[13]->status = STATUS_ELIMINATED;
            $this->force->units[14]->status = STATUS_ELIMINATED;
            $this->force->units[13]->parent = "deadpile";/* TODO OO HEX STUFF */
            $this->force->units[14]->hexagon->parent = "deadpile";
        }
        if($this->turn == 3){
            $this->force->units[15]->status = STATUS_ELIMINATED;
            $this->force->units[16]->status = STATUS_ELIMINATED;
            $this->force->units[17]->status = STATUS_ELIMINATED;
            $this->force->units[15]->hexagon->parent = "deadpile";/* TODO OO HEX STUFF */
            $this->force->units[16]->hexagon->parent = "deadpile";
            $this->force->units[17]->hexagon->parent = "deadpile";
        }
        if($this->turn == 4){
            $this->force->units[18]->status = STATUS_ELIMINATED;
            $this->force->units[19]->status = STATUS_ELIMINATED;
            $this->force->units[18]->parent = "deadpile";/* TODO OO HEX STUFF */
            $this->force->units[19]->hexagon->parent = "deadpile";
        }
        if($this->turn == 5){
            $this->force->units[20]->status = STATUS_ELIMINATED;
            $this->force->units[21]->status = STATUS_ELIMINATED;
            $this->force->units[20]->hexagon->parent = "deadpile";/* TODO OO HEX STUFF */
            $this->force->units[21]->hexagon->parent = "deadpile";
        }
    }

    function getInfo()
    {

        //	var info;
        global $phase_name, $force_name, $mode_name;
        $info = "turn: " + $this->turn;
        $info += " " + $phase_name[$this->phase];
        $info += " ( " + $force_name[$this->force->getVictorId()];
        if ($this->turn < $this->maxTurn) {
            $info += " is winning )";
        } else {
            $info += " wins! )";
        }
        $info += "<br />&nbsp; " + $mode_name[$this->mode];
        $info += "<br />last force to occupy Marysville wins";

        return $info;
    }
}