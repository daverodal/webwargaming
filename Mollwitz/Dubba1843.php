<?php
set_include_path(__DIR__ . "/Dubba1843" . PATH_SEPARATOR . get_include_path());
require_once "JagCore.php";

/* comment */
define("BRITISH_FORCE", 1);
define("BELUCHI_FORCE", 2);
$force_name[BELUCHI_FORCE] = "Beluchi";
$force_name[BRITISH_FORCE] = "British";
$phase_name = array();
$phase_name[1] = "British Move";
$phase_name[2] = "British Combat";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "Beluchi Move";
$phase_name[5] = "Beluchi Combat";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "British Deploy";
$phase_name[9] = "Beluchi Mech";
$phase_name[10] = "Prussian Replacement";
$phase_name[11] = "Russian Mech";
$phase_name[12] = "Russian Replacement";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "Beluchi deploy phase";



// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class Dubba1843 extends JagCore
{

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $playerData;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;
    public $display;
    public $victory;
    public $genTerrain;
    public $roadHex;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "commonHeader.php";
        @include_once "header.php";
        @include_once "Dubba1843Header.php";

    }


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {

        global $force_name;
        @include_once "view.php";
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
        $data->roadHex = $this->roadHex;
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }



    public function init()
    {

        $artRange = 3;

        for ($i = 0; $i < 25; $i++) {
            $this->force->addUnit("infantry-1", BELUCHI_FORCE, "deployBox", "SikhInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Beluchi", false, 'infantry');
        }
        for ($i = 0; $i < 15; $i++) {
            $this->force->addUnit("infantry-1", BELUCHI_FORCE, "deployBox", "SikhCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Beluchi", false, 'cavalry');
        }
        for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", BELUCHI_FORCE, "deployBox", "SikhArtBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 2, "Beluchi", false, 'artillery');
        }


        for ($i = 0; $i < 4; $i++) {
            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritInfBadge.png", 6, 6, 4, true, STATUS_CAN_DEPLOY, "A", 1, 1, "British", false, 'infantry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeInfBadge.png", 5, 5, 4, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Native", false, 'infantry');
        }
        for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritCavBadge.png", 6, 6, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "British", false, 'cavalry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeCavBadge.png", 5, 5, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Native", false, 'cavalry');
        }
         for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritArtBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "British", false, 'artillery');
        }
        for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritHorArtBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "British", false, 'horseartillery');
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->roadHex = $data->roadHex;
            $this->game = $data->game;
            $this->genTerrain = false;
            $this->victory = new Victory("Mollwitz/Dubba1843/dubba1843VictoryCore.php", $data);
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
            $this->victory = new Victory("Mollwitz/Dubba1843/dubba1843VictoryCore.php");

            $this->mapData->setData(28, 19, "js/Dubba1843Small.png");
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
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = array("", "", "");
            $this->playerData = new stdClass();
            for ($player = 0; $player <= 2; $player++) {
                $this->playerData->${player} = new stdClass();
                $this->playerData->${player}->mapWidth = "auto";
                $this->playerData->${player}->mapHeight = "auto";
                $this->playerData->${player}->unitSize = "32px";
                $this->playerData->${player}->unitFontSize = "12px";
                $this->playerData->${player}->unitMargin = "-21px";
                $this->mapViewer[$player]->setData(49.10000000000001 , 78.07219015116715, // originX, originY
                    26.024063383722382, 26.024063383722382, // top hexagon height, bottom hexagon height
                    15.025, 30.05// hexagon edge width, hexagon center width
                );
            }

            // game data
            $this->gameRules->setMaxTurn(12);
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

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", .75, 0, 0, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
            $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
            $this->terrain->addNatAltEntranceCost('forest','Beluchi', 'infantry', 1);
            $this->terrain->addAltEntranceCost('forest', 'horseartillery', 4);
            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("redoubt", "redoubt", "d", 0, 2, 0, false);
            $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("wadi", "wadi", "v", 0, 2, 0, false);
            $this->terrain->addAltEntranceCost('swamp','artillery','blocked');


            for ($col = 100; $col <= 2800; $col += 100) {
                for ($row = 1; $row <= 19; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
            $specialHexA = [];
            $specialHexB = [];
            $this->terrain->addTerrain(1211 ,1 , "town");
            $this->terrain->addReinforceZone(1211,'A');
            $this->terrain->addTerrain(2006 ,1 , "town");
            $this->terrain->addReinforceZone(2006,'B');
            $this->terrain->addTerrain(2106 ,3 , "town");
            $this->terrain->addTerrain(2106 ,1 , "town");
            $this->terrain->addReinforceZone(2106,'B');
            $this->terrain->addTerrain(1116 ,1 , "town");
            $this->terrain->addTerrain(1106 ,1 , "forest");
            $this->terrain->addTerrain(1205 ,3 , "forest");
            $this->terrain->addTerrain(1205 ,1 , "forest");
            $this->terrain->addTerrain(1804 ,1 , "forest");
            $this->terrain->addTerrain(1804 ,2 , "forest");
            $this->terrain->addTerrain(1805 ,1 , "forest");
            $this->terrain->addTerrain(1904 ,3 , "forest");
            $this->terrain->addTerrain(1905 ,4 , "forest");
            $this->terrain->addTerrain(1905 ,3 , "forest");
            $this->terrain->addTerrain(1906 ,4 , "forest");
            $this->terrain->addTerrain(1904 ,1 , "forest");
            $this->terrain->addTerrain(1904 ,2 , "forest");
            $this->terrain->addTerrain(1905 ,1 , "forest");
            $this->terrain->addTerrain(1905 ,2 , "forest");
            $this->terrain->addTerrain(1906 ,1 , "forest");
            $this->terrain->addTerrain(2003 ,3 , "forest");
            $this->terrain->addTerrain(2004 ,4 , "forest");
            $this->terrain->addTerrain(2004 ,3 , "forest");
            $this->terrain->addTerrain(2005 ,4 , "forest");
            $this->terrain->addTerrain(2005 ,3 , "forest");
            $this->terrain->addTerrain(2002 ,1 , "forest");
            $this->terrain->addTerrain(2002 ,2 , "forest");
            $this->terrain->addTerrain(2003 ,1 , "forest");
            $this->terrain->addTerrain(2003 ,2 , "forest");
            $this->terrain->addTerrain(2004 ,1 , "forest");
            $this->terrain->addTerrain(2004 ,2 , "forest");
            $this->terrain->addTerrain(2005 ,1 , "forest");
            $this->terrain->addTerrain(2102 ,3 , "forest");
            $this->terrain->addTerrain(2103 ,4 , "forest");
            $this->terrain->addTerrain(2103 ,3 , "forest");
            $this->terrain->addTerrain(2104 ,4 , "forest");
            $this->terrain->addTerrain(2104 ,3 , "forest");
            $this->terrain->addTerrain(2105 ,4 , "forest");
            $this->terrain->addTerrain(2105 ,3 , "forest");
            $this->terrain->addTerrain(2102 ,1 , "forest");
            $this->terrain->addTerrain(2102 ,2 , "forest");
            $this->terrain->addTerrain(2103 ,1 , "forest");
            $this->terrain->addTerrain(2103 ,2 , "forest");
            $this->terrain->addTerrain(2104 ,1 , "forest");
            $this->terrain->addTerrain(2104 ,2 , "forest");
            $this->terrain->addTerrain(2105 ,1 , "forest");
            $this->terrain->addTerrain(2201 ,3 , "forest");
            $this->terrain->addTerrain(2201 ,3 , "wadi");
            $this->terrain->addTerrain(2202 ,4 , "forest");
            $this->terrain->addTerrain(2202 ,4 , "wadi");
            $this->terrain->addTerrain(2202 ,3 , "forest");
            $this->terrain->addTerrain(2202 ,3 , "wadi");
            $this->terrain->addTerrain(2203 ,4 , "forest");
            $this->terrain->addTerrain(2203 ,4 , "wadi");
            $this->terrain->addTerrain(2203 ,3 , "forest");
            $this->terrain->addTerrain(2203 ,3 , "wadi");
            $this->terrain->addTerrain(2204 ,4 , "forest");
            $this->terrain->addTerrain(2204 ,4 , "wadi");
            $this->terrain->addTerrain(2204 ,3 , "forest");
            $this->terrain->addTerrain(2204 ,3 , "wadi");
            $this->terrain->addTerrain(2201 ,1 , "forest");
            $this->terrain->addTerrain(2201 ,2 , "forest");
            $this->terrain->addTerrain(2202 ,1 , "forest");
            $this->terrain->addTerrain(2202 ,2 , "forest");
            $this->terrain->addTerrain(2203 ,1 , "forest");
            $this->terrain->addTerrain(2203 ,2 , "forest");
            $this->terrain->addTerrain(2204 ,1 , "forest");
            $this->terrain->addTerrain(2302 ,4 , "forest");
            $this->terrain->addTerrain(2302 ,3 , "forest");
            $this->terrain->addTerrain(2303 ,4 , "forest");
            $this->terrain->addTerrain(2303 ,3 , "forest");
            $this->terrain->addTerrain(2304 ,4 , "forest");
            $this->terrain->addTerrain(2304 ,3 , "forest");
            $this->terrain->addTerrain(2305 ,4 , "forest");
            $this->terrain->addTerrain(2302 ,1 , "forest");
            $this->terrain->addTerrain(2302 ,2 , "forest");
            $this->terrain->addTerrain(2303 ,1 , "forest");
            $this->terrain->addTerrain(2303 ,2 , "forest");
            $this->terrain->addTerrain(2304 ,1 , "forest");
            $this->terrain->addTerrain(2304 ,2 , "forest");
            $this->terrain->addTerrain(2305 ,1 , "forest");
            $this->terrain->addTerrain(2405 ,4 , "forest");
            $this->terrain->addTerrain(2405 ,1 , "forest");
            $this->terrain->addTerrain(2204 ,2 , "forest");
            $this->terrain->addTerrain(2204 ,2 , "wadi");
            $this->terrain->addTerrain(2205 ,1 , "forest");
            $this->terrain->addReinforceZone(2205,'B');
            $this->terrain->addTerrain(2205 ,2 , "forest");
            $this->terrain->addTerrain(2206 ,1 , "forest");
            $this->terrain->addReinforceZone(2206,'B');
            $this->terrain->addTerrain(2206 ,2 , "forest");
            $this->terrain->addTerrain(2207 ,1 , "forest");
            $this->terrain->addReinforceZone(2207,'B');
            $this->terrain->addTerrain(2207 ,3 , "forest");
            $this->terrain->addTerrain(2207 ,3 , "wadi");
            $this->terrain->addTerrain(2108 ,1 , "forest");
            $this->terrain->addReinforceZone(2108,'B');
            $this->terrain->addTerrain(2305 ,3 , "forest");
            $this->terrain->addTerrain(2305 ,3 , "wadi");
            $this->terrain->addTerrain(2306 ,4 , "forest");
            $this->terrain->addTerrain(2306 ,3 , "forest");
            $this->terrain->addTerrain(2305 ,2 , "forest");
            $this->terrain->addTerrain(2305 ,2 , "wadi");
            $this->terrain->addTerrain(2306 ,1 , "forest");
            $this->terrain->addReinforceZone(2306,'B');
            $this->terrain->addTerrain(1112 ,1 , "forest");
            $this->terrain->addTerrain(1112 ,1 , "road");
            $this->terrain->addReinforceZone(1112,'A');
            $this->terrain->addTerrain(1211 ,3 , "forest");
            $this->terrain->addTerrain(1211 ,3 , "road");
            $this->terrain->addTerrain(1211 ,3 , "wadi");
            $this->terrain->addTerrain(1210 ,2 , "forest");
            $this->terrain->addTerrain(1210 ,1 , "forest");
            $this->terrain->addReinforceZone(1210,'A');
            $this->terrain->addTerrain(1016 ,1 , "forest");
            $this->terrain->addTerrain(1117 ,4 , "forest");
            $this->terrain->addTerrain(1117 ,1 , "forest");
            $this->terrain->addTerrain(1216 ,3 , "forest");
            $this->terrain->addTerrain(1216 ,1 , "forest");
            $this->terrain->addTerrain(1216 ,4 , "forest");
            $this->terrain->addTerrain(1116 ,2 , "forest");
            $this->terrain->addTerrain(1116 ,3 , "forest");
            $this->terrain->addTerrain(1613 ,1 , "forest");
            $this->terrain->addTerrain(1613 ,2 , "forest");
            $this->terrain->addTerrain(1614 ,1 , "forest");
            $this->terrain->addTerrain(1614 ,2 , "forest");
            $this->terrain->addTerrain(1615 ,1 , "forest");
            $this->terrain->addTerrain(1615 ,2 , "forest");
            $this->terrain->addTerrain(1616 ,1 , "forest");
            $this->terrain->addTerrain(1713 ,3 , "forest");
            $this->terrain->addTerrain(1713 ,3 , "wadi");
            $this->terrain->addTerrain(1715 ,4 , "forest");
            $this->terrain->addTerrain(1715 ,4 , "wadi");
            $this->terrain->addTerrain(1715 ,3 , "forest");
            $this->terrain->addTerrain(1715 ,3 , "wadi");
            $this->terrain->addTerrain(1716 ,4 , "forest");
            $this->terrain->addTerrain(1716 ,4 , "wadi");
            $this->terrain->addTerrain(1716 ,3 , "forest");
            $this->terrain->addTerrain(1716 ,3 , "wadi");
            $this->terrain->addTerrain(1717 ,4 , "forest");
            $this->terrain->addTerrain(1717 ,4 , "wadi");
            $this->terrain->addTerrain(1713 ,1 , "forest");
            $this->terrain->addTerrain(1715 ,1 , "forest");
            $this->terrain->addReinforceZone(1715,'B');
            $this->terrain->addTerrain(1715 ,2 , "forest");
            $this->terrain->addTerrain(1716 ,1 , "forest");
            $this->terrain->addTerrain(1716 ,2 , "forest");
            $this->terrain->addTerrain(1717 ,1 , "forest");
            $this->terrain->addTerrain(1811 ,1 , "forest");
            $this->terrain->addTerrain(1812 ,3 , "forest");
            $this->terrain->addTerrain(1813 ,4 , "forest");
            $this->terrain->addTerrain(1813 ,4 , "wadi");
            $this->terrain->addTerrain(1814 ,3 , "forest");
            $this->terrain->addTerrain(1814 ,3 , "wadi");
            $this->terrain->addTerrain(1815 ,4 , "forest");
            $this->terrain->addTerrain(1815 ,4 , "wadi");
            $this->terrain->addTerrain(1815 ,3 , "forest");
            $this->terrain->addTerrain(1815 ,3 , "wadi");
            $this->terrain->addTerrain(1816 ,4 , "forest");
            $this->terrain->addTerrain(1816 ,4 , "wadi");
            $this->terrain->addTerrain(1816 ,3 , "forest");
            $this->terrain->addTerrain(1816 ,3 , "wadi");
            $this->terrain->addTerrain(1817 ,4 , "forest");
            $this->terrain->addTerrain(1817 ,4 , "wadi");
            $this->terrain->addTerrain(1811 ,2 , "forest");
            $this->terrain->addTerrain(1812 ,1 , "forest");
            $this->terrain->addTerrain(1812 ,2 , "forest");
            $this->terrain->addTerrain(1812 ,2 , "wadi");
            $this->terrain->addTerrain(1813 ,1 , "forest");
            $this->terrain->addReinforceZone(1813,'B');
            $this->terrain->addTerrain(1813 ,2 , "forest");
            $this->terrain->addTerrain(1814 ,1 , "forest");
            $this->terrain->addReinforceZone(1814,'B');
            $this->terrain->addTerrain(1814 ,2 , "forest");
            $this->terrain->addTerrain(1815 ,1 , "forest");
            $this->terrain->addReinforceZone(1815,'B');
            $this->terrain->addTerrain(1815 ,2 , "forest");
            $this->terrain->addTerrain(1816 ,1 , "forest");
            $this->terrain->addTerrain(1816 ,2 , "forest");
            $this->terrain->addTerrain(1817 ,1 , "forest");
            $this->terrain->addTerrain(1912 ,4 , "forest");
            $this->terrain->addTerrain(1912 ,3 , "forest");
            $this->terrain->addTerrain(1913 ,4 , "forest");
            $this->terrain->addTerrain(1913 ,4 , "wadi");
            $this->terrain->addTerrain(1913 ,3 , "forest");
            $this->terrain->addTerrain(1914 ,4 , "forest");
            $this->terrain->addTerrain(1914 ,3 , "forest");
            $this->terrain->addTerrain(1915 ,4 , "forest");
            $this->terrain->addTerrain(1915 ,3 , "forest");
            $this->terrain->addTerrain(1912 ,1 , "forest");
            $this->terrain->addReinforceZone(1912,'B');
            $this->terrain->addTerrain(1912 ,2 , "forest");
            $this->terrain->addTerrain(1912 ,2 , "wadi");
            $this->terrain->addTerrain(1913 ,1 , "forest");
            $this->terrain->addReinforceZone(1913,'B');
            $this->terrain->addTerrain(1913 ,2 , "forest");
            $this->terrain->addTerrain(1914 ,1 , "forest");
            $this->terrain->addReinforceZone(1914,'B');
            $this->terrain->addTerrain(1914 ,2 , "forest");
            $this->terrain->addTerrain(1915 ,1 , "forest");
            $this->terrain->addReinforceZone(1915,'B');
            $this->terrain->addTerrain(116 ,1 , "road");
            $this->terrain->addReinforceZone(116,'A');
            $specialHexA[] = 116;
            $this->terrain->addTerrain(215 ,3 , "road");
            $this->terrain->addTerrain(215 ,1 , "road");
            $this->terrain->addReinforceZone(215,'A');
            $this->terrain->addTerrain(315 ,3 , "road");
            $this->terrain->addTerrain(315 ,1 , "road");
            $this->terrain->addReinforceZone(315,'A');
            $this->terrain->addTerrain(414 ,3 , "road");
            $this->terrain->addTerrain(414 ,1 , "road");
            $this->terrain->addReinforceZone(414,'A');
            $this->terrain->addTerrain(514 ,3 , "road");
            $this->terrain->addTerrain(514 ,1 , "road");
            $this->terrain->addReinforceZone(514,'A');
            $this->terrain->addTerrain(613 ,3 , "road");
            $this->terrain->addTerrain(613 ,1 , "road");
            $this->terrain->addReinforceZone(613,'A');
            $this->terrain->addTerrain(713 ,3 , "road");
            $this->terrain->addTerrain(713 ,1 , "road");
            $this->terrain->addReinforceZone(713,'A');
            $this->terrain->addTerrain(812 ,3 , "road");
            $this->terrain->addTerrain(812 ,1 , "road");
            $this->terrain->addReinforceZone(812,'A');
            $this->terrain->addTerrain(913 ,4 , "road");
            $this->terrain->addTerrain(913 ,1 , "road");
            $this->terrain->addReinforceZone(913,'A');
            $this->terrain->addTerrain(1012 ,3 , "road");
            $this->terrain->addTerrain(1012 ,3 , "wadi");
            $this->terrain->addTerrain(1012 ,1 , "road");
            $this->terrain->addReinforceZone(1012,'A');
            $this->terrain->addTerrain(1112 ,3 , "road");
            $this->terrain->addTerrain(1311 ,3 , "road");
            $this->terrain->addTerrain(1311 ,1 , "road");
            $this->terrain->addReinforceZone(1311,'A');
            $this->terrain->addTerrain(1410 ,3 , "road");
            $this->terrain->addTerrain(1410 ,1 , "road");
            $this->terrain->addTerrain(1510 ,3 , "road");
            $this->terrain->addTerrain(1510 ,1 , "road");
            $this->terrain->addTerrain(1509 ,2 , "road");
            $this->terrain->addTerrain(1509 ,1 , "road");
            $this->terrain->addTerrain(1608 ,3 , "road");
            $this->terrain->addTerrain(1608 ,1 , "road");
            $this->terrain->addTerrain(1708 ,3 , "road");
            $this->terrain->addTerrain(1708 ,1 , "road");
            $this->terrain->addTerrain(1807 ,3 , "road");
            $this->terrain->addTerrain(1807 ,1 , "road");
            $this->terrain->addTerrain(1907 ,3 , "road");
            $this->terrain->addTerrain(1907 ,3 , "wadi");
            $this->terrain->addTerrain(1907 ,1 , "road");
            $this->terrain->addReinforceZone(1907,'B');
            $this->terrain->addTerrain(2006 ,3 , "road");
            $this->terrain->addTerrain(2006 ,3 , "wadi");
            $this->terrain->addTerrain(307 ,4 , "wadi");
            $this->terrain->addTerrain(307 ,3 , "wadi");
            $this->terrain->addTerrain(308 ,4 , "wadi");
            $this->terrain->addTerrain(308 ,3 , "wadi");
            $this->terrain->addTerrain(308 ,2 , "wadi");
            $this->terrain->addTerrain(408 ,3 , "wadi");
            $this->terrain->addTerrain(408 ,2 , "wadi");
            $this->terrain->addTerrain(509 ,3 , "wadi");
            $this->terrain->addTerrain(509 ,2 , "wadi");
            $this->terrain->addTerrain(609 ,3 , "wadi");
            $this->terrain->addTerrain(609 ,2 , "wadi");
            $this->terrain->addTerrain(710 ,4 , "wadi");
            $this->terrain->addTerrain(709 ,2 , "wadi");
            $this->terrain->addTerrain(809 ,3 , "wadi");
            $this->terrain->addTerrain(809 ,2 , "wadi");
            $this->terrain->addTerrain(910 ,3 , "wadi");
            $this->terrain->addTerrain(910 ,2 , "wadi");
            $this->terrain->addTerrain(1010 ,3 , "wadi");
            $this->terrain->addTerrain(1011 ,4 , "wadi");
            $this->terrain->addTerrain(1011 ,3 , "wadi");
            $this->terrain->addTerrain(1012 ,4 , "wadi");
            $this->terrain->addTerrain(1013 ,4 , "wadi");
            $this->terrain->addTerrain(1013 ,3 , "wadi");
            $this->terrain->addTerrain(1014 ,4 , "wadi");
            $this->terrain->addTerrain(1014 ,3 , "wadi");
            $this->terrain->addTerrain(1015 ,4 , "wadi");
            $this->terrain->addTerrain(1015 ,3 , "wadi");
            $this->terrain->addTerrain(1016 ,4 , "wadi");
            $this->terrain->addTerrain(1016 ,3 , "wadi");
            $this->terrain->addTerrain(1017 ,4 , "wadi");
            $this->terrain->addTerrain(1017 ,3 , "wadi");
            $this->terrain->addTerrain(1018 ,4 , "wadi");
            $this->terrain->addTerrain(1018 ,3 , "wadi");
            $this->terrain->addTerrain(1019 ,4 , "wadi");
            $this->terrain->addTerrain(1014 ,2 , "wadi");
            $this->terrain->addTerrain(1115 ,3 , "wadi");
            $this->terrain->addTerrain(1115 ,2 , "wadi");
            $this->terrain->addTerrain(1215 ,3 , "wadi");
            $this->terrain->addTerrain(1215 ,2 , "wadi");
            $this->terrain->addTerrain(1316 ,4 , "wadi");
            $this->terrain->addTerrain(1315 ,2 , "wadi");
            $this->terrain->addTerrain(1415 ,3 , "wadi");
            $this->terrain->addTerrain(1415 ,2 , "wadi");
            $this->terrain->addTerrain(1516 ,3 , "wadi");
            $this->terrain->addTerrain(1516 ,2 , "wadi");
            $this->terrain->addTerrain(1616 ,3 , "wadi");
            $this->terrain->addTerrain(1616 ,2 , "wadi");
            $this->terrain->addTerrain(1717 ,3 , "wadi");
            $this->terrain->addTerrain(1718 ,4 , "wadi");
            $this->terrain->addTerrain(1718 ,3 , "wadi");
            $this->terrain->addTerrain(1719 ,4 , "wadi");
            $this->terrain->addTerrain(1719 ,3 , "wadi");
            $this->terrain->addTerrain(1012 ,2 , "wadi");
            $this->terrain->addTerrain(1113 ,3 , "wadi");
            $this->terrain->addTerrain(1113 ,2 , "wadi");
            $this->terrain->addTerrain(1213 ,4 , "wadi");
            $this->terrain->addTerrain(1212 ,2 , "wadi");
            $this->terrain->addTerrain(1313 ,3 , "wadi");
            $this->terrain->addTerrain(1313 ,2 , "wadi");
            $this->terrain->addTerrain(1413 ,3 , "wadi");
            $this->terrain->addTerrain(1413 ,2 , "wadi");
            $this->terrain->addTerrain(1514 ,3 , "wadi");
            $this->terrain->addTerrain(1514 ,2 , "wadi");
            $this->terrain->addTerrain(1614 ,3 , "wadi");
            $this->terrain->addTerrain(1615 ,4 , "wadi");
            $this->terrain->addTerrain(1615 ,3 , "wadi");
            $this->terrain->addTerrain(1616 ,4 , "wadi");
            $this->terrain->addTerrain(1010 ,2 , "wadi");
            $this->terrain->addTerrain(1111 ,3 , "wadi");
            $this->terrain->addTerrain(1111 ,2 , "wadi");
            $this->terrain->addTerrain(1212 ,4 , "wadi");
            $this->terrain->addTerrain(1212 ,3 , "wadi");
            $this->terrain->addTerrain(1614 ,4 , "wadi");
            $this->terrain->addTerrain(1613 ,3 , "wadi");
            $this->terrain->addTerrain(1613 ,4 , "wadi");
            $this->terrain->addTerrain(1612 ,2 , "wadi");
            $this->terrain->addTerrain(1713 ,4 , "wadi");
            $this->terrain->addTerrain(1712 ,2 , "wadi");
            $this->terrain->addTerrain(1812 ,4 , "wadi");
            $this->terrain->addTerrain(1811 ,3 , "wadi");
            $this->terrain->addTerrain(1811 ,4 , "wadi");
            $this->terrain->addTerrain(1810 ,2 , "wadi");
            $this->terrain->addTerrain(1911 ,3 , "wadi");
            $this->terrain->addTerrain(1911 ,2 , "wadi");
            $this->terrain->addTerrain(2011 ,3 , "wadi");
            $this->terrain->addTerrain(2012 ,4 , "wadi");
            $this->terrain->addTerrain(1813 ,3 , "wadi");
            $this->terrain->addTerrain(1814 ,4 , "wadi");
            $this->terrain->addTerrain(1714 ,2 , "wadi");
            $this->terrain->addTerrain(1714 ,3 , "wadi");
            $this->terrain->addTerrain(1714 ,4 , "wadi");
            $this->terrain->addTerrain(1817 ,3 , "wadi");
            $this->terrain->addTerrain(1818 ,4 , "wadi");
            $this->terrain->addTerrain(1818 ,3 , "wadi");
            $this->terrain->addTerrain(1819 ,4 , "wadi");
            $this->terrain->addTerrain(1911 ,4 , "wadi");
            $this->terrain->addTerrain(1910 ,3 , "wadi");
            $this->terrain->addTerrain(1910 ,4 , "wadi");
            $this->terrain->addTerrain(1909 ,3 , "wadi");
            $this->terrain->addTerrain(1909 ,4 , "wadi");
            $this->terrain->addTerrain(1908 ,3 , "wadi");
            $this->terrain->addTerrain(1908 ,4 , "wadi");
            $this->terrain->addTerrain(1907 ,4 , "wadi");
            $this->terrain->addTerrain(1906 ,2 , "wadi");
            $this->terrain->addTerrain(1906 ,3 , "wadi");
            $this->terrain->addTerrain(1805 ,2 , "wadi");
            $this->terrain->addTerrain(1805 ,3 , "wadi");
            $this->terrain->addTerrain(1805 ,4 , "wadi");
            $this->terrain->addTerrain(1804 ,3 , "wadi");
            $this->terrain->addTerrain(1704 ,2 , "wadi");
            $this->terrain->addTerrain(1704 ,3 , "wadi");
            $this->terrain->addTerrain(1603 ,2 , "wadi");
            $this->terrain->addTerrain(1603 ,3 , "wadi");
            $this->terrain->addTerrain(1603 ,4 , "wadi");
            $this->terrain->addTerrain(1602 ,3 , "wadi");
            $this->terrain->addTerrain(1502 ,2 , "wadi");
            $this->terrain->addTerrain(1503 ,4 , "wadi");
            $this->terrain->addTerrain(1402 ,2 , "wadi");
            $this->terrain->addTerrain(1302 ,2 , "wadi");
            $this->terrain->addTerrain(1402 ,3 , "wadi");
            $this->terrain->addTerrain(1303 ,4 , "wadi");
            $this->terrain->addTerrain(1202 ,2 , "wadi");
            $this->terrain->addTerrain(1202 ,3 , "wadi");
            $this->terrain->addTerrain(1102 ,2 , "wadi");
            $this->terrain->addTerrain(1103 ,4 , "wadi");
            $this->terrain->addTerrain(1002 ,2 , "wadi");
            $this->terrain->addTerrain(1003 ,4 , "wadi");
            $this->terrain->addTerrain(903 ,2 , "wadi");
            $this->terrain->addTerrain(904 ,4 , "wadi");
            $this->terrain->addTerrain(803 ,2 , "wadi");
            $this->terrain->addTerrain(804 ,4 , "wadi");
            $this->terrain->addTerrain(704 ,2 , "wadi");
            $this->terrain->addTerrain(705 ,4 , "wadi");
            $this->terrain->addTerrain(604 ,2 , "wadi");
            $this->terrain->addTerrain(605 ,4 , "wadi");
            $this->terrain->addTerrain(505 ,2 , "wadi");
            $this->terrain->addTerrain(506 ,4 , "wadi");
            $this->terrain->addTerrain(405 ,2 , "wadi");
            $this->terrain->addTerrain(406 ,4 , "wadi");
            $this->terrain->addTerrain(306 ,2 , "wadi");
            $this->terrain->addTerrain(2006 ,2 , "wadi");
            $this->terrain->addTerrain(2107 ,3 , "wadi");
            $this->terrain->addTerrain(2108 ,4 , "wadi");
            $this->terrain->addTerrain(2108 ,3 , "wadi");
            $this->terrain->addTerrain(2108 ,2 , "wadi");
            $this->terrain->addTerrain(2208 ,3 , "wadi");
            $this->terrain->addTerrain(2208 ,2 , "wadi");
            $this->terrain->addTerrain(2309 ,3 , "wadi");
            $this->terrain->addTerrain(2309 ,2 , "wadi");
            $this->terrain->addTerrain(2409 ,3 , "wadi");
            $this->terrain->addTerrain(2409 ,2 , "wadi");
            $this->terrain->addTerrain(2510 ,3 , "wadi");
            $this->terrain->addTerrain(2510 ,2 , "wadi");
            $this->terrain->addTerrain(2610 ,3 , "wadi");
            $this->terrain->addTerrain(2610 ,2 , "wadi");
            $this->terrain->addTerrain(2711 ,3 , "wadi");
            $this->terrain->addTerrain(2711 ,2 , "wadi");
            $this->terrain->addTerrain(2811 ,3 , "wadi");
            $this->terrain->addTerrain(2811 ,2 , "wadi");
            $this->terrain->addTerrain(2811 ,4 , "wadi");
            $this->terrain->addTerrain(2810 ,3 , "wadi");
            $this->terrain->addTerrain(2710 ,2 , "wadi");
            $this->terrain->addTerrain(2710 ,3 , "wadi");
            $this->terrain->addTerrain(2710 ,4 , "wadi");
            $this->terrain->addTerrain(2709 ,3 , "wadi");
            $this->terrain->addTerrain(2608 ,2 , "wadi");
            $this->terrain->addTerrain(2608 ,3 , "wadi");
            $this->terrain->addTerrain(2608 ,4 , "wadi");
            $this->terrain->addTerrain(2607 ,3 , "wadi");
            $this->terrain->addTerrain(2507 ,2 , "wadi");
            $this->terrain->addTerrain(2406 ,2 , "wadi");
            $this->terrain->addTerrain(2507 ,3 , "wadi");
            $this->terrain->addTerrain(2406 ,3 , "wadi");
            $this->terrain->addTerrain(2406 ,4 , "wadi");
            $this->terrain->addTerrain(2405 ,3 , "wadi");
            $this->terrain->addTerrain(2205 ,4 , "wadi");
            $this->terrain->addTerrain(2105 ,2 , "wadi");
            $this->terrain->addTerrain(2106 ,4 , "wadi");
            $this->terrain->addTerrain(2005 ,2 , "wadi");
            $this->terrain->addTerrain(2006 ,4 , "wadi");
            $this->terrain->addTerrain(2201 ,4 , "wadi");
            $this->terrain->addTerrain(2205 ,3 , "wadi");
            $this->terrain->addTerrain(2206 ,4 , "wadi");
            $this->terrain->addTerrain(2206 ,3 , "wadi");
            $this->terrain->addTerrain(2207 ,4 , "wadi");
            $this->terrain->addTerrain(2208 ,4 , "wadi");
            $this->terrain->addTerrain(2012 ,3 , "wadi");
            $this->terrain->addTerrain(2012 ,2 , "wadi");
            $this->terrain->addTerrain(2113 ,3 , "wadi");
            $this->terrain->addTerrain(2114 ,4 , "wadi");
            $this->terrain->addTerrain(2114 ,3 , "wadi");
            $this->terrain->addTerrain(2114 ,2 , "wadi");
            $this->terrain->addTerrain(2214 ,3 , "wadi");
            $this->terrain->addTerrain(2214 ,2 , "wadi");
            $this->terrain->addTerrain(2315 ,3 , "wadi");
            $this->terrain->addTerrain(2316 ,4 , "wadi");
            $this->terrain->addTerrain(2316 ,3 , "wadi");
            $this->terrain->addTerrain(2316 ,2 , "wadi");
            $this->terrain->addTerrain(2416 ,3 , "wadi");
            $this->terrain->addTerrain(2417 ,4 , "wadi");
            $this->terrain->addTerrain(2417 ,3 , "wadi");
            $this->terrain->addTerrain(2417 ,2 , "wadi");
            $this->terrain->addTerrain(2518 ,3 , "wadi");
            $this->terrain->addTerrain(2519 ,4 , "wadi");
            $this->terrain->addTerrain(2519 ,3 , "wadi");
            $this->terrain->addReinforceZone(1504,'A');
            $this->terrain->addReinforceZone(1604,'A');
            $this->terrain->addReinforceZone(1505,'A');
            $this->terrain->addReinforceZone(1506,'A');
            $this->terrain->addReinforceZone(1404,'A');
            $this->terrain->addReinforceZone(1405,'A');
            $this->terrain->addReinforceZone(1406,'A');
            $this->terrain->addReinforceZone(1407,'A');
            $this->terrain->addReinforceZone(1306,'A');
            $this->terrain->addReinforceZone(1307,'A');
            $this->terrain->addReinforceZone(1308,'A');
            $this->terrain->addReinforceZone(1207,'A');
            $this->terrain->addReinforceZone(1208,'A');
            $this->terrain->addReinforceZone(1209,'A');
            $this->terrain->addReinforceZone(1309,'A');
            $this->terrain->addReinforceZone(1109,'A');
            $this->terrain->addReinforceZone(1110,'A');
            $this->terrain->addReinforceZone(1111,'A');
            $this->terrain->addReinforceZone(1010,'A');
            $this->terrain->addReinforceZone(1009,'A');
            $this->terrain->addReinforceZone(910,'A');
            $this->terrain->addReinforceZone(1908,'B');
            $this->terrain->addReinforceZone(1909,'B');
            $this->terrain->addReinforceZone(1910,'B');
            $this->terrain->addReinforceZone(1911,'B');
            $this->terrain->addReinforceZone(1916,'B');
            $this->terrain->addReinforceZone(2015,'B');
            $this->terrain->addReinforceZone(2014,'B');
            $this->terrain->addReinforceZone(2013,'B');
            $this->terrain->addReinforceZone(2012,'B');
            $this->terrain->addReinforceZone(2011,'B');
            $this->terrain->addReinforceZone(2010,'B');
            $this->terrain->addReinforceZone(2009,'B');
            $this->terrain->addReinforceZone(2008,'B');
            $this->terrain->addReinforceZone(2007,'B');
            $this->terrain->addReinforceZone(2107,'B');
            $this->terrain->addReinforceZone(2109,'B');
            $this->terrain->addReinforceZone(2110,'B');
            $this->terrain->addReinforceZone(2111,'B');
            $this->terrain->addReinforceZone(2113,'B');
            $this->terrain->addReinforceZone(2114,'B');
            $this->terrain->addReinforceZone(2115,'B');
            $this->terrain->addReinforceZone(2214,'B');
            $this->terrain->addReinforceZone(2213,'B');
            $this->terrain->addReinforceZone(2212,'B');
            $this->terrain->addReinforceZone(2211,'B');
            $this->terrain->addReinforceZone(2210,'B');
            $this->terrain->addReinforceZone(2209,'B');
            $this->terrain->addReinforceZone(2208,'B');
            $this->terrain->addReinforceZone(2307,'B');
            $this->terrain->addReinforceZone(2308,'B');
            $this->terrain->addReinforceZone(2309,'B');
            $this->terrain->addReinforceZone(2310,'B');
            $this->terrain->addReinforceZone(2311,'B');
            $this->terrain->addReinforceZone(2312,'B');
            $this->terrain->addReinforceZone(2313,'B');
            $this->terrain->addReinforceZone(2314,'B');
            $this->terrain->addReinforceZone(2413,'B');
            $this->terrain->addReinforceZone(2412,'B');
            $this->terrain->addReinforceZone(2411,'B');
            $this->terrain->addReinforceZone(2410,'B');
            $this->terrain->addReinforceZone(2409,'B');
            $this->terrain->addReinforceZone(2408,'B');
            $this->terrain->addReinforceZone(2407,'B');
            $this->terrain->addReinforceZone(2508,'B');
            $this->terrain->addReinforceZone(2509,'B');
            $this->terrain->addReinforceZone(2510,'B');
            $this->terrain->addReinforceZone(2511,'B');
            $this->terrain->addReinforceZone(2512,'B');
            $this->terrain->addReinforceZone(2513,'B');
            $this->terrain->addReinforceZone(2112,'B');

            $this->roadHex = $specialHexA;
            $specialHexes = [];
            foreach ($specialHexA as $specialHexId) {
                $specialHexes[$specialHexId] = BRITISH_FORCE;
            }
            foreach ($specialHexB as $specialHexId) {
                $specialHexes[$specialHexId] = BELUCHI_FORCE;
            }
            $this->mapData->setSpecialHexes($specialHexes);


            // end terrain data ----------------------------------------

        }
    }
}