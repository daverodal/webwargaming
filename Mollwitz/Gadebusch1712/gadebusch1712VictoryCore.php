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

class gadebusch1712VictoryCore extends victoryCore
{
    public $wasIndecisive;
    public $isIndecisive;

    function __construct($data)
    {
        if ($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
            $this->deadGuardInf = $data->victory->deadGuardInf;
            $this->wasIndecisive = $data->victory->wasIndecisive;
            $this->isIndecisive = $data->victory->isIndecisive;
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
        $ret->deadGuardInf = $this->deadGuardInf;
        $ret->wasIndecisive = $this->wasIndecisive;
        $ret->isIndecisive = $this->isIndecisive;
        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $mult = 1;
        if ($unit->nationality == "Guard") {
            $mult = 1.5;
            if ($unit->class == "infantry" && $unit->maxStrength == 9) {
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

        if (in_array($mapHexName, $battle->specialHexB)) {
            if ($forceId == SWEDISH_FORCE) {
                $this->victoryPoints[SWEDISH_FORCE] += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='swedish'>+10 Swedish vp</span>";
            }
            if ($forceId == DANISH_FORCE) {
                $this->victoryPoints[SWEDISH_FORCE] -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='danish'>-10 Swedish vp</span>";
            }
        }
    }

    protected function checkVictory( $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $danishWin = $swedishWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $winScore = 35;
            $highWinScore = 42;
            if ($this->victoryPoints[DANISH_FORCE] >= $winScore && $turn <= 5) {
                $danishWin = true;
                $victoryReason .= "Over $winScore on or before turn 5";
            }
            if ($this->victoryPoints[DANISH_FORCE] >= $highWinScore) {
                $danishWin = true;
                $victoryReason .= "Over $highWinScore ";
            }
            if ($this->victoryPoints[SWEDISH_FORCE] >= $winScore && $turn <= 5) {
                $swedishWin = true;
                $victoryReason .= "Over $winScore on or before turn 5 ";
            }
            if ($this->victoryPoints[SWEDISH_FORCE] >= $highWinScore) {
                $swedishWin = true;
                $victoryReason .= "Over $highWinScore ";
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
            if ($danishWin && $swedishWin) {
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $victoryReason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            echo "$turn ";
            echo $gameRules->maxTurn;
            if ($turn > $gameRules->maxTurn) {
                $this->winner = DANISH_FORCE;
                $gameRules->flashMessages[] = "Danish Win";
                $gameRules->flashMessages[] = "Swedes Fail to Win";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }

    public function preRecoverUnits()
    {
        parent::preRecoverUnits();

        if ($this->wasIndecisive) {
            return;
        }
        $b = Battle::getBattle();
        $turn = $b->gameRules->turn;
        if ($turn <= 3 && $this->wasIndecisive === false && $b->gameRules->phase == RED_MOVE_PHASE) {
            $Die = floor(6 * (rand() / getrandmax()));
            /* 1 or 2 is 0 or 1 */
            if ($Die < 2) {

                $this->isIndecisive = true;
                $this->wasIndecisive = true;
                $b->gameRules->flashMessages[] = "$Die No Danish Movement this turn.";

                return;
            }
        }
        if ($turn == 4 && $this->wasIndecisive === false && $b->gameRules->phase == RED_MOVE_PHASE) {
            $this->isIndecisive = true;
            $this->wasIndecisive = true;
        }
    }

    public function postRecoverUnits($args)
    {
//        parent::postRecoverUnits($args);
        $this->isIndecisive = false;
    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;

        parent::postRecoverUnit($args);

        if ($this->isIndecisive && $unit->status == STATUS_READY) {
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }
    }
}
