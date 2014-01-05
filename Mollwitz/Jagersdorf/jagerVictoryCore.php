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
            if(!$this->isNorkitten()){
                $prussianWin = true;
                $reason = "Win because, No Russian units in Norkitten Woods";
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
                return true;
            }
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