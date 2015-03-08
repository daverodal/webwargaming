<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
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

class victoryCore
{
    public $victoryPoints;
    private $movementCache;

    function __construct($data)
    {
        if($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
        }
    }

    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength;
        } else {
            $victorId = 1;
            $this->victoryPoints[$victorId] += $unit->strength;
        }
    }

    public function phaseChange()
    {
    }

    public function playerTurnChange($arg){
        $attackingId = $arg[0];
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;

        if($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase ==  RED_MOVE_PHASE){
            $gameRules->flashMessages[] = "@hide crt";
        }
        if($attackingId == BLUE_FORCE){
            $gameRules->flashMessages[] = "Austrian Player Turn";
            $gameRules->replacementsAvail = 1;
        }
        if($attackingId  == RED_FORCE){
            $gameRules->flashMessages[] = "French Player Turn";
            $gameRules->replacementsAvail = 10;
        }

        if($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase ==  RED_MOVE_PHASE){
            $gameRules->flashMessages[] = "@hide crt";
            if($gameRules->force->reinforceTurns->$turn->$attackingId){
                $gameRules->flashMessages[] = "You have reinforcements.";
                $gameRules->flashMessages[] = "@show OBC";

            }
        }

    }
    public function postRecoverUnits($args){
        $b = Battle::getBattle();
//        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
//            $b->gameRules->flashMessages[] = "French Movement halved this turn.";
//        }

    }
    public function postRecoverUnit($args)
    {
        return $args;
        $unit = $args[0];
        if($unit->forceId == 1) {
            return;
        }
        $b = Battle::getBattle();
        $id = $unit->id;
//
//        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->status == STATUS_READY) {
//            $this->movementCache->$id = $unit->maxMove;
//            $unit->maxMove = floor($unit->maxMove / 2);
//        }
//        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_COMBAT_PHASE && isset($this->movementCache->$id)) {
//            $unit->maxMove = $this->movementCache->$id;
//            unset($this->movementCache->$id);
//        }
    }
}