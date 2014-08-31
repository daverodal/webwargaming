<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once "victoryCore.php";

class moskowVictoryCore extends victoryCore
{
    public $victoryPoints;
    protected $movementCache;
    protected $combatCache;
    protected $supplyLen = false;
    public $sovietGoal;
    public $germanGoal;

    public $gameOver = false;


    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->supplyLen = $data->victory->supplyLen;
            $this->germanGoal = $data->victory->germanGoal;
            $this->sovietGoal = $data->victory->sovietGoal;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();
            $this->germanGoal = $this->sovietGoal = [];
        }
    }

    public function setSupplyLen($supplyLen)
    {
        $this->supplyLen = $supplyLen[0];
    }

    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->combatCache = $this->combatCache;
        $ret->supplyLen = $this->supplyLen;
        $ret->germanGoal = $this->germanGoal;
        $ret->sovietGoal = $this->sovietGoal;
        $ret->gameOver = $this->gameOver;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;

        if ($forceId == LOYALIST_FORCE) {
        }

    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;

        $forceId = $unit->forceId;
        if($unit->forceId == GERMAN_FORCE){
            $zones = $this->germanGoal;
        }else{
            $zones = $this->sovietGoal;
        }
        $reinforceZones = [];
        foreach($zones as $zone){
            $reinforceZones[] = new ReinforceZone($zone, $zone);
        }
        $battle = Battle::getBattle();

        $specialHexes = $battle->mapData->specialHexes;
        foreach($specialHexes as $hexNum => $specialHex){
            if($specialHex == $forceId){
                $reinforceZones[] = new ReinforceZone($hexNum, $hexNum);
            }
        }
        return array($reinforceZones);
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
//            $victorId = 2;
//            $this->victoryPoints[$victorId] += $vp;
//            $hex = $unit->hexagon;
//            $battle = Battle::getBattle();
//            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
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

    public function gameOver()
    {
        $battle = Battle::getBattle();
        $city = $battle->specialHexA[0];
        if ($battle->mapData->getSpecialHex($city) === LOYALIST_FORCE) {
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

        if ($turn == 1 && $gameRules->phase == BLUE_MOVE_PHASE) {
            /* first 4 units gaga */
            $supply = [];
            $battle->terrain->reinforceZones = [];
            $units = $force->units;
            $num = count($units);
            for ($i = 0; $i <= $num; $i++) {
                $unit = $units[$i];
                if ($unit->forceId == BLUE_FORCE && $unit->hexagon->parent === "gameImages") {
                    $supply[$unit->hexagon->name] = BLUE_FORCE;
                }
            }
        }
        if ($gameRules->phase == RED_COMBAT_PHASE || $gameRules->phase == BLUE_COMBAT_PHASE) {
            $gameRules->flashMessages[] = "@hide deployWrapper";
        } else {
            $gameRules->flashMessages[] = "@hide crt";

            /* Restore all un-supplied strengths */
            $force = $battle->force;
            $this->restoreAllCombatEffects($force);
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

    public function preRecoverUnits($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        $germanGoal = $sovietGoal = [];

        /* German goal is west Edge */
        for($i = 1; $i <= 17;$i++){
            $germanGoal[] = 100 + $i;
        }
        $this->germanGoal = $germanGoal;

        /* Soviet goal is west Edge */
        for($i = 1; $i <= 17;$i++){
            $sovietGoal[] = 2700 + $i;
        }
        $this->sovietGoal = $sovietGoal;

    }

    function isExit($args)
    {
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
            if ($unit->forceId == GERMAN_FORCE) {
                $bias = array(5 => true, 6 => true, 1 => true);
                $goal = $this->germanGoal;
            } else {
                $bias = array(2 => true, 3 => true, 4 => true);
                $goal = $this->sovietGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);

            if($b->gameRules->turn >= 4 && $b->gameRules->turn <= 6){

                if(!isset($this->movementCache->$id)) {
                    $this->movementCache->$id = $unit->maxMove;
                    $unit->maxMove = 1;
                    if($unit->forceId == SOVIET_FORCE){
                        $unit->class = 'mudinf';
                    }
                }
                if($unit->forceId == $b->gameRules->attackingForceId){
                    $unit->addAdjustment('mud','floorHalf');
                }else{
                    $unit->removeAdjustment('mud');
                }
            }
            if($b->gameRules->turn == 7){
                if(isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    $unit->removeAdjustment('mud');
                    $unit->class = 'inf';
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
        if ($attackingId == GERMAN_FORCE) {
            $gameRules->flashMessages[] = "German Player Turn";
            $gameRules->replacementsAvail = 1;
            if($gameRules->turn == 4){
                $gameRules->flashMessages[] = "Mud In Effect ";
            }
        }
        if ($attackingId == SOVIET_FORCE) {
            $gameRules->flashMessages[] = "Soviet Player Turn";
            $gameRules->replacementsAvail = 8;
        }

        /*only get special VPs' at end of first Movement Phase */
        $this->victoryPoints = $vp;
    }
}