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
require_once 'force.php';
class NavalForce extends Force
{

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

                    if ($phase == BLUE_COMBAT_PHASE || $phase == RED_COMBAT_PHASE || $phase == TEAL_COMBAT_PHASE || $phase == PURPLE_COMBAT_PHASE) {
                        if ($mode == COMBAT_SETUP_MODE) {
                            $unit = $this->units[$id];
                            if($this->units[$id]->forceId == $this->attackingForceId) {
                                $unit->removeSpotted();
                            }
                            if($this->units[$id]->forceId == $this->attackingForceId && $unit->torpReload !== false){
                                $unit->reloadTorp();
                            }
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if ($this->units[$id]->forceId == $this->attackingForceId && ($this->unitIsInRange($id))) {
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



                    if ($phase == BLUE_TORP_COMBAT_PHASE || $phase == RED_TORP_COMBAT_PHASE) {
                        if ($mode == COMBAT_SETUP_MODE) {
                            $status = STATUS_UNAVAIL_THIS_PHASE;
                            if ($this->units[$id]->torpLoad > 0 && $this->units[$id]->torpReload === false) {
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

                    if($this->units[$id]->newSpeed !== false){
                        $this->units[$id]->maxMove = $this->units[$id]->newSpeed;
                        $this->units[$id]->newSpeed = false;
                    }


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


    function unitIsInRange($id)
    {
        $b = Battle::getBattle();
        $isInRange = false;
        $range = $this->units[$id]->range;
        $unitRange = $range * 2;
        if($b->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $b->gameRules->phase == RED_TORP_COMBAT_PHASE) {
            $unitRange = $range * 3;
        }
        $range = $unitRange;
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

}