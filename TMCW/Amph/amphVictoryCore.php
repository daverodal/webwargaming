<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once "victoryCore.php";

class amphVictoryCore extends victoryCore
{
    public $victoryPoints;
    protected $movementCache;
    protected $combatCache;
    protected $supplyLen = false;
    private $landingZones;
    private $airdropZones;
    private $scienceCenterDestroyed = false;
    public $gameOver = false;


    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->supplyLen = $data->victory->supplyLen;
            $this->landingZones = $data->victory->landingZones;
            $this->airdropZones = $data->victory->airdropZones;
            $this->scienceCenterDestroyed = $data->victory->scienceCenterDestroyed;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();
            $this->landingZones = [];
            $this->airdropZones = [];
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
        $ret->landingZones = $this->landingZones;
        $ret->airdropZones = $this->airdropZones;
        $ret->gameOver = $this->gameOver;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;

//        if ($mapHexName == 1807 && $forceId == REBEL_FORCE) {
//            $this->scienceCenterDestroyed;
//            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='rebelVictoryPoints'>Marine Science Facility Destroyed</span>";
//            $battle->gameRules->flashMessages[] = "Rebel units may now withdraw from beachheads";
//        }
        if ($forceId == LOYALIST_FORCE) {
            $newLandings = [];
            foreach ($this->landingZones as $landingZone) {
                if ($landingZone == $mapHexName) {
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>Beachhead Destroyed</span>";
                    $battle->mapData->removeSpecialHex($mapHexName);
                    unset($battle->mapData->specialHexesChanges->$mapHexName);
                    continue;
                }
                $newLandings[] = $landingZone;
            }
            $this->landingZones = $newLandings;

            $newAirdrops = [];
            foreach ($this->airdropZones as $airdropZone) {
                if ($airdropZone == $mapHexName) {
                    $battle->mapData->specialHexesVictory->$mapHexName = "<span class='loyalistVictoryPoints'>Airdrop zone Destroyed</span>";
                    $battle->mapData->removeSpecialHex($mapHexName);
                    unset($battle->mapData->specialHexesChanges->$mapHexName);
                    continue;
                }
                $newAirdrops[] = $airdropZone;
            }
            $this->airdropZones = $newAirdrops;
        }

    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;
        if ($unit->forceId == BLUE_FORCE) {
            $zone = $unit->reinforceZone;
            $zones = [];
            if ($zone == "A") {
                foreach ($this->landingZones as $landingZone) {
                    $zones[] = new ReinforceZone($landingZone, "A");
                }
            }
            if ($zone == "C") {
                foreach ($this->airdropZones as $airdropZone) {
                    $zones[] = new ReinforceZone($airdropZone, "C");
                }
            }
        }

        return array($zones);
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];

        $vp = $unit->damage;

        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $vp;
            $hex = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
        } else {
            $victorId = 1;
            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "+$vp vp";
            $this->victoryPoints[$victorId] += $vp;
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
                    if ($unit->class === "para") {
                        $this->airdropZones[] = $unit->hexagon->name;
                    } else {
                        $this->landingZones[] = $unit->hexagon->name;
                    }
                }
            }
            $battle->mapData->setSpecialHexes($supply);
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

        $b = Battle::getBattle();

        $goal = array_merge($this->landingZones, $this->airdropZones);
        $this->rebelGoal = $goal;

        $goal = array();
        $goal = array_merge($goal, array(110, 210, 310, 410, 510, 610, 710, 810, 910, 1010, 1110, 1210, 1310, 1410, 1510, 1610, 1710, 1810, 1910, 2010));
        $this->loyalistGoal = $goal;
    }

    function isExit($args)
    {
        list($unit) = $args;
        if ($unit->forceId == BLUE_FORCE && in_array($unit->hexagon->name, $this->landingZones)) {
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
                $bias = array(5 => true, 6 => true, 1 => true);
                $goal = $this->rebelGoal;
            } else {
                $bias = array(2 => true, 3 => true, 4 => true);
                $goal = $this->loyalistGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }
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