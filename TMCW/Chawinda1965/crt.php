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

require_once "ModernCombatResultsTable.php";

class CombatResultsTable extends ModernCombatResultsTable
{
    use divCombatHalfDoubleTerrain;
    public $aggressorId = INDIAN_FORCE;

    function __construct(){
        $this->combatResultsHeader = array("1:1","2:1","3:1","4:1","5:1","6:1");
        $this->combatResultsTable = array(
            array(AL, AL, AR, DR, DR, DR),
            array(AL, AR, DR, DR, DE, DE),
            array(AR, AR, DR, DE, DE, DE),
            array(AR, DR, DR, DR, DE, DE),
            array(DR, DR, EX0, EX0, DE, DE),
            array(NE, EX0, EX0, EX0, EX0, DE),
        );

        $this->combatResultsTableDetermined = array(
            array(AL,   AL,   AR,   DR,    DE,  DE),
            array(AL,   AR,   DR,   DE,    DE,  DE),
            array(AR,   DR,   DE,   DE,    DE,  DE),
            array(AR,   EX03, EX02, EX02,  DE,  DE),
            array(EX03, EX02, EX0,  EX0,   EX0, DE),
            array(EX02, EX0,  EX0,  EX0,   EX0, EX0),
        );

        $this->combatOddsTable = array(
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
        );

        $this->combatIndexCount = 6;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
        $this->combatResultCount = 5;

        $this->setCombatOddsTable();
    }

    function getCombatResults($Die, $index, $combat)
    {
        if($combat->useDetermined){
            return $this->combatResultsTableDetermined[$Die][$index];
        }
        return $this->combatResultsTable[$Die][$index];
    }
}