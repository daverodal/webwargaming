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
        }else{
            $victorId = 1;
        }
        $this->victoryPoints[$victorId] += $unit->strength;
    }
}