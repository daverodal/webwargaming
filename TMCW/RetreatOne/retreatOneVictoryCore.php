<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */


class retreatOneVictoryCore extends victoryCore
{
    public $victoryPoints;
    public $movementCache;
    public $combatCache;
    public $supplyLen = false;
    public $rebelGoal;
    public $loyalistGoal;
    public $gameOver = false;

    public $headQuarters;

    function __construct($data)
    {
        if ($data) {
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
            $this->supplyLen = $data->victory->supplyLen;
            $this->rebelGoal = $data->victory->rebelGoal;
            $this->loyalistGoal = $data->victory->loyalistGoal;
            $this->gameOver = $data->victory->gameOver;
            $this->headQuarters = $data->victory->headQuarters;

        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();
            $this->rebelGoal = [];
            $this->loyalistGoal = [];
            $this->headQuarters = [];

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
        $ret->rebelGoal = $this->rebelGoal;
        $ret->loyalistGoal = $this->loyalistGoal;
        $ret->gameOver = $this->gameOver;
        $ret->headQuarters = $this->headQuarters;
        return $ret;
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if ($forceId == 1) {
            $this->victoryPoints[$forceId]++;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='rebelVictoryPoints'>+1 vp</span>";
        }

    }

    public function postReinforceZones($args)
    {
        list($zones, $unit) = $args;

        $zones[] = new ReinforceZone(2414, 2414);
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
        if ($unit->forceId == CAPROLIANS_FORCE) {
            $victorId = LACONIANS_FORCE;
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

    function isExit($args){
        list($unit) = $args;
        if($unit->forceId == CAPROLIANS_FORCE && in_array($unit->hexagon->name,[107,116,230])){
            if ($unit->forceId == CAPROLIANS_FORCE) {
                $vp = $unit->strength;
                $victorId = CAPROLIANS_FORCE;
                $this->victoryPoints[$victorId] += $vp;
                $hex = $unit->hexagon;
                $battle = Battle::getBattle();
                $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
            }
            return true;
        }
        return false;
    }

    public function preRecoverUnits($args){
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();
        /* @var Moverules $moveRules */
        $moveRules = $b->moveRules;

        if ($b->scenario->supply === true) {
            $bias = array(5 => true, 6 => true);
            $goal = $moveRules->calcRoadSupply(CAPROLIANS_FORCE, [107, 116, 230], $bias);
            $this->rebelGoal = $goal;


            $bias = array(2 => true, 3 => true);
            $goal = $moveRules->calcRoadSupply(LACONIANS_FORCE, [6002, 6012, 6025], $bias);
            $this->loyalistGoal = $goal;

        }
        $this->headQuarters = [];

    }


    public function preRecoverUnit($arg){
        $unit = $arg[0];
        $b = Battle::getBattle();
        $id = $unit->id;
        if($unit->class == 'hq' && $unit->hexagon->name && $unit->forceId == $b->force->attackingForceId){
            $this->headQuarters[] = $id;
        }
    }

    public function checkCommand($unit){
        $id = $unit->id;
        $b = Battle::getBattle();
        $cmdRange = 8;
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
        /* @var unit $unit */
        $unit = $args[0];


        $b = Battle::getBattle();
        $id = $unit->id;
        if ($unit->forceId != $b->gameRules->attackingForceId) {
//            return;
        }
        if ($b->scenario->supply === true) {
            if ($unit->forceId == CAPROLIANS_FORCE) {
                $bias = array(5 => true, 6 => true);
                $goal = $this->rebelGoal;
            } else {
                $bias = array(2 => true, 3 => true);
                $goal = $this->loyalistGoal;
            }
            $this->unitSupplyEffects($unit, $goal, $bias, $this->supplyLen);
        }
        if($unit->forceId === CAPROLIANS_FORCE){
            $this->checkCommand($unit);
        }
        if($b->gameRules->turn <= 2 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->class == 'hq') {
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
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
                $battle->moveRules->noZocZoc = true;
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
        if ($specialHexes) {
            $scenario = $battle->scenario;
            if ($scenario->supply === true) {
                $inCity = false;
                $roadCut = false;
                foreach ($specialHexes as $k => $v) {
                    if ($v == REBEL_FORCE) {
                        $points = 1;
                        if ($k == 2414 || $k == 2415 || $k == 2515) {
                            $inCity = true;
                            $points = 5;
                        } elseif ($k >= 2416) {
                            /* Remember the first road Cut */
                            if ($roadCut === false) {
                                $roadCut = $k;
                            }
                            continue;
                        }
                        $vp[$v] += $points;
                        $battle->mapData->specialHexesVictory->$k = "<span class='rebelVictoryPoints'>+$points vp</span>";
                    } else {
                        //                    $vp[$v] += .5;
                    }
                }
                if ($roadCut !== false) {
                    $vp[REBEL_FORCE] += 3;
                    $battle->mapData->specialHexesVictory->$roadCut = "<span class='rebelVictoryPoints'>+3 vp</span>";
                }
                if (!$inCity) {
                    /* Cuneiform isolated? */
                    $cuneiform = 2515;
                    if (!$battle->moveRules->calcSupplyHex($cuneiform, array(3014, 3015, 3016, 3017, 3018, 3019, 3020, 2620, 2720, 2820, 2920), array(2 => true, 3 => true), RED_FORCE, $this->supplyLen)) {
                        $vp[REBEL_FORCE] += 5;

                        $battle->mapData->specialHexesVictory->$cuneiform = "<span class='rebelVictoryPoints'>+5 vp</span>";

                    }
                }
            } else {
                foreach ($specialHexes as $k => $v) {
                    if ($v == 1) {
                        $points = 1;
                        if ($k == 2414 || $k == 2415 || $k == 2515) {
                            $points = 5;
                        } elseif ($k >= 2416) {
                            $points = 3;
                        }
                        $vp[$v] += $points;
                        $battle = Battle::getBattle();
                        $battle->mapData->specialHexesVictory->$k = "<span class='rebelVictoryPoints'>+$points vp</span>";
                    } else {
                        //                    $vp[$v] += .5;
                    }
                }
            }
        }
        $this->victoryPoints = $vp;
    }
}