<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include "victoryCore.php";
class hohenfriedebergVictoryCore extends victoryCore
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
        if($unit->class == "cavalry" || $unit->class == "artillery"){
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

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if(in_array($mapHexName,$battle->specialHexA)){
            if ($forceId == PRUSSIAN_FORCE) {
                $this->victoryPoints[PRUSSIAN_FORCE]  += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>+5 Prussian vp</span>";
            }
            if ($forceId == AUSTRIAN_FORCE) {
                $this->victoryPoints[PRUSSIAN_FORCE]  -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>-5 Prussian vp</span>";
            }
        }
        if(in_array($mapHexName,$battle->specialHexB) || in_array($mapHexName,$battle->specialHexC)){
            $vp = 5;
            if(in_array($mapHexName,$battle->specialHexC)){
                $vp = 10;
            }
            if ($forceId == AUSTRIAN_FORCE) {
                $this->victoryPoints[AUSTRIAN_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>+$vp Austrian vp</span>";
            }
            if ($forceId == PRUSSIAN_FORCE) {
                $this->victoryPoints[AUSTRIAN_FORCE]  -= $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>-$vp Austrian vp</span>";
            }
        }
    }
    protected function checkVictory($attackingId,$battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $prussianWin = $austrianWin = false;
            if(($this->victoryPoints[AUSTRIAN_FORCE] >= 60) && ($this->victoryPoints[AUSTRIAN_FORCE] - ($this->victoryPoints[PRUSSIAN_FORCE]) >= 10)){
                $austrianWin = true;
            }
            if(($this->victoryPoints[PRUSSIAN_FORCE] >= 60) && ($this->victoryPoints[PRUSSIAN_FORCE] - $this->victoryPoints[AUSTRIAN_FORCE] >= 10)){
                $prussianWin = true;
            }
            if($prussianWin && $turn > 12 && $turn <= 15){
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
            }
            if(!$prussianWin && $turn > 15){
                $this->winner = AUSTRIAN_FORCE;
                $gameRules->flashMessages[] = "Austrians Win";
            }
            if($prussianWin && $turn <= 12){
                $this->winner = PRUSSIAN_FORCE;
                $msg = "Prussian Win 60 On or before turn 12";
                $gameRules->flashMessages[] = $msg;
            }
            if($austrianWin || $prussianWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
