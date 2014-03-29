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
    }
    protected function checkVictory($attackingId,$battle){
        echo "Attack $attackingId ";
        var_dump($this->gameOver);
        $gameRules = $battle->gameRules;
        var_dump($battle->mapData->specialHexes);
        $turn = $gameRules->turn;
        $frenchWin = $angloMalplaquet =  $angloCities = $angloWing = false;

        if(!$this->gameOver){
            $specialHexes = $battle->mapData->specialHexes;
            if($attackingId == ANGLO_FORCE){
                echo "weeee ";
                $malplaquet = $battle->malplaquet[0];
                var_dump($malplaquet);
                $otherCities = $battle->otherCities;
                if($specialHexes->$malplaquet == ANGLO_FORCE){
                    $angloMalplaquet = true;
                    echo "Got Mal $malplaquet ";
                    foreach($otherCities as $city){
                        if($specialHexes->$city == ANGLO_FORCE){
                            $angloCities = true;
                        }
                    }
                }
            }
            if($angloCities && ($this->victoryPoints[ANGLO_FORCE] - ($this->victoryPoints[FRENCH_FORCE]) > 10)){
                $angloWin = true;
            }
            if($turn == $gameRules->maxTurn+1){
                echo "Turn $turn angloCities $angloCities mal $angloMalplaquet";
                if(!$angloWin){
                    if($angloCities === false && $angloMalplaquet === false){
                        $frenchWin = true;
                    }
                }
                if(!$frenchWin && !$angloWin){
                    $this->winner = 0;
                    $angloWin = $frenchWin = false;
                    $gameRules->flashMessages[] = "Tie Game";
                    $gameRules->flashMessages[] = "Game Over";
                    $this->gameOver = true;
                    return true;
                }            }


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
