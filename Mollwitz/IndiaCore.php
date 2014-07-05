<?php
require_once "JagCore.php";

class IndiaCore extends JagCore{

    function terrainGen($hexDocId)
    {
        $this->terrain->addTerrainFeature("wadi", "wadi", "v", 0, 2, 0, false);
        parent::terrainGen($hexDocId);
        $this->terrain->addTerrainFeature("road", "road", "r", .75, 0, 0, false);
        $this->terrain->addNatAltEntranceCost('forest','Beluchi', 'infantry', 1);
        $this->terrain->addNatAltEntranceCost('forest','Sikh', 'infantry', 1);
        $this->terrain->addAltEntranceCost('forest', 'horseartillery', 4);
    }

}