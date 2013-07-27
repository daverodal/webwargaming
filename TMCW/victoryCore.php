<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */

class victoryCore{
    public $victoryPoints;
    private $movementCache;
    private $combatCache;


    function __construct($data){
        if($data){
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->movementCache = $data->victory->movementCache;
            $this->combatCache = $data->victory->combatCache;
        }else{
            $this->victoryPoints = array(0,0,0);
            $this->movementCache = new stdClass();
            $this->combatCache = new stdClass();
        }
    }
    public function save(){
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        $ret->combatCache = $this->combatCache;
        return $ret;
    }
    public function reduceUnit($args){
        $unit = $args[0];
        if($unit->strength == $unit->maxStrength){
            if($unit->status == STATUS_ELIMINATING || $unit->status == STATUS_RETREATING){
                $vp = $unit->maxStrength;
            }else{
                $vp = $unit->maxStrength - $unit->minStrength;
            }
        }else{
            $vp = $unit->minStrength;
        }
        if($unit->forceId == 1){
            $victorId = 2;
            $this->victoryPoints[$victorId] += $vp;
            $hex  = $unit->hexagon;
            $battle = Battle::getBattle();
            $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='loyalistVictoryPoints'>+$vp vp</span>";
        }else{
//            $victorId = 1;
//            $hex  = $unit->hexagon;
//            $battle = Battle::getBattle();
//            $battle->mapData->specialHexesVictory->{$hex->name} = "+$vp vp";
//            $this->victoryPoints[$victorId] += $vp;
        }
    }
    public function incrementTurn(){
        $battle = Battle::getBattle();

        $theUnits = $battle->force->units;
        foreach($theUnits as $id => $unit){

            if($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox"){
                $theUnits[$id]->status = STATUS_ELIMINATED;
                $theUnits[$id]->hexagon->parent = "deadpile";
            }
        }
    }
    public function phaseChange(){

        echo "Phase ";
        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;


        if($gameRules->phase != BLUE_COMBAT_PHASE || $gameRules->phase == RED_COMBAT_PHASE){
            /* Restore all unsupplied strengths */
            $force = $battle->force;
            foreach($this->combatCache as $id => $strength){
                $unit = $force->getUnit($id);
                $unit->strength = $strength;
                unset($this->combatCache->$id);
            }
            $gameRules->flashMessages[] = "@hide crt";
        }
        if($gameRules->phase == BLUE_REPLACEMENT_PHASE || $gameRules->phase ==  RED_REPLACEMENT_PHASE){
            $gameRules->flashMessages[] = "@show deadpile";
            $forceId = $gameRules->attackingForceId;
            var_dump($battle->force->reinforceTurns);
            if($battle->force->reinforceTurns->$turn->$forceId){
                $gameRules->flashMessages[] = "Reinforcements have been moved to the dead pile";
            }
        }
        if($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase ==  RED_MOVE_PHASE){
            $gameRules->flashMessages[] = "@hide deadpile";
        }
        echo "Changecd ";
    }
    public function postRecoverUnit($args)
    {
        /* @var unit $unit */
        $unit = $args[0];

        $b = Battle::getBattle();
        $id = $unit->id;
        if($unit->forceId != $b->gameRules->attackingForceId){
//            return;
        }
//        echo "Post ".$b->arg;
        if($b->arg == "Supply"){
            if($unit->forceId == BLUE_FORCE){
                $goal = array(101,102,103,104,201,301,401,501,601,701,801,901,1001);
                $bias = array(5=>true,6=>true);
            }else{
                $goal = array(3014,3015,3016,3017,3018,3019,3020,2620,2720,2820,2920);
                $bias =  array(2=>true,3=>true);
            }
//            echo "move ".$b->gameRules->mode;
            if($b->gameRules->mode == MOVING_MODE){
//                echo "About ";
                if($unit->status == STATUS_READY || $unit->status == STATUS_UNAVAIL_THIS_PHASE){
//                    echo "calc? ";
                    $unit->supplied = $b->moveRules->calcSupply($unit->id,$goal,$bias);
//                    echo "did it ";
                }else{
                    return;
                }
                if(!$unit->supplied && !isset($this->movementCache->$id)) {
                    $this->movementCache->$id = $unit->maxMove;
                    $unit->maxMove = floor($unit->maxMove / 2);
                }
                if($unit->supplied && isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    unset($this->movementCache->$id);
                }
            }
            if($b->gameRules->mode == COMBAT_SETUP_MODE){
                if($unit->status == STATUS_READY || $unit->status == STATUS_DEFENDING || $unit->status == STATUS_UNAVAIL_THIS_PHASE){
//                    echo "Down under ".$unit->attackingForceId;
                    $unit->supplied = $b->moveRules->calcSupply($unit->id,$goal, $bias);
//                    echo "Well ";
                }else{
                    return;
                }
                if($unit->forceId == $b->gameRules->attackingForceId && !$unit->supplied && !isset($this->combatCache->$id)) {
                    $this->combatCache->$id = $unit->strength;
                    $unit->strength = floor($unit->strength / 2);
                }
                if($unit->supplied && isset($this->combatCache->$id)) {
                    $unit->strength = $this->combatCache->$id;
                    unset($this->combatCache->$id);
                }
                if($unit->supplied && isset($this->movementCache->$id)) {
                    $unit->maxMove = $this->movementCache->$id;
                    unset($this->movementCache->$id);
                }
            }
        }
    }
    public function preCombatResults($args){
        return $args;
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $battle = Battle::getBattle();
        /* @var mapData $mapData */
        $mapData = $battle->mapData;
        $unit = $battle->force->getUnit($defenderId);
        $defendingHex = $unit->hexagon->name;
        if($defendingHex == 407 || $defendingHex == 2415 || $defendingHex == 2414 || $defendingHex == 2515){
            /* Cunieform */
            if($unit->forceId == RED_FORCE){
                if($combatResults == DR2){
                    $combatResults = NE;
                }
                if($combatResults == DRL2){
                    $combatResults = DL;
                }
            }
        }
        return array($defenderId, $attackers, $combatResults, $dieRoll);
    }
    public function preStartMovingUnit($arg){
        $unit = $arg[0];
        $battle = Battle::getBattle();
        if($battle->arg == "Supply"){
            if($unit->class != 'mech'){
                $battle->moveRules->enterZoc = "stop";
                $battle->moveRules->exitZoc = 0;
                $battle->moveRules->noZocZoc = true;
            }else{
                $battle->moveRules->enterZoc = 2;
                $battle->moveRules->exitZoc = 1;
                $battle->moveRules->noZocZoc = false;

            }
        }
    }
    public function playerTurnChange($arg){
        echo "HERE";
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;

        if($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE){
            $gameRules->flashMessages[] = "@hide crt";
        }
        if($attackingId == BLUE_FORCE){
            $gameRules->flashMessages[] = "Rebel Player Turn";
            $gameRules->replacementsAvail = 1;
        }
        if($attackingId  == RED_FORCE){
            $gameRules->flashMessages[] = "Loyalist Player Turn";
            $gameRules->replacementsAvail = 10;
        }

           /*only get special VPs' at end of first Movement Phase */
//        var_dump($specialHexes);
        echo "isn't that special ";
        if($specialHexes){
            $arg = $battle->arg;
            if($arg == "Supply"){
                $inCity = false;
                $roadCut = false;
                foreach($specialHexes as $k=>$v){
                    if($v == BLUE_FORCE){
                        $points = 1;
                        if($k == 2414 || $k == 2415 || $k == 2515){
                            $inCity = true;
                            $points = 5;
                        }elseif($k >= 2416){
                            /* Remember the first road Cut */
                            if($roadCut === false){
                                $roadCut = $k;
                            }
                            continue;
                        }
                        $vp[$v] += $points;
                        $battle->mapData->specialHexesVictory->$k = "<span class='rebelVictoryPoints'>+$points vp</span>";
                    }else{
    //                    $vp[$v] += .5;
                    }
                }
                if($roadCut !== false){
                    $vp[BLUE_FORCE] += 3;
                    $battle->mapData->specialHexesVictory->$roadCut = "<span class='rebelVictoryPoints'>+3 vp</span>";
                }
                if(!$inCity){
                    /* Cuneiform isolated? */
                    $cuneiform = 2515;
                    if(!$battle->moveRules->calcSupplyHex($cuneiform, array(3014,3015,3016,3017,3018,3019,3020,2620,2720,2820,2920),array(2=>true,3=>true),RED_FORCE)){
                        $vp[BLUE_FORCE] += 5;

                        $battle->mapData->specialHexesVictory->$cuneiform = "<span class='rebelVictoryPoints'>+5 vp</span>";

                    }
                }
            }else{
                foreach($specialHexes as $k=>$v){
                    if($v == 1){
                        $points = 1;
                        if($k == 2414 || $k == 2415 || $k == 2515){
                            $points = 5;
                        }elseif($k >= 2416){
                            $points = 3;
                        }
                        $vp[$v] += $points;
                        $battle = Battle::getBattle();
                        $battle->mapData->specialHexesVictory->$k = "<span class='rebelVictoryPoints'>+$points vp</span>";
                    }else{
                        //                    $vp[$v] += .5;
                    }
                }            }
        }
       $this->victoryPoints = $vp;
    }
}