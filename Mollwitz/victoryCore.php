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
    public $movementCache;
    public $gameOver;

    public $headQuarters;

    function __construct($data)
    {
        if($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
            $this->headQuarters = $data->headQuarters;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->gameOver = false;
            $this->headQuarters = [];
        }
    }

    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->gameOver = $this->gameOver;
        $ret->headQuarters = $this->headQuarters;
        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $this->scoreKills($unit, 1);
    }

    public function scoreKills($unit, $mult = 1){

        global $force_name;

        if ($unit->forceId == 1) {
            $victorId = 2;
        } else {
            $victorId = 1;
        }
        $victorName = $force_name[$victorId];
        $vp = $unit->damage * $mult;
        $this->victoryPoints[$victorId] += $vp;
        $hex = $unit->hexagon;
        $battle = Battle::getBattle();
        $class = "${victorName} victory-points";
        $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$class'>+$vp $victorName</span>";
    }


    public function phaseChange()
    {
    }

    protected function checkVictory( $battle){
        return false;
    }


    public function gameEnded(){
        if($this->gameOver){
            return true;
        }
        $battle = Battle::getBattle();
        $this->checkVictory($battle);
    }

    public function playerTurnChange($arg){
        global $force_name;
        $attackingId = $arg[0];
        $battle = Battle::getBattle();

        /* @var GameRules $gameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $gameRules->flashMessages[] = "@hide crt";

        if($this->checkVictory($battle)){
            return;
        }

        $gameRules->flashMessages[] = $force_name[$attackingId]." Player Turn";
    }

    public function postCombatResults($args){
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $b = Battle::getBattle();
        foreach ($attackers as $attackerId => $val) {
            $unit = $b->force->units[$attackerId];
            if ($unit->class == "artillery" && $unit->status == STATUS_CAN_ADVANCE) {
                $unit->status = STATUS_ATTACKED;
            }
        }
    }

    public function calcFromAttackers(){
        $mapData = MapData::getInstance();

        $battle = Battle::getBattle();
        /* @var CombatRules $cR */
        $cR = $battle->combatRules;
        /* @var Force $force */
        $force = $battle->force;
        $force->clearRequiredCombats();
        $defenderForceId = $force->defendingForceId;
        foreach($cR->attackers as $attackId => $combatId){
            $mapHex = $mapData->getHex($force->getUnitHexagon($attackId)->name);
            $neighbors = $mapHex->neighbors;
            foreach($neighbors as $neighbor){
                /* @var MapHex $hex */
                $hex = $mapData->getHex($neighbor);
                if($hex->isOccupied($defenderForceId)){
                    $units = $hex->forces[$defenderForceId];
                    foreach($units as $unitId=>$unitVal){
                        $requiredVal = true;
                        $combatId = $cR->defenders->$unitId;
                        if($combatId !== null){
                            $attackers = $cR->combats->$combatId->attackers;
                            if($attackers){
                                if(count((array)$attackers) > 0){
                                    $requiredVal = false;
                                }
                            }

                        }

                        $force->requiredDefenses->$unitId = $requiredVal;
                    }
                }
            }
        }
    }
    public function postUnsetAttacker($args){
        $this->calcFromAttackers();
        list($unit) = $args;
        $id = $unit->id;
    }
    public function postUnsetDefender($args){
        $this->calcFromAttackers();

        list($unit) = $args;
    }
    public function postSetAttacker($args){
        $this->calcFromAttackers();

        list($unit) = $args;
    }
    public function postSetDefender($args){
        $this->calcFromAttackers();

    }


    public function preRecoverUnits(){
        $this->headQuarters = [];
        $b = Battle::getBattle();
        $units = $b->force->units;
        foreach($units as $unit){
            if($unit->class == 'hq' && $unit->hexagon->name && $unit->forceId == $b->force->attackingForceId){
                $this->headQuarters[] = $unit->id;
            }
        }

    }

    public function preRecoverUnit($arg){

    }

    public function checkCommand($unit){
        $id = $unit->id;
        $b = Battle::getBattle();
        $cmdRange = 4;
        if($unit->nationality == "Beluchi" || $unit->nationality == "Sikh"){
            $cmdRange = 3;
        }


        if(($b->gameRules->phase == RED_MOVE_PHASE || $b->gameRules->phase == BLUE_MOVE_PHASE)){
            foreach($this->headQuarters as $hq){
                if($id == $hq){
                    return;
                }
                $los = new Los();

                $los->setOrigin($b->force->getUnitHexagon($id));
                $los->setEndPoint($b->force->getUnitHexagon($hq));
                $range = $los->getRange();
                if($range <= $cmdRange){
                    return;
                }
            }
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            return;
        }

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
        if($b->scenario->commandControl){
            $this->checkCommand($unit);
        }
        if($unit->hexagon->parent === 'deployBox' && $b->gameRules->mode !== DEPLOY_MODE){
            $unit->hexagon->parent = "not-used";
        }
    }
}