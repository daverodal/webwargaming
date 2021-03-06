// Line of Sight object

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

function Los() {
	var originX, originY;
	var endPointX, endPointY;
	var range;
	var bearing;
	var sectors;
	var blocked;
  
  // a sector is either a line or a range between lines on a compass
  
  this.sectors = new Array (
    0,  0, 16, 17, 14, 15, 13,
    0, 18, 20, 19, 22, 21, 23,
    12,  0,  8,  7, 10,  9, 11,
    0,  6,  4,  5,  2,  3,  1);
}

Los.prototype.setOrigin = function( hexagon ) {
	this.originX = hexagon.getX();
	this.originY = hexagon.getY();
}

Los.prototype.setEndPoint = function( hexagon ) {
	this.endPointX = hexagon.getX();
	this.endPointY = hexagon.getY();
}

Los.prototype.getRange = function() {
	var absX = Math.abs( this.endPointX - this.originX );
	var absY = Math.abs( this.endPointY - this.originY );
	if ( absX > absY ) {
		this.range = absX / 2;
	} else {
		this.range = ( absX + absY ) / 4;
	}
	return ( this.range );
}

Los.prototype.getBearing = function() {
	var delta_x, delta_y;
	var absolute_x, absolute_y;
	var x3times, sector, quadrant;

//	step 1. find the delta
	delta_x = this.endPointX - this.originX;
	delta_y = this.endPointY - this.originY;

//      step 2. check if at the origin
	if( delta_x == 0 && delta_y == 0 ) {
		this.bearing = -1;
	} else {
//      step 3. find the sector
 
	absolute_x = Math.abs(delta_x);
	absolute_y = Math.abs(delta_y);
	x3times = 3 * absolute_x;
	if( delta_x == 0 )					sector = 0;
	    else{
	        if(delta_y == 0)				sector = 1;
	        else{
	  	    if( absolute_x == absolute_y)		sector = 2;
		    else{
		        if(absolute_x > absolute_y)		sector = 3;
		        else{
			    if( x3times == absolute_y)		sector = 4;
			    else{
			        if( x3times > absolute_y)	sector = 5;
			        else				sector = 6;
	    		        }
			    }
		        }
		   }
	    }
	}

//	step 4. find the quadrant
	if( delta_x < 0 ) 	{
		if( delta_y > 0)	quadrant = 0; 
		else			quadrant = 1;
	}
	else {
		if( delta_y > 0)	quadrant = 2; 
		else			quadrant = 3;
	}
	
	this.bearing = this.sectors[ ( quadrant * 7 ) + sector ];
	return ( this.bearing );
}

Los.prototype.getFacingNumber = function() {
	
	return (Math.floor(this.bearing / 4) + 1);
}

Los.prototype.getLosList = function() {

	var losArray = new Array();
	
	var b, x, y, i, hexsideX, hexsideY;
	var offset1, offset2;

	var stepX = new Array (  0,  2,  2,  4,  2,  2,  0, -2, -2, -4, -2, -2,  0 );
	var stepY = new Array ( -4, -6, -2,  0,  2,  6,  4,  6,  2,  0, -2, -6, -4 );

	b = this.getBearing();

	if ( b >= 0 ) {

	// for even bearing numbers
	  i =  Math.floor( b / 2) ;
	  if ( ( b % 2 ) == 0 ) {
		x = this.originX;
		y = this.originY;
        
        var hexpart1 = new Hexpart(x, y);
		losArray.push(hexpart1);
		do {
			// do hexside first
			hexsideX = ( x + (x + stepX[i]) ) / 2;
			hexsideY = ( y + (y + stepY[i]) ) / 2;
			
            var hexpart2 = new Hexpart(hexsideX, hexsideY);
            losArray.push(hexpart2);
			
			// then do hexagon
			x = x + stepX[i];
			y = y + stepY[i];

            var hexpart3 = new Hexpart(x, y);
            losArray.push(hexpart3);
        } while ( ( x != this.endPointX ) || ( y != this.endPointY ));

	  } else {
	// for odd bearing numbers
		i = Math.floor( b / 4) * 2 ;
		x = this.originX;
		y = this.originY;
		
        var hexpart4 = new Hexpart(x, y);
		losArray.push(hexpart4);
		
		do {
			x1 = x + stepX[i];
			y1 = y + stepY[i];
			x2 = x + stepX[i+2];
			y2 = y + stepY[i+2];
			
			// it's this easy
			offset1 = Math.abs( this.originX*this.endPointY - this.originX*y1 - this.endPointX*this.originY + this.endPointX*y1 + x1*this.originY - x1*this.endPointY ); 
			offset2 = Math.abs( this.originX*this.endPointY - this.originX*y2 - this.endPointX*this.originY + this.endPointX*y2 + x2*this.originY - x2*this.endPointY ); 
		
			if ( offset1 == offset2 )
			{
				// double hexagon traverse
				// add first of near hexagons
				var hexpart5 = new Hexpart(x1, y1);
				losArray.push(hexpart5);
			
				// add second of near hexagon
				var hexpart6 = new Hexpart(x2, y2);
				losArray.push(hexpart6);
			
				// add hexside
				hexsideX =  x + ((stepX[i] + stepX[i+2]) / 2 );
				hexsideY =  y + ((stepY[i] + stepY[i+2]) / 2 );
				
				// add hexagon which is at range of 2
				var hexpart7 = new Hexpart(hexsideX, hexsideY);
				losArray.push(hexpart7);
			
				x = x + stepX[i] + stepX[i+2];
				y = y + stepY[i] + stepY[i+2];

				var hexpart8 = new Hexpart(x, y);
				losArray.push(hexpart8);				
			}
			else
			{
				if ( offset1 < offset2 ) {
					hexsideX = ( x + x1 ) / 2;
					hexsideY = ( y + y1 ) / 2;
					x = x1;
					y = y1;
				} else {
					hexsideX = ( x + x2 ) / 2;
					hexsideY = ( y + y2 ) / 2;
					x = x2;
					y = y2;
				}
			
				var hexpart9 = new Hexpart(hexsideX, hexsideY);
				losArray.push(hexpart9);
		
				var hexpart10 = new Hexpart(x, y);
				losArray.push(hexpart10);
            }
		
		} while ( ( x != this.endPointX) || ( y != this.endPointY ) );		
	  }
 	}
		return losArray;
}
