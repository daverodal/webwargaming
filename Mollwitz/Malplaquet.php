<?php
set_include_path(__DIR__ . "/Malplaquet" . PATH_SEPARATOR . get_include_path());

/* comment */
require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name[1] = "French";
$force_name[2] = "Anglo Allied";
define("FRENCH_FORCE", 1);
define("ANGLO_FORCE", 2);
$phase_name = array();
$phase_name[1] = "French Move";
$phase_name[2] = "French Combat";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "Anglo Allied Move";
$phase_name[5] = "Anglo Allied Combat";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "French Deploy";
$phase_name[9] = "Prussian Mech";
$phase_name[10] = "Prussian Replacement";
$phase_name[11] = "Russian Mech";
$phase_name[12] = "Russian Replacement";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "Anglo Allied deploy phase";


require_once "combatRules.php";
require_once "crt.php";
require_once "force.php";
require_once "gameRules.php";
require_once "hexagon.php";
require_once "hexpart.php";
require_once "los.php";
require_once "mapgrid.php";
require_once "moveRules.php";
require_once "prompt.php";
require_once "display.php";
require_once "terrain.php";
require_once "victory.php";

// battleforallenriver.js

// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class Malplaquet extends Battle
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
    public $angloSpecialHexes;
    public $frenchSpecialHexes;


    public $players;

    static function getHeader($name, $playerData)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "commonHeader.php";
        @include_once "header.php";
        @include_once "MalplaquetHeader.php";

    }

    static function playAs($name, $wargame)
    {
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame)
    {
        @include_once "playMulti.php";
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
        $data->angloSpecialHexes = $this->angloSpecialHexes;
        $data->frenchSpecialHexes = $this->frenchSpecialHexes;
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }

    function poke($event, $id, $x, $y, $user, $click)
    {
        $playerId = $this->gameRules->attackingForceId;
        if ($this->players[$this->gameRules->attackingForceId] != $user) {
            return false;
        }

        switch ($event) {
            case SELECT_MAP_EVENT:
                $mapGrid = new MapGrid($this->mapViewer[$playerId]);
                $mapGrid = new MapGrid($this->mapViewer[$playerId]);
                $mapGrid->setPixels($x, $y);

                $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(), $click);
                break;

            case SELECT_COUNTER_EVENT:
                /* fall through */
            case SELECT_SHIFT_COUNTER_EVENT:

                $ret = $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id), $click);
                return $ret;
                break;


            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0, $click);
                break;

            case KEYPRESS_EVENT:
                $this->gameRules->processEvent(KEYPRESS_EVENT, $id, null, $click);
                break;


        }
        return true;
    }

    public function init()
    {

        $artRange = 3;

        for ($i = 0; $i < 16; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FreInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
        }
        for ($i = 0; $i < 12; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FreCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'cavalry');
        }
        for ($i = 0; $i < 4; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FreArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "French", false, 'artillery');
        }


        for ($i = 0; $i < 8; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngInfBadge.png", 8, 8, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
        }
        for ($i = 0; $i < 4; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
        }
        for ($i = 0; $i < 12; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
        }
         for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "AngloAllied", false, 'artillery');
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->angloSpecialHexes = $data->angloSpecialHexes;
            $this->frenchSpecialHexes = $data->frenchSpecialHexes;
            $this->game = $data->game;
            $this->genTerrain = false;
            $this->victory = new Victory("Mollwitz/Malplaquet/malplaquetVictoryCore.php", $data);
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
            $this->victory = new Victory("Mollwitz/Malplaquet/malplaquetVictoryCore.php");

            $this->mapData->setData(26, 18, "js/Malplaquet.jpg");
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
                $this->mapViewer[$player]->setData(48.70000000000001 , 77.55257490889649, // originX, originY
                    25.850858302965495, 25.850858302965495, // top hexagon height, bottom hexagon height
                    14.925, 29.85// hexagon edge width, hexagon center width
                );
            }

            // game data
            $this->gameRules->setMaxTurn(14);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->attackingForceId); /* so object oriented */


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
            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
            $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("redoubt", "redoubt", "d", 0, 2, 0, false);
            $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);


            for ($col = 100; $col <= 2500; $col += 100) {
                for ($row = 1; $row <= 18; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
            $specialHexA = [];
            $specialHexB = [];
            $this->terrain->addTerrain(514 ,1 , "town");
            $this->terrain->addReinforceZone(514,'B');
            $this->terrain->addTerrain(416 ,1 , "town");
            $this->terrain->addReinforceZone(416,'B');
            $this->terrain->addTerrain(816 ,1 , "town");
            $this->terrain->addReinforceZone(816,'B');
            $this->terrain->addTerrain(1615 ,1 , "town");
            $this->terrain->addReinforceZone(1615,'B');
            $this->terrain->addTerrain(1106 ,4 , "river");
            $this->terrain->addTerrain(1106 ,4 , "forest");
            $this->terrain->addTerrain(1105 ,2 , "river");
            $this->terrain->addTerrain(1205 ,4 , "river");
            $this->terrain->addTerrain(1204 ,2 , "river");
            $this->terrain->addTerrain(1305 ,4 , "river");
            $this->terrain->addTerrain(1304 ,2 , "river");
            $this->terrain->addTerrain(1404 ,4 , "river");
            $this->terrain->addTerrain(1403 ,2 , "river");
            $this->terrain->addTerrain(1504 ,3 , "river");
            $this->terrain->addTerrain(1504 ,2 , "river");
            $this->terrain->addTerrain(1604 ,4 , "river");
            $this->terrain->addTerrain(1603 ,2 , "river");
            $this->terrain->addTerrain(1704 ,4 , "river");
            $this->terrain->addTerrain(1703 ,2 , "river");
            $this->terrain->addTerrain(1703 ,2 , "road");
            $this->terrain->addTerrain(1803 ,4 , "river");
            $this->terrain->addTerrain(1802 ,2 , "river");
            $this->terrain->addTerrain(1903 ,4 , "river");
            $this->terrain->addTerrain(1902 ,2 , "river");
            $this->terrain->addTerrain(2002 ,4 , "river");
            $this->terrain->addTerrain(2001 ,2 , "river");
            $this->terrain->addTerrain(2102 ,4 , "river");
            $this->terrain->addTerrain(2101 ,2 , "river");
            $this->terrain->addTerrain(2201 ,4 , "river");
            $this->terrain->addTerrain(1505 ,4 , "river");
            $this->terrain->addTerrain(1505 ,3 , "river");
            $this->terrain->addTerrain(1506 ,4 , "river");
            $this->terrain->addTerrain(1506 ,3 , "river");
            $this->terrain->addTerrain(1507 ,4 , "river");
            $this->terrain->addTerrain(1507 ,3 , "river");
            $this->terrain->addTerrain(1508 ,4 , "river");
            $this->terrain->addTerrain(1508 ,3 , "river");
            $this->terrain->addTerrain(1509 ,4 , "river");
            $this->terrain->addTerrain(1509 ,3 , "river");
            $this->terrain->addTerrain(1510 ,4 , "river");
            $this->terrain->addTerrain(1510 ,3 , "river");
            $this->terrain->addTerrain(1511 ,4 , "river");
            $this->terrain->addTerrain(1511 ,3 , "river");
            $this->terrain->addTerrain(1512 ,4 , "river");
            $this->terrain->addTerrain(1406 ,2 , "river");
            $this->terrain->addTerrain(1407 ,4 , "river");
            $this->terrain->addTerrain(1307 ,2 , "river");
            $this->terrain->addTerrain(1308 ,4 , "river");
            $this->terrain->addTerrain(1207 ,2 , "river");
            $this->terrain->addTerrain(1208 ,4 , "river");
            $this->terrain->addTerrain(1208 ,3 , "river");
            $this->terrain->addTerrain(1209 ,4 , "river");
            $this->terrain->addTerrain(111 ,2 , "river");
            $this->terrain->addTerrain(211 ,3 , "river");
            $this->terrain->addTerrain(211 ,2 , "river");
            $this->terrain->addTerrain(312 ,4 , "river");
            $this->terrain->addTerrain(311 ,2 , "river");
            $this->terrain->addTerrain(411 ,4 , "river");
            $this->terrain->addTerrain(410 ,2 , "river");
            $this->terrain->addTerrain(511 ,3 , "river");
            $this->terrain->addTerrain(511 ,2 , "river");
            $this->terrain->addTerrain(511 ,2 , "road");
            $this->terrain->addTerrain(611 ,4 , "river");
            $this->terrain->addTerrain(610 ,2 , "river");
            $this->terrain->addTerrain(711 ,3 , "river");
            $this->terrain->addTerrain(712 ,4 , "river");
            $this->terrain->addTerrain(712 ,3 , "river");
            $this->terrain->addTerrain(712 ,2 , "river");
            $this->terrain->addTerrain(812 ,3 , "river");
            $this->terrain->addTerrain(812 ,2 , "river");
            $this->terrain->addTerrain(913 ,3 , "river");
            $this->terrain->addTerrain(914 ,4 , "river");
            $this->terrain->addTerrain(1218 ,4 , "river");
            $this->terrain->addTerrain(1217 ,2 , "river");
            $this->terrain->addTerrain(1318 ,4 , "river");
            $this->terrain->addTerrain(1317 ,2 , "river");
            $this->terrain->addTerrain(1417 ,4 , "river");
            $this->terrain->addTerrain(1416 ,2 , "river");
            $this->terrain->addTerrain(1517 ,4 , "river");
            $this->terrain->addTerrain(1516 ,2 , "river");
            $this->terrain->addTerrain(1616 ,4 , "river");
            $this->terrain->addTerrain(1615 ,2 , "river");
            $this->terrain->addTerrain(1615 ,2 , "road");
            $this->terrain->addTerrain(1716 ,4 , "river");
            $this->terrain->addTerrain(1715 ,3 , "river");
            $this->terrain->addTerrain(1715 ,4 , "river");
            $this->terrain->addTerrain(1912 ,4 , "river");
            $this->terrain->addTerrain(1911 ,2 , "river");
            $this->terrain->addTerrain(2011 ,4 , "river");
            $this->terrain->addTerrain(2010 ,3 , "river");
            $this->terrain->addTerrain(2010 ,4 , "river");
            $this->terrain->addTerrain(2009 ,3 , "river");
            $this->terrain->addTerrain(2009 ,4 , "river");
            $this->terrain->addTerrain(2008 ,3 , "river");
            $this->terrain->addTerrain(1908 ,2 , "river");
            $this->terrain->addTerrain(1909 ,4 , "river");
            $this->terrain->addTerrain(1808 ,2 , "river");
            $this->terrain->addTerrain(2008 ,4 , "river");
            $this->terrain->addTerrain(2007 ,2 , "river");
            $this->terrain->addTerrain(2108 ,4 , "river");
            $this->terrain->addTerrain(2107 ,3 , "river");
            $this->terrain->addTerrain(2107 ,4 , "river");
            $this->terrain->addTerrain(2106 ,2 , "river");
            $this->terrain->addTerrain(2206 ,4 , "river");
            $this->terrain->addTerrain(2205 ,3 , "river");
            $this->terrain->addTerrain(2205 ,4 , "river");
            $this->terrain->addTerrain(2204 ,3 , "river");
            $this->terrain->addTerrain(2204 ,4 , "river");
            $this->terrain->addTerrain(2203 ,2 , "river");
            $this->terrain->addTerrain(2304 ,4 , "river");
            $this->terrain->addTerrain(2303 ,2 , "river");
            $this->terrain->addTerrain(2403 ,4 , "river");
            $this->terrain->addTerrain(2402 ,3 , "river");
            $this->terrain->addTerrain(2402 ,4 , "river");
            $this->terrain->addTerrain(2401 ,2 , "river");
            $this->terrain->addTerrain(2502 ,4 , "river");
            $this->terrain->addTerrain(2501 ,3 , "river");
            $this->terrain->addTerrain(107 ,1 , "forest");
            $this->terrain->addTerrain(107 ,2 , "forest");
            $this->terrain->addTerrain(108 ,1 , "forest");
            $this->terrain->addTerrain(108 ,2 , "forest");
            $this->terrain->addTerrain(109 ,1 , "forest");
            $this->terrain->addTerrain(109 ,2 , "forest");
            $this->terrain->addTerrain(110 ,1 , "forest");
            $this->terrain->addTerrain(110 ,2 , "forest");
            $this->terrain->addTerrain(111 ,1 , "forest");
            $this->terrain->addTerrain(207 ,4 , "forest");
            $this->terrain->addTerrain(207 ,3 , "forest");
            $this->terrain->addTerrain(208 ,4 , "forest");
            $this->terrain->addTerrain(208 ,3 , "forest");
            $this->terrain->addTerrain(209 ,4 , "forest");
            $this->terrain->addTerrain(209 ,3 , "forest");
            $this->terrain->addTerrain(210 ,4 , "forest");
            $this->terrain->addTerrain(210 ,3 , "forest");
            $this->terrain->addTerrain(211 ,4 , "forest");
            $this->terrain->addTerrain(207 ,1 , "forest");
            $this->terrain->addTerrain(207 ,2 , "forest");
            $this->terrain->addTerrain(208 ,1 , "forest");
            $this->terrain->addTerrain(208 ,2 , "forest");
            $this->terrain->addTerrain(209 ,1 , "forest");
            $this->terrain->addTerrain(209 ,2 , "forest");
            $this->terrain->addTerrain(210 ,1 , "forest");
            $this->terrain->addTerrain(210 ,2 , "forest");
            $this->terrain->addTerrain(211 ,1 , "forest");
            $this->terrain->addTerrain(307 ,3 , "forest");
            $this->terrain->addTerrain(308 ,4 , "forest");
            $this->terrain->addTerrain(308 ,3 , "forest");
            $this->terrain->addTerrain(309 ,4 , "forest");
            $this->terrain->addTerrain(309 ,3 , "forest");
            $this->terrain->addTerrain(310 ,4 , "forest");
            $this->terrain->addTerrain(310 ,3 , "forest");
            $this->terrain->addTerrain(311 ,4 , "forest");
            $this->terrain->addTerrain(311 ,3 , "forest");
            $this->terrain->addTerrain(307 ,1 , "forest");
            $this->terrain->addTerrain(307 ,2 , "forest");
            $this->terrain->addTerrain(308 ,1 , "forest");
            $this->terrain->addTerrain(308 ,2 , "forest");
            $this->terrain->addTerrain(309 ,1 , "forest");
            $this->terrain->addTerrain(309 ,2 , "forest");
            $this->terrain->addTerrain(310 ,1 , "forest");
            $this->terrain->addTerrain(310 ,2 , "forest");
            $this->terrain->addTerrain(311 ,1 , "forest");
            $this->terrain->addTerrain(406 ,3 , "forest");
            $this->terrain->addTerrain(407 ,4 , "forest");
            $this->terrain->addTerrain(407 ,3 , "forest");
            $this->terrain->addTerrain(408 ,4 , "forest");
            $this->terrain->addTerrain(408 ,3 , "forest");
            $this->terrain->addTerrain(409 ,4 , "forest");
            $this->terrain->addTerrain(409 ,3 , "forest");
            $this->terrain->addTerrain(410 ,4 , "forest");
            $this->terrain->addTerrain(410 ,3 , "forest");
            $this->terrain->addTerrain(406 ,1 , "forest");
            $this->terrain->addTerrain(406 ,2 , "forest");
            $this->terrain->addTerrain(407 ,1 , "forest");
            $this->terrain->addTerrain(407 ,2 , "forest");
            $this->terrain->addTerrain(408 ,1 , "forest");
            $this->terrain->addTerrain(408 ,2 , "forest");
            $this->terrain->addTerrain(409 ,1 , "forest");
            $this->terrain->addTerrain(409 ,2 , "forest");
            $this->terrain->addTerrain(410 ,1 , "forest");
            $this->terrain->addTerrain(506 ,3 , "forest");
            $this->terrain->addTerrain(507 ,4 , "forest");
            $this->terrain->addTerrain(507 ,3 , "forest");
            $this->terrain->addTerrain(508 ,4 , "forest");
            $this->terrain->addTerrain(508 ,3 , "forest");
            $this->terrain->addTerrain(509 ,4 , "forest");
            $this->terrain->addTerrain(509 ,3 , "forest");
            $this->terrain->addTerrain(510 ,4 , "forest");
            $this->terrain->addTerrain(510 ,3 , "forest");
            $this->terrain->addTerrain(511 ,4 , "forest");
            $this->terrain->addTerrain(506 ,1 , "forest");
            $this->terrain->addTerrain(506 ,1 , "road");
            $this->terrain->addTerrain(506 ,2 , "forest");
            $this->terrain->addTerrain(506 ,2 , "road");
            $this->terrain->addTerrain(507 ,1 , "forest");
            $this->terrain->addTerrain(507 ,1 , "road");
            $this->terrain->addTerrain(507 ,2 , "forest");
            $this->terrain->addTerrain(507 ,2 , "road");
            $this->terrain->addTerrain(508 ,1 , "forest");
            $this->terrain->addTerrain(508 ,1 , "road");
            $this->terrain->addTerrain(508 ,2 , "forest");
            $this->terrain->addTerrain(508 ,2 , "road");
            $this->terrain->addTerrain(509 ,1 , "forest");
            $this->terrain->addTerrain(509 ,1 , "road");
            $this->terrain->addTerrain(509 ,2 , "forest");
            $this->terrain->addTerrain(509 ,2 , "road");
            $this->terrain->addTerrain(510 ,1 , "forest");
            $this->terrain->addTerrain(510 ,1 , "road");
            $this->terrain->addTerrain(510 ,2 , "forest");
            $this->terrain->addTerrain(510 ,2 , "road");
            $this->terrain->addTerrain(511 ,1 , "forest");
            $this->terrain->addTerrain(511 ,1 , "road");
            $this->terrain->addTerrain(605 ,3 , "forest");
            $this->terrain->addTerrain(606 ,4 , "forest");
            $this->terrain->addTerrain(606 ,3 , "forest");
            $this->terrain->addTerrain(607 ,4 , "forest");
            $this->terrain->addTerrain(607 ,3 , "forest");
            $this->terrain->addTerrain(608 ,4 , "forest");
            $this->terrain->addTerrain(608 ,3 , "forest");
            $this->terrain->addTerrain(609 ,4 , "forest");
            $this->terrain->addTerrain(609 ,3 , "forest");
            $this->terrain->addTerrain(610 ,4 , "forest");
            $this->terrain->addTerrain(610 ,3 , "forest");
            $this->terrain->addTerrain(605 ,1 , "forest");
            $this->terrain->addTerrain(605 ,2 , "forest");
            $this->terrain->addTerrain(606 ,1 , "forest");
            $this->terrain->addTerrain(606 ,2 , "forest");
            $this->terrain->addTerrain(607 ,1 , "forest");
            $this->terrain->addTerrain(607 ,2 , "forest");
            $this->terrain->addTerrain(608 ,1 , "forest");
            $this->terrain->addTerrain(608 ,2 , "forest");
            $this->terrain->addTerrain(609 ,1 , "forest");
            $this->terrain->addTerrain(609 ,2 , "forest");
            $this->terrain->addTerrain(610 ,1 , "forest");
            $this->terrain->addTerrain(705 ,3 , "forest");
            $this->terrain->addTerrain(706 ,4 , "forest");
            $this->terrain->addTerrain(706 ,3 , "forest");
            $this->terrain->addTerrain(707 ,4 , "forest");
            $this->terrain->addTerrain(707 ,3 , "forest");
            $this->terrain->addTerrain(708 ,4 , "forest");
            $this->terrain->addTerrain(708 ,3 , "forest");
            $this->terrain->addTerrain(709 ,4 , "forest");
            $this->terrain->addTerrain(709 ,3 , "forest");
            $this->terrain->addTerrain(710 ,4 , "forest");
            $this->terrain->addTerrain(710 ,3 , "forest");
            $this->terrain->addTerrain(711 ,4 , "forest");
            $this->terrain->addTerrain(705 ,1 , "forest");
            $this->terrain->addTerrain(705 ,2 , "forest");
            $this->terrain->addTerrain(706 ,1 , "forest");
            $this->terrain->addTerrain(706 ,2 , "forest");
            $this->terrain->addTerrain(707 ,1 , "forest");
            $this->terrain->addTerrain(707 ,2 , "forest");
            $this->terrain->addTerrain(708 ,1 , "forest");
            $this->terrain->addTerrain(708 ,2 , "forest");
            $this->terrain->addTerrain(709 ,1 , "forest");
            $this->terrain->addTerrain(709 ,2 , "forest");
            $this->terrain->addTerrain(710 ,1 , "forest");
            $this->terrain->addTerrain(710 ,2 , "forest");
            $this->terrain->addTerrain(711 ,1 , "forest");
            $this->terrain->addTerrain(711 ,2 , "forest");
            $this->terrain->addTerrain(712 ,1 , "forest");
            $this->terrain->addTerrain(804 ,3 , "forest");
            $this->terrain->addTerrain(805 ,4 , "forest");
            $this->terrain->addTerrain(805 ,3 , "forest");
            $this->terrain->addTerrain(806 ,4 , "forest");
            $this->terrain->addTerrain(806 ,3 , "forest");
            $this->terrain->addTerrain(807 ,4 , "forest");
            $this->terrain->addTerrain(807 ,3 , "forest");
            $this->terrain->addTerrain(808 ,4 , "forest");
            $this->terrain->addTerrain(808 ,3 , "forest");
            $this->terrain->addTerrain(809 ,4 , "forest");
            $this->terrain->addTerrain(809 ,3 , "forest");
            $this->terrain->addTerrain(810 ,4 , "forest");
            $this->terrain->addTerrain(810 ,3 , "forest");
            $this->terrain->addTerrain(811 ,4 , "forest");
            $this->terrain->addTerrain(811 ,3 , "forest");
            $this->terrain->addTerrain(812 ,4 , "forest");
            $this->terrain->addTerrain(804 ,1 , "forest");
            $this->terrain->addTerrain(804 ,2 , "forest");
            $this->terrain->addTerrain(805 ,1 , "forest");
            $this->terrain->addTerrain(805 ,2 , "forest");
            $this->terrain->addTerrain(806 ,1 , "forest");
            $this->terrain->addTerrain(806 ,2 , "forest");
            $this->terrain->addTerrain(807 ,1 , "forest");
            $this->terrain->addTerrain(807 ,2 , "forest");
            $this->terrain->addTerrain(808 ,1 , "forest");
            $this->terrain->addTerrain(808 ,2 , "forest");
            $this->terrain->addTerrain(809 ,1 , "forest");
            $this->terrain->addTerrain(809 ,2 , "forest");
            $this->terrain->addTerrain(810 ,1 , "forest");
            $this->terrain->addTerrain(810 ,2 , "forest");
            $this->terrain->addTerrain(811 ,1 , "forest");
            $this->terrain->addTerrain(811 ,2 , "forest");
            $this->terrain->addTerrain(812 ,1 , "forest");
            $this->terrain->addTerrain(905 ,4 , "forest");
            $this->terrain->addTerrain(905 ,3 , "forest");
            $this->terrain->addTerrain(906 ,4 , "forest");
            $this->terrain->addTerrain(906 ,3 , "forest");
            $this->terrain->addTerrain(907 ,4 , "forest");
            $this->terrain->addTerrain(907 ,3 , "forest");
            $this->terrain->addTerrain(908 ,4 , "forest");
            $this->terrain->addTerrain(908 ,3 , "forest");
            $this->terrain->addTerrain(909 ,4 , "forest");
            $this->terrain->addTerrain(909 ,3 , "forest");
            $this->terrain->addTerrain(910 ,4 , "forest");
            $this->terrain->addTerrain(910 ,3 , "forest");
            $this->terrain->addTerrain(911 ,4 , "forest");
            $this->terrain->addTerrain(911 ,3 , "forest");
            $this->terrain->addTerrain(912 ,4 , "forest");
            $this->terrain->addTerrain(912 ,3 , "forest");
            $this->terrain->addTerrain(913 ,4 , "forest");
            $this->terrain->addTerrain(905 ,1 , "forest");
            $this->terrain->addTerrain(905 ,2 , "forest");
            $this->terrain->addTerrain(906 ,1 , "forest");
            $this->terrain->addTerrain(906 ,2 , "forest");
            $this->terrain->addTerrain(907 ,1 , "forest");
            $this->terrain->addTerrain(907 ,2 , "forest");
            $this->terrain->addTerrain(908 ,1 , "forest");
            $this->terrain->addTerrain(908 ,2 , "forest");
            $this->terrain->addTerrain(909 ,1 , "forest");
            $this->terrain->addTerrain(909 ,2 , "forest");
            $this->terrain->addTerrain(910 ,1 , "forest");
            $this->terrain->addTerrain(910 ,2 , "forest");
            $this->terrain->addTerrain(911 ,1 , "forest");
            $this->terrain->addTerrain(911 ,2 , "forest");
            $this->terrain->addTerrain(912 ,1 , "forest");
            $this->terrain->addTerrain(912 ,2 , "forest");
            $this->terrain->addTerrain(913 ,1 , "forest");
            $this->terrain->addTerrain(1005 ,4 , "forest");
            $this->terrain->addTerrain(1005 ,3 , "forest");
            $this->terrain->addTerrain(1006 ,4 , "forest");
            $this->terrain->addTerrain(1006 ,3 , "forest");
            $this->terrain->addTerrain(1007 ,4 , "forest");
            $this->terrain->addTerrain(1007 ,3 , "forest");
            $this->terrain->addTerrain(1008 ,4 , "forest");
            $this->terrain->addTerrain(1008 ,3 , "forest");
            $this->terrain->addTerrain(1009 ,4 , "forest");
            $this->terrain->addTerrain(1009 ,3 , "forest");
            $this->terrain->addTerrain(1010 ,4 , "forest");
            $this->terrain->addTerrain(1010 ,3 , "forest");
            $this->terrain->addTerrain(1011 ,4 , "forest");
            $this->terrain->addTerrain(1011 ,3 , "forest");
            $this->terrain->addTerrain(1012 ,4 , "forest");
            $this->terrain->addTerrain(1012 ,3 , "forest");
            $this->terrain->addTerrain(1013 ,4 , "forest");
            $this->terrain->addTerrain(1005 ,1 , "forest");
            $this->terrain->addTerrain(1005 ,2 , "forest");
            $this->terrain->addTerrain(1006 ,1 , "forest");
            $this->terrain->addTerrain(1006 ,2 , "forest");
            $this->terrain->addTerrain(1007 ,1 , "forest");
            $this->terrain->addTerrain(1007 ,2 , "forest");
            $this->terrain->addTerrain(1008 ,1 , "forest");
            $this->terrain->addTerrain(1008 ,2 , "forest");
            $this->terrain->addTerrain(1009 ,1 , "forest");
            $this->terrain->addTerrain(1009 ,2 , "forest");
            $this->terrain->addTerrain(1010 ,1 , "forest");
            $this->terrain->addTerrain(1010 ,2 , "forest");
            $this->terrain->addTerrain(1011 ,1 , "forest");
            $this->terrain->addTerrain(1011 ,2 , "forest");
            $this->terrain->addTerrain(1012 ,1 , "forest");
            $this->terrain->addTerrain(1012 ,2 , "forest");
            $this->terrain->addTerrain(1013 ,1 , "forest");
            $this->terrain->addTerrain(1106 ,3 , "forest");
            $this->terrain->addTerrain(1107 ,4 , "forest");
            $this->terrain->addTerrain(1107 ,3 , "forest");
            $this->terrain->addTerrain(1108 ,4 , "forest");
            $this->terrain->addTerrain(1108 ,3 , "forest");
            $this->terrain->addTerrain(1109 ,4 , "forest");
            $this->terrain->addTerrain(1109 ,3 , "forest");
            $this->terrain->addTerrain(1110 ,4 , "forest");
            $this->terrain->addTerrain(1110 ,3 , "forest");
            $this->terrain->addTerrain(1111 ,4 , "forest");
            $this->terrain->addTerrain(1112 ,4 , "forest");
            $this->terrain->addTerrain(1112 ,3 , "forest");
            $this->terrain->addTerrain(1113 ,4 , "forest");
            $this->terrain->addTerrain(1113 ,3 , "forest");
            $this->terrain->addTerrain(1106 ,1 , "forest");
            $this->terrain->addTerrain(1106 ,2 , "forest");
            $this->terrain->addTerrain(1107 ,1 , "forest");
            $this->terrain->addTerrain(1107 ,2 , "forest");
            $this->terrain->addTerrain(1108 ,1 , "forest");
            $this->terrain->addTerrain(1108 ,2 , "forest");
            $this->terrain->addTerrain(1109 ,1 , "forest");
            $this->terrain->addTerrain(1109 ,2 , "forest");
            $this->terrain->addTerrain(1110 ,1 , "forest");
            $this->terrain->addReinforceZone(1110,'B');
            $this->terrain->addTerrain(1110 ,1 , "redoubt");
            $this->terrain->addTerrain(1110 ,2 , "forest");
            $this->terrain->addTerrain(1111 ,1 , "forest");
            $this->terrain->addReinforceZone(1111,'B');
            $this->terrain->addTerrain(1111 ,2 , "forest");
            $this->terrain->addTerrain(1112 ,1 , "forest");
            $this->terrain->addReinforceZone(1112,'B');
            $this->terrain->addTerrain(1112 ,2 , "forest");
            $this->terrain->addTerrain(1113 ,1 , "forest");
            $this->terrain->addReinforceZone(1113,'B');
            $this->terrain->addTerrain(1210 ,4 , "forest");
            $this->terrain->addTerrain(1210 ,3 , "forest");
            $this->terrain->addTerrain(1211 ,4 , "forest");
            $this->terrain->addTerrain(1211 ,3 , "forest");
            $this->terrain->addTerrain(1212 ,4 , "forest");
            $this->terrain->addTerrain(1212 ,3 , "forest");
            $this->terrain->addTerrain(1210 ,1 , "forest");
            $this->terrain->addReinforceZone(1210,'B');
            $this->terrain->addTerrain(1210 ,1 , "redoubt");
            $this->terrain->addTerrain(1210 ,2 , "forest");
            $this->terrain->addTerrain(1211 ,1 , "forest");
            $this->terrain->addReinforceZone(1211,'B');
            $this->terrain->addTerrain(1211 ,1 , "redoubt");
            $this->terrain->addTerrain(1211 ,2 , "forest");
            $this->terrain->addTerrain(1212 ,1 , "forest");
            $this->terrain->addReinforceZone(1212,'B');
            $this->terrain->addTerrain(1212 ,1 , "redoubt");
            $this->terrain->addTerrain(1311 ,4 , "forest");
            $this->terrain->addTerrain(1311 ,3 , "forest");
            $this->terrain->addTerrain(1311 ,1 , "forest");
            $this->terrain->addTerrain(1311 ,1 , "redoubt");
            $this->terrain->addReinforceZone(1311,'B');
            $this->terrain->addTerrain(1913 ,1 , "forest");
            $this->terrain->addReinforceZone(1913,'B');
            $this->terrain->addTerrain(1913 ,1 , "redoubt");
            $this->terrain->addTerrain(1913 ,2 , "forest");
            $this->terrain->addTerrain(1914 ,1 , "forest");
            $this->terrain->addReinforceZone(1914,'B');
            $this->terrain->addTerrain(1914 ,1 , "redoubt");
            $this->terrain->addTerrain(1914 ,2 , "forest");
            $this->terrain->addTerrain(1915 ,1 , "forest");
            $this->terrain->addReinforceZone(1915,'B');
            $this->terrain->addTerrain(1915 ,2 , "forest");
            $this->terrain->addTerrain(1916 ,1 , "forest");
            $this->terrain->addReinforceZone(1916,'B');
            $this->terrain->addTerrain(1916 ,2 , "forest");
            $this->terrain->addTerrain(1917 ,1 , "forest");
            $this->terrain->addTerrain(1917 ,1 , "road");
            $this->terrain->addReinforceZone(1917,'B');
            $this->terrain->addTerrain(2012 ,3 , "forest");
            $this->terrain->addTerrain(2013 ,4 , "forest");
            $this->terrain->addTerrain(2013 ,3 , "forest");
            $this->terrain->addTerrain(2014 ,4 , "forest");
            $this->terrain->addTerrain(2014 ,3 , "forest");
            $this->terrain->addTerrain(2015 ,4 , "forest");
            $this->terrain->addTerrain(2015 ,3 , "forest");
            $this->terrain->addTerrain(2016 ,4 , "forest");
            $this->terrain->addTerrain(2016 ,3 , "forest");
            $this->terrain->addTerrain(2016 ,3 , "road");
            $this->terrain->addTerrain(2017 ,4 , "forest");
            $this->terrain->addTerrain(2012 ,1 , "forest");
            $this->terrain->addReinforceZone(2012,'B');
            $this->terrain->addTerrain(2012 ,1 , "redoubt");
            $this->terrain->addTerrain(2012 ,2 , "forest");
            $this->terrain->addTerrain(2013 ,1 , "forest");
            $this->terrain->addReinforceZone(2013,'B');
            $this->terrain->addTerrain(2013 ,2 , "forest");
            $this->terrain->addTerrain(2014 ,1 , "forest");
            $this->terrain->addReinforceZone(2014,'B');
            $this->terrain->addTerrain(2014 ,2 , "forest");
            $this->terrain->addTerrain(2015 ,1 , "forest");
            $this->terrain->addReinforceZone(2015,'B');
            $this->terrain->addTerrain(2015 ,2 , "forest");
            $this->terrain->addTerrain(2016 ,1 , "forest");
            $this->terrain->addTerrain(2016 ,1 , "road");
            $this->terrain->addReinforceZone(2016,'B');
            $this->terrain->addTerrain(2016 ,2 , "forest");
            $this->terrain->addTerrain(2017 ,1 , "forest");
            $this->terrain->addReinforceZone(2017,'B');
            $this->terrain->addTerrain(2112 ,3 , "forest");
            $this->terrain->addTerrain(2113 ,4 , "forest");
            $this->terrain->addTerrain(2113 ,3 , "forest");
            $this->terrain->addTerrain(2114 ,4 , "forest");
            $this->terrain->addTerrain(2114 ,3 , "forest");
            $this->terrain->addTerrain(2115 ,4 , "forest");
            $this->terrain->addTerrain(2115 ,3 , "forest");
            $this->terrain->addTerrain(2116 ,4 , "forest");
            $this->terrain->addTerrain(2116 ,3 , "forest");
            $this->terrain->addTerrain(2116 ,3 , "road");
            $this->terrain->addTerrain(2117 ,4 , "forest");
            $this->terrain->addTerrain(2117 ,3 , "forest");
            $this->terrain->addTerrain(2112 ,1 , "forest");
            $this->terrain->addReinforceZone(2112,'B');
            $this->terrain->addTerrain(2112 ,1 , "redoubt");
            $this->terrain->addTerrain(2112 ,2 , "forest");
            $this->terrain->addTerrain(2113 ,1 , "forest");
            $this->terrain->addReinforceZone(2113,'B');
            $this->terrain->addTerrain(2113 ,2 , "forest");
            $this->terrain->addTerrain(2114 ,1 , "forest");
            $this->terrain->addReinforceZone(2114,'B');
            $this->terrain->addTerrain(2114 ,2 , "forest");
            $this->terrain->addTerrain(2115 ,1 , "forest");
            $this->terrain->addReinforceZone(2115,'B');
            $this->terrain->addTerrain(2115 ,2 , "forest");
            $this->terrain->addTerrain(2116 ,1 , "forest");
            $this->terrain->addTerrain(2116 ,1 , "road");
            $this->terrain->addReinforceZone(2116,'B');
            $this->terrain->addTerrain(2116 ,2 , "forest");
            $this->terrain->addTerrain(2117 ,1 , "forest");
            $this->terrain->addReinforceZone(2117,'B');
            $this->terrain->addTerrain(2211 ,3 , "forest");
            $this->terrain->addTerrain(2212 ,4 , "forest");
            $this->terrain->addTerrain(2212 ,3 , "forest");
            $this->terrain->addTerrain(2213 ,4 , "forest");
            $this->terrain->addTerrain(2213 ,3 , "forest");
            $this->terrain->addTerrain(2214 ,4 , "forest");
            $this->terrain->addTerrain(2214 ,3 , "forest");
            $this->terrain->addTerrain(2215 ,4 , "forest");
            $this->terrain->addTerrain(2215 ,3 , "forest");
            $this->terrain->addTerrain(2215 ,3 , "road");
            $this->terrain->addTerrain(2216 ,4 , "forest");
            $this->terrain->addTerrain(2216 ,3 , "forest");
            $this->terrain->addTerrain(2217 ,4 , "forest");
            $this->terrain->addTerrain(2209 ,1 , "forest");
            $this->terrain->addTerrain(2209 ,2 , "forest");
            $this->terrain->addTerrain(2210 ,1 , "forest");
            $this->terrain->addTerrain(2210 ,2 , "forest");
            $this->terrain->addTerrain(2210 ,2 , "redoubt");
            $this->terrain->addTerrain(2211 ,1 , "forest");
            $this->terrain->addReinforceZone(2211,'B');
            $this->terrain->addTerrain(2211 ,1 , "redoubt");
            $this->terrain->addTerrain(2211 ,2 , "forest");
            $this->terrain->addTerrain(2212 ,1 , "forest");
            $this->terrain->addReinforceZone(2212,'B');
            $this->terrain->addTerrain(2212 ,2 , "forest");
            $this->terrain->addTerrain(2213 ,1 , "forest");
            $this->terrain->addReinforceZone(2213,'B');
            $this->terrain->addTerrain(2213 ,2 , "forest");
            $this->terrain->addTerrain(2214 ,1 , "forest");
            $this->terrain->addReinforceZone(2214,'B');
            $this->terrain->addTerrain(2214 ,2 , "forest");
            $this->terrain->addTerrain(2215 ,1 , "forest");
            $this->terrain->addTerrain(2215 ,1 , "road");
            $this->terrain->addReinforceZone(2215,'B');
            $this->terrain->addTerrain(2215 ,2 , "forest");
            $this->terrain->addTerrain(2216 ,1 , "forest");
            $this->terrain->addReinforceZone(2216,'B');
            $this->terrain->addTerrain(2216 ,2 , "forest");
            $this->terrain->addTerrain(2217 ,1 , "forest");
            $this->terrain->addReinforceZone(2217,'B');
            $this->terrain->addTerrain(2309 ,3 , "forest");
            $this->terrain->addTerrain(2310 ,4 , "forest");
            $this->terrain->addTerrain(2310 ,3 , "forest");
            $this->terrain->addTerrain(2311 ,4 , "forest");
            $this->terrain->addTerrain(2311 ,3 , "forest");
            $this->terrain->addTerrain(2312 ,4 , "forest");
            $this->terrain->addTerrain(2312 ,3 , "forest");
            $this->terrain->addTerrain(2313 ,4 , "forest");
            $this->terrain->addTerrain(2313 ,3 , "forest");
            $this->terrain->addTerrain(2314 ,4 , "forest");
            $this->terrain->addTerrain(2314 ,3 , "forest");
            $this->terrain->addTerrain(2315 ,4 , "forest");
            $this->terrain->addTerrain(2315 ,3 , "forest");
            $this->terrain->addTerrain(2315 ,3 , "road");
            $this->terrain->addTerrain(2316 ,4 , "forest");
            $this->terrain->addTerrain(2316 ,3 , "forest");
            $this->terrain->addTerrain(2317 ,4 , "forest");
            $this->terrain->addTerrain(2317 ,3 , "forest");
            $this->terrain->addTerrain(2308 ,1 , "forest");
            $this->terrain->addTerrain(2308 ,2 , "forest");
            $this->terrain->addTerrain(2309 ,1 , "forest");
            $this->terrain->addTerrain(2309 ,1 , "road");
            $this->terrain->addTerrain(2309 ,2 , "forest");
            $this->terrain->addTerrain(2309 ,2 , "road");
            $this->terrain->addTerrain(2310 ,1 , "forest");
            $this->terrain->addTerrain(2310 ,1 , "road");
            $this->terrain->addTerrain(2310 ,2 , "forest");
            $this->terrain->addTerrain(2310 ,2 , "road");
            $this->terrain->addTerrain(2311 ,1 , "forest");
            $this->terrain->addTerrain(2311 ,1 , "road");
            $this->terrain->addTerrain(2311 ,2 , "forest");
            $this->terrain->addTerrain(2311 ,2 , "road");
            $this->terrain->addTerrain(2312 ,1 , "forest");
            $this->terrain->addTerrain(2312 ,1 , "road");
            $this->terrain->addReinforceZone(2312,'B');
            $this->terrain->addTerrain(2312 ,2 , "forest");
            $this->terrain->addTerrain(2312 ,2 , "road");
            $this->terrain->addTerrain(2313 ,1 , "forest");
            $this->terrain->addTerrain(2313 ,1 , "road");
            $this->terrain->addReinforceZone(2313,'B');
            $this->terrain->addTerrain(2313 ,2 , "forest");
            $this->terrain->addTerrain(2313 ,2 , "road");
            $this->terrain->addTerrain(2314 ,1 , "forest");
            $this->terrain->addTerrain(2314 ,1 , "road");
            $this->terrain->addReinforceZone(2314,'B');
            $this->terrain->addTerrain(2314 ,2 , "forest");
            $this->terrain->addTerrain(2314 ,2 , "road");
            $this->terrain->addTerrain(2315 ,1 , "forest");
            $this->terrain->addTerrain(2315 ,1 , "road");
            $this->terrain->addReinforceZone(2315,'B');
            $this->terrain->addTerrain(2315 ,2 , "forest");
            $this->terrain->addTerrain(2316 ,1 , "forest");
            $this->terrain->addReinforceZone(2316,'B');
            $this->terrain->addTerrain(2316 ,2 , "forest");
            $this->terrain->addTerrain(2317 ,1 , "forest");
            $this->terrain->addReinforceZone(2317,'B');
            $this->terrain->addTerrain(2407 ,3 , "forest");
            $this->terrain->addTerrain(2408 ,4 , "forest");
            $this->terrain->addTerrain(2408 ,3 , "forest");
            $this->terrain->addTerrain(2408 ,3 , "road");
            $this->terrain->addTerrain(2409 ,4 , "forest");
            $this->terrain->addTerrain(2409 ,3 , "forest");
            $this->terrain->addTerrain(2410 ,4 , "forest");
            $this->terrain->addTerrain(2410 ,4 , "road");
            $this->terrain->addTerrain(2410 ,3 , "forest");
            $this->terrain->addTerrain(2411 ,4 , "forest");
            $this->terrain->addTerrain(2411 ,3 , "forest");
            $this->terrain->addTerrain(2412 ,4 , "forest");
            $this->terrain->addTerrain(2412 ,3 , "forest");
            $this->terrain->addTerrain(2413 ,4 , "forest");
            $this->terrain->addTerrain(2413 ,3 , "forest");
            $this->terrain->addTerrain(2414 ,4 , "forest");
            $this->terrain->addTerrain(2414 ,3 , "forest");
            $this->terrain->addTerrain(2415 ,4 , "forest");
            $this->terrain->addTerrain(2415 ,3 , "forest");
            $this->terrain->addTerrain(2416 ,4 , "forest");
            $this->terrain->addTerrain(2416 ,3 , "forest");
            $this->terrain->addTerrain(2417 ,4 , "forest");
            $this->terrain->addTerrain(2407 ,1 , "forest");
            $this->terrain->addTerrain(2407 ,2 , "forest");
            $this->terrain->addTerrain(2408 ,1 , "forest");
            $this->terrain->addTerrain(2408 ,1 , "road");
            $this->terrain->addTerrain(2408 ,2 , "forest");
            $this->terrain->addTerrain(2409 ,1 , "forest");
            $this->terrain->addTerrain(2409 ,2 , "forest");
            $this->terrain->addTerrain(2410 ,1 , "forest");
            $this->terrain->addTerrain(2410 ,1 , "road");
            $this->terrain->addTerrain(2410 ,2 , "forest");
            $this->terrain->addTerrain(2411 ,1 , "forest");
            $this->terrain->addTerrain(2411 ,2 , "forest");
            $this->terrain->addTerrain(2412 ,1 , "forest");
            $this->terrain->addTerrain(2412 ,2 , "forest");
            $this->terrain->addTerrain(2413 ,1 , "forest");
            $this->terrain->addTerrain(2413 ,2 , "forest");
            $this->terrain->addTerrain(2414 ,1 , "forest");
            $this->terrain->addTerrain(2414 ,2 , "forest");
            $this->terrain->addTerrain(2415 ,1 , "forest");
            $this->terrain->addTerrain(2415 ,2 , "forest");
            $this->terrain->addTerrain(2416 ,1 , "forest");
            $this->terrain->addTerrain(2416 ,2 , "forest");
            $this->terrain->addTerrain(2417 ,1 , "forest");
            $this->terrain->addTerrain(2508 ,4 , "forest");
            $this->terrain->addTerrain(2508 ,3 , "forest");
            $this->terrain->addTerrain(2508 ,3 , "road");
            $this->terrain->addTerrain(2509 ,4 , "forest");
            $this->terrain->addTerrain(2509 ,3 , "forest");
            $this->terrain->addTerrain(2510 ,4 , "forest");
            $this->terrain->addTerrain(2510 ,3 , "forest");
            $this->terrain->addTerrain(2517 ,4 , "forest");
            $this->terrain->addTerrain(2517 ,3 , "forest");
            $this->terrain->addTerrain(2508 ,1 , "forest");
            $this->terrain->addTerrain(2508 ,1 , "road");
            $this->terrain->addTerrain(2508 ,2 , "forest");
            $this->terrain->addTerrain(2509 ,1 , "forest");
            $this->terrain->addTerrain(2509 ,2 , "forest");
            $this->terrain->addTerrain(2510 ,1 , "forest");
            $this->terrain->addTerrain(2517 ,1 , "forest");
            $this->terrain->addTerrain(2608 ,4 , "forest");
            $this->terrain->addTerrain(2608 ,3 , "forest");
            $this->terrain->addTerrain(2609 ,4 , "forest");
            $this->terrain->addTerrain(2609 ,3 , "forest");
            $this->terrain->addTerrain(2608 ,1 , "forest");
            $this->terrain->addTerrain(2608 ,2 , "forest");
            $this->terrain->addTerrain(2609 ,1 , "forest");
            $this->terrain->addTerrain(1810 ,1 , "forest");
            $this->terrain->addTerrain(1910 ,3 , "forest");
            $this->terrain->addTerrain(1911 ,4 , "forest");
            $this->terrain->addTerrain(1910 ,1 , "forest");
            $this->terrain->addTerrain(1910 ,2 , "forest");
            $this->terrain->addTerrain(1911 ,1 , "forest");
            $this->terrain->addTerrain(501 ,1 , "road");
            $this->terrain->addReinforceZone(501,'A');
            $this->terrain->addTerrain(501 ,2 , "road");
            $this->terrain->addTerrain(502 ,1 , "road");
            $this->terrain->addReinforceZone(502,'A');
            $this->terrain->addTerrain(502 ,2 , "road");
            $this->terrain->addTerrain(503 ,1 , "road");
            $this->terrain->addReinforceZone(503,'A');
            $this->terrain->addTerrain(503 ,2 , "road");
            $this->terrain->addTerrain(504 ,1 , "road");
            $this->terrain->addReinforceZone(504,'A');
            $this->terrain->addTerrain(504 ,2 , "road");
            $this->terrain->addTerrain(505 ,1 , "road");
            $this->terrain->addReinforceZone(505,'A');
            $this->terrain->addTerrain(505 ,2 , "road");
            $this->terrain->addTerrain(512 ,1 , "road");
            $this->terrain->addTerrain(512 ,2 , "road");
            $this->terrain->addTerrain(513 ,1 , "road");
            $this->terrain->addTerrain(513 ,2 , "road");
            $this->terrain->addTerrain(514 ,2 , "road");
            $this->terrain->addTerrain(515 ,1 , "road");
            $this->terrain->addReinforceZone(515,'B');
            $this->terrain->addTerrain(515 ,2 , "road");
            $this->terrain->addTerrain(516 ,1 , "road");
            $this->terrain->addReinforceZone(516,'B');
            $this->terrain->addTerrain(516 ,2 , "road");
            $this->terrain->addTerrain(517 ,1 , "road");
            $this->terrain->addReinforceZone(517,'B');
            $this->terrain->addTerrain(517 ,2 , "road");
            $this->terrain->addTerrain(518 ,1 , "road");
            $this->terrain->addReinforceZone(518,'B');
            $this->terrain->addTerrain(617 ,3 , "road");
            $this->terrain->addTerrain(617 ,1 , "road");
            $this->terrain->addReinforceZone(617,'B');
            $this->terrain->addTerrain(717 ,3 , "road");
            $this->terrain->addTerrain(717 ,1 , "road");
            $this->terrain->addReinforceZone(717,'B');
            $this->terrain->addTerrain(816 ,3 , "road");
            $this->terrain->addTerrain(916 ,3 , "road");
            $this->terrain->addTerrain(916 ,1 , "road");
            $this->terrain->addReinforceZone(916,'B');
            $this->terrain->addTerrain(1015 ,3 , "road");
            $this->terrain->addTerrain(1015 ,1 , "road");
            $this->terrain->addReinforceZone(1015,'B');
            $this->terrain->addTerrain(1115 ,3 , "road");
            $this->terrain->addTerrain(1115 ,1 , "road");
            $this->terrain->addReinforceZone(1115,'B');
            $this->terrain->addTerrain(1214 ,1 , "road");
            $this->terrain->addReinforceZone(1214,'B');
            $this->terrain->addTerrain(1214 ,3 , "road");
            $this->terrain->addTerrain(1314 ,3 , "road");
            $this->terrain->addTerrain(1314 ,1 , "road");
            $this->terrain->addReinforceZone(1314,'B');
            $this->terrain->addTerrain(1413 ,3 , "road");
            $this->terrain->addTerrain(1413 ,1 , "road");
            $this->terrain->addReinforceZone(1413,'B');
            $this->terrain->addTerrain(1413 ,1 , "redoubt");
            $this->terrain->addTerrain(1513 ,3 , "road");
            $this->terrain->addTerrain(1513 ,3 , "redoubt");
            $this->terrain->addTerrain(1513 ,1 , "road");
            $this->terrain->addTerrain(1612 ,3 , "road");
            $this->terrain->addTerrain(1612 ,1 , "road");
            $this->terrain->addTerrain(1612 ,2 , "road");
            $this->terrain->addTerrain(1613 ,1 , "road");
            $this->terrain->addTerrain(1613 ,2 , "road");
            $this->terrain->addTerrain(1613 ,2 , "redoubt");
            $this->terrain->addTerrain(1614 ,1 , "road");
            $this->terrain->addReinforceZone(1614,'B');
            $this->terrain->addTerrain(1614 ,1 , "redoubt");
            $this->terrain->addTerrain(1614 ,2 , "road");
            $this->terrain->addTerrain(1616 ,1 , "road");
            $this->terrain->addReinforceZone(1616,'B');
            $this->terrain->addTerrain(1616 ,2 , "road");
            $this->terrain->addTerrain(1617 ,1 , "road");
            $this->terrain->addReinforceZone(1617,'B');
            $this->terrain->addTerrain(1618 ,1 , "road");
            $this->terrain->addReinforceZone(1618,'B');
            $this->terrain->addTerrain(1718 ,3 , "road");
            $this->terrain->addTerrain(1718 ,1 , "road");
            $this->terrain->addReinforceZone(1718,'B');
            $this->terrain->addTerrain(1817 ,3 , "road");
            $this->terrain->addTerrain(1817 ,1 , "road");
            $this->terrain->addReinforceZone(1817,'B');
            $this->terrain->addTerrain(1917 ,3 , "road");
            $this->terrain->addTerrain(2511 ,4 , "road");
            $this->terrain->addTerrain(2511 ,1 , "road");
            $this->terrain->addTerrain(2611 ,4 , "road");
            $this->terrain->addTerrain(2611 ,1 , "road");
            $this->terrain->addTerrain(2607 ,3 , "road");
            $this->terrain->addTerrain(2607 ,1 , "road");
            $this->terrain->addTerrain(1701 ,1 , "road");
            $this->terrain->addReinforceZone(1701,'A');
            $this->terrain->addTerrain(1701 ,2 , "road");
            $this->terrain->addTerrain(1702 ,1 , "road");
            $this->terrain->addReinforceZone(1702,'A');
            $this->terrain->addTerrain(1702 ,2 , "road");
            $this->terrain->addTerrain(1703 ,1 , "road");
            $this->terrain->addReinforceZone(1703,'A');
            $this->terrain->addTerrain(1704 ,1 , "road");
            $this->terrain->addReinforceZone(1704,'A');
            $this->terrain->addTerrain(1704 ,2 , "road");
            $this->terrain->addTerrain(1705 ,1 , "road");
            $this->terrain->addReinforceZone(1705,'A');
            $this->terrain->addTerrain(1705 ,2 , "road");
            $this->terrain->addTerrain(1706 ,1 , "road");
            $this->terrain->addReinforceZone(1706,'A');
            $this->terrain->addTerrain(1706 ,2 , "road");
            $this->terrain->addTerrain(1707 ,1 , "road");
            $this->terrain->addTerrain(1707 ,2 , "road");
            $this->terrain->addTerrain(1708 ,1 , "road");
            $this->terrain->addTerrain(1708 ,2 , "road");
            $this->terrain->addTerrain(1709 ,1 , "road");
            $this->terrain->addTerrain(1709 ,3 , "road");
            $this->terrain->addTerrain(1609 ,1 , "road");
            $this->terrain->addTerrain(1609 ,2 , "road");
            $this->terrain->addTerrain(1610 ,1 , "road");
            $this->terrain->addTerrain(1610 ,2 , "road");
            $this->terrain->addTerrain(1611 ,1 , "road");
            $this->terrain->addTerrain(1611 ,2 , "road");
            $this->terrain->addReinforceZone(101,'A');
            $this->terrain->addReinforceZone(102,'A');
            $this->terrain->addReinforceZone(103,'A');
            $this->terrain->addReinforceZone(104,'A');
            $this->terrain->addReinforceZone(105,'A');
            $this->terrain->addReinforceZone(106,'A');
            $this->terrain->addReinforceZone(206,'A');
            $this->terrain->addReinforceZone(205,'A');
            $this->terrain->addReinforceZone(204,'A');
            $this->terrain->addReinforceZone(202,'A');
            $this->terrain->addReinforceZone(201,'A');
            $this->terrain->addReinforceZone(301,'A');
            $this->terrain->addReinforceZone(302,'A');
            $this->terrain->addReinforceZone(303,'A');
            $this->terrain->addReinforceZone(304,'A');
            $this->terrain->addReinforceZone(305,'A');
            $this->terrain->addReinforceZone(306,'A');
            $this->terrain->addReinforceZone(405,'A');
            $this->terrain->addReinforceZone(404,'A');
            $this->terrain->addReinforceZone(403,'A');
            $this->terrain->addReinforceZone(402,'A');
            $this->terrain->addReinforceZone(401,'A');
            $this->terrain->addReinforceZone(604,'A');
            $this->terrain->addReinforceZone(603,'A');
            $this->terrain->addReinforceZone(602,'A');
            $this->terrain->addReinforceZone(601,'A');
            $this->terrain->addReinforceZone(701,'A');
            $this->terrain->addReinforceZone(702,'A');
            $this->terrain->addReinforceZone(703,'A');
            $this->terrain->addReinforceZone(704,'A');
            $this->terrain->addReinforceZone(803,'A');
            $this->terrain->addReinforceZone(802,'A');
            $this->terrain->addReinforceZone(801,'A');
            $this->terrain->addReinforceZone(901,'A');
            $this->terrain->addReinforceZone(902,'A');
            $this->terrain->addReinforceZone(903,'A');
            $this->terrain->addReinforceZone(904,'A');
            $this->terrain->addReinforceZone(1004,'A');
            $this->terrain->addReinforceZone(1001,'A');
            $this->terrain->addReinforceZone(1002,'A');
            $this->terrain->addReinforceZone(1003,'A');
            $this->terrain->addReinforceZone(1101,'A');
            $this->terrain->addReinforceZone(1102,'A');
            $this->terrain->addReinforceZone(1103,'A');
            $this->terrain->addReinforceZone(1104,'A');
            $this->terrain->addReinforceZone(1105,'A');
            $this->terrain->addReinforceZone(1205,'A');
            $this->terrain->addReinforceZone(1204,'A');
            $this->terrain->addReinforceZone(1203,'A');
            $this->terrain->addReinforceZone(1202,'A');
            $this->terrain->addReinforceZone(1201,'A');
            $this->terrain->addReinforceZone(1301,'A');
            $this->terrain->addReinforceZone(1302,'A');
            $this->terrain->addReinforceZone(1303,'A');
            $this->terrain->addReinforceZone(1304,'A');
            $this->terrain->addReinforceZone(1305,'A');
            $this->terrain->addReinforceZone(1306,'A');
            $this->terrain->addReinforceZone(1405,'A');
            $this->terrain->addReinforceZone(1404,'A');
            $this->terrain->addReinforceZone(1403,'A');
            $this->terrain->addReinforceZone(1402,'A');
            $this->terrain->addReinforceZone(1401,'A');
            $this->terrain->addReinforceZone(1501,'A');
            $this->terrain->addReinforceZone(1502,'A');
            $this->terrain->addReinforceZone(1503,'A');
            $this->terrain->addReinforceZone(1504,'A');
            $this->terrain->addReinforceZone(1505,'A');
            $this->terrain->addReinforceZone(1506,'A');
            $this->terrain->addReinforceZone(1605,'A');
            $this->terrain->addReinforceZone(1604,'A');
            $this->terrain->addReinforceZone(1603,'A');
            $this->terrain->addReinforceZone(1602,'A');
            $this->terrain->addReinforceZone(1601,'A');
            $this->terrain->addReinforceZone(1805,'A');
            $this->terrain->addReinforceZone(1804,'A');
            $this->terrain->addReinforceZone(1803,'A');
            $this->terrain->addReinforceZone(1801,'A');
            $this->terrain->addReinforceZone(1901,'A');
            $this->terrain->addReinforceZone(1802,'A');
            $this->terrain->addReinforceZone(1902,'A');
            $this->terrain->addReinforceZone(1903,'A');
            $this->terrain->addReinforceZone(1904,'A');
            $this->terrain->addReinforceZone(1905,'A');
            $this->terrain->addReinforceZone(1906,'A');
            $this->terrain->addReinforceZone(2005,'A');
            $this->terrain->addReinforceZone(2004,'A');
            $this->terrain->addReinforceZone(2002,'A');
            $this->terrain->addReinforceZone(2001,'A');
            $this->terrain->addReinforceZone(2003,'A');
            $this->terrain->addReinforceZone(2101,'A');
            $this->terrain->addReinforceZone(2102,'A');
            $this->terrain->addReinforceZone(2103,'A');
            $this->terrain->addReinforceZone(2104,'A');
            $this->terrain->addReinforceZone(2105,'A');
            $this->terrain->addReinforceZone(2205,'A');
            $this->terrain->addReinforceZone(2204,'A');
            $this->terrain->addReinforceZone(2201,'A');
            $this->terrain->addReinforceZone(2202,'A');
            $this->terrain->addReinforceZone(2203,'A');
            $this->terrain->addReinforceZone(2301,'A');
            $this->terrain->addReinforceZone(2302,'A');
            $this->terrain->addReinforceZone(2303,'A');
            $this->terrain->addReinforceZone(2304,'A');
            $this->terrain->addReinforceZone(2305,'A');
            $this->terrain->addReinforceZone(2405,'A');
            $this->terrain->addReinforceZone(2404,'A');
            $this->terrain->addReinforceZone(2402,'A');
            $this->terrain->addReinforceZone(2403,'A');
            $this->terrain->addReinforceZone(2401,'A');
            $this->terrain->addReinforceZone(2501,'A');
            $this->terrain->addReinforceZone(2502,'A');
            $this->terrain->addReinforceZone(2503,'A');
            $this->terrain->addReinforceZone(2504,'A');
            $this->terrain->addReinforceZone(2505,'A');
            $this->terrain->addReinforceZone(2605,'A');
            $this->terrain->addReinforceZone(2604,'A');
            $this->terrain->addReinforceZone(2602,'A');
            $this->terrain->addReinforceZone(2601,'A');
            $this->terrain->addReinforceZone(2603,'A');
            $this->terrain->addReinforceZone(203,'A');
            $this->terrain->addReinforceZone(418,'B');
            $this->terrain->addReinforceZone(417,'B');
            $this->terrain->addReinforceZone(415,'B');
            $this->terrain->addReinforceZone(414,'B');
            $this->terrain->addReinforceZone(614,'B');
            $this->terrain->addTerrain(614 ,1 , "redoubt");
            $this->terrain->addReinforceZone(714,'B');
            $this->terrain->addTerrain(714 ,1 , "redoubt");
            $this->terrain->addReinforceZone(814,'B');
            $this->terrain->addTerrain(814 ,1 , "redoubt");
            $this->terrain->addReinforceZone(915,'B');
            $this->terrain->addTerrain(915 ,1 , "redoubt");
            $this->terrain->addReinforceZone(1114,'B');
            $this->terrain->addReinforceZone(1313,'B');
            $this->terrain->addTerrain(1313 ,1 , "redoubt");
            $this->terrain->addReinforceZone(1514,'B');
            $this->terrain->addTerrain(1514 ,1 , "redoubt");
            $this->terrain->addReinforceZone(1715,'B');
            $this->terrain->addTerrain(1715 ,1 , "redoubt");
            $this->terrain->addReinforceZone(1814,'B');
            $this->terrain->addTerrain(1814 ,1 , "redoubt");
            $this->terrain->addReinforceZone(2318,'B');
            $this->terrain->addReinforceZone(615,'B');
            $this->terrain->addReinforceZone(616,'B');
            $this->terrain->addReinforceZone(618,'B');
            $this->terrain->addReinforceZone(715,'B');
            $this->terrain->addReinforceZone(716,'B');
            $this->terrain->addReinforceZone(718,'B');
            $this->terrain->addReinforceZone(817,'B');
            $this->terrain->addReinforceZone(818,'B');
            $this->terrain->addReinforceZone(815,'B');
            $this->terrain->addReinforceZone(917,'B');
            $this->terrain->addReinforceZone(918,'B');
            $this->terrain->addReinforceZone(1016,'B');
            $this->terrain->addReinforceZone(1017,'B');
            $this->terrain->addReinforceZone(1018,'B');
            $this->terrain->addReinforceZone(1116,'B');
            $this->terrain->addReinforceZone(1117,'B');
            $this->terrain->addReinforceZone(1118,'B');
            $this->terrain->addReinforceZone(1213,'B');
            $this->terrain->addReinforceZone(1215,'B');
            $this->terrain->addReinforceZone(1216,'B');
            $this->terrain->addReinforceZone(1217,'B');
            $this->terrain->addReinforceZone(1218,'B');
            $this->terrain->addReinforceZone(1315,'B');
            $this->terrain->addReinforceZone(1316,'B');
            $this->terrain->addReinforceZone(1317,'B');
            $this->terrain->addReinforceZone(1318,'B');
            $this->terrain->addReinforceZone(1414,'B');
            $this->terrain->addReinforceZone(1415,'B');
            $this->terrain->addReinforceZone(1416,'B');
            $this->terrain->addReinforceZone(1417,'B');
            $this->terrain->addReinforceZone(1418,'B');
            $this->terrain->addReinforceZone(1515,'B');
            $this->terrain->addReinforceZone(1516,'B');
            $this->terrain->addReinforceZone(1517,'B');
            $this->terrain->addReinforceZone(1518,'B');
            $this->terrain->addReinforceZone(1716,'B');
            $this->terrain->addReinforceZone(1717,'B');
            $this->terrain->addReinforceZone(1815,'B');
            $this->terrain->addReinforceZone(1816,'B');
            $this->terrain->addReinforceZone(1818,'B');
            $this->terrain->addReinforceZone(1918,'B');
            $this->terrain->addReinforceZone(2018,'B');
            $this->terrain->addReinforceZone(2118,'B');
            $this->terrain->addReinforceZone(2218,'B');
            $this->terrain->addTerrain(613 ,2 , "redoubt");
            $this->terrain->addTerrain(714 ,4 , "redoubt");
            $this->terrain->addTerrain(713 ,2 , "redoubt");
            $this->terrain->addTerrain(914 ,3 , "redoubt");
            $this->terrain->addTerrain(914 ,2 , "redoubt");
            $this->terrain->addTerrain(1014 ,3 , "redoubt");
            $this->terrain->addTerrain(1209 ,3 , "redoubt");
            $this->terrain->addTerrain(1209 ,2 , "redoubt");
            $this->terrain->addTerrain(1310 ,3 , "redoubt");
            $this->terrain->addTerrain(1310 ,2 , "redoubt");
            $this->terrain->addTerrain(1410 ,3 , "redoubt");
            $this->terrain->addTerrain(1411 ,4 , "redoubt");
            $this->terrain->addTerrain(1311 ,2 , "redoubt");
            $this->terrain->addTerrain(1312 ,4 , "redoubt");
            $this->terrain->addTerrain(1312 ,3 , "redoubt");
            $this->terrain->addTerrain(1312 ,2 , "redoubt");
            $this->terrain->addTerrain(1412 ,3 , "redoubt");
            $this->terrain->addTerrain(1412 ,2 , "redoubt");
            $this->terrain->addTerrain(1513 ,2 , "redoubt");
            $this->terrain->addTerrain(1613 ,3 , "redoubt");
            $this->terrain->addTerrain(1714 ,3 , "redoubt");
            $this->terrain->addTerrain(1714 ,2 , "redoubt");
            $this->terrain->addTerrain(1814 ,4 , "redoubt");
            $this->terrain->addTerrain(1813 ,2 , "redoubt");
            $this->terrain->addTerrain(1914 ,4 , "redoubt");
            $this->terrain->addTerrain(1913 ,3 , "redoubt");
            $this->terrain->addTerrain(1913 ,4 , "redoubt");
            $this->terrain->addTerrain(1912 ,2 , "redoubt");
            $this->terrain->addTerrain(2012 ,4 , "redoubt");
            $this->terrain->addTerrain(2011 ,2 , "redoubt");
            $this->terrain->addTerrain(2112 ,4 , "redoubt");
            $this->terrain->addTerrain(2111 ,2 , "redoubt");
            $this->terrain->addTerrain(2211 ,4 , "redoubt");

//            $this->angloSpecialHexes = $specialHexA;
//            $this->frenchSpecialHexes = $specialHexB;
//            foreach ($specialHexA as $specialHexId) {
//                $specialHexes[$specialHexId] = ANGLO_FORCE;
//            }
//            foreach ($specialHexB as $specialHexId) {
//                $specialHexes[$specialHexId] = FRENCH_FORCE;
//            }
//            $this->mapData->setSpecialHexes($specialHexes);


            // end terrain data ----------------------------------------

        }
    }
}