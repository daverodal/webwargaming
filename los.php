<?php
// Line of Sight object

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

class Los
{
    public $originX, $originY;
    public $endPointX, $endPointY;
    public $range;
    public $bearing;
    public $sectors;
    public $blocked;

    // a sector is either a line or a range between lines on a compass
    function __construct()
    {
        $this->sectors = array(
            0, 0, 16, 17, 14, 15, 13,
            0, 18, 20, 19, 22, 21, 23,
            12, 0, 8, 7, 10, 9, 11,
            0, 6, 4, 5, 2, 3, 1);
    }

    function setOrigin($hexagon)
    {
        if (is_object($hexagon)) {
            $this->originX = $hexagon->getX();
            $this->originY = $hexagon->getY();
        } else {
            list($this->originX, $this->originY) = Hexagon::getHexPartXY($hexagon);
        }

    }

    function setEndPoint($hexagon)
    {
        if (is_object($hexagon)) {
            $this->endPointX = $hexagon->getX();
            $this->endPointY = $hexagon->getY();
        } else {
            list($this->endPointX, $this->endPointY) = Hexagon::getHexPartXY($hexagon);
        }
    }

    function getRange()
    {
        $absX = abs($this->endPointX - $this->originX);
        $absY = abs($this->endPointY - $this->originY);
        if ($absX > $absY) {
            $this->range = $absX / 2;
        } else {
            $this->range = ($absX + $absY) / 4;
        }
        return ($this->range);
    }

    function getBearing()
    {
        //	var delta_x, delta_y;
        //	var absolute_x, absolute_y;
        //	var x3times, sector, quadrant;

        $sector = false;
        //	step 1. find the delta
        $delta_x = $this->endPointX - $this->originX;
        $delta_y = $this->endPointY - $this->originY;

        //      step 2. check if at the origin
        if ($delta_x == 0 && $delta_y == 0) {
            $this->bearing = -1;
        } else {
            //      step 3. find the sector

            $absolute_x = abs($delta_x);
            $absolute_y = abs($delta_y);
            $x3times = 3 * $absolute_x;
            if ($delta_x == 0) $sector = 0;
            else {
                if ($delta_y == 0) $sector = 1;
                else {
                    if ($absolute_x == $absolute_y) $sector = 2;
                    else {
                        if ($absolute_x > $absolute_y) $sector = 3;
                        else {
                            if ($x3times == $absolute_y) $sector = 4;
                            else {
                                if ($x3times > $absolute_y) $sector = 5;
                                else                $sector = 6;
                            }
                        }
                    }
                }
            }
        }

        //	step 4. find the quadrant
        if ($delta_x < 0) {
            if ($delta_y > 0) $quadrant = 0;
            else            $quadrant = 1;
        } else {
            if ($delta_y > 0) $quadrant = 2;
            else            $quadrant = 3;
        }

        $this->bearing = $this->sectors[($quadrant * 7) + $sector];
        return ($this->bearing);
    }

    function getFacingNumber()
    {

        return (floor($this->bearing / 4) + 1);
    }

    function getLosList()
    {

        $losArray = array();

        //	var b, x, y, i, hexsideX, hexsideY;
        //	var offset1, offset2;

        $stepX = array(0, 2, 2, 4, 2, 2, 0, -2, -2, -4, -2, -2, 0);
        $stepY = array(-4, -6, -2, 0, 2, 6, 4, 6, 2, 0, -2, -6, -4);

        $b = $this->getBearing();

        if ($b >= 0) {

            // for even bearing numbers
            $i = (int)floor($b / 2);
            if (($b % 2) == 0) {
                $x = $this->originX;
                $y = $this->originY;

                $hexpart1 = new Hexpart($x, $y);
                array_push($losArray, $hexpart1);
                do {
                    // do hexside first
                    $hexsideX = ($x + ($x + $stepX[$i])) / 2;
                    $hexsideY = ($y + ($y + $stepY[$i])) / 2;

                    $hexpart2 = new Hexpart($hexsideX, $hexsideY);
                    array_push($losArray, $hexpart2);

                    // then do hexagon
                    $x = $x + $stepX[$i];
                    $y = $y + $stepY[$i];

                    $hexpart3 = new Hexpart($x, $y);
                    array_push($losArray, $hexpart3);
                } while (($x != $this->endPointX) || ($y != $this->endPointY));

            } else {
                // for odd bearing numbers
                $i = floor($b / 4) * 2;
                $x = $this->originX;
                $y = $this->originY;

                $hexpart4 = new Hexpart($x, $y);
                array_push($losArray, $hexpart4);

                do {
                    $x1 = $x + $stepX[$i];
                    $y1 = $y + $stepY[$i];
                    $x2 = $x + $stepX[$i + 2];
                    $y2 = $y + $stepY[$i + 2];

                    // it's this easy
                    $offset1 = abs($this->originX * $this->endPointY - $this->originX * $y1 - $this->endPointX * $this->originY + $this->endPointX * $y1 + $x1 * $this->originY - $x1 * $this->endPointY);
                    $offset2 = abs($this->originX * $this->endPointY - $this->originX * $y2 - $this->endPointX * $this->originY + $this->endPointX * $y2 + $x2 * $this->originY - $x2 * $this->endPointY);

                    if ($offset1 == $offset2) {
                        // double hexagon traverse
                        // add first of near hexagons
                        $hexpart5 = new Hexpart($x1, $y1);
                        array_push($losArray, $hexpart5);

                        // add second of near hexagon
                        $hexpart6 = new Hexpart($x2, $y2);
                        array_push($losArray, $hexpart6);

                        // add hexside
                        $hexsideX = $x + (($stepX[$i] + $stepX[$i + 2]) / 2);
                        $hexsideY = $y + (($stepY[$i] + $stepY[$i + 2]) / 2);

                        // add hexagon which is at range of 2
                        $hexpart7 = new Hexpart($hexsideX, $hexsideY);
                        array_push($losArray, $hexpart7);

                        $x = $x + $stepX[$i] + $stepX[$i + 2];
                        $y = $y + $stepY[$i] + $stepY[$i + 2];

                        $hexpart8 = new Hexpart($x, $y);
                        array_push($losArray, $hexpart8);
                    } else {
                        if ($offset1 < $offset2) {
                            $hexsideX = ($x + $x1) / 2;
                            $hexsideY = ($y + $y1) / 2;
                            $x = $x1;
                            $y = $y1;
                        } else {
                            $hexsideX = ($x + $x2) / 2;
                            $hexsideY = ($y + $y2) / 2;
                            $x = $x2;
                            $y = $y2;
                        }

                        $hexpart9 = new Hexpart($hexsideX, $hexsideY);
                        array_push($losArray, $hexpart9);

                        $hexpart10 = new Hexpart($x, $y);
                        array_push($losArray, $hexpart10);
                    }

                } while (($x != $this->endPointX) || ($y != $this->endPointY));
            }
        }
        return $losArray;
    }
}