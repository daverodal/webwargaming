<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
require_once "JagCore.php";

class IndiaCore extends JagCore{

    function terrainGen($mapDoc, $terrainDoc)
    {
        $this->terrain->addTerrainFeature("wadi", "wadi", "v", 0, 2, 0, false);
        $this->terrain->addTerrainFeature("elevation","elevation", "e", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("slope","slope", "s", 0, 1, 0, false);
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("road", "road", "r", .75, 0, 0, false);
        $this->terrain->addNatAltEntranceCost('forest','Beluchi', 'infantry', 1);
        $this->terrain->addNatAltEntranceCost('forest','Sikh', 'infantry', 1);
        $this->terrain->addAltEntranceCost('forest', 'horseartillery', 4);
    }

}