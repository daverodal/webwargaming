<?php
//  MapGrid
//
// copyright (c) 1998-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

class MapHex
{
    private $evenHexesShiftDown = true;
    public $forces;
    public $zocs;
    public $adjacent;
    public $name;
    public $neighbors;
    public $dirty;

    public function __construct($name, $forces = false, $zocs = false, $adjacent = false)
    {
        $this->name = $name;
        $this->dirty = false;
        $this->neighbors = array();
        $row = $name % 100;
        $col = floor($name / 100);
        $colShift = array(0, 1, 1, 0, -1, -1);
        if ($col & 1) {
            $rowShift = array(-1, -1, 0, 1, 0, -1);
        } else {
            $rowShift = array(-1, 0, 1, 1, 1, 0);
        }
        for ($i = 0; $i < 6; $i++) {
            $neighbor = $row + $rowShift[$i] + (($col + $colShift[$i]) * 100);
            $this->neighbors[] = $neighbor;
        }
        if ($forces !== false) {
            $this->dirty = true;
            $this->forces = $forces;
        } else {
            $this->forces = array(new stdClass(), new stdClass(), new stdClass());
        }
        if ($zocs !== false) {
            $this->dirty = true;
            $this->zocs = $zocs;
        } else {
            $this->zocs = array(new stdClass(), new stdClass(), new stdClass());
        }
        if ($adjacent !== false) {
            $this->dirty = true;
            $this->adjacent = $adjacent;
        } else {
            $this->adjacent = array(new stdClass(), new stdClass(), new stdClass());
        }
    }

    public function unsetUnit($forceId, $id)
    {
        if (isset($this->forces[$forceId]->$id)) {
            unset($this->forces[$forceId]->$id);
            $this->dirty = true;
        }
        $neighbors = $this->neighbors;
        $mapData = MapData::getInstance();
        foreach ($neighbors as $neighbor) {
            $hex = $mapData->getHex($neighbor);
            if ($hex) {
                unset($hex->zocs[$forceId]->$id);
                unset($hex->adjacent[$forceId]->$id);
            }

        }
    }

    public function setUnit($forceId, $id)
    {
        $battle = Battle::getBattle();
        if (!$this->forces) {
            $this->forces = array(new stdClass(), new stdClass(), new stdClass());
        }
        if (!$this->forces[$forceId]) {
            $this->forces[$forceId] = new stdClass();
        }
        $this->forces[$forceId]->$id = $id;
        $neighbors = $this->neighbors;
        $mapData = MapData::getInstance();
        $blocksZoc = $mapData->blocksZoc;
        $unitHex = $battle->force->units[$id]->hexagon;
        foreach ($neighbors as $neighbor) {
            $hex = $mapData->getHex($neighbor);

            if ($blocksZoc->blocked && $battle->terrain->terrainIsHexSide($unitHex->name, $neighbor, "blocked")) {
                continue;
            }

            if ($hex) {
                if (!$hex->adjacent) {
                    $hex->adjacent = array(new stdClass(), new stdClass(), new stdClass());
                }

                if (!$hex->adjacent[$forceId]) {
                    $hex->adjacent[$forceId] = new stdClass();
                }
                $hex->adjacent[$forceId]->$id = $id;
            }
            if ($blocksZoc->blocksnonroad && $battle->terrain->terrainIsHexSide($unitHex->name, $neighbor, "blocksnonroad")) {
                continue;
            }
            if ($hex) {
                if (!$hex->zocs) {
                    $hex->zocs = array(new stdClass(), new stdClass(), new stdClass());
                }

                if (!$hex->zocs[$forceId]) {
                    $hex->zocs[$forceId] = new stdClass();
                }
                $hex->zocs[$forceId]->$id = $id;
            }
        }

        $this->dirty = true;
    }

    public function setZoc($forceId, $id)
    {
        $this->zocs[$forceId]->$id = $id;
    }

    public function unsetZoc($forceId, $id)
    {
        unset($this->zocs[$forceId]->$id);
    }

    public function isZoc($forceId)
    {
        return count((array)$this->zocs[$forceId]);
    }

    public function isAdjacent($forceId)
    {
        return count((array)$this->adjacent[$forceId]);
    }

    public function isOccupied($forceId, $num = 1)
    {
        return count((array)$this->forces[$forceId]) >= $num;
    }

    public function getForces($forceId){
        return $this->forces[$forceId];
    }
}

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

    private function __construct()
    {
        $this->vp = array(0, 0, 0);
        $this->blocksZoc = new stdClass();
        $this->breadcrumbs = new stdClass();
    }

    function jsonSerialize()
    {
        foreach ($this->hexes as $k => $hex) {

            $f1 = count((array)$hex->forces[1]) + count((array)$hex->zocs[1]) + count((array)$hex->adjacent[1]);
            $f2 = count((array)$hex->forces[2]) + count((array)$hex->zocs[2]) + count((array)$hex->adjacent[2]);
            if (!$f1 && !$f2) {
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

    public function breadcrumb($id,$turn, $phase, $mode, $fromHex, $toHex){

        $index = $turn.'t'.$phase.'p'.$mode.'m'.$id;
        if(!isset($this->breadcrumbs)){
            $this->breadcrumbs = new stdClass();
        }
        if(!isset($this->breadcrumbs->$index)){
            $this->breadcrumbs->$index = [];
        }
        $crumbs = $this->breadcrumbs->$index;
        $crumb = new stdClass();
        $crumb->fromHex = $fromHex;
        $crumb->toHex = $toHex;
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
}

class MapViewer
{

    public $originX;
    public $originY;
    public $topHeight;
    public $bottomHeight;
    public $hexsideWidth;
    public $centerWidth;

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }

        }
    }

    function setData($originX, $originY
        , $topHeight, $bottomHeight
        , $hexsideWidth, $centerWidth)
    {

        $this->originX = $originX;
        $this->originY = $originY;
        $this->topHeight = $topHeight;
        $this->bottomHeight = $bottomHeight;
        $this->hexsideWidth = $hexsideWidth;
        $this->centerWidth = $centerWidth;
    }
}

// MapGrid Constructor
class MapGrid
{

    public $originX;
    public $originY;
    public $topHeight;
    public $bottomHeight;
    public $hexsideWidth;
    public $centerWidth;
    public $hexagonWidth;
    public $hexagonHeight;
    public $halfHexagonHeight;
    public $halfHexagonWidth;
    public $oneFourthHexagonHeight;
    public $leftMapEdge;

    // pixel info from screen
    public $mapGridX, $mapGridY;
    public $distanceFromLeftEdgeOfHexagon;
    public $distanceFromTopEdgeOfHexagon;
    public $column, $row;

    // hexagon and it's hexpart
    public $hexagon;
    public $hexpart;

    function __construct($mapData)
    {
        $this->originX = $mapData->originX;
        $this->originY = $mapData->originY;
        $this->topHeight = $mapData->topHeight;
        $this->bottomHeight = $mapData->bottomHeight;
        $this->hexsideWidth = $mapData->hexsideWidth;
        $this->centerWidth = $mapData->centerWidth;

        $this->hexagonHeight = $this->topHeight + $this->bottomHeight;
        $this->hexagonWidth = $this->hexsideWidth + $this->centerWidth;
        $this->halfHexagonHeight = $this->hexagonHeight / 2;
        $this->halfHexagonWidth = $this->hexagonWidth / 2;
        $this->oneFourthHexagonHeight = $this->hexagonHeight / 4;
        $this->leftMapEdge = -($this->hexsideWidth + ($this->centerWidth / 2));

        $this->hexagon = new Hexagon();
        $this->hexpart = new Hexpart();
    }

    function setPixels($pixelX, $pixelY)
    {

        $this->calculateHexpartFromPixels($pixelX, $pixelY);
        $this->calculateHexagonFromPixels();
    }

    function setHexagonXY($x, $y)
    {

        $this->setHexpartXY($x, $y);
    }

    function setHexpartXY($x, $y)
    {

        $this->mapGridX = ($this->halfHexagonWidth * $x) - $this->originX;
        $this->mapGridY = ($this->oneFourthHexagonHeight * $y) - $this->originY;
        $this->hexpart->setXY($x, $y);
    }

    function calculateHexpartFromPixels($pixelX, $pixelY)
    {

        //  var hexpart, hexpartY;

        // adjust for hexagonGrid origin
        $this->mapGridX = $pixelX + $this->originX;
        $this->mapGridY = $pixelY + $this->originY;

        $this->column = floor(($this->mapGridX - $this->leftMapEdge) / $this->hexagonWidth);
        $this->distanceFromLeftEdgeOfHexagon = ($this->mapGridX - $this->leftMapEdge) - ($this->column * $this->hexagonWidth);

        if ($this->distanceFromLeftEdgeOfHexagon < $this->hexsideWidth) {

            //  it's a / or \ hexside
            $hexpartX = (2 * $this->column) - 1;
            $this->row = floor($this->mapGridY / $this->halfHexagonHeight);
            $hexpartY = (2 * $this->row) + 1;
            $this->distanceFromTopEdgeOfHexagon = $this->mapGridY - ($this->row * $this->topHeight);
        } else {

            // it's a center or lower hexside
            $hexpartX = 2 * ($this->column);
            $this->mapGridY = $this->mapGridY + $this->oneFourthHexagonHeight;
            $this->row = floor($this->mapGridY / $this->halfHexagonHeight);
            $hexpartY = (2 * $this->row);
            $this->distanceFromTopEdgeOfHexagon = $this->mapGridY - ($this->row * $this->topHeight);
        }
        $this->hexpart->setXY($hexpartX, $hexpartY);
    }

    function calculateHexagonFromPixels()
    {

        //    var hexpartX, hexpartY, hexpartType;

        $hexpartX = $this->hexpart->getX();
        $hexpartY = $this->hexpart->getY();
        $hexpartType = $this->hexpart->getHexpartType();

        switch ($hexpartType) {
            case 1:
                $this->hexagon->setXY($hexpartX, $hexpartY);
                break;

            case 2:
                if ($this->distanceFromTopEdgeOfHexagon < $this->oneFourthHexagonHeight) {
                    $this->hexagon->setXY($hexpartX, $hexpartY - 2);
                } else {
                    $this->hexagon->setXY($hexpartX, $hexpartY + 2);
                }
                break;

            case 3:
                // check the tangent of the hexside line with tangent of the mappoint
                //
                // the hexside line tangent is opposite / adjacent = $this->hexsideWidth / $this->topHeight
                // the mappoint tangent is opposite / adjacent =  $this->distanceFromLeftEdgeOfHexagon / $this->distanceFromTopEdgeOfHexagon
                //
                // is mappoint tangent <  line tangent ?
                // ($this->distanceFromLeftEdgeOfHexagon / $this->distanceFromTopEdgeOfHexagon) < ($this->hexsideWidth / $this->topHeight)
                //
                // multiply both sides by $this->topHeight
                // ($this->distanceFromLeftEdgeOfHexagon / $this->distanceFromTopEdgeOfHexagon) * $this->topHeight  < ($this->hexsideWidth )
                //
                // multiply both sides by $this->distanceFromTopEdgeOfHexagon
                // ($this->distanceFromLeftEdgeOfHexagon * $this->topHeight ) < ($this->distanceFromTopEdgeOfHexagon * $this->hexsideWidth)
                //

                if ($this->distanceFromLeftEdgeOfHexagon * $this->topHeight < $this->distanceFromTopEdgeOfHexagon * $this->hexsideWidth) {
                    //  ______
                    //  |\ |  |
                    //  | \|  |
                    //  |* |\ |
                    //  |__|_\|
                    //
                    $this->hexagon->setXY($hexpartX - 1, $hexpartY + 1);
                } else {
                    //  ______
                    //  |\ |  |
                    //  | \|* |
                    //  |  |\ |
                    //  |__|_\|
                    //
                    $this->hexagon->setXY($hexpartX + 1, $hexpartY - 1);
                }
                break;

            case 4:
                // check the tangent of the hexside line with tangent of the mappoint
                //
                // see above
                //

                if ($this->distanceFromLeftEdgeOfHexagon * $this->topHeight < $this->distanceFromTopEdgeOfHexagon * $this->hexsideWidth) {
                    //  ______
                    //  |  | /|
                    //  |* |/ |
                    //  | /|  |
                    //  |/_|_ |
                    //
                    $this->hexagon->setXY($hexpartX - 1, $hexpartY - 1);
                } else {
                    //  ______
                    //  |  | /|
                    //  |  |/ |
                    //  | /|* |
                    //  |/_|_ |
                    //
                    $this->hexagon->setXY($hexpartX + 1, $hexpartY + 1);
                }
                break;
        }
    }

    function getHexpart()
    {
        return $this->hexpart;
    }

    function getHexagon()
    {
        return $this->hexagon;
    }

    function getPixelX()
    {
        return $this->mapGridX;
    }

    function getPixelY()
    {
        return $this->mapGridY;
    }
}