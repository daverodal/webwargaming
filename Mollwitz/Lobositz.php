<?php
set_include_path(__DIR__ . "/Lobositz" . PATH_SEPARATOR . get_include_path());
require_once "JagCore.php";

/* comment */

$force_name[1] = "Prussian";
$force_name[2] = "Austrian";
define("PRUSSIAN_FORCE", 1);
define("AUSTRIAN_FORCE", 2);
$phase_name = array();
$phase_name[1] = "Prussian Move";
$phase_name[2] = "Prussian Combat";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "Austrian Move";
$phase_name[5] = "Austrian Combat";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "Prussian Deploy";
$phase_name[9] = "Prussian Mech";
$phase_name[10] = "Prussian Replacement";
$phase_name[11] = "Russian Mech";
$phase_name[12] = "Russian Replacement";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "Austrian deploy phase";


// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class Lobositz extends JagCore
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
    public $austrianSpecialHexes;
    public $prussianSpecialHexes;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "commonHeader.php";
        @include_once "header.php";
        @include_once "LobositzHeader.php";

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
        $data->austrianSpecialHexes = $this->austrianSpecialHexes;
        $data->prussianSpecialHexes = $this->prussianSpecialHexes;
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }


    public function init()
    {

        $artRange = 3;

        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 11; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        }
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        for ($i = 0; $i < 4; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        }


        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        }
        for ($i = 0; $i < 15; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        }

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->austrianSpecialHexes = $data->austrianSpecialHexes;
            $this->prussianSpecialHexes = $data->prussianSpecialHexes;
            $this->game = $data->game;
            $this->genTerrain = false;
            $this->victory = new Victory("Mollwitz/Lobositz/lobositzVictoryCore.php", $data);
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
            $this->victory = new Victory("Mollwitz/Lobositz/lobositzVictoryCore.php");
            $this->mapData->setData(24, 19, "js/Lobositz1.jpg");
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
                $this->mapViewer[$player]->setData(51.400000000000006 , 83.65805400557677, // originX, originY
                    27.886018001858925, 27.886018001858925, // top hexagon height, bottom hexagon height
                    16.1, 32.2// hexagon edge width, hexagon center width
                );
            }

            // game data
            $this->gameRules->setMaxTurn(14);
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
            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
            $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
            $this->terrain->addTerrainFeature("sunkenroad", "sunkenroad", "k", 1, 0, 1, true, false);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);
            $this->terrain->addAltEntranceCost('swamp','artillery','blocked');


            for ($col = 100; $col <= 2400; $col += 100) {
                for ($row = 1; $row <= 19; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
            $specialHexA = [];
            $specialHexB = [];
            $this->terrain->addTerrain(1101 ,1 , "town");
            $specialHexA[] = 1101;
            $this->terrain->addReinforceZone(1101,'B');
            $this->terrain->addTerrain(804 ,1 , "town");
            $this->terrain->addReinforceZone(804,'B');
            $this->terrain->addTerrain(1107 ,1 , "town");
            $this->terrain->addReinforceZone(1107,'B');
            $this->terrain->addTerrain(108 ,1 , "town");
            $this->terrain->addReinforceZone(108,'B');
            $this->terrain->addTerrain(313 ,1 , "town");
            $this->terrain->addTerrain(1213 ,1 , "town");
            $this->terrain->addReinforceZone(1213,'A');
            $this->terrain->addTerrain(1914 ,1 , "town");
            $this->terrain->addReinforceZone(1914,'A');
            $this->terrain->addTerrain(2014 ,4 , "town");
            $this->terrain->addTerrain(2014 ,1 , "town");
            $this->terrain->addReinforceZone(2014,'A');
            $this->terrain->addTerrain(208 ,3 , "river");
            $this->terrain->addTerrain(209 ,4 , "river");
            $this->terrain->addTerrain(209 ,3 , "river");
            $this->terrain->addTerrain(210 ,4 , "river");
            $this->terrain->addTerrain(210 ,3 , "river");
            $this->terrain->addTerrain(210 ,2 , "river");
            $this->terrain->addTerrain(311 ,3 , "river");
            $this->terrain->addTerrain(311 ,2 , "river");
            $this->terrain->addTerrain(311 ,2 , "road");
            $this->terrain->addTerrain(411 ,3 , "river");
            $this->terrain->addTerrain(411 ,2 , "river");
            $this->terrain->addTerrain(512 ,4 , "river");
            $this->terrain->addTerrain(511 ,2 , "river");
            $this->terrain->addTerrain(611 ,3 , "river");
            $this->terrain->addTerrain(611 ,2 , "river");
            $this->terrain->addTerrain(712 ,3 , "river");
            $this->terrain->addTerrain(712 ,2 , "river");
            $this->terrain->addTerrain(812 ,4 , "river");
            $this->terrain->addTerrain(811 ,2 , "river");
            $this->terrain->addTerrain(912 ,3 , "river");
            $this->terrain->addTerrain(914 ,4 , "river");
            $this->terrain->addTerrain(813 ,2 , "river");
            $this->terrain->addTerrain(814 ,4 , "river");
            $this->terrain->addTerrain(714 ,2 , "river");
            $this->terrain->addTerrain(715 ,4 , "river");
            $this->terrain->addTerrain(614 ,2 , "river");
            $this->terrain->addTerrain(615 ,4 , "river");
            $this->terrain->addTerrain(515 ,2 , "river");
            $this->terrain->addTerrain(516 ,4 , "river");
            $this->terrain->addTerrain(415 ,2 , "river");
            $this->terrain->addTerrain(416 ,4 , "river");
            $this->terrain->addTerrain(316 ,2 , "river");
            $this->terrain->addTerrain(1012 ,2 , "river");
            $this->terrain->addTerrain(1012 ,2 , "forest");
            $this->terrain->addTerrain(1113 ,3 , "river");
            $this->terrain->addTerrain(1113 ,3 , "forest");
            $this->terrain->addTerrain(1113 ,2 , "river");
            $this->terrain->addTerrain(1113 ,2 , "forest");
            $this->terrain->addTerrain(1213 ,3 , "river");
            $this->terrain->addTerrain(1315 ,3 , "river");
            $this->terrain->addTerrain(1316 ,4 , "river");
            $this->terrain->addTerrain(1316 ,3 , "river");
            $this->terrain->addTerrain(1316 ,2 , "river");
            $this->terrain->addTerrain(1416 ,3 , "river");
            $this->terrain->addTerrain(1417 ,4 , "river");
            $this->terrain->addTerrain(1417 ,3 , "river");
            $this->terrain->addTerrain(1417 ,3 , "road");
            $this->terrain->addTerrain(1418 ,4 , "river");
            $this->terrain->addTerrain(1418 ,3 , "river");
            $this->terrain->addTerrain(1419 ,4 , "river");
            $this->terrain->addTerrain(1519 ,3 , "river");
            $this->terrain->addTerrain(1519 ,4 , "river");
            $this->terrain->addTerrain(1518 ,3 , "river");
            $this->terrain->addTerrain(1518 ,4 , "river");
            $this->terrain->addTerrain(1517 ,3 , "river");
            $this->terrain->addTerrain(1517 ,3 , "road");
            $this->terrain->addTerrain(1517 ,4 , "river");
            $this->terrain->addTerrain(1516 ,3 , "river");
            $this->terrain->addTerrain(1415 ,2 , "river");
            $this->terrain->addTerrain(1415 ,3 , "river");
            $this->terrain->addTerrain(1415 ,4 , "river");
            $this->terrain->addTerrain(1414 ,3 , "river");
            $this->terrain->addTerrain(1314 ,2 , "river");
            $this->terrain->addTerrain(2115 ,4 , "river");
            $this->terrain->addTerrain(2115 ,3 , "river");
            $this->terrain->addTerrain(2116 ,4 , "river");
            $this->terrain->addTerrain(2116 ,3 , "river");
            $this->terrain->addTerrain(2117 ,4 , "river");
            $this->terrain->addTerrain(2117 ,4 , "road");
            $this->terrain->addTerrain(2117 ,3 , "river");
            $this->terrain->addTerrain(2117 ,3 , "forest");
            $this->terrain->addTerrain(2118 ,4 , "river");
            $this->terrain->addTerrain(2118 ,4 , "forest");
            $this->terrain->addTerrain(2118 ,3 , "river");
            $this->terrain->addTerrain(2119 ,4 , "river");
            $this->terrain->addTerrain(612 ,1 , "swamp");
            $this->terrain->addTerrain(713 ,1 , "swamp");
            $this->terrain->addTerrain(812 ,1 , "swamp");
            $this->terrain->addTerrain(813 ,1 , "swamp");
            $this->terrain->addTerrain(714 ,1 , "swamp");
            $this->terrain->addTerrain(614 ,1 , "swamp");
            $this->terrain->addTerrain(515 ,1 , "swamp");
            $this->terrain->addTerrain(415 ,1 , "swamp");
            $this->terrain->addTerrain(316 ,1 , "swamp");
            $this->terrain->addTerrain(1315 ,1 , "swamp");
            $this->terrain->addReinforceZone(1315,'A');
            $this->terrain->addTerrain(1316 ,1 , "swamp");
            $this->terrain->addReinforceZone(1316,'A');
            $this->terrain->addTerrain(1416 ,1 , "swamp");
            $this->terrain->addReinforceZone(1416,'A');
            $this->terrain->addTerrain(1417 ,1 , "swamp");
            $this->terrain->addTerrain(1417 ,1 , "road");
            $this->terrain->addReinforceZone(1417,'A');
            $this->terrain->addTerrain(1418 ,1 , "swamp");
            $this->terrain->addReinforceZone(1418,'A');
            $this->terrain->addTerrain(1419 ,1 , "swamp");
            $this->terrain->addReinforceZone(1419,'A');
            $this->terrain->addTerrain(2316 ,1 , "swamp");
            $this->terrain->addTerrain(2317 ,1 , "swamp");
            $this->terrain->addTerrain(2416 ,1 , "swamp");
            $this->terrain->addTerrain(2416 ,2 , "swamp");
            $this->terrain->addTerrain(101 ,1 , "forest");
            $this->terrain->addReinforceZone(101,'B');
            $this->terrain->addTerrain(102 ,1 , "forest");
            $this->terrain->addReinforceZone(102,'B');
            $this->terrain->addTerrain(103 ,1 , "forest");
            $this->terrain->addReinforceZone(103,'B');
            $this->terrain->addTerrain(104 ,1 , "forest");
            $this->terrain->addReinforceZone(104,'B');
            $this->terrain->addTerrain(106 ,1 , "forest");
            $this->terrain->addReinforceZone(106,'B');
            $this->terrain->addTerrain(107 ,1 , "forest");
            $this->terrain->addReinforceZone(107,'B');
            $this->terrain->addTerrain(109 ,1 , "forest");
            $this->terrain->addTerrain(101 ,2 , "forest");
            $this->terrain->addTerrain(102 ,2 , "forest");
            $this->terrain->addTerrain(103 ,2 , "forest");
            $this->terrain->addTerrain(106 ,2 , "forest");
            $this->terrain->addTerrain(109 ,2 , "forest");
            $this->terrain->addTerrain(110 ,1 , "forest");
            $this->terrain->addTerrain(110 ,2 , "forest");
            $this->terrain->addTerrain(111 ,1 , "forest");
            $this->terrain->addTerrain(114 ,1 , "forest");
            $this->terrain->addTerrain(114 ,1 , "road");
            $this->terrain->addTerrain(116 ,1 , "forest");
            $this->terrain->addTerrain(118 ,1 , "forest");
            $this->terrain->addReinforceZone(118,'A');
            $this->terrain->addTerrain(201 ,4 , "forest");
            $this->terrain->addTerrain(201 ,3 , "forest");
            $this->terrain->addTerrain(202 ,4 , "forest");
            $this->terrain->addTerrain(202 ,3 , "forest");
            $this->terrain->addTerrain(203 ,4 , "forest");
            $this->terrain->addTerrain(203 ,3 , "forest");
            $this->terrain->addTerrain(204 ,4 , "forest");
            $this->terrain->addTerrain(205 ,3 , "forest");
            $this->terrain->addTerrain(206 ,4 , "forest");
            $this->terrain->addTerrain(206 ,3 , "forest");
            $this->terrain->addTerrain(215 ,3 , "forest");
            $this->terrain->addTerrain(217 ,3 , "forest");
            $this->terrain->addTerrain(201 ,1 , "forest");
            $this->terrain->addReinforceZone(201,'B');
            $this->terrain->addTerrain(201 ,2 , "forest");
            $this->terrain->addTerrain(202 ,1 , "forest");
            $this->terrain->addReinforceZone(202,'B');
            $this->terrain->addTerrain(202 ,2 , "forest");
            $this->terrain->addTerrain(203 ,1 , "forest");
            $this->terrain->addReinforceZone(203,'B');
            $this->terrain->addTerrain(203 ,2 , "forest");
            $this->terrain->addTerrain(204 ,1 , "forest");
            $this->terrain->addReinforceZone(204,'B');
            $this->terrain->addTerrain(204 ,2 , "forest");
            $this->terrain->addTerrain(205 ,1 , "forest");
            $this->terrain->addReinforceZone(205,'B');
            $this->terrain->addTerrain(205 ,2 , "forest");
            $this->terrain->addTerrain(206 ,1 , "forest");
            $this->terrain->addReinforceZone(206,'B');
            $this->terrain->addTerrain(215 ,1 , "forest");
            $this->terrain->addTerrain(217 ,1 , "forest");
            $this->terrain->addReinforceZone(217,'A');
            $this->terrain->addTerrain(301 ,3 , "forest");
            $this->terrain->addTerrain(302 ,4 , "forest");
            $this->terrain->addTerrain(302 ,3 , "forest");
            $this->terrain->addTerrain(303 ,4 , "forest");
            $this->terrain->addTerrain(303 ,3 , "forest");
            $this->terrain->addTerrain(304 ,4 , "forest");
            $this->terrain->addTerrain(304 ,3 , "forest");
            $this->terrain->addTerrain(305 ,4 , "forest");
            $this->terrain->addTerrain(305 ,3 , "forest");
            $this->terrain->addTerrain(306 ,4 , "forest");
            $this->terrain->addTerrain(306 ,3 , "forest");
            $this->terrain->addTerrain(317 ,3 , "forest");
            $this->terrain->addTerrain(301 ,1 , "forest");
            $this->terrain->addReinforceZone(301,'B');
            $this->terrain->addTerrain(301 ,2 , "forest");
            $this->terrain->addTerrain(302 ,1 , "forest");
            $this->terrain->addReinforceZone(302,'B');
            $this->terrain->addTerrain(302 ,2 , "forest");
            $this->terrain->addTerrain(303 ,1 , "forest");
            $this->terrain->addReinforceZone(303,'B');
            $this->terrain->addTerrain(303 ,2 , "forest");
            $this->terrain->addTerrain(304 ,1 , "forest");
            $this->terrain->addReinforceZone(304,'B');
            $this->terrain->addTerrain(304 ,2 , "forest");
            $this->terrain->addTerrain(305 ,1 , "forest");
            $this->terrain->addReinforceZone(305,'B');
            $this->terrain->addTerrain(305 ,2 , "forest");
            $this->terrain->addTerrain(306 ,1 , "forest");
            $this->terrain->addReinforceZone(306,'B');
            $this->terrain->addTerrain(317 ,1 , "forest");
            $this->terrain->addReinforceZone(317,'A');
            $this->terrain->addTerrain(401 ,4 , "forest");
            $this->terrain->addTerrain(401 ,3 , "forest");
            $this->terrain->addTerrain(402 ,4 , "forest");
            $this->terrain->addTerrain(402 ,3 , "forest");
            $this->terrain->addTerrain(404 ,4 , "forest");
            $this->terrain->addTerrain(404 ,3 , "forest");
            $this->terrain->addTerrain(405 ,4 , "forest");
            $this->terrain->addTerrain(405 ,3 , "forest");
            $this->terrain->addTerrain(417 ,4 , "forest");
            $this->terrain->addTerrain(401 ,1 , "forest");
            $this->terrain->addReinforceZone(401,'B');
            $this->terrain->addTerrain(401 ,2 , "forest");
            $this->terrain->addTerrain(402 ,1 , "forest");
            $this->terrain->addReinforceZone(402,'B');
            $this->terrain->addTerrain(404 ,1 , "forest");
            $this->terrain->addReinforceZone(404,'B');
            $this->terrain->addTerrain(404 ,2 , "forest");
            $this->terrain->addTerrain(405 ,1 , "forest");
            $this->terrain->addReinforceZone(405,'B');
            $this->terrain->addTerrain(417 ,1 , "forest");
            $this->terrain->addReinforceZone(417,'A');
            $this->terrain->addTerrain(409 ,1 , "forest");
            $this->terrain->addTerrain(501 ,3 , "forest");
            $this->terrain->addTerrain(502 ,4 , "forest");
            $this->terrain->addTerrain(502 ,3 , "forest");
            $this->terrain->addTerrain(503 ,4 , "forest");
            $this->terrain->addTerrain(504 ,3 , "forest");
            $this->terrain->addTerrain(505 ,4 , "forest");
            $this->terrain->addTerrain(505 ,3 , "forest");
            $this->terrain->addTerrain(501 ,1 , "forest");
            $this->terrain->addReinforceZone(501,'B');
            $this->terrain->addTerrain(501 ,2 , "forest");
            $this->terrain->addTerrain(502 ,1 , "forest");
            $this->terrain->addReinforceZone(502,'B');
            $this->terrain->addTerrain(502 ,2 , "forest");
            $this->terrain->addTerrain(503 ,1 , "forest");
            $this->terrain->addReinforceZone(503,'B');
            $this->terrain->addTerrain(503 ,2 , "forest");
            $this->terrain->addTerrain(504 ,1 , "forest");
            $this->terrain->addReinforceZone(504,'B');
            $this->terrain->addTerrain(504 ,2 , "forest");
            $this->terrain->addTerrain(505 ,1 , "forest");
            $this->terrain->addReinforceZone(505,'B');
            $this->terrain->addTerrain(601 ,4 , "forest");
            $this->terrain->addTerrain(601 ,3 , "forest");
            $this->terrain->addTerrain(602 ,4 , "forest");
            $this->terrain->addTerrain(602 ,3 , "forest");
            $this->terrain->addTerrain(603 ,4 , "forest");
            $this->terrain->addTerrain(603 ,3 , "forest");
            $this->terrain->addTerrain(604 ,4 , "forest");
            $this->terrain->addTerrain(604 ,3 , "forest");
            $this->terrain->addTerrain(605 ,4 , "forest");
            $this->terrain->addTerrain(601 ,1 , "forest");
            $this->terrain->addReinforceZone(601,'B');
            $this->terrain->addTerrain(601 ,2 , "forest");
            $this->terrain->addTerrain(602 ,1 , "forest");
            $this->terrain->addReinforceZone(602,'B');
            $this->terrain->addTerrain(602 ,2 , "forest");
            $this->terrain->addTerrain(603 ,1 , "forest");
            $this->terrain->addReinforceZone(603,'B');
            $this->terrain->addTerrain(603 ,2 , "forest");
            $this->terrain->addTerrain(604 ,1 , "forest");
            $this->terrain->addReinforceZone(604,'B');
            $this->terrain->addTerrain(604 ,2 , "forest");
            $this->terrain->addTerrain(605 ,1 , "forest");
            $this->terrain->addReinforceZone(605,'B');
            $this->terrain->addTerrain(701 ,3 , "forest");
            $this->terrain->addTerrain(702 ,4 , "forest");
            $this->terrain->addTerrain(702 ,3 , "forest");
            $this->terrain->addTerrain(703 ,4 , "forest");
            $this->terrain->addTerrain(703 ,3 , "forest");
            $this->terrain->addTerrain(701 ,1 , "forest");
            $this->terrain->addReinforceZone(701,'B');
            $this->terrain->addTerrain(701 ,2 , "forest");
            $this->terrain->addTerrain(702 ,1 , "forest");
            $this->terrain->addReinforceZone(702,'B');
            $this->terrain->addTerrain(702 ,2 , "forest");
            $this->terrain->addTerrain(703 ,1 , "forest");
            $this->terrain->addReinforceZone(703,'B');
            $this->terrain->addTerrain(801 ,4 , "forest");
            $this->terrain->addTerrain(801 ,3 , "forest");
            $this->terrain->addTerrain(802 ,4 , "forest");
            $this->terrain->addTerrain(802 ,3 , "forest");
            $this->terrain->addTerrain(803 ,4 , "forest");
            $this->terrain->addTerrain(801 ,1 , "forest");
            $this->terrain->addReinforceZone(801,'B');
            $this->terrain->addTerrain(801 ,2 , "forest");
            $this->terrain->addTerrain(802 ,1 , "forest");
            $this->terrain->addReinforceZone(802,'B');
            $this->terrain->addTerrain(802 ,2 , "forest");
            $this->terrain->addTerrain(803 ,1 , "forest");
            $this->terrain->addReinforceZone(803,'B');
            $this->terrain->addTerrain(901 ,3 , "forest");
            $this->terrain->addTerrain(902 ,4 , "forest");
            $this->terrain->addTerrain(902 ,3 , "forest");
            $this->terrain->addTerrain(903 ,4 , "forest");
            $this->terrain->addTerrain(903 ,3 , "forest");
            $this->terrain->addTerrain(901 ,1 , "forest");
            $this->terrain->addTerrain(901 ,1 , "road");
            $specialHexA[] = 901;
            $this->terrain->addReinforceZone(901,'B');
            $this->terrain->addTerrain(901 ,2 , "forest");
            $this->terrain->addTerrain(901 ,2 , "road");
            $this->terrain->addTerrain(902 ,1 , "forest");
            $this->terrain->addTerrain(902 ,1 , "road");
            $this->terrain->addReinforceZone(902,'B');
            $this->terrain->addTerrain(902 ,2 , "forest");
            $this->terrain->addTerrain(903 ,1 , "forest");
            $this->terrain->addReinforceZone(903,'B');
            $this->terrain->addTerrain(807 ,1 , "forest");
            $this->terrain->addTerrain(907 ,3 , "forest");
            $this->terrain->addTerrain(908 ,4 , "forest");
            $this->terrain->addTerrain(907 ,1 , "forest");
            $this->terrain->addTerrain(907 ,2 , "forest");
            $this->terrain->addTerrain(908 ,1 , "forest");
            $this->terrain->addTerrain(710 ,1 , "forest");
            $this->terrain->addTerrain(710 ,2 , "forest");
            $this->terrain->addTerrain(711 ,1 , "forest");
            $this->terrain->addTerrain(711 ,1 , "road");
            $this->terrain->addTerrain(914 ,1 , "forest");
            $this->terrain->addReinforceZone(914,'A');
            $this->terrain->addTerrain(1013 ,3 , "forest");
            $this->terrain->addTerrain(1013 ,1 , "forest");
            $this->terrain->addReinforceZone(1013,'A');
            $this->terrain->addTerrain(1012 ,1 , "forest");
            $this->terrain->addTerrain(1012 ,1 , "road");
            $this->terrain->addTerrain(1113 ,4 , "forest");
            $this->terrain->addTerrain(1113 ,4 , "road");
            $this->terrain->addTerrain(1114 ,4 , "forest");
            $this->terrain->addTerrain(1113 ,1 , "forest");
            $this->terrain->addTerrain(1113 ,1 , "road");
            $this->terrain->addTerrain(1114 ,1 , "forest");
            $this->terrain->addReinforceZone(1114,'A');
            $this->terrain->addTerrain(918 ,1 , "forest");
            $this->terrain->addReinforceZone(918,'A');
            $this->terrain->addTerrain(1017 ,3 , "forest");
            $this->terrain->addTerrain(1018 ,4 , "forest");
            $this->terrain->addTerrain(1017 ,1 , "forest");
            $this->terrain->addReinforceZone(1017,'A');
            $this->terrain->addTerrain(1017 ,2 , "forest");
            $this->terrain->addTerrain(1018 ,1 , "forest");
            $this->terrain->addReinforceZone(1018,'A');
            $this->terrain->addTerrain(1617 ,1 , "forest");
            $this->terrain->addTerrain(1617 ,1 , "road");
            $this->terrain->addReinforceZone(1617,'A');
            $this->terrain->addTerrain(1617 ,2 , "forest");
            $this->terrain->addTerrain(1618 ,1 , "forest");
            $this->terrain->addReinforceZone(1618,'A');
            $this->terrain->addTerrain(1512 ,1 , "forest");
            $this->terrain->addTerrain(1512 ,1 , "road");
            $this->terrain->addTerrain(1611 ,3 , "forest");
            $this->terrain->addTerrain(1611 ,1 , "forest");
            $this->terrain->addTerrain(1913 ,1 , "forest");
            $this->terrain->addTerrain(1913 ,1 , "road");
            $this->terrain->addReinforceZone(1913,'A');
            $this->terrain->addTerrain(1913 ,2 , "forest");
            $this->terrain->addTerrain(1913 ,2 , "road");
            $this->terrain->addTerrain(2017 ,1 , "forest");
            $this->terrain->addReinforceZone(2017,'A');
            $this->terrain->addTerrain(2117 ,1 , "forest");
            $this->terrain->addTerrain(2117 ,1 , "road");
            $this->terrain->addReinforceZone(2117,'A');
            $this->terrain->addTerrain(2117 ,2 , "forest");
            $this->terrain->addTerrain(2117 ,2 , "road");
            $this->terrain->addTerrain(2118 ,1 , "forest");
            $this->terrain->addTerrain(2118 ,1 , "road");
            $this->terrain->addReinforceZone(2118,'A');
            $this->terrain->addTerrain(2212 ,1 , "forest");
            $this->terrain->addTerrain(2212 ,2 , "forest");
            $this->terrain->addTerrain(2213 ,1 , "forest");
            $this->terrain->addTerrain(2312 ,3 , "forest");
            $this->terrain->addTerrain(2313 ,4 , "forest");
            $this->terrain->addTerrain(2313 ,3 , "forest");
            $this->terrain->addTerrain(2314 ,4 , "forest");
            $this->terrain->addTerrain(2311 ,1 , "forest");
            $this->terrain->addTerrain(2311 ,2 , "forest");
            $this->terrain->addTerrain(2312 ,1 , "forest");
            $this->terrain->addTerrain(2312 ,2 , "forest");
            $this->terrain->addTerrain(2313 ,1 , "forest");
            $this->terrain->addTerrain(2313 ,2 , "forest");
            $this->terrain->addTerrain(2314 ,1 , "forest");
            $this->terrain->addTerrain(2410 ,3 , "forest");
            $this->terrain->addTerrain(2411 ,4 , "forest");
            $this->terrain->addTerrain(2411 ,3 , "forest");
            $this->terrain->addTerrain(2412 ,4 , "forest");
            $this->terrain->addTerrain(2412 ,3 , "forest");
            $this->terrain->addTerrain(2413 ,4 , "forest");
            $this->terrain->addTerrain(2413 ,3 , "forest");
            $this->terrain->addTerrain(2414 ,4 , "forest");
            $this->terrain->addTerrain(2409 ,1 , "forest");
            $this->terrain->addTerrain(2409 ,2 , "forest");
            $this->terrain->addTerrain(2410 ,1 , "forest");
            $this->terrain->addTerrain(2410 ,2 , "forest");
            $this->terrain->addTerrain(2411 ,1 , "forest");
            $this->terrain->addTerrain(2411 ,2 , "forest");
            $this->terrain->addTerrain(2412 ,1 , "forest");
            $this->terrain->addTerrain(2412 ,2 , "forest");
            $this->terrain->addTerrain(2413 ,1 , "forest");
            $this->terrain->addTerrain(2413 ,2 , "forest");
            $this->terrain->addTerrain(2414 ,1 , "forest");
            $this->terrain->addTerrain(2406 ,1 , "forest");
            $this->terrain->addTerrain(2406 ,2 , "forest");
            $this->terrain->addTerrain(2407 ,1 , "forest");
            $this->terrain->addTerrain(2407 ,1 , "road");
            $this->terrain->addReinforceZone(2407,'A');
            $this->terrain->addTerrain(1201 ,1 , "forest");
            $this->terrain->addReinforceZone(1201,'B');
            $this->terrain->addTerrain(1301 ,3 , "forest");
            $this->terrain->addTerrain(1302 ,4 , "forest");
            $this->terrain->addTerrain(1301 ,1 , "forest");
            $this->terrain->addReinforceZone(1301,'B');
            $this->terrain->addTerrain(1301 ,2 , "forest");
            $this->terrain->addTerrain(1302 ,1 , "forest");
            $this->terrain->addReinforceZone(1302,'B');
            $this->terrain->addTerrain(1401 ,4 , "forest");
            $this->terrain->addTerrain(1401 ,3 , "forest");
            $this->terrain->addTerrain(1402 ,4 , "forest");
            $this->terrain->addTerrain(1401 ,1 , "forest");
            $this->terrain->addReinforceZone(1401,'B');
            $this->terrain->addTerrain(1401 ,2 , "forest");
            $this->terrain->addTerrain(1402 ,1 , "forest");
            $this->terrain->addReinforceZone(1402,'B');
            $this->terrain->addTerrain(1502 ,4 , "forest");
            $this->terrain->addTerrain(1502 ,3 , "forest");
            $this->terrain->addTerrain(1502 ,1 , "forest");
            $this->terrain->addReinforceZone(1502,'B');
            $this->terrain->addTerrain(1502 ,2 , "forest");
            $this->terrain->addTerrain(1503 ,1 , "forest");
            $this->terrain->addReinforceZone(1503,'B');
            $this->terrain->addTerrain(1602 ,4 , "forest");
            $this->terrain->addTerrain(1602 ,3 , "forest");
            $this->terrain->addTerrain(1602 ,1 , "forest");
            $this->terrain->addReinforceZone(1602,'B');
            $this->terrain->addTerrain(1702 ,3 , "forest");
            $this->terrain->addTerrain(1703 ,4 , "forest");
            $this->terrain->addTerrain(1702 ,1 , "forest");
            $this->terrain->addReinforceZone(1702,'B');
            $this->terrain->addTerrain(1702 ,2 , "forest");
            $this->terrain->addTerrain(1703 ,1 , "forest");
            $this->terrain->addReinforceZone(1703,'B');
            $this->terrain->addTerrain(1801 ,3 , "forest");
            $this->terrain->addTerrain(1802 ,4 , "forest");
            $this->terrain->addTerrain(1802 ,3 , "forest");
            $this->terrain->addTerrain(1803 ,4 , "forest");
            $this->terrain->addTerrain(1801 ,1 , "forest");
            $this->terrain->addReinforceZone(1801,'B');
            $this->terrain->addTerrain(1801 ,2 , "forest");
            $this->terrain->addTerrain(1802 ,1 , "forest");
            $this->terrain->addReinforceZone(1802,'B');
            $this->terrain->addTerrain(1802 ,2 , "forest");
            $this->terrain->addTerrain(1803 ,1 , "forest");
            $this->terrain->addReinforceZone(1803,'B');
            $this->terrain->addTerrain(1901 ,3 , "forest");
            $this->terrain->addTerrain(1902 ,4 , "forest");
            $this->terrain->addTerrain(1902 ,3 , "forest");
            $this->terrain->addTerrain(1903 ,4 , "forest");
            $this->terrain->addTerrain(1903 ,3 , "forest");
            $this->terrain->addTerrain(1904 ,4 , "forest");
            $this->terrain->addTerrain(1901 ,1 , "forest");
            $this->terrain->addReinforceZone(1901,'B');
            $this->terrain->addTerrain(1901 ,2 , "forest");
            $this->terrain->addTerrain(1902 ,1 , "forest");
            $this->terrain->addReinforceZone(1902,'B');
            $this->terrain->addTerrain(1902 ,2 , "forest");
            $this->terrain->addTerrain(1903 ,1 , "forest");
            $this->terrain->addReinforceZone(1903,'B');
            $this->terrain->addTerrain(1903 ,2 , "forest");
            $this->terrain->addTerrain(1904 ,1 , "forest");
            $this->terrain->addReinforceZone(1904,'B');
            $this->terrain->addTerrain(2001 ,4 , "forest");
            $this->terrain->addTerrain(2001 ,3 , "forest");
            $this->terrain->addTerrain(2002 ,4 , "forest");
            $this->terrain->addTerrain(2002 ,3 , "forest");
            $this->terrain->addTerrain(2003 ,4 , "forest");
            $this->terrain->addTerrain(2003 ,3 , "forest");
            $this->terrain->addTerrain(2004 ,4 , "forest");
            $this->terrain->addTerrain(2001 ,1 , "forest");
            $this->terrain->addTerrain(2001 ,2 , "forest");
            $this->terrain->addTerrain(2002 ,1 , "forest");
            $this->terrain->addTerrain(2002 ,2 , "forest");
            $this->terrain->addTerrain(2003 ,1 , "forest");
            $this->terrain->addTerrain(2003 ,2 , "forest");
            $this->terrain->addTerrain(2004 ,1 , "forest");
            $this->terrain->addTerrain(2101 ,3 , "forest");
            $this->terrain->addTerrain(2102 ,4 , "forest");
            $this->terrain->addTerrain(2102 ,3 , "forest");
            $this->terrain->addTerrain(2103 ,4 , "forest");
            $this->terrain->addTerrain(2103 ,3 , "forest");
            $this->terrain->addTerrain(2104 ,4 , "forest");
            $this->terrain->addTerrain(2104 ,3 , "forest");
            $this->terrain->addTerrain(2105 ,4 , "forest");
            $this->terrain->addTerrain(2101 ,1 , "forest");
            $this->terrain->addTerrain(2101 ,2 , "forest");
            $this->terrain->addTerrain(2102 ,1 , "forest");
            $this->terrain->addTerrain(2102 ,2 , "forest");
            $this->terrain->addTerrain(2103 ,1 , "forest");
            $this->terrain->addTerrain(2103 ,2 , "forest");
            $this->terrain->addTerrain(2104 ,1 , "forest");
            $this->terrain->addTerrain(2104 ,2 , "forest");
            $this->terrain->addTerrain(2105 ,1 , "forest");
            $this->terrain->addTerrain(2201 ,4 , "forest");
            $this->terrain->addTerrain(2201 ,3 , "forest");
            $this->terrain->addTerrain(2202 ,4 , "forest");
            $this->terrain->addTerrain(2202 ,3 , "forest");
            $this->terrain->addTerrain(2203 ,4 , "forest");
            $this->terrain->addTerrain(2203 ,3 , "forest");
            $this->terrain->addTerrain(2201 ,1 , "forest");
            $this->terrain->addTerrain(2201 ,2 , "forest");
            $this->terrain->addTerrain(2202 ,1 , "forest");
            $this->terrain->addTerrain(2202 ,2 , "forest");
            $this->terrain->addTerrain(2203 ,1 , "forest");
            $this->terrain->addTerrain(2301 ,3 , "forest");
            $this->terrain->addTerrain(2302 ,4 , "forest");
            $this->terrain->addTerrain(2302 ,3 , "forest");
            $this->terrain->addTerrain(2303 ,4 , "forest");
            $this->terrain->addTerrain(2303 ,3 , "forest");
            $this->terrain->addTerrain(2301 ,1 , "forest");
            $this->terrain->addTerrain(2301 ,2 , "forest");
            $this->terrain->addTerrain(2302 ,1 , "forest");
            $this->terrain->addTerrain(2302 ,2 , "forest");
            $this->terrain->addTerrain(2303 ,1 , "forest");
            $this->terrain->addTerrain(2401 ,4 , "forest");
            $this->terrain->addTerrain(2401 ,3 , "forest");
            $this->terrain->addTerrain(2402 ,4 , "forest");
            $this->terrain->addTerrain(2402 ,3 , "forest");
            $this->terrain->addTerrain(2401 ,1 , "forest");
            $this->terrain->addTerrain(2401 ,2 , "forest");
            $this->terrain->addTerrain(2402 ,1 , "forest");
            $this->terrain->addTerrain(1002 ,4 , "road");
            $this->terrain->addTerrain(1002 ,1 , "road");
            $this->terrain->addReinforceZone(1002,'B');
            $this->terrain->addTerrain(1002 ,2 , "road");
            $this->terrain->addTerrain(1003 ,1 , "road");
            $this->terrain->addReinforceZone(1003,'B');
            $this->terrain->addTerrain(1003 ,2 , "road");
            $this->terrain->addTerrain(1004 ,1 , "road");
            $this->terrain->addReinforceZone(1004,'B');
            $this->terrain->addTerrain(1105 ,4 , "road");
            $this->terrain->addTerrain(1105 ,1 , "road");
            $this->terrain->addReinforceZone(1105,'B');
            $this->terrain->addTerrain(1105 ,2 , "road");
            $this->terrain->addTerrain(1106 ,1 , "road");
            $this->terrain->addReinforceZone(1106,'B');
            $this->terrain->addTerrain(1206 ,4 , "road");
            $this->terrain->addTerrain(1206 ,1 , "road");
            $this->terrain->addReinforceZone(1206,'B');
            $this->terrain->addTerrain(1206 ,2 , "road");
            $this->terrain->addTerrain(1207 ,1 , "road");
            $this->terrain->addReinforceZone(1207,'B');
            $this->terrain->addTerrain(905 ,4 , "road");
            $this->terrain->addTerrain(905 ,1 , "road");
            $this->terrain->addReinforceZone(905,'B');
            $this->terrain->addTerrain(1005 ,4 , "road");
            $this->terrain->addTerrain(1005 ,1 , "road");
            $this->terrain->addReinforceZone(1005,'B');
            $this->terrain->addTerrain(1106 ,4 , "road");
            $this->terrain->addTerrain(1101 ,2 , "road");
            $this->terrain->addTerrain(1102 ,1 , "road");
            $this->terrain->addReinforceZone(1102,'B');
            $this->terrain->addTerrain(1102 ,2 , "road");
            $this->terrain->addReinforceZone(1102,'B');
            $this->terrain->addTerrain(1103 ,1 , "road");
            $this->terrain->addTerrain(1103 ,2 , "road");
            $this->terrain->addTerrain(1104 ,1 , "road");
            $this->terrain->addReinforceZone(1104,'B');
            $this->terrain->addTerrain(1104 ,2 , "road");
            $this->terrain->addTerrain(1207 ,2 , "road");
            $this->terrain->addTerrain(1208 ,1 , "road");
            $this->terrain->addTerrain(1309 ,4 , "road");
            $this->terrain->addTerrain(1309 ,1 , "road");
            $this->terrain->addTerrain(1409 ,4 , "road");
            $this->terrain->addTerrain(1409 ,1 , "road");
            $this->terrain->addTerrain(1510 ,4 , "road");
            $this->terrain->addTerrain(1510 ,1 , "road");
            $this->terrain->addTerrain(1510 ,2 , "road");
            $this->terrain->addTerrain(1511 ,1 , "road");
            $this->terrain->addTerrain(1511 ,2 , "road");
            $this->terrain->addTerrain(1612 ,4 , "road");
            $this->terrain->addTerrain(1612 ,1 , "road");
            $this->terrain->addReinforceZone(1612,'A');
            $this->terrain->addTerrain(1713 ,4 , "road");
            $this->terrain->addTerrain(1713 ,1 , "road");
            $this->terrain->addReinforceZone(1713,'A');
            $this->terrain->addTerrain(1813 ,4 , "road");
            $this->terrain->addTerrain(1813 ,1 , "road");
            $this->terrain->addReinforceZone(1813,'A');
            $this->terrain->addTerrain(1914 ,4 , "road");
            $this->terrain->addTerrain(208 ,4 , "road");
            $this->terrain->addTerrain(208 ,1 , "road");
            $this->terrain->addTerrain(208 ,2 , "road");
            $this->terrain->addTerrain(209 ,1 , "road");
            $this->terrain->addTerrain(310 ,4 , "road");
            $this->terrain->addTerrain(310 ,1 , "road");
            $this->terrain->addTerrain(410 ,4 , "road");
            $this->terrain->addTerrain(410 ,1 , "road");
            $this->terrain->addTerrain(510 ,3 , "road");
            $this->terrain->addTerrain(510 ,1 , "road");
            $this->terrain->addTerrain(610 ,4 , "road");
            $this->terrain->addTerrain(610 ,1 , "road");
            $this->terrain->addTerrain(711 ,4 , "road");
            $this->terrain->addTerrain(810 ,3 , "road");
            $this->terrain->addTerrain(810 ,1 , "road");
            $this->terrain->addTerrain(911 ,4 , "road");
            $this->terrain->addTerrain(911 ,1 , "road");
            $this->terrain->addTerrain(1011 ,4 , "road");
            $this->terrain->addTerrain(1011 ,1 , "road");
            $this->terrain->addTerrain(1111 ,3 , "road");
            $this->terrain->addTerrain(1111 ,1 , "road");
            $this->terrain->addTerrain(1211 ,4 , "road");
            $this->terrain->addTerrain(1211 ,1 , "road");
            $this->terrain->addTerrain(1312 ,4 , "road");
            $this->terrain->addTerrain(1312 ,1 , "road");
            $this->terrain->addTerrain(1411 ,3 , "road");
            $this->terrain->addTerrain(1411 ,1 , "road");
            $this->terrain->addTerrain(1512 ,4 , "road");
            $this->terrain->addTerrain(213 ,3 , "road");
            $this->terrain->addTerrain(213 ,1 , "road");
            $this->terrain->addTerrain(313 ,3 , "road");
            $this->terrain->addTerrain(312 ,2 , "road");
            $this->terrain->addTerrain(312 ,1 , "road");
            $this->terrain->addTerrain(311 ,1 , "road");
            $this->terrain->addTerrain(410 ,3 , "road");
            $this->terrain->addTerrain(811 ,4 , "road");
            $this->terrain->addTerrain(811 ,1 , "road");
            $this->terrain->addTerrain(912 ,4 , "road");
            $this->terrain->addTerrain(912 ,1 , "road");
            $this->terrain->addTerrain(1012 ,4 , "road");
            $this->terrain->addTerrain(1213 ,4 , "road");
            $this->terrain->addTerrain(1314 ,4 , "road");
            $this->terrain->addTerrain(1314 ,1 , "road");
            $this->terrain->addReinforceZone(1314,'A');
            $this->terrain->addTerrain(1414 ,4 , "road");
            $this->terrain->addTerrain(1414 ,1 , "road");
            $this->terrain->addReinforceZone(1414,'A');
            $this->terrain->addTerrain(1414 ,2 , "road");
            $this->terrain->addTerrain(1415 ,1 , "road");
            $this->terrain->addReinforceZone(1415,'A');
            $this->terrain->addTerrain(1516 ,4 , "road");
            $this->terrain->addTerrain(1516 ,1 , "road");
            $this->terrain->addReinforceZone(1516,'A');
            $this->terrain->addTerrain(1516 ,2 , "road");
            $this->terrain->addTerrain(1517 ,1 , "road");
            $this->terrain->addTerrain(1517 ,1 , "sunkenroad");
            $this->terrain->addReinforceZone(1517,'A');
            $this->terrain->addTerrain(1318 ,1 , "road");
            $this->terrain->addReinforceZone(1318,'A');
            $this->terrain->addTerrain(1318 ,2 , "road");
            $this->terrain->addTerrain(1319 ,1 , "road");
            $this->terrain->addReinforceZone(1319,'A');
            $this->terrain->addTerrain(1616 ,3 , "road");
            $this->terrain->addTerrain(1616 ,1 , "road");
            $this->terrain->addTerrain(1616 ,1 , "sunkenroad");
            $this->terrain->addReinforceZone(1616,'A');
            $this->terrain->addTerrain(1716 ,3 , "road");
            $this->terrain->addTerrain(1716 ,1 , "road");
            $this->terrain->addTerrain(1716 ,1 , "sunkenroad");
            $this->terrain->addReinforceZone(1716,'A');
            $this->terrain->addTerrain(1816 ,4 , "road");
            $this->terrain->addTerrain(1816 ,1 , "road");
            $this->terrain->addTerrain(1816 ,1 , "sunkenroad");
            $this->terrain->addReinforceZone(1816,'A');
            $this->terrain->addTerrain(1916 ,3 , "road");
            $this->terrain->addTerrain(1916 ,1 , "road");
            $this->terrain->addTerrain(1916 ,1 , "sunkenroad");
            $this->terrain->addReinforceZone(1916,'A');
            $this->terrain->addTerrain(1915 ,2 , "road");
            $this->terrain->addTerrain(1915 ,1 , "road");
            $this->terrain->addTerrain(1915 ,1 , "sunkenroad");
            $this->terrain->addReinforceZone(1915,'A');
            $this->terrain->addTerrain(2014 ,3 , "road");
            $this->terrain->addTerrain(1617 ,4 , "road");
            $this->terrain->addTerrain(1718 ,4 , "road");
            $this->terrain->addTerrain(1718 ,1 , "road");
            $this->terrain->addReinforceZone(1718,'A');
            $this->terrain->addTerrain(1718 ,2 , "road");
            $this->terrain->addTerrain(1719 ,1 , "road");
            $specialHexB[] = 1719;
            $this->terrain->addReinforceZone(1719,'A');
            $this->terrain->addTerrain(2014 ,2 , "road");
            $this->terrain->addTerrain(2015 ,1 , "road");
            $this->terrain->addReinforceZone(2015,'A');
            $this->terrain->addTerrain(2015 ,2 , "road");
            $this->terrain->addTerrain(2016 ,1 , "road");
            $this->terrain->addReinforceZone(2016,'A');
            $this->terrain->addTerrain(2118 ,2 , "road");
            $this->terrain->addTerrain(2119 ,1 , "road");
            $specialHexB[] = 2119;
            $this->terrain->addReinforceZone(2119,'A');
            $this->terrain->addTerrain(1912 ,2 , "road");
            $this->terrain->addTerrain(1912 ,1 , "road");
            $this->terrain->addReinforceZone(1912,'A');
            $this->terrain->addTerrain(1911 ,2 , "road");
            $this->terrain->addTerrain(1911 ,1 , "road");
            $this->terrain->addReinforceZone(1911,'A');
            $this->terrain->addTerrain(2010 ,3 , "road");
            $this->terrain->addTerrain(2010 ,1 , "road");
            $this->terrain->addReinforceZone(2010,'A');
            $this->terrain->addTerrain(2110 ,3 , "road");
            $this->terrain->addTerrain(2110 ,1 , "road");
            $this->terrain->addReinforceZone(2110,'A');
            $this->terrain->addTerrain(2209 ,3 , "road");
            $this->terrain->addTerrain(2208 ,2 , "road");
            $this->terrain->addTerrain(2208 ,1 , "road");
            $this->terrain->addReinforceZone(2208,'A');
            $this->terrain->addTerrain(2308 ,3 , "road");
            $this->terrain->addTerrain(2308 ,1 , "road");
            $this->terrain->addReinforceZone(2308,'A');
            $this->terrain->addTerrain(2407 ,3 , "road");
            $this->terrain->addTerrain(2408 ,1 , "blocked");
            $this->terrain->addTerrain(2309 ,1 , "blocked");
            $this->terrain->addTerrain(2310 ,1 , "blocked");
            $this->terrain->addTerrain(2210 ,1 , "blocked");
            $this->terrain->addTerrain(2111 ,1 , "blocked");
            $this->terrain->addTerrain(2011 ,1 , "blocked");
            $this->terrain->addTerrain(2012 ,1 , "blocked");
            $this->terrain->addTerrain(2013 ,1 , "blocked");
            $this->terrain->addTerrain(2114 ,1 , "blocked");
            $this->terrain->addTerrain(2214 ,1 , "blocked");
            $this->terrain->addTerrain(2315 ,1 , "blocked");
            $this->terrain->addTerrain(2415 ,1 , "blocked");
            $this->terrain->addTerrain(2215 ,1 , "blocked");
            $this->terrain->addTerrain(2216 ,1 , "blocked");
            $this->terrain->addTerrain(2217 ,1 , "blocked");
            $this->terrain->addTerrain(2218 ,1 , "blocked");
            $this->terrain->addTerrain(2219 ,1 , "blocked");
            $this->terrain->addTerrain(1214 ,1 , "blocked");
            $this->terrain->addTerrain(913 ,1 , "blocked");
            $this->terrain->addTerrain(216 ,1 , "blocked");
            $this->terrain->addTerrain(117 ,1 , "blocked");
            $this->terrain->addReinforceZone(416,'A');
            $this->terrain->addReinforceZone(516,'A');
            $this->terrain->addReinforceZone(615,'A');
            $this->terrain->addReinforceZone(715,'A');
            $this->terrain->addReinforceZone(814,'A');
            $this->terrain->addReinforceZone(1413,'A');
            $this->terrain->addReinforceZone(1513,'A');
            $this->terrain->addReinforceZone(1712,'A');
            $this->terrain->addReinforceZone(1711,'A');
            $this->terrain->addReinforceZone(1810,'A');
            $this->terrain->addReinforceZone(1910,'A');
            $this->terrain->addReinforceZone(2009,'A');
            $this->terrain->addReinforceZone(2109,'A');
            $this->terrain->addReinforceZone(119,'A');
            $this->terrain->addReinforceZone(218,'A');
            $this->terrain->addReinforceZone(219,'A');
            $this->terrain->addReinforceZone(318,'A');
            $this->terrain->addReinforceZone(319,'A');
            $this->terrain->addReinforceZone(418,'A');
            $this->terrain->addReinforceZone(419,'A');
            $this->terrain->addReinforceZone(517,'A');
            $this->terrain->addReinforceZone(518,'A');
            $this->terrain->addReinforceZone(519,'A');
            $this->terrain->addReinforceZone(616,'A');
            $this->terrain->addReinforceZone(617,'A');
            $this->terrain->addReinforceZone(618,'A');
            $this->terrain->addReinforceZone(619,'A');
            $this->terrain->addReinforceZone(716,'A');
            $this->terrain->addReinforceZone(717,'A');
            $this->terrain->addReinforceZone(718,'A');
            $this->terrain->addReinforceZone(719,'A');
            $this->terrain->addReinforceZone(815,'A');
            $this->terrain->addReinforceZone(816,'A');
            $this->terrain->addReinforceZone(817,'A');
            $this->terrain->addReinforceZone(818,'A');
            $this->terrain->addReinforceZone(819,'A');
            $this->terrain->addReinforceZone(915,'A');
            $this->terrain->addReinforceZone(916,'A');
            $this->terrain->addReinforceZone(917,'A');
            $this->terrain->addReinforceZone(919,'A');
            $this->terrain->addReinforceZone(1014,'A');
            $this->terrain->addReinforceZone(1015,'A');
            $this->terrain->addReinforceZone(1016,'A');
            $this->terrain->addReinforceZone(1019,'A');
            $this->terrain->addReinforceZone(1115,'A');
            $this->terrain->addReinforceZone(1116,'A');
            $this->terrain->addReinforceZone(1117,'A');
            $this->terrain->addReinforceZone(1118,'A');
            $this->terrain->addReinforceZone(1119,'A');
            $this->terrain->addReinforceZone(1215,'A');
            $this->terrain->addReinforceZone(1216,'A');
            $this->terrain->addReinforceZone(1217,'A');
            $this->terrain->addReinforceZone(1218,'A');
            $this->terrain->addReinforceZone(1219,'A');
            $this->terrain->addReinforceZone(1317,'A');
            $this->terrain->addReinforceZone(1514,'A');
            $this->terrain->addReinforceZone(1515,'A');
            $this->terrain->addReinforceZone(1518,'A');
            $this->terrain->addReinforceZone(1519,'A');
            $this->terrain->addReinforceZone(1613,'A');
            $this->terrain->addReinforceZone(1614,'A');
            $this->terrain->addReinforceZone(1615,'A');
            $this->terrain->addReinforceZone(1619,'A');
            $this->terrain->addReinforceZone(1714,'A');
            $this->terrain->addReinforceZone(1715,'A');
            $this->terrain->addReinforceZone(1717,'A');
            $this->terrain->addReinforceZone(1811,'A');
            $this->terrain->addReinforceZone(1812,'A');
            $this->terrain->addReinforceZone(1814,'A');
            $this->terrain->addReinforceZone(1815,'A');
            $this->terrain->addReinforceZone(1817,'A');
            $this->terrain->addReinforceZone(1818,'A');
            $this->terrain->addReinforceZone(1819,'A');
            $this->terrain->addReinforceZone(1917,'A');
            $this->terrain->addReinforceZone(1918,'A');
            $this->terrain->addReinforceZone(1919,'A');
            $this->terrain->addReinforceZone(2018,'A');
            $this->terrain->addReinforceZone(2019,'A');
            $this->terrain->addReinforceZone(2115,'A');
            $this->terrain->addReinforceZone(2116,'A');
            $this->terrain->addReinforceZone(2209,'A');
            $this->terrain->addReinforceZone(207,'B');
            $this->terrain->addReinforceZone(308,'B');
            $this->terrain->addReinforceZone(407,'B');
            $this->terrain->addReinforceZone(507,'B');
            $this->terrain->addReinforceZone(607,'B');
            $this->terrain->addReinforceZone(707,'B');
            $this->terrain->addReinforceZone(806,'B');
            $this->terrain->addReinforceZone(906,'B');
            $this->terrain->addReinforceZone(1006,'B');
            $this->terrain->addReinforceZone(1307,'B');
            $this->terrain->addReinforceZone(1406,'B');
            $this->terrain->addReinforceZone(1506,'B');
            $this->terrain->addReinforceZone(1605,'B');
            $this->terrain->addReinforceZone(1705,'B');
            $this->terrain->addReinforceZone(1804,'B');
            $this->terrain->addReinforceZone(105,'B');
            $this->terrain->addReinforceZone(307,'B');
            $this->terrain->addReinforceZone(406,'B');
            $this->terrain->addReinforceZone(403,'B');
            $this->terrain->addReinforceZone(506,'B');
            $this->terrain->addReinforceZone(606,'B');
            $this->terrain->addReinforceZone(706,'B');
            $this->terrain->addReinforceZone(705,'B');
            $this->terrain->addReinforceZone(704,'B');
            $this->terrain->addReinforceZone(805,'B');
            $this->terrain->addReinforceZone(904,'B');
            $this->terrain->addReinforceZone(1001,'B');
            $this->terrain->addReinforceZone(1205,'B');
            $this->terrain->addReinforceZone(1204,'B');
            $this->terrain->addReinforceZone(1203,'B');
            $this->terrain->addReinforceZone(1202,'B');
            $this->terrain->addReinforceZone(1306,'B');
            $this->terrain->addReinforceZone(1305,'B');
            $this->terrain->addReinforceZone(1304,'B');
            $this->terrain->addReinforceZone(1303,'B');
            $this->terrain->addReinforceZone(1405,'B');
            $this->terrain->addReinforceZone(1403,'B');
            $this->terrain->addReinforceZone(1404,'B');
            $this->terrain->addReinforceZone(1505,'B');
            $this->terrain->addReinforceZone(1504,'B');
            $this->terrain->addReinforceZone(1501,'B');
            $this->terrain->addReinforceZone(1601,'B');
            $this->terrain->addReinforceZone(1603,'B');
            $this->terrain->addReinforceZone(1604,'B');
            $this->terrain->addReinforceZone(1704,'B');
            $this->terrain->addReinforceZone(1701,'B');




            $this->prussianSpecialHexes = $specialHexA;
            $this->austrianSpecialHexes = $specialHexB;
            foreach ($specialHexA as $specialHexId) {
                $specialHexes[$specialHexId] = PRUSSIAN_FORCE;
            }
            foreach ($specialHexB as $specialHexId) {
                $specialHexes[$specialHexId] = AUSTRIAN_FORCE;
            }
            $this->mapData->setSpecialHexes($specialHexes);


            // end terrain data ----------------------------------------

        }
    }
}