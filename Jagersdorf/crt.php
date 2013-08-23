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
    
    function __construct(){
        $this->combatResultsHeader = array("1:5","1:4","1:3","1:2","1:1","2:1","3:1","4:1","5:1","6:1");
	    $this->combatResultsTable = array(
            array(AE, AR, AR, AR, DR, DR, DR, DE, DE, DE),
            array(AE, AE, AR, AR, DR, DR, DR, DE, DE, DE),
            array(AE, AE, AE, AR, DR, DR, DR, DR, DE, DE),
            array(AE, AE, AE, AR, AR, DR, DR, DR, DE, DE),
            array(AE, AE, AE, AR, AR, EX, DR, EX, EX, DE),
            array(AE, AE, AE, AE, AR, AR, EX, EX, EX, DE),
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

        function getCombatResults($Die, $index)
        {
            return $this->combatResultsTable[$Die][$index];
        }

    function getCombatDisplay(){
        return $this->combatResultsHeader;
    }

    public function setCombatIndex($defenderId){

        /* @var Jagersdorf $battle */
        $battle = Battle::getBattle();
        $combats = $battle->combatRules->combats->$defenderId;
        $hexagon = $battle->force->units[$defenderId]->hexagon;
        $hexpart = new Hexpart();
        $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

        $isClear = $battle->terrain->terrainIs($hexpart,'clear');

        $defenders = $combats->defenders;

        $attackStrength = $battle->force->getAttackerStrength($combats->attackers);
        $defenseStrength = 0;
        foreach($defenders as $defId => $defender){
            $defenseStrength += $battle->force->getDefenderStrength($defId);
        }

        $combinedArms = array();

        if($attackStrength >= $defenseStrength){
            foreach($combats->attackers as $attackerId => $attacker){
                $combinedArms[$battle->force->units[$attackerId]->class]++;
            }
            if(!$isClear){
                unset($combinedArms['cavalry']);
            }
        }

        $armsShift = count($combinedArms) - 1;
        if($armsShift < 0){
            $armsShift = 0;
        }


        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }

        $terrainCombatEffect = $battle->combatRules->getDefenderTerrainCombatEffect($defenderId);

        $combatIndex -= $terrainCombatEffect;
        $combatIndex += $armsShift;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $terrainCombatEffect + $armsShift;
        $combats->index = $combatIndex;
    }

    function getCombatIndex($attackStrength, $defenseStrength){
        if($attackStrength >= $defenseStrength){
            $combatIndex = floor($attackStrength / $defenseStrength)+3;
        }else{
            $combatIndex = 5 - floor($defenseStrength /$attackStrength );
        }
        return $combatIndex;
    }
    function setCombatOddsTable()
    {
        $odds = array();

    //    var Die;
    //    var combatIndex;
    //    var combatResultIndex;
    //    var numerator;
    //    var denominator;
    //    var percent;
    //    var intPercent;

        for ($combatIndex = 0; $combatIndex < $this->combatIndexCount; $combatIndex++)
        {

            $odds[0] = 0;
            $odds[1] = 0;
            $odds[2] = 0;
            $odds[3] = 0;
            $odds[4] = 0;

            for( $Die = 0; $Die < $this->dieSideCount; $Die++ )
            {
                $combatResultIndex = $this->combatResultsTable[$Die][$combatIndex];
                $odds[$combatResultIndex] = $odds[$combatResultIndex] + 1;
            }

            $list = "";

            $list += $odds[0] + ", ";
            $list += $odds[1] + ", ";
            $list += $odds[2] + ", ";
            $list += $odds[3] + ", ";
            $list += $odds[4];

            for( $combatResultIndex = 0; $combatResultIndex < $this->combatResultCount; $combatResultIndex++ )
            {
                $numerator = $odds[$combatResultIndex];
                $denominator = $this->dieSideCount;
                $percent = 100 * ($numerator/$denominator);
                $intPercent = (int)floor($percent);
                $this->combatOddsTable[$combatResultIndex][$combatIndex] = $intPercent;
            }
       }
    }

    function getCombatOddsList($combatIndex)
    {
        global $results_name;
       $combatOddsList = "";
       //  combatOddsList  += "combat differential: " + combatIndex;

    //    var i;
        for ( $i = 0; $i < $this->combatResultCount; $i++ )
        {
            //combatOddsList += "<br />";
            $combatOddsList .= $results_name[$i];
            $combatOddsList .= ":";
            $combatOddsList .= $this->combatOddsTable[$i][$combatIndex];
            $combatOddsList .= "% ";
        }

        return $combatOddsList;
    }

}
