// terrain.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

function Terrain() {

	var maxTerrainX;
	var maxTerrainY;
	var terrainArray;
	var towns;
	var terrainFeatures;
	var reinforceZones;
	var allAreAttackingAcrossRiverCombatEffect;

	this.towns = new Array();
	this.terrainFeatures = new Array();
	this.reinforceZones = new Array();
	
	this.allAreAttackingAcrossRiverCombatEffect = 3;

	this.terrainArray = new Array(29);
		for(var i = 0; i < this.terrainArray.length; i++) {
		this.terrainArray[i] = new Array(16);
	}

	this.maxTerrainX = this.terrainArray[0].length;
	this.maxTerrainY = this.terrainArray.length;
	//alert( this.maxTerrainX + ", " + this.maxTerrainY );
	for( var y = 0; y < this.maxTerrainY; y++ )
	{
		for ( var x = 0; x < this.maxTerrainX; x++ )
		{
			this.terrainArray[y][x] = 1;
		}
	}
} 
 
function Town(townName, townHexagon) {

	var name, hexagon;

	this.name = townName;
	this.hexagon = townHexagon;
}

Terrain.prototype.addTown = function(name, hexagonName) 
{
	var hexagon = new Hexagon(hexagonName);	
	var town = new Town(name, hexagon);
	this.towns.push(town);
}

Terrain.prototype.getTownName = function(hexagon) {

	var townName = "";	
	for ( var i = 0; i < this.towns.length; i++ ) {
		
		if ( this.towns[i].hexagon.equals(hexagon) ) {

			townName += this.towns[i].name;
		}
	}
	return townName;
}

function TerrainFeature(terrainFeatureCode, terrainFeatureName, terrainFeatureDisplayName, terrainFeatureLetter,
						terrainFeatureEntranceCost, terrainFeatureTraverseCost, 
						terrainFeatureCombatEffect, terrainFeatureIsExclusive) {

	var code;
	var name;
	var displayName;
	var letter;
	var entranceCost;
	var traverseCost;
	var combatEffect;
	var isExclusive;
	
	this.code = terrainFeatureCode;
	this.name = terrainFeatureName;
	this.displayName = terrainFeatureDisplayName;
	this.letter = terrainFeatureLetter;
	this.entranceCost = terrainFeatureEntranceCost;
	this.traverseCost = terrainFeatureTraverseCost;
	this.combatEffect = terrainFeatureCombatEffect;
	this.isExclusive = terrainFeatureIsExclusive;
	
}

Terrain.prototype.addTerrainFeature = function( name, displayName, letter, entranceCost, traverseCost, combatEffect, isExclusive) {

    var code = Math.pow(2, this.terrainFeatures.length);
	var terrainFeature = new TerrainFeature(code, name, displayName, letter, entranceCost, traverseCost, combatEffect, isExclusive);
	this.terrainFeatures.push(terrainFeature);
}

function ReinforceZone(zoneHexagonName, zoneName)
{
	var hexagon;
	var name;

	this.hexagon = new Hexagon(zoneHexagonName);
	this.name = zoneName;
}

Terrain.prototype.addReinforceZone = function(hexagonName, zoneName)
{
	var reinforceZone = new ReinforceZone(hexagonName, zoneName);
	this.reinforceZones.push(reinforceZone);
}

Terrain.prototype.getTerrainCode = function(hexpart) {
  
    var terrainCode;
    var x = hexpart.getX();
    var y = hexpart.getY();
    
	if ( ( x >= 0 && x < this.maxTerrainX ) && ( y >= 0 && y < this.maxTerrainY ) )
		terrainCode = this.terrainArray[y][x];
	else
		terrainCode = 0;
    
    return terrainCode;
}

Terrain.prototype.getTerrainDisplayName = function(hexpart) {

	var code = this.getTerrainCode(hexpart);
	var terrainName = "";	

		for( var i = 0; i < this.terrainFeatures.length; i++ ) {

			if( (this.terrainFeatures[i].code & code) == this.terrainFeatures[i].code ) {
	
				terrainName += this.terrainFeatures[i].displayName;
				terrainName += " ";
			}
		}
	//}
	return terrainName;
}

Terrain.prototype.terrainIs = function(hexpart, terrainName)
{
    var terrainCode = this.getTerrainCode(hexpart);
    var found = false;

    for (var i = 0; i < this.terrainFeatures.length; i++ )
    {
		// match name
        if (this.terrainFeatures[i].name == terrainName)
        {
			// get terrain code and check
            code = this.terrainFeatures[i].code;
            if ((terrainCode & code) == code)
            {
                found = true;
                break;
            }
        }
    }

    return found;
}

Terrain.prototype.moveIsTraverse = function(startHexagon, endHexagon, name)
{
	var moveIsTraverse = false;
	var hexsideX = ( startHexagon.getX() + endHexagon.getX() ) / 2;
	var hexsideY = ( startHexagon.getY() + endHexagon.getY() ) / 2;
	
	var hexpart = new Hexpart(hexsideX, hexsideY);
	
	var endHexpart = new Hexpart();
	endHexpart.setXY(endHexagon.getX(), endHexagon.getY());

	if( ( this.terrainIs(hexpart, name) == true ) 
		&& ( this.terrainIs(hexpart, name) == true ) 
		&& ( this.terrainIs(endHexpart, name) == true ) )
	{
		moveIsTraverse = true;
	}
	
	return moveIsTraverse;
}

Terrain.prototype.getTerrainList = function( ) {

	var list;
	var terrainCode;
    var hexpart;
    
	list = "list<br />";

	for( var y = 0; y <= this.maxTerrainY; y++ ) {

		for( var x = 0; x <= this.maxTerrainX; x++ ) {

			terrainCode = this.getTerrainCode( x, y );
			
			for (var i = 0; i < this.terrainFeatures.length; i++ )
			{
				// get terrain code and check
				code = this.terrainFeatures[i].code;
				if ((terrainCode & code) == code)
				{
					hexpart = new Hexpart(x, y);
					if( code > 1 ) {
						list += "   this.terrain.addTerrain( \"" + hexpart.getName() + "\", \"" + this.terrainFeatures[i].name + "\" );<br />";
					}
				}
			}
		}
	}
	return list;
}

Terrain.prototype.getTerrainArray = function() {

	var array;
	array = "this.terrainArray = new Array (";

	for( var y = 0; y <= this.maxTerrainY; y++ ) {

	      array += "<br/>           new Array ( ";
		for( var x = 0; x <= this.maxTerrainX; x++ ) {

			array += " " + this.getTerrainCode( x, y);
			if( x < this.maxTerrainX) array += ", ";
		}
		array += " )";
		if( y < this.maxTerrainY) array += ",";
	}
	array += "<br/>);";

	return array;
}

Terrain.prototype.getBlankTerrainArray = function(maxHexpartX, maxHexpartY) {

	var maxX = maxHexpartX + 3;
	var maxY = maxHexpartY + 4;

	var array;
	array = "this.terrainArray = new Array (";

	for( var y = 0; y <= maxY ; y++ ) {

	      array += "<br/>           new Array ( ";
		for( var x = 0; x <= maxX; x++ ) {

			if ( x >= 4 && x <= maxHexpartX && y >= 8 && y <= maxHexpartY )
			{
				array += " 1";
			}
			else
			{
				array += " 0";
			}
			if( x < maxX) array += ", ";
		}
		array += " )";
		if( y < maxY ) array += ",";
	}
	array += "<br/>);";

	return array;
}

Terrain.prototype.setTerrain = function(letter, x, y) {

	var code = this.terrainArray[y][x];

	// check for offmap
	if ( letter == "o" )
	{
		// find offmap code
		for (var eachType = 0; eachType < this.terrainFeatures.length; eachType++ )
		{
			if (this.terrainFeatures[eachType].letter == letter)
			{
				this.terrainArray[y][x] = this.terrainFeatures[eachType].code;
				break;
			}
		}
	}
	else {
		for (var eachType = 0; eachType < this.terrainFeatures.length; eachType++ )
		{
			if (this.terrainFeatures[eachType].letter == letter)
			{
				// if is exclusive
				if (this.terrainFeatures[eachType].isExclusive == true)
				{
					// clear any other exclusive terrain types present
					for (var eachExclusiveCheck = 0; eachExclusive < this.terrainFeatures.length; eachExclusiveCheck++ )
					{
						// is it exclusive
						if (this.terrainFeatures[eachExclusiveCheck].isExclusive == true)
						{
							//  if it is present, remove it
							if ( (code & this.terrainFeatures[eachExclusiveCheck].code == this.terrainFeatures[eachExclusiveCheck].code) ) {
			
								this.terrainArray[y][x] = this.terrainArray[y][x] - this.terrainFeatures[eachExclusiveCheck].code;
							}
						}
					}
					// add in exclusive
					this.terrainArray[y][x] = this.terrainArray[y][x] + this.terrainFeatures[eachType].code;
				}
				else
				{
					// toggle terrain type
					// remove it if there
					if ( (code & this.terrainFeatures[eachType].code == this.terrainFeatures[eachType].code) ) {
			
						this.terrainArray[y][x] = this.terrainArray[y][x] - this.terrainFeatures[eachType].code;
					}
					// add it if not there
					else {
			
						this.terrainArray[y][x] = this.terrainArray[y][x] + this.terrainFeatures[eachType].code;			
					}
				} // end terrain array modification
			} // end letter match
		} // end looping thru each terrain type
	} // end offmap check
}

Terrain.prototype.addTerrain = function(hexagonName, hexpartType, terrainName)
{
	var hexagon = new Hexagon(hexagonName);

	var x = hexagon.getX();
	var y = hexagon.getY();

	switch ( hexpartType ) {
	
		case HEXAGON_CENTER:
			
			break;
			
		case BOTTOM_HEXSIDE:
		
			y = y + 2;
			break;
			
		case LOWER_LEFT_HEXSIDE:
		
			x = x - 1;
			y = y + 1;
			break;
			
		case UPPER_LEFT_HEXSIDE:
		
			x = x - 1;
			y = y - 1;
			break;	
	
	}

	for (var eachTerrainFeature = 0; eachTerrainFeature < this.terrainFeatures.length; eachTerrainFeature++ )
	{
		if ( this.terrainFeatures[eachTerrainFeature].name == terrainName )
		{
			// if exclusive, remove any conflicting terrain
			
			if ( this.terrainFeatures[eachTerrainFeature].isExclusive == true )
			{
				for ( var eachExclusiveType = 0; eachExclusiveType < this.terrainFeatures.length; eachExclusiveType++)
				{
					if ( this.terrainArray[y][x] & this.terrainFeatures[eachExclusiveType].code == this.terrainFeatures[eachExclusiveType].code )
					{
						this.terrainArray[y][x] -= this.terrainFeatures[eachExclusiveType].code;
					}
				}
			}
			
			this.terrainArray[y][x] = this.terrainArray[y][x] + this.terrainFeatures[eachTerrainFeature].code;
		}
	}
}

Terrain.prototype.getTerrainTraverseCostFor = function(name) {
 	
    var traverseCost = 0;
	var terrainFeature;
	
    for ( var i = 0; i < this.terrainFeatures.length; i++ )
    {
		terrainFeature = this.terrainFeatures[i];
		if (terrainFeature.name == name)
		{
			traverseCost = terrainFeature.traverseCost;
		}
    }
    
	return traverseCost;
}

Terrain.prototype.getTerrainEntranceMoveCost = function(hexagon) {
 	
    var entranceMoveCost = 0;
	var terrainFeature;
	
	var hexpart = new Hexpart(hexagon.getX(), hexagon.getY());
	
    for ( var i = 0; i < this.terrainFeatures.length; i++ )
    {
		terrainFeature = this.terrainFeatures[i];
		if (this.terrainIs(hexpart, terrainFeature.name) == true)
		{
			if (terrainFeature.entranceCost > entranceMoveCost)
			{
				entranceMoveCost = terrainFeature.entranceCost;
			}
		}
    }
    
	return entranceMoveCost;
}
 
Terrain.prototype.getTerrainMoveCost = function(startHexagon, endHexagon, maxMoveAmount ) {
 	
 	var moveCost = 0;
 	var hexsideX = ( startHexagon.getX() + endHexagon.getX() ) / 2;
 	var hexsideY = ( startHexagon.getY() + endHexagon.getY()  ) / 2;

	
	// if road, override terrain
	if (this.moveIsTraverse(startHexagon, endHexagon, "road") == true) {
 	        moveCost = this.getTerrainTraverseCostFor("road");
	}
	else {
 	    
		// get entrance cost
		moveCost = this.getTerrainEntranceMoveCost(endHexagon);
			
		// check hexside for river
		var hexpart = new Hexpart(hexsideX, hexsideY);
		
		if( this.terrainIs(hexpart, "river") == true ) {
			
			moveCost = maxMoveAmount;
		}
	}
 
 	// move cost on exit is the entrance cost of the leaving hexagon
	if ( this.isExit(endHexagon) == true )
	{
		// if leaving road, exit cost is road
		var endHexpart = new Hexpart(startHexagon.getX(), startHexagon.getY());
		
		if (this.terrainIs(endHexpart, "road") == true) {
 	        moveCost = this.getTerrainTraverseCostFor("road");
		}
		else {
		 
			// get entrance cost
			moveCost = this.getTerrainEntranceMoveCost(startHexagon);
		}
	}

	return moveCost;
}
 
Terrain.prototype.getTerrainTypeMoveCost = function(name)
{
	var moveCost = 0;
	
	for ( var i = 0; i < this.terrainFeatures.length; i++ )
    {
		if ( this.terrainFeatures[i].name == name )
		{
			moveCost = this.terrainFeatures[i].entranceCost;
		}
	}
	return moveCost;
}

Terrain.prototype.getDefenderTerrainCombatEffect = function(hexagon)
{
    var combatEffect;
    combatEffect = 0;

	var hexpart = new Hexpart(hexagon.getX(), hexagon.getY());
	
    for ( var i = 0; i < this.terrainFeatures.length; i++ )
        {
			if ( this.terrainIs( hexpart, this.terrainFeatures[i].name ) )
			{
				if ( this.terrainFeatures[i].combatEffect > combatEffect)
				{
					combatEffect = this.terrainFeatures[i].combatEffect;
				}
            }
        }
    return combatEffect;
}


Terrain.prototype.getAllAreAttackingAcrossRiverCombatEffect = function()
{
	return this.allAreAttackingAcrossRiverCombatEffect;
}

Terrain.prototype.isExit = function(hexagon) {
	
 	var isExit = false;
	var terrainCode;
	
	var hexpart = new Hexpart(hexagon.getX(), hexagon.getY());
	
 	terrainCode = this.getTerrainCode(hexpart);
 	
	if (this.terrainIs(hexpart, "offmap") == true) {
		isExit = true;
	}
	return isExit;
}

Terrain.prototype.getReinforceZone = function(hexagon)
{
    var zoneName = "";

    for( var i = 0; i < this.reinforceZones.length; i++ )
    {
//alert("" + i + " " + this.reinforceZones[i].hexagon.getName() + " : " + hexagon.getName());
        if ( this.reinforceZones[i].hexagon.equals(hexagon) == true )
		{
			zoneName = this.reinforceZones[i].name;
		}
    }
    
    return zoneName;
}

Terrain.prototype.isOnMap = function(hexagon)
{
	var isOnMap = true;
	
	var hexpart = new Hexpart(hexagon.getX(), hexagon.getY());
	
 	if (this.terrainIs(hexpart, "offmap") == true) 
 	{
		isOnMap = false;
 	}
 	
	return isOnMap;
}
