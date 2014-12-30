<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include "victoryCore.php";
class hastenbeckVictoryCore extends victoryCore
{

    function __construct($data)
    {
        if($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = array(0, 20, 0);
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
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[ALLIED_FORCE]  += 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>+5 Allied vp</span>";
            }
            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[ALLIED_FORCE]  -= 5;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>-5 Allied vp</span>";
            }
        }
        if(in_array($mapHexName,$battle->specialHexB)){
            $vp = 5;

            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>+$vp French vp</span>";
            }
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  -= $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>-$vp French vp</span>";
            }
        }
        if(in_array($mapHexName,$battle->specialHexC)){
            $vp = 5;

            if ($forceId == FRENCH_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  += $vp;
                $this->victoryPoints[ALLIED_FORCE]  -= $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>+$vp French vp</span>";
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='austrian'>-$vp Allied vp</span>";
            }
            if ($forceId == ALLIED_FORCE) {
                $this->victoryPoints[FRENCH_FORCE]  -= $vp;
                $this->victoryPoints[ALLIED_FORCE]  += $vp;
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>-$vp French vp</span>";
                $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>+$vp Allied vp</span>";
            }
        }
    }

    protected function checkVictory($attackingId,$battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $frenchWin = $angloMalplaquet =  $angloCities = $angloWing = false;

        if(!$this->gameOver){
            $specialHexes = $battle->mapData->specialHexes;
            if($attackingId == ANGLO_FORCE){
                $otherCities = $battle->loc;
                $frenchCities = 0;
                if($specialHexes->$malplaquet == FRENCH_FORCE){
                    foreach($otherCities as $city){
                        if($specialHexes->$city == FRENCH_FORCE){
                            $frenchCities++;
                        }
                    }
                }
            }
            if($this->victoryPoints[ANGLO_FORCE] >= 45){
                $angloWin = true;
            }
            if($frenchCities >= 3 && ($this->victoryPoints[FRENCH_FORCE] >= 60) && $turn <= 10){
                $frenchWin = true;
            }
            if($turn == $gameRules->maxTurn+1){
                if($angloWin && !$frenchWin){
                }
                if($frenchWin && !$angloWin){
                }
                if($frenchWin && $angloWin){
                    $this->winner = 0;
                    $angloWin = $frenchWin = false;
                    $gameRules->flashMessages[] = "Tie Game";
                    $gameRules->flashMessages[] = "Game Over";
                    $this->gameOver = true;
                    return true;
                }
                if(!$angloWin && !$frenchWin){
                    $angloWin = true;
                }
            }


            if($angloWin){
                $this->winner = ANGLO_FORCE;
                $gameRules->flashMessages[] = "Allies Win";
            }
            if($frenchWin){
                $this->winner = FRENCH_FORCE;
                $msg = "French Win Allies hold no cities";
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
