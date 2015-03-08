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
class lobositzVictoryCore extends victoryCore
{

    function __construct($data)
    {
        if($data) {
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
        if($unit->class == "cavalry" || $unit->class == "artillery"){
            $mult = 2;
        }
        if($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength * $mult;
        } else {
            $victorId = 1;
            $this->victoryPoints[$victorId] += $unit->strength * $mult;
        }
    }

    public function specialHexChange($args)
    {
    }
    protected function checkVictory($attackingId,$battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $reason = "";
        if(!$this->gameOver){
            $prussianWin = $austrianWin = false;
            if(($this->victoryPoints[AUSTRIAN_FORCE] > 60) && ($this->victoryPoints[AUSTRIAN_FORCE] - ($this->victoryPoints[PRUSSIAN_FORCE]) >= 10)){
                $austrianWin = true;
                $reason .= "Austrian Win On Kills ";
            }
            if(($this->victoryPoints[PRUSSIAN_FORCE] > 60) && ($this->victoryPoints[PRUSSIAN_FORCE] - $this->victoryPoints[AUSTRIAN_FORCE] >= 10)){
                $prussianWin = true;
                $reason .= "Prussian Win On Kills ";
            }

            if($attackingId == AUSTRIAN_FORCE){
                foreach($battle->prussianSpecialHexes as $specialHex){
                    if($battle->mapData->getSpecialHex($specialHex) == AUSTRIAN_FORCE){
                        $austrianWin = true;
                        $reason .= "Austrian Win on Taking Road Hex ";
                    }
                }
            }
            if($attackingId == PRUSSIAN_FORCE){
                foreach($battle->austrianSpecialHexes as $specialHex){
                    if($battle->mapData->getSpecialHex($specialHex) == PRUSSIAN_FORCE){
                        $prussianWin = true;
                        $reason .= "Prussian Win on Taking Road Hex ";
                    }
                }
            }
            if($prussianWin && $austrianWin){
                $this->winner = 0;
                $austrian = $prussianWin = false;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = $reason;
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }

            if($austrianWin){
                $this->winner = AUSTRIAN_FORCE;
                $gameRules->flashMessages[] = $reason;
            }
            if($prussianWin){
                $this->winner = PRUSSIAN_FORCE;
                $msg = $reason;
                $gameRules->flashMessages[] = $msg;
            }
            if($austrianWin || $prussianWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
