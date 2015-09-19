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

class TinCansVictoryCore extends victoryCore
{
    public $victoryPoints;
    protected $movementCache;
    protected $combatCache;
    protected $supplyLen = false;

    private $scienceCenterDestroyed = false;
    public $gameOver = false;
    public $winner = false;


    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->supplyLen = $data->victory->supplyLen;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();

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
        $ret->gameOver = $this->gameOver;
        return $ret;
    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;
        return array($zones);
    }

    public function reduceUnit($args)
    {

        $unit = $args[0];



        $type = $unit->class;

        $vp = 0;

        if($type === 'ca'){

            $vp = 10 - $unit->vp;

        }

        if($type === 'dd' || $type === 'cl'){
                $vp = 2 - $unit->vp;
            }

        if($vp) {
               if ($unit->forceId == 1) {                $victorId = 2;

                        $this->victoryPoints[$victorId] += $vp;
                        $hex = $unit->hexagon;
                        $battle = Battle::getBattle();
                        $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
                    } else {
                        $victorId = 1;
                        $hex = $unit->hexagon;
                        $battle = Battle::getBattle();
                        $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='rebelVictoryPoints'>+$vp vp</span>";
                        $this->victoryPoints[$victorId] += $vp;
                    }
       }
    }

    public function scoreHit($args){
        $unit = $args[0];
        $vp = $unit->vp;
        $newVp = 0;
        switch($unit->class){
            case 'ca':
                if($unit->hits > 0){
                    if($vp == 0){
                        $newVp = 2;
                    }
                }
                if($unit->pDamage > 1){
                    $newVp = 5 - $vp;
                }

                break;

            case 'cl':
            case 'dd':
            if($unit->hits){
                if($vp == 0){
                    $newVp = 1;
                }
            }
            if($unit->pDamage > 1){
                    $newVp = 2 - $vp;
                }

                break;
        }
        if($newVp) {
            $unit->vp += $newVp;
            if ($unit->forceId == 1) {
                $victorId = 2;

                $this->victoryPoints[$victorId] += $newVp;
                $hex = $unit->hexagon;
                $battle = Battle::getBattle();
                $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='ijnVictoryPoints'>+$newVp vp</span>";
            } else {
                $victorId = 1;
                $hex = $unit->hexagon;
                $battle = Battle::getBattle();
                $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='usnVictoryPoints'>+$newVp vp</span>";
                $this->victoryPoints[$victorId] += $newVp;
            }
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

    public function gameOver()
    {
        $battle = Battle::getBattle();
        if ($this->victoryPoints[LOYALIST_FORCE] > $this->victoryPoints[REBEL_FORCE]) {
            $battle->gameRules->flashMessages[] = "Loyalist Player Wins";
            $this->winner = LOYALIST_FORCE;
        }
        if ($this->victoryPoints[REBEL_FORCE] > $this->victoryPoints[LOYALIST_FORCE]) {
            $battle->gameRules->flashMessages[] = "Rebel Player Wins";
            $this->winner = REBEL_FORCE;
        }
        if ($this->victoryPoints[LOYALIST_FORCE] == $this->victoryPoints[REBEL_FORCE]) {
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

    }

    function isExit($args)
    {
        list($unit) = $args;
        $hexNum = $unit->hexagon->name;
        $row = $unit->hexagon->name;
        $row = $row%100;
        if($row === 1 || $row === 34){
            return true;
        }
        $hexNum = (int)floor($hexNum / 100);
        if($hexNum === 61 || $hexNum === 1){
            return true;
        }
        return false;
    }


    public function postRecoverUnit($args)
    {

     }

    public function preStartMovingUnit($arg)
    {

    }

    public function playerTurnChange($arg)
    {
    }
}