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

class burkersdorfVictoryCore extends victoryCore
{
    public $prussianEnterVictory;

    function __construct($data)
    {
        if ($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
            $this->prussianEnterVictory = $this->prussianEnterVictory;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->gameOver = false;
            $this->prussianEnterVictory = false;
        }
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $mult = 1;
        if ($unit->class == "cavalry" || $unit->class == "artillery") {
            $mult = 2;
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
        if (in_array($mapHexName, $battle->cities)) {
            if ($forceId == PRUSSIAN_FORCE) {
                $this->prussianEnterVictory = true;
                $this->victoryPoints[PRUSSIAN_FORCE] += 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>+10 Prussian vp</span>";
            }
            if ($forceId == AUSTRIAN_FORCE) {
                $this->victoryPoints[PRUSSIAN_FORCE] -= 10;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>-10 Prussian vp</span>";
            }
        }
        if (in_array($mapHexName, $battle->loc)) {
            $vp = 50;
            if ($forceId == PRUSSIAN_FORCE) {
                $this->prussianEnterVictory = true;
                $this->victoryPoints[PRUSSIAN_FORCE] += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>+$vp Prussian vp</span>";
            }
            if ($forceId == AUSTRIAN_FORCE) {
                $this->victoryPoints[PRUSSIAN_FORCE] -= $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>-$vp Prussian vp</span>";
            }
        }
    }

    protected function checkVictory($attackingId, $battle)
    {
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if (!$this->gameOver) {
            $prussianWin = $austrianWin = false;
            if (($this->victoryPoints[AUSTRIAN_FORCE] >= 70) && ($this->victoryPoints[AUSTRIAN_FORCE] - ($this->victoryPoints[PRUSSIAN_FORCE]) >= 10)) {
                $austrianWin = true;
            }
            if (($this->victoryPoints[PRUSSIAN_FORCE] >= 70) && ($this->victoryPoints[PRUSSIAN_FORCE] - $this->victoryPoints[AUSTRIAN_FORCE] >= 10)) {
                $prussianWin = true;
            }

            $cities = $battle->cities;
            $loc = $battle->loc;
            $cities = array_merge($cities, $loc);
            $victoryHexes = 0;
            foreach ($cities as $city) {
                if ($battle->mapData->getSpecialHex($city) === PRUSSIAN_FORCE) {
                    $victoryHexes++;
                }
            }
            if ($prussianWin && $victoryHexes < 2) {
                $prussianWin = false;
            }
            if ($prussianWin && $austrianWin) {
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
            }
            if ($prussianWin) {
                $this->winner = PRUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Prussian Win";
            }
            if ($austrianWin) {
                $this->winner = AUSTRIAN_FORCE;
                $gameRules->flashMessages[] = "Austrians Win";
            }
            if ($austrianWin || $prussianWin || $turn > 15) {
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }

    public function postRecoverUnits($args)
    {
        $b = Battle::getBattle();
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Austrian Movement alowance 2 this turn.";
        }

    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $id = $unit->id;

        parent::postRecoverUnit($args);
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE && $unit->status == STATUS_READY) {
            $this->movementCache->$id = $unit->maxMove;
            $unit->maxMove = 2;
        }
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_COMBAT_PHASE && isset($this->movementCache->$id)) {
            $unit->maxMove = $this->movementCache->$id;
            unset($this->movementCache->$id);
        }
    }
}
