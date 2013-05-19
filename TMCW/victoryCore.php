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
            echo "$id ";

            if($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox"){
                $theUnits[$id]->status = STATUS_ELIMINATED;
                $theUnits[$id]->hexagon->parent = "deadpile";
            }
        }
    }
    public function playerTurnChange(){
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;

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