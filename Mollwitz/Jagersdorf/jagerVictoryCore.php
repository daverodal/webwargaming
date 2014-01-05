<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
require_once "victoryCore.php";



class jagerVictoryCore extends victoryCore
{

    function __construct($data)
    {
        if($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->gameOver = false;
        }
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength;
        } else {
            $victorId = 1;
            $this->victoryPoints[$victorId] += $unit->strength;
        }
    }

    public function phaseChange()
    {
    }

    protected function checkVictory($attackingId, $battle){
        global $force_name;
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $prussianWin = $russianWin = false;
            if($this->victoryPoints[RUSSIAN_FORCE] > 20){
                $russianWin = true;
                $reason = "Win on Kills";
            }
            if($this->victoryPoints[PRUSSIAN_FORCE] > 25){
                $reason = "Win on Kills";
                $prussianWin = true;
            }
            if($turn > 1){
                if($this->isNorkitten()){
                    $prussianWin = true;
                    $reason = "Win because, No Russian units in Norkitten Woods";
                }
                if($this->isJagersdorf()){
                    $russianWin = true;
                    $reason = "Win because, No Prussians within 5 hexes of Jagersdorf";
                }
                if($this->isAlmCreek()){
                    $prussianWin = true;
                    $reason = "Win because, No Russians below alm creek and prussians own allm creek bridge";
                }
            }
            if($russianWin && $prussianWin){
                $this->winner = 0;
                $russianWin = $prussianWin = false;
                $gameRules->flashMessages[] = "Tie Game";
            }
            if($russianWin){
                $this->winner = RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Russian $reason";
            }
            if($prussianWin){
                $this->winner = PRUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Prussian $reason";
            }
            if($russianWin || $prussianWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
//                $gameRules->mode = GAME_OVER_MODE;
//                $gameRules->phase = GAME_OVER_PHASE;
                return true;
            }
        }
        return false;
    }

    public function postRecoverUnits($args){
        $b = Battle::getBattle();
        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Russian Movement alowance 2 this turn.";
        }

    }

    private function isNorkitten(){
        $norKitten = [808,908,909,1007,1008,1009,1010,1111,1110,1109,1108,1208,1209,1210,1312,1311,1211,1310,1309,1408,1409,1410,1411,1512,1511,1510,1509,1609,1610];
        $b = Battle::getBattle();
        $force = $b->force;
        $units = $force->units;
        foreach($units as $unit){
            if($unit->forceId == RUSSIAN_FORCE && in_array($unit->hexagon->name, $norKitten) ){
                return false;
            }
        }
        return true;
    }

    protected function isJagersdorf(){
        $jagersdorf = [108,208,309,409,510,610,711,712,612,513,413,314,214,114,113,213,313,412,512,611,511,411,312,212,112,211,311,410,310,210,111,110,209,109];
        $b = Battle::getBattle();
        $force = $b->force;
        $units = $force->units;
        foreach($units as $unit){
            if($unit->forceId == PRUSSIAN_FORCE && in_array($unit->hexagon->name, $jagersdorf) ){
                return false;
            }
        }
        return true;
    }

    protected function isAlmCreek(){
        $almCreek = [115,116,117,118,119,219,218,217,216,215,315,316,317,318,319,418,417,416,415,414,514,515,516,517,518,519,619,
        618,617,616,615,614,613,713,714,715,716,717,718,719,820,720,819,818,817,816,815,814,813,914,915,916,917,918,919,920,
        1019,1018,1017,1016,1015,1014,1013,1114,1115,1116,1117,1118,1119,1120,1219,1218,1217,1216,1215,1214,1213,1314,1315,
        1316,1317,1318,1319,1418,1417,1416,1415,1414,1514,1515,1516,1517,1518,1618,1617,1616,1615,1614,1715,1716,1717,1718,
        1719,1819,1818,1817,1816,1815,1814,1915,1916,1917,1918,1919,1920,2019,2018,2017,2014,2015,2016];
        $almBridge = [1714,1715];
        $b = Battle::getBattle();
        $force = $b->force;
        $units = $force->units;
        $bothSides = 0;
        foreach($units as $unit){
            if($unit->forceId == RUSSIAN_FORCE && in_array($unit->hexagon->name, $almCreek)){
                return false;
            }
            if($unit->forceId == PRUSSIAN_FORCE && in_array($unit->hexagon->name, $almBridge) ){
                $bothSides++;
            }
        }

        if($bothSides > 0){
            return true;
        }
        return false;

    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $id = $unit->id;

        parent::postRecoverUnit($args);
        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->status == STATUS_READY) {
            $this->movementCache->$id = $unit->maxMove;
            $unit->maxMove = 2;
        }
        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_COMBAT_PHASE && isset($this->movementCache->$id)) {
            $unit->maxMove = $this->movementCache->$id;
            unset($this->movementCache->$id);
        }
    }
}