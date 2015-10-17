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

class NomonhanCombatResultsTable extends CombatResultsTable
{

    function setCombatIndex($defenderId)
    {
        $combatLog = "";

        $battle = Battle::getBattle();
        $combatRules = $battle->combatRules;
        $terrain = $battle->terrain;
        $combats = $battle->combatRules->combats->$defenderId;
        /* @var Force $force */
        $force = $battle->force;
        $hexagon = $battle->force->units[$defenderId]->hexagon;
        $hexpart = new Hexpart();
        $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

        if (count((array)$combats->attackers) == 0) {
            $combats->index = null;
            $combats->attackStrength = null;
            $combats->defenseStrength = null;
            $combats->terrainCombatEffect = null;
            return;
        }

        $defenders = $combats->defenders;
        $attackStrength = 0;

//        $attackStrength = $this->force->getAttackerStrength($combats->attackers);
        $defenseStrength = 0;
        $defMarsh = false;
        $defArt = false;
        $combatLog .= "Defenders<br> ";

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $unitStr = $unit->defStrength;
            $combatLog .= $unitStr. " " .$unit->class." ";
            $unit = $force->units[$defId];
            $unitHex = $unit->hexagon;
            if($unit->class == "artillery"){
                $defArt = true;
            }
            if($terrain->terrainIsHex($unitHex->name, "rough") || $terrain->terrainIsHex($unitHex->name, "hills")){
                $unitStr *= 2;
            }
            if($terrain->terrainIsHex($unitHex->name, "marsh")){
                $defMarsh = true;
                if($unit->class == "inf" || $unit->class == "cavalry"){
                    $unitStr *= 2;
                }
            }
            $defenseStrength += $unitStr;
        }

        $defHex = $unitHex;
        $combatLog .= "<br>Attackers<br>";

        foreach ($combats->attackers as $id => $v) {

            $unit = $force->units[$id];
            $unitStr = $unit->strength;
            $combatLog .= $unit->strength." ".$unit->class;

            if($unit->class != 'artillery' && $terrain->terrainIsHexSide($defHex->name,$unit->hexagon->name, "blocked")){
                $unitStr = 0;
            }
            if($unit->class != 'artillery' && ($terrain->terrainIsHexSide($defHex->name,$unit->hexagon->name, "river") || $terrain->terrainIsHexSide($defHex->name,$unit->hexagon->name, "ford"))){
                $unitStr /= 2;
            }
            if($defMarsh && $force->units[$id]->class == 'mech'){
                $unitStr /= 2;
            }
            if($defArt && $force->units[$id]->class != 'artillery'){
                $unitStr *= 2;
            }
            $attackStrength += $unitStr;
        }

        $terrainCombatEffect = 0;

        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }



        $combatIndex -= $terrainCombatEffect;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
//    $this->force->storeCombatIndex($defenderId, $combatIndex);
    }

}
