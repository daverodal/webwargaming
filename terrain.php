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
    public $altEntranceCost;
    public $blocksRanged;

    function __construct($terrainFeatureName, $terrainFeatureDisplayName, $terrainFeatureLetter,
                         $terrainFeatureEntranceCost, $terrainFeatureTraverseCost,
                         $terrainFeatureCombatEffect, $terrainFeatureIsExclusive, $blocksRanged)
    {


        $this->name = $terrainFeatureName;
        $this->displayName = $terrainFeatureDisplayName;
        $this->letter = $terrainFeatureLetter;
        $this->entranceCost = $terrainFeatureEntranceCost;
        $this->traverseCost = $terrainFeatureTraverseCost;
        $this->combatEffect = $terrainFeatureCombatEffect;
        $this->isExclusive = $terrainFeatureIsExclusive;
        $this->blocksRanged = $blocksRanged;
        $this->altEntranceCost = new stdClass();

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

            $mapData = MapData::getInstance();


            $x = $mapData->maxX;
            $y = $mapData->maxY;
            $hexName = sprintf("%02d%02d",$x,$y);

            list($x, $y) = Hexagon::getHexPartXY($hexName);
            $this->maxTerrainY = $y + 4;/* add 4 for bottom and even/odd columns */
            $this->maxTerrainX = $x;

//            for ($x = 0; $x <= $this->maxTerrainX; $x++) {
//                for ($y = 0; $y <= $this->maxTerrainY; $y++) {
//                    $this->terrainArray[$y][$x] = new stdClass();
//                }
//
//            }
        }
    }

    public function addAltEntranceCost($terrain,$altClass,$entranceCost){
        $feature = $this->terrainFeatures->$terrain;
        if($feature){
            $feature->altEntranceCost->$altClass = $entranceCost;
        }
    }

    public function addNatAltEntranceCost($terrain, $nationality,$altClass,$entranceCost){
        $feature = $this->terrainFeatures->$terrain;
        if($feature){
            if(!$feature->altEntranceCost->$nationality){
                $feature->altEntranceCost->$nationality = new stdClass();
            }
            $feature->altEntranceCost->$nationality->$altClass = $entranceCost;
        }
    }
    /* this method will die someday  sooner than later */
    public function setMaxHex(){
        return;
        $mapData = MapData::getInstance();


        $x = $mapData->maxX;
        $y = $mapData->maxY;
        $hexName = sprintf("%02d%02d",$x,$y);

        new Hexagon();
        list($x, $y) = Hexagon::getHexPartXY($hexName);
        $this->maxTerrainY = $y + 4;/* for bottom and even odd columns */
        $this->maxTerrainX = $x;


//        for ($x = 0; $x < $this->maxTerrainX; $x++) {
//            for ($y = 0; $y <= $this->maxTerrainY; $y++) {
//                $this->terrainArray[$y][$x] = new stdClass();
//            }
//
//        }
    }

    /*
     * public
     */
    function addTerrainFeature($name, $displayName, $letter, $entranceCost, $traverseCost, $combatEffect, $isExclusive, $blocksRanged = false)
    {

        $this->terrainFeatures->$name = new TerrainFeature($name, $displayName, $letter, $entranceCost, $traverseCost, $combatEffect, $isExclusive, $blocksRanged);
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
        if(!$this->terrainArray[$y][$x]){
            $this->terrainArray[$y][$x] = new stdClass();
        }
        if ($feature = $this->terrainFeatures->$terrainName) {
            /* new feature is exclusive */
            if ($feature->isExclusive === true) {
                $thisHexpart = $this->terrainArray[$y][$x];
                /* reset all exclusive terrains found, leave non exclusive ones */
                foreach($thisHexpart as $thisTerrainName => $thisTerrainValue){
                    if($this->terrainFeatures->$thisTerrainName->isExclusive){
                        unset($this->terrainArray[$y][$x]->$thisTerrainName);
                    }
                }
//                $this->terrainArray[$y][$x] = new stdClass();
            }
            $this->terrainArray[$y][$x]->$terrainName = $terrainName;
            if($feature->blocksRanged){
                $this->terrainArray[$y][$x]->blocksRanged = "blocksRanged";
            }
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
    private function getTerrainCode(Hexpart $hexpart)
    {
        $x = $hexpart->getX();
        $y = $hexpart->getY();
        return $this->getTerrainCodeXY($x, $y);
    }


    /*
     * private !
     */
    private function getTerrainCodeXY($x,$y)
    {
        if (($x >= 0 && $x <= $this->maxTerrainX) && ($y >= 0 && $y <= $this->maxTerrainY)){
            if(isset($this->terrainArray) && isset($this->terrainArray->$y) && isset($this->terrainArray->$y->$x)){
                $terrainCode = $this->terrainArray->$y->$x;
            }else{
                $terrainCode = new stdClass();
            }
        }else{
            $terrainCode = 0;
        }
        if(!$terrainCode){
            $terrainCode = new stdClass();
        }
        return $terrainCode;
    }

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
     * public
     * Check the hexside AND destination Hex
     */
    function terrainIsHexSide($startHex,$endHex, $terrainName)
    {
        list($startX, $startY) = Hexagon::getHexPartXY($startHex);
        list($endX, $endY) = Hexagon::getHexPartXY($endHex);
        $hexsideX = ($startX + $endX) / 2;
        $hexsideY = ($startY + $endY) / 2;

        $terrainCode = $this->getTerrainCodeXY($hexsideX,$hexsideY);
        if ($terrainCode->$terrainName) {
            return true;
        }
        $terrainCode = $this->getTerrainCodeXY($endX, $endY);
        if($terrainCode->$terrainName){
            return true;
        }
        return false;
    }

    /*
     * public
     * Check the hexside AND destination Hex
     */
    function terrainIsHex($hex, $terrainName)
    {
        list($endX, $endY) = Hexagon::getHexPartXY($hex);

        $terrainCode = $this->getTerrainCodeXY($endX, $endY);
        if($terrainCode->$terrainName){
            return true;
        }
        return false;
    }
    /*
     * public
     */
    function terrainIsXY($x,$y, $terrainName)
    {
        $terrainCode = $this->getTerrainCodeXY($x,$y);
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


    private function isHexpart($hexpart, $name){
        return $this->terrainIs($hexpart,$name);
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
        $terrainCode = $this->getTerrainCodeXY($hexsideX,$hexsideY);
        $traverseCost = 0;
        foreach ($terrainCode as $code) {
            $traverseCost += $this->terrainFeatures->$code->traverseCost;
        }
        return $traverseCost;
    }

    /*
     * can be private
     */
    private function getTerrainEntranceMoveCost($hexagon, unit $unit)
    {

        $entranceCost = 0;

        $name = $hexagon->name;
//        $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());
        list($endX, $endY) = Hexagon::getHexPartXY($hexagon);

        $terrains = $this->getTerrainCodeXY($endX,$endY);

        foreach ($terrains as $terrainFeature) {
            /* @var TerrainFeature $feature */
            if($terrainFeature == "road" || $terrainFeature == "trail"){
                continue;
            }
            $feature = $this->terrainFeatures->$terrainFeature;
            if($unit->nationality && $unit->class && $feature->altEntranceCost->{$unit->nationality}->{$unit->class}){
                    $entranceCost += $feature->altEntranceCost->{$unit->nationality}->{$unit->class};
            }else if($unit->class && $feature->altEntranceCost->{$unit->class}){
                $entranceCost += $feature->altEntranceCost->{$unit->class};
            }else{
                $entranceCost += $feature->entranceCost;
            }
        }
        return $entranceCost;
    }

    private function getTerrainXYCost($x,$y){
        $terrainCode = $this->getTerrainCodeXY($x,$y);
        $traverseCost = 0;
        foreach ($terrainCode as $code) {
            $traverseCost += $this->terrainFeatures->$code->traverseCost;
        }
        return $traverseCost;

    }

    private function getTerrainCodeCost($terrainCode, $unit){
        $traverseCost = 0;
        foreach ($terrainCode as $code) {
                $feature = $this->terrainFeatures->$code;
            if($unit->nationality && $unit->class && $feature->altEntranceCost->{$unit->nationality}->{$unit->class}){
                $traverseCost += $feature->altEntranceCost->{$unit->nationality}->{$unit->class};
            }else if($unit->class && $feature->altEntranceCost->{$unit->class}){
                $traverseCost += $feature->altEntranceCost->{$unit->class};
            }else{
                $traverseCost += $this->terrainFeatures->$code->traverseCost;
            }
        }
        return $traverseCost;

    }

    private function getTerrainCodeUnitCost($terrainCode, $unit){
        $feature = $this->terrainFeatures->$terrainCode;
        if($unit->nationality && $unit->class && $feature->altEntranceCost->{$unit->nationality}->{$unit->class}){
            $moveCost = $feature->altEntranceCost->{$unit->nationality}->{$unit->class};
        }else if($unit->class && $feature->altEntranceCost->{$unit->class}){
            $moveCost = $feature->altEntranceCost->{$unit->class};
        }else{
            $moveCost = $feature->entranceCost;

        }
        return $moveCost;

    }
    /*
      * very public
      * used in moveRules
      */
    function getTerrainMoveCost($startHexagon, $endHexagon, $railMove, unit $unit)
    {
        if(is_object($startHexagon)){
            $startHexagon = $startHexagon->name;
        }
        if(is_object($endHexagon)){
            $endHexagon = $endHexagon->name;
    }
        $moveCost = 0;
        list($startX, $startY) = Hexagon::getHexPartXY($startHexagon);
        list($endX, $endY) = Hexagon::getHexPartXY($endHexagon);
        $hexsideX = ($startX + $endX) / 2;
        $hexsideY = ($startY + $endY) / 2;

        // if road, or trail,override terrain,  add fordCost where needed
        $terrainCode = $this->getTerrainCodeXY($hexsideX,$hexsideY);
        if($railMove && ($terrainCode->road || $terrainCode->trail || $terrainCode->ford)){
            $roadCost = $this->getTerrainCodeUnitCost('road',$unit);
            $trailCost = $this->getTerrainCodeUnitCost('trail',$unit);
            $fordCost = $this->getTerrainCodeUnitCost('fordCost',$unit);
            if($roadCost == 0){
                $roadCost = .5;/* legacy crap */
            }
            if($trailCost == 0){
                $trailCost = 1;/* Legacy */
            }

            if($fordCost == 0){
                $fordCost = 2;/* legacy */
            }

            $moveCost = $roadCost;
            if($terrainCode->trail){
                $moveCost = $trailCost;
            }
            if($terrainCode->ford){
                $moveCost += $fordCost;
            }
        }
        else
         {

            //  get entrance cost
            $moveCost = $this->getTerrainEntranceMoveCost($endHexagon, $unit);
            $moveCost += $this->getTerrainCodeCost($terrainCode, $unit);
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


        $terrains = $this->getTerrainCodeXY($hexagon->getx(),$hexagon->getY());
        foreach ($terrains as $terrainFeature) {
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

        $terrains = $this->getTerrainCodeXY($hexpart->getX(),$hexpart->getY());
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

        if (is_object($hexagon)) {
            $X = $hexagon->getX();
            $Y = $hexagon->getY();
        } else {
            list($X, $Y) = Hexagon::getHexPartXY($hexagon);
        }
        $hexpart = new Hexpart($X, $Y);

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
  * public moveRules
  */
    function getReinforceZones($name)
    {
        $zones = array();
        for ($i = 0; $i < count($this->reinforceZones); $i++) {
            //alert("" + i + " " + $this->reinforceZones[$i]->hexagon->getName() + " : " + hexagon->getName());
            if ($this->reinforceZones[$i]->name == $name) {
                $zones[] = $this->reinforceZones[$i];
            }
        }

        return $zones;
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
