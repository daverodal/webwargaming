<?php
// force.js

// Copyright (c) 20092011 Mark Butler
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
class RetreatStep
{
    public $stepNumber;
    /* @var Hexagon */
    public $hexagon;

    /* @var Hexagon $RetreatHexagon */
    function set($RetreatStepStepNumber, $RetreatHexagon)
    {
        $this->stepNumber = $RetreatStepStepNumber;
        $this->hexagon = new Hexagon($RetreatHexagon->getNumber());
    }

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "hexagon") {
                    $this->hexagon = new Hexagon($v->name);
                    continue;
                }
                $this->$k = $v;
            }
        }
    }
}
class BaseUnit{

    public $id;
    public $forceId;
    public $name;
    /* @var Hexagon */
    public $hexagon;
    public $image;
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
    public $adjustments;
    public $unitDesig;
    public $moveAmountUnused;

    /* damage is related to exchangeAmount, damage is always strength points, for victory points,
     * exchangeAmount may be in steps or strength points
     */
    public $damage;
    public $exchangeAmount;


    public function jsonSerialize()
    {
        if (is_object($this->hexagon)) {
            if ($this->hexagon->name) {
                $this->hexagon = $this->hexagon->getName();

            } else {
                $this->hexagon = $this->hexagon->parent;
            }
        }
        return $this;
    }

    function unitHasMoveAmountAvailable($moveAmount)
    {
        if ($this->moveAmountUsed + $moveAmount <= $this->maxMove) {
            $canMove = true;
        } else {
            $canMove = false;
        }
        return $canMove;
    }

    function addAdjustment($name, $adjustment)
    {
        $this->adjustments->$name = $adjustment;
    }

    function removeAdjustment($name)
    {
        unset($this->adjustments->$name);
    }

    function unitHasNotMoved()
    {
        if ($this->moveAmountUsed == 0) {
            $hasMoved = true;
        } else {
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
        } else {
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
        $prevStatus = $this->status;
        switch ($status) {
            case STATUS_EXCHANGED:
                if (($this->status == STATUS_CAN_ATTACK_LOSE || $this->status == STATUS_CAN_EXCHANGE)) {
                    $this->damageUnit();
                    $success = true;
                }
                break;

            case STATUS_CAN_REPLACE:
                if ($this->status == STATUS_ELIMINATED) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_REPLACED:
                if ($this->status == STATUS_CAN_REPLACE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_ELIMINATED:
                if ($this->status == STATUS_CAN_REPLACE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_REINFORCING:
                if ($this->status == STATUS_CAN_REINFORCE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_DEPLOYING:
                if ($this->status == STATUS_CAN_DEPLOY) {
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
                $id = $this->id;
                if ($status === STATUS_ATTACKING) {
                    if ($battle->force->combatRequired && isset($battle->force->requiredAttacks->$id)) {
                        $battle->force->requiredAttacks->$id = false;
                    }
                }
                if ($status === STATUS_DEFENDING) {
                    if ($battle->force->combatRequired && isset($battle->force->requiredDefenses->$id)) {
                        $battle->force->requiredDefenses->$id = false;
                    }
                }
                if ($status === STATUS_READY) {

                    if ($battle->force->combatRequired && isset($battle->force->requiredAttacks->$id)) {
                        $battle->force->requiredAttacks->$id = true;
                    }
                    if ($battle->force->combatRequired && isset($battle->force->requiredDefenses->$id)) {
                        $battle->force->requiredDefenses->$id = true;
                    }
                }
                break;

            case STATUS_MOVING:
                if (($this->status == STATUS_READY || $this->status == STATUS_REINFORCING)
                ) {
                    $this->status = $status;
                    $this->moveCount = 0;
                    $this->moveAmountUsed = 0;
                    $this->moveAmountUnused = $this->maxMove;
                    $success = true;
                }
                break;

            case STATUS_STOPPED:
                if ($this->status == STATUS_MOVING || $this->status == STATUS_DEPLOYING) {
                    $this->status = $status;
                    $this->moveAmountUnused = $this->maxMove - $this->moveAmountUsed;
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
                    $this->status = $status;
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
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $attackingForceId = $battle->force->attackingForceId;
//        $mapData = MapData::getInstance();
        /* @var MapHex $mapHex */
        $fromHex = $this->hexagon->getName();
        $toHex = $hexagon->getName();
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->unsetUnit($this->forceId, $this->id);
        }

        $this->hexagon = $hexagon;
        $this->dirty = true;
        $mapData->breadcrumbMove($this->id, $attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $fromHex, $toHex);
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this->id);
            $mapHexName = $mapHex->name;
            if (isset($mapData->specialHexes->$mapHexName)) {

                if ($mapData->specialHexes->$mapHexName >= 0 && $mapData->specialHexes->$mapHexName != $this->forceId) {
                    $victory = $battle->victory;
                    $mapData->specialHexesChanges->$mapHexName = true;
                    $victory->specialHexChange($mapHexName, $this->forceId);
                    $mapData->alterSpecialHex($mapHexName, $this->forceId);
                }
            }
        }
        $this->moveCount++;
        $this->moveAmountUsed = $this->moveAmountUsed + $moveAmount;
    }

    function isDeploy(){
        return $this->hexagon->parent == "deployBox";
    }

    function getEliminated( $hexagon)
    {
        if ($this->status == STATUS_CAN_REPLACE) {
            $hexagon = new Hexagon($hexagon);
            $this->status = STATUS_REPLACED;
            $this->updateMoveStatus($hexagon, 0);
            return $this->id;
        }
        return false;
    }
}

class unit extends BaseUnit implements JsonSerializable
{

//    public $strength;
    public $maxStrength;
    public $minStrength;
    public $isReduced;



    public $isDisrupted = false;
    public $supplied = true;




    public function getUnmodifiedStrength(){
        if ($this->isReduced) {
            $strength = $this->minStrength;
        } else {
            $strength = $this->maxStrength;
        }
        return $strength;
    }

    public function getUnmodifiedDefStrength(){
        return  $this->getUnmodifiedStrength();
    }

    public function __get($name)
    {
        if ($name !== "strength" && $name !== "defStrength" && $name !== "attStrength") {
            return false;
        }
        if ($this->isReduced) {
            $strength = $this->minStrength;
        } else {
            $strength = $this->maxStrength;
        }
        foreach ($this->adjustments as $adjustment) {
            switch ($adjustment) {
                case 'floorHalf':
                    $strength = floor($strength / 2);
                    break;
                case 'half':
                    $strength = $strength / 2;
                    break;
                case 'double':
                    $strength = $strength * 2;
                    break;
            }
        }
        return $strength;
    }



    function set($unitId, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $forceMarch, $class, $unitDesig)
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
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this->id);
        }
        $this->image = $unitImage;
//        $this->strength = $isReduced ? $unitMinStrength : $unitMaxStrength;
        $this->maxMove = $unitMaxMove;
        $this->moveAmountUnused = $unitMaxMove;
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
        $this->moveCount = 0;
        $this->retreatCountRequired = 0;
        $this->combatResults = NR;
        $this->range = $range;
        $this->nationality = $nationality;
        $this->forceMarch = $forceMarch;
        $this->unitDesig = $unitDesig;
        $this->facing = 2;
    }

    function damageUnit($kill = false)
    {
        $battle = Battle::getBattle();

        if ($this->isReduced || $kill) {
            $this->status = STATUS_ELIMINATING;
            $this->exchangeAmount = $this->getUnmodifiedStrength();
            $this->defExchangeAmount = $this->getUnmodifiedDefStrength();
            return true;
        } else {
            $this->damage = $this->maxStrength - $this->minStrength;
            $battle->victory->reduceUnit($this);
            $this->isReduced = true;
            $this->exchangeAmount = $this->damage;
            $this->defExchangeAmount = $this->damage;
        }
        return false;
    }

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "hexagon") {
                    $this->hexagon = new Hexagon($v);
//                    $this->hexagon->parent = $data->parent;
                    continue;
                }
                $this->$k = $v;
            }
            $this->dirty = false;
        } else {
            $this->adjustments = new stdClass();
        }
    }


    public function fetchData(){
        $mapUnit = new StdClass();
        $mapUnit->isReduced = $this->isReduced;
        $mapUnit->parent = $this->hexagon->parent;
        $mapUnit->moveAmountUsed = $this->moveAmountUsed;
        $mapUnit->maxMove = $this->maxMove;
        $mapUnit->strength = $this->strength;
        $mapUnit->supplied = $this->supplied;
        $mapUnit->reinforceZone = $this->reinforceZone;
        $mapUnit->forceId = $this->forceId;
        return $mapUnit;
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
    public $exchangeAmount;
    public $requiredAttacks;
    public $requiredDefenses;
    public $combatRequired;
    public $exchangesKill = false;

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "units") {
                    $this->units = array();
                    foreach ($v as $unit) {
                        $this->units[] = UnitFactory::build($unit);
                    }
                    continue;
                }
                if ($k == "retreatHexagonList") {
                    $this->retreatHexagonList = array();
                    foreach ($v as $retreatStep) {
                        $this->retreatHexagonList[] = new RetreatStep($retreatStep);
                    }
                    continue;
                }
                $this->$k = $v;
            }
        } else {

            $this->reinforceTurns = new stdClass();
            $this->units = array();
            $this->victor = RED_FORCE;
            $this->ZOCrule = true;
            $this->deleteCount = 0;

            $this->retreatHexagonList = array();
            $this->requiredAttacks = new stdClass();
            $this->requiredDefenses = new stdClass();
            $this->combatRequired = false;
        }
    }


    function requiredCombats()
    {
        foreach ($this->requiredAttacks as $attack) {
            if ($attack === true) {
                return true;
            }
        }
        foreach ($this->requiredDefenses as $defense) {
            if ($defense === true) {
                return true;
            }
        }
        return false;
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

    function addUnit($unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range = 1, $nationality = "neutral", $forceMarch = true, $class = false, $unitDesig = false)
    {
        if ($unitStatus == STATUS_CAN_REINFORCE) {
            if (!$this->reinforceTurns->$unitReinforceTurn) {
                $this->reinforceTurns->$unitReinforceTurn = new stdClass();
            }
            $this->reinforceTurns->$unitReinforceTurn->$unitForceId++;
        }
        $id = count($this->units);
        $unit = UnitFactory::build();
        $unit->set($id, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig);

        array_push($this->units, $unit);
        return $id;
    }

    function injectUnit($unit)
    {
        $unitStatus = $unit->status;
        $unitReinforceTurn = $unit->reinforceTurn;
        $unitForceId = $unit->forceId;
        if ($unitStatus == STATUS_CAN_REINFORCE) {
            if (!$this->reinforceTurns->$unitReinforceTurn) {
                $this->reinforceTurns->$unitReinforceTurn = new stdClass();
            }
            $this->reinforceTurns->$unitReinforceTurn->$unitForceId++;
        }
        $id = count($this->units);
        $unit->id = $id;
        array_push($this->units, $unit);
        return $id;
    }

    function getFirstRetreatHex($id)
    {
        if (count($this->retreatHexagonList) == 0) {
            throw new Exception("Cannot get reatreat hex");
        }
        return $this->retreatHexagonList[0]->hexagon;
    }

    function getAllFirstRetreatHexes($id)
    {
        if (count($this->retreatHexagonList) == 0) {
            throw new Exception("Cannot get reatreat hex");
        }
        $hexes = array();
        foreach ($this->retreatHexagonList as $hexList) {
            if ($hexList->stepNumber == 0) {
                $hexes[] = $hexList->hexagon;
            }
        }
        return $hexes;
    }

    function advanceIsOnRetreatList($id, $hexagon)
    {
        $isOnList = false;
        for ($i = 0; $i < count($this->retreatHexagonList); $i++) {
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
        list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);
        $vacated = false;
        $exchangeMultiplier = 1;
        if($combatResults === EX02){
            $distance = 0;
            $combatResults = EX;
            $exchangeMultiplier = 2;
        }
        if($combatResults === EX03){
            $distance = 0;
            $combatResults = EX;
            $exchangeMultiplier = 3;
        }
        if ($combatResults === EX0) {
            $distance = 0;
            $combatResults = EX;
        }
        $defUnit = $this->units[$defenderId];
        switch ($combatResults) {
            case EX2:
                $distance = 2;
            case EX:
                $eliminated = $defUnit->damageUnit($this->exchangesKill);
                if (!$eliminated){
                    if($distance) {
                        $defUnit->status = STATUS_CAN_RETREAT;
                    }else{
                        $this->clearAdvancing();
                        $defUnit->status = STATUS_EXCHANGED;
                    }
                    $defUnit->retreatCountRequired = $distance;
                }else{
                    $defUnit->moveCount = 0;
                    $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));
                }
                $this->exchangeAmount += $defUnit->defExchangeAmount * $exchangeMultiplier;
                $defUnit->moveCount = 0;
                break;

            case DD:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                $defUnit->isDisrupted = $battle->gameRules->phase;
                $battle->victory->disruptUnit($defUnit);
                break;

            case AL:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;

            case AE:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;

            case AR:
                $defUnit->status = STATUS_DEFENDED;
                $defUnit->retreatCountRequired = 0;
                break;

            case DE:
                $defUnit->status = STATUS_ELIMINATING;
                $defUnit->retreatCountRequired = $distance;
                $defUnit->moveCount = 0;
                $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));
                break;

            case DRL2:
                $distance = 2;
            case DRL:
                $eliminated = $defUnit->damageUnit();
                if ($eliminated) {
                    $defUnit->moveCount = 0;
                    $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));

                } else {
                    $defUnit->status = STATUS_CAN_RETREAT;
                }
                $defUnit->retreatCountRequired = $distance;
                break;
            case DR2:
                $distance = 2;
            case DR:
                $defUnit->status = STATUS_CAN_RETREAT;
                $defUnit->retreatCountRequired = $distance;
                break;

            case NE:
                $defUnit->status = STATUS_NO_RESULT;
                $defUnit->retreatCountRequired = 0;
                break;
            case DL:
                $eliminated = $defUnit->damageUnit();
                if ($eliminated) {
                    $vacated = true;
                    $defUnit->retreatCountRequired = 0;
                    $defUnit->moveCount = 0;
                    $this->addToRetreatHexagonList($defenderId, $this->getUnitHexagon($defenderId));

                } else {
                    $defUnit->status = STATUS_DEFENDED;
                    $defUnit->retreatCountRequired = 0;
                }
                break;
            default:
                break;
        }
        $defUnit->combatResults = $combatResults;
        $defUnit->dieRoll = $dieRoll;
        $defUnit->combatNumber = 0;
        $defUnit->moveCount = 0;


        foreach ($attackers as $attacker => $val) {
            if ($this->units[$attacker]->status == STATUS_BOMBARDING) {
                $this->units[$attacker]->status = STATUS_ATTACKED;
                $this->units[$attacker]->retreatCountRequired = 0;

                $this->units[$attacker]->combatResults = $combatResults;
                $this->units[$attacker]->dieRoll = $dieRoll;
                $this->units[$attacker]->combatNumber = 0;
                $this->units[$attacker]->moveCount = 0;
            }

            if ($this->units[$attacker]->status == STATUS_ATTACKING) {
                switch ($combatResults) {
                    case EX2:
                    case EX:
                        $this->units[$attacker]->status = STATUS_CAN_EXCHANGE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case AE:
                        $this->units[$attacker]->status = STATUS_ELIMINATING;
                        $defUnit->retreatCountRequired = 0;
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

                    case DL:
                        /* for multi defender combats */
                        if ($vacated || $this->units[$attacker]->status == STATUS_CAN_ADVANCE) {
                            $this->units[$attacker]->status = STATUS_CAN_ADVANCE;
                        } else {
                            $this->units[$attacker]->status = STATUS_NO_RESULT;
                        }
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
        $gameRules = $battle->gameRules;
        $mapData = $battle->mapData;
        $mapData->breadcrumbCombat($defenderId,$this->attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $combatResults, $dieRoll, $this->getUnitHexagon($defenderId)->name);

        $battle->victory->postCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $this->removeEliminatingUnits();
    }

    function clearRetreatHexagonList()
    {

        $this->retreatHexagonList = array();
    }



    function eliminateUnit($id)
    {
        /* @var unit $unit */
        $unit = $this->units[$id];
        $battle = Battle::getBattle();
        $unit->damage = $unit->getUnmodifiedStrength();
        $battle->victory->reduceUnit($unit);
        $forceId = $unit->forceId;
        $this->deleteCount++;
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $mapHex = $mapData->getHex($unit->hexagon->getName());

        if ($mapHex) {
            $mapHex->unsetUnit($forceId, $id);
        }
        $unit->status = STATUS_ELIMINATED;
        $col = 0;
        $unit->hexagon = new Hexagon($col + $id % 10);

        $unit->hexagon->parent = "deadpile";
        $battle->victory->postEliminated($unit);
    }

    function getAttackerStrength($attackers)
    {
        $attackerStrength = 0;

        foreach ($attackers as $id => $v) {
            $attackerStrength += $this->units[$id]->strength;
        }

        return $attackerStrength;
    }

    function getCombatInfo($id)
    {
        return $this->units[$id]->combatOdds;
    }

    function getDefenderStrength($defenderId)
    {
        $defenderStrength = 0;
        $defenderStrength += $this->units[$defenderId]->defStrength;
        return $defenderStrength;
    }

    function getUnitBeingEliminatedId()
    {

        for ($i = 0; $i < count($this->units); $i++) {
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
            } else {
                $unitInfo += "<br />&nbsp;";
            }

            if ($this->units[$id]->status == STATUS_CAN_REINFORCE) {
                $unitInfo += "<br />can reinforce on turn " + $this->units[$id]->reinforceTurn;
            } else {
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

    function getUnit($id)
    {
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


    function mapHexIsZoc(MapHex $mapHex, $defendingForceId = false)
    {
        if ($defendingForceId === false) {
            $defendingForceId = $this->defendingForceId;
        }
        return $mapHex->isZoc($defendingForceId);
        $neighbors = $mapHex->neighbors;

        if ($neighbors) {
            $battle = Battle::getBattle();
            $mapData = $battle->mapData;
            foreach ($neighbors as $neighbor) {
                if ($this->mapHexIsOccupiedEnemy($mapData->getHex($neighbor))) {
                    return true;
                }
            }
        }
        return false;
    }

    function hexagonIsOccupied($hexagon, $stacking = 1, $unit = false)
    {
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $mapHex = $mapData->getHex($hexagon->getName());
        if ($mapHex->isOccupied($this->defendingForceId)) {
            return true;
        }
        return $mapHex->isOccupied($this->attackingForceId, $stacking, $unit);

    }





    function isForceEliminated()
    {
        $isForceEliminated = false;
        $isDefendingForceEliminated = true;
        $isAttackingForceEliminated = true;

        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->forceId == $this->defendingForceId && $this->units[$id]->status != STATUS_ELIMINATED) {
                // found one alive, so make it false
                $isDefendingForceEliminated = false;
            }
        }

        for ($id = 0; $id < count($this->units); $id++) {
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

        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_DEFENDING
                && $this->unitHasAttackers($id)
            ) {
                $moreCombatToResolve = true;
                break;
            }
        }
        return $moreCombatToResolve;
    }

    function clearRequiredCombats()
    {
        $this->requiredAttacks = new stdClass();
        $this->requiredDefenses = new stdClass();
    }

    function recoverUnits($phase, $moveRules, $mode)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $victory->preRecoverUnits();
        for ($id = 0; $id < count($this->units); $id++) {
            $victory->preRecoverUnit($this->units[$id]);

            switch ($this->units[$id]->status) {
                case STATUS_CAN_DEPLOY:
                    if($mode == DEPLOY_MODE){
                        continue;
                    }
                    if ($this->units[$id]->isDeploy()) {
                        continue;
                    }

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

                    if ($phase == BLUE_MECH_PHASE && $this->units[$id]->forceId == BLUE_FORCE && $this->units[$id]->class != "mech") {
                        $status = STATUS_STOPPED;
                    }
                    if ($phase == RED_MECH_PHASE && $this->units[$id]->forceId == RED_FORCE && $this->units[$id]->class != "mech") {
                        $status = STATUS_STOPPED;
                    }
                    if ($phase == BLUE_REPLACEMENT_PHASE || $phase == RED_REPLACEMENT_PHASE || $phase == TEAL_REPLACEMENT_PHASE || $phase == PURPLE_REPLACEMENT_PHASE) {
                        $status = STATUS_STOPPED;
                        /* TODO Hack Hack Hack better answer is not isReduced, but canReduce */
                        if ($this->units[$id]->forceId == $this->attackingForceId &&
                            $this->units[$id]->isReduced && $this->units[$id]->class !== "gorilla"
                        ) {
                            $status = STATUS_CAN_UPGRADE;
                        }
                    }
                    if ($phase == BLUE_COMBAT_PHASE || $phase == RED_COMBAT_PHASE || $phase == TEAL_COMBAT_PHASE || $phase == PURPLE_COMBAT_PHASE) {
                        if ($mode == COMBAT_SETUP_MODE) {
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            /* unitIsZoc has Side Effect */
                            $isZoc = $this->unitIsZoc($id);
                            if ($isZoc) {
                                $this->markRequired($id);
                            }
                            $isAdjacent = $this->unitIsAdjacent($id);
                            if ($this->units[$id]->forceId == $this->attackingForceId && ($isZoc || $isAdjacent || $this->unitIsInRange($id))) {
                                $status = STATUS_READY;
                            }
                        }
                        if ($mode == COMBAT_RESOLUTION_MODE) {
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if ($this->units[$id]->status == STATUS_ATTACKING ||
                                $this->units[$id]->status == STATUS_DEFENDING
                            ) {
                                $status = $this->units[$id]->status;
                            }

                        }
                    }

                if ($mode == MOVING_MODE && $moveRules->stickyZOC) {
                        if ($this->units[$id]->forceId == $this->attackingForceId &&
                            $this->unitIsZOC($id)
                        ) {
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
            if($phase === BLUE_MOVE_PHASE || $phase === RED_MOVE_PHASE || $phase == TEAL_MOVE_PHASE || $phase == PURPLE_MOVE_PHASE){
                $this->units[$id]->moveAmountUnused = $this->units[$id]->maxMove;
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
        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_ELIMINATING) {
                $this->eliminateUnit($id);
            }
        }
    }

    function resetRemainingNonAdvancingUnits()
    {
        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_CAN_ADVANCE) {
                $this->units[$id]->status = STATUS_ATTACKED;
            }
        }
    }

    function resetRemainingAdvancingUnits()
    {
        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_ADVANCING || $this->units[$id]->status == STATUS_CAN_ADVANCE) {
                $this->units[$id]->status = STATUS_ATTACKED;
            }
        }
    }

    function setAttackingForceId($forceId, $defId = false)
    {
        $this->attackingForceId = $forceId;

        if ($forceId == BLUE_FORCE) {
            $this->defendingForceId = RED_FORCE;

        } else {
            $this->defendingForceId = BLUE_FORCE;
        }

        if($defId !== false){
            $this->defendingForceId = $defId;
        }
    }

    function setStatus($id, $status)
    {
        /* @var unit $unit */
        $unit = $this->units[$id];
        $ret = $unit->setStatus($status);
        if ($status === STATUS_EXCHANGED) {
            $this->exchangeAmount -= $unit->exchangeAmount;
        }
        return $ret;
    }

    function getExchangeAmount()
    {
        return $this->exchangeAmount;
    }

    function clearExchangeAmount()
    {
        $this->exchangeAmount = 0;
    }

    function setupAttacker($id, $range)
    {
        if ($range > 1) {
            $this->units[$id]->status = STATUS_BOMBARDING;

        } else {
            $this->units[$id]->status = STATUS_ATTACKING;
        }

        if ($this->combatRequired && isset($this->requiredAttacks->$id)) {
            $this->requiredAttacks->$id = false;
        }
    }

    function setupDefender($id)
    {
        $this->units[$id]->status = STATUS_DEFENDING;
        $battle = Battle::getBattle();

        if ($this->combatRequired && isset($this->requiredDefenses->$id)) {
            $this->requiredDefenses->$id = false;
        }
    }

    function storeCombatIndex($combatNumber, $combatIndex)
    {
        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->combatNumber == $combatNumber) {
                $this->units[$id]->combatIndex = $combatIndex;
            }
        }
    }

    function storeCombatOdds($combatNumber, $combatOdds)
    {
        for ($id = 0; $id < count($this->units); $id++) {
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
        if ($this->combatRequired && isset($this->requiredAttacks->$id)) {
            $this->requiredAttacks->$id = true;
        }
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
        if ($this->units[$id]->status == STATUS_READY && $this->units[$id]->forceId == $this->attackingForceId) {
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

        for ($i = 0; $i < count($this->units); $i++) {
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

        for ($i = 0; $i < count($this->units); $i++) {
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
        } else {
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
        } else {
            $hasMoved = false;
        }
        return $hasMoved;
    }

    function unitHasUsedMoveAmount($id)
    {
        // moveRules amount used can be larger if can always moveRules at least one hexagon
        if ($this->units[$id]->moveAmountUsed >= $this->units[$id]->maxMove) {
            $maxMove = true;
        } else {
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

    function enemy($forceId)
    {

        if ($forceId == $this->attackingForceId) {
            return $this->defendingForceId;
        }
        if ($forceId == $this->defendingForceId) {
            return $this->attackingForceId;
        }
        throw new Exception("Enemy Unknown $forceId");
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
        } else {
            $isReinforcing = false;
        }
        return $isReinforcing;
    }

    function unitIsDeploying($id)
    {
        if ($this->units[$id]->status == STATUS_DEPLOYING) {
            $isDeploying = true;
        } else {
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

    function unitIsZOC($id)
    {
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        /* @var unit $unit */
        $unit = $this->units[$id];

        $mapHex = $mapData->getHex($unit->hexagon->name);
        if ($this->mapHexIsZoc($mapHex, $unit->forceId == $this->attackingForceId ? $this->defendingForceId : $this->attackingForceId)) {
            return true;
        }
        return false;
    }

    function unitIsAdjacent($id)
    {
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        /* @var unit $unit */
        $unit = $this->units[$id];

        $mapHex = $mapData->getHex($unit->hexagon->name);

        if ($mapHex->isAdjacent($unit->forceId == $this->attackingForceId ? $this->defendingForceId : $this->attackingForceId)) {
            return true;
        }
        return false;
    }


    function markRequired($id)
    {
        if ($this->combatRequired) {
            if ($this->units[$id]->forceId == $this->attackingForceId) {
                $this->markRequiredAttack($id);
            } else {
                $this->markRequiredDefense($id);
            }
        }
    }

    function unitIsInRange($id)
    {
        $b = Battle::getBattle();
        $isInRange = false;
        $range = $this->units[$id]->range;
        if ($range <= 1) {
            return false;
        }
        if ($this->ZOCrule == true) {
            $los = new Los();
            $los->setOrigin($this->units[$id]->hexagon);

            for ($i = 0; $i < count($this->units); $i++) {
                /* hexagons without names are off map */
                if(!$this->units[$i]->hexagon->name){
                    continue;
                }
                $los->setEndPoint($this->units[$i]->hexagon);
                $losRange = $los->getRange();
                if ($losRange <= $range
                    && $this->units[$i]->forceId != $this->units[$id]->forceId
                    && $this->units[$i]->status != STATUS_CAN_REINFORCE
                    && $this->units[$i]->status != STATUS_ELIMINATED
                ) {
                    if($b->combatRules->checkBlocked($los, $id))
                    {
                        $isInRange = true;
                        break;
                    }
                }
            }
        }
        return $isInRange;
    }

    function markRequiredAttack($id)
    {
        $this->requiredAttacks->$id = true;
    }

    function markRequiredDefense($id)
    {
        $this->requiredDefenses->$id = true;
    }

    function unitWillUseMaxMove($id, $moveAmount)
    {
        if ($this->units[$id]->moveAmountUsed + $moveAmount >= $this->units[$id]->maxMove) {
            $willStop = true;
        } else {
            $willStop = false;
        }
        return $willStop;
    }

    function replace($id)
    {
        if ($this->units[$id]->isReduced && $this->units[$id]->status != STATUS_REPLACED) {
//            $this->units[$id]->strength = $this->units[$id]->maxStrength;
            $this->units[$id]->isReduced = false;
            $this->units[$id]->status = STATUS_REPLACED;
            return true;
        }
        return false;
    }

    function exchangingAreAdvancing()
    {
        $areAdvancing = false;
        /*
         * Todo should not assign to status, should set status
         */
        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_CAN_EXCHANGE) {
                if(count($this->retreatHexagonList)){
                    $this->units[$id]->status = STATUS_CAN_ADVANCE;
                    $areAdvancing = true;
                }else{
                    $this->units[$id]->status = STATUS_ATTACKED;
                }
            }
            if ($this->units[$id]->status == STATUS_CAN_ATTACK_LOSE) {
                $this->units[$id]->status = STATUS_ATTACKED;
            }
        }
        return $areAdvancing;
    }

    function unitsAreExchanging()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_CAN_EXCHANGE) {
                return true;
            }
        }
        return $areAdvancing;
    }

    function unitsAreAttackerLosing()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_CAN_ATTACK_LOSE) {
                return true;
            }
        }
        return $areAdvancing;
    }

    function clearAdvancing()
    {
        foreach($this->units as $unit){
            if($unit->status == STATUS_CAN_ADVANCE){
                $unit->setStatus(STATUS_ADVANCING);
            }
            if($unit->status == STATUS_ADVANCING){
                $unit->setStatus(STATUS_ADVANCED);
            }
        }
    }

    function unitsAreAdvancing()
    {
        $areAdvancing = false;

        for ($id = 0; $id < count($this->units); $id++) {
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

        for ($id = 0; $id < count($this->units); $id++) {
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

        for ($id = 0; $id < count($this->units); $id++) {
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
        for ($id = 0; $id < count($this->units); $id++) {
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
        $this->units[$id]->updateMoveStatus($hexagon, $moveAmount);
        return;
        $this->units[$id]->hexagon = $hexagon;
        $this->units[$id]->moveCount++;
        $this->units[$id]->moveAmountUsed = $this->units[$id]->moveAmountUsed + $moveAmount;
    }

}