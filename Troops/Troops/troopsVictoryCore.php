<?php
/**
 *
 * Copyright 2012-2015 David Rodal
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
include "victoryCore.php";
include "troopersVictoryCore.php";

class troopsVictoryCore extends troopersVictoryCore
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
        $hex = $unit->hexagon;
        $battle = Battle::getBattle();

        $mult = 1;
        if ($unit->nationality == "British") {
            $mult = 2;
        }
        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength * $mult;
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='british'>DE</span>";
        } else {
            $victorId = 1;
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='german'>DE</span>";
            $this->victoryPoints[$victorId] += $unit->strength * $mult;
        }
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();
        if ($battle->scenario->dayTwo) {
            list($mapHexName, $forceId) = $args;

            if ($forceId == SIKH_FORCE) {
                $this->victoryPoints[SIKH_FORCE] += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='beluchi'>+5 Sikh  vp</span>";
            }
            if ($forceId == BRITISH_FORCE) {
                $this->victoryPoints[SIKH_FORCE] -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='british'>-5 Sikh  vp</span>";
            }
        } else {

            list($mapHexName, $forceId) = $args;
            if ($mapHexName == $battle->moodkee) {
                if ($forceId == SIKH_FORCE) {
                    $this->victoryPoints[SIKH_FORCE] += 20;
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='beluchi'>+5 Sikh  vp</span>";
                }
                if ($forceId == BRITISH_FORCE) {
                    $this->victoryPoints[SIKH_FORCE] -= 20;
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='british'>-5 Sikh  vp</span>";
                }

            } else {
                if ($forceId == BRITISH_FORCE) {
                    $this->victoryPoints[BRITISH_FORCE] += 5;
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='british'>+5 British  vp</span>";
                }
                if ($forceId == SIKH_FORCE) {
                    $this->victoryPoints[BRITISH_FORCE] -= 5;
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='beluchi'>-5 British  vp</span>";
                }
            }
        }
    }


    protected function checkVictory($attackingId, $battle)
    {
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $sikhWin = $britishWin = false;

        return false;
        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $britVic = 40;
            $lead = 15;
            if (($this->victoryPoints[BRITISH_FORCE] >= $britVic && ($this->victoryPoints[BRITISH_FORCE] - ($this->victoryPoints[SIKH_FORCE]) >= $lead))) {
                $britishWin = true;
            }
            if (($this->victoryPoints[SIKH_FORCE] >= 35)) {
                $sikhWin = true;
            }
            if ($turn == $gameRules->maxTurn + 1) {
                if (!$britishWin) {
                    $sikhWin = true;
                }
                if ($sikhWin && $britishWin) {
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

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();

        /* Deal with Forced March */
        if($b->gameRules->mode == COMBAT_SETUP_MODE){
            if($unit->class === 'infantry'){
                if( $unit->moveAmountUnused < 2){
                    $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                }
            } else{
                if($unit->moveAmountUnused !== $unit->maxMove){
                    $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                }
            }
        }
        if($b->gameRules->phase == BLUE_FIRST_COMBAT_PHASE && $unit->isDisrupted === BLUE_COMBAT_RES_PHASE) {
            $unit->isDisrupted = false;
        }
        if($b->gameRules->phase == RED_SECOND_COMBAT_PHASE && $unit->isDisrupted === RED_COMBAT_RES_PHASE){
            $unit->isDisrupted = false;
        }
        if($unit->isDisrupted !== false){
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }
    }
}
