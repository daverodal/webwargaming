<?php

/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 8/27/15
 * Time: 7:21 PM
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
require_once 'Force.php';
class NavalForce extends SimpleForce
{

    function applyCRTResults($defenderId, $attackers, $combatResults, $dieRoll)
    {
        $battle = Battle::getBattle();

        list($defenderId, $attackers, $combatResults, $dieRoll) = $battle->victory->preCombatResults($defenderId, $attackers, $combatResults, $dieRoll);

        $defUnit = $this->units[$defenderId];
        switch ($combatResults) {



            case NE:
            case MISS:
                $defUnit->status = STATUS_NO_RESULT;
                $defUnit->retreatCountRequired = 0;
                break;
            case P:
            case W:
            case PW:
            case P2:
            case S:
                $eliminated = $defUnit->damageUnit($combatResults);
                if ($eliminated) {
                    $defUnit->moveCount = 0;
                } else {
                    $defUnit->status = STATUS_DEFENDED;
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

            if($battle->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $battle->gameRules->phase == RED_TORP_COMBAT_PHASE){
                $this->units[$attacker]->torpFired();
            }else{
                if(is_callable([$this->units[$attacker],'firedGun'])){
                    $this->units[$attacker]->firedGun();
                }
            }

            if ($this->units[$attacker]->status == STATUS_ATTACKING) {
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


    function setupAttacker($id, $range)
    {
        $this->units[$id]->status = STATUS_BOMBARDING;
    }

    function setupDefender($id)
    {
        $this->units[$id]->status = STATUS_DEFENDING;
    }

    function recoverUnits($phase, $moveRules, $mode)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $victory->preRecoverUnits();
        for ($id = 0; $id < count($this->units); $id++) {
            /* @var $unit NavalUnit */
            $unit = $this->units[$id];
            $victory->preRecoverUnit($unit);

            switch ($unit->status) {
                case STATUS_CAN_DEPLOY:
                    if($mode == DEPLOY_MODE){
                        continue;
                    }
                    if ($unit->isDeploy()) {
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

                    if ($phase == BLUE_COMBAT_PHASE || $phase == RED_COMBAT_PHASE || $phase == TEAL_COMBAT_PHASE || $phase == PURPLE_COMBAT_PHASE) {
                        if ($mode == COMBAT_SETUP_MODE) {
                            $unit = $unit;
                            if($unit->forceId == $this->attackingForceId) {
                                $unit->removeSpotted();
                            }
                            if($unit->forceId == $this->attackingForceId && $unit->torpReload !== false){
                                $unit->reloadTorp();
                            }
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if ($unit->forceId == $this->attackingForceId && ($this->unitIsInRange($id))) {
                                $status = STATUS_READY;
                            }
                        }
                        if ($mode == COMBAT_RESOLUTION_MODE) {
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if ($unit->status == STATUS_ATTACKING ||
                                $unit->status == STATUS_DEFENDING
                            ) {
                                $status = $unit->status;
                            }

                        }
                    }



                    if ($phase == BLUE_TORP_COMBAT_PHASE || $phase == RED_TORP_COMBAT_PHASE) {
                        if ($mode == COMBAT_SETUP_MODE) {
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if ($unit->torpLoad > 0 && $unit->torpReload === false) {
                                $status = STATUS_READY;
                            }
                        }
                        if ($mode == COMBAT_RESOLUTION_MODE) {
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if ($unit->status == STATUS_ATTACKING ||
                                $unit->status == STATUS_DEFENDING
                            ) {
                                $status = $unit->status;
                            }

                        }
                    }

                    if($unit->newSpeed !== false){
                        $unit->maxMove = $unit->newSpeed;
                        $unit->newSpeed = false;
                    }

                    if ($mode == MOVING_MODE ) {
                        if ($unit->pDamage > 1 || $unit->maxMove == 0) {
                            $status = STATUS_STOPPED;
                        }
                    }

                    $unit->status = $status;
                    $unit->moveAmountUsed = 0;
                    break;

                default:
                    break;
            }
            if($phase === BLUE_MOVE_PHASE || $phase === RED_MOVE_PHASE || $phase == TEAL_MOVE_PHASE || $phase == PURPLE_MOVE_PHASE){
                $unit->moveAmountUnused = $unit->maxMove;
            }
            /* Post Movement Phase, Put out fires, and mark dead in water ships sighted if within 3 hexes */
            if($phase === BLUE_SPEED_PHASE || $phase === RED_SPEED_PHASE){
                $unit->postMove();
                if ($unit->pDamage > 1 || $unit->maxMove == 0) {
                    if($this->unitIsInRange($id, 3)){
                        $unit->spotted = true;
                    }
                }
            }
            $unit->combatIndex = 0;
            $unit->combatNumber = 0;
            $unit->combatResults = NE;
            $victory->postRecoverUnit($unit);

        }
        $victory->postRecoverUnits();

    }

    function eliminateUnit($id){
        parent::eliminateUnit($id);
        $this->units[$id]->eliminate();
    }


    function unitIsInRange($id, $argRange = false)
    {
        $b = Battle::getBattle();
        $isInRange = false;
        $range = $this->units[$id]->range;
        $unitRange = $range * 2;
        if($b->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $b->gameRules->phase == RED_TORP_COMBAT_PHASE) {
            $unitRange = $range * 3;
        }
        $range = $unitRange;
        if($argRange !== false){
            $range = $argRange;
        }
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
        return $isInRange;
    }


}