// force.js

// copyright (c) 20092011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

function Force() {

  var units;
  var victor;
  var ZOCrule;
  var attackingForceId;
  var defendingForceId;
  var deleteCount;
  
  var retreatHexagonList;
  var eliminationTrayHexagonX;
  var eliminationTrayHexagonY;

  this.units = new Array();
  this.victor = RED_FORCE;
  this.ZOCrule = true;
  this.deleteCount = 0;

  this.retreatHexagonList = new Array();
  this.eliminationTrayHexagonX = 9;
  this.eliminationTrayHexagonY = 0;
}

function unit( unitId, unitName, unitForceId, unitHexagon, unitImage, unitStrength, unitMaxMove, unitStatus, unitReinforceZone, unitReinforceTurn ) {

  var id;
  var forceId;
 	var name;
 	var hexagon;
 	var image;
	var strength;
	var maxMove;
	var status;
	var moveAmountUsed;
	var reinforceZone;
	var reinforceTurn;
	var combatNumber;
	var combatIndex;
	var combatOdds;
	var hasContact;
	var moveCount;
	var retreatCountRequired;
	var combatResults;
	var dieRoll;
	var eliminationHexagonX;
	var eliminationHexagonY;
	
	this.id = unitId;
	this.name = unitName;
	this.forceId = unitForceId;
    this.hexagon = new Hexagon(unitHexagon);
    this.image = unitImage;
    this.strength = unitStrength;
    this.maxMove = unitMaxMove;
    this.status = unitStatus;
    this.moveAmountUsed = 0;
    this.reinforceZone = unitReinforceZone;
    this.reinforceTurn = unitReinforceTurn;
    this.combatNumber = 0;
    this.combatIndex = 0;
    this.combatOdds = "";
    this.hasContact = false;
	this.moveCount = 0;
    this.retreatCountRequired = 0;
    this.combatResults = NR;
}

function RetreatStep( RetreatStepStepNumber, RetreatHexagon )
{
	var stepNumber;
	var hexagon;

	this.stepNumber = RetreatStepStepNumber;
	this.hexagon = new Hexagon(RetreatHexagon.getNumber());
}

Force.prototype.addToRetreatHexagonList = function(id, retreatHexagon) {
	
	//alert("Force.prototype..prototype. adding: " + id + " : " + retreatHexagon.getName());
	// note: addToRetreatHexagonList() is invoked before retreat move, so
	//  the moveCount is 0 for 1st step and 1 for 2nd step
	
	var retreatStep = new RetreatStep( this.units[id].moveCount, retreatHexagon );
	
    this.retreatHexagonList.push( retreatStep );
}

Force.prototype.addUnit = function( unitName, unitForceId, unitHexagon, unitImage, unitStrength, unitMaxMove, unitStatus, unitReinforceZoneName, unitReinforceTurn ) {

    var id = this.units.length;
    this.units.push(new unit( id, unitName, unitForceId, unitHexagon, unitImage, unitStrength, unitMaxMove, unitStatus, unitReinforceZoneName, unitReinforceTurn ));
}
 
Force.prototype.advanceIsOnRetreatList = function(id, hexagon)
{
	var isOnList = false;
	
    for ( var i = 0; i < this.retreatHexagonList.length; i++ )
    {
	// note: addToRetreatHexagonList() is invoked before retreat move, so
	//  the moveCount is 0 for 1st step and 1 for 2nd step
	//  when advancing unit.moveCount will be 0 which will match 1st step retreat number
	
	//alert("Force.prototype..prototype. checkingt: " + id + " hexagon: " + hexagon.getName() + " with array " + this.retreatHexagonList[i].hexagon.getName());
		if ( this.retreatHexagonList[i].stepNumber == this.units[id].moveCount 
			&& this.retreatHexagonList[i].hexagon.equals( hexagon ))
		{
			isOnList = true;
		}
	}
	
	return isOnList;
}

Force.prototype.applyCRTresults = function(combatNumber, combatResults, dieRoll)
{
    this.clearRetreatHexagonList();

    for ( var defender = 0; defender < this.units.length; defender++ )
    {
        if (this.units[defender].status == STATUS_DEFENDING && this.units[defender].combatNumber == combatNumber)
        {
            switch (combatResults)
            {
               case AR:
                    this.units[defender].status = STATUS_DEFENDED;
                    this.units[defender].retreatCountRequired = 0;
                    break;

               case AE:
                    this.units[defender].status = STATUS_DEFENDED;
                    this.units[defender].retreatCountRequired = 0;
                    break;

               case DE:
                    this.units[defender].status = STATUS_ELIMINATING;
                    this.units[defender].retreatCountRequired = 1;
                    this.addToRetreatHexagonList(defender, this.getUnitHexagon(defender));                    break;

                case DR:
                    this.units[defender].status = STATUS_CAN_RETREAT;
                    this.units[defender].retreatCountRequired = 1;
                    break;

                default:
                break;
             }
                this.units[defender].combatResults = combatResults;
                this.units[defender].dieRoll = dieRoll;
                this.units[defender].combatNumber = 0;
                this.units[defender].moveCount = 0;
        }
    }

    for ( var attacker = 0; attacker < this.units.length; attacker++ )
    {
        if (this.units[attacker].status == STATUS_ATTACKING && this.units[attacker].combatNumber == combatNumber)
        {
            switch (combatResults)
            {
                case AR:
                    this.units[attacker].status = STATUS_CAN_RETREAT;
                    this.units[attacker].retreatCountRequired = 1;
                    break;

                case AE:
                    this.units[attacker].status = STATUS_ELIMINATING;
                    this.units[attacker].retreatCountRequired = 0;
                    break;

                case DE:
                    this.units[attacker].status = STATUS_CAN_ADVANCE;
                    this.units[attacker].retreatCountRequired = 0;
                    break;

                case DR:
                    this.units[attacker].status = STATUS_CAN_ADVANCE;
                    this.units[attacker].retreatCountRequired = 0;
                    break;

                default:
                    break;
            }
            this.units[attacker].combatResults = combatResults;
            this.units[attacker].dieRoll = dieRoll;
            this.units[attacker].combatNumber = 0;
            this.units[attacker].moveCount = 0;
        }
    }
    this.removeEliminatingUnits();
}

Force.prototype.checkVictoryConditions = function()
{
    // last to occupy Marysville at 403 wins
    
    var hexagon = new Hexagon(403);
    
    for ( var id = 0; id < this.units.length; id++ )
    {
       if (this.units[id].hexagon.equals(hexagon))
        {
            this.victor = this.units[id].forceId;
        }
    }
}

Force.prototype.clearRetreatHexagonList = function() {

	this.retreatHexagonList = [];
}

Force.prototype.eliminateUnit = function(id)
{
	var hexagon = new Hexagon(this.units[id].hexpartX, this.units[id].hexpartY);
    this.deleteCount++;
	//alert("elim " + id + " at " + this.eliminationTrayHexagonX + ", " + this.eliminationTrayHexagonY);
    this.units[id].status = STATUS_ELIMINATED;
    this.units[id].hexagon.setXY(this.eliminationTrayHexagonX + ( 2 * this.deleteCount), this.eliminationTrayHexagonY);
}

Force.prototype.getAttackerStrength = function(combatNumber)
{
    var attackerStrength = 0;

    for ( var id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_ATTACKING && this.units[id].combatNumber == combatNumber)
        {
            attackerStrength += this.units[id].strength;
        }
    }

    return attackerStrength;
}

Force.prototype.getAttackerHexagonList = function(combatNumber) {

    var hexagonList = new Array();

    for (var id = 0; id < this.units.length; id++) {
        if (this.units[id].status == STATUS_ATTACKING && this.units[id].combatNumber == combatNumber) {

            hexagonList.push(this.units[id].hexagon);
        }
    }
    return hexagonList;
}

Force.prototype.getCombatHexagon = function(combatNumber)
{
    var hexagon;

    for ( var id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].combatNumber == combatNumber && this.units[id].status == STATUS_DEFENDING)
        {
            hexagon = this.units[id].hexagon;
        }
    }
    return hexagon;
}

Force.prototype.getCombatInfo = function(id)
{
	return this.units[id].combatOdds;
}

Force.prototype.getDefenderStrength = function(combatNumber)
{
    var defenderStrength = 0;

    for ( var id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_DEFENDING && this.units[id].combatNumber == combatNumber)
        {
            defenderStrength += this.units[id].strength;
        }
    }

    return defenderStrength;
}

Force.prototype.getRetreatHexagonList = function() {

    var i;
    var retreatHexagonList = "advance hexagon list: ";

    for (i = 0; i < 6; i++) {

        if (retreatHexpartX[i] > 0 || retreatHexpartY[i] > 0) {
            hexagon = new hexagon(retreatHexpartX[i], retreatHexpartY[i]);
            retreatHexagonList = retreatHexagonList + " " + hexagon.getHexagonName();
        }
        //retreatHexagonList += " | " + retreatHexpartX[i] + ", " + retreatHexpartY[i];
    }

    //retreatHexagonList = retreatHexpartX[0] + ", " + retreatHexpartY[0];

    return (retreatHexagonList);
}

Force.prototype.getUnitBeingEliminatedId = function()
{
    var id;

    for ( var i = 0; i < this.units.length; i++ )
    {
        if (this.units[i].status == STATUS_ELIMINATING)
        {
            id = this.units[i].id;
        }
    }
    return id;
}

Force.prototype.getUnitCombatIndex = function(id)
{
    return this.units[id].combatIndex;
}

Force.prototype.getUnitCombatNumber = function(id)
{
    return this.units[id].combatNumber;
}

Force.prototype.getUnitForceId = function(id)
{
    return this.units[id].forceId;
}

Force.prototype.getUnitHexagon = function( id ) 
{

	return this.units[id].hexagon;
}

Force.prototype.getUnitInfo = function( id ) {

    var unitInfo = "";
    
    if (id >= 0) {
        unitInfo = this.units[id].name;
        unitInfo += " " + status_name[this.units[id].status];
        unitInfo += "<br />strength: " + this.units[id].strength;
        unitInfo += "<br />can move: " + this.units[id].maxMove;
 
        if (this.units[id].status == STATUS_MOVING) {
            unitInfo += "<br />has moved " + this.units[id].moveAmountUsed + " of " + this.units[id].maxMove;
        }
        else {
			unitInfo +="<br />&nbsp;";
        }

        if (this.units[id].status == STATUS_CAN_REINFORCE) {
            unitInfo += "<br />can reinforce on turn " + this.units[id].reinforceTurn;
        }
        else {
			unitInfo +="<br />&nbsp;";        
        }

        if (this.units[id].status == STATUS_DEFENDING && this.unitHasAttackers(id)) {
        }
    }
	return unitInfo;
}

Force.prototype.getUnitMaximumMoveAmount = function(id)
{
    return this.units[id].maxMove;
}

Force.prototype.getUnitMoveCount = function(id)
{
    return this.units[id].moveCount;
}

Force.prototype.getUnitName = function(id)
{
	return this.units[id].name;
}

Force.prototype.getUnitReinforceTurn = function(id)
{
	return this.units[id].reinforceTurn;
}

Force.prototype.getUnitReinforceZone = function( id ) 
{
	return this.units[id].reinforceZone;
}

Force.prototype.getUnitRetreatCountRequired = function(id)
{
    return this.units[id].retreatCountRequired;
}

Force.prototype.getVictorId = function() 
{
    return this.victor;
}

Force.prototype.hexagonIsZOC = function(id, hexagon)
{
    var isZOC = false;

    if (this.ZOCrule == true)
    {
        var los = new Los();
        los.setOrigin(hexagon);

        for ( i = 0; i < this.units.length; i++ )
        {
            los.setEndPoint(this.units[i].hexagon);
            if (los.getRange() == 1 
                && this.units[i].forceId != this.units[id].forceId 
                && this.units[i].status != STATUS_CAN_REINFORCE
                && this.units[i].status != STATUS_ELIMINATED
            )
            {
                isZOC = true;
                break;
            }
        }
    }
    return isZOC;
}

Force.prototype.hexagonIsOccupied = function(hexagon)
{
    var isOccupied = false;

    for ( var id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].hexagon.equals(hexagon))
        {
            isOccupied = true;
        }
    }

    return isOccupied;
}

Force.prototype.isForceEliminated = function()
{
	var isForceEliminated = false;
	var isDefendingForceEliminated = true;
	var isAttackingForceEliminated = true;
	
    for ( var id = 0; id < this.units.length; id++ )
    {
       if (this.units[id].forceId == this.defendingForceId && this.units[id].status != STATUS_ELIMINATED )
        {
			// found one alive, so make it false
            isDefendingForceEliminated = false;
        }
    }
    
    for ( var id = 0; id < this.units.length; id++ )
    {
       if (this.units[id].forceId == this.attackingForceId && this.units[id].status != STATUS_ELIMINATED )
        {
			// found one alive, so make it false
            isAttackingForceEliminated = false;
        }
    }
    
    if(isDefendingForceEliminated == true ||  isAttackingForceEliminated == true)
    {
		isForceEliminated = true;
    }
    return isForceEliminated;
}

Force.prototype.moreCombatToResolve = function() {

    var moreCombatToResolve = false;

    for ( id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_DEFENDING 
            && this.unitHasAttackers(id)
        )
        {
            moreCombatToResolve = true;
            break;
        }
    }
    return moreCombatToResolve;
}

Force.prototype.recoverUnits = function()
{
    for ( id = 0; id < this.units.length; id++ )
    {
    switch (this.units[id].status)
        {
            case STATUS_STOPPED:
            case STATUS_DEFENDED:
            case STATUS_DEFENDING:
            case STATUS_ATTACKED:
            case STATUS_ATTACKING:
            case STATUS_RETREATED:
            case STATUS_ADVANCED:
            case STATUS_CAN_ADVANCE:

                this.units[id].status = STATUS_READY;
                this.units[id].moveAmountUsed = 0;
                break;

             default:
                break;
        }
        this.units[id].combatIndex = 0;
        this.units[id].combatNumber = 0;
        this.units[id].combatResults = NR;
    }
}

Force.prototype.removeEliminatingUnits = function()
{
    for ( var id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_ELIMINATING)
        {
           this.eliminateUnit(id);
        }
    }
}
 
Force.prototype.resetRemainingAdvancingUnits = function()
{
    for ( var id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_ADVANCING || this.units[id].status == STATUS_CAN_ADVANCE)
        {
            this.units[id].status = STATUS_ATTACKED;
        }
    }
}

Force.prototype.setAttackingForceId = function(forceId)
{
    if ( forceId == BLUE_FORCE )
    {
        this.attackingForceId = BLUE_FORCE;
        this.defendingForceId = RED_FORCE;
    }
    else
    {
        this.attackingForceId = RED_FORCE;
        this.defendingForceId = BLUE_FORCE;
    }
}

Force.prototype.setEliminationTrayXY = function(hexagonNumber)
{
	var hexagon = new Hexagon(hexagonNumber);
	this.eliminationTrayHexagonX = hexagon.getX();
	this.eliminationTrayHexagonY = hexagon.getY();
}

Force.prototype.setStatus = function( id,  status)
{
    var success = false;
    switch (status)
    {
        case STATUS_REINFORCING:
            if (this.units[id].forceId == this.attackingForceId && this.units[id].status == STATUS_CAN_REINFORCE)
            {
                this.units[id].status = status;
                success = true;
            }
            break;

        case STATUS_CAN_REINFORCE:
            if (this.units[id].forceId == this.attackingForceId && this.units[id].status == STATUS_REINFORCING)
            {
                this.units[id].status = status;
                success = true;
            }
            break;

        case STATUS_READY:
        case STATUS_DEFENDING:
        case STATUS_ATTACKING:
            this.units[id].status = status;
            break;

        case STATUS_MOVING:
            if (this.units[id].forceId == this.attackingForceId 
                && (this.units[id].status == STATUS_READY || this.units[id].status == STATUS_REINFORCING))
            {
               this.units[id].status = status;
               this.units[id].moveCount = 0;
               this.units[id].moveAmountUsed = 0;
               success = true;
            }
            break;

        case STATUS_STOPPED:
            if (this.units[id].status == STATUS_MOVING)
            {
                this.units[id].status = status;
                success = true;
            }
            if (this.units[id].status == STATUS_ADVANCING)
            {
                this.units[id].status = STATUS_ADVANCED;
                success = true;
            }
            if (this.units[id].status == STATUS_RETREATING)
            {
                this.units[id].status = STATUS_RETREATED;
                success = true;
            }
            break;

        case STATUS_EXITED:
            if (this.units[id].status == STATUS_MOVING)
            {
                this.units[id].status = status;
                success = true;
            }
            break;

        case STATUS_RETREATING:
            if (this.units[id].status == STATUS_CAN_RETREAT)
            {
                this.units[id].status = status;
                this.units[id].moveCount = 0;
                this.units[id].moveAmountUsed = 0;
                success = true;
            }
            break;

        case STATUS_ADVANCING:
            if (this.units[id].status == STATUS_CAN_ADVANCE)
            {
                this.units[id].status = status;
                this.units[id].moveCount = 0;
                this.units[id].moveAmountUsed = 0;
                success = true;
            }
            break;

        case STATUS_ADVANCED:
            if (this.units[id].status == STATUS_ADVANCING) {
                this.units[id].status = status;
                success = true;
            }
            break;

        default:
            break;
    }
    return success;
}

Force.prototype.setupAttacker = function(id, combatNumber)
{
    this.units[id].status = STATUS_ATTACKING;
    this.units[id].combatNumber = combatNumber;
}

Force.prototype.setupDefender = function(id, combatNumber)
{
    this.units[id].status = STATUS_DEFENDING; ;
    this.units[id].combatNumber = combatNumber;
}

Force.prototype.storeCombatIndex = function(combatNumber, combatIndex)
{
    for ( var id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].combatNumber == combatNumber)
        {
            this.units[id].combatIndex = combatIndex;
        }
    }
}

Force.prototype.storeCombatOdds = function(combatNumber, combatOdds)
{
    for ( var id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].combatNumber == combatNumber)
        {
            this.units[id].combatOdds = combatOdds;
        }
    }
}

Force.prototype.undoAttackerSetup = function(id)
{
    this.units[id].status = STATUS_READY;
    this.units[id].combatNumber = 0;
    this.units[id].combatIndex = 0;
}

 Force.prototype.undoDefendersWithoutAttackers = function()
{
    for ( id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_DEFENDING && this.unitHasAttackers(id) == false)
        {
            this.units[id].status = STATUS_READY;
        }
    }
}

Force.prototype.unitCanAdvance = function(id)
{
    var Advance = false;
    if ( this.units[id].status == STATUS_CAN_ADVANCE )
    {
        Advance = true;
    }
    return Advance;
}

Force.prototype.unitCanMove = function(id) {
    var canMove = false;
    if (this.units[id].status == STATUS_READY
        && this.units[id].forceId == this.attackingForceId) {
        canMove = true;
    }
    return canMove;
}

Force.prototype.unitCanReinforce = function(id) {
    var canReinforce = false;
    if (this.units[id].status == STATUS_CAN_REINFORCE
        && this.units[id].forceId == this.attackingForceId)
    {
        canReinforce = true;
    }
    return canReinforce;
}

Force.prototype.unitCanRetreat = function(id)
{
    var canRetreat = false;
    if ( this.units[id].status == STATUS_CAN_RETREAT )
    {
        canRetreat = true;
    }
    return canRetreat;
}

Force.prototype.unitGetAttackerList = function(id)
{
    var attackerList = "";

    for ( i = 0; i < this.units.length; i++ )
    {
        if (this.units[i].forceId == this.attackingForceId 
        && this.units[i].combatNumber == this.units[id].combatNumber
        )
        {
            attackerList += this.units[i].name + " ";
        }
    }
    return attackerList;
}

Force.prototype.unitHasAttackers = function(id)
{
    var hasAttackers = false;

    for ( i = 0; i < this.units.length; i++ )
    {
        if (this.units[i].forceId == this.attackingForceId 
        && this.units[i].combatNumber == this.units[id].combatNumber
        )
        {
            hasAttackers = true;
            break;
        }
    }
    return hasAttackers;
}

Force.prototype.unitHasMetRetreatCountRequired = function(id)
{
    var unitHasMetRetreatCountRequired = false;
    
    if( this.units[id].moveCount == this.units[id].retreatCountRequired )
    {
        unitHasMetRetreatCountRequired = true;
    }
    
    return unitHasMetRetreatCountRequired;
}

Force.prototype.unitHasMoveAmountAvailable = function( id, moveAmount)
{
    var canMove;

    if (this.units[id].moveAmountUsed + moveAmount <= this.units[id].maxMove)
    {
        canMove = true;
    }
    else
    {
        canMove = false;
    }
    return canMove;
}

Force.prototype.unitHasNotMoved = function(id)
{
    var hasMoved;

    if (this.units[id].moveAmountUsed == 0)
    {
        hasMoved = true;
    }
    else
    {
    hasMoved = false;
    }
    return hasMoved;
}

Force.prototype.unitHasUsedMoveAmount = function(id)
{
    var maxMove;

    // moveRules amount used can be larger if can always moveRules at least one hexagon
    if (this.units[id].moveAmountUsed >= this.units[id].maxMove)
    {
        maxMove = true;
    }
    else
    {
        maxMove = false;
    }
    return maxMove;
}

Force.prototype.unitIsAttacking = function(id)
{
    var isAttacking = false;

    if (this.units[id].status == STATUS_ATTACKING)
    {
        isAttacking = true;
    }
    return isAttacking;
}

Force.prototype.unitIsDefending = function(id)
{
	var isDefending = false;

	if (this.units[id].status == STATUS_DEFENDING) {
	    isDefending = true;
	}

	return isDefending;
}

Force.prototype.unitIsEliminated = function(id)
{
    var isEliminated = false;

    if (this.units[id].status == STATUS_ELIMINATED)
    {
        isEliminated = true;
    }
    return isEliminated;
}

Force.prototype.unitIsEnemy = function(id)
{
    var isEnemy = false;

    if (this.units[id].forceId == this.defendingForceId)
    {
        isEnemy = true;
    }
    return isEnemy;
}

Force.prototype.unitIsFriendly = function(id)
{
	var isFriendly = false;

	if (this.units[id].forceId == this.attackingForceId)
	{
		isFriendly = true;
	}
	return isFriendly;
}

Force.prototype.unitIsInCombat = function(id)
{
    var inCombat = false;
    if (this.units[id].combatNumber > 0)
    {
        inCombat = true;
    }
     return inCombat;
}

Force.prototype.unitIsMoving = function(id)
{
    var isMoving = false;
    if ( this.units[id].status == STATUS_MOVING )
    {
        isMoving = true;
    }
    return isMoving;
}

Force.prototype.unitIsReinforcing = function(id)
{
    var isReinforcing;
    if (this.units[id].status == STATUS_REINFORCING)
    {
        isReinforcing = true;
    }
    else
    {
        isReinforcing = false;
    }
    return isReinforcing;
}

Force.prototype.unitIsRetreating = function(id)
{
    var isRetreating = false;
    if ( this.units[id].status == STATUS_RETREATING )
    {
        isRetreating = true;
    }
    return isRetreating;
}

Force.prototype.unitIsZOC = function(id)
{
    var isZOC = false;

    if (this.ZOCrule == true)
    {
        var los = new Los();
        los.setOrigin(this.units[id].hexagon);

        for ( i = 0; i < this.units.length; i++ )
        {
            los.setEndPoint(this.units[i].hexagon);
            if (los.getRange() == 1 
                && this.units[i].forceId != this.units[id].forceId 
                && this.units[i].status != STATUS_CAN_REINFORCE
                && this.units[i].status != STATUS_ELIMINATED
            )
            {
                isZOC = true;
                break;
            }
        }
    }
    return isZOC;
}

Force.prototype.unitWillUseMaxMove = function(id, moveAmount)
{
	var willStop;
	
    if (this.units[id].moveAmountUsed + moveAmount >= this.units[id].maxMove)
    {
		willStop = true;
	}
	else
    {
		willStop = false;
	}
	return willStop;
}

Force.prototype.unitsAreAdvancing = function()
{
    var areAdvancing = false;

    for ( id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_CAN_ADVANCE 
            || this.units[id].status == STATUS_ADVANCING
        )
        {
            areAdvancing = true;
            break;
        }
    }
    return areAdvancing ;
}


Force.prototype.unitsAreBeingEliminated = function()
{
    var areBeingEliminated= false;

    for ( id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_ELIMINATING)
        {
            areBeingEliminated= true;
            break;
        }
    }
    return areBeingEliminated;
}

Force.prototype.unitsAreDefending = function()
{
    var areDefending = false;

    for ( id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_DEFENDING)
        {
            areDefending= true;
            break;
        }
    }
    return areDefending;
}

Force.prototype.unitsAreRetreating = function()
{
    var areRetreating = false;

    for ( id = 0; id < this.units.length; id++ )
    {
        if (this.units[id].status == STATUS_CAN_RETREAT 
            || this.units[id].status == STATUS_RETREATING
        )
        {
            areRetreating = true;
            break;
        }
    }
    return areRetreating;
}

Force.prototype.updateMoveStatus = function( id,  hexagon,  moveAmount)
{
    this.units[id].hexagon = hexagon;
    this.units[id].moveCount++;
    this.units[id].moveAmountUsed = this.units[id].moveAmountUsed + moveAmount;
}

