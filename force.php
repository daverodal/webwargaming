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
    public $hexagon;

    function __construct($RetreatStepStepNumber, $RetreatHexagon)
    {
        $this->stepNumber = $RetreatStepStepNumber;
        $this->hexagon = new Hexagon($RetreatHexagon->getNumber());
    }
}

class unit
{

    public $id;
    public $forceId;
    public $name;
    public $hexagon;
    public $image;
    public $strength;
    public $maxMove;
    public $status;
    public $moveAmountUsed;
    public $reinforceZone;
    public $reinforceTurn;
    public $combatNumber;
    public $combatIndex;
    public $combatOdds;
    public $hasContact;
    public $moveCount;
    public $retreatCountRequired;
    public $combatResults;
    public $dieRoll;
    public $eliminationHexagonX;
    public $eliminationHexagonY;

    function __construct($unitId, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $unitMaxMove, $unitStatus, $unitReinforceZone, $unitReinforceTurn)
    {
        $this->id = $unitId;
        $this->name = $unitName;
        $this->forceId = $unitForceId;
        $this->hexagon = new Hexagon($unitHexagon);
        $this->image = $unitImage;
        $this->strength = $unitStrength;
        $this->maxMove = $unitMaxMove;
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
    }
}

class Force
{
    public $units;
    public $victor;
    public $ZOCrule;
    public $attackingForceId;
    public $defendingForceId;
    public $deleteCount;

    public $retreatHexagonList;
    public $eliminationTrayHexagonX;
    public $eliminationTrayHexagonY;

    function __construct()
    {


        $this->units = array();
        $this->victor = RED_FORCE;
        $this->ZOCrule = true;
        $this->deleteCount = 0;

        $this->retreatHexagonList = array();
        $this->eliminationTrayHexagonX = 9;
        $this->eliminationTrayHexagonY = 0;
    }


    function addToRetreatHexagonList($id, $retreatHexagon)
    {

        //alert("function .prototype. adding: " + id + " : " + retreatHexagon.getName());
        // note: addToRetreatHexagonList() is invoked before retreat move, so
        //  the moveCount is 0 for 1st step and 1 for 2nd step

        $retreatStep = new RetreatStep($this->units[$id]->moveCount, $retreatHexagon);

        $this->retreatHexagonList->push($retreatStep);
    }

    function addUnit($unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $unitMaxMove, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn)
    {

        $id = count($this->units);
        array_push($this->units,new unit($id, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $unitMaxMove, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn));
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

    function applyCRTresults($combatNumber, $combatResults, $dieRoll)
    {
        $this->clearRetreatHexagonList();

        for ($defender = 0; $defender < count($this->units); $defender++)
        {
            if ($this->units[$defender]->status == STATUS_DEFENDING && $this->units[$defender]->combatNumber == $combatNumber) {
                switch ($combatResults)
                {
                    case AR:
                        $this->units[$defender]->status = STATUS_DEFENDED;
                        $this->units[$defender]->retreatCountRequired = 0;
                        break;

                    case AE:
                        $this->units[$defender]->status = STATUS_DEFENDED;
                        $this->units[$defender]->retreatCountRequired = 0;
                        break;

                    case DE:
                        $this->units[$defender]->status = STATUS_ELIMINATING;
                        $this->units[$defender]->retreatCountRequired = 1;
                        $this->addToRetreatHexagonList(defender, $this->getUnitHexagon(defender));
                        break;

                    case DR:
                        $this->units[defender]->status = STATUS_CAN_RETREAT;
                        $this->units[defender]->retreatCountRequired = 1;
                        break;

                    default:
                        break;
                }
                $this->units[defender]->combatResults = combatResults;
                $this->units[defender]->dieRoll = dieRoll;
                $this->units[defender]->combatNumber = 0;
                $this->units[defender]->moveCount = 0;
            }
        }

        for ($attacker = 0; $attacker < count($this->units); $attacker++)
        {
            if ($this->units[$attacker]->status == STATUS_ATTACKING && $this->units[$attacker]->combatNumber == $combatNumber) {
                switch ($combatResults)
                {
                    case AR:
                        $this->units[$attacker]->status = STATUS_CAN_RETREAT;
                        $this->units[$attacker]->retreatCountRequired = 1;
                        break;

                    case AE:
                        $this->units[$attacker]->status = STATUS_ELIMINATING;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case DE:
                        $this->units[$attacker]->status = STATUS_CAN_ADVANCE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    case DR:
                        $this->units[$attacker]->status = STATUS_CAN_ADVANCE;
                        $this->units[$attacker]->retreatCountRequired = 0;
                        break;

                    default:
                        break;
                }
                $this->units[$attacker]->combatResults = combatResults;
                $this->units[$attacker]->dieRoll = dieRoll;
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
        $hexagon = new Hexagon($this->units[$id]->hexpartX, $this->units[$id]->hexpartY);
        $this->deleteCount++;
        //alert("elim " + id + " at " + $this->eliminationTrayHexagonX + ", " + $this->eliminationTrayHexagonY);
        $this->units[$id]->status = STATUS_ELIMINATED;
        $this->units[$id]->hexagon->setXY($this->eliminationTrayHexagonX + (2 * $this->deleteCount), $this->eliminationTrayHexagonY);
    }

    function getAttackerStrength($combatNumber)
    {
        $attackerStrength = 0;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_ATTACKING && $this->units[$id]->combatNumber == combatNumber) {
                $attackerStrength += $this->units[$id]->strength;
            }
        }

        return $attackerStrength;
    }

    function getAttackerHexagonList($combatNumber)
    {

        $hexagonList = array();

        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_ATTACKING && $this->units[$id]->combatNumber == $combatNumber) {

                $hexagonList->push($this->units[$id]->hexagon);
            }
        }
        return $hexagonList;
    }

    function getCombatHexagon($combatNumber)
    {

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

    function getDefenderStrength($combatNumber)
    {
        $defenderStrength = 0;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_DEFENDING && $this->units[$id]->combatNumber == $combatNumber) {
                $defenderStrength += $this->units[$id]->strength;
            }
        }

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
            $los->setOrigin(hexagon);

            for ($i = 0; $i < count($this->units); $i++)
            {
                $los->setEndPoint($this->units[$i]->hexagon);
                if ($los->getRange() == 1
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

    function hexagonIsOccupied($hexagon)
    {
        $isOccupied = false;

        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->hexagon->equals($hexagon)) {
                $isOccupied = true;
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

    function recoverUnits()
    {
        for ($id = 0; $id < count($this->units); $id++)
        {
            switch ($this->units[$id]->status)
            {
                case STATUS_STOPPED:
                case STATUS_DEFENDED:
                case STATUS_DEFENDING:
                case STATUS_ATTACKED:
                case STATUS_ATTACKING:
                case STATUS_RETREATED:
                case STATUS_ADVANCED:
                case STATUS_CAN_ADVANCE:

                    $this->units[$id]->status = STATUS_READY;
                    $this->units[$id]->moveAmountUsed = 0;
                    break;

                default:
                    break;
            }
            $this->units[$id]->combatIndex = 0;
            $this->units[$id]->combatNumber = 0;
            $this->units[$id]->combatResults = NR;
        }
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
        $success = false;
        switch ($status)
        {
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
                    $success = true;
                }
                if ($this->units[$id]->status == STATUS_ADVANCING) {
                    $this->units[$id]->status = STATUS_ADVANCED;
                    $success = true;
                }
                if ($this->units[$id]->status == STATUS_RETREATING) {
                    $this->units[$id]->status = STATUS_RETREATED;
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
        return $success;
    }

    function setupAttacker($id, $combatNumber)
    {
        $this->units[$id]->status = STATUS_ATTACKING;
        $this->units[$id]->combatNumber = $combatNumber;
    }

    function setupDefender($id, $combatNumber)
    {
        $this->units[$id]->status = STATUS_DEFENDING;
        ;
        $this->units[$id]->combatNumber = $combatNumber;
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

    function undoDefendersWithoutAttackers()
    {
        for ($id = 0; $id < count($this->units); $id++)
        {
            if ($this->units[$id]->status == STATUS_DEFENDING && $this->unitHasAttackers($id) == false) {
                $this->units[$id]->status = STATUS_READY;
            }
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
        if ($this->units[$id]->status == STATUS_READY
            && $this->units[$id]->forceId == $this->attackingForceId
        ) {
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

        if ($this->units[$id]->moveCount == $this->units[$id]->retreatCountRequired) {
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
        $isZOC = false;

        if ($this->ZOCrule == true) {
            $los = new Los();
            $los->setOrigin($this->units[$id]->hexagon);

            for ($i = 0; $i < count($this->units); $i++)
            {
                $los->setEndPoint($this->units[i]->hexagon);
                if ($los->getRange() == 1
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
        $this->units[$id]->hexagon = $hexagon;
        $this->units[$id]->moveCount++;
        $this->units[$id]->moveAmountUsed = $this->units[$id]->moveAmountUsed + $moveAmount;
    }

}