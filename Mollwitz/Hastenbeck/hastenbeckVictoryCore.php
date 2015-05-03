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
class hastenbeckVictoryCore extends victoryCore
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
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if(in_array($mapHexName,$battle->specialHexA)){
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[ALLIED_FORCE]  += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>+5 Allied vp</span>";
            }
            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[ALLIED_FORCE]  -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>-5 Allied vp</span>";
            }
        }
        if(in_array($mapHexName,$battle->specialHexB)){
            $vp = 5;

            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>+$vp French vp</span>";
            }
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  -= $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>-$vp French vp</span>";
            }
        }
        if(in_array($mapHexName,$battle->specialHexC)){
            $vp = 5;

            $prevForceId = $battle->mapData->specialHexes->$mapHexName;
            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>+$vp French vp</span>";
                if($prevForceId !== 0) {
                    $this->victoryPoints[ALLIED_FORCE] -= $vp;
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>-$vp Allied vp</span>";
                }
            }
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[ALLIED_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>+$vp Allied vp</span>";
                if($prevForceId !== 0) {
                    $this->victoryPoints[FRENCH_FORCE] -= $vp;
                    $battle->mapData->specialHexesVictory->$mapHexName .= "<span class='french'>-$vp French vp</span>";
                }
            }
        }
    }

    protected function checkVictory($attackingId,$battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $frenchLateWin = $frenchWin = $frenchThreeObjectives = $angloWin = false;
        $specialHexes = $battle->mapData->specialHexes;
        $objectiveHexes = array_merge($battle->specialHexesB, $battle->specialHexesC);

        $alliedVictoryPointsNeeded = 45;
        $frenchVictoryPointsNeeded = 60;
        $lead = 10;

        if(!$this->gameOver){
            $frenchObjectives = 0;
            foreach($objectiveHexes as $objectiveHex){
                if($specialHexes->$objectiveHex == FRENCH_FORCE){
                    $frenchObjectives++;
                }
            }
            if($frenchObjectives >= 3){
                $frenchThreeObjectives = true;
            }


            if($this->victoryPoints[ALLIED_FORCE] >= $alliedVictoryPointsNeeded){
                $angloWin = true;
            }
            if($frenchThreeObjectives && ($this->victoryPoints[FRENCH_FORCE] >= $frenchVictoryPointsNeeded)){
                if($turn <= 10) {
                    $frenchWin = true;
                }else{
                    $frenchLateWin = true;
                }
            }
            if($turn == $gameRules->maxTurn+1){
                if($angloWin && !$frenchWin){
                }
                if($frenchWin && !$angloWin){
                }
                if($frenchWin && $angloWin){
                    $this->winner = 0;
                    $angloWin = $frenchWin = false;
                    $gameRules->flashMessages[] = "Tie Game";
                    $gameRules->flashMessages[] = "Game Over";
                    $this->gameOver = true;
                    return true;
                }
                if(!$angloWin && !$frenchWin){
                    if(!$frenchLateWin){
                        $angloWin = true;
                        $gameRules->flashMessages[] = "French Fail to Win";
                    }else{
                        $this->winner = 0;
                        $gameRules->flashMessages[] = "Tie Game";
                        $gameRules->flashMessages[] = "Game Over";
                        $this->gameOver = true;
                        return true;
                    }

                }
            }


            if($angloWin){
                $this->winner = ALLIED_FORCE;
                $gameRules->flashMessages[] = "Allies Win";
            }
            if($frenchWin){
                $this->winner = FRENCH_FORCE;
                $msg = "French Win";
                $gameRules->flashMessages[] = $msg;
            }
            if($angloWin || $frenchWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
