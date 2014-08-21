<?php
set_include_path(__DIR__ . "/Meanee1843" . PATH_SEPARATOR . get_include_path());
define("BRITISH_FORCE", 1);
define("BELUCHI_FORCE", 2);

global $force_name;
$force_name[BRITISH_FORCE] = "British";
$force_name[BELUCHI_FORCE] = "Beluchi";

require_once "IndiaCore.php";

class Meanee1843 extends IndiaCore
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


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "Meanee1843Header.php";

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
        $deployOne = $playerTwo = "Beluchi";
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
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }



    public function init()
    {

        $artRange = 3;


            /* Beluchi */
            for ($i = 0; $i < 9; $i++) {
                $this->force->addUnit("infantry-1", BELUCHI_FORCE, "deployBox", "SikhInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Beluchi", false, 'infantry');
            }
            for ($i = 0; $i < 20; $i++) {
                $this->force->addUnit("infantry-1", BELUCHI_FORCE, "deployBox", "SikhCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Beluchi", false, 'cavalry');
            }
            for ($i = 0; $i < 2; $i++) {
                $this->force->addUnit("infantry-1", BELUCHI_FORCE, "deployBox", "SikhArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, 2, "Beluchi", false, 'artillery');
            }

             /* British */
            for ($i = 0; $i < 3; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritInfBadge.png", 7, 7, 4, true, STATUS_CAN_DEPLOY, "B", 1, 1, "British", false, 'infantry');
            }
            for ($i = 0; $i < 5; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeInfBadge.png", 6, 6, 4, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Native", false, 'infantry');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritCavBadge.png", 7, 7, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "British", false, 'cavalry');
            }
            for ($i = 0; $i < 3; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeCavBadge.png", 6, 6, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Native", false, 'cavalry');
            }
             for ($i = 0; $i < 2; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritArtBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 4, "British", false, 'artillery');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritHorArtBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "B", 1, 3, "British", false, 'horseartillery');
            }

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->game = $data->game;
            $this->genTerrain = false;
            $this->victory = new Victory("Mollwitz/Meanee1843/meanee1843VictoryCore.php", $data);
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
            $this->victory = new Victory("Mollwitz/Meanee1843/meanee1843VictoryCore.php");


//            $this->mapData->setData(28, 19, "js/Meanee1843NoLOCSmall.png");
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
//                $this->mapViewer[$player]->setData(52.79999999999998 , 84.17766924784743, // originX, originY
//                    28.059223082615812, 28.059223082615812, // top hexagon height, bottom hexagon height
//                    16.2, 32.4// hexagon edge width, hexagon center width
//                );
//            }

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

//            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
//            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("road", "road", "r", .75, 0, 0, false);
//            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
//            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
//            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
//            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
//            $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
//            $this->terrain->addNatAltEntranceCost('forest','Beluchi', 'infantry', 1);
//            $this->terrain->addAltEntranceCost('forest', 'horseartillery', 4);
//            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
//            $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
//            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("redoubt", "redoubt", "d", 0, 2, 0, false);
//            $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);
//            $this->terrain->addTerrainFeature("wadi", "wadi", "v", 0, 2, 0, false);
//            $this->terrain->addAltEntranceCost('swamp','artillery','blocked');


//            for ($col = 100; $col <= 3300; $col += 100) {
//                for ($row = 1; $row <= 21; $row++) {
//                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");
//
//                }
//            }
//            $specialHexA = [];
//            $specialHexB = [];
//            $this->terrain->addReinforceZone(1303,'A');
//            $this->terrain->addReinforceZone(1402,'A');
//            $this->terrain->addReinforceZone(1503,'A');
//            $this->terrain->addReinforceZone(1403,'A');
//            $this->terrain->addReinforceZone(1504,'A');
//            $this->terrain->addReinforceZone(1603,'A');
//            $this->terrain->addReinforceZone(1604,'A');
//            $this->terrain->addReinforceZone(1704,'A');
//            $this->terrain->addReinforceZone(1804,'A');
//            $this->terrain->addReinforceZone(1705,'A');
//            $this->terrain->addReinforceZone(1506,'A');
//            $this->terrain->addReinforceZone(1606,'A');
//            $this->terrain->addReinforceZone(1706,'A');
//            $this->terrain->addTerrain(1706 ,1 , "town");
//            $this->terrain->addReinforceZone(1607,'A');
//            $this->terrain->addReinforceZone(1608,'A');
//            $this->terrain->addTerrain(1608 ,1 , "road");
//            $this->terrain->addReinforceZone(1609,'A');
//            $this->terrain->addTerrain(1609 ,1 , "forest");
//            $this->terrain->addTerrain(1609 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1610,'A');
//            $this->terrain->addTerrain(1610 ,1 , "forest");
//            $this->terrain->addTerrain(1610 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1511,'A');
//            $this->terrain->addTerrain(1511 ,1 , "forest");
//            $this->terrain->addTerrain(1511 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1512,'A');
//            $this->terrain->addTerrain(1512 ,1 , "forest");
//            $this->terrain->addTerrain(1512 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1412,'A');
//            $this->terrain->addTerrain(1412 ,1 , "forest");
//            $this->terrain->addTerrain(1412 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1413,'A');
//            $this->terrain->addTerrain(1413 ,1 , "forest");
//            $this->terrain->addTerrain(1413 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1314,'A');
//            $this->terrain->addTerrain(1314 ,1 , "forest");
//            $this->terrain->addTerrain(1314 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1315,'A');
//            $this->terrain->addTerrain(1315 ,1 , "forest");
//            $this->terrain->addTerrain(1315 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1414,'A');
//            $this->terrain->addTerrain(1414 ,1 , "forest");
//            $this->terrain->addReinforceZone(1515,'A');
//            $this->terrain->addTerrain(1515 ,1 , "forest");
//            $this->terrain->addReinforceZone(1514,'A');
//            $this->terrain->addTerrain(1514 ,1 , "forest");
//            $this->terrain->addReinforceZone(1513,'A');
//            $this->terrain->addTerrain(1513 ,1 , "forest");
//            $this->terrain->addReinforceZone(1615,'A');
//            $this->terrain->addTerrain(1615 ,1 , "forest");
//            $this->terrain->addReinforceZone(1614,'A');
//            $this->terrain->addTerrain(1614 ,1 , "forest");
//            $this->terrain->addReinforceZone(1613,'A');
//            $this->terrain->addTerrain(1613 ,1 , "forest");
//            $this->terrain->addReinforceZone(1612,'A');
//            $this->terrain->addTerrain(1612 ,1 , "forest");
//            $this->terrain->addReinforceZone(1611,'A');
//            $this->terrain->addTerrain(1611 ,1 , "forest");
//            $this->terrain->addReinforceZone(1716,'A');
//            $this->terrain->addTerrain(1716 ,1 , "forest");
//            $this->terrain->addReinforceZone(1715,'A');
//            $this->terrain->addTerrain(1715 ,1 , "forest");
//            $this->terrain->addReinforceZone(1714,'A');
//            $this->terrain->addTerrain(1714 ,1 , "forest");
//            $this->terrain->addReinforceZone(1713,'A');
//            $this->terrain->addTerrain(1713 ,1 , "forest");
//            $this->terrain->addReinforceZone(1712,'A');
//            $this->terrain->addTerrain(1712 ,1 , "forest");
//            $this->terrain->addReinforceZone(1711,'A');
//            $this->terrain->addTerrain(1711 ,1 , "forest");
//            $this->terrain->addReinforceZone(1710,'A');
//            $this->terrain->addTerrain(1710 ,2 , "forest");
//            $this->terrain->addReinforceZone(1710,'A');
//            $this->terrain->addTerrain(1710 ,1 , "forest");
//            $this->terrain->addReinforceZone(1709,'A');
//            $this->terrain->addTerrain(1709 ,1 , "forest");
//            $this->terrain->addTerrain(1709 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1708,'A');
//            $this->terrain->addTerrain(1708 ,1 , "road");
//            $this->terrain->addReinforceZone(1707,'A');
//            $this->terrain->addTerrain(1707 ,1 , "road");
//            $this->terrain->addReinforceZone(1805,'A');
//            $this->terrain->addReinforceZone(1806,'A');
//            $this->terrain->addReinforceZone(1807,'A');
//            $this->terrain->addReinforceZone(1808,'A');
//            $this->terrain->addTerrain(1808 ,1 , "road");
//            $this->terrain->addReinforceZone(1809,'A');
//            $this->terrain->addTerrain(1809 ,1 , "forest");
//            $this->terrain->addTerrain(1809 ,1 , "redoubt");
//            $this->terrain->addReinforceZone(1810,'A');
//            $this->terrain->addTerrain(1810 ,1 , "forest");
//            $this->terrain->addReinforceZone(1811,'A');
//            $this->terrain->addTerrain(1811 ,1 , "forest");
//            $this->terrain->addReinforceZone(1812,'A');
//            $this->terrain->addTerrain(1812 ,1 , "forest");
//            $this->terrain->addReinforceZone(1813,'A');
//            $this->terrain->addTerrain(1813 ,1 , "forest");
//            $this->terrain->addReinforceZone(1814,'A');
//            $this->terrain->addTerrain(1814 ,1 , "forest");
//            $this->terrain->addReinforceZone(1815,'A');
//            $this->terrain->addTerrain(1815 ,1 , "forest");
//            $this->terrain->addReinforceZone(1916,'A');
//            $this->terrain->addTerrain(1916 ,1 , "forest");
//            $this->terrain->addReinforceZone(1915,'A');
//            $this->terrain->addTerrain(1915 ,1 , "forest");
//            $this->terrain->addReinforceZone(1914,'A');
//            $this->terrain->addTerrain(1914 ,1 , "forest");
//            $this->terrain->addReinforceZone(1913,'A');
//            $this->terrain->addTerrain(1913 ,1 , "forest");
//            $this->terrain->addReinforceZone(1912,'A');
//            $this->terrain->addTerrain(1912 ,1 , "forest");
//            $this->terrain->addReinforceZone(1911,'A');
//            $this->terrain->addTerrain(1911 ,1 , "forest");
//            $this->terrain->addReinforceZone(1910,'A');
//            $this->terrain->addReinforceZone(1909,'A');
//            $this->terrain->addTerrain(1909 ,1 , "road");
//            $this->terrain->addReinforceZone(1908,'A');
//            $this->terrain->addReinforceZone(1907,'A');
//            $this->terrain->addReinforceZone(1906,'A');
//            $this->terrain->addReinforceZone(2006,'A');
//            $this->terrain->addReinforceZone(2007,'A');
//            $this->terrain->addReinforceZone(2008,'A');
//            $this->terrain->addReinforceZone(2009,'A');
//            $this->terrain->addTerrain(2009 ,1 , "road");
//            $this->terrain->addReinforceZone(2010,'A');
//            $this->terrain->addReinforceZone(2011,'A');
//            $this->terrain->addReinforceZone(2012,'A');
//            $this->terrain->addReinforceZone(2013,'A');
//            $this->terrain->addTerrain(2013 ,1 , "forest");
//            $this->terrain->addReinforceZone(2014,'A');
//            $this->terrain->addTerrain(2014 ,1 , "forest");
//            $this->terrain->addReinforceZone(2015,'A');
//            $this->terrain->addTerrain(2015 ,1 , "forest");
//            $this->terrain->addReinforceZone(2110,'A');
//            $this->terrain->addReinforceZone(2109,'A');
//            $this->terrain->addTerrain(2109 ,1 , "road");
//            $this->terrain->addReinforceZone(2108,'A');
//            $this->terrain->addReinforceZone(2107,'A');
//            $this->terrain->addReinforceZone(108,'B');
//            $this->terrain->addReinforceZone(208,'B');
//            $this->terrain->addReinforceZone(309,'B');
//            $this->terrain->addReinforceZone(409,'B');
//            $this->terrain->addReinforceZone(510,'B');
//            $this->terrain->addReinforceZone(610,'B');
//            $this->terrain->addReinforceZone(611,'B');
//            $this->terrain->addReinforceZone(612,'B');
//            $this->terrain->addTerrain(612 ,1 , "road");
//            $this->terrain->addReinforceZone(513,'B');
//            $this->terrain->addReinforceZone(412,'B');
//            $this->terrain->addTerrain(412 ,1 , "road");
//            $this->terrain->addReinforceZone(313,'B');
//            $this->terrain->addReinforceZone(212,'B');
//            $this->terrain->addTerrain(212 ,1 , "road");
//            $this->terrain->addReinforceZone(113,'B');
//            $this->terrain->addReinforceZone(112,'B');
//            $this->terrain->addTerrain(112 ,1 , "town");
//            $this->terrain->addReinforceZone(211,'B');
//            $this->terrain->addReinforceZone(312,'B');
//            $this->terrain->addTerrain(312 ,1 , "road");
//            $this->terrain->addReinforceZone(411,'B');
//            $this->terrain->addReinforceZone(512,'B');
//            $this->terrain->addTerrain(512 ,1 , "road");
//            $this->terrain->addReinforceZone(511,'B');
//            $this->terrain->addReinforceZone(410,'B');
//            $this->terrain->addReinforceZone(311,'B');
//            $this->terrain->addReinforceZone(210,'B');
//            $this->terrain->addReinforceZone(111,'B');
//            $this->terrain->addReinforceZone(110,'B');
//            $this->terrain->addReinforceZone(109,'B');
//            $this->terrain->addReinforceZone(209,'B');
//            $this->terrain->addReinforceZone(310,'B');
//            $this->terrain->addReinforceZone(712,'B');
//            $this->terrain->addTerrain(712 ,1 , "road");
//            $this->terrain->addReinforceZone(811,'B');
//            $this->terrain->addTerrain(811 ,1 , "road");
//            $this->terrain->addReinforceZone(911,'B');
//            $this->terrain->addTerrain(911 ,1 , "road");
//            $this->terrain->addReinforceZone(1012,'A');
//            $this->terrain->addTerrain(1012 ,1 , "town");
//            $this->terrain->addTerrain(2609 ,1 , "town");
//            $this->terrain->addTerrain(701 ,1 , "road");
//            $this->terrain->addTerrain(801 ,4 , "road");
//            $this->terrain->addTerrain(801 ,1 , "road");
//            $this->terrain->addTerrain(902 ,4 , "road");
//            $this->terrain->addTerrain(902 ,1 , "road");
//            $this->terrain->addTerrain(1002 ,4 , "road");
//            $this->terrain->addTerrain(1002 ,1 , "road");
//            $this->terrain->addTerrain(1103 ,4 , "road");
//            $this->terrain->addTerrain(1103 ,1 , "road");
//            $this->terrain->addTerrain(1203 ,4 , "road");
//            $this->terrain->addTerrain(1203 ,1 , "road");
//            $this->terrain->addTerrain(1304 ,4 , "road");
//            $this->terrain->addTerrain(1304 ,1 , "road");
//            $this->terrain->addTerrain(1404 ,4 , "road");
//            $this->terrain->addTerrain(1404 ,1 , "road");
//            $this->terrain->addTerrain(1505 ,4 , "road");
//            $this->terrain->addTerrain(1605 ,1 , "road");
//            $this->terrain->addTerrain(1706 ,4 , "road");
//            $this->terrain->addTerrain(1706 ,2 , "road");
//            $this->terrain->addTerrain(1707 ,2 , "road");
//            $this->terrain->addTerrain(1808 ,4 , "road");
//            $this->terrain->addTerrain(2009 ,4 , "road");
//            $this->terrain->addTerrain(2109 ,3 , "road");
//            $this->terrain->addTerrain(2209 ,4 , "road");
//            $this->terrain->addTerrain(2209 ,1 , "road");
//            $this->terrain->addTerrain(2309 ,3 , "road");
//            $this->terrain->addTerrain(2309 ,1 , "road");
//            $this->terrain->addTerrain(2409 ,4 , "road");
//            $this->terrain->addTerrain(2409 ,1 , "road");
//            $this->terrain->addTerrain(2510 ,4 , "road");
//            $this->terrain->addTerrain(2510 ,1 , "road");
//            $this->terrain->addTerrain(2609 ,3 , "road");
//            $this->terrain->addTerrain(2710 ,4 , "road");
//            $this->terrain->addTerrain(2710 ,1 , "road");
//            $this->terrain->addTerrain(2810 ,4 , "road");
//            $this->terrain->addTerrain(2810 ,1 , "road");
//            $this->terrain->addTerrain(1708 ,3 , "road");
//            $this->terrain->addTerrain(1608 ,3 , "road");
//            $this->terrain->addTerrain(1509 ,1 , "road");
//            $this->terrain->addTerrain(1509 ,3 , "road");
//            $this->terrain->addTerrain(1409 ,1 , "road");
//            $this->terrain->addTerrain(1409 ,4 , "road");
//            $this->terrain->addTerrain(1309 ,1 , "road");
//            $this->terrain->addTerrain(1309 ,3 , "road");
//            $this->terrain->addTerrain(1209 ,1 , "road");
//            $this->terrain->addTerrain(1209 ,3 , "road");
//            $this->terrain->addTerrain(1110 ,1 , "road");
//            $this->terrain->addTerrain(1110 ,3 , "road");
//            $this->terrain->addTerrain(1010 ,1 , "road");
//            $this->terrain->addTerrain(1010 ,3 , "road");
//            $this->terrain->addTerrain(911 ,3 , "road");
//            $this->terrain->addTerrain(811 ,3 , "road");
//            $this->terrain->addTerrain(712 ,3 , "road");
//            $this->terrain->addTerrain(612 ,4 , "road");
//            $this->terrain->addTerrain(612 ,4 , "wadi");
//            $this->terrain->addTerrain(512 ,3 , "road");
//            $this->terrain->addTerrain(412 ,4 , "road");
//            $this->terrain->addTerrain(312 ,3 , "road");
//            $this->terrain->addTerrain(212 ,4 , "road");
//            $this->terrain->addTerrain(113 ,2 , "wadi");
//            $this->terrain->addTerrain(213 ,4 , "wadi");
//            $this->terrain->addTerrain(212 ,2 , "wadi");
//            $this->terrain->addTerrain(313 ,3 , "wadi");
//            $this->terrain->addTerrain(313 ,2 , "wadi");
//            $this->terrain->addTerrain(413 ,4 , "wadi");
//            $this->terrain->addTerrain(412 ,2 , "wadi");
//            $this->terrain->addTerrain(513 ,3 , "wadi");
//            $this->terrain->addTerrain(513 ,2 , "wadi");
//            $this->terrain->addTerrain(613 ,4 , "wadi");
//            $this->terrain->addTerrain(612 ,2 , "wadi");
//            $this->terrain->addTerrain(713 ,3 , "wadi");
//            $this->terrain->addTerrain(713 ,2 , "wadi");
//            $this->terrain->addTerrain(813 ,4 , "wadi");
//            $this->terrain->addTerrain(812 ,2 , "wadi");
//            $this->terrain->addTerrain(913 ,3 , "wadi");
//            $this->terrain->addTerrain(913 ,2 , "wadi");
//            $this->terrain->addTerrain(1013 ,3 , "wadi");
//            $this->terrain->addTerrain(1013 ,2 , "wadi");
//            $this->terrain->addTerrain(1114 ,3 , "wadi");
//            $this->terrain->addTerrain(1114 ,2 , "wadi");
//            $this->terrain->addTerrain(1214 ,3 , "wadi");
//            $this->terrain->addTerrain(1214 ,2 , "wadi");
//            $this->terrain->addTerrain(1315 ,3 , "wadi");
//            $this->terrain->addTerrain(1315 ,3 , "forest");
//            $this->terrain->addTerrain(1315 ,2 , "wadi");
//            $this->terrain->addTerrain(1315 ,2 , "forest");
//            $this->terrain->addTerrain(1415 ,4 , "wadi");
//            $this->terrain->addTerrain(1415 ,4 , "forest");
//            $this->terrain->addTerrain(1414 ,2 , "wadi");
//            $this->terrain->addTerrain(1414 ,2 , "forest");
//            $this->terrain->addTerrain(1515 ,3 , "wadi");
//            $this->terrain->addTerrain(1515 ,3 , "forest");
//            $this->terrain->addTerrain(1515 ,2 , "wadi");
//            $this->terrain->addTerrain(1515 ,2 , "forest");
//            $this->terrain->addTerrain(1615 ,3 , "wadi");
//            $this->terrain->addTerrain(1615 ,3 , "forest");
//            $this->terrain->addTerrain(1615 ,2 , "wadi");
//            $this->terrain->addTerrain(1615 ,2 , "forest");
//            $this->terrain->addTerrain(1716 ,3 , "wadi");
//            $this->terrain->addTerrain(1716 ,3 , "forest");
//            $this->terrain->addTerrain(1716 ,2 , "wadi");
//            $this->terrain->addTerrain(1716 ,2 , "forest");
//            $this->terrain->addTerrain(1816 ,4 , "wadi");
//            $this->terrain->addTerrain(1816 ,4 , "forest");
//            $this->terrain->addTerrain(1815 ,2 , "wadi");
//            $this->terrain->addTerrain(1815 ,2 , "forest");
//            $this->terrain->addTerrain(1916 ,3 , "wadi");
//            $this->terrain->addTerrain(1916 ,3 , "forest");
//            $this->terrain->addTerrain(1916 ,2 , "wadi");
//            $this->terrain->addTerrain(1916 ,2 , "forest");
//            $this->terrain->addTerrain(2016 ,4 , "wadi");
//            $this->terrain->addTerrain(2016 ,4 , "forest");
//            $this->terrain->addTerrain(2015 ,2 , "wadi");
//            $this->terrain->addTerrain(2015 ,2 , "forest");
//            $this->terrain->addTerrain(2116 ,4 , "wadi");
//            $this->terrain->addTerrain(2115 ,3 , "wadi");
//            $this->terrain->addTerrain(2115 ,4 , "wadi");
//            $this->terrain->addTerrain(2114 ,3 , "wadi");
//            $this->terrain->addTerrain(2114 ,4 , "wadi");
//            $this->terrain->addTerrain(2113 ,3 , "wadi");
//            $this->terrain->addTerrain(2012 ,2 , "wadi");
//            $this->terrain->addTerrain(2012 ,3 , "wadi");
//            $this->terrain->addTerrain(2012 ,4 , "wadi");
//            $this->terrain->addTerrain(2011 ,3 , "wadi");
//            $this->terrain->addTerrain(2011 ,4 , "wadi");
//            $this->terrain->addTerrain(2010 ,3 , "wadi");
//            $this->terrain->addTerrain(1910 ,2 , "wadi");
//            $this->terrain->addTerrain(1910 ,3 , "wadi");
//            $this->terrain->addTerrain(1910 ,4 , "wadi");
//            $this->terrain->addTerrain(1909 ,3 , "wadi");
//            $this->terrain->addTerrain(1909 ,4 , "wadi");
//            $this->terrain->addTerrain(1908 ,3 , "wadi");
//            $this->terrain->addTerrain(1908 ,4 , "wadi");
//            $this->terrain->addTerrain(1907 ,3 , "wadi");
//            $this->terrain->addTerrain(1907 ,4 , "wadi");
//            $this->terrain->addTerrain(1906 ,2 , "wadi");
//            $this->terrain->addTerrain(2006 ,4 , "wadi");
//            $this->terrain->addTerrain(2005 ,2 , "wadi");
//            $this->terrain->addTerrain(2106 ,4 , "wadi");
//            $this->terrain->addTerrain(2105 ,2 , "wadi");
//            $this->terrain->addTerrain(2205 ,4 , "wadi");
//            $this->terrain->addTerrain(2204 ,2 , "wadi");
//            $this->terrain->addTerrain(2305 ,3 , "wadi");
//            $this->terrain->addTerrain(2305 ,2 , "wadi");
//            $this->terrain->addTerrain(2405 ,3 , "wadi");
//            $this->terrain->addTerrain(2406 ,4 , "wadi");
//            $this->terrain->addTerrain(2406 ,3 , "wadi");
//            $this->terrain->addTerrain(2407 ,4 , "wadi");
//            $this->terrain->addTerrain(2407 ,3 , "wadi");
//            $this->terrain->addTerrain(2407 ,2 , "wadi");
//            $this->terrain->addTerrain(2508 ,3 , "wadi");
//            $this->terrain->addTerrain(2508 ,2 , "wadi");
//            $this->terrain->addTerrain(2608 ,3 , "wadi");
//            $this->terrain->addTerrain(2608 ,2 , "wadi");
//            $this->terrain->addTerrain(2709 ,4 , "wadi");
//            $this->terrain->addTerrain(2708 ,2 , "wadi");
//            $this->terrain->addTerrain(2808 ,3 , "wadi");
//            $this->terrain->addTerrain(2808 ,2 , "wadi");
//            $this->terrain->addTerrain(1906 ,3 , "wadi");
//            $this->terrain->addTerrain(1805 ,2 , "wadi");
//            $this->terrain->addTerrain(1805 ,3 , "wadi");
//            $this->terrain->addTerrain(1705 ,2 , "wadi");
//            $this->terrain->addTerrain(1705 ,3 , "wadi");
//            $this->terrain->addTerrain(1604 ,2 , "wadi");
//            $this->terrain->addTerrain(1605 ,4 , "wadi");
//            $this->terrain->addTerrain(1505 ,2 , "wadi");
//            $this->terrain->addTerrain(1506 ,4 , "wadi");
//            $this->terrain->addTerrain(1405 ,2 , "wadi");
//            $this->terrain->addTerrain(1406 ,4 , "wadi");
//            $this->terrain->addTerrain(1306 ,2 , "wadi");
//            $this->terrain->addTerrain(1307 ,4 , "wadi");
//            $this->terrain->addTerrain(1206 ,2 , "wadi");
//            $this->terrain->addTerrain(1207 ,4 , "wadi");
//            $this->terrain->addTerrain(1107 ,2 , "wadi");
//            $this->terrain->addTerrain(1108 ,4 , "wadi");
//            $this->terrain->addTerrain(1007 ,2 , "wadi");
//            $this->terrain->addTerrain(1008 ,4 , "wadi");
//            $this->terrain->addTerrain(1008 ,3 , "wadi");
//            $this->terrain->addTerrain(1009 ,4 , "wadi");
//            $this->terrain->addTerrain(909 ,2 , "wadi");
//            $this->terrain->addTerrain(910 ,4 , "wadi");
//            $this->terrain->addTerrain(809 ,2 , "wadi");
//            $this->terrain->addTerrain(810 ,4 , "wadi");
//            $this->terrain->addTerrain(710 ,2 , "wadi");
//            $this->terrain->addTerrain(711 ,4 , "wadi");
//            $this->terrain->addTerrain(711 ,3 , "wadi");
//            $this->terrain->addTerrain(712 ,4 , "wadi");
//            $this->terrain->addTerrain(611 ,2 , "wadi");
//            $this->terrain->addTerrain(612 ,3 , "wadi");
//            $this->terrain->addTerrain(1903 ,1 , "forest");
//            $this->terrain->addTerrain(1903 ,2 , "forest");
//            $this->terrain->addTerrain(1904 ,1 , "forest");
//            $this->terrain->addTerrain(1904 ,2 , "forest");
//            $this->terrain->addTerrain(1905 ,1 , "forest");
//            $this->terrain->addTerrain(2004 ,3 , "forest");
//            $this->terrain->addTerrain(2004 ,4 , "forest");
//            $this->terrain->addTerrain(2003 ,3 , "forest");
//            $this->terrain->addTerrain(2003 ,4 , "forest");
//            $this->terrain->addTerrain(2003 ,1 , "forest");
//            $this->terrain->addTerrain(2004 ,1 , "forest");
//            $this->terrain->addTerrain(2003 ,2 , "forest");
//            $this->terrain->addTerrain(2404 ,1 , "forest");
//            $this->terrain->addTerrain(2404 ,2 , "forest");
//            $this->terrain->addTerrain(2405 ,1 , "forest");
//            $this->terrain->addTerrain(2405 ,2 , "forest");
//            $this->terrain->addTerrain(2406 ,1 , "forest");
//            $this->terrain->addTerrain(2406 ,2 , "forest");
//            $this->terrain->addTerrain(2407 ,1 , "forest");
//            $this->terrain->addTerrain(2508 ,1 , "forest");
//            $this->terrain->addTerrain(2507 ,2 , "forest");
//            $this->terrain->addTerrain(2507 ,3 , "forest");
//            $this->terrain->addTerrain(2507 ,4 , "forest");
//            $this->terrain->addTerrain(2506 ,3 , "forest");
//            $this->terrain->addTerrain(2506 ,4 , "forest");
//            $this->terrain->addTerrain(2505 ,3 , "forest");
//            $this->terrain->addTerrain(2505 ,4 , "forest");
//            $this->terrain->addTerrain(2504 ,3 , "forest");
//            $this->terrain->addTerrain(2504 ,1 , "forest");
//            $this->terrain->addTerrain(2504 ,2 , "forest");
//            $this->terrain->addTerrain(2505 ,1 , "forest");
//            $this->terrain->addTerrain(2505 ,2 , "forest");
//            $this->terrain->addTerrain(2506 ,1 , "forest");
//            $this->terrain->addTerrain(2506 ,2 , "forest");
//            $this->terrain->addTerrain(2507 ,1 , "forest");
//            $this->terrain->addTerrain(2608 ,1 , "forest");
//            $this->terrain->addTerrain(2608 ,4 , "forest");
//            $this->terrain->addTerrain(2607 ,3 , "forest");
//            $this->terrain->addTerrain(2607 ,4 , "forest");
//            $this->terrain->addTerrain(2606 ,3 , "forest");
//            $this->terrain->addTerrain(2606 ,4 , "forest");
//            $this->terrain->addTerrain(2605 ,3 , "forest");
//            $this->terrain->addTerrain(2605 ,4 , "forest");
//            $this->terrain->addTerrain(2604 ,3 , "forest");
//            $this->terrain->addTerrain(2604 ,4 , "forest");
//            $this->terrain->addTerrain(2603 ,3 , "forest");
//            $this->terrain->addTerrain(2603 ,1 , "forest");
//            $this->terrain->addTerrain(2603 ,2 , "forest");
//            $this->terrain->addTerrain(2604 ,1 , "forest");
//            $this->terrain->addTerrain(2604 ,2 , "forest");
//            $this->terrain->addTerrain(2605 ,1 , "forest");
//            $this->terrain->addTerrain(2605 ,2 , "forest");
//            $this->terrain->addTerrain(2606 ,1 , "forest");
//            $this->terrain->addTerrain(2606 ,2 , "forest");
//            $this->terrain->addTerrain(2607 ,1 , "forest");
//            $this->terrain->addTerrain(2607 ,2 , "forest");
//            $this->terrain->addTerrain(2708 ,3 , "forest");
//            $this->terrain->addTerrain(2708 ,4 , "forest");
//            $this->terrain->addTerrain(2707 ,3 , "forest");
//            $this->terrain->addTerrain(2707 ,4 , "forest");
//            $this->terrain->addTerrain(2706 ,3 , "forest");
//            $this->terrain->addTerrain(2706 ,4 , "forest");
//            $this->terrain->addTerrain(2705 ,3 , "forest");
//            $this->terrain->addTerrain(2705 ,4 , "forest");
//            $this->terrain->addTerrain(2704 ,3 , "forest");
//            $this->terrain->addTerrain(2704 ,4 , "forest");
//            $this->terrain->addTerrain(2704 ,1 , "forest");
//            $this->terrain->addTerrain(2704 ,2 , "forest");
//            $this->terrain->addTerrain(2705 ,1 , "forest");
//            $this->terrain->addTerrain(2705 ,2 , "forest");
//            $this->terrain->addTerrain(2706 ,1 , "forest");
//            $this->terrain->addTerrain(2706 ,2 , "forest");
//            $this->terrain->addTerrain(2707 ,1 , "forest");
//            $this->terrain->addTerrain(2707 ,2 , "forest");
//            $this->terrain->addTerrain(2708 ,1 , "forest");
//            $this->terrain->addTerrain(2807 ,3 , "forest");
//            $this->terrain->addTerrain(2807 ,4 , "forest");
//            $this->terrain->addTerrain(2806 ,3 , "forest");
//            $this->terrain->addTerrain(2806 ,4 , "forest");
//            $this->terrain->addTerrain(2805 ,3 , "forest");
//            $this->terrain->addTerrain(2805 ,4 , "forest");
//            $this->terrain->addTerrain(2804 ,3 , "forest");
//            $this->terrain->addTerrain(2804 ,4 , "forest");
//            $this->terrain->addTerrain(2804 ,1 , "forest");
//            $this->terrain->addTerrain(2804 ,2 , "forest");
//            $this->terrain->addTerrain(2805 ,1 , "forest");
//            $this->terrain->addTerrain(2805 ,2 , "forest");
//            $this->terrain->addTerrain(2806 ,1 , "forest");
//            $this->terrain->addTerrain(2806 ,2 , "forest");
//            $this->terrain->addTerrain(2807 ,1 , "forest");
//            $this->terrain->addTerrain(1215 ,1 , "forest");
//            $this->terrain->addTerrain(1216 ,1 , "forest");
//            $this->terrain->addTerrain(1215 ,2 , "forest");
//            $this->terrain->addTerrain(1316 ,3 , "forest");
//            $this->terrain->addTerrain(1316 ,4 , "forest");
//            $this->terrain->addTerrain(1314 ,2 , "forest");
//            $this->terrain->addTerrain(1316 ,1 , "forest");
//            $this->terrain->addTerrain(1416 ,4 , "forest");
//            $this->terrain->addTerrain(1415 ,3 , "forest");
//            $this->terrain->addTerrain(1414 ,3 , "forest");
//            $this->terrain->addTerrain(1414 ,4 , "forest");
//            $this->terrain->addTerrain(1413 ,3 , "forest");
//            $this->terrain->addTerrain(1412 ,2 , "forest");
//            $this->terrain->addTerrain(1413 ,2 , "forest");
//            $this->terrain->addTerrain(1415 ,1 , "forest");
//            $this->terrain->addTerrain(1415 ,2 , "forest");
//            $this->terrain->addTerrain(1416 ,1 , "forest");
//            $this->terrain->addTerrain(1517 ,4 , "forest");
//            $this->terrain->addTerrain(1516 ,3 , "forest");
//            $this->terrain->addTerrain(1516 ,4 , "forest");
//            $this->terrain->addTerrain(1515 ,4 , "forest");
//            $this->terrain->addTerrain(1514 ,3 , "forest");
//            $this->terrain->addTerrain(1514 ,4 , "forest");
//            $this->terrain->addTerrain(1513 ,3 , "forest");
//            $this->terrain->addTerrain(1513 ,4 , "forest");
//            $this->terrain->addTerrain(1512 ,3 , "forest");
//            $this->terrain->addTerrain(1511 ,2 , "forest");
//            $this->terrain->addTerrain(1512 ,2 , "forest");
//            $this->terrain->addTerrain(1513 ,2 , "forest");
//            $this->terrain->addTerrain(1514 ,2 , "forest");
//            $this->terrain->addTerrain(1516 ,1 , "forest");
//            $this->terrain->addTerrain(1516 ,2 , "forest");
//            $this->terrain->addTerrain(1517 ,1 , "forest");
//            $this->terrain->addTerrain(1616 ,3 , "forest");
//            $this->terrain->addTerrain(1616 ,4 , "forest");
//            $this->terrain->addTerrain(1615 ,4 , "forest");
//            $this->terrain->addTerrain(1614 ,3 , "forest");
//            $this->terrain->addTerrain(1614 ,4 , "forest");
//            $this->terrain->addTerrain(1613 ,3 , "forest");
//            $this->terrain->addTerrain(1613 ,4 , "forest");
//            $this->terrain->addTerrain(1612 ,3 , "forest");
//            $this->terrain->addTerrain(1612 ,4 , "forest");
//            $this->terrain->addTerrain(1611 ,3 , "forest");
//            $this->terrain->addTerrain(1611 ,4 , "forest");
//            $this->terrain->addTerrain(1610 ,3 , "forest");
//            $this->terrain->addTerrain(1609 ,2 , "forest");
//            $this->terrain->addTerrain(1610 ,2 , "forest");
//            $this->terrain->addTerrain(1611 ,2 , "forest");
//            $this->terrain->addTerrain(1612 ,2 , "forest");
//            $this->terrain->addTerrain(1613 ,2 , "forest");
//            $this->terrain->addTerrain(1614 ,2 , "forest");
//            $this->terrain->addTerrain(1616 ,1 , "forest");
//            $this->terrain->addTerrain(1717 ,4 , "forest");
//            $this->terrain->addTerrain(1716 ,4 , "forest");
//            $this->terrain->addTerrain(1715 ,3 , "forest");
//            $this->terrain->addTerrain(1715 ,4 , "forest");
//            $this->terrain->addTerrain(1714 ,3 , "forest");
//            $this->terrain->addTerrain(1714 ,4 , "forest");
//            $this->terrain->addTerrain(1713 ,3 , "forest");
//            $this->terrain->addTerrain(1713 ,4 , "forest");
//            $this->terrain->addTerrain(1712 ,3 , "forest");
//            $this->terrain->addTerrain(1712 ,4 , "forest");
//            $this->terrain->addTerrain(1711 ,3 , "forest");
//            $this->terrain->addTerrain(1711 ,4 , "forest");
//            $this->terrain->addTerrain(1710 ,3 , "forest");
//            $this->terrain->addTerrain(1710 ,4 , "forest");
//            $this->terrain->addTerrain(1709 ,3 , "forest");
//            $this->terrain->addTerrain(1717 ,1 , "forest");
//            $this->terrain->addTerrain(1715 ,2 , "forest");
//            $this->terrain->addTerrain(1714 ,2 , "forest");
//            $this->terrain->addTerrain(1713 ,2 , "forest");
//            $this->terrain->addTerrain(1712 ,2 , "forest");
//            $this->terrain->addTerrain(1711 ,2 , "forest");
//            $this->terrain->addTerrain(1709 ,2 , "forest");
//            $this->terrain->addTerrain(1809 ,4 , "forest");
//            $this->terrain->addTerrain(1809 ,3 , "forest");
//            $this->terrain->addTerrain(1810 ,4 , "forest");
//            $this->terrain->addTerrain(1810 ,3 , "forest");
//            $this->terrain->addTerrain(1811 ,4 , "forest");
//            $this->terrain->addTerrain(1811 ,3 , "forest");
//            $this->terrain->addTerrain(1812 ,4 , "forest");
//            $this->terrain->addTerrain(1812 ,3 , "forest");
//            $this->terrain->addTerrain(1813 ,4 , "forest");
//            $this->terrain->addTerrain(1813 ,3 , "forest");
//            $this->terrain->addTerrain(1814 ,4 , "forest");
//            $this->terrain->addTerrain(1814 ,3 , "forest");
//            $this->terrain->addTerrain(1815 ,4 , "forest");
//            $this->terrain->addTerrain(1815 ,3 , "forest");
//            $this->terrain->addTerrain(1816 ,3 , "forest");
//            $this->terrain->addTerrain(1817 ,4 , "forest");
//            $this->terrain->addTerrain(1809 ,2 , "forest");
//            $this->terrain->addTerrain(1810 ,2 , "forest");
//            $this->terrain->addTerrain(1811 ,2 , "forest");
//            $this->terrain->addTerrain(1812 ,2 , "forest");
//            $this->terrain->addTerrain(1813 ,2 , "forest");
//            $this->terrain->addTerrain(1814 ,2 , "forest");
//            $this->terrain->addTerrain(1816 ,1 , "forest");
//            $this->terrain->addTerrain(1817 ,1 , "forest");
//            $this->terrain->addTerrain(1816 ,2 , "forest");
//            $this->terrain->addTerrain(1917 ,3 , "forest");
//            $this->terrain->addTerrain(1917 ,4 , "forest");
//            $this->terrain->addTerrain(1916 ,4 , "forest");
//            $this->terrain->addTerrain(1915 ,3 , "forest");
//            $this->terrain->addTerrain(1915 ,4 , "forest");
//            $this->terrain->addTerrain(1914 ,3 , "forest");
//            $this->terrain->addTerrain(1914 ,4 , "forest");
//            $this->terrain->addTerrain(1913 ,3 , "forest");
//            $this->terrain->addTerrain(1913 ,4 , "forest");
//            $this->terrain->addTerrain(1912 ,3 , "forest");
//            $this->terrain->addTerrain(1912 ,4 , "forest");
//            $this->terrain->addTerrain(1911 ,3 , "forest");
//            $this->terrain->addTerrain(1911 ,4 , "forest");
//            $this->terrain->addTerrain(1911 ,2 , "forest");
//            $this->terrain->addTerrain(1912 ,2 , "forest");
//            $this->terrain->addTerrain(1917 ,1 , "forest");
//            $this->terrain->addTerrain(1915 ,2 , "forest");
//            $this->terrain->addTerrain(1914 ,2 , "forest");
//            $this->terrain->addTerrain(1913 ,2 , "forest");
//            $this->terrain->addTerrain(2013 ,4 , "forest");
//            $this->terrain->addTerrain(2013 ,3 , "forest");
//            $this->terrain->addTerrain(2014 ,4 , "forest");
//            $this->terrain->addTerrain(2014 ,3 , "forest");
//            $this->terrain->addTerrain(2015 ,4 , "forest");
//            $this->terrain->addTerrain(2015 ,3 , "forest");
//            $this->terrain->addTerrain(2016 ,3 , "forest");
//            $this->terrain->addTerrain(2016 ,3 , "river");
//            $this->terrain->addTerrain(2017 ,4 , "forest");
//            $this->terrain->addTerrain(2017 ,4 , "river");
//            $this->terrain->addTerrain(2017 ,1 , "forest");
//            $this->terrain->addTerrain(2016 ,2 , "forest");
//            $this->terrain->addTerrain(2016 ,1 , "forest");
//            $this->terrain->addTerrain(2014 ,2 , "forest");
//            $this->terrain->addTerrain(2013 ,2 , "forest");
//            $this->terrain->addTerrain(2117 ,4 , "forest");
//            $this->terrain->addTerrain(2117 ,3 , "forest");
//            $this->terrain->addTerrain(2118 ,4 , "forest");
//            $this->terrain->addTerrain(2117 ,1 , "forest");
//            $this->terrain->addTerrain(2117 ,2 , "forest");
//            $this->terrain->addTerrain(2118 ,1 , "forest");
//            $this->terrain->addTerrain(2217 ,3 , "forest");
//            $this->terrain->addTerrain(2217 ,4 , "forest");
//            $this->terrain->addTerrain(2217 ,1 , "forest");
//            $this->terrain->addTerrain(2019 ,4 , "river");
//            $this->terrain->addTerrain(2018 ,3 , "river");
//            $this->terrain->addTerrain(2018 ,4 , "river");
//            $this->terrain->addTerrain(2017 ,3 , "river");
//            $this->terrain->addTerrain(1315 ,4 , "redoubt");
//            $this->terrain->addTerrain(1314 ,3 , "redoubt");
//            $this->terrain->addTerrain(1314 ,4 , "redoubt");
//            $this->terrain->addTerrain(1313 ,2 , "redoubt");
//            $this->terrain->addTerrain(1413 ,4 , "redoubt");
//            $this->terrain->addTerrain(1412 ,3 , "redoubt");
//            $this->terrain->addTerrain(1412 ,4 , "redoubt");
//            $this->terrain->addTerrain(1411 ,2 , "redoubt");
//            $this->terrain->addTerrain(1512 ,4 , "redoubt");
//            $this->terrain->addTerrain(1511 ,3 , "redoubt");
//            $this->terrain->addTerrain(1511 ,4 , "redoubt");
//            $this->terrain->addTerrain(1510 ,2 , "redoubt");
//            $this->terrain->addTerrain(1610 ,4 , "redoubt");
//            $this->terrain->addTerrain(1609 ,3 , "redoubt");
//            $this->terrain->addTerrain(1609 ,4 , "redoubt");
//            $this->terrain->addTerrain(1608 ,2 , "redoubt");
//            $this->terrain->addTerrain(1709 ,4 , "redoubt");
//            $this->terrain->addTerrain(1708 ,2 , "redoubt");
//            $this->terrain->addTerrain(1808 ,3 , "redoubt");
//            $this->terrain->addTerrain(1808 ,2 , "redoubt");
//
//            $this->moodkee = $specialHexB[0];
//            $specialHexes = [];
//            foreach ($specialHexA as $specialHexId) {
//                $specialHexes[$specialHexId] = BRITISH_FORCE;
//            }
//            foreach ($specialHexB as $specialHexId) {
//                $specialHexes[$specialHexId] = BELUCHI_FORCE;
//            }
//            $this->mapData->setSpecialHexes($specialHexes);

            // end terrain data ----------------------------------------

        }
    }
}