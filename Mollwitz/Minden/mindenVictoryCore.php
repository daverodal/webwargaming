<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include "victoryCore.php";
class mindenVictoryCore extends victoryCore
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
        if ($forceId == FRENCH_FORCE && in_array($mapHexName,$battle->angloSpecialHexes)) {
            $this->victoryPoints[FRENCH_FORCE]  += 5;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>+5 French vp</span>";
        }
        if ($forceId == ANGLO_FORCE && in_array($mapHexName,$battle->angloSpecialHexes)) {
            $this->victoryPoints[FRENCH_FORCE]  -= 5;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='french'>-5 French vp</span>";
        }
        if ($forceId == ANGLO_FORCE && in_array($mapHexName,$battle->frenchSpecialHexes)) {
            $this->victoryPoints[ANGLO_FORCE]  += 10;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>+10 Anglo Allied vp</span>";
        }
        if ($forceId == FRENCH_FORCE && in_array($mapHexName,$battle->frenchSpecialHexes)) {
            $this->victoryPoints[ANGLO_FORCE]  -= 10;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='anglo'>-10 Anglo Allied vp</span>";
        }
    }
    protected function checkVictory($attackingId,$battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $frenchWin = $angloWing = false;
            if(($this->victoryPoints[ANGLO_FORCE] > 50) && ($this->victoryPoints[ANGLO_FORCE] - ($this->victoryPoints[FRENCH_FORCE]) > 10)){
                $angloWin = true;
            }
            if(($this->victoryPoints[FRENCH_FORCE] > 50) && ($this->victoryPoints[FRENCH_FORCE] - $this->victoryPoints[ANGLO_FORCE] > 10)){
                $frenchWin = true;
            }
            if($frenchWin && $angloWin){
                $this->winner = 0;
                $angloWin = $frenchWin = false;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }

            if($angloWin){
                $this->winner = ANGLO_FORCE;
                $gameRules->flashMessages[] = "Anglo Allied Win over 50 points";
            }
            if($frenchWin){
                $this->winner = FRENCH_FORCE;
                $msg = "French Win over 50 points";
                $gameRules->flashMessages[] = $msg;
            }
            if($angloWin || $frenchWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
