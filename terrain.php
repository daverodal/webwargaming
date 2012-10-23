<?php
// terrain.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 
class ReinforceZone
{
    public $hexagon;
    public $name;

    function __construct($zoneHexagonName, $zoneName)
    {

        $this->hexagon = new Hexagon($zoneHexagonName);
        $this->name = $zoneName;
    }
}

class TerrainFeature
{
    public $name;
    public $displayName;
    public $letter;
    public $entranceCost;
    public $traverseCost;
    public $combatEffect;
    public $isExclusive;

    function __construct($terrainFeatureName, $terrainFeatureDisplayName, $terrainFeatureLetter,
                         $terrainFeatureEntranceCost, $terrainFeatureTraverseCost,
                         $terrainFeatureCombatEffect, $terrainFeatureIsExclusive)
    {


        $this->name = $terrainFeatureName;
        $this->displayName = $terrainFeatureDisplayName;
        $this->letter = $terrainFeatureLetter;
        $this->entranceCost = $terrainFeatureEntranceCost;
        $this->traverseCost = $terrainFeatureTraverseCost;
        $this->combatEffect = $terrainFeatureCombatEffect;
        $this->isExclusive = $terrainFeatureIsExclusive;

    }
}

class Town
{
    public $name, $hexagon;

    function __construct($townName, $townHexagon)
    {


        $this->name = $townName;
        $this->hexagon = $townHexagon;
    }
}

class Terrain
{
    public $maxTerrainX;
    public $maxTerrainY;
    public $terrainArray;
    public $towns;
    public $terrainFeatures;
    public $reinforceZones;
    public $allAreAttackingAcrossRiverCombatEffect;

    function __construct($data = null)
    {

        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "reinforceZones") {
                    $this->reinforceZones = array();
                    foreach ($v as $reinforceZone) {
                        $this->reinforceZones[] = new ReinforceZone($reinforceZone->hexagon->name, $reinforceZone->name);
                    }
                    continue;
                }
                $this->$k = $v;
            }

        } else {
            $this->towns = array();
            $this->terrainFeatures = new stdClass();
            $this->reinforceZones = array();

            $this->allAreAttackingAcrossRiverCombatEffect = 1;

            $this->maxTerrainY = 60;
            $this->maxTerrainX = 54;

            for ($x = 0; $x < $this->maxTerrainX; $x++) {
                for ($y = 0; $y < $this->maxTerrainY; $y++) {
                    $this->terrainArray[$y][$x] = new stdClass();
                }

            }
        }
    }


    /*
     * can be removed
     */
    /*
function addTown($name, $hexagonName)
{
$hexagon = new Hexagon($hexagonName);
$town = new Town($name, $hexagon);
array_push($this->towns, $town);
}*/
    /*
     * can be removed
     */
    /*
function getTownName($hexagon) {

$townName = "";
for ( $i = 0; $i < count($this->towns); $i++ ) {

    if ( $this->towns[$i]->hexagon->equals($hexagon) ) {

        $townName += $this->towns[$i]->name;
    }
}
return $townName;
}
*/
    /*
     * public
     */
    function addTerrainFeature($name, $displayName, $letter, $entranceCost, $traverseCost, $combatEffect, $isExclusive)
    {

        $this->terrainFeatures->$name = new TerrainFeature($name, $displayName, $letter, $entranceCost, $traverseCost, $combatEffect, $isExclusive);
    }

    /*
     * often used!
     */
    function addTerrain($hexagonName, $hexpartType, $terrainName)
    {
        $hexagon = new Hexagon($hexagonName);

        $x = $hexagon->getX();
        $y = $hexagon->getY();
        switch ($hexpartType) {

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
        if ($feature = $this->terrainFeatures->$terrainName) {
            if ($feature->isExclusive === true) {
                $this->terrainArray[$y][$x] = new stdClass();
            }
            $this->terrainArray[$y][$x]->$terrainName = $terrainName;
        }

    }

    /*
     * public init
     */
    function addReinforceZone($hexagonName, $zoneName)
    {
        $reinforceZone = new ReinforceZone($hexagonName, $zoneName);
        array_push($this->reinforceZones, $reinforceZone);
    }

    /*
     * private !
     */
    private function getTerrainCode($hexpart)
    {

        $x = $hexpart->getX();
        $y = $hexpart->getY();
        if (($x >= 0 && $x < $this->maxTerrainX) && ($y >= 0 && $y < $this->maxTerrainY))
            $terrainCode = $this->terrainArray[$y][$x];
        else
            $terrainCode = 0;

        return $terrainCode;
    }

    /*
     * can be removed
     */
    /*
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
}*/
    /*
     * public
     */
    function terrainIs($hexpart, $terrainName)
    {
        $terrainCode = $this->getTerrainCode($hexpart);
        $found = false;
        if ($terrainCode->$terrainName) {
            return true;
        }
        return false;
    }

    /*
     * move rules
     */
    function moveIsInto($hexagon, $name)
    {
        $hexpart = new Hexpart();
        $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

        $moveIsInto = false;
        if (($this->terrainIs($hexpart, $name) == true)) {
            $moveIsInto = true;
        }

        return $moveIsInto;
    }

    /*
     * can be private
     */
    private function moveIsTraverse($startHexagon, $endHexagon, $name)
    {
        $moveIsTraverse = false;
        $hexsideX = ($startHexagon->getX() + $endHexagon->getX()) / 2;
        $hexsideY = ($startHexagon->getY() + $endHexagon->getY()) / 2;

        $hexpart = new Hexpart($hexsideX, $hexsideY);
        $endHexpart = new Hexpart();
        $endHexpart->setXY($endHexagon->getX(), $endHexagon->getY());

        if (($this->terrainIs($hexpart, $name) == true)
            && ($this->terrainIs($hexpart, $name) == true)
            && ($this->terrainIs($endHexpart, $name) == true)
        ) {
            $moveIsTraverse = true;
        }

        return $moveIsTraverse;
    }


    /*
     * can be private
     */
    private function getTerrainTraverseCostFor($name)
    {

        $traverseCost = 0;
        if ($this->terrainFeatures->$name) {
            return $this->terrainFeatures->$name->traverseCost;
        }
        return 0;
        /*for (  $i = 0; $i < count($this->terrainFeatures); $i++ )
        {
            $terrainFeature = $this->terrainFeatures->$i;
            if ($terrainFeature->name == $name)
            {
                $traverseCost = $terrainFeature->traverseCost;
            }
        }

        return $traverseCost;*/
    }

    private function getTerrainTraverseMoveCost($startHexagon, $endHexagon)
    {
        $hexsideX = ($startHexagon->getX() + $endHexagon->getX()) / 2;
        $hexsideY = ($startHexagon->getY() + $endHexagon->getY()) / 2;

        $hexpart = new Hexpart($hexsideX, $hexsideY);
        $terrainCode = $this->getTerrainCode($hexpart);
        $traverseCost = 0;
        foreach ($terrainCode as $code) {
            $traverseCost += $this->terrainFeatures->$code->traverseCost;
        }
        return $traverseCost;
    }

    /*
     * can be private
     */
    private function getTerrainEntranceMoveCost($hexagon)
    {

        $entranceCost = 0;

        $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());
        $terrains = $this->terrainArray[$hexagon->getY()][$hexagon->getX()];

        foreach ($terrains as $terrainFeature) {
            $entranceCost += $this->terrainFeatures->$terrainFeature->entranceCost;
        }
        return $entranceCost;
        for ($i = 0; $i < count($this->terrainFeatures); $i++) {
            $terrainFeature = $this->terrainFeatures[$i];
            if ($this->terrainIs($hexpart, $terrainFeature->name) == true) {
                if ($terrainFeature->entranceCost > $entranceMoveCost) {
                    $entranceMoveCost = $terrainFeature->entranceCost;
                }
            }
        }

        return $entranceMoveCost;
    }

    /*
     * very public
     * used in moveRules
     */
    function getTerrainMoveCost($startHexagon, $endHexagon, $maxMoveAmount, $railMove)
    {

        $moveCost = 0;
        $hexsideX = ($startHexagon->getX() + $endHexagon->getX()) / 2;
        $hexsideY = ($startHexagon->getY() + $endHexagon->getY()) / 2;


        // if road, override terrain
        if ($railMove && $this->moveIsTraverse($startHexagon, $endHexagon, "road") == true) {
            $moveCost = 1;
        } else {

            // get entrance cost
            $moveCost = $this->getTerrainEntranceMoveCost($endHexagon);
            $moveCost += $this->getTerrainTraverseMoveCost($startHexagon, $endHexagon);

            // check hexside for river
            $hexpart = new Hexpart($hexsideX, $hexsideY);

//		if( $this->terrainIs($hexpart, "river") == true ) {
//
//			$moveCost = $maxMoveAmount;
//		}
        }

        // move cost on exit is the entrance cost of the leaving hexagon
        if ($this->isExit($endHexagon) == true) {
            // if leaving road, exit cost is road
            $endHexpart = new Hexpart($startHexagon->getX(), $startHexagon->getY());

            if ($this->terrainIs($endHexpart, "road") == true) {
                $moveCost = $this->getTerrainTraverseCostFor("road");
            } else {

                // get entrance cost
                $moveCost = $this->getTerrainEntranceMoveCost($startHexagon);
            }
        }

        return $moveCost;
    }

    /*
     * public used in combatRules
     */
    function getDefenderTerrainCombatEffect($hexagon, $attackingForceId)
    {
        $combatEffect = 0;

        $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());


        $terrains = $this->terrainArray[$hexagon->getY()][$hexagon->getX()];
        foreach ($terrains as $terrainFeature) {
            if ($terrainFeature == "fortified" && $attackingForceId == 2) {
                /* German don't benefit from fortificatons */
                continue;
            }
            $combatEffect += $this->terrainFeatures->$terrainFeature->combatEffect;
        }
        return $combatEffect;


        for ($i = 0; $i < count($this->terrainFeatures); $i++) {
            if ($this->terrainFeatures->$i->name == "fortified" && $attackingForceId == 2) {
                /* German don't benefit from fortificatons */
                continue;
            }
            if ($this->terrainIs($hexpart, $this->terrainFeatures->$i->name)) {
                if ($this->terrainFeatures->$i->combatEffect > $combatEffect) {
                    $combatEffect = $this->terrainFeatures->$i->combatEffect;
                }
            }
        }
        return $combatEffect;
    }

    /*
     * public used in combatRules
     */
    function getDefenderTraverseCombatEffect($startHexagon, $endHexagon, $attackingForceId)
    {
        $combatEffect = 0;
        $hexsideX = ($startHexagon->getX() + $endHexagon->getX()) / 2;
        $hexsideY = ($startHexagon->getY() + $endHexagon->getY()) / 2;

        $hexpart = new Hexpart($hexsideX, $hexsideY);

        $terrains = $this->terrainArray[$hexpart->getY()][$hexpart->getX()];
        foreach ($terrains as $terrainFeature) {

            $combatEffect += $this->terrainFeatures->$terrainFeature->combatEffect;
        }
        return $combatEffect;

    }

    /*
     * public used in combatRules
     */
    function getAllAreAttackingAcrossRiverCombatEffect()
    {
        return $this->allAreAttackingAcrossRiverCombatEffect;
    }

    /*
     * public used lots
     */
    function isExit($hexagon)
    {
        $isExit = false;

        $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());

        $terrainCode = $this->getTerrainCode($hexpart);

        if ($this->terrainIs($hexpart, "offmap") == true) {
            $isExit = true;
        }
        return $isExit;
    }

    /*
     * public moveRules
     */
    function getReinforceZone($hexagon)
    {
        $zoneName = "";
        for ($i = 0; $i < count($this->reinforceZones); $i++) {
            //alert("" + i + " " + $this->reinforceZones[$i]->hexagon->getName() + " : " + hexagon->getName());
            if ($this->reinforceZones[$i]->hexagon->equals($hexagon) == true) {
                $zoneName = $this->reinforceZones[$i]->name;
            }
        }

        return $zoneName;
    }
    /*
     * can be removed
     */
    /*
function isOnMap($hexagon)
{
    $isOnMap = true;

    $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());

    if ($this->terrainIs($hexpart, "offmap") == true)
    {
       $isOnMap = false;
    }

   return $isOnMap;
}*/
}
