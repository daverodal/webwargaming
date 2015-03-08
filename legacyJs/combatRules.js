// combatRules.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

function CombatRules(Force, Terrain)
{
	// Class references
    var force;
    var terrain;
    
    // local variables
    var crt;
    var currentCombatNumber;
    var maximumCombatNumberUsed;

    this.force = Force;
    this.terrain = Terrain;

    this.crt = new CombatResultsTable();
    this.currentCombatNumber = 0;
    this.maximumCombatNumberUsed = 0;

}

CombatRules.prototype.setupCombat = function( id ) {

    if (this.force.unitIsEnemy(id) == true)
    {
        // defender is already in combatRules, so make it currently selected
        if (this.force.unitIsInCombat(id) == true)
        {
            this.currentCombatNumber = this.force.getUnitCombatNumber(id);
        }
        else
        {
            this.maximumCombatNumberUsed++;
            this.currentCombatNumber = this.maximumCombatNumberUsed;

            this.force.setupDefender(id, this.currentCombatNumber);
        }
    }
    else
    // attacker
    {
        if (this.currentCombatNumber > 0)
        {
            var los = new Los();
            los.setOrigin(this.force.getUnitHexagon(id));
            los.setEndPoint(this.force.getCombatHexagon(this.currentCombatNumber));
            var range = los.getRange();
            if (range == 1)
            {
                if (this.force.unitIsAttacking(id) == true)
                {
                    this.force.undoAttackerSetup(id);
                    this.setCombatIndex(this.currentCombatNumber);
                }
                else
                {
                    this.force.setupAttacker(id, this.currentCombatNumber);
                    this.setCombatIndex(this.currentCombatNumber);
                }
            }
        }
    }
}

CombatRules.prototype.setupFireCombat = function( id ){
}

CombatRules.prototype.getDefenderTerrainCombatEffect = function(combatNumber)
{
    var terrainCombatEffect;
    
    terrainCombatEffect = this.terrain.getDefenderTerrainCombatEffect(this.force.getCombatHexagon(combatNumber));

    if (this.allAreAttackingAcrossRiver(combatNumber)) {

        terrainCombatEffect = this.terrain.getAllAreAttackingAcrossRiverCombatEffect();
    }
    
	return terrainCombatEffect;
}


CombatRules.prototype.setCombatIndex = function(combatNumber)
{
    var attackStrength = this.force.getAttackerStrength(combatNumber);
    var defenseStrength = this.force.getDefenderStrength(combatNumber);

    var combatIndex = attackStrength - defenseStrength;

    var terrainCombatEffect = this.getDefenderTerrainCombatEffect(combatNumber);

    combatIndex -= terrainCombatEffect;

    if (combatIndex < 1) combatIndex = 0;

    if (combatIndex >= this.crt.maxCombatIndex) {
        combatIndex = this.crt.maxCombatIndex;
    }

    this.force.storeCombatIndex(combatNumber, combatIndex);
}

CombatRules.prototype.resolveCombat = function( id ) {
 
    var combatResults;
    var die;
    var index;
	
    // Math.random yields number between 0 and 1
    //  6 * Math.random yields number between 0 and 6
    //  Math.floor gives lower integer, which is now 0,1,2,3,4,5

    die = Math.floor(this.crt.dieSideCount * Math.random());
    index = this.force.getUnitCombatIndex(id);
    combatResults = this.crt.getCombatResults(die, index);

    this.force.applyCRTresults(force.getUnitCombatNumber(id), combatResults, die);
}

CombatRules.prototype.resolveFireCombat = function( id ) {
}

CombatRules.prototype.allAreAttackingAcrossRiver = function(combatNumber) {

    var allAttackingAcrossRiver = true;

    var attackerHexagonList = new Array();
    attackerHexagonList = this.force.getAttackerHexagonList(combatNumber);

    var hexsideX, hexsideY;
    var defenderHexagon = this.force.getCombatHexagon(combatNumber);

    for (var i = 0; i < attackerHexagonList.length; i++) {

        hexsideX = (defenderHexagon.getX() + attackerHexagonList[i].getX()) / 2;
        hexsideY = (defenderHexagon.getY() + attackerHexagonList[i].getY()) / 2;
        
        var hexside = new Hexpart(hexsideX, hexsideY);
        
        if (this.terrain.terrainIs(hexside, "river") == false) {

            allAttackingAcrossRiver = false;
        }
    }
    
    return allAttackingAcrossRiver;
}

CombatRules.prototype.getCombatOddsList = function(combatIndex)
{
   return this.crt.getCombatOddsList(combatIndex);
}
