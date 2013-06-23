<?php
// force.js

// copyright (c) 20092011 Mark Butler
// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 
class RetreatStep
{
    public $stepNumber;
    /* @var Hexagon */
    public $hexagon;

    function set($RetreatStepStepNumber, $RetreatHexagon)
    {
        $this->stepNumber = $RetreatStepStepNumber;
        $this->hexagon = new Hexagon($RetreatHexagon->getNumber());
    }
    function __construct($data = null)
    {
        if($data){
            foreach($data as $k => $v){
                if($k == "hexagon"){
                    $this->hexagon = new Hexagon($v->name);
                    continue;
                }
                $this->$k = $v;
            }
        }
    }
}

class unit implements JsonSerializable
{

    public $id;
    public $forceId;
    public $name;
    /* @var Hexagon */
    public $hexagon;
    public $image;
    public $strength;
    public $maxStrength;
    public $minStrength;
    public $isReduced;
    public $maxMove;
    public $status;
    public $moveAmountUsed;
    public $reinforceZone;
    public $reinforceTurn;
    public $combatNumber;
    public $combatIndex;
    public $combatOdds;
    public $moveCount;
    public $retreatCountRequired;
    public $combatResults;
    public $dieRoll;
    public $range;
    public $nationality;
    public $forceMarch = false;
    public $class;
    public $dirty;

    public function jsonSerialize(){
        if(is_object($this->hexagon)){
        if($this->hexagon->name){
            $this->hexagon = $this->hexagon->getName();

        }else{
            $this->hexagon = $this->hexagon->parent;
        }
        }
        return $this;
    }
    function unitHasMoveAmountAvailable($moveAmount)
    {
        if ($this->moveAmountUsed + $moveAmount <= $this->maxMove) {
            $canMove = true;
        }
        else
        {
            $canMove = false;
        }
        return $canMove;
    }

    function unitHasNotMoved()
    {
        if ($this->moveAmountUsed == 0) {
            $hasMoved = true;
        }
        else
        {
            $hasMoved = false;
        }
        return $hasMoved;
    }

    function unitIsMoving()
    {
        $isMoving = false;
        if ($this->status == STATUS_MOVING) {
            $isMoving = true;
        }
        return $isMoving;
    }

    function unitHasUsedMoveAmount()
    {
        // moveRules amount used can be larger if can always moveRules at least one hexagon
        if ($this->moveAmountUsed >= $this->maxMove) {
            $maxMove = true;
        }
        else
        {
            $maxMove = false;
        }
        return $maxMove;
    }

    function getUnitHexagon()
    {

        return $this->hexagon;
    }

    function setStatus($status)
    {
        $battle = Battle::getBattle();
        $success = false;
        switch ($status)
        {
            case STATUS_EXCHANGED:
                if (($this->status == STATUS_CAN_ATTACK_LOSE || $this->status == STATUS_CAN_EXCHANGE)) {
                    if($this->isReduced){
                        $this->status = STATUS_ELIMINATING;
                        $amtLost = $this->minStrength;
                    }else{
                        $battle->victory->reduceUnit($this);
                        $this->strength = $this->minStrength;
                        $this->isReduced = true;
                        $amtLost = $this->maxStrength - $this->minStrength;
                    }
                    $this->exchangeAmount -= $amtLost;
                    if($this->exchangeAmount <= 0){
                        $success = true;
                    }
                }
                break;

            case STATUS_CAN_REPLACE:
                if ( $this->status == STATUS_ELIMINATED) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_REPLACED:
                if ( $this->status == STATUS_CAN_REPLACE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_ELIMINATED:
                if ( $this->status == STATUS_CAN_REPLACE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_REINFORCING:
                if ( $this->status == STATUS_CAN_REINFORCE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_DEPLOYING:
                if ( $this->status == STATUS_CAN_DEPLOY) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_CAN_REINFORCE:
                if ($this->status == STATUS_REINFORCING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_CAN_DEPLOY:
                if ($this->status == STATUS_DEPLOYING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_READY:
            case STATUS_DEFENDING:
            case STATUS_ATTACKING:
                $this->status = $status;
                break;

            case STATUS_MOVING:
                if (($this->status == STATUS_READY || $this->status == STATUS_REINFORCING)
                ) {
                    $this->status = $status;
                    $this->moveCount = 0;
                    $this->moveAmountUsed = 0;
                    $success = true;
                }
                break;

            case STATUS_STOPPED:
                if ($this->status == STATUS_MOVING || $this->status == STATUS_DEPLOYING) {
                    $this->status = $status;
                    $this->moveAmountUsed = $this->maxMove;

                    $success = true;
                }
                if ($this->status == STATUS_ADVANCING) {
                    $this->status = STATUS_ADVANCED;
//                    $this->moveAmountUsed = $$this->maxMove;
                    $success = true;
                }
                if ($this->status == STATUS_RETREATING) {
                    $this->status = STATUS_RETREATED;
//                    $this->moveAmountUsed = $$this->maxMove;
                    $success = true;
                }
                break;

            case STATUS_EXITED:
                if ($this->status == STATUS_MOVING) {
                    $this->status = status;
                    $success = true;
                }
                break;

            case STATUS_RETREATING:
                if ($this->status == STATUS_CAN_RETREAT) {
                    $this->status = $status;
                    $this->moveCount = 0;
                    $this->moveAmountUsed = 0;
                    $success = true;
                }
                break;

            case STATUS_ADVANCING:
                if ($this->status == STATUS_CAN_ADVANCE) {
                    $this->status = $status;
                    $this->moveCount = 0;
                    $this->moveAmountUsed = 0;
                    $success = true;
                }
                break;

            case STATUS_ADVANCED:
                if ($this->status == STATUS_ADVANCING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            default:
                break;
        }
        $this->dirty = true;
        return $success;
    }

    function updateMoveStatus($hexagon, $moveAmount)
    {

        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
//        $mapData = MapData::getInstance();
        /* @var MapHex $mapHex */
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if($mapHex){
            $mapHex->unsetUnit($this->forceId,$this->id);
        }

        $this->hexagon = $hexagon;
        $this->dirty = true;
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if($mapHex){
            $mapHex->setUnit($this->forceId,$this->id);
            $mapHexName = $mapHex->name;
            if($mapData->specialHexes->$mapHexName){
                if($mapData->specialHexes->$mapHexName != $this->forceId){
                    if($this->forceId == 1){
                        $battle->victory->victoryPoints[1]++;
                    }
                    $mapData->specialHexes->$mapHexName = $this->forceId;
                    $mapData->specialHexesChanges->$mapHexName = true;
                }
            }
        }
        $this->moveCount++;
        $this->moveAmountUsed = $this->moveAmountUsed + $moveAmount;
    }


    function set($unitId, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $forceMarch, $class)
    {
        $this->dirty = true;
        $this->id = $unitId;
        $this->name = $unitName;
        $this->forceId = $unitForceId;
        $this->class = $class;
        $this->hexagon = new Hexagon($unitHexagon);
        /* blah! this can get called from the constructor of Battle. so we can't get ourselves while creating ourselves */
//        $battle = Battle::getBattle();
//        $mapData = $battle->mapData;
        $mapData = MapData::getInstance();
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if($mapHex){
            $mapHex->setUnit($this->forceId,$this->id);
        }
        $this->image = $unitImage;
        $this->strength = $isReduced ? $unitMinStrength : $unitMaxStrength;
        $this->maxMove = $unitMaxMove;
        $this->maxStrength = $unitMaxStrength;
        $this->minStrength = $unitMinStrength;
        $this->isReduced = $isReduced;
        $this->status = $unitStatus;
        $this->moveAmountUsed = 0;
        $this->reinforceZone = $unitReinforceZone;
        $this->reinforceTurn = $unitReinforceTurn;
        $this->combatNumber = 0;
        $this->combatIndex = 0;
        $this->combatOdds = "";
        $this->hasContact = false;
        $this->moveCount = 0;
        $this->retreatCountRequired = 0;
        $this->combatResults = NR;
        $this->range = $range;
        $this->nationality = $nationality;
        $this->forceMarch = $forceMarch;
    }

    function __construct($data = null)
    {
        if($data){
            foreach($data as $k => $v){
                if($k == "hexagon"){
                    $this->hexagon = new Hexagon($v);
//                    $this->hexagon->parent = $data->parent;
                    continue;
                }
                $this->$k = $v;
            }
            $this->dirty = false;
        }
    }
}

class Force
{
    /* @var  unit $units */
    public $units;
    public $victor;
    public $ZOCrule;
    public $attackingForceId;
    public $defendingForceId;
    public $deleteCount;
    public $reinforceTurns;
    public $retreatHexagonList;
    public $eliminationTrayHexagonX;
    public $eliminationTrayHexagonY;
    public $exchangeAmount;

    function __construct($data = null)
    {
        if($data){
                foreach($data as $k => $v){
                    if($k == "units"){
                        $this->units = array();
                        foreach($v as $unit){
                            $this->units[] = new unit($unit);
                        }
                        continue;
                    }
                    if($k == "retreatHexagonList"){
                        $this->retreatHexagonList = array();
                        foreach($v as $retreatStep){
                            $this->retreatHexagonList[] = new RetreatStep($retreatStep);
                        }
                        continue;
                    }
                    $this->$k = $v;
                }
        }else{

            $this->reinforceTurns = new stdClass();
            $this->units = array();
            $this->victor = RED_FORCE;
            $this->ZOCrule = true;
            $this->deleteCount = 0;

            $this->retreatHexagonList = array();
            $this->eliminationTrayHexagonX = 1;
            $this->eliminationTrayHexagonY = 2;
        }
    }


    function addToRetreatHexagonList($id, $retreatHexagon)
    {

        //alert("function .prototype. adding: " + id + " : " + retreatHexagon.getName());
        // note: addToRetreatHexagonList() is invoked before retreat move, so
        //  the moveCount is 0 for 1st step and 1 for 2nd step

        $retreatStep = new RetreatStep();
        $retreatStep->set($this->units[$id]->moveCount, $retreatHexagon);

        $this->retreatHexagonList[] = $retreatStep;
    }

    function addUnit($unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range = 1, $nationality = "neutral",$forceMarch = true, $class = false)
    {
        if($unitStatus == STATUS_CAN_REINFORCE){
            if(!$this->reinforceTurns->$unitReinforceTurn){
                $this->reinforceTurns->$unitReinforceTurn = new stdClass();
            }
            $this->reinforceTurns->$unitReinforceTurn->$unitForceId++;
        }
        $id = count($this->units);
        $unit = new unit();
        $unit->set($id, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove,  $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class);

        array_push($this->units,$unit);
    }

    function getFirstRetreatHex($id){
        if(count($this->retreatHexagonList) == 0){
            throw new Exception("Cannot get reatreat hex");
        }
        return $this->retreatHexagonList[0]->hexagon;
    }
    function getAllFirstRetreatHexes($id){
        if(count($this->retreatHexagonList) == 0){
            throw new Exception("Cannot get reatreat hex");
        }
        $hexes = array();
        foreach($this->retreatHexagonList as $hexList){
            if($hexList->stepNumber == 0){
                $hexes[] = $hexList->hexagon;
            }
        }
        return $hexes;
    }
    function advanceIsOnRetreatList($id, $hexagon)
    {
        $isOnList = false;
        for ($i = 0; $i < count($this->retreatHexagonList); $i++)
        {
            // note: addToRetreatHexagonList() is invoked before retreat move, so
            //  the moveCount is 0 for 1st step and 1 for 2nd step
            //  when advancing unit.moveCount will be 0 which will match 1st step retreat number

            //alert("function .prototype. checkingt: " + id + " hexagon: " + hexagon.getName() + " with array " + $this->retreatHexagonList[i].hexagon.getName());
            if ($this->retreatHexagonList[$i]->stepNumber == $this->units[$id]->moveCount
                && $this->retreatHexagonList[$i]->hexagon->equals($hexagon)
            ) {
                $isOnList = true;
            }
        }

        return $isOnList;
    }

    function applyCRTresults($defenderId, $attackers, $combatResults, $dieRoll)
    {
        $battle = Battle::getBattle();
//        $this->clearRetreatHexagonList();

        $distance = 1;

                switch ($combatResults)
                {
                    case EX2:
                        $distance = 2;
                    case EX:
                        if($this->units[$defenderId]->isReduced){
                            $this->units[$defenderId]->status = STATUS_ELIMINATING;
                            $this->exchangeAmount = $this->units[$defenderId]->minStrength;
                        }else{
                            $battle->victory->reduceUnit($this->units[$defenderId]);
                            $this->units[$defenderId]->status = STATUS_CAN_RETREAT;
                            $this->units[$defenderId]->isReduced = true;
                            $this->units[$defenderId]->strength = $this->units[$defenderId]->minStrength;
                            $this->units[$defenderId]->retreatCountRequired = $distance;
                            $this->exchangeAmount = $this->units[$defenderId]->maxStrength - $this->units[$defenderId]->minStrength;
                        }
                        $this->units[$defenderId]->moveCount = 0;
                        $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));
                        break;

                    case AL:
                        $this->units[$defenderId]->status = STATUS_DEFENDED;
                        $this->units[$defenderId]->retreatCountRequired = 0;
                        break;

                    case AE:
                        $this->units[$defenderId]->status = STATUS_DEFENDED;
                        $this->units[$defenderId]->retreatCountRequired = 0;
                        break;

                    case AR:
                        $this->units[$defenderId]->status = STATUS_DEFENDED;
                        $this->units[$defenderId]->retreatCountRequired = 0;
                        break;

                    case DE:
                        $this->units[$defenderId]->status = STATUS_ELIMINATING;
                        $this->units[$defenderId]->retreatCountRequired = $distance;
                        $this->units[$defenderId]->moveCount = 0;
                        $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));
                        break;

                    case DRL2:
                        $distance = 2;
                    case DRL:
                        if($this->units[$defenderId]->isReduced){
                            $this->units[$defenderId]->status = STATUS_ELIMINATING;
                            $this->units[$defenderId]->retreatCountRequired = $distance;
                            $this->units[$defenderId]->moveCount = 0;
                            $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));

                        }else{
                            $battle->victory->reduceUnit($this->units[$defenderId]);
                            $this->units[$defenderId]->isReduced = true;
                            $this->units[$defenderId]->strength = $this->units[$defenderId]->minStrength;
                            $this->units[$defenderId]->status = STATUS_CAN_RETREAT;
                            $this->units[$defenderId]->retreatCountRequired = $distance;
                        }
                        break;
                    case DR2:
                        $distance = 2;
                    case DR:
                        $this->units[$defenderId]->status = STATUS_CAN_RETREAT;
                        $this->units[$defenderId]->retreatCountRequired = $distance;
                        break;

                    case NE:
                        $this->units[$defenderId]->status = STATUS_NO_RESULT;
                        $this->units[$defenderId]->retreatCountRequired = 0;
                        break;

                    default:
                        break;
                }
                $this->units[$defenderId]->combatResults = $combatResults;
                $this->units[$defenderId]->dieRoll = $dieRoll;
                $this->units[$defenderId]->combatNumber = 0;
                $this->units[$defenderId]->moveCount = 0;
            
        
        foreach ($attackers as $attacker => $val)
        {
            if ($this->units[$attacker]->status == STATUS_BOMBARDING) {
                    $this->units[$attacker]->status = STATUS_ATTACKED;
                    $this->units[$attacker]->retreatCountRequired = 0;

            $this->units[$attacker]->combatResults = $combatResults;
            $this->units[$attacker]->dieRoll = $dieRoll;
            $this->units[$attacker]->combatNumber = 0;
            $this->units[$attacker]->moveCount = 0;
        }

            if ($this->units[$attacker]->status == STATUS_ATTACKING) {
                switch ($combatResults)
                {
                    case EX2:
                    case EX:
                        $this->units[$attacker]->status = STATUS_CAN_EXCHANGE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case AE:
                        $this->units[$attacker]->status = STATUS_ELIMINATING;
                        $this->units[$defenderId]->retreatCountRequired = 0;
                        break;

                    case AL:
                        $this->units[$attacker]->status = STATUS_CAN_ATTACK_LOSE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        $this->exchangeAmount = 1;
                        break;

                    case DE:
                        $this->units[$attacker]->status = STATUS_CAN_ADVANCE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case AR:
                        $this->units[$attacker]->status = STATUS_CAN_RETREAT;
                        $this->units[$attacker]->retreatCountRequired = $distance;
                        break;

                    case DRL2:
                    case DR2:
                    case DRL:
                    case DR:
                        $this->units[$attacker]->status = STATUS_CAN_ADVANCE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case NE:
                        $this->units[$attacker]->status = STATUS_NO_RESULT;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    default:
                        break;
                }
                $this->units[$attacker]->combatResults = $combatResults;
                $this->units[$attacker]->dieRoll = $dieRoll;
                $this->units[$attacker]->combatNumber = 0;
                $this->units[$attacker]->moveCount = 0;
            }
        }
        $this->removeEliminatingUnits();
    }

    function checkVictoryConditions()
    {
        // last to occupy Marysville at 403 wins
        $hexagon = new Hexagon(403);
        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->hexagon->equals($hexagon)) {
                $this->victor = $this->units[$id]->forceId;
            }
        }
    }

    function clearRetreatHexagonList()
    {

        $this->retreatHexagonList = array();
    }

    function eliminateUnit($id)
    {
        $unit = $this->units[$id];
        $battle = Battle::getBattle();
        $battle->victory->reduceUnit($unit);
        $forceId = $unit->forceId;
        $this->deleteCount++;
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
//        $mapData = MapData::getInstance();
        $mapHex = $mapData->getHex($unit->hexagon->getName());

        if($mapHex){
            $mapHex->unsetUnit($forceId,$id);
        }
        $unit->status = STATUS_ELIMINATED;
        $unit->isReduced = true;
        $unit->strength = $this->units[$id]->minStrength;
        $col = 0;
//        if($unit->forceId == 2){
//
//            $col = 2100 + floor($id / 10) * 100;
//        }
        $unit->hexagon = new Hexagon($col+$id%10);

        $unit->hexagon->parent = "deadpile";
//        $this->units[$id]->hexagon->setXY($this->eliminationTrayHexagonX + (2 * $this->deleteCount), $this->eliminationTrayHexagonY);
    }

    function getAttackerStrength($attackers)
    {
        $attackerStrength = 0;

          foreach ($attackers as $id => $v)
        {
                $attackerStrength += $this->units[$id]->strength;
        }

        return $attackerStrength;
    }

    function getAttackerHexagonList($defenderId)
    {
        return
        $hexagonList = array();

        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_ATTACKING && $this->units[$id]->combatNumber == $combatNumber) {

               $hexagonList[] = $this->units[$id]->hexagon;
            }
        }
        return $hexagonList;
    }

    function getCombatHexagon($defenderId)
    {
        $hexagon = $this->units[$defenderId]->hexagon;
        return $hexagon;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->combatNumber == $combatNumber && $this->units[$id]->status == STATUS_DEFENDING) {
                $hexagon = $this->units[$id]->hexagon;
            }
        }
        return $hexagon;
    }

    function getCombatInfo($id)
    {
        return $this->units[$id]->combatOdds;
    }

    function getDefenderStrength($defenderId)
    {
        $defenderStrength = 0;
        $defenderStrength += $this->units[$defenderId]->strength;
        return $defenderStrength;
    }

//function getRetreatHexagonList() {
//
//    $retreatHexagonList = "advance hexagon list: ";
//
//    for ($i = 0; $i < 6; $i++) {
//
//        if ($retreatHexpartX[$i] > 0 || $retreatHexpartY[$i] > 0) {
//            $hexagon = new hexagon($retreatHexpartX[$i], $retreatHexpartY[$i]);
//            $retreatHexagonList = $retreatHexagonList + " " + $hexagon->getHexagonName();
//        }
//        //retreatHexagonList += " | " + retreatHexpartX[i] + ", " + retreatHexpartY[i];
//    }
//
//    //retreatHexagonList = retreatHexpartX[0] + ", " + retreatHexpartY[0];
//
//    return ($retreatHexagonList);
//}

    function getUnitBeingEliminatedId()
    {

        for ($i = 0; $i < count($this->units); $i++)
        {
            if ($this->units[$i]->status == STATUS_ELIMINATING) {
                $id = $this->units[$i]->id;
            }
        }
        return $id;
    }

    function getUnitCombatIndex($id)
    {
        return $this->units[$id]->combatIndex;
    }

    function getUnitCombatNumber($id)
    {
        return $this->units[$id]->combatNumber;
    }

    function getUnitForceId($id)
    {
        return $this->units[$id]->forceId;
    }

    function getUnitHexagon($id)
    {

        return $this->units[$id]->hexagon;
    }

    function getUnitInfo($id)
    {
        global $status_name;
        $unitInfo = "";

        if ($id >= 0) {
            $unitInfo = $this->units[$id]->name;
            $unitInfo += " " + $status_name[$this->units[$id]->status];
            $unitInfo += "<br />strength: " + $this->units[$id]->strength;
            $unitInfo += "<br />can move: " + $this->units[$id]->maxMove;

            if ($this->units[$id]->status == STATUS_MOVING) {
                $unitInfo += "<br />has moved " + $this->units[$id]->moveAmountUsed + " of " + $this->units[$id]->maxMove;
            }
            else {
                $unitInfo += "<br />&nbsp;";
            }

            if ($this->units[$id]->status == STATUS_CAN_REINFORCE) {
                $unitInfo += "<br />can reinforce on turn " + $this->units[$id]->reinforceTurn;
            }
            else {
                $unitInfo += "<br />&nbsp;";
            }

            if ($this->units[$id]->status == STATUS_DEFENDING && $this->unitHasAttackers($id)) {
            }
        }
        return $unitInfo;
    }

    function getUnitMaximumMoveAmount($id)
    {
        return $this->units[$id]->maxMove;
    }

    function getUnitMoveCount($id)
    {
        return $this->units[$id]->moveCount;
    }

    function getUnitName($id)
    {
        return $this->units[$id]->name;
    }

    function getUnitReinforceTurn($id)
    {
        return $this->units[$id]->reinforceTurn;
    }

    function getUnit($id){
        return $this->units[$id];
    }
    function getUnitReinforceZone($id)
    {
        return $this->units[$id]->reinforceZone;
    }

    function getUnitRetreatCountRequired($id)
    {
        return $this->units[$id]->retreatCountRequired;
    }

    function getVictorId()
    {
        return $this->victor;
    }

    function hexagonIsZOC($id, $hexagon)
    {
        $isZOC = false;

        if ($this->ZOCrule == true) {
            $los = new Los();
            $los->setOrigin($hexagon);

            for ($i = 0; $i < count($this->units); $i++)
            {
//                echo "eye $i ";
                $los->setEndPoint($this->units[$i]->hexagon);
//                echo "los ".$los->getRange();
                if($los->getRange() == 1){
//                                    echo "ids ".$this->units[$i]->forceId." and ".$this->units[$id]->forceId;
//                    echo "status ".$this->units[$i]->status;

                }
                if ($los->getRange() == 1
                    && $this->units[$i]->forceId != $this->units[$id]->forceId
                    && $this->units[$i]->status != STATUS_CAN_REINFORCE
                    && $this->units[$i]->status != STATUS_ELIMINATED
                ) {
//                    echo "ZOC!!!!";
                    $isZOC = true;
                    break;
                }
            }
        }
        return $isZOC;
    }

    function mapHexIsOccupiedFriendly(MapHex $mapHex)
    {
        if($mapHex->isOccupied($this->attackingForceId)){
            return true;
        }
        return false;
        $isOccupied = false;
        if(is_array($mapHex->forces)){
            foreach($mapHex->forces as $force)
            {
                if(count((array)$force) > 0){
                    $isOccupied = true;
                }
            }
        }
//        for ($id = 0; $id < count($this->units); $id++)
//        {
//            if ($this->units[$id]->hexagon->equals($hexagon)) {
//                $isOccupied = true;
//            }
//        }

        return $isOccupied;
    }

    function mapHexIsZoc(MapHex $mapHex){
        return $mapHex->isZoc($this->defendingForceId);
        $neighbors = $mapHex->neighbors;

        if($neighbors){
            $battle = Battle::getBattle();
            $mapData = $battle->mapData;
//            $mapData = MapData::getInstance();
            foreach($neighbors as $neighbor){
                if($this->mapHexIsOccupiedEnemy($mapData->getHex($neighbor))){
                    return true;
                }
            }
        }
        return false;
    }
    function hexagonIsOccupied($hexagon)
    {
        $isOccupied = false;
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
//        $mapData = MapData::getInstance();
        $mapHex = $mapData->getHex($hexagon->getName());
        if(is_array($mapHex->forces)){
        foreach($mapHex->forces as $force)
        {
           if(count((array)$force) > 0){
                $isOccupied = true;
            }
        }
        }
//        for ($id = 0; $id < count($this->units); $id++)
//        {
//            if ($this->units[$id]->hexagon->equals($hexagon)) {
//                $isOccupied = true;
//            }
//        }

        return $isOccupied;
    }
    function mapHexIsOccupiedEnemy(MapHex $mapHex)
    {
        return $mapHex->isOccupied($this->defendingForceId);
        $isOccupied = false;
        $friendlyId = $this->attackingForceId;

        if($mapHex->forces){
        foreach($mapHex->forces as $forceId => $force)
        {
            if($friendlyId == $forceId){
                continue;
            }
            if(count((array)$force) > 0){
                $isOccupied = true;
            }
        }
        }
        return $isOccupied;
    }
    function hexagonIsOccupiedEnemy($hexagon,$id)
    {
        $isOccupied = false;
        $friendlyId = $this->units[$id]->forceId;
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
//        $mapData = MapData::getInstance();
        $mapHex = $mapData->getHex($hexagon->getName());
        foreach($mapHex->forces as $forceId => $force)
        {
            if($friendlyId == $forceId){
                continue;
            }
            if(count((array)$force) > 0){
                $isOccupied = true;
            }
        }
        return $isOccupied;
    }
    function hexagonIsEnemyOccupied($hexagon)
    {
        $isOccupied = false;
        $friendlyId = $this->attackingForceId;
        for ($id = 0; $id < count($this->units); $id++)
        {
            if($this->units[$id]->forceId != $friendlyId){
                if ($this->units[$id]->hexagon->equals($hexagon)) {
                    $isOccupied = true;
                }
            }
        }

        return $isOccupied;
    }


    function isForceEliminated()
    {
        $isForceEliminated = false;
        $isDefendingForceEliminated = true;
        $isAttackingForceEliminated = true;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->forceId == $this->defendingForceId && $this->units[$id]->status != STATUS_ELIMINATED) {
                // found one alive, so make it false
                $isDefendingForceEliminated = false;
            }
        }

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->forceId == $this->attackingForceId && $this->units[$id]->status != STATUS_ELIMINATED) {
                // found one alive, so make it false
                $isAttackingForceEliminated = false;
            }
        }

        if ($isDefendingForceEliminated == true || $isAttackingForceEliminated == true) {
            $isForceEliminated = true;
        }
        return $isForceEliminated;
    }

    function moreCombatToResolve()
    {

        $moreCombatToResolve = false;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_DEFENDING
                && $this->unitHasAttackers($id)
            ) {
                $moreCombatToResolve = true;
                break;
            }
        }
        return $moreCombatToResolve;
    }

    function recoverUnits($phase,$moveRules, $mode)
    {
        $battle  = Battle::getBattle();
        $victory = $battle->victory;
        $victory->preRecoverUnits();
        for ($id = 0; $id < count($this->units); $id++)
        {
            $victory->preRecoverUnit($id);

            switch ($this->units[$id]->status)
            {
                case STATUS_UNAVAIL_THIS_PHASE:
                case STATUS_STOPPED:
                case STATUS_DEFENDED:
                case STATUS_DEFENDING:
                case STATUS_ATTACKED:
                case STATUS_ATTACKING:
                case STATUS_RETREATED:
                case STATUS_ADVANCED:
                case STATUS_CAN_ADVANCE:
                case STATUS_REPLACED:
                case STATUS_READY:
                case STATUS_REPLACED:
                case STATUS_CAN_UPGRADE:
                case STATUS_NO_RESULT:
                case STATUS_EXCHANGED:
                case STATUS_CAN_ATTACK_LOSE:




                    $status = STATUS_READY;
                /*
                 * Active Locking Zoc rules
                 */
//                    if($this->unitIsZOC($id)){
//                        $status = STATUS_STOPPED;
//                    }
                    if($phase == BLUE_MECH_PHASE && $this->units[$id]->forceId == BLUE_FORCE && $this->units[$id]->maxMove < 6){
                        $status = STATUS_STOPPED;
                    }
                    if($phase == RED_MECH_PHASE && $this->units[$id]->forceId == RED_FORCE && $this->units[$id]->maxMove < 6){
                        $status = STATUS_STOPPED;
                    }
                    if($phase == BLUE_REPLACEMENT_PHASE || $phase == RED_REPLACEMENT_PHASE){
                        $status = STATUS_STOPPED;
                        if($this->units[$id]->forceId == $this->attackingForceId &&
                            $this->units[$id]->isReduced ){
                            $status = STATUS_CAN_UPGRADE;
                        }
                    }
                    if($phase == BLUE_COMBAT_PHASE || $phase == RED_COMBAT_PHASE){
                        if($mode == COMBAT_SETUP_MODE){
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if($this->units[$id]->forceId == $this->attackingForceId &&
                                $this->unitIsZOC($id,  $this->units[$id]->range )){
                                $status = STATUS_READY;
                            }
                        }
                        if($mode == COMBAT_RESOLUTION_MODE){
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if($this->units[$id]->status == STATUS_ATTACKING ||
                                $this->units[$id]->status == STATUS_DEFENDING){
                                $status = $this->units[$id]->status;
                            }

                        }
                    }
                if($mode  == MOVING_MODE && $moveRules->stickyZOC){
                    if($this->units[$id]->forceId == $this->attackingForceId &&
                        $this->unitIsZOC($id,  $this->units[$id]->range )){
                        $status = STATUS_STOPPED;
                    }
                }
//                    if($phase == RED_RAILROAD_PHASE) {
//                        $status = STATUS_STOPPED;
//                        $hexpart = new Hexpart();
//                        $hexpart->setXYwithNameAndType($this->units[$id]->hexagon->name, HEXAGON_CENTER);
//                        $terrain = $moveRules->terrain;
//                        if ($terrain->terrainIs($hexpart, "fortified") || $terrain->terrainIs($hexpart, "newrichmond")) {
//                            $status = STATUS_READY;
//                        }
//                    }
                    $this->units[$id]->status = $status;
                    $this->units[$id]->moveAmountUsed = 0;
                    break;

                default:
                    break;
            }
            $this->units[$id]->combatIndex = 0;
            $this->units[$id]->combatNumber = 0;
            $this->units[$id]->combatResults = NE;
            $victory->postRecoverUnit($this->units[$id]);

        }
        $victory->postRecoverUnits();

    }

    function removeEliminatingUnits()
    {
        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_ELIMINATING) {
                $this->eliminateUnit($id);
            }
        }
    }
    function resetRemainingNonAdvancingUnits()
    {
        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_CAN_ADVANCE) {
                $this->units[$id]->status = STATUS_ATTACKED;
            }
        }
    }

    function resetRemainingAdvancingUnits()
    {
        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_ADVANCING || $this->units[$id]->status == STATUS_CAN_ADVANCE) {
                $this->units[$id]->status = STATUS_ATTACKED;
            }
        }
    }

    function setAttackingForceId($forceId)
    {
        if ($forceId == BLUE_FORCE) {
            $this->attackingForceId = BLUE_FORCE;
            $this->defendingForceId = RED_FORCE;
        }
        else
        {
            $this->attackingForceId = RED_FORCE;
            $this->defendingForceId = BLUE_FORCE;
        }
    }

    function setEliminationTrayXY($hexagonNumber)
    {
        $hexagon = new Hexagon($hexagonNumber);
        $this->eliminationTrayHexagonX = $hexagon->getX();
        $this->eliminationTrayHexagonY = $hexagon->getY();
    }

    function setStatus($id, $status)
    {
        return $this->units[$id]->setStatus($status);
      /*  $success = false;
        switch ($status)
        {
            case STATUS_EXCHANGED:
                if ($this->units[$id]->forceId == $this->attackingForceId && ($this->units[$id]->status == STATUS_CAN_ATTACK_LOSE || $this->units[$id]->status == STATUS_CAN_EXCHANGE)) {
                    if($this->units[$id]->isReduced){
                        $this->units[$id]->status = STATUS_ELIMINATING;
                        $amtLost = $this->units[$id]->minStrength;
                    }else{
                        $this->units[$id]->strength = $this->units[$id]->minStrength;
                        $this->units[$id]->isReduced = true;
                        $amtLost = $this->units[$id]->maxStrength - $this->units[$id]->minStrength;
                    }
                    echo "Amount Lost $amtLost";
                    $this->exchangeAmount -= $amtLost;
                    if($this->exchangeAmount <= 0){
                        $success = true;
                    }
                }
                break;

            case STATUS_REINFORCING:
                if ($this->units[$id]->forceId == $this->attackingForceId && $this->units[$id]->status == STATUS_CAN_REINFORCE) {
                    $this->units[$id]->status = $status;
                    $success = true;
                }
                break;

            case STATUS_CAN_REINFORCE:
                if ($this->units[$id]->forceId == $this->attackingForceId && $this->units[$id]->status == STATUS_REINFORCING) {
                    $this->units[$id]->status = $status;
                    $success = true;
                }
                break;

            case STATUS_READY:
            case STATUS_DEFENDING:
            case STATUS_ATTACKING:
                $this->units[$id]->status = $status;
                break;

            case STATUS_MOVING:
                if ($this->units[$id]->forceId == $this->attackingForceId
                    && ($this->units[$id]->status == STATUS_READY || $this->units[$id]->status == STATUS_REINFORCING)
                ) {
                    $this->units[$id]->status = $status;
                    $this->units[$id]->moveCount = 0;
                    $this->units[$id]->moveAmountUsed = 0;
                    $success = true;
                }
                break;

            case STATUS_STOPPED:
                if ($this->units[$id]->status == STATUS_MOVING) {
                    $this->units[$id]->status = $status;
                    $this->units[$id]->moveAmountUsed = $this->units[$id]->maxMove;

                    $success = true;
                }
                if ($this->units[$id]->status == STATUS_ADVANCING) {
                    $this->units[$id]->status = STATUS_ADVANCED;
//                    $this->units[$id]->moveAmountUsed = $$this->units[$id]->maxMove;
                    $success = true;
                }
                if ($this->units[$id]->status == STATUS_RETREATING) {
                    $this->units[$id]->status = STATUS_RETREATED;
//                    $this->units[$id]->moveAmountUsed = $$this->units[$id]->maxMove;
                    $success = true;
                }
                break;

            case STATUS_EXITED:
                if ($this->units[$id]->status == STATUS_MOVING) {
                    $this->units[$id]->status = status;
                    $success = true;
                }
                break;

            case STATUS_RETREATING:
                if ($this->units[$id]->status == STATUS_CAN_RETREAT) {
                    $this->units[$id]->status = $status;
                    $this->units[$id]->moveCount = 0;
                    $this->units[$id]->moveAmountUsed = 0;
                    $success = true;
                }
                break;

            case STATUS_ADVANCING:
                if ($this->units[$id]->status == STATUS_CAN_ADVANCE) {
                    $this->units[$id]->status = $status;
                    $this->units[$id]->moveCount = 0;
                    $this->units[$id]->moveAmountUsed = 0;
                    $success = true;
                }
                break;

            case STATUS_ADVANCED:
                if ($this->units[$id]->status == STATUS_ADVANCING) {
                    $this->units[$id]->status = $status;
                    $success = true;
                }
                break;

            default:
                break;
        }
        return $success;*/
    }

    function setupAttacker($id, $range)
    {
        if($range > 1){
            $this->units[$id]->status = STATUS_BOMBARDING;

        }else{
        $this->units[$id]->status = STATUS_ATTACKING;
        }
    }

    function setupDefender($id)
    {
        $this->units[$id]->status = STATUS_DEFENDING;
        ;
    }

    function getEliminated($id, $hexagon)
    {
            if ($this->units[$id]->status == STATUS_CAN_REPLACE) {
                $this->units[$id]->status = STATUS_REPLACED;
                $this->units[$id]->isReduced = true;
                $this->units[$id]->updateMoveStatus($hexagon,0);
                return $id;
            }

        return false;
    }

    function storeCombatIndex($combatNumber, $combatIndex)
    {
        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->combatNumber == $combatNumber) {
                $this->units[$id]->combatIndex = $combatIndex;
            }
        }
    }

    function storeCombatOdds($combatNumber, $combatOdds)
    {
        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->combatNumber == $combatNumber) {
                $this->units[$id]->combatOdds = $combatOdds;
            }
        }
    }

    function undoAttackerSetup($id)
    {
        $this->units[$id]->status = STATUS_READY;
        $this->units[$id]->combatNumber = 0;
        $this->units[$id]->combatIndex = 0;
    }


    function unitCanAdvance($id)
    {
        $Advance = false;
        if ($this->units[$id]->status == STATUS_CAN_ADVANCE) {
            $Advance = true;
        }
        return $Advance;
    }

    function unitCanMove($id)
    {
        $canMove = false;
        if ($this->units[$id]->status == STATUS_READY && $this->units[$id]->forceId == $this->attackingForceId ) {
            $canMove = true;
        }
        return $canMove;
    }

    function unitCanReinforce($id)
    {
        $canReinforce = false;
        if ($this->units[$id]->status == STATUS_CAN_REINFORCE
            && $this->units[$id]->forceId == $this->attackingForceId
        ) {
            $canReinforce = true;
        }
        return $canReinforce;
    }

    function unitCanDeploy($id)
    {
        $canDeploy = false;
        if ($this->units[$id]->status == STATUS_CAN_DEPLOY
            && $this->units[$id]->forceId == $this->attackingForceId
        ) {
            $canDeploy = true;
        }
        return $canDeploy;
    }

    function unitCanRetreat($id)
    {
        $canRetreat = false;
        if ($this->units[$id]->status == STATUS_CAN_RETREAT) {
            $canRetreat = true;
        }
        return $canRetreat;
    }

    function unitGetAttackerList($id)
    {
        $attackerList = "";

        for ($i = 0; $i < count($this->units); $i++)
        {
            if ($this->units[$i]->forceId == $this->attackingForceId
                && $this->units[$i]->combatNumber == $this->units[$id]->combatNumber
            ) {
                $attackerList += $this->units[$i]->name + " ";
            }
        }
        return $attackerList;
    }

    function unitHasAttackers($id)
    {
        $hasAttackers = false;

        for ($i = 0; $i < count($this->units); $i++)
        {
            if ($this->units[$i]->forceId == $this->attackingForceId
                && $this->units[$i]->combatNumber == $this->units[$id]->combatNumber
            ) {
                $hasAttackers = true;
                break;
            }
        }
        return $hasAttackers;
    }

    function unitHasMetRetreatCountRequired($id)
    {
        $unitHasMetRetreatCountRequired = false;

        if ($this->units[$id]->moveCount >= $this->units[$id]->retreatCountRequired) {
            $unitHasMetRetreatCountRequired = true;
        }

        return $unitHasMetRetreatCountRequired;
    }

    function unitHasMoveAmountAvailable($id, $moveAmount)
    {
        if ($this->units[$id]->moveAmountUsed + $moveAmount <= $this->units[$id]->maxMove) {
            $canMove = true;
        }
        else
        {
            $canMove = false;
        }
        return $canMove;
    }
     function unitMoveAmountAvailable($id)
     {
         return $this->units[$id]->maxMove - $this->units[$id]->moveAmountUsed;
     }

    function unitHasNotMoved($id)
    {
        if ($this->units[$id]->moveAmountUsed == 0) {
            $hasMoved = true;
        }
        else
        {
            $hasMoved = false;
        }
        return $hasMoved;
    }

    function unitHasUsedMoveAmount($id)
    {
        // moveRules amount used can be larger if can always moveRules at least one hexagon
        if ($this->units[$id]->moveAmountUsed >= $this->units[$id]->maxMove) {
            $maxMove = true;
        }
        else
        {
            $maxMove = false;
        }
        return $maxMove;
    }

    function unitIsAttacking($id)
    {
        $isAttacking = false;

        if ($this->units[$id]->status == STATUS_ATTACKING) {
            $isAttacking = true;
        }
        return $isAttacking;
    }

    function unitIsDefending($id)
    {
        $isDefending = false;

        if ($this->units[$id]->status == STATUS_DEFENDING) {
            $isDefending = true;
        }

        return $isDefending;
    }

    function unitIsEliminated($id)
    {
        $isEliminated = false;

        if ($this->units[$id]->status == STATUS_ELIMINATED) {
            $isEliminated = true;
        }
        return $isEliminated;
    }

    function unitIsEnemy($id)
    {
        $isEnemy = false;

        if ($this->units[$id]->forceId == $this->defendingForceId) {
            $isEnemy = true;
        }
        return $isEnemy;
    }

    function getUnitRange($id)
    {
        return $this->units[$id]->range;
    }

    function unitIsFriendly($id)
    {
        $isFriendly = false;

        if ($this->units[$id]->forceId == $this->attackingForceId) {
            $isFriendly = true;
        }
        return $isFriendly;
    }

    function unitIsInCombat($id)
    {
        $inCombat = false;
        if ($this->units[$id]->combatNumber > 0) {
            $inCombat = true;
        }
        return $inCombat;
    }

    function unitIsMoving($id)
    {
        $isMoving = false;
        if ($this->units[$id]->status == STATUS_MOVING) {
            $isMoving = true;
        }
        return $isMoving;
    }

    function unitIsReinforcing($id)
    {
        if ($this->units[$id]->status == STATUS_REINFORCING) {
            $isReinforcing = true;
        }
        else
        {
            $isReinforcing = false;
        }
        return $isReinforcing;
    }

    function unitIsDeploying($id)
    {
        if ($this->units[$id]->status == STATUS_DEPLOYING) {
            $isDeploying = true;
        }
        else
        {
            $isDeploying = false;
        }
        return $isDeploying;
    }
    function unitIsRetreating($id)
    {
        $isRetreating = false;
        if ($this->units[$id]->status == STATUS_RETREATING) {
            $isRetreating = true;
        }
        return $isRetreating;
    }

    function unitIsZOC($id, $range = 1)
    {
        $isZOC = false;

        if ($this->ZOCrule == true) {
            $los = new Los();
            $los->setOrigin($this->units[$id]->hexagon);

            for ($i = 0; $i < count($this->units); $i++)
            {
                $los->setEndPoint($this->units[$i]->hexagon);
                if ($los->getRange() == $range
                    && $this->units[$i]->forceId != $this->units[$id]->forceId
                    && $this->units[$i]->status != STATUS_CAN_REINFORCE
                    && $this->units[$i]->status != STATUS_ELIMINATED
                ) {
                    $isZOC = true;
                    break;
                }
            }
        }
        return $isZOC;
    }
    function hexIsZOC($hexagon, $range = 1)
    {

        $isZOC = false;

        if ($this->ZOCrule == true) {
            $los = new Los();
            $los->setOrigin($hexagon);

            for ($i = 0; $i < count($this->units); $i++)
            {
                $los->setEndPoint($this->units[$i]->hexagon);
                if ($los->getRange() == $range
                    && $this->units[$i]->forceId != $this->attackingForceId
                    && $this->units[$i]->status != STATUS_CAN_REINFORCE
                    && $this->units[$i]->status != STATUS_ELIMINATED
                ) {
                    $isZOC = true;
                    break;
                }
            }
        }

        return $isZOC;
    }

    function unitWillUseMaxMove($id, $moveAmount)
    {
        if ($this->units[$id]->moveAmountUsed + $moveAmount >= $this->units[$id]->maxMove) {
            $willStop = true;
        }
        else
        {
            $willStop = false;
        }
        return $willStop;
    }

    function replace($id){
        if($this->units[$id]->isReduced && $this->units[$id]->status != STATUS_REPLACED){
            $this->units[$id]->strength = $this->units[$id]->maxStrength;
            $this->units[$id]->isReduced = false;
            $this->units[$id]->status = STATUS_REPLACED;
            return  true;
        }
        return false;
    }
    function exchangingAreAdvancing()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_CAN_EXCHANGE) {
                $this->units[$id]->status = STATUS_CAN_ADVANCE;
                $areAdvancing = true;
            }
            if ($this->units[$id]->status == STATUS_CAN_ATTACK_LOSE) {
                $this->units[$id]->status = STATUS_ATTACKED;
                $areAdvancing = true;
            }
        }
        return $areAdvancing;
    }
    function unitsAreExchanging()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_CAN_EXCHANGE) {
                return true;
            }
        }
        return $areAdvancing;
    }

    function unitsAreAttackerLosing()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_CAN_ATTACK_LOSE) {
                return true;
            }
        }
        return $areAdvancing;
    }

   function unitsAreAdvancing()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_CAN_ADVANCE
                || $this->units[$id]->status == STATUS_ADVANCING
            ) {
                $areAdvancing = true;
                break;
            }
        }
        return $areAdvancing;
    }


    function unitsAreBeingEliminated()
    {
        $areBeingEliminated = false;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_ELIMINATING) {
                $areBeingEliminated = true;
                break;
            }
        }
        return $areBeingEliminated;
    }

    function unitsAreDefending()
    {
        $areDefending = false;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_DEFENDING) {
                $areDefending = true;
                break;
            }
        }
        return $areDefending;
    }

    function unitsAreRetreating()
    {
        $areRetreating = false;
        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_CAN_RETREAT
                || $this->units[$id]->status == STATUS_RETREATING
            ) {
                $areRetreating = true;
                break;
            }
        }
        return $areRetreating;
    }

    function updateMoveStatus($id, $hexagon, $moveAmount)
    {
        $this->units[$id]->updateMoveStatus($hexagon,$moveAmount);
        return;
        $this->units[$id]->hexagon = $hexagon;
        $this->units[$id]->moveCount++;
        $this->units[$id]->moveAmountUsed = $this->units[$id]->moveAmountUsed + $moveAmount;
    }

}