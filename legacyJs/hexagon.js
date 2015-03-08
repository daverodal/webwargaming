// Hexagon.js
//
// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

// Hexagon Constructor

function Hexagon() {

	var evenColumnShiftDown;
	var number;
	var x, y;
	var name;
	var minX, minY;
	var maxX, maxY;

	this.evenColumnShiftDown = true;
	this.number = 0;
	this.x = 0;
	this.y = 0;
	this.name = "";
	this.minX = 4;
	this.minY = 8;
	this.maxX = 12;
	this.maxY = 24;

	// Hexagon(name) 
	if ( Hexagon.arguments.length == 1 ) {

		this.name = Hexagon.arguments[0];
		this.number = parseInt(this.name, 10);
		this.calculateHexpartXY();
		this.calculateHexagonName();	
	}

	// Hexagon(x, y)
	if ( Hexagon.arguments.length == 2 ) {

            this.x = Hexagon.arguments[0];
            this.y = Hexagon.arguments[1];
            this.calculateHexagonNumber();
            this.calculateHexagonName();
	}
}

Hexagon.prototype.setNumber = function( number )
{
	this.number = number;
	this.calculateHexpartXY();
	this.calculateHexagonName();
}

Hexagon.prototype.setName = function( name )
{
	this.name = name;
	this.number = parseInt(this.name, 10);
	this.calculateHexagonXY();
}

Hexagon.prototype.setXY = function( x, y ) {

	this.x = x;
	this.y = y;

	this.calculateHexagonNumber();	
	this.calculateHexagonName();
}

Hexagon.prototype.calculateHexagonNumber = function()
{
  	var x, y;
  	x = ( (this.x - this.minX ) / 2 ) + 1;

	if(this.evenColumnShiftDown == true)
	{
    		y = Math.floor(((this.y - this.minY) / 4) + 1);
  	} else {
    		y = Math.floor(((this.y - this.minY + 2) / 4) + 1);
  	}
  	this.number = x * 100 + y;
}

Hexagon.prototype.calculateHexpartXY = function() {

	var x, y;
  
 	x = Math.floor( this.number / 100 );
	y = this.number - ( x * 100 );

 	this.y = 4 * ( y - 1 ) + this.minY;

	if(this.evenColumnShiftDown == true)
	{
		if ( x % 2 == 0 ) this.y += 2;
	} else {
		if ( x % 2 == 0 ) this.y -= 2;
	}
	
	this.x = 2 * ( x - 1 ) + this.minX;
}

Hexagon.prototype.parseX = function(number) {

	var x;

	x = Math.floor(number / 100);
	x = 2 * ( x - 1 ) + this.minX;

	return x;
}

Hexagon.prototype.parseY = function(number) {

	var x, y;

	x = Math.floor(number / 100);
	y = hexagon_Number % 100;

	y = 4 * ( y - 1 ) + this.minY;

	if (this.evenColumnsShiftDown == true)
	{
		if (x % 2 == 0) y += 2;
	}
	else
	{
		if (x % 2 == 0) y -= 2;
	}

	return y;
}

Hexagon.prototype.calculateHexagonName = function() {

    this.name = "";
    
	if (this.x >= this.minX && this.y >= this.minY && this.x <= this.maxX && this.y <= this.maxY)
	{
	    if (this.number < 1000) {
	        this.name = "0" + this.number;
	    }
	    else {
	        this.name = this.number;
	    }
    }
}

Hexagon.prototype.equals = function(hexagon) {

	var isEqual = false;
	
	if ( this.x == hexagon.getX() && this.y == hexagon.getY())
	{
		isEqual = true;
	}
	
	return isEqual;
}

Hexagon.prototype.getAdjacentHexagon = function( direction ) {

	// direction 1=N, 2=NE, 3=SE, 4=S, 5=SW, 6=NW
	var adjX = new Array( 0,  0,  2,  2,  0, -2, -2 );
	var adjY = new Array( 0, -4, -2,  2,  4,  2, -2 );

	this.x += adjX[direction];
	this.y += adjY[direction];
	
	this.calculateHexagonNumber();
	this.calculateHexagonName();
}

Hexagon.prototype.getX = function() {

	return this.x;
}

Hexagon.prototype.getY = function() {

    return this.y;
}

Hexagon.prototype.getNumber = function() {

	return this.number;
}

Hexagon.prototype.getName = function() {

	return this.name;
}
