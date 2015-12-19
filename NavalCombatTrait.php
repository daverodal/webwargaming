<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 10:59 AM
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
trait NavalCombatTrait
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
        $attackStrength = 0;
        $combatLog .= "Attackers<br>";

        $attackers = 0;
        foreach ($combats->attackers as $id => $v) {
            $attackers++;
            $unit = $force->units[$id];
            $los = new Los();

            $los->setOrigin($force->getUnitHexagon($id));
            $los->setEndPoint($force->getUnitHexagon($defenderId));
            $range = (int)$los->getRange();
            $strength = $unit->strength;

            $combatLog .= $strength." ".$unit->class;


            if($battle->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $battle->gameRules->phase == RED_TORP_COMBAT_PHASE){
                if($range > (2* $unit->range)){
                    $strength /= 3;
                    $combatLog .= " One third for extended Range $strength";
                }elseif($range > $unit->range) {
                    $strength /= 2;
                    $combatLog .= " Halved for extended Range $strength";
                }
                if($range === 1){
                    $strength *= 2;
                    $combatLog .= " Doubled for close Range $strength";
                }
                if($range === 2 && $unit->nationality === 'ijn'){
                    $strength *= 2;
                    $combatLog .= " Doubled for close Range $strength";
                }
            }else{
                if($range > $unit->range) {
                    $strength /= 2;
                    $combatLog .= " Halved for extended Range $strength";
                }
                if($range <= ($unit->range/2)){
                    if($range <= 2){
                        $strength *= 3;
                        $combatLog .= " Tripled for range 2 or less $strength";
                    }else{
                        $strength *= 2;
                        $combatLog .= " Doubled for Range less than half normal $strength";
                    }

                }
            }
            $combatLog .= "<br>";

            $attackStrength += $strength;

        }
        if($attackers > 1  && !($battle->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $battle->gameRules->phase == RED_TORP_COMBAT_PHASE)){
            $beforeStr = $attackStrength;
            $attackStrength /= 2;
            $combatLog .= "$beforeStr Attack strength halved fore multi ship attack $attackStrength<br>";

        }
        $defenseStrength = 0;
        $combatLog .= " = $attackStrength<br>Defenders<br> ";

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= " " .$unit->class." ";

            $defenseStrength += $unit->defStrength;
            $combatLog .= "<br>";
        }
        if($battle->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $battle->gameRules->phase == RED_TORP_COMBAT_PHASE) {
            $combats->unitDefenseStrength = $defenseStrength;
            $combats->oneHitCol = $this->getOneHitColumn($defenseStrength);
            $combats->twoHitCol = $this->getTwoHitColumn($defenseStrength);
            $defenseStrength = $unit->maxMove + 1;
        }
        $combatLog .= " = $defenseStrength";
        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }


        /* @var $combatRules CombatRules */


        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = 0;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}
