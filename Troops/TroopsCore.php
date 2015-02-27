<?php
require_once "TroopersCore.php";

class TroopsCore extends TroopersCore{

    function terrainGen($mapDoc, $terrainDoc)
    {
        $this->terrain->addTerrainFeature("wadi", "wadi", "v", 0, 2, 0, false);
        $this->terrain->addTerrainFeature("elevation","elevation", "e", 0, 0, 0, false);
        $this->terrain->addTerrainFeature("slope","slope", "s", 0, 1, 0, false);
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("road", "road", "r", .75, 0, 0, false);
        $this->terrain->addNatAltEntranceCost('forest','Beluchi', 'infantry', 1);
        $this->terrain->addNatAltEntranceCost('forest','Sikh', 'infantry', 1);
        $this->terrain->addAltEntranceCost('forest', 'horseartillery', 4);
    }

}