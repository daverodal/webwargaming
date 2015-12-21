<?php
namespace Troops\Troops;
use \stdClass;
use \Battle;
use \Hexpart;
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


class troopsVictoryCore extends \Troops\troopersVictoryCore
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
        $playerOne = strtolower($battle->scenario->playerOne);
        $playerTwo = strtolower($battle->scenario->playerTwo);


        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength;
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerTwo'>DE</span>";
        } else {
            $victorId = 1;
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerOne'>DE</span>";
            $this->victoryPoints[$victorId] += $unit->strength;
        }
    }

    public function disruptUnit($args)
    {
        list($unit) = $args;
        $hex = $unit->hexagon;
        $battle = Battle::getBattle();
        $playerOne = strtolower($battle->scenario->playerOne);
        $playerTwo = strtolower($battle->scenario->playerTwo);

        if ($unit->forceId == 1) {
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerTwo'>DD</span>";
        } else {
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerOne'>DD</span>";
        }
    }

    public function noEffectUnit($args)
    {
        list($unit) = $args;
        $hex = $unit->hexagon;
        $battle = Battle::getBattle();
        $playerOne = strtolower($battle->scenario->playerOne);
        $playerTwo = strtolower($battle->scenario->playerTwo);

        if ($unit->forceId == 1) {
            $victorId = 2;
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerTwo'>NE</span>";
        } else {
            $victorId = 1;
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerOne'>NE</span>";
        }
    }

    public function specialHexChange($args)
    {

    }


    protected function checkVictory($attackingId, $battle)
    {
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $sikhWin = $britishWin = false;

        return false;
    }

    public function postCombatResults($args)
    {
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $b = Battle::getBattle();
        $cr = $b->combatRules;
        $f = $b->force;
        foreach ($attackers as $attackId => $v) {
            $unit = $f->units[$attackId];

            $hexagon = $unit->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            if ($b->terrain->terrainIs($hexpart, 'town') || $b->terrain->terrainIs($hexpart, 'forest')) {
                $cr->sighted($hexagon->name);
            }
        }
    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();

        /* Deal with Forced March */
        if ($b->gameRules->mode == COMBAT_SETUP_MODE) {
            if ($unit->isImproved !== true) {
                if ($unit->class === 'infantry') {
                    if ($unit->moveAmountUnused < 2) {
                        $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                    }
                } elseif ($unit->class === 'artillery' && $unit->nationality === 'French') {
                    if ($unit->moveAmountUnused < 4) {
                        $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                    }
                } else {
                    if ($unit->moveAmountUnused !== $unit->maxMove) {
                        $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                    }
                }
            }
        }
        if ($b->gameRules->phase == BLUE_FIRST_COMBAT_PHASE && $unit->isDisrupted === BLUE_COMBAT_RES_PHASE) {
            $unit->disruptLen--;
            if ($unit->disruptLen === 0) {
                $unit->isDisrupted = false;
            }
        }
        if ($b->gameRules->phase == RED_SECOND_COMBAT_PHASE && $unit->isDisrupted === RED_COMBAT_RES_PHASE) {
            $unit->disruptLen--;
            if ($unit->disruptLen === 0) {
                $unit->isDisrupted = false;
            }
        }
        if ($unit->isDisrupted !== false) {
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }
    }
}
