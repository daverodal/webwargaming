<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
include "victoryCore.php";
class dubba1843VictoryCore extends victoryCore
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
        if($unit->nationality == "British"){
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
        if ($forceId == BELUCHI_FORCE) {
            $this->victoryPoints[BELUCHI_FORCE]  += 15;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='beluchi'>+15 Beluchis  vp</span>";
        }
        if ($forceId == BRITISH_FORCE) {
            $this->victoryPoints[BELUCHI_FORCE]  -= 15;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='british'>-15 Beluchis  vp</span>";
        }
    }
    protected function checkVictory($attackingId,$battle){
        echo "Attack $attackingId ";
        var_dump($this->gameOver);
        $gameRules = $battle->gameRules;
        var_dump($battle->mapData->specialHexes);
        $turn = $gameRules->turn;
        $beluchiWin =  $britishWin = false;

        if(!$this->gameOver){
            $specialHexes = $battle->mapData->specialHexes;
            if($attackingId == BRITISH_FORCE){
//                $roadHex = $battle->roadHex[0];
//                $otherCities = $battle->otherCities;
//                if($specialHexes->$malplaquet == BRITISH_FORCE){
//                    $angloMalplaquet = true;
//                    echo "Got Mal $malplaquet ";
//                    foreach($otherCities as $city){
//                        if($specialHexes->$city == BRITISH_FORCE){
//                            $angloCities = true;
//                        }
//                    }
//                }
            }
            if(($this->victoryPoints[BRITISH_FORCE] >= 45)){
                $britishWin = true;
            }
            if(($this->victoryPoints[BELUCHI_FORCE] >= 40)){
                $beluchiWin = true;
            }
            if($turn == $gameRules->maxTurn+1){
                if(!$britishWin){
                        $beluchiWin = true;
                }
                if($beluchiWin && $britishWin){
                    $this->winner = 0;
                    $britishWin = $beluchiWin = false;
                    $gameRules->flashMessages[] = "Tie Game";
                    $gameRules->flashMessages[] = "Game Over";
                    $this->gameOver = true;
                    return true;
                }
            }


            if($britishWin){
                $this->winner = BRITISH_FORCE;
                $gameRules->flashMessages[] = "British Win";
            }
            if($beluchiWin){
                $this->winner = BELUCHI_FORCE;
                $msg = "Beluchi Win";
                $gameRules->flashMessages[] = $msg;
            }
            if($britishWin || $beluchiWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
}
