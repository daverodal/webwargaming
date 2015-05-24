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
include "indiaVictoryCore.php";

class chillianwallah1849VictoryCore extends indiaVictoryCore
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

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if(in_array($mapHexName,$battle->specialHexA)){
            if ($forceId == SIKH_FORCE) {
                $this->victoryPoints[SIKH_FORCE]  += 20;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>+20 Sikh vp</span>";
            }
            if ($forceId == BRITISH_FORCE) {
                $this->victoryPoints[SIKH_FORCE]  -= 20;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>-20 Sikh vp</span>";
            }
        }
        if(in_array($mapHexName,$battle->specialHexB)){
            $vp = 10;

            if ($forceId == BRITISH_FORCE) {
                $this->victoryPoints[BRITISH_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>+$vp British vp</span>";
            }
            if ($forceId == SIKH_FORCE) {
                $this->victoryPoints[BRITISH_FORCE]  -= $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>-$vp British vp</span>";
            }
        }
    }

    protected function checkVictory($attackingId, $battle)
    {
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $sikhWin = $britishWin = false;

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $britVic = 50;
            if (($this->victoryPoints[BRITISH_FORCE] >= $britVic && ($this->victoryPoints[BRITISH_FORCE] - ($this->victoryPoints[SIKH_FORCE]) >= 15))) {
                $britishWin = true;
            }
            if (($this->victoryPoints[SIKH_FORCE] >= 50)) {
                $sikhWin = true;
            }
            if ($turn == $gameRules->maxTurn + 1) {

                if (($sikhWin && $britishWin) || (!$sikhWin && !$britishWin)) {
                    $this->winner = 0;
                    $britishWin = $sikhWin = false;
                    $gameRules->flashMessages[] = "Tie Game";
                    $gameRules->flashMessages[] = "Game Over";
                    $this->gameOver = true;
                    return true;
                }
            }


            if ($britishWin) {
                $this->winner = BRITISH_FORCE;
                $gameRules->flashMessages[] = "British Win";
            }
            if ($sikhWin) {
                $this->winner = SIKH_FORCE;
                $msg = "Sikh Win";
                $gameRules->flashMessages[] = $msg;
            }
            if ($britishWin || $sikhWin) {
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
