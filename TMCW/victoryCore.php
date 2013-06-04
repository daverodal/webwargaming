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
    function __construct($data){
        if($data){
            $this->victoryPoints = $data->victory->victoryPoints;
        }else{
            $this->victoryPoints = array(0,0,0);
        }
    }
    public function save(){
        return $this;
    }
    public function reduceUnit($args){
        $unit = $args[0];
        if($unit->forceId == 1){
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength;
        }else{
            $victorId = 1;
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
        /* @var $battle MartianCivilWar */
        $battle = Battle::getBattle();
        /* @var $gameRules GameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;


        if($gameRules->phase != BLUE_COMBAT_PHASE || $gameRules->phase == RED_COMBAT_PHASE){
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
        if($gameRules->phase == BLUE_MOVEMENT_PHASE || $gameRules->phase ==  RED_MOVEMENT_PHASE){
            $gameRules->flashMessages[] = "@hide deadpile";
        }
    }
    public function playerTurnChange($arg){
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
        if($specialHexes){
            foreach($specialHexes as $k=>$v){
                if($v == 1){
                    if($k == 2414 || $k == 2415 || $k == 2515){
                        $vp[$v] += 5;
                    }else{
                        $vp[$v]++;
                    }
                }else{
                    $vp[$v] += .5;
                }
            }
        }
       $this->victoryPoints = $vp;
    }
}