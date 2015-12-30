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

class Lesnaya1708VictoryCore extends victoryCore
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
        if (in_array($mapHexName, $battle->specialHexA)) {
            if ($forceId == SWEDISH_FORCE) {
                $this->victoryPoints[SWEDISH_FORCE] += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='swedish'>+5 Swedish vp</span>";
            }
            if ($forceId == SAXON_POLISH_FORCE) {
                $this->victoryPoints[SWEDISH_FORCE] -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='russian'>-5 Swedish vp</span>";
            }
        }
        if (in_array($mapHexName, $battle->specialHexB)) {
            if ($forceId == SAXON_POLISH_FORCE) {
                $this->victoryPoints[SAXON_POLISH_FORCE] += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='russian'>+5 Saxon Polish vp</span>";
            }
            if ($forceId == SWEDISH_FORCE) {
                $this->victoryPoints[SAXON_POLISH_FORCE] -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='swedish'>-5 Saxon Polish vp</span>";
            }
        }
    }

    public function scoreKills($unit, $mult = 1)
    {
        if($unit->class === "wagon"){
            $mult = 1.5;
        }

        parent::scoreKills($unit, $mult);

    }
        protected function checkVictory( $battle)
    {
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $swedishWin = $russianWin = $draw = false;

        if (!$this->gameOver) {
            $specialHexes = $battle->mapData->specialHexes;
            $russianWinScore = 35;
            $swedWinScore = 30;
            if ($this->victoryPoints[SWEDISH_FORCE] >= $swedWinScore) {
                    $swedishWin = true;
            }

            if ($this->victoryPoints[SAXON_POLISH_FORCE] >= $russianWinScore) {
                $russianWin = true;
            }


            if ($swedishWin && !$russianWin) {
                $this->winner = SWEDISH_FORCE;
                $gameRules->flashMessages[] = "Swedish Win";
            }

            if ($russianWin && $swedishWin) {
                $this->winner = 0;
                $msg = "Tie Game";
                $gameRules->flashMessages[] = $msg;
            }
            if ($swedishWin || $russianWin ||  $turn == ($gameRules->maxTurn + 1)) {
                if(!$russianWin){
                    $this->winner = SWEDISH_FORCE;
                    $msg = "Swedish Win";
                    $gameRules->flashMessages[] = $msg;
                    $gameRules->flashMessages[] = "Russians Fail to Win";
                }
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }

    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
        if ($unit->class === "wagon") {
            $battle->moveRules->noZoc = true;
        }else{
            $battle->moveRules->noZoc = false;

        }
    }

    public function postRecoverUnits($args)
    {
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == BLUE_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Swedish Movement allowance +1 this turn.";
        }
        if ($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            if($scenario->noMovementFirstTurn) {
                $b->gameRules->flashMessages[] = "No Saxon Polish Movement this turn.";
            }else{
                $b->gameRules->flashMessages[] = "Saxon Polish Movement halved this turn.";

            }
        }
    }
    public function preCombatResults($args){
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $b = Battle::getBattle();
        if($b->force->units[$defenderId]->class === "wagon"){
            if($combatResults === DR){
                $combatResults = DE;
            }
        }
        return [$defenderId, $attackers, $combatResults, $dieRoll];

    }
    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;

        parent::postRecoverUnit($args);

        if($b->gameRules->mode === COMBAT_SETUP_MODE){
            if($unit->class === "wagon"){
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            }
        }
    }
}
