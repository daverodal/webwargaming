<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 10/10/15
 * Time: 10:13 AM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

abstract class SimpleForce{
    public $units;
    public $reinforceTurns;

    public $attackingForceId;
    public $defendingForceId;


    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "units") {
                    $this->units = array();
                    foreach ($v as $unit) {
//                        $this->units[] = UnitFactory::build($unit);
                    }
                    continue;
                }
                $this->$k = $v;
            }
            $this->units = [];
        } else {

            $this->reinforceTurns = new stdClass();
            $this->units = array();
        }
    }

    abstract function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll);

    abstract function recoverUnits($phase, $moveRules, $mode);

    abstract function setupAttacker($id, $range);

    abstract function setupDefender($id);

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

    function unitIsFriendly($id)
    {
        $isFriendly = false;

        if ($this->units[$id]->forceId == $this->attackingForceId) {
            $isFriendly = true;
        }
        return $isFriendly;
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





    function getUnit($id)
    {
        return $this->units[$id];
    }


    /* TODO: this may not want to be here, also, yucky code, yucky  */
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

    /*
     * Silly ones that need to go.
     */
    function xxxgetDefenderStrength($defenderId)
    {
        $defenderStrength = 0;
        $defenderStrength += $this->units[$defenderId]->defStrength;
        return $defenderStrength;
    }

    /* TODO: hard to fix 41 places */
    function getUnitHexagon($id)
    {

        return $this->units[$id]->hexagon;
    }

    function xxxgetUnitRange($id)
    {
        return $this->units[$id]->range;
    }

    /*
     * This seems pointless, but subclasses watch
     * this and do extra things when it's called.
     * Like 'required attacks' is handled in a subclass.
     */
    function undoAttackerSetup($id)
    {
        $unit = $this->units[$id];
        $unit->status = STATUS_READY;
        $unit->combatNumber = 0;
        $unit->combatIndex = 0;
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

    function removeEliminatingUnits()
    {
        for ($id = 0; $id < count($this->units); $id++) {
            if ($this->units[$id]->status == STATUS_ELIMINATING) {
                $this->eliminateUnit($id);
            }
        }
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
}
