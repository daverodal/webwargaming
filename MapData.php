<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 10:24 AM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class MapData implements JsonSerializable
{

    public $hexes;
    public $maxX;
    public $maxY;
    public $specialHexes;
    private static $instance;
    public $mapUrl;
    public $vp;
    public $blocksZoc;
    public $breadcrumbs;
    public $mapSymbols;

    private function __construct()
    {
        $this->vp = array(0, 0, 0);
        $this->blocksZoc = new stdClass();
        $this->breadcrumbs = new stdClass();
        $this->mapSymbols = new stdClass();
    }

    function jsonSerialize()
    {
        foreach ($this->hexes as $k => $hex) {

            $f1 = count((array)$hex->forces[1]) + count((array)$hex->zocs[1]) + count((array)$hex->adjacent[1]);
            $f2 = count((array)$hex->forces[2]) + count((array)$hex->zocs[2]) + count((array)$hex->adjacent[2]);
            $f3 = count((array)$hex->forces[3]) + count((array)$hex->zocs[3]) + count((array)$hex->adjacent[3]);
            $f4 = count((array)$hex->forces[4]) + count((array)$hex->zocs[4]) + count((array)$hex->adjacent[4]);

            if (!$f1 && !$f2 && !$f3 && !$f4) {
                unset($this->hexes->$k);
                continue;
            }
//            if(!$hex->dirty){
//                continue;
//            }
            unset($this->hexes->$k->dirty);
            unset($this->hexes->$k->neighbors);
        }
        return $this;
    }

    public function breadcrumbMove($id,$attackingForceId, $turn, $phase, $mode, $fromHex, $toHex){

        $index = $turn.'t'.$attackingForceId.'a'.$phase.'p'.$mode.'m'.$id;
        if(!isset($this->breadcrumbs)){
            $this->breadcrumbs = new stdClass();
        }
        if(!isset($this->breadcrumbs->$index)){
            $this->breadcrumbs->$index = [];
        }
        $crumbs = $this->breadcrumbs->$index;
        $crumb = new stdClass();
        $crumb->type = "move";
        $crumb->fromHex = $fromHex;
        $crumb->toHex = $toHex;
        $crumbs[] = $crumb;
        $this->breadcrumbs->$index = $crumbs;
    }

    public function breadcrumbCombat($id, $attackingForceId, $turn, $phase, $mode, $result, $dieRoll, $hex){
        global $results_name;

        $index = $turn.'t'.$attackingForceId.'a'.$phase.'p'.$mode.'m'.$id;
        if(!isset($this->breadcrumbs)){
            $this->breadcrumbs = new stdClass();
        }
        if(!isset($this->breadcrumbs->$index)){
            $this->breadcrumbs->$index = [];
        }
        $crumbs = $this->breadcrumbs->$index;
        $crumb = new stdClass();
        $crumb->type = "combatResult";
        $crumb->result = $results_name[$result];
        $crumb->dieRoll = $dieRoll + 1;
        $crumb->hex = $hex;
        $crumbs[] = $crumb;
        $this->breadcrumbs->$index = $crumbs;
    }

    public static function getInstance()
    {
        if (!MapData::$instance) {
            MapData::$instance = new MapData();
        }
        return MapData::$instance;
    }

    public function init($data)
    {
        $hexes = $data->hexes;
        unset($data->hexes);

        foreach ($data as $k => $v) {
            if ($k == "hexes") {
//                $this->hexes = new stdClass();
//                foreach($v as $hexName => $hex){
//                    $this->hexes->$hexName = new MapHex($hex->name,$hex->forces);
//                }
            } else {
                $this->$k = $v;
            }
        }
        $this->hexes = new stdClass();
        for ($i = 1; $i <= $this->maxX + 1; $i++) {
            for ($j = 1; $j <= $this->maxY + 1; $j++) {
                $name = sprintf("%02d%02d", $i, $j);
                if (isset($hexes->$name) && $hexes->$name) {
                    $x = new MapHex($name, $hexes->$name->forces, $hexes->$name->zocs, $hexes->$name->adjacent);
                    $this->hexes->$name = $x;
                } else {
//                    $x = new MapHex($name);
                }

            }
        }
    }

    function setMapSymbols($hexes, $symbol){
        if (!$this->mapSymbols) {
            $this->mapSymbols = new stdClass();
        }
        foreach ($hexes as $k => $v) {
            $k = sprintf("%04d", "0000" . $k);
            if(!isset($this->mapSymbols->$k)){
                $this->mapSymbols->$k = new stdClass();
            }
            $this->mapSymbols->$k->$symbol = $v;
        }
    }



    /* Only change map symbol if it still exists */
    function alterMapSymbol($hex, $symbol, $v)
    {
        if (!$this->mapSymbols) {
            return;
        }
        $hex = sprintf("%04d", "0000" . $hex);
        if(isset($this->mapSymbols->$hex)){
            if(isset($this->mapSymbols->$hex->$symbol)) {
                $this->mapSymbols->$hex->$symbol = $v;
            }
        }
    }


    function removeMapSymbol($hex, $symbol)
    {
        if (!$this->mapSymbols) {
            return;
        }
        $k = sprintf("%04d", "0000" . $hex);
        unset($this->mapSymbols->$k->$symbol);
        /* if all symbols removed, remove hexname instance */
        if(count((array)$this->mapSymbols->$k) <= 0){
            unset($this->mapSymbols->$k);
        }
    }

    function getMapSymbols($hex)
    {
        $name = sprintf("%04d", "0000" . $hex);

        if (!$this->mapSymbols) {
            return false;
        }
        if (!$this->mapSymbols->$name) {
            return false;
        }
        return $this->mapSymbols->$name;
    }

    function getMapSymbol($hex, $symbol)
    {
        $name = sprintf("%04d", "0000" . $hex);

        if (!$this->mapSymbols) {
            return false;
        }
        if (!$this->mapSymbols->$name) {
            return false;
        }
        return $this->mapSymbols->$name->$symbol;
    }
    function removeSpecialHex($hex)
    {
        if (!$this->specialHexes) {
            return;
        }
        $k = sprintf("%04d", "0000" . $hex);
        unset($this->specialHexes->$k);
    }

    function setSpecialHexes($hexes)
    {
        if (!$this->specialHexes) {
            $this->specialHexes = new stdClass();
        }
        foreach ($hexes as $k => $v) {
            $k = sprintf("%04d", "0000" . $k);
            $this->specialHexes->$k = $v;
        }
    }


    /* Only change special Hex if it still exists */
    function alterSpecialHex($hex, $v)
    {
        if (!$this->specialHexes) {
            return;
        }
        $hex = sprintf("%04d", "0000" . $hex);
        if(isset($this->specialHexes->$hex)){
            $this->specialHexes->$hex = $v;
        }
    }

    function getSpecialHex($name)
    {
        $name = sprintf("%04d", "0000" . $name);

        if (!$this->specialHexes) {
            return false;
        }
        if (!$this->specialHexes->$name) {
            return false;
        }
        return $this->specialHexes->$name;
    }

    function setData($maxRight, $maxBottom, $map)
    {
        $this->mapUrl = $map;
        $this->maxY = $maxBottom;
        $this->maxX = $maxRight;
        $this->hexes = new stdClass();
        for ($i = 1; $i <= $maxRight + 1; $i++) {
            for ($j = 1; $j <= $maxBottom + 1; $j++) {
                $name = sprintf("%02d%02d", $i, $j);
                $this->hexes->$name = new MapHex($name);
            }
        }
    }

    function getHex($name)
    {
        $name = sprintf("%04d", $name);
        if (!isset($this->hexes->$name)) {
            $this->hexes->$name = new MapHex($name);
        }
        return $this->hexes->$name;
    }

    function hexagonIsOccupiedForce($hexagon, $forceId, $stacking = 1, $unit = false)
    {
        $mapHex = $this->getHex($hexagon->getName());
        return $mapHex->isOccupied($forceId, $stacking, $unit);
    }

    function hexagonIsOccupiedEnemy($hexagon, $friendlyId)
    {
        $isOccupied = false;

        $mapHex = $this->getHex($hexagon->getName());
        foreach ($mapHex->forces as $forceId => $force) {
            if ($friendlyId == $forceId) {
                continue;
            }
            if (count((array)$force) > 0) {
                $isOccupied = true;
            }
        }
        return $isOccupied;
    }

}
