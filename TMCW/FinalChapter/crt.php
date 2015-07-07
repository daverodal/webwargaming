<?php
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

require_once "ModernCombatResultsTable.php";

class CombatResultsTable extends ModernCombatResultsTable
{
    use divCombatDoubleMultipleTerrain;


    public $aggressorId = EASTERN_FORCE;

    function __construct(){
        $this->combatResultsHeader = array("1:4","1:3","1:2","1:1","2:1","3:1","4:1","5:1","6:1","7:1");
        $this->crts = new stdClass();
        $this->crts->normal = array(
            array(AR, DR, DR, EX, EX, DE, DE, DE, DE, DE),
            array(AR, AR, DR, DR, EX, EX, DE, DE, DE, DE),
            array(AR, AR, AR, DR, DR, EX, EX, DE, DE, DE),
            array(AE, AR, AR, DR, DR, DR, EX, EX, DE, DE),
            array(AE, AE, AR, DR, DR, DR, DR, EX, EX, DE),
            array(AE, AE, AR, AR, DR, DR, DR, DR, EX, EX),
        );

        $this->combatOddsTable = array(
            array(),
            array(),
            array(),
            array(),
            array(),
            array()
        );

        $this->combatIndexCount = 10;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
        $this->combatResultCount = 9;

        $this->setCombatOddsTable();
    }

    function getCombatIndex($attackStrength, $defenseStrength)
    {
        $ratio = $attackStrength / $defenseStrength;
        if ($attackStrength >= $defenseStrength) {
            $combatIndex = floor($ratio) + 2;
        } else {
            $combatIndex = 4 - ceil($defenseStrength / $attackStrength);
        }
        return $combatIndex;
    }


}
