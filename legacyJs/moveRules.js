// moveRules.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

function MoveRules(Force, Terrain)
{
	// Class references
    var force;
    var terrain;
    
    // local variables
    var movingUnitId;
    var anyUnitIsMoving;
    
    this.force = Force;
    this.terrain = Terrain;
    
    this.movingUnitId = NONE;
    this.anyUnitIsMoving = false;
}

// id will be map if map event, id will be unit id if counter event
MoveRules.prototype.moveUnit = function(eventType, id, hexagon, turn)
{
    if (eventType == SELECT_MAP_EVENT)
    {
        if (this.anyUnitIsMoving)
        {
            // click on map, so try to move
            if (this.force.unitIsMoving(this.movingUnitId) == true) {
                this.move(this.movingUnitId, hexagon);
            }
            if (this.force.unitIsReinforcing(this.movingUnitId) == true) {
                this.reinforce(this.movingUnitId, hexagon);
            }
        }
    }
    else
    // click on a unit
    {
        if (this.anyUnitIsMoving == true)
        {
            if (id == this.movingUnitId)
            {
                // clicked on moving or reinforcing unit
                if (this.force.unitIsMoving(id) == true) 
                {
                    this.stopMove(id);
                }
                if (this.force.unitIsReinforcing(id) == true) 
                {
                    this.stopReinforcing(id);
                }
            }
            else
            {
                // clicked on another unit
                this.moveOver(this.movingUnitId, id, hexagon);
            }
        }
        else
        {
            // no one is moving, so start new move
            if (this.force.unitCanMove(id) == true) {
                this.startMoving(id);
            }
            if (this.force.unitCanReinforce(id) == true) {
                this.startReinforcing(id, turn);
            }
        }
    }
}

MoveRules.prototype.startMoving = function(id)
{
    if (this.force.unitIsZOC(id) == false)
    {
        if (this.force.setStatus(id, STATUS_MOVING) == true)
        {
            this.anyUnitIsMoving = true;
            this.movingUnitId = id;
        }
    }
}

MoveRules.prototype.move = function(id, hexagon)
{
    if (this.force.unitIsMoving(this.movingUnitId) 
		&& this.moveIsValid(id, hexagon))
    {
        this.updateMoveData(id, hexagon);
    }
}

MoveRules.prototype.moveOver = function(id, moveOverUnitId, hexagon)
{
    if (this.force.unitIsFriendly(moveOverUnitId) == true)
    {
		if ( this.moveIsValid(id, hexagon) == true )
		{
			if (this.moveWillCauseStop(id, moveOverUnitId, hexagon) == false)
			{
				this.updateMoveData(id, hexagon);
			}
			else
			{
				alert("unit cannot end move in hexagon with another unit");
			}
		}
    }
}

MoveRules.prototype.stopMove = function(id)
{
    if (this.force.unitIsMoving(id) == true)
    {
        if (this.force.setStatus(id, STATUS_STOPPED) == true)
        {
            this.anyUnitIsMoving = false;
            this.movingUnitId = 0;
        }
    }
}

MoveRules.prototype.exit = function(id)
{
    if (this.force.unitIsMoving(id) == true)
    {
        if (this.force.setStatus(id, STATUS_EXITED) == true)
        {
            this.anyUnitIsMoving = false;
            this.movingUnitId = 0;
        }
    }
}

MoveRules.prototype.moveWillCauseStop = function(id, moveOverUnitId, hexagon)
{
	var willCauseStop = false;
    var moveAmount = this.terrain.getTerrainMoveCost(this.force.getUnitHexagon(id), hexagon, this.force.getUnitMaximumMoveAmount(id));
	
	// out of moves stop
	if( this.force.unitWillUseMaxMove(id, moveAmount) == true)
	{
		willCauseStop = true;
	}
	
	// zone of control stop
	if(this.force.unitIsZOC(moveOverUnitId) == true)
	{
		willCauseStop = true;
	}

	// if using 'can always move one hexagon' and stop rule
	if(this.force.unitHasNotMoved(id) == true)	
	{
		if((this.force.unitHasMoveAmountAvailable(id, moveAmount) == false))
		{
			willCauseStop = true;
		}
	}
	
	// if moving across river stop
    if ((this.moveIsAcrossRiverNoBridge(id, hexagon) == true) && (this.force.unitHasNotMoved(id) == true))
    {
        willCauseStop = true;
    }
    
	return willCauseStop;
}

MoveRules.prototype.moveIsValid = function(id, hexagon)
{
	// all 4 conditions must be true, so any one that is false 
	//    will make the move invalid
	
   var isValid = true;

	// condition 1
    // can only move to nearby hexagon
    if( this.rangeIsOneHexagon(this.force.getUnitHexagon(id), hexagon) == false )
    {
        isValid = false ;
    }
                
	// condition 2
    // check if unit has enough move points
    var moveAmount = terrain.getTerrainMoveCost(this.force.getUnitHexagon(id), hexagon, this.force.getUnitMaximumMoveAmount(this.movingUnitId));

    // need move points, but can always move at least one hexagon
    //  can always move at least one hexagon
    //  only check move amount if unit has been moving
    if (this.force.unitHasNotMoved(id) == false)
    {
		if(this.force.unitHasMoveAmountAvailable(id, moveAmount) == false)
		{
			isValid = false;
		}
    }

	// condition 3
    // can only move across river hexside if at start of move
    if ((this.moveIsAcrossRiverNoBridge(id, hexagon) == true) && (this.force.unitHasNotMoved(id) == false))
    {
        isValid = false;
    }
    
	// condition 4
    // can not exit
    if ((this.terrain.isExit(hexagon) == true))
    {
        isValid = false;
    }
    return isValid;
}

MoveRules.prototype.moveIsAcrossRiverNoBridge = function(id, hexagon)
{
	var moveIsAcrossRiverNoBridge = false;
	
    var hexpart = new Hexpart(this.force.getUnitHexagon(id).getX(), this.force.getUnitHexagon(id).getY());
    
    // check for river without a bridge
    if ((this.terrain.terrainIs(hexpart, "river") == true) 
		&& (this.terrain.terrainIs(hexpart, "road") == false))
    {
        moveIsAcrossRiverNoBridge = true;
    }
	return moveIsAcrossRiverNoBridge;
}

MoveRules.prototype.updateMoveData = function(id, hexagon)
{
	var moveAmount = terrain.getTerrainMoveCost(this.force.getUnitHexagon(id), hexagon, this.force.getUnitMaximumMoveAmount(id)	);

	this.force.updateMoveStatus(id, hexagon, moveAmount);

    if (this.force.unitHasUsedMoveAmount(id) == true)
    {
        this.stopMove(id);
    }

    if (this.force.unitIsZOC(id) == true)
    {
        this.stopMove(id);
    }
    
    if ( this.terrain.isExit(hexagon) )
    {
		this.exit(id);
    }
}

MoveRules.prototype.rangeIsOneHexagon = function(startHexagon, endHexagon)
{
    var rangeIsOne = false;

    var los = new Los();
    los.setOrigin(startHexagon);
    los.setEndPoint(endHexagon);

    if (los.getRange() == 1)
    {
        rangeIsOne = true;
    }

    return rangeIsOne;
}

MoveRules.prototype.startReinforcing = function(id, turn)
{
    if (this.force.getUnitReinforceTurn(id) <= turn)
    {
        if (this.force.setStatus(id, STATUS_REINFORCING) == true)
        {
            this.anyUnitIsMoving = true;
            this.movingUnitId = id;
        }
    }
}

MoveRules.prototype.reinforce = function(id, hexagon)
{
    if (this.force.unitIsReinforcing(id) == true)
    {
        if (this.force.getUnitReinforceZone(id) == this.terrain.getReinforceZone(hexagon))
        {
			// get move cost
			var moveAmount = this.terrain.getTerrainEntranceMoveCost(hexagon);
			
			// override move cost if road movement
			var hexpart = new Hexpart(hexagon.getX(), hexagon.getY());
			
			if( this.terrain.terrainIs(hexpart, "road") == true )
			{
				moveAmount = this.terrain.getTerrainTraverseCostFor("road");
			}
			
			// set status
            if (this.force.unitHasMoveAmountAvailable(id, moveAmount) == true)
            {
				if (this.force.setStatus(id, STATUS_MOVING) == true)
				{
					this.force.updateMoveStatus(id, hexagon, moveAmount);
				}
            }
            
            // stop if out of moves
            if (force.unitHasUsedMoveAmount(id) == true)
			{
				this.stopMove(id);
			}
        }
    }
}

MoveRules.prototype.stopReinforcing = function(id)
{
    if (this.force.unitIsReinforcing(id) == true)
    {
        if (this.force.setStatus(id, STATUS_CAN_REINFORCE) == true)
        {
            this.anyUnitIsMoving = false;
            this.movingUnitId = 0;
        }
    }
}

// retreat rules

// gameRules has cleared retreat list

MoveRules.prototype.retreatUnit = function(eventType, id, hexagon )
{
	// id will be map if map event
    if (eventType == SELECT_MAP_EVENT)
    {
        if (this.anyUnitIsMoving == true)
        {
          this.retreat(this.movingUnitId, hexagon);
        }
    }
    else
    {
		// id will be retreating unit id if counter event
        if (this.anyUnitIsMoving == false)
        {
            if (this.force.unitCanRetreat(id) == true)
            {
                this.startRetreating(id);
            }
        }
    }
}

MoveRules.prototype.startRetreating = function(id)
{
    if (this.force.setStatus(id, STATUS_RETREATING) == true)
    {
		if (this.retreatIsBlocked(id) == true)
    	{
        	alert("reteat is blocked");
            
          var hexagon = this.force.getUnitHexagon(id);
 
        	this.force.addToRetreatHexagonList( id, hexagon );
                
        	this.stopMove(id);
        	this.force.eliminateUnit(id);
    	}
    	else 
    	{
          this.anyUnitIsMoving = true;
        	this.movingUnitId = id;
      }
    }
}

MoveRules.prototype.retreatIsBlocked = function(id)
{
    var isBlocked = true;

    var adjacentHexagonXadjustment = new Array( 0, 2, 2, 0, -2, -2 );
    var adjacentHexagonYadjustment = new Array( -4, -2, 2, 4, 2, -2 );

    var hexagon = this.force.getUnitHexagon(id);
    var hexagonX = hexagon.getX(id);
    var hexagonY = hexagon.getY(id);

    for( var eachHexagon = 0; eachHexagon < 6; eachHexagon++)
    {
        var adjacentHexagonX = hexagonX + adjacentHexagonXadjustment[eachHexagon];
        var adjacentHexagonY = hexagonY + adjacentHexagonYadjustment[eachHexagon];
		var adjacentHexagon = new Hexagon(adjacentHexagonX, adjacentHexagonY);
		//alert("testing " + adjacentHexagon.getName());
		
		if ( this.hexagonIsBlocked(id, adjacentHexagon) == false )
		{
            isBlocked = false;
			break;
        }
    }

    return isBlocked;
}

MoveRules.prototype.hexagonIsBlocked = function(id, hexagon)
{
    isBlocked = false;

    var unitHexagon = this.force.getUnitHexagon(id);
    
    var hexsideX = (hexagon.getX() + unitHexagon.getX(id)) / 2;
    var hexsideY = (hexagon.getY() + unitHexagon.getY(id)) / 2;
    
    var hexpart = new Hexpart(hexsideX, hexsideY);
		
    // make sure hexagon is not ZOC 
    if ((this.force.hexagonIsZOC(id, hexagon) == true))
    {
        isBlocked = true;
    }
    // make sure hexagon is not occupied
    
    if (this.force.hexagonIsOccupied(hexagon) == true)
    {
        isBlocked = true; 
    }
    // make sure hexagon is not retreating across a river
     
    if (this.terrain.terrainIs(hexpart, "river") == true) 
    {
        isBlocked = true;    
    }
    
    if (this.terrain.isExit(hexagon) == true)
    {
        isBlocked = true;        
    }
    //alert(unitHexagon.getName() + " to " + hexagon.getName() + " zoc: " + this.force.hexagonIsZOC(id, hexagon) + " occ: " + this.force.hexagonIsOccupied(hexagon)  + " river: " + this.terrain.terrainIs(hexpart, "river")); 
    return isBlocked;
}

MoveRules.prototype.retreat = function(id, hexagon)
{
    if (this.rangeIsOneHexagon( this.force.getUnitHexagon(id), hexagon)
			&& this.hexagonIsBlocked(id, hexagon) == false
			&& this.terrain.isExit(hexagon) == false)
    {
        this.force.addToRetreatHexagonList(id, this.force.getUnitHexagon(id));
                    
        // set move amount to 0 
        this.force.updateMoveStatus(id, hexagon, 0);

        // check crt retreat count required to how far the unit has retreated
        if (this.force.unitHasMetRetreatCountRequired(id))
        {
            // stop if unit has retreated the required amount
            if (this.force.setStatus(id, STATUS_STOPPED) == true)
            {
                this.anyUnitIsMoving = false;
                this.movingUnitId = 0;
			}
		}
   }
		
	// if forced to retreat offmap, unit is eliminated
	if (this.terrain.isExit(hexagon) == true)
    {
    	this.stopMove(id);
      	this.force.eliminateUnit(id);
    }
}

// advancing rules

MoveRules.prototype.advanceUnit = function(eventType, id, hexagon )
{
    if (eventType == SELECT_MAP_EVENT)
    {
        if (this.anyUnitIsMoving == true)
        {
			//alert("advance");
            this.advance( this.movingUnitId, hexagon );
        }
    }
    else
    {
        if ((this.anyUnitIsMoving == true) && (id == this.movingUnitId))
        {
            this.stopAdvance(this.movingUnitId);
        }
        else
        {
            if (this.force.unitCanAdvance(id) == true)
            {
                this.startAdvancing(id);
            }
        }
    }
}

MoveRules.prototype.startAdvancing = function(id)
{
    if (this.force.setStatus(id, STATUS_ADVANCING) == true)
    {
        this.anyUnitIsMoving = true;
        this.movingUnitId = id;
    }
}

MoveRules.prototype.advance = function(id, hexagon)
{
    if (this.advanceIsValid( id, hexagon ) == true)
    {
	// set move amount to 0 
		this.force.updateMoveStatus(id, hexagon, 0);
        this.stopAdvance(id);
    }
}

MoveRules.prototype.stopAdvance = function(id)
{
    if (this.force.setStatus(id, STATUS_ADVANCED) == true) {
        this.force.resetRemainingAdvancingUnits();
        this.anyUnitIsMoving = false;
        this.movingUnitId = NONE;
    }
}

MoveRules.prototype.advanceIsValid = function(id, hexagon)
{
    var isValid = false;
            
	var startHexagon = this.force.getUnitHexagon(id);

    if ( this.force.advanceIsOnRetreatList(id, hexagon) == true && this.rangeIsOneHexagon(startHexagon, hexagon) == true )
    {
		//alert("retreat list: true");
		isValid = true;
    }
    else
    {
		//alert("retreat list: false");
    }

    return isValid;
}
