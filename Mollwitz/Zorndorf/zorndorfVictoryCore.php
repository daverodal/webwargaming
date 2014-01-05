<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include "victoryCore.php";
class zorndorfVictoryCore extends victoryCore
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
        $mult = 1;
        if($unit->class == "artillery"){
            $mult = 2;
        }
        if($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength * $mult;
        } else {
            $victorId = 1;
            $this->victoryPoints[$victorId] += $unit->strength * $mult;
        }
    }

    protected function checkVictory($attackingId,$battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $prussianWin = $russianWin = false;
            if($this->victoryPoints[RUSSIAN_FORCE] > 62 && ($this->victoryPoints[RUSSIAN_FORCE] - $this->victoryPoints[PRUSSIAN_FORCE] > 10)){
                $russianWin = true;
            }
            if($this->victoryPoints[PRUSSIAN_FORCE] > 62 && ($this->victoryPoints[PRUSSIAN_FORCE] - $this->victoryPoints[RUSSIAN_FORCE] > 10)){
                $prussianWin = true;
            }
            if($russianWin && $prussianWin){
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
            }
            if($russianWin){
                $this->winner = RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Russian Win on Kills";
            }
            if($prussianWin){
                $this->winner = PRUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Prussian Win on Kills";
            }
            if($russianWin || $prussianWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                $gameRules->mode = GAME_OVER_MODE;
                $gameRules->phase = GAME_OVER_PHASE;
                return true;
            }
        }
        return false;
    }
}