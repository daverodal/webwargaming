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

class oudenarde1708VictoryCore extends victoryCore
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
            if ($forceId == ANGLO_ALLIED_FORCE) {
                $this->victoryPoints[ANGLO_ALLIED_FORCE] += 15;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='angloallied'>+15 Anglo Allied vp</span>";
            }
            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[ANGLO_ALLIED_FORCE] -= 15;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>-15 Anglo Allied vp</span>";
            }
        }
        if (in_array($mapHexName, $battle->specialHexB)) {
            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[FRENCH_FORCE] += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>+10 French vp</span>";
            }
            if ($forceId == ANGLO_ALLIED_FORCE) {
                $this->victoryPoints[FRENCH_FORCE] -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='angloallied'>-10 French vp</span>";
            }
        }
    }

    protected function checkVictory($attackingId, $battle)
    {
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $angloWin = $frenchWin = $draw = false;

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $winScore = 40;
            if (($this->victoryPoints[ANGLO_ALLIED_FORCE] >= $winScore && ($this->victoryPoints[ANGLO_ALLIED_FORCE] - ($this->victoryPoints[FRENCH_FORCE]) >= 5))) {
                if ($turn <= 7) {
                    $angloWin = true;
                }
            }
            if ($this->victoryPoints[FRENCH_FORCE] >= $winScore) {
                $frenchWin = true;
            }

            if ($angloWin) {
                $this->winner = ANGLO_ALLIED_FORCE;
                $gameRules->flashMessages[] = "Anglo Allied Win";
            }
            if ($frenchWin) {
                $this->winner = FRENCH_FORCE;
                $msg = "French Win";
                $gameRules->flashMessages[] = $msg;
            }
            if ($angloWin || $frenchWin ||  $turn == ($gameRules->maxTurn + 1)) {
                if(!$angloWin && !$frenchWin){
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
