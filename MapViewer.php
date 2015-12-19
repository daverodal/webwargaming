<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 10:25 AM
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
class MapViewer
{

    public $originX;
    public $originY;
    public $topHeight;
    public $bottomHeight;
    public $hexsideWidth;
    public $centerWidth;
    public $mapWidth;
    public $trueRows = false;

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
        , $hexsideWidth, $centerWidth, $mapWidth)
    {

        $this->originX = $originX;
        $this->originY = $originY;
        $this->topHeight = $topHeight;
        $this->bottomHeight = $bottomHeight;
        $this->hexsideWidth = $hexsideWidth;
        $this->centerWidth = $centerWidth;
        $this->mapWidth = $mapWidth;
    }
}
