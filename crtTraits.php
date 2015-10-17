<?php
/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 8/24/13
 * Time: 5:44 PM
 * To change this template use File | Settings | File Templates.
 */
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version->

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */

trait diffCombatShiftTerrain
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

        foreach ($combats->attackers as $id => $v) {
            $unit = $force->units[$id];
            $combatLog .= $unit->strength." ".$unit->class." ";

            $attackStrength += $unit->strength;

        }
        $defenseStrength = 0;
        $combatLog .= " = $attackStrength<br>Defenders<br> ";

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= $unit->strength. " " .$unit->class." ";

            $defenseStrength += $unit->defStrength;
            $combatLog .= "<br>";
        }
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
//        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->terrainCombatEffect = 0;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}


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

trait divCombatShiftTerrain
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

        foreach ($combats->attackers as $id => $v) {
            $unit = $force->units[$id];
            $strength = $unit->strength;
            $combatLog .= $strength." ".$unit->class;

            $attackStrength += $strength;

        }
        $defenseStrength = 0;
        $combatLog .= " = $attackStrength<br>Defenders<br> ";

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= $unit->strength. " " .$unit->class." ";

            $defenseStrength += $unit->defStrength;
            $combatLog .= "<br>";
        }
        $combatLog .= " = $defenseStrength";
        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }


        /* @var $combatRules CombatRules */
        $terrainCombatEffect = $combatRules->getDefenderTerrainCombatEffect($defenderId);

        $combatIndex -= $terrainCombatEffect;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}

trait divCombatHalfDoubleTerrain
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

        foreach ($defenders as $defId => $defender) {
            $hexagon = $battle->force->units[$defId]->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isTown |= $battle->terrain->terrainIs($hexpart, 'town');
            $isHill |= $battle->terrain->terrainIs($hexpart, 'hill');
            $isForest |= $battle->terrain->terrainIs($hexpart, 'forest');
            $isSwamp |= $battle->terrain->terrainIs($hexpart, 'swamp');
            $isElevated |= $battle->terrain->terrainIs($hexpart, 'elevation');
        }
        $isClear = true;
        if ($isTown || $isForest || $isHill || $isSwamp) {
            $isClear = false;
        }

        $attackStrength = 0;
        $combatLog .= "Attackers<br>";

        foreach ($combats->attackers as $attackerId => $v) {
            $unit = $force->units[$attackerId];
            $combatLog .= $unit->strength." ".$unit->class;


            $acrossRiver = false;
            foreach ($defenders as $defId => $defender) {
                if ($battle->combatRules->thisAttackAcrossRiver($defId, $attackerId)) {
                    $combatLog  .= " attack across river or wadi ";
                    $acrossRiver = true;
                }
            }

            $strength = $unit->strength;
            if($acrossRiver){
                $strength /= 2;
            }
            $attackStrength += $strength;
        }
        $defenseStrength = 0;
        $combatLog .= " = $attackStrength<br>Defenders<br> ";

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= $unit->strength. " " .$unit->class." ";

            $defenseStrength += $unit->defStrength;
            $combatLog .= "<br>";
        }
        if($isTown){
            $defenseStrength *= 2;
        }
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
        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}

trait divCombatDoubleMultipleTerrain
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
            if($terrain->terrainIs($hexpart, 'forta'))
            {
                $thisHex['forta'] = 2;
                $thisLog .= "2x defend in fort ";
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


trait divMCWCombatShiftTerrain
{
    function setCombatIndex($defenderId)
    {
        $combatLog = "";
        $battle = Battle::getBattle();
        $combatRules = $battle->combatRules;
        $combats = $battle->combatRules->combats->$defenderId;
        /* @var Force $force */
        $force = $battle->force;
        $attackingForceId = $force->attackingForceId;
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

        $isFortA = $isFortB = $isHeavy = $isShock = $isMountain = $isMountainInf = $isTown = $isHill = $isForest = $isSwamp = $attackerIsSunkenRoad = $isRedoubt = false;

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $hexagon = $unit->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isMountain |= $battle->terrain->terrainIs($hexpart, 'mountain');
            $isFortA = $battle->terrain->terrainIs($hexpart, 'forta');
            $isFortB = $battle->terrain->terrainIs($hexpart, 'fortb');
            if(($isFortB || $isFortA) && $unit->class == "heavy"){
                $isHeavy = true;
            }

        }
        $combatLog .= "Attackers<br>";

        foreach ($combats->attackers as $id => $v) {
            $unit = $force->units[$id];
            $combatLog .= $unit->strength." ".$unit->class;

            if($unit->class == "mountain"){
                $combatLog .= "+1 shift Mountain Inf in Mountain";
                $isMountainInf = true;
            }
            if($unit->class == "shock"){
                $combatLog .= "+1 shift Attacking with Shock Troops";
                $isShock = true;
            }
            $attackStrength += $unit->strength;
            $combatLog .= "<br>";
        }
        $combatLog .= "= $attackStrength<br>Defenders<br> ";

        $defenseStrength = 0;
        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= $unit->defStrength. " " .$unit->class." ";
            $combatLog .= "<br>";
            $defenseStrength += $unit->defStrength;
        }
        if($isHeavy){
            $combatLog .= "+1 Strength for Heavy Inf in Fortified";
            $defenseStrength++;
        }
        $combatLog .= " = $defenseStrength";

        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }


        /* @var $combatRules CombatRules */
        $terrainCombatEffect = $combatRules->getDefenderTerrainCombatEffect($defenderId);

        if($isMountainInf && $isMountain){
            /* Mountain Inf helps combat agains Mountain hexes */
            $terrainCombatEffect--;
        }
        /* FortB trumps FortA in multi defender attacks */
        /* TODO: FORCED ID SHOULD NOT BE HERE!!! */
        if(isset($this->aggressorId) && $attackingForceId !== $this->aggressorId){

        }else{
            global $force_name;
            $player = $force_name[$attackingForceId];
            if($isFortB){
                $combatLog .= "<br>Shift 2 left for $player attacking into Fortified B";
                $terrainCombatEffect += 2;
            }else if($isFortA){
                $combatLog .= "<br>Shift 1 left for $player attacking into Fortified A";
                $terrainCombatEffect += 1;
            }
        }

        $combatIndex -= $terrainCombatEffect;

        if($isShock && $combatIndex >= 0){
            $combatIndex++;
        }
        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->index = $combatIndex;
        $combats->combatLog = $combatLog;
    }
}