<?php
// crt.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

class CombatResultsTable
{
    public $combatIndexCount;
    public $maxCombatIndex;
    public $dieSideCount;
    public $dieMaxValue;
    public $combatResultCount;

    public $combatResultsTable;
    public $combatResultsHeader;
    public $combatOddsTable;

    //     combatIndexeCount is 6; maxCombatIndex = 5
    //     index is 0 to 5;  dieSidesCount = 6

    function __construct()
    {
        $this->combatResultsHeader = array("1:4", "1:3", "1:2", "1:1", "1.5:1", "2:1", "3:1", "4:1", "5:1", "6:1");
        $this->combatResultsTable = array(
            array(AE, AE, AE, AR, AR, AR, DR, DR, DR, DR),
            array(AE, AE, AR, AR, AR, DR, DR, DR, DR, DE),
            array(AE, AE, AR, AR, DR, DR, DR, DR, DE, DE),
            array(AE, AE, NE, NE, DR, DR, EX, DE, DE, DE),
            array(AE, AR, NE, DR, DR, EX, DE, DE, DE, DE),
            array(AR, AR, DR, DR, EX, DE, DE, DE, DE, DE),
        );
        $this->combatResultsTableCav = array(
            array(AE, AE, AE, AR, AR, AR, DR, DR, DR, DR),
            array(AE, AE, AR, AR, AR, DR, DR, DR, DR, DR),
            array(AE, AE, AR, AR, DR, DR, DR, DR, DR, DR),
            array(AE, AE, NE, DR, DR, DR, DR, DR, DR, DR),
            array(AE, AR, NE, DR, DR, DR, DR, DR, DR, DR),
            array(AR, AR, DR, DR, DR, DR, DR, DR, DR, DR),
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
        $this->combatResultCount = 5;

        $this->setCombatOddsTable();
    }

    function getCombatResults($Die, $index, $combat)
    {
        if ($combat->useAlt) {
            return $this->combatResultsTableCav[$Die][$index];
        } else {
            return $this->combatResultsTable[$Die][$index];
        }
    }

    function getCombatDisplay()
    {
        return $this->combatResultsHeader;
    }

    public function setCombatIndex($defenderId)
    {

        $combatLog = "";
        /* @var JagCore $battle */
        $battle = Battle::getBattle();
        $scenario = $battle->scenario;
        $combats = $battle->combatRules->combats->$defenderId;

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

        $attackers = $combats->attackers;
        $attackStrength = 0;
        $attackersCav = false;
        $combinedArms = array();

        $combatLog .= "Attackers<br>";
        foreach ($attackers as $attackerId => $attacker) {
            $terrainReason = "";
            $unit = $battle->force->units[$attackerId];
            $unitStrength = $unit->strength;

            $hexagon = $unit->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

            $attackerIsSwamp = $battle->terrain->terrainIs($hexpart, 'swamp');
            $attackerIsSunkenRoad = $battle->terrain->terrainIs($hexpart, 'sunkenroad');

            if($attackerIsSwamp){
                $terrainReason .= "attacker is in swamp ";
            }
            if($attackerIsSunkenRoad){
                $terrainReason .= "attacker is in sunken road ";
            }

            $attackerIsElevated = $battle->terrain->terrainIs($hexpart, 'elevation');
            $attackUpHill = false;
            if($isElevated && !$attackerIsElevated){
                $terrainReason .= "attack uphill ";
                $attackUpHill = true;
            }

            $acrossRiver = false;
            foreach ($defenders as $defId => $defender) {
                if ($battle->combatRules->thisAttackAcrossRiver($defId, $attackerId)) {
                    $terrainReason .= "attack across river or wadi";
                    $acrossRiver = true;
                }
            }

            $acrossRedoubt = false;
            foreach ($defenders as $defId => $defender) {
                $isRedoubt = false;
                $hexagon = $battle->force->units[$defId]->hexagon;
                $hexpart = new Hexpart();
                $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
                $isRedoubt |= $battle->terrain->terrainIs($hexpart, 'redoubt');

                if ($isRedoubt && $battle->combatRules->thisAttackAcrossType($defId, $attackerId, "redoubt")) {
                    $acrossRedoubt = true;
                    $terrainReason .= "attack across redoubt ";
                }
            }

            if ($unit->class == "infantry") {
                $combinedArms[$battle->force->units[$attackerId]->class]++;
                $combatLog .= "$unitStrength Infantry ";
                if($scenario->jagersdorfCombat){
                    if ($unit->nationality == "Prussian" && $isClear && !$acrossRiver) {
                        $unitStrength++;
                        $combatLog .= "+1 for attack into clear ";
                    }
                    if ($unit->nationality == "Russian" && ($isTown || $isForest) && !$acrossRiver) {
                        $unitStrength++;
                        $combatLog .= "+1 for attack into town or forest ";
                    }
                }
                if (($unit->nationality == "Beluchi" || $unit->nationality == "Sikh") && ($isTown || $isForest) && !$acrossRiver) {
                    $unitStrength++;
                    $combatLog .= "+1 for attack into town or forest ";
                }
                if ($isSwamp || $attackerIsSwamp || $acrossRiver || $attackerIsSunkenRoad || $acrossRedoubt || $attackUpHill) {
                    if(!$terrainReason){
                        $terrainReason = " terrain ";
                    }
                    $combatLog .= "attacker halved for $terrainReason ";
                    $unitStrength /= 2;
                }
            }

            if ($unit->class == "cavalry") {
                $combatLog .= "$unitStrength Cavalry ";
                $attackersCav = true;

                if ($attackerIsSwamp || $acrossRiver || !$isClear || $attackerIsSunkenRoad || $acrossRedoubt || $attackUpHill) {
                    if(!$terrainReason){
                        $terrainReason = " terrain ";
                    }
                    $combatLog .= " halved for $terrainReason, loses combined arms bonus ";
                    $unitStrength /= 2;
                }else{
                    if($scenario->angloCavBonus && $unit->nationality == "AngloAllied"){
                        $unitStrength++;
                        $combatLog .= "+1 for attack into clear ";
                    }
                    if($unit->nationality != "Beluchi" && $unit->nationality != "Sikh"){
                        $combinedArms[$battle->force->units[$attackerId]->class]++;
                    }else{
                        $combatLog .= "no combined arms bonus for ".$unit->nationality." cavalry";
                    }
                }
            }
            if ($unit->class == "artillery" || $unit->class == "horseartillery") {
                $combatLog .= "$unitStrength ".ucfirst($unit->class)." ";
                if($isSwamp || $isRedoubt || $attackUpHill){
                    $unitStrength /= 2;
                    if(!$terrainReason){
                        $terrainReason = " terrain ";
                    }
                    $combatLog .= "attacker halved for $terrainReason ";
                }
                $class = $unit->class;
                if($class == 'horseartillery'){
                    $class = 'artillery';
                }
                if($unit->nationality != "Beluchi"){
                    $combinedArms[$class]++;
                }else{
                    $combatLog .= "no combined arms bonus for Beluchi";
                }
            }
            $combatLog .= "<br>";
            $attackStrength += $unitStrength;
        }
//        $combatLog .= "<br>";

        $defenseStrength = 0;
        $defendersAllCav = true;
        $combatLog .= " = $attackStrength<br>Defenders<br>";
        foreach ($defenders as $defId => $defender) {

            $unit = $battle->force->units[$defId];
            $class = $unit->class;
            $unitDefense = $battle->force->getDefenderStrength($defId);
            $combatLog .= "$unitDefense ".$unit->class." ";
            /* set to true to disable for not scenario->doubleArt */
            $clearHex = false;
            $artInClear = false;
            if($scenario->doubleArt){
                $notClearHex = false;
                $hexagon = $unit->hexagon;
                $hexpart = new Hexpart();
                $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
                $notClearHex |= $battle->terrain->terrainIs($hexpart, 'town');
                $notClearHex |= $battle->terrain->terrainIs($hexpart, 'hill');
                $notClearHex |= $battle->terrain->terrainIs($hexpart, 'forest');
                $notClearHex |= $battle->terrain->terrainIs($hexpart, 'swamp');
                $clearHex = !$notClearHex;
                if(($unit->class == 'artillery' || $unit->class == 'horseartillery') && $clearHex){
                    $combatLog .= "doubled for defending in clear";
                    $artInClear = true;
                }
            }
            if ($unit->class != 'cavalry') {
                $defendersAllCav = false;
            }
            if($scenario->jagersdorfCombat){
                if ($unit->forceId == PRUSSIAN_FORCE && $class == "infantry" && $isClear) {
                    $unitDefense += 1;
                    $combatLog .= "+1 for defending in clear ";
                }
                if ($unit->forceId == RUSSIAN_FORCE && $class == "infantry" && ($isTown || $isForest)) {
                    $unitDefense += 1;
                    $combatLog .= "+1 for defending in town or forest ";
                }
            }
            if (($unit->nationality == "Beluchi" || $unit->nationality == "Sikh") && $class == "infantry" && ($isTown || $isForest)) {
                $unitDefense++;
                $combatLog .= "+1 for defending into town or forest ";
            }

            $defenseStrength += $unitDefense * (($isTown && $class !== 'cavalry') || ($artInClear) || $isHill ? 2 : 1);
            $combatLog .= "<br>";
        }

        $combatLog .= " = $defenseStrength";
        $armsShift = 0;
        if ($attackStrength >= $defenseStrength) {
            $armsShift = count($combinedArms) - 1;
        }

        if ($armsShift < 0) {
            $armsShift = 0;
        }

        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        $combatIndex += $armsShift;

        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }

//        $terrainCombatEffect = $battle->combatRules->getDefenderTerrainCombatEffect($defenderId);

//        $combatIndex -= $terrainCombatEffect;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $armsShift;

        if($combats->pinCRT !== false){
            $pinIndex = $combats->pinCRT;
            if($combatIndex > $pinIndex){
                $combatLog .= "<br>Pinned to {$this->combatResultsHeader[$pinIndex]} ";
            }
        }
        $combats->index = $combatIndex;
        $combats->useAlt = false;
        if ($defendersAllCav && !$attackersCav) {
            $combats->useAlt = true;
            $combatLog .= "using cavalry table ";
        }
        $combats->combatLog = $combatLog;
    }

    function getCombatIndex($attackStrength, $defenseStrength)
    {
        $ratio = $attackStrength / $defenseStrength;
        if ($attackStrength >= $defenseStrength) {
            $combatIndex = floor($ratio) + 2;
            if ($ratio >= 1.5) {
                $combatIndex++;
            }
        } else {
            $combatIndex = 4 - ceil($defenseStrength / $attackStrength);
        }
        return $combatIndex;
    }

    function setCombatOddsTable()
    {
        return;
        $odds = array();

        //    var Die;
        //    var combatIndex;
        //    var combatResultIndex;
        //    var numerator;
        //    var denominator;
        //    var percent;
        //    var intPercent;

        for ($combatIndex = 0; $combatIndex < $this->combatIndexCount; $combatIndex++) {

            $odds[0] = 0;
            $odds[1] = 0;
            $odds[2] = 0;
            $odds[3] = 0;
            $odds[4] = 0;

            for ($Die = 0; $Die < $this->dieSideCount; $Die++) {
                $combatResultIndex = $this->combatResultsTable[$Die][$combatIndex];
                $odds[$combatResultIndex] = $odds[$combatResultIndex] + 1;
            }

            $list = "";

            $list += $odds[0] + ", ";
            $list += $odds[1] + ", ";
            $list += $odds[2] + ", ";
            $list += $odds[3] + ", ";
            $list += $odds[4];

            for ($combatResultIndex = 0; $combatResultIndex < $this->combatResultCount; $combatResultIndex++) {
                $numerator = $odds[$combatResultIndex];
                $denominator = $this->dieSideCount;
                $percent = 100 * ($numerator / $denominator);
                $intPercent = (int)floor($percent);
                $this->combatOddsTable[$combatResultIndex][$combatIndex] = $intPercent;
            }
        }
    }

    function getCombatOddsList($combatIndex)
    {
        die("sad");
        global $results_name;
        $combatOddsList = "";
        //  combatOddsList  += "combat differential: " + combatIndex;

        //    var i;
        for ($i = 0; $i < $this->combatResultCount; $i++) {
            //combatOddsList += "<br />";
            $combatOddsList .= $results_name[$i];
            $combatOddsList .= ":";
            $combatOddsList .= $this->combatOddsTable[$i][$combatIndex];
            $combatOddsList .= "% ";
        }

        return $combatOddsList;
    }

}
