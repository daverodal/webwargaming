// crt.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

function CombatResultsTable()
{
    	var combatIndexCount;
    	var maxCombatIndex;
    	var dieSideCount;
    	var dieMaxValue;
    	var combatResultCount;

    	var combatResultsTable;
    	var combatOddsTable;
    	//     combatIndexeCount is 6; maxCombatIndex = 5
	//     index is 0 to 5;  dieSidesCount = 6

	this.combatResultsTable = new Array(
    new Array(DR, DR, DR, DE, DE, DE),   
    new Array(NR, DR, DR, DR, DE, DE),   	
    new Array(NR, NR, DR, DR, DR, DE),   
    new Array(AR, NR, NR, DR, DR, DR),   
    new Array(AR, AR, NR, NR, DR, DR),   
    new Array(AE, AR, AR, NR, DR, DR)    
  );

	this.combatOddsTable = new Array(
    new Array(),
    new Array(),
    new Array(),
    new Array(),
    new Array(),
    new Array()
  );

    this.combatIndexCount = 6;
    this.maxCombatIndex = this.combatIndexCount - 1;
    this.dieSideCount = 6;
    this.combatResultCount = 5;

    this.setCombatOddsTable(); 
}

CombatResultsTable.prototype.getCombatResults = function(die, index)
{
	return this.combatResultsTable[die][index];
}

CombatResultsTable.prototype.setCombatOddsTable = function()
{
    var odds = new Array();
    
    var die;
    var combatIndex;
    var combatResultIndex;
    var numerator;
    var denominator;
    var percent;
    var intPercent;

    for (combatIndex = 0; combatIndex < this.combatIndexCount; combatIndex++)
    {

        odds[0] = 0;
        odds[1] = 0;
        odds[2] = 0;
        odds[3] = 0;
        odds[4] = 0;
   
        for( die = 0; die < this.dieSideCount; die++ ) 
        {
            combatResultIndex = this.combatResultsTable[die][combatIndex];
            odds[combatResultIndex] = odds[combatResultIndex] + 1;
        }

        var list = ""

        list += odds[0] + ", ";
        list += odds[1] + ", ";
        list += odds[2] + ", ";
        list += odds[3] + ", ";
        list += odds[4];
            
        for( combatResultIndex = 0; combatResultIndex < this.combatResultCount; combatResultIndex++ )
        {
            numerator = odds[combatResultIndex];
            denominator = this.dieSideCount;
            percent = 100 * (numerator/denominator);
            intPercent = Math.floor(percent);
            this.combatOddsTable[combatResultIndex][combatIndex] = intPercent;
        }
   }
}

CombatResultsTable.prototype.getCombatOddsList = function(combatIndex)
{
   var combatOddsList = "";
   //  combatOddsList  += "combat differential: " + combatIndex;

    var i;
    for ( i = 0; i < this.combatResultCount; i++ )
    {
        //combatOddsList += "<br />";
        combatOddsList += results_name[i];
        combatOddsList += ":";
        combatOddsList += this.combatOddsTable[i][combatIndex];        
        combatOddsList += "% ";
    }

	return combatOddsList;
}


