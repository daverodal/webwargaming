<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
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
        $this->scoreKills($unit, $mult);
    }

    public function specialHexChange($args)
    {
        $battle = Battle::getBattle();

        list($mapHexName, $forceId) = $args;
        if ($forceId == PRUSSIAN_FORCE) {
            $this->victoryPoints[PRUSSIAN_FORCE]  += 5;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='prussian'>+5 Prussian vp</span>";
        }
        if ($forceId == RUSSIAN_FORCE) {
            $this->victoryPoints[PRUSSIAN_FORCE]  -= 5;
            $battle->mapData->specialHexesVictory->$mapHexName = "<span class='russian'>-5 Prussian vp</span>";
        }
    }
    protected function checkVictory($attackingId,$battle){
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $prussianWin = $russianWin = false;
            if(($this->victoryPoints[RUSSIAN_FORCE] >= 62) && ($this->victoryPoints[RUSSIAN_FORCE] - ($this->victoryPoints[PRUSSIAN_FORCE]) >= 10)){
                $russianWin = true;
            }
            if(($this->victoryPoints[PRUSSIAN_FORCE] >= 62) && ($this->victoryPoints[PRUSSIAN_FORCE] - $this->victoryPoints[RUSSIAN_FORCE] >= 10)){
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

                return true;
            }
        }
        return false;
    }
}
