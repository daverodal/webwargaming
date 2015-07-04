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

class helsingborg1710VictoryCore extends victoryCore
{

    function __construct($data)
    {
        if ($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
            $this->deadGuardInf = $data->victory->deadGuardInf;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->gameOver = false;
            $this->deadGuardInf = false;
        }
    }

    public function save()
    {
        $ret = parent::save();
        $ret->deadGuardInf = $this->deadGuardInf;
        return $ret;
    }
    public function reduceUnit($args)
    {
        $unit = $args[0];
        $mult = 1;
        if($unit->nationality == "Guard"){
            $mult = 1.5;
            if($unit->class == "infantry" && $unit->maxStrength == 9){
                $mult = 2.0;
                $this->deadGuardInf = true;
            }
        }
        $this->scoreKills($unit, $mult);
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if (in_array($mapHexName, $battle->specialHexA)) {
            if ($forceId == DANISH_FORCE) {
                $this->victoryPoints[DANISH_FORCE]  += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='danish'>+10 Danish vp</span>";
            }
            if ($forceId == SWEDISH_FORCE) {
                $this->victoryPoints[DANISH_FORCE]  -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='swedish'>-10 Danish vp</span>";
            }
        }

        if (in_array($mapHexName, $battle->specialHexB)) {
            if ($forceId == SWEDISH_FORCE) {
                $this->victoryPoints[SWEDISH_FORCE]  += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='swedish'>+10 Swedish vp</span>";
            }
            if ($forceId == DANISH_FORCE) {
                $this->victoryPoints[SWEDISH_FORCE]  -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='danish'>-10 Swedish vp</span>";
            }
        }
    }

    protected function checkVictory($attackingId, $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $danishWin = $swedishWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $winScore = 25;

            if($this->victoryPoints[DANISH_FORCE] >= $winScore && ($this->victoryPoints[DANISH_FORCE] > $this->victoryPoints[SWEDISH_FORCE] + 5)){
                $danishWin = true;
                $victoryReason .= "Over $winScore ";
            }
            if ($this->victoryPoints[SWEDISH_FORCE] >= $winScore && ($this->victoryPoints[SWEDISH_FORCE] > $this->victoryPoints[DANISH_FORCE] + 5)) {
                $swedishWin = true;
                $victoryReason .= "Over $winScore ";
            }

            if ($danishWin && !$swedishWin) {
                $this->winner = DANISH_FORCE;
                $gameRules->flashMessages[] = "Danish Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($swedishWin && !$danishWin) {
                $this->winner = SWEDISH_FORCE;
                $gameRules->flashMessages[] = "Swedish Win";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if($danishWin && $swedishWin){
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ($turn > $gameRules->maxTurn) {
                $this->winner = SWEDISH_FORCE;
                $gameRules->flashMessages[] = "Danish Win";
                $gameRules->flashMessages[] = "Swedes Fail to Win";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }


    public function postRecoverUnits($args)
    {
        $b = Battle::getBattle();
        $scenario = $b->scenario;

        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Swedish Movement alowance +1 this turn.";
        }

        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "No Danish Movement this turn.";
        }
    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;

        parent::postRecoverUnit($args);
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE && $unit->status == STATUS_READY) {
            $this->movementCache->$id = $unit->maxMove;
            $unit->maxMove = $unit->maxMove+1;
        }
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_COMBAT_PHASE && isset($this->movementCache->$id)) {
            $unit->maxMove = $this->movementCache->$id;
            unset($this->movementCache->$id);
        }

        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->status == STATUS_READY) {
            $this->movementCache->$id = $unit->maxMove;
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }
    }
}
