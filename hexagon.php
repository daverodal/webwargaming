<?php
// Hexagon.php
//
// Copyright (c) 2009-2011 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */

// Hexagon Constructor

class extHex extends Hexagon implements JsonSerializable{
    public function jsonSerialize(){
        return $this;
    }

}
class HexPath implements JsonSerializable{
    public $name = false;
    public $pointsLeft = false;
    public $isZoc = false;
    public $isValid = true;
    public $isOccupied = false;
    public $pathToHere = array();
    public $depth = false;
    public $firstHex = false;
    public function jsonSerialize(){
        unset($this->isZoc);
        unset($this->isValid);
//        unset($this->isOccupied);
        unset($this->name);
        unset($this->depth);
        unset($this->firstHex);
        return $this;
    }

}
class Hexagon  {

	public static $evenColumnShiftDown;
	public $number;
	public $x = false, $y = false;
	public $name;
    private static $setup = false;
	public static $minX, $minY;
	public static $maxX, $maxY;
    public $parent = "gameImages";

    public static function setMinMax(){
        $mapData = MapData::getInstance();
        $x = $mapData->maxX;
        $y = $mapData->maxY;

        self::$evenColumnShiftDown = true;
        self::$minX = 4;
        self::$minY = 8;

        self::$maxY = 4 * ( $y - 1 ) + self::$minY + 2;

//        if(self::$evenColumnShiftDown == true)
//        {
//            if ( $x % 2 == 0 ) self::$maxY += 2;
//        } else {
//            if ( $x % 2 == 0 ) self::$maxY -= 2;
//        }

        self::$maxX = 2 * ( $x - 1 ) + self::$minX;
        self::$setup = true;

    }

    function __construct($a1 = false, $a2 = false){
        if(!self::$setup){
            self::setMinMax();
        }
//        $mapData = MapData::getInstance();

//        self::$evenColumnShiftDown = true;
        $this->number = 0;
        $this->x = 0;
        $this->y = 0;
        $this->name = "";
//        self::$minX = 4;
//        self::$minY = 8;
//        self::$maxX = $mapData->maxX * 2 + 2;
//       self::$maxY = $mapData->maxY * 4 + 4 + 2;

        // jej
//        $x = $mapData->maxX;
//        $y = $mapData->maxY;
//
//        self::$maxY = 4 * ( $y - 1 ) + self::$minY;
//
//        if(self::$evenColumnShiftDown == true)
//        {
//            if ( $x % 2 == 0 ) self::$maxY += 2;
//        } else {
//            if ( $x % 2 == 0 ) self::$maxY -= 2;
//        }
//
//        self::$maxX = 2 * ( $x - 1 ) + self::$minX;
        // jejej


        // Hexagon(name)
        if ( $a1 !== false && $a2 === false ) {
          if(preg_match("/^[a-z]/",$a1)){
              $this->name = "0000";
              $this->parent = $a1;
          }  else{
            $this->name = $a1;
          }
            $this->number = intVal($this->name, 10);
            $this->calculateHexpartXY();
            $this->calculateHexagonName();
        }

        // Hexagon(x, y)
        if ( $a1 !== false && $a2 !== false ) {

                $this->x = $a1;
                $this->y = $a2;
                $this->calculateHexagonNumber();
                $this->calculateHexagonName();
        }
    }

    function getParent(){
        return $this->parent;
    }
function setNumber($number )
{
	$this->number = $number;
	$this->calculateHexpartXY();
	$this->calculateHexagonName();
}

private function setName( $name )
{
	$this->name = $name;
	$this->number = parseInt($this->name, 10);
	$this->calculateHexagonXY();
}

function setXY( $x, $y ) {

	$this->x = $x;
	$this->y = $y;

	$this->calculateHexagonNumber();	
	$this->calculateHexagonName();
}

private function calculateHexagonNumber()
{
  	$x = ( ($this->x - self::$minX ) / 2 ) + 1;

	if(self::$evenColumnShiftDown == true)
	{
    		$y = floor((($this->y - self::$minY) / 4) + 1);
  	} else {
    		$y = floor((($this->y - self::$minY + 2) / 4) + 1);
  	}
  	$this->number = $x * 100 + $y;
}
public static function getHexPartXY($name){
    if(is_a($name, "Hexagon")){
        $name = $name->name;
    }
    if(!self::$setup){
        self::setMinMax();
    }
    $x = floor( $name / 100 );
    $y = $name - ( $x * 100 );

    $retY = 4 * ( $y - 1 ) + self::$minY;

    if(self::$evenColumnShiftDown == true)
    {
        if ( $x % 2 == 0 ) $retY += 2;
    } else {
        if ( $x % 2 == 0 ) $retY -= 2;
    }

    $retX = 2 * ( $x - 1 ) + self::$minX;
    if ($retX >= self::$minX && $retY >= self::$minY && $retX <= self::$maxX && $retY <= self::$maxY){
        return array($retX, $retY);
    }

    return false;
}
private function calculateHexpartXY() {


 	$x = floor( $this->number / 100 );
	$y = $this->number - ( $x * 100 );

 	$this->y = 4 * ( $y - 1 ) + self::$minY;

	if(self::$evenColumnShiftDown == true)
	{
		if ( $x % 2 == 0 ) $this->y += 2;
	} else {
		if ( $x % 2 == 0 ) $this->y -= 2;
	}
	
	$this->x = 2 * ( $x - 1 ) + self::$minX;
}



private function calculateHexagonName() {

    $this->name = "";
    
	if ($this->x >= self::$minX && $this->y >= self::$minY && $this->x <= self::$maxX && $this->y <= self::$maxY)
	{
	    if ($this->number < 1000) {
	        $this->name = "0" + $this->number;
	    }
	    else {
	        $this->name = $this->number;
	    }
    }
}

function equals(Hexagon $hexagon) {

	$isEqual = false;
	
	if ( $this->x == $hexagon->getX() && $this->y == $hexagon->getY())
	{
		$isEqual = true;
	}
	
	return $isEqual;
}

 function getAdjacentHexagon( $direction ) {

	// direction 1=N, 2=NE, 3=SE, 4=S, 5=SW, 6=NW
	$adjX = array( 0,  0,  2,  2,  0, -2, -2 );
	$adjY = array( 0, -4, -2,  2,  4,  2, -2 );
	$this->x += $adjX[$direction];
	$this->y += $adjY[$direction];

    $this->calculateHexagonNumber();
	$this->calculateHexagonName();
}

function getX() {

	return $this->x;
}

function getY() {

    return $this->y;
}

function getNumber() {

	return $this->number;
}

function getName()
{
    $name = $this->name;

    if (preg_match("/^[a-z]/", $name)) {
        return $name;
    }
    $name = "0000".strval($this->number);
    $name = substr($name,-4);
    return $name;

}
}
