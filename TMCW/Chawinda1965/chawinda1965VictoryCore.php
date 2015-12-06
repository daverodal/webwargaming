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

class chawinda1965VictoryCore extends victoryCore
{
    public $victoryPoints;
    protected $movementCache;
    protected $combatCache;
    protected $supplyLen = false;
    public $pakistaniGoal;
    public $indianGoal;

    public $unsuppliedDefenderHalved = true;

    public $gameOver = false;


    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->supplyLen = $data->victory->supplyLen;
            $this->indianGoal = $data->victory->indianGoal;
            $this->pakistaniGoal = $data->victory->pakistaniGoal;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = [0, 0, 0];
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();
            $this->indianGoal = $this->pakistaniGoal = [];
        }
    }

    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

    public function setInitialPakistaniVP($args){
        list($vp) = $args;
        $this->victoryPoints[PAKISTANI_FORCE] = $vp;
    }

    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->combatCache = $this->combatCache;
        $ret->supplyLen = $this->supplyLen;
        $ret->indianGoal = $this->indianGoal;
        $ret->pakistaniGoal = $this->pakistaniGoal;
        $ret->gameOver = $this->gameOver;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();
        list($mapHexName, $forceId) = $args;

        if(in_array($mapHexName, $battle->specialHexA)){

            if ($forceId == PAKISTANI_FORCE) {
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='pakistani'>+3 Pakistani vp -3 Indian vp</span>";
                $this->victoryPoints[PAKISTANI_FORCE]  += 3;
                $this->victoryPoints[INDIAN_FORCE]  -= 3;
            }
            if ($forceId == INDIAN_FORCE) {
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='indian'>+3 Indian vp -3 Pakistani vp</span>";
                $this->victoryPoints[INDIAN_FORCE]  += 3;
                $this->victoryPoints[PAKISTANI_FORCE]  -= 3;
            }
        }
        if(in_array($mapHexName, $battle->specialHexB)){

            if ($forceId == PAKISTANI_FORCE) {
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='pakistani'>+3 Pakistani vp</span>";
                $this->victoryPoints[PAKISTANI_FORCE]  += 3;
            }
            if ($forceId == INDIAN_FORCE) {
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='intian'>-3 Pakistani vp</span>";
                $this->victoryPoints[PAKISTANI_FORCE]  -= 3;
            }
        }
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $multiplier = 1;
        /* mech units score double */
        if($unit->class == "mech"){
            $multiplier = 2;
        }
        $vp = $unit->damage * $multiplier;

        if ($unit->forceId == INDIAN_FORCE) {
            $victorId = PAKISTANI_FORCE;
            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='pakistani'>+$vp vp</span>";
        } else {
            $victorId = INDIAN_FORCE;
            $this->victoryPoints[$victorId] += $vp;
            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='indian'>+$vp vp</span>";
        }
    }

    public function incrementTurn()
    {
        $battle = Battle::getBattle();

        $theUnits = $battle->force->units;
        foreach ($theUnits as $id => $unit) {

            if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
                $theUnits[$id]->status = STATUS_CAN_REINFORCE;
                $theUnits[$id]->hexagon->parent = "deployBox";
            }
        }
    }

    public function gameOver()
    {
        $battle = Battle::getBattle();
        $pakistaniVP = $this->victoryPoints[PAKISTANI_FORCE];
        $indianVP = $this->victoryPoints[INDIAN_FORCE];
        if($pakistaniVP > $indianVP){
            $battle->gameRules->flashMessages[] = "Pakistani Player Wins";
        }
        if($pakistaniVP < $indianVP){
            $battle->gameRules->flashMessages[] = "Indian Player Wins";
        }
        if($pakistaniVP == $indianVP){
            $battle->gameRules->flashMessages[] = "Tie Game";
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

        $indianGoal = $pakistaniGoal = [];

        /* indian goal is west Edge */
        for($i = 1; $i <= 28;$i++){
            $indianGoal[] = 3100 + $i;
        }
        $this->indianGoal = $indianGoal;

        /* pakistani goal is east Edge */
        for($i = 1; $i <= 28    ;$i++){
            $pakistaniGoal[] = 100 + $i;
        }
        $this->pakistaniGoal = $pakistaniGoal;

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
            if ($unit->forceId == INDIAN_FORCE) {
                $bias = array(2 => true, 3 => true);
                $goal = $this->indianGoal;
            } else {
                $bias = array(5 => true, 6 => true);
                $goal = $this->pakistaniGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }

    }


    public function playerTurnChange($arg)
    {
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == INDIAN_FORCE) {
            if($gameRules->turn <= $gameRules->maxTurn){
                $gameRules->flashMessages[] = "Indian Player Turn";
                $gameRules->replacementsAvail = 3;
            }
        }
        if ($attackingId == PAKISTANI_FORCE) {
            $gameRules->flashMessages[] = "Pakistani Player Turn";
            $gameRules->replacementsAvail = 3;
        }

        /*only get special VPs' at end of first Movement Phase */
        $this->victoryPoints = $vp;
    }
}