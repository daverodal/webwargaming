<?php
namespace SPI\ClashOverCrude;
// crt.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

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

class CombatResultsTable extends \SPI\ModernCombatResultsTable
{
    use \DivCombatShiftTerrain;


    public $aggressorId = EASTERN_FORCE;

    function __construct(){
        $this->combatResultsHeader = array("-1","0","+1","+2","+3","+4","+6","+8","+12","+16", "+20");
        $this->crts = new \stdClass();
        $this->crts->normal = array(
            array(DR, DR, DR, DR, DR, DE, DE, DE, DE, DE, DE),
            array(AR, DR, DR, DR, DR, DR, DE, DE, DE, DE, DE),
            array(AR, AR, DR, DR, DR, DR, DR, DE, DE, DE, DE),
            array(AR, AR, AR, DR, DR, DR, DR, DR, DE, DE, DE),
            array(AE, AR, AR, AR, DR, DR, DR, DR, DR, DE, DE),
            array(AE, AE, AR, AR, AR, DR, DR, DR, DR, DR, DE),
        );

        $this->combatOddsTable = array(
            array(),
            array(),
            array(),
            array(),
            array(),
            array()
        );

        $this->combatIndexCount = 11;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
        $this->combatResultCount = 10;

        $this->setCombatOddsTable();
    }

    function getCombatIndex($attackStrength, $defenseStrength)
    {
        $difference = $attackStrength - $defenseStrength;

        if($difference < -1 ){
            $difference = -1;
        }
        if($difference  > 20){
            $difference = 20;
        }
        if($difference < 4){
            return $difference + 1;
        }
        if($difference >= 4 && $difference < 6){
            return 5;
        }
        if($difference >= 6 && $difference < 8){
            return 6;
        }
        if($difference >= 8 && $difference < 12){
            return 7;
        }
        if($difference >= 12 && $difference < 16){
            return 8;
        }
        if($difference >= 16 && $difference < 20){
            return 9;
        }
        return 10;
    }


}
