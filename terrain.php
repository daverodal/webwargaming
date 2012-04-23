<?php
// terrain.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 
    class ReinforceZone{
    public $hexagon;
    public $name;
    function __construct($zoneHexagonName, $zoneName)
{

    $this->hexagon = new Hexagon($zoneHexagonName);
    $this->name = $zoneName;
}}
    class TerrainFeature{
        public $code;
        public $name;
        public $displayName;
        public $letter;
        public $entranceCost;
        public $traverseCost;
        public $combatEffect;
        public $isExclusive;
    function __construct($terrainFeatureCode, $terrainFeatureName, $terrainFeatureDisplayName, $terrainFeatureLetter,
						$terrainFeatureEntranceCost, $terrainFeatureTraverseCost, 
						$terrainFeatureCombatEffect, $terrainFeatureIsExclusive) {


        $this->code = $terrainFeatureCode;
        $this->name = $terrainFeatureName;
        $this->displayName = $terrainFeatureDisplayName;
        $this->letter = $terrainFeatureLetter;
        $this->entranceCost = $terrainFeatureEntranceCost;
        $this->traverseCost = $terrainFeatureTraverseCost;
        $this->combatEffect = $terrainFeatureCombatEffect;
        $this->isExclusive = $terrainFeatureIsExclusive;

    }
    }
class Town{
    public $name, $hexagon;
    function __construct($townName, $townHexagon) {


	$this->name = $townName;
	$this->hexagon = $townHexagon;
}}
    class Terrain{
    public $maxTerrainX;
    public $maxTerrainY;
    public $terrainArray;
    public $towns;
    public $terrainFeatures;
    public $reinforceZones;
    public $allAreAttackingAcrossRiverCombatEffect;
    function __construct($data =  null){

        if($data){
                foreach($data as $k => $v){
                    if($k == "reinforceZones"){
                        $this->reinforceZones = array();
                        foreach($v as $reinforceZone){
                            $this->reinforceZones[] = new ReinforceZone($reinforceZone->hexagon->name, $reinforceZone->name);
                        }
                        continue;
                    }
                    $this->$k = $v;
                }

        }else{
	$this->towns = array();
	$this->terrainFeatures = array();
	$this->reinforceZones = array();
	
	$this->allAreAttackingAcrossRiverCombatEffect = 1;

        $this->maxTerrainY = 60;
        $this->maxTerrainX = 40;

        for ($x = 0; $x < $this->maxTerrainX; $x++) {
            for ($y = 0; $y < $this->maxTerrainY; $y++) {
                $this->terrainArray[$y][$x] = 1;
            }

        }
        //	$this->terrainArray = array(29);
//		for($i = 0; $i < count($this->terrainArray); $i++) {
//		$this->terrainArray[$i] = array(16);
//	}
//
//	$this->maxTerrainX = count($this->terrainArray[0]);
//	$this->maxTerrainY = count($this->terrainArray);
//	//alert( $this->maxTerrainX + ", " + $this->maxTerrainY );
//	for( $y = 0; $y < $this->maxTerrainY; $y++ )
//	{
//		for ( $x = 0; $x < $this->maxTerrainX; $x++ )
//		{
//			$this->terrainArray[$y][$x] = 1;
//		}
//	}
    }
}



function addTown($name, $hexagonName)
{
	$hexagon = new Hexagon($hexagonName);
	$town = new Town($name, $hexagon);
	array_push($this->towns, $town);
}

function getTownName($hexagon) {

	$townName = "";
	for ( $i = 0; $i < count($this->towns); $i++ ) {

		if ( $this->towns[$i]->hexagon->equals($hexagon) ) {

			$townName += $this->towns[$i]->name;
		}
	}
	return $townName;
}


function addTerrainFeature( $name, $displayName, $letter, $entranceCost, $traverseCost, $combatEffect, $isExclusive) {

    $code = pow(2, count($this->terrainFeatures));
	$terrainFeature = new TerrainFeature($code, $name, $displayName, $letter, $entranceCost, $traverseCost, $combatEffect, $isExclusive);
	array_push($this->terrainFeatures, $terrainFeature);
}


function addReinforceZone($hexagonName, $zoneName)
{
	$reinforceZone = new ReinforceZone($hexagonName, $zoneName);
	array_push($this->reinforceZones, $reinforceZone);
}

function getTerrainCode($hexpart) {

    $x = $hexpart->getX();
    $y = $hexpart->getY();
	if ( ( $x >= 0 && $x < $this->maxTerrainX ) && ( $y >= 0 && $y < $this->maxTerrainY ) )
		$terrainCode = $this->terrainArray[$y][$x];
	else
		$terrainCode = 0;

    return $terrainCode;
}

function getTerrainDisplayName($hexpart) {

	$code = $this->getTerrainCode($hexpart);
	$terrainName = "";

		for($i = 0; $i < count($this->terrainFeatures); $i++ ) {

			if( ($this->terrainFeatures[$i]->code & $code) == $this->terrainFeatures[$i]->code ) {

				$terrainName += $this->terrainFeatures[$i]->displayName;
				$terrainName += " ";
			}
		}
	//}
	return $terrainName;
}

function terrainIs($hexpart, $terrainName)
{
    $terrainCode = $this->getTerrainCode($hexpart);
    $found = false;

    for ($i = 0; $i < count($this->terrainFeatures); $i++ )
    {
		// match name
        if ($this->terrainFeatures[$i]->name == $terrainName)
        {
			// get terrain code and check
            $code = $this->terrainFeatures[$i]->code;
            if (($terrainCode & $code) == $code)
            {
                $found = true;
                break;
            }
        }
    }

    return $found;
}

function moveIsTraverse($startHexagon, $endHexagon, $name)
{
	$moveIsTraverse = false;
	$hexsideX = ( $startHexagon->getX() + $endHexagon->getX() ) / 2;
	$hexsideY = ( $startHexagon->getY() + $endHexagon->getY() ) / 2;

	$hexpart = new Hexpart($hexsideX, $hexsideY);
	$endHexpart = new Hexpart();
	$endHexpart->setXY($endHexagon->getX(), $endHexagon->getY());
var_dump($this->terrainIs($hexpart, $name));
    var_dump(dechex($this->getTerrainCode($endHexpart)));
    var_dump($endHexpart);echo "HEx $name Parted";

    var_dump($this->terrainIs($endHexpart, $name));
	if( ( $this->terrainIs($hexpart, $name) == true )
		&& ( $this->terrainIs($hexpart, $name) == true )
		&& ( $this->terrainIs($endHexpart, $name) == true ) )
	{
		$moveIsTraverse = true;
	}

	return $moveIsTraverse;
}

function getTerrainList( ) {


	$myList = "list<br />";

	for( $y = 0; $y <= $this->maxTerrainY; $y++ ) {

		for( $x = 0; $x <= $this->maxTerrainX; $x++ ) {

			$terrainCode = $this->getTerrainCode( $x, $y );

			for ($i = 0; $i < count($this->terrainFeatures); $i++ )
			{
				// get terrain code and check
				$code = $this->terrainFeatures[$i]->code;
				if (($terrainCode & $code) == $code)
				{
					$hexpart = new Hexpart(x, $y);
					if( $code > 1 ) {
						$myList += "   $this->terrain->addTerrain( \"" + $hexpart->getName() + "\", \"" + $this->terrainFeatures[$i]->name + "\" );<br />";
					}
				}
			}
		}
	}
	return $myList;
}

function getTerrainArray() {

	$myArray = "$this->terrainArray = array (";

	for( $y = 0; $y <= $this->maxTerrainY; $y++ ) {

	      $myArray += "<br/>           array ( ";
		for( $x = 0; x <= $this->maxTerrainX; $x++ ) {

			$myArray += " " + $this->getTerrainCode( x, $y);
			if( $x < $this->maxTerrainX) $myArray += ", ";
		}
		$myArray += " )";
		if( $y < $this->maxTerrainY) $myArray += ",";
	}
	$myArray += "<br/>);";

	return $myArray;
}

function getBlankTerrainArray($maxHexpartX, $maxHexpartY) {

	$maxX = $maxHexpartX + 3;
	$maxY = $maxHexpartY + 4;

	$myArray = "$this->terrainArray = array (";

	for( $y = 0; $y <= maxY ; $y++ ) {

	      $myArray += "<br/>           array ( ";
		for( $x = 0; $x <= $maxX; $x++ ) {

			if ( $x >= 4 && $x <= $maxHexpartX && $y >= 8 && $y <= $maxHexpartY )
			{
				$myArray += " 1";
			}
			else
			{
				$myArray += " 0";
			}
			if( $x < $maxX) $myArray += ", ";
		}
		$myArray += " )";
		if( $y < $maxY ) $myArray += ",";
	}
	$myArray += "<br/>);";

	return $myArray;
}

function setTerrain($letter, $x, $y) {

	$code = $this->terrainArray[$y][$x];

	// check for offmap
	if ( $letter == "o" )
	{
		// find offmap code
		for ($eachType = 0; $eachType < count($this->terrainFeatures); $eachType++ )
		{
			if ($this->terrainFeatures[$eachType]->letter == $letter)
			{
				$this->terrainArray[$y][$x] = $this->terrainFeatures[$eachType]->code;
				break;
			}
		}
	}
	else {
		for ($eachType = 0; $eachType < count($this->terrainFeatures); $eachType++ )
		{
			if ($this->terrainFeatures[$eachType]->letter == $letter)
			{
				// if is exclusive
				if ($this->terrainFeatures[$eachType]->isExclusive == true)
				{
					// clear any other exclusive terrain types present
					for ($eachExclusiveCheck = 0; $eachExclusiveCheck < count($this->terrainFeatures); $eachExclusiveCheck++ )
					{
						// is it exclusive
						if ($this->terrainFeatures[$eachExclusiveCheck]->isExclusive == true)
						{
							//  if it is present, remove it
							if ( ($code & $this->terrainFeatures[$eachExclusiveCheck]->code == $this->terrainFeatures[$eachExclusiveCheck]->code) ) {

								$this->terrainArray[$y][$x] = $this->terrainArray[$y][$x] - $this->terrainFeatures[$eachExclusiveCheck]->code;
							}
						}
					}
					// add in exclusive
					$this->terrainArray[$y][$x] = $this->terrainArray[$y][$x] + $this->terrainFeatures[$eachType]->code;
				}
				else
				{
					// toggle terrain type
					// remove it if there
					if ( ($code & $this->terrainFeatures[$eachType]->code == $this->terrainFeatures[$eachType]->code) ) {

						$this->terrainArray[$y][$x] = $this->terrainArray[$y][$x] - $this->terrainFeatures[$eachType]->code;
					}
					// add it if not there
					else {

						$this->terrainArray[$y][$x] = $this->terrainArray[$y][$x] + $this->terrainFeatures[$eachType]->code;
					}
				} // end terrain array modification
			} // end letter match
		} // end looping thru each terrain type
	} // end offmap check
}

function addTerrain($hexagonName, $hexpartType, $terrainName)
{
	$hexagon = new Hexagon($hexagonName);

	$x = $hexagon->getX();
	$y = $hexagon->getY();

	switch ( $hexpartType ) {

		case HEXAGON_CENTER:

			break;

		case BOTTOM_HEXSIDE:

			$y = $y + 2;
			break;

		case LOWER_LEFT_HEXSIDE:

			$x = $x - 1;
			$y = $y + 1;
			break;

		case UPPER_LEFT_HEXSIDE:

			$x = $x - 1;
			$y = $y - 1;
			break;

	}

	for ($eachTerrainFeature = 0; $eachTerrainFeature < count($this->terrainFeatures); $eachTerrainFeature++ )
	{
		if ( $this->terrainFeatures[$eachTerrainFeature]->name == $terrainName )
		{
			// if exclusive, remove any conflicting terrain

			if ( $this->terrainFeatures[$eachTerrainFeature]->isExclusive == true )
			{
				for (  $eachExclusiveType = 0; $eachExclusiveType < count($this->terrainFeatures); $eachExclusiveType++)
				{
					if ( $this->terrainArray[$y][$x] &&
                        $this->terrainFeatures[$eachExclusiveType]->code == $this->terrainFeatures[$eachExclusiveType]->code )
					{
                        $this->terrainArray[$y][$x] = $this->terrainArray[$y][$x] & (~$this->terrainFeatures[$eachExclusiveType]->code);
					}
				}
			}

			$this->terrainArray[$y][$x] = $this->terrainArray[$y][$x] | $this->terrainFeatures[$eachTerrainFeature]->code;
		}
	}
}

function getTerrainTraverseCostFor($name) {

     $traverseCost = 0;

    for (  $i = 0; $i < count($this->terrainFeatures); $i++ )
    {
		$terrainFeature = $this->terrainFeatures[$i];
		if ($terrainFeature->name == $name)
		{
			$traverseCost = $terrainFeature->traverseCost;
		}
    }

	return $traverseCost;
}

function getTerrainEntranceMoveCost($hexagon) {

     $entranceMoveCost = 0;

	 $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());

    for (  $i = 0; $i < count($this->terrainFeatures); $i++ )
    {
		$terrainFeature = $this->terrainFeatures[$i];
		if ($this->terrainIs($hexpart, $terrainFeature->name) == true)
		{
			if ($terrainFeature->entranceCost > $entranceMoveCost)
			{
				$entranceMoveCost = $terrainFeature->entranceCost;
			}
		}
    }

	return $entranceMoveCost;
}

function getTerrainMoveCost($startHexagon, $endHexagon, $maxMoveAmount ,$railMove) {

 	 $moveCost = 0;
 	 $hexsideX = ( $startHexagon->getX() + $endHexagon->getX() ) / 2;
 	 $hexsideY = ( $startHexagon->getY() + $endHexagon->getY()  ) / 2;


	// if road, override terrain
	if ($railMove && $this->moveIsTraverse($startHexagon, $endHexagon, "road") == true) {
 	        $moveCost = 1;
	}
	else {

		// get entrance cost
		$moveCost = $this->getTerrainEntranceMoveCost($endHexagon);

		// check hexside for river
		 $hexpart = new Hexpart($hexsideX, $hexsideY);

//		if( $this->terrainIs($hexpart, "river") == true ) {
//
//			$moveCost = $maxMoveAmount;
//		}
	}

 	// move cost on exit is the entrance cost of the leaving hexagon
	if ( $this->isExit($endHexagon) == true )
	{
		// if leaving road, exit cost is road
		 $endHexpart = new Hexpart($startHexagon->getX(), $startHexagon->getY());

		if ($this->terrainIs($endHexpart, "road") == true) {
 	        $moveCost = $this->getTerrainTraverseCostFor("road");
		}
		else {

			// get entrance cost
			$moveCost = $this->getTerrainEntranceMoveCost($startHexagon);
		}
	}

	return $moveCost;
}

function getTerrainTypeMoveCost($name)
{
	 $moveCost = 0;

	for (  $i = 0; $i < count($this->terrainFeatures); $i++ )
    {
		if ( $this->terrainFeatures[$i]->name == $name )
		{
			$moveCost = $this->terrainFeatures[$i]->entranceCost;
		}
	}
	return $moveCost;
}

function getDefenderTerrainCombatEffect($hexagon,$attackingForceId)
{
    $combatEffect = 0;

	 $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());

    for (  $i = 0; $i < count($this->terrainFeatures); $i++ )
        {
            var_dump($this->terrainFeatures[$i]->name);
            var_dump($attackingForceId);
            if($this->terrainFeatures[$i]->name == "fortified" && $attackingForceId == 2){
                /* German don't benefit from fortificatons */
                continue;
            }
			if ( $this->terrainIs( $hexpart, $this->terrainFeatures[$i]->name ) )
			{
				if ( $this->terrainFeatures[$i]->combatEffect > $combatEffect)
				{
					$combatEffect = $this->terrainFeatures[$i]->combatEffect;
				}
            }
        }
    return $combatEffect;
}


function getAllAreAttackingAcrossRiverCombatEffect()
{
	return $this->allAreAttackingAcrossRiverCombatEffect;
}

function isExit($hexagon) {
 	 $isExit = false;

	 $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());

 	$terrainCode = $this->getTerrainCode($hexpart);

	if ($this->terrainIs($hexpart, "offmap") == true) {
		$isExit = true;
	}
	return $isExit;
}

function getReinforceZone($hexagon)
{
     $zoneName = "";
    for(  $i = 0; $i < count($this->reinforceZones); $i++ )
    {
 //alert("" + i + " " + $this->reinforceZones[$i]->hexagon->getName() + " : " + hexagon->getName());
        if ( $this->reinforceZones[$i]->hexagon->equals($hexagon) == true )
		{
			$zoneName = $this->reinforceZones[$i]->name;
		}
    }

    return $zoneName;
}

function isOnMap($hexagon)
{
	 $isOnMap = true;
	
	 $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());
	
 	if ($this->terrainIs($hexpart, "offmap") == true)
 	{
		$isOnMap = false;
 	}
 	
	return $isOnMap;
}
    }
