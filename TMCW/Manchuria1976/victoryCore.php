<?php
namespace TMCW\Manchuria1976;
use \Battle;
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */

class victoryCore
{
    public $victoryPoints;
    private $movementCache;
    private $combatCache;
    public $sovietGoal;
    private $gameOver = false;


    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->sovietGoal = $data->victory->sovietGoal;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new \stdClass();
            $this->combatCache = new \stdClass();
            $this->sovietGoal = [];
        }
    }

    public function save()
    {
        $ret = new \stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->combatCache = $this->combatCache;
        $ret->sovietGoal = $this->sovietGoal;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if ($forceId == SOVIET_FORCE) {
            $this->victoryPoints[SOVIET_FORCE] += 10;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='sovietVictoryPoints'>+10 Soviet vp</span>";
        }
        if ($forceId == PRC_FORCE) {
            $this->victoryPoints[SOVIET_FORCE] -= 10;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='sovietVictoryPoints'>-10 Soviet vp</span>";
        }

    }

    public function postEliminated($args){
        list($unit) = $args;
        if($unit->class === "gorilla"){
            $unit->hexagon->parent = "undeadpile";
        }
    }

    public function postReinforceZones($args)
    {
        $battle = Battle::getBattle();

        list($zones, $unit) = $args;
        if ($unit->nationality == "prc") {
            $battle = Battle::getBattle();
            $mapData = $battle->mapData;

            if ($unit->class == "gorilla") {

                $newZones = [];
                /* @var Force $force */
                $force = $battle->force;
                foreach ($zones as $zone) {
                    $mapHex = $mapData->getHex($zone->hexagon->name);
                    if ($force->mapHexIsZOC($mapHex)) {
                        continue;
                    }
                    $newZones[] = $zone;
                }
                return array($newZones);
            }

            /* @var MapData $mapData */
            $mapData = $battle->mapData;
            $specialHexes = $battle->specialHexA;

            $zones = [];
            foreach ($specialHexes as $specialHex) {
                if ($mapData->getSpecialHex($specialHex) == PRC_FORCE) {
                    $zones[] = new \ReinforceZone($specialHex, $specialHex);
                }
            }
        }
        return array($zones);
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if ($unit->strength == $unit->maxStrength) {
            if ($unit->status == STATUS_ELIMINATING || $unit->status == STATUS_RETREATING) {
                $vp = $unit->maxStrength;
            } else {
                $vp = $unit->maxStrength - $unit->minStrength;
            }
        } else {
            $vp = $unit->minStrength;
        }
        if ($unit->forceId == PRC_FORCE) {
            $victorId = SOVIET_FORCE;
            $vp /= 2;
            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='sovietVictoryPoints'>+$vp vp</span>";
        } else {
            $victorId = PRC_FORCE;
            $this->victoryPoints[$victorId] += $vp * 1.5;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='prcVictoryPoints'>+$vp vp</span>";
        }
    }

    public function incrementTurn()
    {
        $battle = Battle::getBattle();

        $theUnits = $battle->force->units;
        foreach ($theUnits as $id => $unit) {

            if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
//                $theUnits[$id]->status = STATUS_ELIMINATED;
                $theUnits[$id]->hexagon->parent = "deployBox";
            }
        }
    }

    public function phaseChange()
    {

        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $forceId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;

        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";

            /* Restore all un-supplied strengths */
            $force = $battle->force;
            foreach ($this->combatCache as $id => $strength) {
                $unit = $force->getUnit($id);
                $unit->removeAdjustment('supply');
//                $unit->strength = $strength;
                unset($this->combatCache->$id);
            }
        }
        if ($gameRules->phase == BLUE_REPLACEMENT_PHASE || $gameRules->phase == RED_REPLACEMENT_PHASE) {
            $gameRules->flashMessages[] = "@show deadpile";
            $forceId = $gameRules->attackingForceId;
        }
        if ($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "@hide deadpile";
            if ($battle->force->reinforceTurns->$turn->$forceId) {
                $gameRules->flashMessages[] = "@show deployWrapper";
                $gameRules->flashMessages[] = "Reinforcements have been moved to the Deploy/Staging Area";
            }
        }
    }

    public function preRecoverUnits($args){
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();

        if ($b->scenario->supply === true) {
            $bias = array(2 => true, 3 => true);
            $goal = $b->moveRules->calcRoadSupply(SOVIET_FORCE, 3920, $bias);
            $goal = array_merge($goal, $b->moveRules->calcRoadSupply(SOVIET_FORCE, 1932, $bias));
            $goal = array_merge($goal, $b->moveRules->calcRoadSupply(SOVIET_FORCE, 3233, $bias));
            $goal = array_merge($goal, array(3910, 3911, 3912, 3913, 3914, 3915, 3916, 3917, 3918, 3919));
            $this->sovietGoal = $goal;

        }

    }
    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];
        $supplyLen = 12;

        $b = Battle::getBattle();
        $id = $unit->id;
        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
        $goal = $this->sovietGoal;
        if ($b->scenario->supply === true) {
            if ($unit->forceId == PRC_FORCE) {
                return; /* in supply in china, should verify we ARE in china, but..... */
            }
            if ($unit->forceId == SOVIET_FORCE) {
                $bias = array(2 => true, 3 => true);
            } else {
                $goal = array(101, 102, 103, 104, 201, 301, 401, 501, 601, 701, 801, 901, 1001);
                $bias = array(5 => true, 6 => true);
            }
            if ($b->gameRules->mode == REPLACING_MODE) {
                if ($unit->status == STATUS_CAN_UPGRADE) {
                    $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
                    if (!$unit->supplied) {
                        /* TODO: make this not cry  (call a method) */
                        $unit->status = STATUS_STOPPED;
                    }
                }
                return;
            }
            if ($b->gameRules->mode == MOVING_MODE) {
                if ($unit->status == STATUS_READY || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {
                    $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
                } else {
                    return;
                }
                if (!$unit->supplied && !isset($this->movementCache->$id)) {
                    $this->movementCache->$id = $unit->maxMove;
                    $unit->maxMove = floor($unit->maxMove / 2);
                }
                if ($unit->supplied && isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    unset($this->movementCache->$id);
                }
            }
            if ($b->gameRules->mode == COMBAT_SETUP_MODE) {
                if ($unit->status == STATUS_READY || $unit->status == STATUS_DEFENDING || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {
                    $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
                } else {
                    return;
                }
                if ($unit->forceId == $b->gameRules->attackingForceId && !$unit->supplied && !isset($this->combatCache->$id)) {
                    $this->combatCache->$id = true;
                    $unit->addAdjustment('supply','floorHalf');
//                    $unit->strength = floor($unit->strength / 2);
                }
                if ($unit->supplied && isset($this->combatCache->$id)) {
//                    $unit->strength = $this->combatCache->$id;
                    $unit->removeAdjustment('supply');
                    unset($this->combatCache->$id);
                }
                if ($unit->supplied && isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    unset($this->movementCache->$id);
                }
            }
        }
    }

    public function preCombatResults($args)
    {
        return $args;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        $unit = $battle->force->getUnit($defenderId);
        $defendingHex = $unit->hexagon->name;
        if ($defendingHex == 407 || $defendingHex == 2415 || $defendingHex == 2414 || $defendingHex == 2515) {
            /* Cunieform */
            if ($unit->forceId == RED_FORCE) {
                if ($combatResults == DR2) {
                    $combatResults = NE;
                }
                if ($combatResults == DRL2) {
                    $combatResults = DL;
                }
            }
        }
        return array($defenderId, $attackers, $combatResults, $dieRoll);
    }

    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
        if ($battle->scenario->supply === true) {
            if ($unit->class != 'mech') {
                $battle->moveRules->enterZoc = "stop";
                $battle->moveRules->exitZoc = 0;
                $battle->moveRules->noZocZoc = true;
                if ($battle->terrain->terrainIsHex($unit->hexagon, "mountain")) {
                    $battle->moveRules->noZocZoc = false;
                }
            } else {
                $battle->moveRules->enterZoc = 2;
                $battle->moveRules->exitZoc = 1;
                $battle->moveRules->noZocZoc = false;
            }
        }
    }

    public function playerTurnChange($arg)
    {
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == SOVIET_FORCE) {
            $gameRules->flashMessages[] = "Soviet Player Turn";
            $gameRules->replacementsAvail = 1;
        }
        if ($attackingId == PRC_FORCE) {
            $gameRules->flashMessages[] = "PRC Player Turn";
            $gameRules->replacementsAvail = 5;
        }

        /*only get special VPs' at end of first Movement Phase */

    }
}