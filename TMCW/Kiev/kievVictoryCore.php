<?php
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
include_once "victoryCore.php";

class kievVictoryCore extends victoryCore
{
    public $victoryPoints;
    protected $movementCache;
    protected $combatCache;
    protected $supplyLen = false;
    public $sovietGoal;
    public $germanGoal;

    public $gameOver = false;

    public $unsuppliedDefenderHalved = true;



    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->supplyLen = $data->victory->supplyLen;
            $this->germanGoal = $data->victory->germanGoal;
            $this->sovietGoal = $data->victory->sovietGoal;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = [0,0,0,0];
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();
            $this->germanGoal = $this->sovietGoal = [];
        }
    }

    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->combatCache = $this->combatCache;
        $ret->supplyLen = $this->supplyLen;
        $ret->germanGoal = $this->germanGoal;
        $ret->sovietGoal = $this->sovietGoal;
        $ret->gameOver = $this->gameOver;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();
        list($mapHexName, $forceId) = $args;

//        if(in_array($mapHexName, $battle->specialHexC)){
//
//            if ($forceId == SOVIET_FORCE) {
//                $this->victoryPoints = "The Soviets hold Kiev";
//            }
//            if ($forceId == GERMAN_FORCE) {
//                $this->victoryPoints = "The Germans hold Kiev";
//            }
//        }
    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;

        $forceId = $unit->forceId;
        if($unit->forceId == GERMAN_FORCE){
            $zones = $this->germanGoal;
        }else{
            $zones = $this->sovietGoal;
        }
        $reinforceZones = [];
        foreach($zones as $zone){
            $reinforceZones[] = new ReinforceZone($zone, $zone);
        }
        $battle = Battle::getBattle();

        return array($reinforceZones);
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
        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
        } else {
            $victorId = 1;
            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "+$vp vp";
            $this->victoryPoints[$victorId] += $vp;
        }
    }

    protected function checkVictory($attackingId, $battle){
        $battle = Battle::getBattle();

        global $force_name;
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $this->victoryPoints[3] = 0;
        if(!$this->gameOver){
            $battle = Battle::getBattle();

            $units = $battle->force->units;
            foreach($units as $unit){
                if($unit->forceId == SOVIET_FORCE){
                    if($unit->hexagon->parent == "gameImages"){
                        if($unit->supplied === false){
                            $this->victoryPoints[3] += $unit->getUnmodifiedStrength();
                        }
                    }
                }
            }
        }
        return false;
    }

    public function incrementTurn()
    {
        $battle = Battle::getBattle();

        $theUnits = $battle->force->units;
        foreach ($theUnits as $id => $unit) {

            if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
                $theUnits[$id]->status = STATUS_ELIMINATED;
                $theUnits[$id]->hexagon->parent = "deadpile";
            }
        }
    }

    public function gameOver()
    {
        $battle = Battle::getBattle();
        $kiev = $battle->specialHexC[0];
        if ($battle->mapData->getSpecialHex($kiev) === SOVIET_FORCE) {
            $battle->gameRules->flashMessages[] = "Soviet Player Wins";
        }else{
            $battle->gameRules->flashMessages[] = "German Player Wins";
        }
        $this->gameOver = true;
        return true;
    }

    public function phaseChange()
    {

        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $forceId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;
        $force = $battle->force;

        if ($turn == 1 && $gameRules->phase == BLUE_MOVE_PHASE) {
            /* first 4 units gaga */
            $supply = [];
            $battle->terrain->reinforceZones = [];
            $units = $force->units;
            $num = count($units);
            for ($i = 0; $i <= $num; $i++) {
                $unit = $units[$i];
                if ($unit->forceId == BLUE_FORCE && $unit->hexagon->parent === "gameImages") {
                    $supply[$unit->hexagon->name] = BLUE_FORCE;
                }
            }
        }
        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";

            /* Restore all un-supplied strengths */
            $force = $battle->force;
            $this->restoreAllCombatEffects($force);
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

    public function preRecoverUnits($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        $germanGoal = $sovietGoal = [];


        $b = Battle::getBattle();


        if ($b->scenario->supplyRailroads === true) {
            $germanBias = array(5 => true, 6 => true);
            $sovietBias = array(2 => true, 3 => true);
            $germanGoal = array_merge($b->moveRules->calcRoadSupply(GERMAN_FORCE, 112, $germanBias),
            $b->moveRules->calcRoadSupply(GERMAN_FORCE, 121, $germanBias),
            $b->moveRules->calcRoadSupply(GERMAN_FORCE, 125, $germanBias),
            $b->moveRules->calcRoadSupply(GERMAN_FORCE, 1901, $germanBias));
            /* Magic to remove dups then remove holes created by removal */
//            $germanGoal = array_merge(array_unique($germanGoal));

            $sovietGoal = array_merge($b->moveRules->calcRoadSupply(SOVIET_FORCE, 4201, $sovietBias),
            $b->moveRules->calcRoadSupply(SOVIET_FORCE, 4612, $sovietBias),
            $b->moveRules->calcRoadSupply(SOVIET_FORCE, 4622, $sovietBias),
            $b->moveRules->calcRoadSupply(SOVIET_FORCE, 4626, $sovietBias));

            /* Magic to remove dups then remove holes created by removal */
//            $sovietGoal = array_merge(array_unique($sovietGoal));


        }else{
            /* German goal is west Edge */
            for($i = 1; $i <= 38;$i++){
                $germanGoal[] = 100 + $i;
            }
            /* Soviet goal is west Edge */
            for($i = 1; $i <= 38    ;$i++){
                $sovietGoal[] = 4600 + $i;
            }
        }

        $this->germanGoal = $germanGoal;


        $this->sovietGoal = $sovietGoal;

    }

    function isExit($args)
    {
        return false;
    }


    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();
        $id = $unit->id;
        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
        if ($b->scenario->supply === true) {
            if ($unit->forceId == GERMAN_FORCE) {
                $bias = array(5 => true, 6 => true);
                $goal = $this->germanGoal;
            } else {
                $bias = array(2 => true, 3 => true);
                $goal = $this->sovietGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }
    }


    public function playerTurnChange($arg)
    {
        parent::playerTurnChange($arg);
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == GERMAN_FORCE) {
            if($gameRules->turn <= $gameRules->maxTurn){
                $gameRules->flashMessages[] = "German Player Turn";
                $gameRules->replacementsAvail = 1;
            }
        }
        if ($attackingId == SOVIET_FORCE) {
            $gameRules->flashMessages[] = "Soviet Player Turn";
            $gameRules->replacementsAvail = 6;
        }

        /*only get special VPs' at end of first Movement Phase */
        $this->victoryPoints = $vp;
    }
}