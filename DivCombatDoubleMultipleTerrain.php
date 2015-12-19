<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 11:01 AM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
trait DivCombatDoubleMultipleTerrain
{
    function setCombatIndex($defenderId)
    {
        $combatLog = "";

        $battle = Battle::getBattle();
        $combatRules = $battle->combatRules;
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
        $isTown = $isHill = $isForest = $isSwamp = $attackerIsSunkenRoad = $isRedoubt = $isElevated = false;

        $totalDefense = 1;
        $terrain = $battle->terrain;
        $defCombatLog = "";
        foreach ($defenders as $defId => $defender) {
            $hexagon = $battle->force->units[$defId]->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $thisHex = [];
            $thisLog = "";
            $hexName = $hexagon->name;
            if($battle->mapData->getMapSymbol($hexagon->name,'westwall')){
                $thisHex['forta'] = 2;
                $thisLog .= "2x defend in westwall ";
            }
            if($terrain->terrainIs($hexpart, 'roughone'))
            {
                $thisHex['roughone'] = 2;
                $thisLog .= "2x defend in rough ";
            }
            if($terrain->terrainIs($hexpart, 'roughtwo'))
            {
                $thisHex['roughtwo'] = 3;
                $thisLog .= "3x defend in mountain ";
            }
            if($battle->combatRules->allAreAttackingAcrossRiver($defId))
            {
                $thisHex['river'] = 2;
                $thisLog .= "2x defend behind river ";
            }
            $multiple = count($thisHex);
            $defense = array_sum($thisHex);
            if($multiple > 1){
                $defense--;
            }
            if($defense > $totalDefense){
                $totalDefense = $defense;
                $defCombatLog = $thisLog."<br>total def ${defense}x";
            }
        }

        $attackStrength = 0;
        $combatLog .= "Attackers<br>";

        foreach ($combats->attackers as $attackerId => $v) {
            $unit = $force->units[$attackerId];
            $combatLog .= $unit->strength." ".$unit->class."<br>";
            $strength = $unit->strength;
            $attackStrength += $strength;
        }
        $defenseStrength = 0;
        $combatLog .= " = $attackStrength<br>Defenders<br> ";

        $unitDefenseStrength = 0;
        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= $unit->defStrength. " " .$unit->class." ";

            $unitDefenseStrength += $unit->defStrength;
            $defenseStrength += $unit->defStrength * $totalDefense;
            $combatLog .= "<br>";
        }
        $combatLog .= "= $unitDefenseStrength<br>";
        $combatLog .= $defCombatLog;

        $combatLog .= " = $defenseStrength";
        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }


        /* @var $combatRules CombatRules */
//        $terrainCombatEffect = $combatRules->getDefenderTerrainCombatEffect($defenderId);

//        $combatIndex -= $terrainCombatEffect;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = 0;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}
