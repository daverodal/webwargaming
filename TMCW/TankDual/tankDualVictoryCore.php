<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include "victoryCore.php";

class tankDualVictoryCore extends victoryCore
{
    public $victoryPoints;
    private $movementCache;
    private $combatCache;
    private $supplyLen = false;
    private $landingZones;


    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->supplyLen = $data->victory->supplyLen;
            $this->landingZones = $data->victory->landingZones;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();
            $this->landingZones = [];
        }
    }

    public function setSupplyLen($supplyLen){
        $this->supplyLen = $supplyLen[0];
    }
    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->combatCache = $this->combatCache;
        $ret->supplyLen = $this->supplyLen;
        $ret->landingZones = $this->landingZones;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;

        if($forceId == RED_FORCE){
            $newLandings = [];
            foreach($this->landingZones as $landingZone){
                if($landingZone == $mapHexName){
                    continue;
                }
                $newLandings[] = $landingZone;
            }
            $this->landingZones = $newLandings;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>Beachhead Destroyed</span>";

            $battle->mapData->removeSpecialHex($mapHexName);
        }

    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;

        if($unit->forceId == BLUE_FORCE){
            $zones = [];
            foreach($this->landingZones as $landingZone){
                $zones[] = new ReinforceZone($landingZone,"A");
            }
        }

        return array($zones);
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if ($unit->strength == $unit->maxStrength) {
            if ($unit->status == STATUS_ELIMINATING || $unit->status == STATUS_RETREATING) {
                $vp = $unit->maxStrength;
            } else {
                $vp = $unit->maxStrength - $unit->minStrength;
            }
        } else {
            $vp = $unit->minStrength;
        }
        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
        } else {
//            $victorId = 1;
//            $hex  = $unit->hexagon;
//            $battle = Battle::getBattle();
//            $battle->mapData->specialHexesVictory->{$hex->name} = "+$vp vp";
//            $this->victoryPoints[$victorId] += $vp;
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

    public function gameOver(){
        $battle = Battle::getBattle();

        if($this->victoryPoints[REBEL_FORCE] > $this->victoryPoints[LOYALIST_FORCE]){
            $battle->gameRules->flashMessages[] = "Rebel Player Wins";
        }else{
            $battle->gameRules->flashMessages[] = "Loyalist Player Wins";
        }
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
            foreach($this->combatCache as $id => $strength){
                $unit = $force->getUnit($id);
                $unit->removeAdjustment('supply');
//                $unit->strength = $strength;
                unset($this->combatCache->$id);
            }
        }
        if ($gameRules->phase == BLUE_REPLACEMENT_PHASE || $gameRules->phase == RED_REPLACEMENT_PHASE) {
            $gameRules->flashMessages[] = "@show deadpile";
            $forceId = $gameRules->attackingForceId;
        }
        if ($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase == RED_MOVE_PHASE) {
            $gameRules->flashMessages[] = "@hide deadpile";
            if ($battle->force->reinforceTurns->$turn->$forceId) {
                $gameRules->flashMessages[] = "@show deployWrapper";
                $gameRules->flashMessages[] = "Reinforcements have been moved to the Deploy/Staging Area";
            }
        }
    }
    public function preRecoverUnits($args){
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();

        $goal = $this->landingZones;
        $this->rebelGoal = $goal;

        $goal = array();
        $goal = array_merge($goal, array(110,210,310,410,510,610,710,810,910,1010,1110,1210,1310,1410,1510,1610,1710,1810,1910,2010));
        $this->loyalistGoal = $goal;
    }

    function isExit($args){
        list($unit) = $args;
        if($unit->forceId == BLUE_FORCE && in_array($unit->hexagon->name,$this->landingZones)){
            return true;
        }
        return false;
    }


    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();
        $id = $unit->id;
        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
        if ($b->scenario->supply === true) {
            if ($unit->forceId == REBEL_FORCE) {
                $bias = array(5 => true, 6 => true, 1=>true);
                $goal = $this->rebelGoal;
            } else {
                $bias = array(2 => true, 3 => true, 4=>true);
                $goal = $this->loyalistGoal;
            }
            if ($b->gameRules->mode == REPLACING_MODE) {
                if ($unit->status == STATUS_CAN_UPGRADE) {
                    $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $this->supplyLen);
                    if (!$unit->supplied) {
                        /* TODO: make this not cry  (call a method) */
                        $unit->status = STATUS_STOPPED;
                    }
                }
                return;
            }
            if ($b->gameRules->mode == MOVING_MODE) {
                if ($unit->status == STATUS_READY || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {
                    $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias,$this->supplyLen);
                } else {
                    return;
                }
                if (!$unit->supplied && !isset($this->movementCache->$id)) {
                    $this->movementCache->$id = $unit->maxMove;
                    $unit->maxMove = floor($unit->maxMove / 2);
                }
                if ($unit->supplied && isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    unset($this->movementCache->$id);
                }
            }
            if ($b->gameRules->mode == COMBAT_SETUP_MODE) {
                if ($unit->status == STATUS_READY || $unit->status == STATUS_DEFENDING || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {

                    $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $this->supplyLen);
                } else {
                    return;
                }
                if ($unit->forceId == $b->gameRules->attackingForceId && !$unit->supplied && !isset($this->combatCache->$id)) {
                    $this->combatCache->$id = true;
//                    $unit->strength = floor($unit->strength / 2);
                    $unit->addAdjustment('supply','floorHalf');
                }
                if ($unit->supplied && isset($this->combatCache->$id)) {
                    $unit->strength = $this->combatCache->$id;
                    unset($this->combatCache->$id);
                }
                if ($unit->supplied && isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    unset($this->movementCache->$id);
                }
            }
        }
    }

    public function preCombatResults($args)
    {
        return $args;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        $unit = $battle->force->getUnit($defenderId);
        $defendingHex = $unit->hexagon->name;
        if ($defendingHex == 407 || $defendingHex == 2415 || $defendingHex == 2414 || $defendingHex == 2515) {
            /* Cunieform */
            if ($unit->forceId == RED_FORCE) {
                if ($combatResults == DR2) {
                    $combatResults = NE;
                }
                if ($combatResults == DRL2) {
                    $combatResults = DL;
                }
            }
        }
        return array($defenderId, $attackers, $combatResults, $dieRoll);
    }

    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
        if ($battle->scenario->supply === true) {
            if ($unit->class != 'mech') {
                $battle->moveRules->enterZoc = "stop";
                $battle->moveRules->exitZoc = 0;
                $battle->moveRules->noZocZoc = false;
            } else {
                $battle->moveRules->enterZoc = 2;
                $battle->moveRules->exitZoc = 1;
                $battle->moveRules->noZocZoc = false;

            }
        }
    }

    public function playerTurnChange($arg)
    {
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == REBEL_FORCE) {
            $gameRules->flashMessages[] = "Rebel Player Turn";
            $gameRules->replacementsAvail = 1;
        }
        if ($attackingId == LOYALIST_FORCE) {
            $gameRules->flashMessages[] = "Loyalist Player Turn";
            $gameRules->replacementsAvail = 10;
        }

        /*only get special VPs' at end of first Movement Phase */
        $this->victoryPoints = $vp;
    }
}