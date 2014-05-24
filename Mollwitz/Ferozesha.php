<?php
set_include_path(__DIR__ . "/Ferozesha" . PATH_SEPARATOR . get_include_path());
require_once "JagCore.php";
/* comment */
define("BRITISH_FORCE", 1);
define("SIKH_FORCE", 2);
$force_name[SIKH_FORCE] = "Sikh";
$force_name[BRITISH_FORCE] = "British";
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


class Ferozesha extends JagCore
{

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $playerData;
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
    public $roadHex;


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
            for ($i = 0; $i < 2; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 3, "Sikh", false, 'artillery');
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

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->roadHex = $data->roadHex;
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

            if($scenario->dayTwo){
                $this->mapData->setData(33, 21, "js/Ferozesha2Small.png");
            }else{
                $this->mapData->setData(33, 21, "js/Ferozesha1Small.png");
            }
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
            $this->players = array("", "", "");
            $this->playerData = new stdClass();
            for ($player = 0; $player <= 2; $player++) {
                $this->playerData->${player} = new stdClass();
                $this->playerData->${player}->mapWidth = "auto";
                $this->playerData->${player}->mapHeight = "auto";
                $this->playerData->${player}->unitSize = "32px";
                $this->playerData->${player}->unitFontSize = "12px";
                $this->playerData->${player}->unitMargin = "-21px";
                $this->mapViewer[$player]->setData(51.10000000000001 , 81.96930446819712, // originX, originY
                    27.323101489399043, 27.323101489399043, // top hexagon height, bottom hexagon height
                    15.775, 31.55// hexagon edge width, hexagon center width
                );
            }

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

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", .75, 0, 0, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
            $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
            $this->terrain->addNatAltEntranceCost('forest','Sikh', 'infantry', 1);
            $this->terrain->addAltEntranceCost('forest', 'horseartillery', 4);
            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("redoubt", "redoubt", "d", 0, 2, 0, false);
            $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("wadi", "wadi", "v", 0, 2, 0, false);
            $this->terrain->addAltEntranceCost('swamp','artillery','blocked');


            for ($col = 100; $col <= 3300; $col += 100) {
                for ($row = 1; $row <= 21; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
            $specialHexA = [];
            $specialHexB = [];
            if($scenario->dayOne){
                $this->terrain->addReinforceZone(801,'A');
                $this->terrain->addReinforceZone(802,'A');
                $this->terrain->addTerrain(802 ,1 , "road");
                $this->terrain->addReinforceZone(903,'A');
                $this->terrain->addTerrain(903 ,1 , "road");
                $this->terrain->addReinforceZone(1003,'A');
                $this->terrain->addTerrain(1003 ,1 , "town");
                $this->terrain->addReinforceZone(1104,'A');
                $this->terrain->addTerrain(1104 ,1 , "road");
                $this->terrain->addReinforceZone(1204,'A');
                $this->terrain->addTerrain(1204 ,1 , "road");
                $this->terrain->addReinforceZone(1304,'A');
                $this->terrain->addTerrain(1304 ,1 , "forest");
                $this->terrain->addTerrain(1304 ,1 , "road");
                $this->terrain->addReinforceZone(1404,'A');
                $this->terrain->addTerrain(1404 ,1 , "forest");
                $this->terrain->addTerrain(1404 ,1 , "road");
                $this->terrain->addReinforceZone(1305,'A');
                $this->terrain->addReinforceZone(1505,'A');
                $this->terrain->addTerrain(1505 ,1 , "road");
                $this->terrain->addTerrain(1505 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1506,'A');
                $this->terrain->addTerrain(1506 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1507,'A');
                $this->terrain->addTerrain(1507 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1508,'A');
                $this->terrain->addTerrain(1508 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1608,'A');
                $this->terrain->addTerrain(1608 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1708,'A');
                $this->terrain->addTerrain(1708 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1807,'A');
                $this->terrain->addTerrain(1807 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1806,'A');
                $this->terrain->addTerrain(1806 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1906,'A');
                $this->terrain->addTerrain(1906 ,1 , "road");
                $this->terrain->addTerrain(1906 ,1 , "redoubt");
                $this->terrain->addReinforceZone(2006,'A');
                $this->terrain->addTerrain(2006 ,1 , "road");
                $this->terrain->addReinforceZone(2107,'A');
                $this->terrain->addTerrain(2107 ,1 , "road");
                $this->terrain->addReinforceZone(2207,'A');
                $this->terrain->addTerrain(2207 ,1 , "road");
                $this->terrain->addReinforceZone(2308,'A');
                $this->terrain->addTerrain(2308 ,1 , "road");
                $this->terrain->addReinforceZone(2307,'A');
                $this->terrain->addReinforceZone(2306,'A');
                $this->terrain->addReinforceZone(2305,'A');
                $this->terrain->addReinforceZone(2304,'A');
                $this->terrain->addTerrain(2304 ,1 , "road");
                $this->terrain->addReinforceZone(2303,'A');
                $this->terrain->addReinforceZone(2302,'A');
                $this->terrain->addReinforceZone(2301,'A');
                $this->terrain->addReinforceZone(2201,'A');
                $this->terrain->addReinforceZone(2202,'A');
                $this->terrain->addReinforceZone(2203,'A');
                $this->terrain->addReinforceZone(2204,'A');
                $this->terrain->addTerrain(2204 ,1 , "road");
                $this->terrain->addReinforceZone(2205,'A');
                $this->terrain->addReinforceZone(2206,'A');
                $this->terrain->addReinforceZone(2106,'A');
                $this->terrain->addReinforceZone(2005,'A');
                $this->terrain->addReinforceZone(2105,'A');
                $this->terrain->addReinforceZone(2104,'A');
                $this->terrain->addTerrain(2104 ,1 , "road");
                $this->terrain->addReinforceZone(2103,'A');
                $this->terrain->addReinforceZone(2004,'A');
                $this->terrain->addTerrain(2004 ,1 , "road");
                $this->terrain->addReinforceZone(2003,'A');
                $this->terrain->addReinforceZone(2002,'A');
                $this->terrain->addReinforceZone(2102,'A');
                $this->terrain->addReinforceZone(2101,'A');
                $this->terrain->addReinforceZone(2001,'A');
                $this->terrain->addReinforceZone(1903,'A');
                $this->terrain->addReinforceZone(1902,'A');
                $this->terrain->addReinforceZone(1901,'A');
                $this->terrain->addReinforceZone(1801,'A');
                $this->terrain->addReinforceZone(1802,'A');
                $this->terrain->addReinforceZone(1805,'A');
                $this->terrain->addTerrain(1805 ,1 , "road");
                $this->terrain->addReinforceZone(1905,'A');
                $this->terrain->addTerrain(1905 ,1 , "road");
                $this->terrain->addTerrain(1905 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1904,'A');
                $this->terrain->addTerrain(1904 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1804,'A');
                $this->terrain->addReinforceZone(1803,'A');
                $this->terrain->addTerrain(1803 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1706,'A');
                $this->terrain->addTerrain(1706 ,1 , "town");
                $this->terrain->addReinforceZone(1605,'A');
                $this->terrain->addTerrain(1605 ,1 , "town");
                $this->terrain->addReinforceZone(1606,'A');
                $this->terrain->addTerrain(1606 ,1 , "forest");
                $this->terrain->addReinforceZone(1707,'A');
                $this->terrain->addTerrain(1707 ,1 , "forest");
                $this->terrain->addReinforceZone(1705,'A');
                $this->terrain->addTerrain(1705 ,1 , "town");
                $this->terrain->addReinforceZone(1604,'A');
                $this->terrain->addTerrain(1604 ,1 , "town");
                $this->terrain->addReinforceZone(1704,'A');
                $this->terrain->addReinforceZone(1703,'A');
                $this->terrain->addTerrain(1703 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1702,'A');
                $this->terrain->addReinforceZone(1701,'A');
                $this->terrain->addReinforceZone(1601,'A');
                $this->terrain->addReinforceZone(1602,'A');
                $this->terrain->addTerrain(1602 ,2 , "redoubt");
                $this->terrain->addReinforceZone(1504,'A');
                $this->terrain->addTerrain(1504 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1503,'A');
                $this->terrain->addReinforceZone(1502,'A');
                $this->terrain->addReinforceZone(1501,'A');
                $this->terrain->addReinforceZone(1401,'A');
                $this->terrain->addReinforceZone(1402,'A');
                $this->terrain->addReinforceZone(1403,'A');
                $this->terrain->addReinforceZone(1303,'A');
                $this->terrain->addReinforceZone(1302,'A');
                $this->terrain->addReinforceZone(1301,'A');
                $this->terrain->addReinforceZone(1201,'A');
                $this->terrain->addReinforceZone(1202,'A');
                $this->terrain->addReinforceZone(1203,'A');
                $this->terrain->addReinforceZone(1103,'A');
                $this->terrain->addTerrain(1103 ,1 , "forest");
                $this->terrain->addReinforceZone(1102,'A');
                $this->terrain->addReinforceZone(1101,'A');
                $this->terrain->addReinforceZone(1001,'A');
                $this->terrain->addReinforceZone(1002,'A');
                $this->terrain->addTerrain(1002 ,1 , "forest");
                $this->terrain->addReinforceZone(902,'A');
                $this->terrain->addReinforceZone(901,'A');
                $this->terrain->addReinforceZone(113,'B');
                $this->terrain->addTerrain(113 ,1 , "road");
                $this->terrain->addReinforceZone(213,'B');
                $this->terrain->addTerrain(213 ,1 , "road");
                $this->terrain->addReinforceZone(314,'B');
                $this->terrain->addTerrain(314 ,1 , "road");
                $this->terrain->addReinforceZone(413,'B');
                $this->terrain->addTerrain(413 ,1 , "road");
                $this->terrain->addReinforceZone(514,'B');
                $this->terrain->addTerrain(514 ,1 , "road");
                $this->terrain->addReinforceZone(614,'B');
                $this->terrain->addTerrain(614 ,1 , "road");
                $this->terrain->addReinforceZone(715,'B');
                $this->terrain->addTerrain(715 ,1 , "road");
                $this->terrain->addReinforceZone(814,'B');
                $this->terrain->addTerrain(814 ,1 , "town");
                $this->terrain->addReinforceZone(915,'B');
                $this->terrain->addTerrain(915 ,1 , "forest");
                $this->terrain->addTerrain(915 ,1 , "road");
                $this->terrain->addReinforceZone(1015,'B');
                $this->terrain->addTerrain(1015 ,1 , "road");
                $this->terrain->addReinforceZone(1115,'B');
                $this->terrain->addTerrain(1115 ,1 , "road");
                $this->terrain->addReinforceZone(1215,'B');
                $this->terrain->addTerrain(1215 ,1 , "road");
                $this->terrain->addReinforceZone(1316,'B');
                $this->terrain->addTerrain(1316 ,1 , "road");
                $this->terrain->addReinforceZone(1415,'B');
                $this->terrain->addTerrain(1415 ,1 , "road");
                $this->terrain->addReinforceZone(1516,'B');
                $this->terrain->addTerrain(1516 ,1 , "road");
                $this->terrain->addReinforceZone(1615,'B');
                $this->terrain->addTerrain(1615 ,1 , "road");
                $this->terrain->addReinforceZone(1716,'B');
                $this->terrain->addTerrain(1716 ,1 , "road");
                $this->terrain->addReinforceZone(1815,'B');
                $this->terrain->addTerrain(1815 ,1 , "road");
                $this->terrain->addReinforceZone(1916,'B');
                $this->terrain->addTerrain(1916 ,1 , "road");
                $this->terrain->addReinforceZone(2015,'B');
                $this->terrain->addTerrain(2015 ,1 , "road");
                $this->terrain->addReinforceZone(2115,'B');
                $this->terrain->addTerrain(2115 ,1 , "road");
                $this->terrain->addReinforceZone(2214,'B');
                $this->terrain->addTerrain(2214 ,1 , "road");
                $this->terrain->addReinforceZone(2314,'B');
                $this->terrain->addTerrain(2314 ,1 , "road");
                $this->terrain->addReinforceZone(2414,'B');
                $this->terrain->addTerrain(2414 ,1 , "road");
                $this->terrain->addReinforceZone(2514,'B');
                $this->terrain->addTerrain(2514 ,1 , "road");
                $this->terrain->addReinforceZone(2614,'B');
                $this->terrain->addTerrain(2614 ,1 , "road");
                $this->terrain->addReinforceZone(2714,'B');
                $this->terrain->addTerrain(2714 ,1 , "road");
                $this->terrain->addReinforceZone(2814,'B');
                $this->terrain->addTerrain(2814 ,1 , "road");
                $this->terrain->addReinforceZone(2915,'B');
                $this->terrain->addTerrain(2915 ,1 , "road");
                $this->terrain->addReinforceZone(3015,'B');
                $this->terrain->addTerrain(3015 ,1 , "road");
                $this->terrain->addReinforceZone(3116,'B');
                $this->terrain->addTerrain(3116 ,1 , "forest");
                $this->terrain->addTerrain(3116 ,1 , "road");
                $this->terrain->addReinforceZone(3216,'B');
                $this->terrain->addTerrain(3216 ,1 , "forest");
                $this->terrain->addTerrain(3216 ,1 , "road");
                $this->terrain->addReinforceZone(3217,'B');
                $this->terrain->addTerrain(3217 ,1 , "town");
                $this->terrain->addReinforceZone(3318,'B');
                $this->terrain->addTerrain(3318 ,1 , "road");
                $this->terrain->addReinforceZone(3319,'B');
                $this->terrain->addReinforceZone(3218,'B');
                $this->terrain->addReinforceZone(3118,'B');
                $this->terrain->addReinforceZone(3017,'B');
                $this->terrain->addReinforceZone(3016,'B');
                $this->terrain->addReinforceZone(2916,'B');
                $this->terrain->addReinforceZone(2815,'B');
                $this->terrain->addReinforceZone(2715,'B');
                $this->terrain->addReinforceZone(2615,'B');
                $this->terrain->addReinforceZone(2515,'B');
                $this->terrain->addReinforceZone(2415,'B');
                $this->terrain->addReinforceZone(2315,'B');
                $this->terrain->addReinforceZone(2215,'B');
                $this->terrain->addReinforceZone(2116,'B');
                $this->terrain->addReinforceZone(2016,'B');
                $this->terrain->addReinforceZone(1917,'B');
                $this->terrain->addReinforceZone(1816,'B');
                $this->terrain->addReinforceZone(1717,'B');
                $this->terrain->addReinforceZone(1616,'B');
                $this->terrain->addReinforceZone(1517,'B');
                $this->terrain->addReinforceZone(1416,'B');
                $this->terrain->addReinforceZone(1317,'B');
                $this->terrain->addReinforceZone(1216,'B');
                $this->terrain->addReinforceZone(1116,'B');
                $this->terrain->addReinforceZone(1016,'B');
                $this->terrain->addReinforceZone(916,'B');
                $this->terrain->addReinforceZone(815,'B');
                $this->terrain->addReinforceZone(716,'B');
                $this->terrain->addReinforceZone(615,'B');
                $this->terrain->addReinforceZone(515,'B');
                $this->terrain->addReinforceZone(414,'B');
                $this->terrain->addReinforceZone(315,'B');
                $this->terrain->addReinforceZone(214,'B');
                $this->terrain->addReinforceZone(114,'B');
                $this->terrain->addReinforceZone(115,'B');
                $this->terrain->addReinforceZone(215,'B');
                $this->terrain->addReinforceZone(316,'B');
                $this->terrain->addReinforceZone(415,'B');
                $this->terrain->addReinforceZone(516,'B');
                $this->terrain->addReinforceZone(616,'B');
                $this->terrain->addReinforceZone(717,'B');
                $this->terrain->addReinforceZone(816,'B');
                $this->terrain->addReinforceZone(917,'B');
                $this->terrain->addReinforceZone(1017,'B');
                $this->terrain->addReinforceZone(1117,'B');
                $this->terrain->addReinforceZone(1217,'B');
                $this->terrain->addReinforceZone(1318,'B');
                $this->terrain->addReinforceZone(1417,'B');
                $this->terrain->addReinforceZone(1518,'B');
                $this->terrain->addReinforceZone(1617,'B');
                $this->terrain->addReinforceZone(1718,'B');
                $this->terrain->addReinforceZone(1817,'B');
                $this->terrain->addReinforceZone(1918,'B');
                $this->terrain->addReinforceZone(2017,'B');
                $this->terrain->addReinforceZone(2117,'B');
                $this->terrain->addReinforceZone(2216,'B');
                $this->terrain->addReinforceZone(2316,'B');
                $this->terrain->addReinforceZone(2416,'B');
                $this->terrain->addReinforceZone(2516,'B');
                $this->terrain->addReinforceZone(2616,'B');
                $this->terrain->addReinforceZone(2716,'B');
                $this->terrain->addReinforceZone(2816,'B');
                $this->terrain->addReinforceZone(2917,'B');
                $this->terrain->addReinforceZone(3320,'B');
                $this->terrain->addReinforceZone(3219,'B');
                $this->terrain->addReinforceZone(3119,'B');
                $this->terrain->addReinforceZone(3018,'B');
                $this->terrain->addReinforceZone(2918,'B');
                $this->terrain->addReinforceZone(2817,'B');
                $this->terrain->addReinforceZone(2717,'B');
                $this->terrain->addReinforceZone(2617,'B');
                $this->terrain->addReinforceZone(2518,'B');
                $this->terrain->addReinforceZone(2418,'B');
                $this->terrain->addReinforceZone(2319,'B');
                $this->terrain->addReinforceZone(2218,'B');
                $this->terrain->addReinforceZone(2119,'B');
                $this->terrain->addReinforceZone(2019,'B');
                $this->terrain->addReinforceZone(2018,'B');
                $this->terrain->addReinforceZone(2118,'B');
                $this->terrain->addReinforceZone(2217,'B');
                $this->terrain->addReinforceZone(2317,'B');
                $this->terrain->addReinforceZone(2417,'B');
                $this->terrain->addReinforceZone(2618,'B');
                $this->terrain->addReinforceZone(2718,'B');
                $this->terrain->addReinforceZone(2719,'B');
                $this->terrain->addReinforceZone(2818,'B');
                $this->terrain->addReinforceZone(2819,'B');
                $this->terrain->addReinforceZone(2919,'B');
                $this->terrain->addReinforceZone(3019,'B');
                $this->terrain->addReinforceZone(2920,'B');
                $this->terrain->addReinforceZone(3020,'B');
                $this->terrain->addReinforceZone(3120,'B');
                $this->terrain->addReinforceZone(3220,'B');
                $this->terrain->addReinforceZone(3321,'B');
                $this->terrain->addReinforceZone(1919,'B');
                $this->terrain->addReinforceZone(1818,'B');
                $this->terrain->addReinforceZone(1719,'B');
                $this->terrain->addReinforceZone(1618,'B');
                $this->terrain->addReinforceZone(1519,'B');
                $this->terrain->addReinforceZone(1418,'B');
                $this->terrain->addReinforceZone(1218,'B');
                $this->terrain->addReinforceZone(1118,'B');
                $this->terrain->addReinforceZone(1018,'B');
                $this->terrain->addReinforceZone(918,'B');
                $this->terrain->addReinforceZone(817,'B');
                $this->terrain->addReinforceZone(718,'B');
                $this->terrain->addReinforceZone(618,'B');
                $this->terrain->addReinforceZone(519,'B');
                $this->terrain->addReinforceZone(518,'B');
                $this->terrain->addReinforceZone(617,'B');
                $this->terrain->addReinforceZone(517,'B');
                $this->terrain->addReinforceZone(417,'B');
                $this->terrain->addReinforceZone(416,'B');
                $this->terrain->addReinforceZone(317,'B');
                $this->terrain->addReinforceZone(216,'B');
                $this->terrain->addReinforceZone(116,'B');
                $this->terrain->addReinforceZone(117,'B');
                $this->terrain->addReinforceZone(217,'B');
                $this->terrain->addReinforceZone(318,'B');
                $this->terrain->addReinforceZone(418,'B');
                $this->terrain->addReinforceZone(118,'B');
                $this->terrain->addReinforceZone(218,'B');
                $this->terrain->addReinforceZone(319,'B');
                $this->terrain->addReinforceZone(419,'B');
                $this->terrain->addReinforceZone(520,'B');
                $this->terrain->addReinforceZone(619,'B');
                $this->terrain->addReinforceZone(719,'B');
                $this->terrain->addReinforceZone(818,'B');
                $this->terrain->addReinforceZone(919,'B');
                $this->terrain->addReinforceZone(1019,'B');
                $this->terrain->addReinforceZone(1119,'B');
                $this->terrain->addReinforceZone(1219,'B');
                $this->terrain->addReinforceZone(1319,'B');
                $this->terrain->addReinforceZone(1419,'B');
                $this->terrain->addReinforceZone(1520,'B');
                $this->terrain->addReinforceZone(1619,'B');
                $this->terrain->addReinforceZone(1720,'B');
                $this->terrain->addReinforceZone(1819,'B');
                $this->terrain->addReinforceZone(1920,'B');
                $this->terrain->addReinforceZone(2020,'B');
                $this->terrain->addReinforceZone(2120,'B');
                $this->terrain->addReinforceZone(2219,'B');
                $this->terrain->addReinforceZone(2320,'B');
                $this->terrain->addReinforceZone(2419,'B');
                $this->terrain->addReinforceZone(2519,'B');
                $this->terrain->addReinforceZone(2619,'B');
                $this->terrain->addReinforceZone(2720,'B');
                $this->terrain->addReinforceZone(2820,'B');
                $this->terrain->addReinforceZone(2921,'B');
                $this->terrain->addReinforceZone(3021,'B');
                $this->terrain->addReinforceZone(3121,'B');
                $this->terrain->addReinforceZone(3221,'B');
                $this->terrain->addReinforceZone(2821,'B');
                $this->terrain->addReinforceZone(2721,'B');
                $this->terrain->addReinforceZone(2621,'B');
                $this->terrain->addReinforceZone(2620,'B');
                $this->terrain->addReinforceZone(2521,'B');
                $this->terrain->addReinforceZone(2520,'B');
                $this->terrain->addReinforceZone(2420,'B');
                $this->terrain->addReinforceZone(2421,'B');
                $this->terrain->addReinforceZone(2321,'B');
                $this->terrain->addReinforceZone(2220,'B');
                $this->terrain->addReinforceZone(2221,'B');
                $this->terrain->addReinforceZone(2121,'B');
                $this->terrain->addReinforceZone(2021,'B');
                $this->terrain->addReinforceZone(1921,'B');
                $this->terrain->addReinforceZone(1820,'B');
                $this->terrain->addReinforceZone(1821,'B');
                $this->terrain->addReinforceZone(1721,'B');
                $this->terrain->addReinforceZone(1621,'B');
                $this->terrain->addReinforceZone(1620,'B');
                $this->terrain->addReinforceZone(1521,'B');
                $this->terrain->addReinforceZone(1420,'B');
                $this->terrain->addReinforceZone(1421,'B');
                $this->terrain->addReinforceZone(1321,'B');
                $this->terrain->addReinforceZone(1320,'B');
                $this->terrain->addReinforceZone(1220,'B');
                $this->terrain->addReinforceZone(1221,'B');
                $this->terrain->addReinforceZone(1121,'B');
                $this->terrain->addReinforceZone(1120,'B');
                $this->terrain->addReinforceZone(1020,'B');
                $this->terrain->addReinforceZone(1021,'B');
                $this->terrain->addReinforceZone(921,'B');
                $this->terrain->addReinforceZone(920,'B');
                $this->terrain->addReinforceZone(820,'B');
                $this->terrain->addReinforceZone(821,'B');
                $this->terrain->addReinforceZone(819,'B');
                $this->terrain->addReinforceZone(720,'B');
                $this->terrain->addReinforceZone(721,'B');
                $this->terrain->addReinforceZone(621,'B');
                $this->terrain->addReinforceZone(620,'B');
                $this->terrain->addReinforceZone(521,'B');
                $this->terrain->addReinforceZone(420,'B');
                $this->terrain->addReinforceZone(421,'B');
                $this->terrain->addReinforceZone(321,'B');
                $this->terrain->addReinforceZone(320,'B');
                $this->terrain->addReinforceZone(220,'B');
                $this->terrain->addReinforceZone(219,'B');
                $this->terrain->addReinforceZone(221,'B');
                $this->terrain->addReinforceZone(121,'B');
                $this->terrain->addReinforceZone(120,'B');
                $this->terrain->addReinforceZone(119,'B');
                $this->terrain->addTerrain(201 ,1 , "forest");
                $this->terrain->addTerrain(302 ,1 , "forest");
                $this->terrain->addTerrain(402 ,1 , "forest");
                $this->terrain->addTerrain(303 ,1 , "forest");
                $this->terrain->addTerrain(707 ,1 , "forest");
                $this->terrain->addTerrain(708 ,1 , "forest");
                $this->terrain->addTerrain(807 ,1 , "forest");
                $this->terrain->addTerrain(808 ,1 , "forest");
                $this->terrain->addTerrain(908 ,1 , "forest");
                $this->terrain->addTerrain(909 ,1 , "forest");
                $this->terrain->addTerrain(1103 ,4 , "forest");
                $this->terrain->addTerrain(402 ,4 , "forest");
                $this->terrain->addTerrain(402 ,3 , "forest");
                $this->terrain->addTerrain(302 ,2 , "forest");
                $this->terrain->addTerrain(302 ,4 , "forest");
                $this->terrain->addTerrain(707 ,2 , "forest");
                $this->terrain->addTerrain(807 ,4 , "forest");
                $this->terrain->addTerrain(807 ,3 , "forest");
                $this->terrain->addTerrain(807 ,2 , "forest");
                $this->terrain->addTerrain(908 ,4 , "forest");
                $this->terrain->addTerrain(908 ,3 , "forest");
                $this->terrain->addTerrain(909 ,4 , "forest");
                $this->terrain->addTerrain(908 ,2 , "forest");
                $this->terrain->addTerrain(1008 ,3 , "forest");
                $this->terrain->addTerrain(1008 ,1 , "forest");
                $this->terrain->addTerrain(1009 ,1 , "forest");
                $this->terrain->addTerrain(910 ,1 , "forest");
                $this->terrain->addTerrain(1009 ,3 , "forest");
                $this->terrain->addTerrain(1009 ,4 , "forest");
                $this->terrain->addTerrain(1008 ,2 , "forest");
                $this->terrain->addTerrain(1009 ,2 , "forest");
                $this->terrain->addTerrain(1110 ,4 , "forest");
                $this->terrain->addTerrain(1010 ,4 , "forest");
                $this->terrain->addTerrain(1010 ,1 , "forest");
                $this->terrain->addTerrain(1110 ,1 , "forest");
                $this->terrain->addTerrain(1210 ,4 , "forest");
                $this->terrain->addTerrain(1110 ,2 , "forest");
                $this->terrain->addTerrain(1111 ,4 , "forest");
                $this->terrain->addTerrain(1111 ,1 , "forest");
                $this->terrain->addTerrain(1210 ,1 , "forest");
                $this->terrain->addTerrain(1311 ,4 , "forest");
                $this->terrain->addTerrain(1210 ,2 , "forest");
                $this->terrain->addTerrain(1211 ,4 , "forest");
                $this->terrain->addTerrain(1111 ,2 , "forest");
                $this->terrain->addTerrain(1112 ,1 , "forest");
                $this->terrain->addTerrain(1211 ,1 , "forest");
                $this->terrain->addTerrain(1311 ,1 , "forest");
                $this->terrain->addTerrain(1311 ,3 , "forest");
                $this->terrain->addTerrain(1211 ,3 , "forest");
                $this->terrain->addTerrain(1210 ,3 , "forest");
                $this->terrain->addTerrain(1110 ,3 , "forest");
                $this->terrain->addTerrain(1008 ,4 , "forest");
                $this->terrain->addTerrain(808 ,4 , "forest");
                $this->terrain->addTerrain(909 ,2 , "forest");
                $this->terrain->addTerrain(1312 ,4 , "forest");
                $this->terrain->addTerrain(1311 ,2 , "forest");
                $this->terrain->addTerrain(1411 ,4 , "forest");
                $this->terrain->addTerrain(1411 ,1 , "forest");
                $this->terrain->addTerrain(1312 ,1 , "forest");
                $this->terrain->addTerrain(1411 ,3 , "forest");
                $this->terrain->addTerrain(1512 ,4 , "forest");
                $this->terrain->addTerrain(1411 ,2 , "forest");
                $this->terrain->addTerrain(1412 ,4 , "forest");
                $this->terrain->addTerrain(1412 ,1 , "forest");
                $this->terrain->addTerrain(1512 ,3 , "forest");
                $this->terrain->addTerrain(1512 ,1 , "forest");
                $this->terrain->addTerrain(1611 ,3 , "forest");
                $this->terrain->addTerrain(1611 ,1 , "forest");
                $this->terrain->addTerrain(1712 ,4 , "forest");
                $this->terrain->addTerrain(1712 ,1 , "forest");
                $this->terrain->addTerrain(1811 ,3 , "forest");
                $this->terrain->addTerrain(1811 ,1 , "forest");
                $this->terrain->addTerrain(1912 ,4 , "forest");
                $this->terrain->addTerrain(1912 ,1 , "forest");
                $this->terrain->addTerrain(2012 ,4 , "forest");
                $this->terrain->addTerrain(2012 ,1 , "forest");
                $this->terrain->addTerrain(2112 ,3 , "forest");
                $this->terrain->addTerrain(2112 ,1 , "forest");
                $this->terrain->addTerrain(2212 ,4 , "forest");
                $this->terrain->addTerrain(2212 ,1 , "forest");
                $this->terrain->addTerrain(2313 ,1 , "forest");
                $this->terrain->addTerrain(2313 ,4 , "forest");
                $this->terrain->addTerrain(2312 ,2 , "forest");
                $this->terrain->addTerrain(2312 ,3 , "forest");
                $this->terrain->addTerrain(2211 ,2 , "forest");
                $this->terrain->addTerrain(2211 ,3 , "forest");
                $this->terrain->addTerrain(2211 ,1 , "forest");
                $this->terrain->addTerrain(2312 ,1 , "forest");
                $this->terrain->addTerrain(2311 ,3 , "forest");
                $this->terrain->addTerrain(2311 ,2 , "forest");
                $this->terrain->addTerrain(2411 ,3 , "forest");
                $this->terrain->addTerrain(2411 ,1 , "forest");
                $this->terrain->addTerrain(2311 ,1 , "forest");
                $this->terrain->addTerrain(2411 ,4 , "forest");
                $this->terrain->addTerrain(2310 ,1 , "forest");
                $this->terrain->addTerrain(2409 ,3 , "forest");
                $this->terrain->addTerrain(2409 ,1 , "forest");
                $this->terrain->addTerrain(2409 ,1 , "road");
                $this->terrain->addTerrain(2509 ,3 , "forest");
                $this->terrain->addTerrain(2509 ,1 , "forest");
                $this->terrain->addTerrain(2609 ,4 , "forest");
                $this->terrain->addTerrain(2609 ,1 , "forest");
                $this->terrain->addTerrain(2609 ,2 , "forest");
                $this->terrain->addTerrain(2610 ,1 , "forest");
                $this->terrain->addTerrain(2610 ,3 , "forest");
                $this->terrain->addTerrain(2511 ,1 , "forest");
                $this->terrain->addTerrain(2511 ,1 , "road");
                $this->terrain->addTerrain(2511 ,3 , "forest");
                $this->terrain->addTerrain(2711 ,4 , "forest");
                $this->terrain->addTerrain(2711 ,1 , "forest");
                $this->terrain->addTerrain(2810 ,3 , "forest");
                $this->terrain->addTerrain(2810 ,1 , "forest");
                $this->terrain->addTerrain(2910 ,3 , "forest");
                $this->terrain->addTerrain(2910 ,1 , "forest");
                $this->terrain->addTerrain(3010 ,4 , "forest");
                $this->terrain->addTerrain(3010 ,1 , "forest");
                $this->terrain->addTerrain(3110 ,3 , "forest");
                $this->terrain->addTerrain(3110 ,1 , "forest");
                $this->terrain->addTerrain(3209 ,3 , "forest");
                $this->terrain->addTerrain(3109 ,2 , "forest");
                $this->terrain->addTerrain(3209 ,4 , "forest");
                $this->terrain->addTerrain(3109 ,1 , "forest");
                $this->terrain->addTerrain(3209 ,1 , "forest");
                $this->terrain->addTerrain(3208 ,1 , "forest");
                $this->terrain->addTerrain(3309 ,4 , "forest");
                $this->terrain->addTerrain(3309 ,1 , "forest");
                $this->terrain->addTerrain(3309 ,3 , "forest");
                $this->terrain->addTerrain(3208 ,2 , "forest");
                $this->terrain->addTerrain(3208 ,3 , "forest");
                $this->terrain->addTerrain(3308 ,2 , "forest");
                $this->terrain->addTerrain(3308 ,3 , "forest");
                $this->terrain->addTerrain(3308 ,1 , "forest");
                $this->terrain->addTerrain(3317 ,1 , "forest");
                $this->terrain->addTerrain(3317 ,3 , "town");
                $this->terrain->addTerrain(3317 ,4 , "forest");
                $this->terrain->addTerrain(3216 ,3 , "forest");
                $this->terrain->addTerrain(3116 ,2 , "forest");
                $this->terrain->addTerrain(3216 ,4 , "forest");
                $this->terrain->addTerrain(3216 ,4 , "road");
                $this->terrain->addTerrain(714 ,1 , "forest");
                $this->terrain->addTerrain(1103 ,3 , "town");
                $this->terrain->addTerrain(1002 ,2 , "town");
                $this->terrain->addTerrain(1706 ,4 , "town");
                $this->terrain->addTerrain(1705 ,2 , "town");
                $this->terrain->addTerrain(1705 ,3 , "town");
                $this->terrain->addTerrain(1705 ,4 , "town");
                $this->terrain->addTerrain(1604 ,2 , "town");
                $this->terrain->addTerrain(1605 ,2 , "town");
                $this->terrain->addTerrain(1706 ,3 , "town");
                $this->terrain->addTerrain(2903 ,1 , "town");
                $this->terrain->addTerrain(2902 ,2 , "town");
                $this->terrain->addTerrain(2903 ,4 , "town");
                $this->terrain->addTerrain(2903 ,3 , "town");
                $this->terrain->addTerrain(2903 ,2 , "town");
                $this->terrain->addTerrain(3003 ,4 , "town");
                $this->terrain->addTerrain(3217 ,4 , "town");
                $this->terrain->addTerrain(3216 ,2 , "town");
                $this->terrain->addTerrain(814 ,4 , "town");
                $this->terrain->addTerrain(915 ,4 , "town");
                $this->terrain->addTerrain(2904 ,1 , "forest");
                $this->terrain->addTerrain(3003 ,3 , "forest");
                $this->terrain->addTerrain(3003 ,1 , "forest");
                $this->terrain->addTerrain(2902 ,1 , "forest");
                $this->terrain->addTerrain(2902 ,3 , "forest");
                $this->terrain->addTerrain(2802 ,2 , "forest");
                $this->terrain->addTerrain(2803 ,1 , "forest");
                $this->terrain->addTerrain(2803 ,1 , "road");
                $this->terrain->addTerrain(2802 ,1 , "forest");
                $this->terrain->addTerrain(2802 ,4 , "forest");
                $this->terrain->addTerrain(2702 ,1 , "forest");
                $this->terrain->addTerrain(1707 ,4 , "forest");
                $this->terrain->addTerrain(1706 ,2 , "town");
                $this->terrain->addReinforceZone(1603,'A');
                $this->terrain->addTerrain(1603 ,1 , "redoubt");
                $this->terrain->addReinforceZone(1607,'A');
                $this->terrain->addReinforceZone(3117,'B');
                $this->terrain->addTerrain(3117 ,1 , "forest");
                $this->terrain->addReinforceZone(2517,'B');
                $this->terrain->addReinforceZone(2318,'B');
                $this->terrain->addTerrain(501 ,1 , "road");
                $this->terrain->addTerrain(601 ,4 , "road");
                $this->terrain->addTerrain(601 ,1 , "road");
                $this->terrain->addTerrain(702 ,4 , "road");
                $this->terrain->addTerrain(702 ,1 , "road");
                $this->terrain->addTerrain(802 ,4 , "road");
                $this->terrain->addTerrain(903 ,4 , "road");
                $this->terrain->addTerrain(1204 ,4 , "road");
                $this->terrain->addTerrain(1304 ,3 , "road");
                $this->terrain->addTerrain(1404 ,4 , "road");
                $this->terrain->addTerrain(1404 ,4 , "forest");
                $this->terrain->addTerrain(1505 ,4 , "road");
                $this->terrain->addTerrain(1505 ,4 , "redoubt");
                $this->terrain->addTerrain(1906 ,4 , "road");
                $this->terrain->addTerrain(2006 ,4 , "road");
                $this->terrain->addTerrain(2006 ,4 , "redoubt");
                $this->terrain->addTerrain(2107 ,4 , "road");
                $this->terrain->addTerrain(2207 ,4 , "road");
                $this->terrain->addTerrain(2308 ,4 , "road");
                $this->terrain->addTerrain(2408 ,4 , "road");
                $this->terrain->addTerrain(2408 ,1 , "road");
                $this->terrain->addTerrain(2408 ,2 , "road");
                $this->terrain->addTerrain(2510 ,4 , "road");
                $this->terrain->addTerrain(2510 ,1 , "road");
                $this->terrain->addTerrain(2510 ,2 , "road");
                $this->terrain->addTerrain(2611 ,4 , "road");
                $this->terrain->addTerrain(2611 ,1 , "road");
                $this->terrain->addTerrain(2611 ,2 , "road");
                $this->terrain->addTerrain(2612 ,1 , "road");
                $this->terrain->addTerrain(2613 ,1 , "road");
                $this->terrain->addTerrain(2714 ,4 , "road");
                $this->terrain->addTerrain(2814 ,4 , "road");
                $this->terrain->addTerrain(2915 ,4 , "road");
                $this->terrain->addTerrain(3015 ,4 , "road");
                $this->terrain->addTerrain(3116 ,4 , "road");
                $this->terrain->addTerrain(1905 ,3 , "road");
                $this->terrain->addTerrain(2004 ,3 , "road");
                $this->terrain->addTerrain(2004 ,3 , "redoubt");
                $this->terrain->addTerrain(2104 ,3 , "road");
                $this->terrain->addTerrain(2204 ,4 , "road");
                $this->terrain->addTerrain(2304 ,3 , "road");
                $this->terrain->addTerrain(2404 ,4 , "road");
                $this->terrain->addTerrain(2404 ,1 , "road");
                $this->terrain->addTerrain(2504 ,3 , "road");
                $this->terrain->addTerrain(2504 ,1 , "road");
                $this->terrain->addTerrain(2603 ,3 , "road");
                $this->terrain->addTerrain(2603 ,1 , "road");
                $this->terrain->addTerrain(2703 ,3 , "road");
                $this->terrain->addTerrain(2703 ,1 , "road");
                $this->terrain->addTerrain(2803 ,4 , "road");
                $this->terrain->addTerrain(3002 ,1 , "road");
                $this->terrain->addTerrain(3102 ,3 , "road");
                $this->terrain->addTerrain(3102 ,1 , "road");
                $this->terrain->addTerrain(3202 ,4 , "road");
                $this->terrain->addTerrain(3202 ,1 , "road");
                $this->terrain->addTerrain(3302 ,3 , "road");
                $this->terrain->addTerrain(213 ,4 , "road");
                $this->terrain->addTerrain(314 ,4 , "road");
                $this->terrain->addTerrain(413 ,3 , "road");
                $this->terrain->addTerrain(514 ,4 , "road");
                $this->terrain->addTerrain(614 ,4 , "road");
                $this->terrain->addTerrain(715 ,4 , "road");
                $this->terrain->addTerrain(1015 ,4 , "road");
                $this->terrain->addTerrain(1115 ,3 , "road");
                $this->terrain->addTerrain(1215 ,4 , "road");
                $this->terrain->addTerrain(1316 ,4 , "road");
                $this->terrain->addTerrain(1415 ,3 , "road");
                $this->terrain->addTerrain(1516 ,4 , "road");
                $this->terrain->addTerrain(1615 ,3 , "road");
                $this->terrain->addTerrain(1716 ,4 , "road");
                $this->terrain->addTerrain(1815 ,3 , "road");
                $this->terrain->addTerrain(1916 ,4 , "road");
                $this->terrain->addTerrain(2015 ,3 , "road");
                $this->terrain->addTerrain(2115 ,3 , "road");
                $this->terrain->addTerrain(2214 ,3 , "road");
                $this->terrain->addTerrain(2314 ,3 , "road");
                $this->terrain->addTerrain(2414 ,4 , "road");
                $this->terrain->addTerrain(2514 ,3 , "road");
                $this->terrain->addTerrain(2614 ,4 , "road");
                $this->terrain->addTerrain(2714 ,3 , "road");
                $this->terrain->addTerrain(2612 ,2 , "road");
                $this->terrain->addTerrain(1508 ,3 , "redoubt");
                $this->terrain->addTerrain(1508 ,4 , "redoubt");
                $this->terrain->addTerrain(1507 ,3 , "redoubt");
                $this->terrain->addTerrain(1507 ,4 , "redoubt");
                $this->terrain->addTerrain(1506 ,3 , "redoubt");
                $this->terrain->addTerrain(1506 ,4 , "redoubt");
                $this->terrain->addTerrain(1505 ,3 , "redoubt");
                $this->terrain->addTerrain(1504 ,3 , "redoubt");
                $this->terrain->addTerrain(1504 ,4 , "redoubt");
                $this->terrain->addTerrain(1503 ,2 , "redoubt");
                $this->terrain->addTerrain(1603 ,4 , "redoubt");
                $this->terrain->addTerrain(1703 ,4 , "redoubt");
                $this->terrain->addTerrain(1702 ,2 , "redoubt");
                $this->terrain->addTerrain(1802 ,3 , "redoubt");
                $this->terrain->addTerrain(1802 ,2 , "redoubt");
                $this->terrain->addTerrain(1903 ,3 , "redoubt");
                $this->terrain->addTerrain(1903 ,2 , "redoubt");
                $this->terrain->addTerrain(2003 ,3 , "redoubt");
                $this->terrain->addTerrain(2004 ,4 , "redoubt");
                $this->terrain->addTerrain(2005 ,4 , "redoubt");
                $this->terrain->addTerrain(2005 ,3 , "redoubt");
                $this->terrain->addTerrain(1906 ,2 , "redoubt");
                $this->terrain->addTerrain(1907 ,4 , "redoubt");
                $this->terrain->addTerrain(1907 ,3 , "redoubt");
                $this->terrain->addTerrain(1908 ,4 , "redoubt");
                $this->terrain->addTerrain(1807 ,2 , "redoubt");
                $this->terrain->addTerrain(1808 ,4 , "redoubt");
                $this->terrain->addTerrain(1708 ,2 , "redoubt");
                $this->terrain->addTerrain(1709 ,4 , "redoubt");
                $this->terrain->addTerrain(1608 ,2 , "redoubt");
                $this->terrain->addTerrain(1608 ,3 , "redoubt");
                $this->terrain->addTerrain(1508 ,2 , "redoubt");
            }else{

            }



            $this->roadHex = $specialHexA;
            $specialHexes = [];
            foreach ($specialHexA as $specialHexId) {
                $specialHexes[$specialHexId] = BRITISH_FORCE;
            }
            foreach ($specialHexB as $specialHexId) {
                $specialHexes[$specialHexId] = SIKH_FORCE;
            }
            $this->mapData->setSpecialHexes($specialHexes);

            $this->terrain->addReinforceZone(113,'A');
            $this->terrain->addReinforceZone(501,'B');


            // end terrain data ----------------------------------------

        }
    }
}