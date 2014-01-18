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

    public function preStartMovingUnit($arg)
    {
        $unit = $arg[0];
        $battle = Battle::getBattle();
        if ($unit->class === 'artillery') {
            $battle->moveRules->oneHex = false;
        } else {
            $battle->moveRules->oneHex = true;
        }
    }

//    public function specialHexChange($args)
//    {
//        $battle = Battle::getBattle();
//
//        list($mapHexName, $forceId) = $args;
//        if ($forceId == PRUSSIAN_FORCE) {
//            $this->victoryPoints[PRUSSIAN_FORCE]  += 5;
//            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>+5 Prussian vp</span>";
//        }
//        if ($forceId == RUSSIAN_FORCE) {
//            $this->victoryPoints[PRUSSIAN_FORCE]  -= 5;
//            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='russian'>-5 Prussian vp</span>";
//        }
//    }
    protected function checkVictory($attackingId,$battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $prussianWin = $russianWin = false;
            if(($this->victoryPoints[RUSSIAN_FORCE] > 62) && ($this->victoryPoints[RUSSIAN_FORCE] - ($this->victoryPoints[PRUSSIAN_FORCE]) > 10)){
                $russianWin = true;
            }
            if(($this->victoryPoints[PRUSSIAN_FORCE] > 62) && ($this->victoryPoints[PRUSSIAN_FORCE] - $this->victoryPoints[RUSSIAN_FORCE] > 10)){
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
                $msg = "Prussian Win 62 or more VP's";
                $gameRules->flashMessages[] = $msg;
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
}
