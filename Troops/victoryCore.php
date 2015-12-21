<?php
namespace Troops;
use \stdClass;
use \Battle;
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
class victoryCore
{
    public $victoryPoints;
    public $movementCache;
    public $gameOver;


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

    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->gameOver = $this->gameOver;
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

    protected function checkVictory($attackingId, $battle){
        global $force_name;
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $prussianWin = $austrianWin = false;
            if($this->victoryPoints[AUSTRIAN_FORCE] >= 35){
                $austrianWin = true;
                $reason = "Win on kills";
            }
            if($this->victoryPoints[PRUSSIAN_FORCE] >= 35){
                $prussianWin = true;
                $reason = "Win on kills";
            }
            if($turn > 1){
                if($attackingId == PRUSSIAN_FORCE &&  $this->isMollwitz()){
                    $prussianWin = true;
                    $reason = " Occupy Mollwitz";
                }
                if($attackingId == AUSTRIAN_FORCE &&  $this->isNeudorf()){
                    $austrianWin = true;
                    $reason = " Occupy Neudorf";
                }
            }
            if($austrianWin && $prussianWin){
                $this->winner = 0;
                $austrianWin = $prussianWin = false;
                $this->gameOver = true;
                $gameRules->flashMessages[] = "Tie Game";
            }
            if($austrianWin){
                $this->winner = AUSTRIAN_FORCE;
                $gameRules->flashMessages[] = $force_name[AUSTRIAN_FORCE]." $reason";
            }
            if($prussianWin){
                $this->winner = PRUSSIAN_FORCE;
                $gameRules->flashMessages[] = $force_name[PRUSSIAN_FORCE]. " $reason";
            }
            if($austrianWin || $prussianWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if($turn > $gameRules->maxTurn){
                $this->gameOver = true;
                $gameRules->flashMessages[] = "Tie Game";
                return true;
            }
        }
        return false;
    }


    public function playerTurnChange($arg){
        global $force_name;
        $attackingId = $arg[0];
        $battle = Battle::getBattle();

        /* @var GameRules $gameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $gameRules->flashMessages[] = "@hide crt";

        if($this->checkVictory($attackingId,$battle)){
            return;
        }

            $gameRules->flashMessages[] = $force_name[$attackingId]." Player Turn";


    }


    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();

        /* Deal with Forced March */
        if(($b->gameRules->phase == RED_MOVE_PHASE || $b->gameRules->phase == BLUE_MOVE_PHASE) && $unit->forceMarch){
            $unit->forceMarch = false;
        }
        if(($b->gameRules->phase == RED_COMBAT_PHASE || $b->gameRules->phase == BLUE_COMBAT_PHASE) && $unit->forceMarch){
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }

    }
}