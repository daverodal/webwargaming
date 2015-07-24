<?php
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
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include "victoryCore.php";

class brandywine1777VictoryCore extends victoryCore
{
    public $isDemoralized = false;

    function __construct($data)
    {
        if ($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
            $this->isDemoralized = $data->victory->isDemoralized;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->gameOver = false;
            $this->deadGuardInf = false;
            $this->wasIndecisive = $this->isIndecisive = false;
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->isDemoralized = $this->isDemoralized;
        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $mult = 1;
        $this->scoreKills($unit, $mult);
        if($this->victoryPoints[LOYALIST_FORCE] > 30){
            $this->isDemoralized = true;
        }
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;

        if (in_array($mapHexName, $battle->specialHexB)) {
            if ($forceId == LOYALIST_FORCE) {
                $this->victoryPoints[LOYALIST_FORCE] += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalist'>+10 Loyalist vp</span>";
            }
            if ($forceId == REBEL_FORCE) {
                $this->victoryPoints[LOYALIST_FORCE] -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='rebel'>-10 Loyalist vp</span>";
            }
        }
    }

    protected function checkVictory( $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $loyalWin = $rebelWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $loyalScore = 40;
            $rebelScore = 30;

            if ($this->victoryPoints[LOYALIST_FORCE] >= $loyalScore) {
                $loyalWin = true;
                $victoryReason .= "Over $loyalScore ";
            }

            if ($this->victoryPoints[REBEL_FORCE] >= $rebelScore) {
                $rebelWin = true;
                $victoryReason .= "Over $rebelScore ";
            }

            if ($rebelWin && !$loyalWin) {
                $this->winner = REBEL_FORCE;
                $gameRules->flashMessages[] = "Rebel Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($loyalWin && !$rebelWin) {
                $this->winner = LOYALIST_FORCE;
                $gameRules->flashMessages[] = "Loyal Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($loyalWin && $rebelWin) {
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                $this->winner = REBEL_FORCE;
                $gameRules->flashMessages[] = "Rebel Win";
                $gameRules->flashMessages[] = "Loyalist Fail to Win";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }

    public function preRecoverUnits()
    {
        parent::preRecoverUnits();

    }

    public function postRecoverUnits($args)
    {
//        parent::postRecoverUnits($args);
        $b = Battle::getBattle();

        if($this->isDemoralized){
            if($b->gameRules->phase == RED_MOVE_PHASE) {
                $b->moveRules->noZoc = true;
            }
            else{
                $b->moveRules->noZoc = true;
            }

        }

    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;

        parent::postRecoverUnit($args);
        if($this->isDemoralized){
            if ($b->gameRules->phase == RED_COMBAT_PHASE) {
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            }
        }
    }
}
