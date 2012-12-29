<?php
//  Hexpart
//
// copyright (c) 1998-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

// Hexpart Constructor
class Hexpart
{
	public $x, $y;
	public $refHexpartX, $refHexpartY;
	public $hexpartType;
	public $name;
	public $prefix;
	
    function __construct(){
	// Hexpart(name)
    $arguments = func_get_args();
	if ( count($arguments) == 1 )
	{
		$this->name = $arguments[0];
		$this->calculateHexpart();
	}
	
	// Hexpart(x,y)
	if (count($arguments) == 2 )
	{
		$this->x = $arguments[0];
		$this->y = $arguments[1];
		
//		$this->calculateHexpartType();
//		$this->calculateHexpartName();
	}
}

function setXY($x, $y)
{
	$this->x = $x;
	$this->y = $y;;
		
	$this->calculateHexpartType();
	$this->calculateHexpartName();
}

function setXYwithNameAndType( $hexagonName, $hexpartType )
{
	$hexagon = new Hexagon($hexagonName);//

	$this->x = $hexagon->getX();
	$this->y = $hexagon->getY();

	switch ( $hexpartType ) {
	
		case HEXAGON_CENTER:
			
			break;
			
		case BOTTOM_HEXSIDE:
		
			$this->y = $this->y + 2;
			break;
			
		case LOWER_LEFT_HEXSIDE:
		
			$this->x = $this->x - 1;
			$this->y = $this->y + 1;
			break;
			
		case UPPER_LEFT_HEXSIDE:
		
			$this->x = $this->x - 1;
			$this->y = $this->y - 1;
			break;	
	}
}

function setName( $hexpartName )
{
	$this->name = $hexpartName;

	$this->calculateHexpart();
}

function calculateHexpartType() {

	// center = 1, lower = 2, lower left = 3, upper left = 4
     $this->hexpartType = 0;

     // 8 cases
	switch ( $this->x % 4 ) {
 		case 0:
			switch ( $this->y % 4 ) {
				case 0:
					$this->hexpartType = 1;
					break;
				case 2:
					$this->hexpartType = 2;
					break;
			}
			break;

 		case 1:
			switch ( $this->y % 4 ) {
				case 1:
					$this->hexpartType = 4;
					break;
				case 3:
					$this->hexpartType = 3;
					break;
			}
			break;

 		case 2:
			switch ( $this->y % 4 ) {
				case 0:
					$this->hexpartType = 2;
					break;
				case 2:
					$this->hexpartType = 1;
					break;
			}
	 		break;

	 	case 3:
			switch ( $this->y % 4 ) {
				case 1:
					$this->hexpartType = 3;
					break;
				case 3:
					$this->hexpartType = 4;
					break;
			}
			break;

	 	default:
			$this->hexpartType = 0;
	    }
} 

function calculateHexpartName() {

//	var name;

	// center = 1, lower = 2, lower left = 3, upper left = 4

 	switch ( $this->hexpartType ) {

	    case 1:
			$this->refHexpartX = $this->x;
			$this->refHexpartY = $this->y;
			$this->prefix = "hexpart:";
			break;

	    case 2:
			$this->refHexpartX = $this->x;
			$this->refHexpartY = $this->y - 2;
			$this->prefix = "hexpart_";
			break;

	    case 3:
			$this->refHexpartX = $this->x + 1;
			$this->refHexpartY = $this->y - 1;
			$this->prefix = "hexpart\\";
			break;

	    case 4:
			$this->refHexpartX = $this->x + 1;
			$this->refHexpartY = $this->y + 1;
			$this->prefix = "hexpart/";
			break;
	}

	if ( $this->hexpartType > 0 ) {

	   $refHexagon = new Hexagon();

		$refHexagon->setXY($this->refHexpartX, $this->refHexpartY);
		$this->name = $this->prefix + $refHexagon->getName();

	} else {

		$this->hexpartName = "null";
	}
}
 
function calculateHexpart()
{
	// center = :, lower = _, lower left = \\, upper left = /
	//    since \ is a javascript escape char, need to check for \\

	$hexagon = new Hexagon();
	$hexagon->setNumber($this->name->substr(8,4));
	
	$hexpartTypeLetter = $this->name->charAt(7);

	$this->refHexpartX = $hexagon->getX();
	$this->refHexpartY = $hexagon->getY();
	
	switch ( $hexpartTypeLetter ) {
	
		case ':':
		
			$this->hexpartType = 1;
			$this->x = $this->refHexpartX;
			$this->y = $this->refHexpartY;
			break;
			
		case '_':
		
			$this->hexpartType = 2;
			$this->x = $this->refHexpartX;
			$this->y = $this->refHexpartY + 2;
			break;
			
		case '\\':
		
			$this->hexpartType = 3;
			$this->x = $this->refHexpartX - 1;
			$this->y = $this->refHexpartY + 1;
			break;
			
		case '/':
		
			$this->hexpartType = 4;
			$this->x = $this->refHexpartX - 1;
			$this->y = $this->refHexpartY - 1;
			break;
			
		default:
		
			$this->hexpartType = 1;
			$this->x = $this->refHexpartX;
			$this->y = $this->refHexpartY;
			break;
	}
}

function equals($hexpart)
{
//    var isEqual;
    $isEqual = false;
    
    if ( $this->x == $hexpart->getX() && $this->y == $hexpart->getY() )
    {
      $isEqual = true;
    }
    
    return $isEqual;
}

function getName()
{
	return $this->name;
}

function getX()
{
	return $this->x;
}

function getY() {

	return $this->y;
}

function getHexpartType()
{
	return $this->hexpartType;
}

function getHexpartTypeName() {

    $hexpartTypeName = array("", "center", "lower", "lowerLeft", "lowerRight");

    return $hexpartTypeName[$this->hexpartType];
}

}