//  MapGrid
//
// copyright (c) 1998-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

// MapData Constructor
function MapData(  ){

	var originX;
	var originY;
	var topHeight;
	var bottomHeight;
	var hexsideWidth;
	var centerWidth;
};

MapData.prototype.setData = function( originX, originY
						, topHeight, bottomHeight
						, hexsideWidth, centerWidth ){
						
  	this.originX = originX;
  	this.originY = originY;
    this.topHeight = topHeight;
  	this.bottomHeight = bottomHeight;
  	this.hexsideWidth = hexsideWidth;
  	this.centerWidth = centerWidth;
}

// MapGrid Constructor
function MapGrid(mapData) {

	var originX;
	var originY;
	var topHeight;
	var bottomHeight;
	var hexsideWidth;
	var centerWidth;
	var hexagonWidth;
	var hexagonHeight;
	var halfHexagonHeight;
	var halfHexagonWidth;
	var oneFourthHexagonHeight;
	var leftMapEdge;

	// pixel info from screen
	var mapGridX, mapGridY;
	var distanceFromLeftEdgeOfHexagon;
	var distanceFromTopEdgeOfHexagon;
	var column, row;

  	// hexagon and it's hexpart
  	var hexagon;
  	var hexpart;
   
  	this.originX = mapData.originX;
  	this.originY = mapData.originY;
    this.topHeight = mapData.topHeight;
  	this.bottomHeight = mapData.bottomHeight;
  	this.hexsideWidth = mapData.hexsideWidth;
  	this.centerWidth = mapData.centerWidth;

  	this.hexagonHeight = this.topHeight + this.bottomHeight;
  	this.hexagonWidth = this.hexsideWidth + this.centerWidth;
  	this.halfHexagonHeight = this.hexagonHeight / 2;
  	this.halfHexagonWidth = this.hexagonWidth / 2;
  	this.oneFourthHexagonHeight = this.hexagonHeight / 4;
  	this.leftMapEdge = -( this.hexsideWidth + ( this.centerWidth / 2 ) );

	this.hexagon = new Hexagon();
	this.hexpart = new Hexpart();
}

MapGrid.prototype.setPixels = function( pixelX, pixelY ) {

  this.calculateHexpartFromPixels(pixelX, pixelY);
  this.calculateHexagonFromPixels();
}
 
MapGrid.prototype.setHexagonXY = function( x, y) {

	this.setHexpartXY(x, y);
}

MapGrid.prototype.setHexpartXY = function( x, y) {

	this.mapGridX = (this.halfHexagonWidth * x) - this.originX;
	this.mapGridY = (this.oneFourthHexagonHeight * y) - this.originY;
	this.hexpart.setXY(x, y);
}

MapGrid.prototype.calculateHexpartFromPixels = function( pixelX, pixelY ) {

  var hexpartX, hexpartY;
    
  // adjust for hexagonGrid origin
  this.mapGridX = pixelX + this.originX;
  this.mapGridY = pixelY + this.originY;

  this.column = Math.floor((this.mapGridX - this.leftMapEdge) / this.hexagonWidth);
  this.distanceFromLeftEdgeOfHexagon = (this.mapGridX - this.leftMapEdge) - (this.column * this.hexagonWidth);

  if (this.distanceFromLeftEdgeOfHexagon < this.hexsideWidth)
  {

    //  it's a / or \ hexside
    hexpartX = (2 * this.column) - 1;
    this.row = Math.floor(this.mapGridY / this.halfHexagonHeight);
    hexpartY = (2 * this.row) + 1;
    this.distanceFromTopEdgeOfHexagon = this.mapGridY - (this.row * this.topHeight);
  }
  else
  {

  // it's a center or lower hexside
  hexpartX = 2 * (this.column);
  this.mapGridY = this.mapGridY + this.oneFourthHexagonHeight;
  this.row = Math.floor(this.mapGridY / this.halfHexagonHeight);
  hexpartY = (2 * this.row);
  this.distanceFromTopEdgeOfHexagon = this.mapGridY - (this.row * this.topHeight);
  }
    
  this.hexpart.setXY(hexpartX, hexpartY);
}

MapGrid.prototype.calculateHexagonFromPixels = function() {

    var hexpartX, hexpartY, hexpartType;
	
	hexpartX = this.hexpart.getX();
	hexpartY = this.hexpart.getY();
	hexpartType = this.hexpart.getHexpartType()
	
    switch (hexpartType)
    {
      case 1:
        this.hexagon.setXY(hexpartX, hexpartY);
        break;

      case 2:
        if (this.distanceFromTopEdgeOfHexagon < this.oneFourthHexagonHeight)
        {
          this.hexagon.setXY(hexpartX, hexpartY - 2);
        }
        else
        {
          this.hexagon.setXY(hexpartX, hexpartY + 2);
        }
        break;

      case 3:
        // check the tangent of the hexside line with tangent of the mappoint
        //
        // the hexside line tangent is opposite / adjacent = this.hexsideWidth / this.topHeight
        // the mappoint tangent is opposite / adjacent =  this.distanceFromLeftEdgeOfHexagon / this.distanceFromTopEdgeOfHexagon
        //
        // is mappoint tangent <  line tangent ?
        // (this.distanceFromLeftEdgeOfHexagon / this.distanceFromTopEdgeOfHexagon) < (this.hexsideWidth / this.topHeight)
        //
        // multiply both sides by this.topHeight
        // (this.distanceFromLeftEdgeOfHexagon / this.distanceFromTopEdgeOfHexagon) * this.topHeight  < (this.hexsideWidth )
        //
        // multiply both sides by this.distanceFromTopEdgeOfHexagon
        // (this.distanceFromLeftEdgeOfHexagon * this.topHeight ) < (this.distanceFromTopEdgeOfHexagon * this.hexsideWidth)
        //
        
        if (this.distanceFromLeftEdgeOfHexagon * this.topHeight < this.distanceFromTopEdgeOfHexagon * this.hexsideWidth)
        {
        //  ______
        //  |\ |  |
        //  | \|  |
        //  |* |\ |
        //  |__|_\|
        //  
          this.hexagon.setXY(hexpartX - 1, hexpartY + 1);
        }
        else
        {
        //  ______
        //  |\ |  |
        //  | \|* |
        //  |  |\ |
        //  |__|_\|
        //  
          this.hexagon.setXY(hexpartX + 1, hexpartY - 1);
        }
        break;

      case 4:
         // check the tangent of the hexside line with tangent of the mappoint
        //
        // see above
        //

        if (this.distanceFromLeftEdgeOfHexagon * this.topHeight < this.distanceFromTopEdgeOfHexagon * this.hexsideWidth)
        {
        //  ______
        //  |  | /|
        //  |* |/ |
        //  | /|  |
        //  |/_|_ |
        //  
          this.hexagon.setXY(hexpartX - 1, hexpartY - 1);
        }
        else
        {
        //  ______
        //  |  | /|
        //  |  |/ |
        //  | /|* |
        //  |/_|_ |
        //  
          this.hexagon.setXY(hexpartX + 1, hexpartY + 1);
        }
        break;
	}
}

MapGrid.prototype.getHexpart = function() {
	return this.hexpart;
}

MapGrid.prototype.getHexagon = function() {
	return this.hexagon;
}

MapGrid.prototype.getPixelX = function() {
    return this.mapGridX;
}

MapGrid.prototype.getPixelY = function() {
    return this.mapGridY;
}
