<?php
set_include_path(__DIR__ . "/Ferozesha" . PATH_SEPARATOR . get_include_path());
require_once "IndiaCore.php";
/* comment */
define("BRITISH_FORCE", 1);
define("SIKH_FORCE", 2);
$force_name[BRITISH_FORCE] = "British";
$force_name[SIKH_FORCE] = "Sikh";
$phase_name = array();
$phase_name[1] = "<span class='playerOneFace'>British</span> Move";
$phase_name[2] = "<span class='playerOneFace'>British</span> Combat";
$phase_name[3] = "";
$phase_name[4] = "<span class='playerTwoFace'>Sikh</span> Move";
$phase_name[5] = "<span class='playerTwoFace'>Sikh</span> Combat";
$phase_name[6] = "";
$phase_name[7] = "Victory";
$phase_name[8] = "<span class='playerOneFace'>British</span> Deploy";
$phase_name[9] = "";
$phase_name[10] = "";
$phase_name[11] = "";
$phase_name[12] = "";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "<span class='playerTwoFace'>Sikh</span> Deploy";



// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class Ferozesha extends IndiaCore
{

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>1];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $playerData;
    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;
    /* @var MoveRules */
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;
    public $display;
    public $victory;
    public $genTerrain;
    public $moodkee;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "FerozeshaHeader.php";

    }


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "British";
        $deployOne = $playerTwo = "Sikh";
        @include_once "view.php";
    }

    public function terrainInit($terrainName){
        parent::terrainInit($terrainName);
        $this->moodkee = $this->specialHexB[0];
    }

    function save()
    {
        $data = new stdClass();
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->playerData = $this->playerData;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = "terrain-".get_class($this);
        $data->genTerrain = $this->genTerrain;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;
        $data->moodkee = $this->moodkee;
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }



    public function init()
    {

            /* Sikh */
            for ($i = 0; $i < 21; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Sikh", false, 'infantry');
            }
            for ($i = 0; $i < 10; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Sikh", false, 'cavalry');
            }
            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 3, "Sikh", false, 'artillery');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 3, "Sikh", false, 'artillery');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, 5, "Sikh", false, 'artillery');
            }
            /* British */
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritInfBadge.png", 7, 7, 4, true, STATUS_CAN_DEPLOY, "B", 1, 1, "British", false, 'infantry');
            }
            for ($i = 0; $i < 15; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeInfBadge.png", 6, 6, 4, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Native", false, 'infantry');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritCavBadge.png", 7, 7, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "British", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeCavBadge.png", 6, 6, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Native", false, 'cavalry');
            }
             for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritArtBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 4, "British", false, 'artillery');
            }
            for ($i = 0; $i < 2; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritHorArtBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "B", 1, 3, "British", false, 'horseartillery');
            }

    }

//    function terrainGen($hexDocId){
//        $CI =& get_instance();
//        $terrainDoc = $CI->couchsag->get($hexDocId);
//        $terrainArr = json_decode($terrainDoc->hexStr->hexEncodedStr);
//        $mapId = $terrainDoc->hexStr->map;
//        $mapDoc = $CI->couchsag->get($mapId);
//        $map = $mapDoc->map;
//        $this->terrain->mapUrl = $mapUrl = $map->mapUrl;
//        $this->terrain->maxCol = $maxCol = $map->numX;
//        $this->terrain->maxRow = $maxRow = $map->numY;
//        $this->mapData->setData($maxCol, $maxRow, $mapUrl);
//
//        Hexagon::setMinMax();
//        $this->terrain->setMaxHex();

//        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
//        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
//        $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
//        $this->terrain->addTerrainFeature("road", "road", "r", .75, 0, 0, false);
//        $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
//        $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
//        $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
//        $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
//        $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
//        $this->terrain->addNatAltEntranceCost('forest','Sikh', 'infantry', 1);
//        $this->terrain->addAltEntranceCost('forest', 'horseartillery', 4);
//        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
//        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
//        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
//        $this->terrain->addTerrainFeature("redoubt", "redoubt", "d", 0, 2, 0, false);
//        $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);
//        $this->terrain->addTerrainFeature("wadi", "wadi", "v", 0, 2, 0, false);
//        $this->terrain->addAltEntranceCost('swamp','artillery','blocked');



//        for ($col = 100; $col <= $maxCol * 100; $col += 100) {
//            for ($row = 1; $row <= $maxRow; $row++) {
//                $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
//                $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
//                $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
//                $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");
//
//            }
//        }
//        foreach($terrainArr as $terrain){
//            foreach($terrain->type as $terrainType){
//                $name = $terrainType->name;
//                $matches = [];
//                if(preg_match("/^ReinforceZone(.*)$/", $name,$matches)){
//                    $this->terrain->addReinforceZone($terrain->number, $matches[1]);
//                }else{
//                    $this->terrain->addTerrain($terrain->number, $terrain->hexpartType, $name);
//                }
//            }
//        }
//    }

//    function terrainInit($terrainName){
//
//        $CI =& get_instance();
//        $terrainDoc = $CI->couchsag->get($terrainName);
//        $terrainInfo = $terrainDoc->terrain;
//
//        $oldMapUrl = $this->mapData->mapUrl;
//        if(!$oldMapUrl){
//            $maxCol = $terrainInfo->maxCol;
//            $maxRow = $terrainInfo->maxRow;
//            $mapUrl = $terrainInfo->mapUrl;
//            $this->mapData->setData($maxCol, $maxRow, $mapUrl);
//
//            Hexagon::setMinMax();
//            $this->terrain->setMaxHex();
//        }
//        $this->genTerrain = false;
//        return;
//        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
//        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
//        $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
//        $this->terrain->addTerrainFeature("road", "road", "r", .75, 0, 0, false);
//        $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
//        $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
//        $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
//        $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
//        $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
//        $this->terrain->addNatAltEntranceCost('forest','Sikh', 'infantry', 1);
//        $this->terrain->addAltEntranceCost('forest', 'horseartillery', 4);
//        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
//        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
//        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
//        $this->terrain->addTerrainFeature("redoubt", "redoubt", "d", 0, 2, 0, false);
//        $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);
//        $this->terrain->addTerrainFeature("wadi", "wadi", "v", 0, 2, 0, false);
//        $this->terrain->addAltEntranceCost('swamp','artillery','blocked');
//
//
//        for ($col = 100; $col <= 3300; $col += 100) {
//            for ($row = 1; $row <= 25; $row++) {
//                $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
//                $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
//                $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
//                $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");
//
//            }
//        }
//        foreach($terrainArr as $terrain){
//            foreach($terrain->type as $terrainType){
//                $name = $terrainType->name;
//                $matches = [];
//                if(preg_match("/^ReinforceZone(.*)$/", $name,$matches)){
//                    $this->terrain->addReinforceZone($terrain->number, $matches[1]);
//                }else{
//                    $this->terrain->addTerrain($terrain->number, $terrain->hexpartType, $name);
//                }
//            }
//        }
//                $this->terrain->addReinforceZone(1609,'A');
//        $this->terrain->addReinforceZone(123,'B');
//
//        $specialHexA = [];
//        $specialHexB = [];
//        $this->terrain->addTerrain(1609 ,1 , "town");
//        $this->terrain->addReinforceZone(1609,'A');
//        $this->terrain->addTerrain(1608 ,1 , "town");
//        $this->terrain->addReinforceZone(1608,'A');
//        $this->terrain->addTerrain(1709 ,1 , "town");
//        $this->terrain->addReinforceZone(1709,'A');
//        $this->terrain->addTerrain(1710 ,1 , "town");
//        $this->terrain->addReinforceZone(1710,'A');
//        $this->terrain->addTerrain(1710 ,4 , "town");
//        $this->terrain->addTerrain(1709 ,2 , "town");
//        $this->terrain->addTerrain(1709 ,3 , "town");
//        $this->terrain->addTerrain(1709 ,4 , "town");
//        $this->terrain->addTerrain(1608 ,2 , "town");
//        $this->terrain->addTerrain(1007 ,1 , "town");
//        $this->terrain->addReinforceZone(1007,'A');
//        $this->terrain->addTerrain(2907 ,1 , "town");
//        $this->terrain->addTerrain(3221 ,1 , "town");
//        $this->terrain->addReinforceZone(3221,'B');
//        $this->terrain->addTerrain(1507 ,2 , "redoubt");
//        $this->terrain->addTerrain(1607 ,4 , "redoubt");
//        $this->terrain->addTerrain(1606 ,2 , "redoubt");
//        $this->terrain->addTerrain(1707 ,4 , "redoubt");
//        $this->terrain->addTerrain(1706 ,2 , "redoubt");
//        $this->terrain->addTerrain(1806 ,3 , "redoubt");
//        $this->terrain->addTerrain(1806 ,2 , "redoubt");
//        $this->terrain->addTerrain(1907 ,3 , "redoubt");
//        $this->terrain->addTerrain(1907 ,2 , "redoubt");
//        $this->terrain->addTerrain(2007 ,3 , "redoubt");
//        $this->terrain->addTerrain(2008 ,4 , "redoubt");
//        $this->terrain->addTerrain(2008 ,3 , "redoubt");
//        $this->terrain->addTerrain(2009 ,4 , "redoubt");
//        $this->terrain->addTerrain(2009 ,3 , "redoubt");
//        $this->terrain->addTerrain(2010 ,4 , "redoubt");
//        $this->terrain->addTerrain(2010 ,4 , "road");
//        $this->terrain->addTerrain(1910 ,2 , "redoubt");
//        $this->terrain->addTerrain(1911 ,4 , "redoubt");
//        $this->terrain->addTerrain(1911 ,3 , "redoubt");
//        $this->terrain->addTerrain(1912 ,4 , "redoubt");
//        $this->terrain->addTerrain(1811 ,2 , "redoubt");
//        $this->terrain->addTerrain(1812 ,4 , "redoubt");
//        $this->terrain->addTerrain(1712 ,2 , "redoubt");
//        $this->terrain->addTerrain(1713 ,4 , "redoubt");
//        $this->terrain->addTerrain(1612 ,2 , "redoubt");
//        $this->terrain->addTerrain(1612 ,3 , "redoubt");
//        $this->terrain->addTerrain(1512 ,2 , "redoubt");
//        $this->terrain->addTerrain(1512 ,3 , "redoubt");
//        $this->terrain->addTerrain(1512 ,4 , "redoubt");
//        $this->terrain->addTerrain(1511 ,3 , "redoubt");
//        $this->terrain->addTerrain(1511 ,4 , "redoubt");
//        $this->terrain->addTerrain(1510 ,3 , "redoubt");
//        $this->terrain->addTerrain(1510 ,4 , "redoubt");
//        $this->terrain->addTerrain(1509 ,3 , "redoubt");
//        $this->terrain->addTerrain(1509 ,4 , "redoubt");
//        $this->terrain->addTerrain(1509 ,4 , "road");
//        $this->terrain->addTerrain(1508 ,3 , "redoubt");
//        $this->terrain->addTerrain(1508 ,4 , "redoubt");
//        $this->terrain->addTerrain(1508 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1508,'A');
//        $this->terrain->addTerrain(1607 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1607,'A');
//        $this->terrain->addTerrain(1707 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1707,'A');
//        $this->terrain->addTerrain(1807 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1807,'A');
//        $this->terrain->addTerrain(1908 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1908,'A');
//        $this->terrain->addTerrain(1909 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1909,'A');
//        $this->terrain->addTerrain(1910 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1910,'A');
//        $this->terrain->addTerrain(1910 ,1 , "road");
//        $this->terrain->addTerrain(1810 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1810,'A');
//        $this->terrain->addTerrain(1811 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1811,'A');
//        $this->terrain->addTerrain(1712 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1712,'A');
//        $this->terrain->addTerrain(1612 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1612,'A');
//        $this->terrain->addTerrain(1512 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1512,'A');
//        $this->terrain->addTerrain(1511 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1511,'A');
//        $this->terrain->addTerrain(1510 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1510,'A');
//        $this->terrain->addTerrain(1509 ,1 , "redoubt");
//        $this->terrain->addReinforceZone(1509,'A');
//        $this->terrain->addTerrain(1509 ,1 , "road");
//        $this->terrain->addTerrain(307 ,1 , "forest");
//        $this->terrain->addTerrain(406 ,1 , "forest");
//        $this->terrain->addTerrain(406 ,3 , "forest");
//        $this->terrain->addTerrain(406 ,4 , "forest");
//        $this->terrain->addTerrain(306 ,2 , "forest");
//        $this->terrain->addTerrain(306 ,1 , "forest");
//        $this->terrain->addTerrain(306 ,4 , "forest");
//        $this->terrain->addTerrain(205 ,1 , "forest");
//        $this->terrain->addTerrain(1107 ,4 , "forest");
//        $this->terrain->addTerrain(1006 ,1 , "forest");
//        $this->terrain->addReinforceZone(1006,'A');
//        $this->terrain->addTerrain(1107 ,1 , "forest");
//        $this->terrain->addReinforceZone(1107,'A');
//        $this->terrain->addTerrain(403 ,1 , "forest");
//        $this->terrain->addTerrain(503 ,3 , "forest");
//        $this->terrain->addTerrain(503 ,1 , "forest");
//        $this->terrain->addTerrain(602 ,3 , "forest");
//        $this->terrain->addTerrain(602 ,1 , "forest");
//        $this->terrain->addTerrain(602 ,4 , "forest");
//        $this->terrain->addTerrain(502 ,2 , "forest");
//        $this->terrain->addTerrain(503 ,4 , "forest");
//        $this->terrain->addTerrain(402 ,2 , "forest");
//        $this->terrain->addTerrain(402 ,1 , "forest");
//        $this->terrain->addTerrain(502 ,3 , "forest");
//        $this->terrain->addTerrain(502 ,1 , "forest");
//        $this->terrain->addTerrain(1001 ,1 , "forest");
//        $this->terrain->addTerrain(1102 ,4 , "forest");
//        $this->terrain->addTerrain(1101 ,3 , "forest");
//        $this->terrain->addTerrain(1101 ,2 , "forest");
//        $this->terrain->addTerrain(1101 ,1 , "forest");
//        $this->terrain->addTerrain(1102 ,1 , "forest");
//        $this->terrain->addTerrain(1102 ,2 , "forest");
//        $this->terrain->addTerrain(1103 ,1 , "forest");
//        $this->terrain->addTerrain(1202 ,3 , "forest");
//        $this->terrain->addTerrain(1202 ,4 , "forest");
//        $this->terrain->addTerrain(1201 ,3 , "forest");
//        $this->terrain->addTerrain(1201 ,4 , "forest");
//        $this->terrain->addTerrain(1201 ,1 , "forest");
//        $this->terrain->addTerrain(1201 ,2 , "forest");
//        $this->terrain->addTerrain(1202 ,1 , "forest");
//        $this->terrain->addTerrain(1302 ,3 , "forest");
//        $this->terrain->addTerrain(1302 ,4 , "forest");
//        $this->terrain->addTerrain(1301 ,3 , "forest");
//        $this->terrain->addTerrain(1301 ,1 , "forest");
//        $this->terrain->addTerrain(1301 ,2 , "forest");
//        $this->terrain->addTerrain(1302 ,1 , "forest");
//        $this->terrain->addTerrain(1401 ,3 , "forest");
//        $this->terrain->addTerrain(1401 ,4 , "forest");
//        $this->terrain->addTerrain(1401 ,1 , "forest");
//        $this->terrain->addTerrain(1501 ,3 , "forest");
//        $this->terrain->addTerrain(1502 ,4 , "forest");
//        $this->terrain->addTerrain(1502 ,1 , "forest");
//        $this->terrain->addTerrain(1501 ,2 , "forest");
//        $this->terrain->addTerrain(1501 ,1 , "forest");
//        $this->terrain->addTerrain(1601 ,4 , "forest");
//        $this->terrain->addTerrain(1601 ,3 , "forest");
//        $this->terrain->addTerrain(1601 ,1 , "forest");
//        $this->terrain->addTerrain(1701 ,3 , "forest");
//        $this->terrain->addTerrain(1702 ,4 , "forest");
//        $this->terrain->addTerrain(1701 ,1 , "forest");
//        $this->terrain->addTerrain(1701 ,2 , "forest");
//        $this->terrain->addTerrain(1702 ,1 , "forest");
//        $this->terrain->addTerrain(1801 ,4 , "forest");
//        $this->terrain->addTerrain(1801 ,3 , "forest");
//        $this->terrain->addTerrain(1802 ,4 , "forest");
//        $this->terrain->addTerrain(1801 ,1 , "forest");
//        $this->terrain->addTerrain(1801 ,2 , "forest");
//        $this->terrain->addTerrain(1802 ,1 , "forest");
//        $this->terrain->addTerrain(1902 ,3 , "forest");
//        $this->terrain->addTerrain(1902 ,4 , "forest");
//        $this->terrain->addTerrain(1901 ,3 , "forest");
//        $this->terrain->addTerrain(1901 ,1 , "forest");
//        $this->terrain->addTerrain(1901 ,2 , "forest");
//        $this->terrain->addTerrain(1902 ,1 , "forest");
//        $this->terrain->addTerrain(2001 ,4 , "forest");
//        $this->terrain->addTerrain(2001 ,3 , "forest");
//        $this->terrain->addTerrain(2002 ,4 , "forest");
//        $this->terrain->addTerrain(2001 ,2 , "forest");
//        $this->terrain->addTerrain(2001 ,1 , "forest");
//        $this->terrain->addTerrain(2002 ,1 , "forest");
//        $this->terrain->addTerrain(2101 ,3 , "forest");
//        $this->terrain->addTerrain(2101 ,1 , "forest");
//        $this->terrain->addTerrain(1308 ,1 , "forest");
//        $this->terrain->addReinforceZone(1308,'A');
//        $this->terrain->addTerrain(1308 ,1 , "road");
//        $this->terrain->addTerrain(1408 ,4 , "forest");
//        $this->terrain->addTerrain(1408 ,4 , "road");
//        $this->terrain->addTerrain(1408 ,1 , "forest");
//        $this->terrain->addReinforceZone(1408,'A');
//        $this->terrain->addTerrain(1408 ,1 , "road");
//        $this->terrain->addTerrain(1610 ,1 , "forest");
//        $this->terrain->addReinforceZone(1610,'A');
//        $this->terrain->addTerrain(1711 ,4 , "forest");
//        $this->terrain->addTerrain(1711 ,1 , "forest");
//        $this->terrain->addReinforceZone(1711,'A');
//        $this->terrain->addTerrain(2706 ,1 , "forest");
//        $this->terrain->addTerrain(2806 ,4 , "forest");
//        $this->terrain->addTerrain(2806 ,1 , "forest");
//        $this->terrain->addTerrain(2906 ,3 , "forest");
//        $this->terrain->addTerrain(2907 ,4 , "forest");
//        $this->terrain->addTerrain(2806 ,2 , "forest");
//        $this->terrain->addTerrain(2807 ,1 , "forest");
//        $this->terrain->addTerrain(2907 ,3 , "forest");
//        $this->terrain->addTerrain(2908 ,4 , "forest");
//        $this->terrain->addTerrain(2908 ,1 , "forest");
//        $this->terrain->addTerrain(3007 ,3 , "forest");
//        $this->terrain->addTerrain(3007 ,1 , "forest");
//        $this->terrain->addTerrain(3007 ,4 , "forest");
//        $this->terrain->addTerrain(2906 ,2 , "forest");
//        $this->terrain->addTerrain(2906 ,1 , "forest");
//        $this->terrain->addTerrain(3101 ,1 , "forest");
//        $this->terrain->addTerrain(3101 ,2 , "forest");
//        $this->terrain->addTerrain(3102 ,1 , "forest");
//        $this->terrain->addTerrain(3201 ,4 , "forest");
//        $this->terrain->addTerrain(3201 ,3 , "forest");
//        $this->terrain->addTerrain(3202 ,4 , "forest");
//        $this->terrain->addTerrain(3201 ,2 , "forest");
//        $this->terrain->addTerrain(3201 ,1 , "forest");
//        $this->terrain->addTerrain(3202 ,1 , "forest");
//        $this->terrain->addTerrain(3301 ,3 , "forest");
//        $this->terrain->addTerrain(3301 ,1 , "forest");
//        $this->terrain->addTerrain(718 ,1 , "forest");
//        $this->terrain->addTerrain(818 ,4 , "forest");
//        $this->terrain->addTerrain(919 ,4 , "forest");
//        $this->terrain->addTerrain(919 ,4 , "road");
//        $this->terrain->addTerrain(919 ,1 , "forest");
//        $this->terrain->addReinforceZone(919,'B');
//        $this->terrain->addTerrain(919 ,1 , "road");
//        $this->terrain->addTerrain(711 ,1 , "forest");
//        $this->terrain->addTerrain(711 ,2 , "forest");
//        $this->terrain->addTerrain(712 ,1 , "forest");
//        $this->terrain->addTerrain(811 ,4 , "forest");
//        $this->terrain->addTerrain(811 ,3 , "forest");
//        $this->terrain->addTerrain(812 ,4 , "forest");
//        $this->terrain->addTerrain(811 ,2 , "forest");
//        $this->terrain->addTerrain(811 ,1 , "forest");
//        $this->terrain->addTerrain(812 ,1 , "forest");
//        $this->terrain->addTerrain(912 ,3 , "forest");
//        $this->terrain->addTerrain(912 ,4 , "forest");
//        $this->terrain->addTerrain(911 ,2 , "forest");
//        $this->terrain->addTerrain(913 ,4 , "forest");
//        $this->terrain->addTerrain(912 ,1 , "forest");
//        $this->terrain->addTerrain(912 ,2 , "forest");
//        $this->terrain->addTerrain(913 ,1 , "forest");
//        $this->terrain->addTerrain(913 ,2 , "forest");
//        $this->terrain->addTerrain(914 ,1 , "forest");
//        $this->terrain->addTerrain(1014 ,4 , "forest");
//        $this->terrain->addTerrain(1013 ,3 , "forest");
//        $this->terrain->addTerrain(1013 ,4 , "forest");
//        $this->terrain->addTerrain(1012 ,3 , "forest");
//        $this->terrain->addTerrain(1012 ,4 , "forest");
//        $this->terrain->addTerrain(1012 ,1 , "forest");
//        $this->terrain->addTerrain(1012 ,2 , "forest");
//        $this->terrain->addTerrain(1013 ,1 , "forest");
//        $this->terrain->addTerrain(1013 ,2 , "forest");
//        $this->terrain->addTerrain(1014 ,1 , "forest");
//        $this->terrain->addTerrain(1115 ,4 , "forest");
//        $this->terrain->addTerrain(1114 ,3 , "forest");
//        $this->terrain->addTerrain(1114 ,4 , "forest");
//        $this->terrain->addTerrain(1114 ,1 , "forest");
//        $this->terrain->addTerrain(1114 ,2 , "forest");
//        $this->terrain->addTerrain(1115 ,1 , "forest");
//        $this->terrain->addTerrain(1115 ,2 , "forest");
//        $this->terrain->addTerrain(1116 ,1 , "forest");
//        $this->terrain->addTerrain(1214 ,4 , "forest");
//        $this->terrain->addTerrain(1214 ,3 , "forest");
//        $this->terrain->addTerrain(1215 ,4 , "forest");
//        $this->terrain->addTerrain(1215 ,3 , "forest");
//        $this->terrain->addTerrain(1215 ,1 , "forest");
//        $this->terrain->addTerrain(1214 ,2 , "forest");
//        $this->terrain->addTerrain(1214 ,1 , "forest");
//        $this->terrain->addTerrain(1315 ,4 , "forest");
//        $this->terrain->addTerrain(1315 ,3 , "forest");
//        $this->terrain->addTerrain(1316 ,4 , "forest");
//        $this->terrain->addTerrain(1315 ,2 , "forest");
//        $this->terrain->addTerrain(1315 ,1 , "forest");
//        $this->terrain->addTerrain(1316 ,1 , "forest");
//        $this->terrain->addTerrain(1415 ,3 , "forest");
//        $this->terrain->addTerrain(1415 ,4 , "forest");
//        $this->terrain->addTerrain(1415 ,1 , "forest");
//        $this->terrain->addTerrain(1416 ,4 , "forest");
//        $this->terrain->addTerrain(1415 ,2 , "forest");
//        $this->terrain->addTerrain(1416 ,1 , "forest");
//        $this->terrain->addTerrain(1516 ,3 , "forest");
//        $this->terrain->addTerrain(1516 ,4 , "forest");
//        $this->terrain->addTerrain(1516 ,1 , "forest");
//        $this->terrain->addTerrain(1615 ,3 , "forest");
//        $this->terrain->addTerrain(1615 ,1 , "forest");
//        $this->terrain->addTerrain(1716 ,4 , "forest");
//        $this->terrain->addTerrain(1716 ,1 , "forest");
//        $this->terrain->addTerrain(1815 ,3 , "forest");
//        $this->terrain->addTerrain(1815 ,1 , "forest");
//        $this->terrain->addTerrain(1916 ,4 , "forest");
//        $this->terrain->addTerrain(1916 ,1 , "forest");
//        $this->terrain->addTerrain(2016 ,4 , "forest");
//        $this->terrain->addTerrain(2016 ,1 , "forest");
//        $this->terrain->addTerrain(2116 ,3 , "forest");
//        $this->terrain->addTerrain(2116 ,1 , "forest");
//        $this->terrain->addTerrain(2215 ,3 , "forest");
//        $this->terrain->addTerrain(2216 ,4 , "forest");
//        $this->terrain->addTerrain(2216 ,1 , "forest");
//        $this->terrain->addTerrain(2215 ,2 , "forest");
//        $this->terrain->addTerrain(2215 ,1 , "forest");
//        $this->terrain->addTerrain(2317 ,4 , "forest");
//        $this->terrain->addTerrain(2316 ,3 , "forest");
//        $this->terrain->addTerrain(2316 ,4 , "forest");
//        $this->terrain->addTerrain(2315 ,3 , "forest");
//        $this->terrain->addTerrain(2314 ,1 , "forest");
//        $this->terrain->addTerrain(2314 ,2 , "forest");
//        $this->terrain->addTerrain(2315 ,1 , "forest");
//        $this->terrain->addTerrain(2315 ,2 , "forest");
//        $this->terrain->addTerrain(2316 ,1 , "forest");
//        $this->terrain->addTerrain(2316 ,2 , "forest");
//        $this->terrain->addTerrain(2317 ,1 , "forest");
//        $this->terrain->addTerrain(2415 ,3 , "forest");
//        $this->terrain->addTerrain(2415 ,4 , "forest");
//        $this->terrain->addTerrain(2413 ,3 , "forest");
//        $this->terrain->addTerrain(2413 ,1 , "forest");
//        $this->terrain->addTerrain(2413 ,1 , "road");
//        $this->terrain->addTerrain(2513 ,3 , "forest");
//        $this->terrain->addTerrain(2513 ,1 , "forest");
//        $this->terrain->addTerrain(2613 ,4 , "forest");
//        $this->terrain->addTerrain(2613 ,1 , "forest");
//        $this->terrain->addTerrain(2613 ,2 , "forest");
//        $this->terrain->addTerrain(2614 ,1 , "forest");
//        $this->terrain->addTerrain(2614 ,3 , "forest");
//        $this->terrain->addTerrain(2515 ,1 , "forest");
//        $this->terrain->addTerrain(2515 ,1 , "road");
//        $this->terrain->addTerrain(2515 ,3 , "forest");
//        $this->terrain->addTerrain(2415 ,1 , "forest");
//        $this->terrain->addTerrain(2715 ,4 , "forest");
//        $this->terrain->addTerrain(2715 ,1 , "forest");
//        $this->terrain->addTerrain(2814 ,3 , "forest");
//        $this->terrain->addTerrain(2814 ,1 , "forest");
//        $this->terrain->addTerrain(2914 ,3 , "forest");
//        $this->terrain->addTerrain(2914 ,1 , "forest");
//        $this->terrain->addTerrain(3014 ,4 , "forest");
//        $this->terrain->addTerrain(3014 ,1 , "forest");
//        $this->terrain->addTerrain(3114 ,3 , "forest");
//        $this->terrain->addTerrain(3114 ,1 , "forest");
//        $this->terrain->addTerrain(3213 ,3 , "forest");
//        $this->terrain->addTerrain(3113 ,2 , "forest");
//        $this->terrain->addTerrain(3213 ,4 , "forest");
//        $this->terrain->addTerrain(3212 ,3 , "forest");
//        $this->terrain->addTerrain(3212 ,2 , "forest");
//        $this->terrain->addTerrain(3313 ,3 , "forest");
//        $this->terrain->addTerrain(3213 ,1 , "forest");
//        $this->terrain->addTerrain(3313 ,4 , "forest");
//        $this->terrain->addTerrain(3312 ,3 , "forest");
//        $this->terrain->addTerrain(3312 ,2 , "forest");
//        $this->terrain->addTerrain(3312 ,1 , "forest");
//        $this->terrain->addTerrain(3313 ,1 , "forest");
//        $this->terrain->addTerrain(3113 ,1 , "forest");
//        $this->terrain->addTerrain(3321 ,3 , "forest");
//        $this->terrain->addTerrain(3220 ,2 , "forest");
//        $this->terrain->addTerrain(3220 ,2 , "road");
//        $this->terrain->addTerrain(3221 ,4 , "forest");
//        $this->terrain->addTerrain(3121 ,1 , "forest");
//        $this->terrain->addReinforceZone(3121,'B');
//        $this->terrain->addTerrain(3120 ,2 , "forest");
//        $this->terrain->addTerrain(3120 ,1 , "forest");
//        $this->terrain->addReinforceZone(3120,'B');
//        $this->terrain->addTerrain(3120 ,1 , "road");
//        $this->terrain->addTerrain(3220 ,4 , "forest");
//        $this->terrain->addTerrain(3220 ,4 , "road");
//        $this->terrain->addTerrain(3220 ,3 , "forest");
//        $this->terrain->addTerrain(3220 ,1 , "forest");
//        $this->terrain->addReinforceZone(3220,'B');
//        $this->terrain->addTerrain(3220 ,1 , "road");
//        $this->terrain->addTerrain(3321 ,4 , "forest");
//        $this->terrain->addTerrain(3321 ,1 , "forest");
//        $this->terrain->addReinforceZone(3321,'B');
//        $this->terrain->addReinforceZone(1309,'A');
//        $this->terrain->addReinforceZone(1208,'A');
//        $this->terrain->addTerrain(1208 ,1 , "road");
//        $this->terrain->addReinforceZone(1108,'A');
//        $this->terrain->addTerrain(1108 ,1 , "road");
//        $this->terrain->addReinforceZone(1207,'A');
//        $this->terrain->addReinforceZone(907,'A');
//        $this->terrain->addTerrain(907 ,1 , "road");
//        $this->terrain->addReinforceZone(906,'A');
//        $this->terrain->addReinforceZone(806,'A');
//        $this->terrain->addTerrain(806 ,1 , "road");
//        $this->terrain->addReinforceZone(805,'A');
//        $this->terrain->addReinforceZone(905,'A');
//        $this->terrain->addReinforceZone(1005,'A');
//        $this->terrain->addReinforceZone(1106,'A');
//        $this->terrain->addReinforceZone(1206,'A');
//        $this->terrain->addReinforceZone(1307,'A');
//        $this->terrain->addReinforceZone(1407,'A');
//        $this->terrain->addReinforceZone(1809,'A');
//        $this->terrain->addTerrain(1809 ,1 , "road");
//        $this->terrain->addReinforceZone(1611,'A');
//        $this->terrain->addReinforceZone(2010,'A');
//        $this->terrain->addTerrain(2010 ,1 , "road");
//        $this->terrain->addReinforceZone(1808,'A');
//        $this->terrain->addReinforceZone(1708,'A');
//        $this->terrain->addReinforceZone(1507,'A');
//        $this->terrain->addReinforceZone(1406,'A');
//        $this->terrain->addReinforceZone(1306,'A');
//        $this->terrain->addReinforceZone(1205,'A');
//        $this->terrain->addReinforceZone(1105,'A');
//        $this->terrain->addReinforceZone(1004,'A');
//        $this->terrain->addReinforceZone(1204,'A');
//        $this->terrain->addReinforceZone(1305,'A');
//        $this->terrain->addReinforceZone(1405,'A');
//        $this->terrain->addReinforceZone(1506,'A');
//        $this->terrain->addReinforceZone(1606,'A');
//        $this->terrain->addTerrain(2111 ,4 , "road");
//        $this->terrain->addTerrain(2211 ,4 , "road");
//        $this->terrain->addReinforceZone(2111,'A');
//        $this->terrain->addTerrain(2111 ,1 , "road");
//        $this->terrain->addReinforceZone(2211,'A');
//        $this->terrain->addTerrain(2211 ,1 , "road");
//        $this->terrain->addReinforceZone(2311,'A');
//        $this->terrain->addReinforceZone(2210,'A');
//        $this->terrain->addReinforceZone(2110,'A');
//        $this->terrain->addReinforceZone(2009,'A');
//        $this->terrain->addReinforceZone(2310,'A');
//        $this->terrain->addReinforceZone(2209,'A');
//        $this->terrain->addReinforceZone(2109,'A');
//        $this->terrain->addReinforceZone(2008,'A');
//        $this->terrain->addReinforceZone(2309,'A');
//        $this->terrain->addReinforceZone(2208,'A');
//        $this->terrain->addReinforceZone(2108,'A');
//        $this->terrain->addReinforceZone(2007,'A');
//        $this->terrain->addReinforceZone(1907,'A');
//        $this->terrain->addReinforceZone(1806,'A');
//        $this->terrain->addReinforceZone(1706,'A');
//        $this->terrain->addReinforceZone(1605,'A');
//        $this->terrain->addReinforceZone(1505,'A');
//        $this->terrain->addReinforceZone(1404,'A');
//        $this->terrain->addReinforceZone(1604,'A');
//        $this->terrain->addReinforceZone(1705,'A');
//        $this->terrain->addReinforceZone(1805,'A');
//        $this->terrain->addReinforceZone(1906,'A');
//        $this->terrain->addReinforceZone(2006,'A');
//        $this->terrain->addReinforceZone(2107,'A');
//        $this->terrain->addReinforceZone(2207,'A');
//        $this->terrain->addReinforceZone(2308,'A');
//        $this->terrain->addReinforceZone(2307,'A');
//        $this->terrain->addReinforceZone(2206,'A');
//        $this->terrain->addReinforceZone(2106,'A');
//        $this->terrain->addReinforceZone(2005,'A');
//        $this->terrain->addReinforceZone(1905,'A');
//        $this->terrain->addReinforceZone(1804,'A');
//        $this->terrain->addReinforceZone(2004,'A');
//        $this->terrain->addReinforceZone(2105,'A');
//        $this->terrain->addReinforceZone(2205,'A');
//        $this->terrain->addReinforceZone(2306,'A');
//        $this->terrain->addReinforceZone(117,'B');
//        $this->terrain->addTerrain(117 ,1 , "road");
//        $this->terrain->addReinforceZone(118,'B');
//        $this->terrain->addReinforceZone(119,'B');
//        $this->terrain->addReinforceZone(120,'B');
//        $this->terrain->addReinforceZone(220,'B');
//        $this->terrain->addReinforceZone(219,'B');
//        $this->terrain->addReinforceZone(218,'B');
//        $this->terrain->addReinforceZone(217,'B');
//        $this->terrain->addTerrain(217 ,1 , "road");
//        $this->terrain->addReinforceZone(318,'B');
//        $this->terrain->addTerrain(318 ,1 , "road");
//        $this->terrain->addReinforceZone(319,'B');
//        $this->terrain->addReinforceZone(320,'B');
//        $this->terrain->addReinforceZone(321,'B');
//        $this->terrain->addReinforceZone(420,'B');
//        $this->terrain->addReinforceZone(419,'B');
//        $this->terrain->addReinforceZone(418,'B');
//        $this->terrain->addReinforceZone(417,'B');
//        $this->terrain->addTerrain(417 ,1 , "road");
//        $this->terrain->addReinforceZone(518,'B');
//        $this->terrain->addTerrain(518 ,1 , "road");
//        $this->terrain->addReinforceZone(519,'B');
//        $this->terrain->addReinforceZone(520,'B');
//        $this->terrain->addReinforceZone(521,'B');
//        $this->terrain->addReinforceZone(621,'B');
//        $this->terrain->addReinforceZone(620,'B');
//        $this->terrain->addReinforceZone(619,'B');
//        $this->terrain->addReinforceZone(618,'B');
//        $this->terrain->addTerrain(618 ,1 , "road");
//        $this->terrain->addReinforceZone(719,'B');
//        $this->terrain->addTerrain(719 ,1 , "road");
//        $this->terrain->addReinforceZone(720,'B');
//        $this->terrain->addReinforceZone(721,'B');
//        $this->terrain->addReinforceZone(722,'B');
//        $this->terrain->addReinforceZone(822,'B');
//        $this->terrain->addReinforceZone(821,'B');
//        $this->terrain->addReinforceZone(820,'B');
//        $this->terrain->addReinforceZone(819,'B');
//        $this->terrain->addReinforceZone(818,'B');
//        $this->terrain->addTerrain(818 ,1 , "town");
//        $this->terrain->addReinforceZone(920,'B');
//        $this->terrain->addReinforceZone(921,'B');
//        $this->terrain->addReinforceZone(922,'B');
//        $this->terrain->addReinforceZone(923,'B');
//        $this->terrain->addReinforceZone(1022,'B');
//        $this->terrain->addReinforceZone(1021,'B');
//        $this->terrain->addReinforceZone(1020,'B');
//        $this->terrain->addReinforceZone(1019,'B');
//        $this->terrain->addTerrain(1019 ,1 , "road");
//        $this->terrain->addReinforceZone(1119,'B');
//        $this->terrain->addTerrain(1119 ,1 , "road");
//        $this->terrain->addReinforceZone(1120,'B');
//        $this->terrain->addReinforceZone(1121,'B');
//        $this->terrain->addReinforceZone(1122,'B');
//        $this->terrain->addReinforceZone(1123,'B');
//        $this->terrain->addReinforceZone(1222,'B');
//        $this->terrain->addReinforceZone(1221,'B');
//        $this->terrain->addReinforceZone(1220,'B');
//        $this->terrain->addReinforceZone(1219,'B');
//        $this->terrain->addTerrain(1219 ,1 , "road");
//        $this->terrain->addReinforceZone(1320,'B');
//        $this->terrain->addTerrain(1320 ,1 , "road");
//        $this->terrain->addReinforceZone(1321,'B');
//        $this->terrain->addReinforceZone(1322,'B');
//        $this->terrain->addReinforceZone(1323,'B');
//        $this->terrain->addReinforceZone(1422,'B');
//        $this->terrain->addReinforceZone(1421,'B');
//        $this->terrain->addReinforceZone(1420,'B');
//        $this->terrain->addReinforceZone(1419,'B');
//        $this->terrain->addTerrain(1419 ,1 , "road");
//        $this->terrain->addReinforceZone(1520,'B');
//        $this->terrain->addTerrain(1520 ,1 , "road");
//        $this->terrain->addReinforceZone(1521,'B');
//        $this->terrain->addReinforceZone(1522,'B');
//        $this->terrain->addReinforceZone(1523,'B');
//        $this->terrain->addReinforceZone(1622,'B');
//        $this->terrain->addReinforceZone(1621,'B');
//        $this->terrain->addReinforceZone(1620,'B');
//        $this->terrain->addReinforceZone(1619,'B');
//        $this->terrain->addTerrain(1619 ,1 , "road");
//        $this->terrain->addReinforceZone(1720,'B');
//        $this->terrain->addTerrain(1720 ,1 , "road");
//        $this->terrain->addReinforceZone(1721,'B');
//        $this->terrain->addReinforceZone(1722,'B');
//        $this->terrain->addReinforceZone(1723,'B');
//        $this->terrain->addReinforceZone(1822,'B');
//        $this->terrain->addReinforceZone(1821,'B');
//        $this->terrain->addReinforceZone(1820,'B');
//        $this->terrain->addReinforceZone(1819,'B');
//        $this->terrain->addTerrain(1819 ,1 , "road");
//        $this->terrain->addReinforceZone(1920,'B');
//        $this->terrain->addTerrain(1920 ,1 , "road");
//        $this->terrain->addReinforceZone(1921,'B');
//        $this->terrain->addReinforceZone(1922,'B');
//        $this->terrain->addReinforceZone(1923,'B');
//        $this->terrain->addReinforceZone(2022,'B');
//        $this->terrain->addReinforceZone(2021,'B');
//        $this->terrain->addReinforceZone(2020,'B');
//        $this->terrain->addReinforceZone(2019,'B');
//        $this->terrain->addTerrain(2019 ,1 , "road");
//        $this->terrain->addReinforceZone(2119,'B');
//        $this->terrain->addTerrain(2119 ,1 , "road");
//        $this->terrain->addReinforceZone(2120,'B');
//        $this->terrain->addReinforceZone(2121,'B');
//        $this->terrain->addReinforceZone(2122,'B');
//        $this->terrain->addReinforceZone(2123,'B');
//        $this->terrain->addReinforceZone(2222,'B');
//        $this->terrain->addReinforceZone(2221,'B');
//        $this->terrain->addReinforceZone(2220,'B');
//        $this->terrain->addReinforceZone(2219,'B');
//        $this->terrain->addReinforceZone(2218,'B');
//        $this->terrain->addTerrain(2218 ,1 , "road");
//        $this->terrain->addReinforceZone(2318,'B');
//        $this->terrain->addTerrain(2318 ,1 , "road");
//        $this->terrain->addReinforceZone(2319,'B');
//        $this->terrain->addReinforceZone(2320,'B');
//        $this->terrain->addReinforceZone(2321,'B');
//        $this->terrain->addReinforceZone(2322,'B');
//        $this->terrain->addReinforceZone(2323,'B');
//        $this->terrain->addReinforceZone(2422,'B');
//        $this->terrain->addReinforceZone(2421,'B');
//        $this->terrain->addReinforceZone(2420,'B');
//        $this->terrain->addReinforceZone(2419,'B');
//        $this->terrain->addReinforceZone(2418,'B');
//        $this->terrain->addTerrain(2418 ,1 , "road");
//        $this->terrain->addReinforceZone(2518,'B');
//        $this->terrain->addTerrain(2518 ,1 , "road");
//        $this->terrain->addReinforceZone(2519,'B');
//        $this->terrain->addReinforceZone(2520,'B');
//        $this->terrain->addReinforceZone(2521,'B');
//        $this->terrain->addReinforceZone(2522,'B');
//        $this->terrain->addReinforceZone(2621,'B');
//        $this->terrain->addReinforceZone(2620,'B');
//        $this->terrain->addReinforceZone(2619,'B');
//        $this->terrain->addReinforceZone(2618,'B');
//        $this->terrain->addTerrain(2618 ,1 , "road");
//        $this->terrain->addReinforceZone(2718,'B');
//        $this->terrain->addTerrain(2718 ,1 , "road");
//        $this->terrain->addReinforceZone(2719,'B');
//        $this->terrain->addReinforceZone(2720,'B');
//        $this->terrain->addReinforceZone(2721,'B');
//        $this->terrain->addReinforceZone(2821,'B');
//        $this->terrain->addReinforceZone(2820,'B');
//        $this->terrain->addReinforceZone(2819,'B');
//        $this->terrain->addReinforceZone(2818,'B');
//        $this->terrain->addTerrain(2818 ,1 , "road");
//        $this->terrain->addReinforceZone(2919,'B');
//        $this->terrain->addTerrain(2919 ,1 , "road");
//        $this->terrain->addReinforceZone(2920,'B');
//        $this->terrain->addReinforceZone(2921,'B');
//        $this->terrain->addReinforceZone(2922,'B');
//        $this->terrain->addReinforceZone(3019,'B');
//        $this->terrain->addTerrain(3019 ,1 , "road");
//        $this->terrain->addReinforceZone(3020,'B');
//        $this->terrain->addReinforceZone(3021,'B');
//        $this->terrain->addReinforceZone(3122,'B');
//        $this->terrain->addReinforceZone(3322,'B');
//        $this->terrain->addTerrain(3322 ,1 , "road");
//        $this->terrain->addTerrain(103 ,1 , "road");
//        $this->terrain->addTerrain(203 ,4 , "road");
//        $this->terrain->addTerrain(203 ,1 , "road");
//        $this->terrain->addTerrain(304 ,4 , "road");
//        $this->terrain->addTerrain(304 ,1 , "road");
//        $this->terrain->addTerrain(404 ,4 , "road");
//        $this->terrain->addTerrain(404 ,1 , "road");
//        $this->terrain->addTerrain(505 ,4 , "road");
//        $this->terrain->addTerrain(505 ,1 , "road");
//        $this->terrain->addTerrain(605 ,4 , "road");
//        $this->terrain->addTerrain(605 ,1 , "road");
//        $this->terrain->addTerrain(706 ,4 , "road");
//        $this->terrain->addTerrain(706 ,1 , "road");
//        $this->terrain->addTerrain(806 ,4 , "road");
//        $this->terrain->addTerrain(907 ,4 , "road");
//        $this->terrain->addTerrain(1007 ,4 , "road");
//        $this->terrain->addTerrain(1108 ,4 , "road");
//        $this->terrain->addTerrain(1308 ,3 , "road");
//        $this->terrain->addTerrain(1609 ,4 , "road");
//        $this->terrain->addTerrain(1809 ,4 , "road");
//        $this->terrain->addTerrain(1910 ,4 , "road");
//        $this->terrain->addTerrain(2312 ,4 , "road");
//        $this->terrain->addTerrain(2312 ,1 , "road");
//        $this->terrain->addTerrain(2412 ,4 , "road");
//        $this->terrain->addTerrain(2412 ,1 , "road");
//        $this->terrain->addTerrain(2412 ,2 , "road");
//        $this->terrain->addTerrain(2514 ,4 , "road");
//        $this->terrain->addTerrain(2514 ,1 , "road");
//        $this->terrain->addTerrain(2514 ,2 , "road");
//        $this->terrain->addTerrain(2615 ,4 , "road");
//        $this->terrain->addTerrain(2615 ,1 , "road");
//        $this->terrain->addTerrain(2615 ,2 , "road");
//        $this->terrain->addTerrain(2616 ,1 , "road");
//        $this->terrain->addTerrain(2616 ,2 , "road");
//        $this->terrain->addTerrain(2617 ,1 , "road");
//        $this->terrain->addTerrain(2718 ,4 , "road");
//        $this->terrain->addTerrain(2818 ,4 , "road");
//        $this->terrain->addTerrain(2919 ,4 , "road");
//        $this->terrain->addTerrain(3019 ,4 , "road");
//        $this->terrain->addTerrain(3120 ,4 , "road");
//        $this->terrain->addTerrain(3322 ,4 , "road");
//        $this->terrain->addTerrain(2718 ,3 , "road");
//        $this->terrain->addTerrain(2618 ,4 , "road");
//        $this->terrain->addTerrain(217 ,4 , "road");
//        $this->terrain->addTerrain(318 ,4 , "road");
//        $this->terrain->addTerrain(417 ,3 , "road");
//        $this->terrain->addTerrain(518 ,4 , "road");
//        $this->terrain->addTerrain(618 ,4 , "road");
//        $this->terrain->addTerrain(719 ,4 , "road");
//        $this->terrain->addTerrain(818 ,3 , "road");
//        $this->terrain->addTerrain(1019 ,4 , "road");
//        $this->terrain->addTerrain(1119 ,3 , "road");
//        $this->terrain->addTerrain(1219 ,4 , "road");
//        $this->terrain->addTerrain(1320 ,4 , "road");
//        $this->terrain->addTerrain(1419 ,3 , "road");
//        $this->terrain->addTerrain(1520 ,4 , "road");
//        $this->terrain->addTerrain(1619 ,3 , "road");
//        $this->terrain->addTerrain(1720 ,4 , "road");
//        $this->terrain->addTerrain(1819 ,3 , "road");
//        $this->terrain->addTerrain(1920 ,4 , "road");
//        $this->terrain->addTerrain(2019 ,3 , "road");
//        $this->terrain->addTerrain(2119 ,3 , "road");
//        $this->terrain->addTerrain(2218 ,3 , "road");
//        $this->terrain->addTerrain(2318 ,3 , "road");
//        $this->terrain->addTerrain(2418 ,4 , "road");
//        $this->terrain->addTerrain(2518 ,3 , "road");


//
//        $this->moodkee = $specialHexB[0];
//        $specialHexes = [];
//        foreach ($specialHexA as $specialHexId) {
//            $specialHexes[$specialHexId] = SIKH_FORCE;
//        }
//        foreach ($specialHexB as $specialHexId) {
//            $specialHexes[$specialHexId] = BRITISH_FORCE;
//        }
//        $this->mapData->setSpecialHexes($specialHexes);
//
//    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->moodkee = $data->moodkee;
            $this->game = $data->game;
            $this->genTerrain = false;
            $this->victory = new Victory("Mollwitz/Ferozesha/ferozeshaVictoryCore.php", $data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->moveRules->stickyZOC = false;
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = $data->players;
            $this->playerData = $data->playerData;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;
            $this->genTerrain = true;
            $this->victory = new Victory("Mollwitz/Ferozesha/ferozeshaVictoryCore.php");


            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;


            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
//            $this->force->combatRequired = true;
            $this->terrain = new Terrain();
//            $this->terrain->setMaxHex("2223");
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->zocBlocksRetreat = true;
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
//            $this->players = array("", "", "");
//            $this->playerData = new stdClass();
//            for ($player = 0; $player <= 2; $player++) {
//                $this->playerData->${player} = new stdClass();
//                $this->playerData->${player}->mapWidth = "auto";
//                $this->playerData->${player}->mapHeight = "auto";
//                $this->playerData->${player}->unitSize = "32px";
//                $this->playerData->${player}->unitFontSize = "12px";
//                $this->playerData->${player}->unitMargin = "-21px";
//                $this->mapViewer[$player]->setData(51.10000000000001 , 81.96930446819712, // originX, originY
//                    27.323101489399043, 27.323101489399043, // top hexagon height, bottom hexagon height
//                    15.775, 31.55// hexagon edge width, hexagon center width
//                );
//            }

            // game data
            if($scenario->dayTwo){
                $this->gameRules->setMaxTurn(14);
            }else{
                $this->gameRules->setMaxTurn(12);
            }
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data

            $i = 0;

            // end unit data -------------------------------------------

            // unit terrain data----------------------------------------



            // end terrain data ----------------------------------------

            // Added this comment
        }
    }
}