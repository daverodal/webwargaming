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
include_once "victoryCore.php";

class finalChapterVictoryCore extends victoryCore
{
    public $victoryPoints;
    protected $movementCache;
    protected $combatCache;
    private $landingZones;
    private $airdropZones;
    private $scienceCenterDestroyed = false;
    public $gameOver = false;
    public $germanySurrenders = false;


    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->landingZones = $data->victory->landingZones;
            $this->airdropZones = $data->victory->airdropZones;
            $this->scienceCenterDestroyed = $data->victory->scienceCenterDestroyed;
            $this->gameOver = $data->victory->gameOver;
            $this->germanySurrenders = $data->victory->germanySurrenders;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();
            $this->landingZones = [];
            $this->airdropZones = [];
        }
    }

    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->combatCache = $this->combatCache;
        $ret->landingZones = $this->landingZones;
        $ret->airdropZones = $this->airdropZones;
        $ret->gameOver = $this->gameOver;
        $ret->germanySurrenders = $this->germanySurrenders;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();
        list($mapHexName, $forceId) = $args;
        $vp = 0;
        if(in_array($mapHexName, $battle->specialHexA)){
            $vp = 1;
        }
        if(in_array($mapHexName, $battle->specialHexB)){
            $vp = 2;
        }
        if(in_array($mapHexName, $battle->specialHexC)){
            if($forceId === EASTERN_FORCE || $forceId === WESTERN_FORCE) {
                $vp = 10;
                $units = $battle->force->units;
                $this->germanySurrenders = true;
                foreach ($units as $id => $unit) {
                    if ($unit->forceId > 2) {
                        if ($unit->status !== STATUS_ELIMINATED) {
                            $battle->force->eliminateUnit($id);
                        }
                    }
                }
            }
        }

        if($vp) {
            if ($forceId == WESTERN_FORCE || $forceId == EASTERN_FORCE) {
                $this->victoryPoints[$forceId] += $vp;
                if($forceId == EASTERN_FORCE){
                    $class = 'easternVictoryPoints';
                    $name = 'Eastern';
                }else{
                    $class = 'westernVictoryPoints';
                    $name = "Western";
                }
                if($vp < 10){
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='$class'>+$vp $name</span>";

                }else{
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='$class'>Berlin Falls! +10 vp</span>";
                    $battle->gameRules->flashMessages[] = "Germany Surrenders! Victory in Europe!";

                }
            } else {
                $previousOwner = $battle->mapData->specialHexes->$mapHexName;
                $this->victoryPoints[$previousOwner] -= $vp;
                if($forceId == EASTERN_FORCE){
                    $name = 'Eastern';
                }else{
                    $name = 'Western';
                }
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>-$vp $name</span>";
            }
        }
    }


    public function enterMapSymbol($args)
    {
        $battle = Battle::getBattle();
        /* @var $mapData MapData */
        $mapData = $battle->mapData;
        /* @var $unit MovableUnit */
        list($mapHexName, $unit) = $args;

        if ($unit->forceId == WESTERN_FORCE) {
                    $mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>West Wall Destroyed</span>";
                    $mapData->removeMapSymbol($mapHexName, "westwall");
            }



    }







    public function incrementTurn()
    {
        $battle = Battle::getBattle();

        $theUnits = $battle->force->units;
        foreach ($theUnits as $id => $unit) {

            if ($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox") {
//                $theUnits[$id]->status = STATUS_ELIMINATED;
                $theUnits[$id]->hexagon->parent = "deployBox";
            }
        }
    }

    public function gameOver()
    {
        $battle = Battle::getBattle();
        $city = $battle->specialHexA[0];
        if ($battle->mapData->getSpecialHex($city) === WESTERN_EMPIRE_FORCE) {
            $battle->gameRules->flashMessages[] = "Loyalist Player Wins";
        }else{
            $battle->gameRules->flashMessages[] = "Rebel Player Wins";
        }
        $this->gameOver = true;
        return true;
    }

    public function phaseChange()
    {

        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $forceId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;
        $force = $battle->force;

        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";

            /* Restore all un-supplied strengths */
            $force = $battle->force;
            $this->restoreAllCombatEffects($force);
        }
        if ($gameRules->mode === REPLACING_MODE) {
            $gameRules->flashMessages[] = "@show deadpile";
            $forceId = $gameRules->attackingForceId;
        }
        if ($gameRules->mode === MOVING_MODE) {
            $gameRules->flashMessages[] = "@hide deadpile";
            if ($battle->force->reinforceTurns->$turn->$forceId) {
                $gameRules->flashMessages[] = "@show deployWrapper";
                $gameRules->flashMessages[] = "Reinforcements have been moved to the Deploy/Staging Area";
            }
        }
        if($this->germanySurrenders){
            if($forceId == WESTERN_FORCE){
                $defenderId = EASTERN_FORCE;
            }
            if($forceId == EASTERN_FORCE){
                $defenderId = WESTERN_FORCE;
            }
            $battle->force->setAttackingForceId($forceId, $defenderId);
            $gameRules->defendingForceId = $defenderId;
        }
    }

    public function preRecoverUnits($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();

        $goal = array_merge([101], $this->airdropZones);
        $this->rebelGoal = $goal;

        $goal = array();
        for($row = 1;$row <= 20;$row++){
            $goal[] = 2000+$row;
        }
        /* Don't put lower right corner in twice! */
        for($col = 1;$col <= 19;$col++){
            $goal[] = ($col*100)+20;
        }
        $this->loyalistGoal = $goal;
    }


    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();
        if($this->germanySurrenders){
            if($b->gameRules->mode == COMBAT_SETUP_MODE){
                if($unit->status === STATUS_READY){
                    $unit->status = STATUS_UNAVAIL_THIS_PHASE;
                }
            }
        }
    }

    public function postRecoverUnits()
    {
        $battle = Battle::getBattle();
        $mode = $battle->gameRules->mode;
        if($mode === REPLACING_MODE){

        $westernReplacements = [0, 2, 2, 2, 2, 2, 2, 2, 2, 2];
        $sovietReplacements =  [0, 2, 2, 2, 2, 1, 1, 1, 1, 1];
        $eastGermanReplacements = [0, 1, 1, 1, 1, 1, 1, 1, 1, 1];
        $westGermanReplacements = [0, 1, 0, 1, 0, 0, 1, 0, 1, 0];
        $mapData = $battle->mapData;
        $attackingId = $battle->force->attackingForceId;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn - 1;

        $gameRules->replacementsAvail = 0;
        if ($attackingId == EASTERN_FORCE) {
            /* turn changes before soviet turn but after this check here */
            $gameRules->replacementsAvail = $sovietReplacements[$turn];
            $units = $this->getSovietReplacements($gameRules->replacementsAvail);
        }
        if ($attackingId == WESTERN_EMPIRE_FORCE && !$this->germanySurrenders) {
            $units = $this->getLowest(WESTERN_EMPIRE_FORCE);
            $gameRules->replacementsAvail = $westGermanReplacements[$turn];
        }

        if($attackingId == WESTERN_FORCE){
            $gameRules->replacementsAvail = $westernReplacements[$turn];
        }

        if ($attackingId == EASTERN_EMPIRE_FORCE  && !$this->germanySurrenders) {
            $units = $this->getLowest(EASTERN_EMPIRE_FORCE);
            $gameRules->replacementsAvail = $eastGermanReplacements[$turn];
        }


        }
    }


    public function postEliminated($arg){
        $unit = $arg[0];
        switch($unit->forceId){
            case WESTERN_FORCE:
                $unit->hexagon->parent .= " #western";
                break;
            case EASTERN_EMPIRE_FORCE:
                $unit->hexagon->parent .= " #eastGerman";
                break;
            case WESTERN_EMPIRE_FORCE:
                $unit->hexagon->parent .= " #westGerman";
                break;
            case EASTERN_FORCE:
                $unit->hexagon->parent .= " #eastern";
                break;
        }
    }
    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
        $battle->moveRules->enterZoc = "stop";
        $battle->moveRules->exitZoc = 0;
        $battle->moveRules->noZocZoc = true;
    }

    public function playerTurnChange($arg)
    {
        $battle = Battle::getBattle();
        $attackingId = $arg[0];
        $gameRules = $battle->gameRules;
        global $force_name;
        $gameRules->flashMessages[] = $force_name[$attackingId]." Player Turn";
    }

    private function getLowest($forceId){
        $battle = Battle::getBattle();
        $units = $battle->force->units;
        $lowest = 10;/* bigger than biggest */
        $lowestUnits = [];
        $rejects = [];
        foreach($units as $unitId => $unit){
            if($unit->forceId !== $forceId){
                continue;
            }
            if($unit->status === STATUS_CAN_REPLACE){
                $str = $unit->strength;
                if($str < $lowest){
                    $rejects = array_merge($rejects, $lowestUnits);
                    $lowestUnits = [];
                    $lowestUnits[] = $unitId;
                    $lowest = $str;
                    continue;
                }
                if($str === $lowest){
                    $lowestUnits[] = $unitId;
                    continue;
                }
                $rejects[] = $unitId;
            }
        }
        foreach($rejects as $unitId){
            $units[$unitId]->status = STATUS_ELIMINATED;
        }
        return $lowestUnits;
    }

    private function getSovietReplacements($numReplacements){
        $battle = Battle::getBattle();
        $units = $battle->force->units;
        $lowest = 10;/* bigger than biggest */
        $lowestUnits = [];
        $rejects = [];
        $secondLowest = 10;/* bigger than biggest */
        $secondLowestUnits = [];
        foreach($units as $unitId => $unit){
            if($unit->forceId !== EASTERN_FORCE){
                continue;
            }
            if($unit->status === STATUS_CAN_REPLACE){
                if($unit->nationality === "yugoslavian"){
                    $rejects[] = $unitId;
                    continue;
                }
                if($unit->nationality === "bulgarian" || $unit->nationality === "polish"){
                    continue;
                }
                $str = $unit->strength;
                if($str < $lowest){
                    $rejects = array_merge($rejects, $secondLowestUnits);
                    $secondLowest = $lowest;
                    $secondLowestUnits = $lowestUnits;
                    $lowestUnits = [];
                    $lowestUnits[] = $unitId;
                    $lowest = $str;
                    continue;
                }
                if($str === $lowest){
                    $lowestUnits[] = $unitId;
                    continue;
                }
                if($str < $secondLowest){
                    $rejects = array_merge($rejects, $secondLowestUnits);
                    $secondLowestUnits = [];
                    $secondLowestUnits[] = $unitId;
                    $secondLowest = $str;
                    continue;
                }
                $rejects[] = $unitId;
            }
        }
        if(count($lowestUnits) < $numReplacements){
            if(count($secondLowestUnits) > 0){
                $lowestUnits[] = array_shift($secondLowestUnits);
                $rejects = array_merge($rejects, $secondLowestUnits);
            }
        }else{
            $rejects = array_merge($rejects, $secondLowestUnits);
        }
        foreach($rejects as $unitId){
            $units[$unitId]->status = STATUS_ELIMINATED;
        }
        return $lowestUnits;
    }

}