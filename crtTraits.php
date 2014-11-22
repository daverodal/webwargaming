<?php
/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 8/24/13
 * Time: 5:44 PM
 * To change this template use File | Settings | File Templates.
 */

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
            $combatLog .= $unit->strength." ".$unit->class;

            $attackStrength += $unit->strength;

        }
        $defenseStrength = 0;
        $combatLog .= " = $attackStrength<br>Defenders<br> ";

        foreach ($defenders as $defId => $defender) {
            $unit = $battle->force->units[$defId];
            $combatLog .= $unit->strength. " " .$unit->class." ";

            $defenseStrength += $force->getDefenderStrength($defId);
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

            $defenseStrength += $force->getDefenderStrength($defId);
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
        $terrainCombatEffect = $combatRules->getDefenderTerrainCombatEffect($defenderId);

        $combatIndex -= $terrainCombatEffect;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $terrainCombatEffect;
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
            $combatLog .= $unit->strength. " " .$unit->class." ";
            $combatLog .= "<br>";
            $defenseStrength += $force->getDefenderStrength($defId);
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