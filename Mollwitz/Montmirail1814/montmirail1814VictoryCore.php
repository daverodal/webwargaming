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

class montmirail1814VictoryCore extends victoryCore
{

    function __construct($data)
    {
        if ($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->gameOver = false;
        }
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $mult = 1;
        if($unit->nationality == "Guard"){
            $mult = 1.5;
        }
        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength * $mult;
        } else {
            $victorId = 1;
            $this->victoryPoints[$victorId] += $unit->strength * $mult;
        }
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if (in_array($mapHexName, $battle->specialHexA)) {
            if ($forceId == FRENCH_FORCE) {
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>French Control Vital Objective</span>";
            }
            if ($forceId == ALLIED_FORCE) {
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='allied'>French Lose Vital Objective</span>";
            }
        }
    }

    protected function checkVictory($attackingId, $battle)
    {
        return false;
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $frenchWin = $alliedWin = $draw = false;

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $winScore = 30;
            if ($this->victoryPoints[FRENCH_FORCE] >= $winScore) {
                if ($turn <= 5) {
                    $frenchWin = true;
                }
            }
            if ($this->victoryPoints[ALLIED_FORCE] >= $winScore) {
                $alliedWin = true;
            }

            if ($frenchWin) {
                $this->winner = FRENCH_FORCE;
                $gameRules->flashMessages[] = "Prussian Win";
            }
            if ($alliedWin) {
                $this->winner = ALLIED_FORCE;
                $msg = "Austrian Win";
                $gameRules->flashMessages[] = $msg;
            }
            if ($frenchWin || $alliedWin ||  $turn == ($gameRules->maxTurn + 1)) {
                if(!$frenchWin && !$alliedWin){
                    $gameRules->flashMessages[] = "Tie Game";
                }
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
