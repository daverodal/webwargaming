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
            $this->forces = array(new stdClass(), new stdClass(), new stdClass(), new stdClass(), new stdClass());
        }
        if ($zocs !== false) {
            $this->dirty = true;
            $this->zocs = $zocs;
        } else {
            $this->zocs = array(new stdClass(), new stdClass(), new stdClass(), new stdClass(), new stdClass());
        }
        if ($adjacent !== false) {
            $this->dirty = true;
            $this->adjacent = $adjacent;
        } else {
            $this->adjacent = array(new stdClass(), new stdClass(), new stdClass(), new stdClass(), new stdClass());
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

    public function setUnit($forceId, $unit)
    {
        $id = $unit->id;
        $battle = Battle::getBattle();
        if (!$this->forces) {
            $this->forces = array(new stdClass(), new stdClass(), new stdClass(), new stdClass(), new stdClass());
        }
        if (!$this->forces[$forceId]) {
            $this->forces[$forceId] = new stdClass();
        }
        $this->forces[$forceId]->$id = $id;
        $neighbors = $this->neighbors;
        $mapData = MapData::getInstance();
        $blocksZoc = $mapData->blocksZoc;
        $unitHex = $unit->hexagon;

        if($unit->noZoc !== true) {
            foreach ($neighbors as $neighbor) {
                $hex = $mapData->getHex($neighbor);

                if ($blocksZoc->blocked && $battle->terrain->terrainIsHexSide($unitHex->name, $neighbor, "blocked")) {
                    continue;
                }

                if ($hex) {
                    if (!$hex->adjacent) {
                        $hex->adjacent = array(new stdClass(), new stdClass(), new stdClass(), new stdClass(), new stdClass());
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
                        $hex->zocs = array(new stdClass(), new stdClass(), new stdClass(), new stdClass(), new stdClass());
                    }

                    if (!$hex->zocs[$forceId]) {
                        $hex->zocs[$forceId] = new stdClass();
                    }
                    $hex->zocs[$forceId]->$id = $id;
                }
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

    public function getZocUnits($forceId)
    {
        return $this->zocs[$forceId];
    }

    public function isAdjacent($forceId)
    {
        return count((array)$this->adjacent[$forceId]);
    }

    public function getAdjacentUnits($forceId)
    {
        return $this->adjacent[$forceId];
    }

    public function isOccupied($forceId, $num = 1, $unit = false)
    {
        if(is_callable($num)){
            $closure = $num;
            return $closure($this, $forceId, $unit);
        }
        return count((array)$this->forces[$forceId]) >= $num;
    }

    public function getForces($forceId){
        return $this->forces[$forceId];
    }
}
