<?php
set_include_path(__DIR__ . "/Minden" . PATH_SEPARATOR . get_include_path());
require_once "JagCore.php";

/* comment */

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



class Minden extends JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>1];
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

    static function getHeader($name, $playerData, $arg = false)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "MindenHeader.php";

    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "French";
        $deployOne = $playerTwo = "AngloAllied";
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

    public function terrainInit($terrainName){
        parent::terrainInit($terrainName);
        $specialHexes = $this->mapData->specialHexes;
        foreach($specialHexes as $hexId => $forceId){
            if($forceId == ANGLO_FORCE){
                $this->angloSpecialHexes[] = $hexId;
            }else{
                $this->frenchSpecialHexes[] = $hexId;
            }
        }
    }
    public function terrainGen($hexDocId){
        parent::terrainGen($hexDocId);

        for ($col = 2200; $col <= 2500; $col += 100) {
            for ($row = 1; $row <= 18; $row++) {
                $this->terrain->addReinforceZone($col + $row, 'B');
            }
        }

        for ($col = 100; $col <= 700; $col += 100) {
            for ($row = 1; $row <= 18; $row++) {
                $this->terrain->addReinforceZone($col + $row, 'A');
            }
        }


    }

    public function init()
    {

        $artRange = 3;

        for ($i = 0; $i < 19; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
        }
        for ($i = 0; $i < 12; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'cavalry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'cavalry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "French", false, 'artillery');
        }
        $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 5, 5, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "French", false, 'artillery');


        for ($i = 0; $i < 20; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
        }
        for ($i = 0; $i < 6; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
        }
        $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "AngloAllied", false, 'artillery');
        }

        for ($i = 0; $i < 2; $i++) {
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
            $this->victory = new Victory("Mollwitz/Minden/mindenVictoryCore.php", $data);
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
            $this->victory = new Victory("Mollwitz/Minden/mindenVictoryCore.php");

            $this->mapData->setData(25, 18, "js/MindenExport1.jpg");
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
//            $this->players = array("", "", "");
//            $this->playerData = new stdClass();
//            for ($player = 0; $player <= 2; $player++) {
//                $this->playerData->${player} = new stdClass();
//                $this->playerData->${player}->mapWidth = "auto";
//                $this->playerData->${player}->mapHeight = "auto";
//                $this->playerData->${player}->unitSize = "32px";
//                $this->playerData->${player}->unitFontSize = "12px";
//                $this->playerData->${player}->unitMargin = "-21px";
//                $this->mapViewer[$player]->setData(50, 80.5403625519528, // originX, originY
//                    26.846787517317598, 26.846787517317598, // top hexagon height, bottom hexagon height
//                    15.5, 31// hexagon edge width, hexagon center width
//                );
//            }

            // game data
            $this->gameRules->setMaxTurn(14);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data

            $i = 0;

            // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

//            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
//            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
//            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
//            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
//            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
//            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
//            $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
//            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
//            $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
//            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);
//            $this->terrain->addAltEntranceCost('swamp','artillery','blocked');
//
//
//            for ($col = 100; $col <= 2500; $col += 100) {
//                for ($row = 1; $row <= 18; $row++) {
//                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");
//
//                }
//            }
//            $specialHexA = [];
//            $specialHexB = [];
//            $this->terrain->addTerrain(902, 1, "town");
//            $this->terrain->addTerrain(1103, 1, "town");
//            $this->terrain->addTerrain(505, 1, "town");
//            $specialHexA[] = 505;
//            $this->terrain->addTerrain(806, 1, "town");
//            $specialHexA[] = 806;
//            $this->terrain->addReinforceZone(806, 'A');
//            $this->terrain->addTerrain(1601, 1, "town");
//            $this->terrain->addReinforceZone(1601, 'B');
//            $this->terrain->addTerrain(1804, 1, "town");
//            $this->terrain->addReinforceZone(1804, 'B');
//            $this->terrain->addTerrain(2106, 1, "town");
//            $specialHexB[] = 2106;
//            $this->terrain->addReinforceZone(2106, 'B');
//            $this->terrain->addTerrain(2107, 1, "town");
//            $specialHexB[] = 2107;
//            $this->terrain->addReinforceZone(2107, 'B');
//            $this->terrain->addTerrain(2106, 2, "town");
//            $this->terrain->addTerrain(2206, 3, "town");
//            $this->terrain->addTerrain(2206, 3, "river");
//            $this->terrain->addTerrain(2206, 3, "trail");
//            $this->terrain->addTerrain(2206, 1, "town");
//            $specialHexB[] = 2206;
//            $this->terrain->addTerrain(411, 1, "town");
//            $specialHexA[] = 411;
//            $this->terrain->addTerrain(214, 1, "town");
//            $specialHexA[] = 214;
//            $this->terrain->addTerrain(1118, 1, "town");
//            $this->terrain->addReinforceZone(1118, 'A');
//            $this->terrain->addTerrain(1215, 1, "town");
//            $specialHexA[] = 1215;
//            $this->terrain->addReinforceZone(1215, 'A');
//            $this->terrain->addTerrain(101, 1, "forest");
//            $this->terrain->addTerrain(101, 2, "forest");
//            $this->terrain->addTerrain(102, 1, "forest");
//            $this->terrain->addTerrain(102, 2, "forest");
//            $this->terrain->addTerrain(103, 1, "forest");
//            $this->terrain->addTerrain(103, 2, "forest");
//            $this->terrain->addTerrain(104, 1, "forest");
//            $this->terrain->addTerrain(104, 1, "road");
//            $this->terrain->addTerrain(204, 4, "forest");
//            $this->terrain->addTerrain(204, 4, "road");
//            $this->terrain->addTerrain(204, 1, "forest");
//            $this->terrain->addTerrain(204, 1, "road");
//            $this->terrain->addTerrain(305, 4, "forest");
//            $this->terrain->addTerrain(305, 1, "forest");
//            $this->terrain->addTerrain(404, 3, "forest");
//            $this->terrain->addTerrain(404, 1, "forest");
//            $this->terrain->addTerrain(404, 1, "road");
//            $this->terrain->addTerrain(404, 4, "forest");
//            $this->terrain->addTerrain(404, 4, "road");
//            $this->terrain->addTerrain(304, 1, "forest");
//            $this->terrain->addTerrain(304, 1, "road");
//            $this->terrain->addTerrain(304, 3, "forest");
//            $this->terrain->addTerrain(304, 3, "road");
//            $this->terrain->addTerrain(201, 4, "forest");
//            $this->terrain->addTerrain(201, 3, "forest");
//            $this->terrain->addTerrain(202, 4, "forest");
//            $this->terrain->addTerrain(202, 3, "forest");
//            $this->terrain->addTerrain(201, 1, "forest");
//            $this->terrain->addTerrain(201, 2, "forest");
//            $this->terrain->addTerrain(202, 1, "forest");
//            $this->terrain->addTerrain(301, 3, "forest");
//            $this->terrain->addTerrain(302, 4, "forest");
//            $this->terrain->addTerrain(302, 3, "forest");
//            $this->terrain->addTerrain(301, 2, "forest");
//            $this->terrain->addTerrain(302, 1, "forest");
//            $this->terrain->addTerrain(301, 1, "forest");
//            $this->terrain->addTerrain(401, 4, "forest");
//            $this->terrain->addTerrain(401, 3, "forest");
//            $this->terrain->addTerrain(401, 1, "forest");
//            $this->terrain->addTerrain(109, 1, "forest");
//            $this->terrain->addTerrain(109, 2, "forest");
//            $this->terrain->addTerrain(110, 1, "forest");
//            $this->terrain->addTerrain(110, 2, "forest");
//            $this->terrain->addTerrain(111, 1, "forest");
//            $this->terrain->addTerrain(111, 1, "road");
//            $this->terrain->addTerrain(111, 2, "forest");
//            $this->terrain->addTerrain(112, 1, "forest");
//            $this->terrain->addTerrain(210, 4, "forest");
//            $this->terrain->addTerrain(210, 3, "forest");
//            $this->terrain->addTerrain(210, 3, "road");
//            $this->terrain->addTerrain(211, 4, "forest");
//            $this->terrain->addTerrain(211, 3, "forest");
//            $this->terrain->addTerrain(210, 1, "forest");
//            $this->terrain->addTerrain(210, 1, "road");
//            $this->terrain->addTerrain(210, 2, "forest");
//            $this->terrain->addTerrain(211, 1, "forest");
//            $this->terrain->addTerrain(808, 1, "forest");
//            $this->terrain->addReinforceZone(808, 'A');
//            $this->terrain->addTerrain(908, 3, "forest");
//            $this->terrain->addTerrain(909, 4, "forest");
//            $this->terrain->addTerrain(908, 1, "forest");
//            $this->terrain->addTerrain(908, 2, "forest");
//            $this->terrain->addTerrain(909, 1, "forest");
//            $this->terrain->addTerrain(909, 1, "road");
//            $this->terrain->addTerrain(1008, 4, "forest");
//            $this->terrain->addTerrain(1008, 3, "forest");
//            $this->terrain->addTerrain(1008, 3, "road");
//            $this->terrain->addTerrain(1008, 1, "forest");
//            $this->terrain->addTerrain(1008, 1, "road");
//            $this->terrain->addTerrain(412, 1, "forest");
//            $this->terrain->addTerrain(412, 1, "road");
//            $this->terrain->addTerrain(512, 3, "forest");
//            $this->terrain->addTerrain(512, 1, "forest");
//            $this->terrain->addTerrain(117, 1, "forest");
//            $this->terrain->addTerrain(117, 2, "forest");
//            $this->terrain->addTerrain(118, 1, "forest");
//            $this->terrain->addTerrain(217, 4, "forest");
//            $this->terrain->addTerrain(217, 3, "forest");
//            $this->terrain->addTerrain(218, 4, "forest");
//            $this->terrain->addTerrain(217, 1, "forest");
//            $this->terrain->addTerrain(217, 2, "forest");
//            $this->terrain->addTerrain(218, 1, "forest");
//            $this->terrain->addTerrain(318, 4, "forest");
//            $this->terrain->addTerrain(318, 3, "forest");
//            $this->terrain->addTerrain(318, 1, "forest");
//            $this->terrain->addTerrain(515, 1, "forest");
//            $this->terrain->addTerrain(515, 1, "road");
//            $this->terrain->addTerrain(614, 3, "forest");
//            $this->terrain->addTerrain(615, 4, "forest");
//            $this->terrain->addTerrain(615, 4, "road");
//            $this->terrain->addTerrain(614, 1, "forest");
//            $this->terrain->addTerrain(614, 2, "forest");
//            $this->terrain->addTerrain(615, 1, "forest");
//            $this->terrain->addTerrain(615, 1, "road");
//            $this->terrain->addTerrain(615, 2, "forest");
//            $this->terrain->addTerrain(615, 2, "road");
//            $this->terrain->addTerrain(616, 1, "forest");
//            $this->terrain->addTerrain(616, 1, "road");
//            $this->terrain->addTerrain(714, 3, "forest");
//            $this->terrain->addTerrain(715, 4, "forest");
//            $this->terrain->addTerrain(715, 3, "forest");
//            $this->terrain->addTerrain(717, 4, "forest");
//            $this->terrain->addTerrain(717, 4, "road");
//            $this->terrain->addTerrain(717, 1, "forest");
//            $this->terrain->addTerrain(717, 1, "road");
//            $this->terrain->addTerrain(717, 2, "forest");
//            $this->terrain->addTerrain(718, 1, "forest");
//            $this->terrain->addTerrain(714, 1, "forest");
//            $this->terrain->addTerrain(714, 2, "forest");
//            $this->terrain->addTerrain(715, 1, "forest");
//            $this->terrain->addTerrain(814, 4, "forest");
//            $this->terrain->addTerrain(814, 3, "forest");
//            $this->terrain->addTerrain(814, 1, "forest");
//            $this->terrain->addReinforceZone(814, 'A');
//            $this->terrain->addTerrain(1016, 1, "forest");
//            $this->terrain->addReinforceZone(1016, 'A');
//            $this->terrain->addTerrain(1016, 2, "forest");
//            $this->terrain->addTerrain(1017, 1, "forest");
//            $this->terrain->addTerrain(1017, 1, "road");
//            $this->terrain->addReinforceZone(1017, 'A');
//            $this->terrain->addTerrain(1017, 3, "forest");
//            $this->terrain->addTerrain(1017, 3, "road");
//            $this->terrain->addTerrain(918, 1, "forest");
//            $this->terrain->addTerrain(918, 1, "road");
//            $this->terrain->addReinforceZone(918, 'A');
//            $this->terrain->addTerrain(1318, 1, "forest");
//            $this->terrain->addTerrain(1417, 3, "forest");
//            $this->terrain->addTerrain(1417, 1, "forest");
//            $this->terrain->addTerrain(1517, 3, "forest");
//            $this->terrain->addTerrain(1517, 1, "forest");
//            $this->terrain->addTerrain(1516, 2, "forest");
//            $this->terrain->addTerrain(1516, 1, "forest");
//            $this->terrain->addTerrain(1615, 3, "forest");
//            $this->terrain->addTerrain(1615, 1, "forest");
//            $this->terrain->addTerrain(1614, 2, "forest");
//            $this->terrain->addTerrain(1614, 1, "forest");
//            $this->terrain->addReinforceZone(1614, 'B');
//            $this->terrain->addTerrain(1714, 3, "forest");
//            $this->terrain->addTerrain(1714, 1, "forest");
//            $this->terrain->addReinforceZone(1714, 'B');
//            $this->terrain->addTerrain(1713, 2, "forest");
//            $this->terrain->addTerrain(1713, 1, "forest");
//            $this->terrain->addReinforceZone(1713, 'B');
//            $this->terrain->addTerrain(1812, 3, "forest");
//            $this->terrain->addTerrain(1812, 1, "forest");
//            $this->terrain->addReinforceZone(1812, 'B');
//            $this->terrain->addTerrain(1811, 2, "forest");
//            $this->terrain->addTerrain(1811, 1, "forest");
//            $this->terrain->addTerrain(1811, 1, "road");
//            $this->terrain->addReinforceZone(1811, 'B');
//            $this->terrain->addTerrain(1911, 3, "forest");
//            $this->terrain->addTerrain(1911, 1, "forest");
//            $this->terrain->addReinforceZone(1911, 'B');
//            $this->terrain->addTerrain(1910, 2, "forest");
//            $this->terrain->addTerrain(1910, 1, "forest");
//            $this->terrain->addReinforceZone(1910, 'B');
//            $this->terrain->addTerrain(1407, 1, "forest");
//            $this->terrain->addTerrain(1407, 1, "road");
//            $this->terrain->addTerrain(1407, 2, "forest");
//            $this->terrain->addTerrain(1408, 1, "forest");
//            $this->terrain->addTerrain(1508, 4, "forest");
//            $this->terrain->addTerrain(1508, 3, "forest");
//            $this->terrain->addTerrain(1508, 1, "forest");
//            $this->terrain->addReinforceZone(1508, 'B');
//            $this->terrain->addTerrain(1608, 4, "forest");
//            $this->terrain->addTerrain(1608, 1, "forest");
//            $this->terrain->addReinforceZone(1608, 'B');
//            $this->terrain->addTerrain(1708, 3, "forest");
//            $this->terrain->addTerrain(1708, 1, "forest");
//            $this->terrain->addTerrain(1708, 1, "road");
//            $this->terrain->addReinforceZone(1708, 'B');
//            $this->terrain->addTerrain(2207, 4, "forest");
//            $this->terrain->addTerrain(2207, 4, "river");
//            $this->terrain->addTerrain(2207, 1, "forest");
//            $this->terrain->addTerrain(2207, 2, "forest");
//            $this->terrain->addTerrain(2208, 1, "forest");
//            $this->terrain->addTerrain(2208, 2, "forest");
//            $this->terrain->addTerrain(2209, 1, "forest");
//            $this->terrain->addTerrain(2209, 2, "forest");
//            $this->terrain->addTerrain(2210, 1, "forest");
//            $this->terrain->addTerrain(2210, 2, "forest");
//            $this->terrain->addTerrain(2211, 1, "forest");
//            $this->terrain->addTerrain(2211, 1, "road");
//            $this->terrain->addTerrain(2211, 3, "forest");
//            $this->terrain->addTerrain(2211, 3, "river");
//            $this->terrain->addTerrain(2112, 1, "forest");
//            $this->terrain->addReinforceZone(2112, 'B');
//            $this->terrain->addTerrain(2112, 2, "forest");
//            $this->terrain->addTerrain(2113, 1, "forest");
//            $this->terrain->addReinforceZone(2113, 'B');
//            $this->terrain->addTerrain(2113, 2, "forest");
//            $this->terrain->addTerrain(2114, 1, "forest");
//            $this->terrain->addReinforceZone(2114, 'B');
//            $this->terrain->addTerrain(2114, 3, "forest");
//            $this->terrain->addTerrain(2014, 1, "forest");
//            $this->terrain->addReinforceZone(2014, 'B');
//            $this->terrain->addTerrain(2014, 3, "forest");
//            $this->terrain->addTerrain(1915, 1, "forest");
//            $this->terrain->addReinforceZone(1915, 'B');
//            $this->terrain->addTerrain(1915, 2, "forest");
//            $this->terrain->addTerrain(1916, 1, "forest");
//            $this->terrain->addReinforceZone(1916, 'B');
//            $this->terrain->addTerrain(1916, 2, "forest");
//            $this->terrain->addTerrain(1917, 1, "forest");
//            $this->terrain->addTerrain(1917, 2, "forest");
//            $this->terrain->addTerrain(1918, 1, "forest");
//            $this->terrain->addTerrain(2018, 4, "forest");
//            $this->terrain->addTerrain(2018, 1, "forest");
//            $this->terrain->addTerrain(2313, 1, "forest");
//            $this->terrain->addTerrain(2314, 1, "forest");
//            $this->terrain->addTerrain(2412, 3, "forest");
//            $this->terrain->addTerrain(2413, 4, "forest");
//            $this->terrain->addTerrain(2413, 3, "forest");
//            $this->terrain->addTerrain(2415, 3, "forest");
//            $this->terrain->addTerrain(2416, 4, "forest");
//            $this->terrain->addTerrain(2416, 3, "forest");
//            $this->terrain->addTerrain(2417, 4, "forest");
//            $this->terrain->addTerrain(2417, 3, "forest");
//            $this->terrain->addTerrain(2318, 1, "forest");
//            $this->terrain->addTerrain(2317, 2, "forest");
//            $this->terrain->addTerrain(2317, 1, "forest");
//            $this->terrain->addTerrain(2316, 2, "forest");
//            $this->terrain->addTerrain(2316, 1, "forest");
//            $this->terrain->addTerrain(2409, 1, "forest");
//            $this->terrain->addTerrain(2409, 2, "forest");
//            $this->terrain->addTerrain(2410, 1, "forest");
//            $this->terrain->addTerrain(2410, 2, "forest");
//            $this->terrain->addTerrain(2411, 1, "forest");
//            $this->terrain->addTerrain(2411, 2, "forest");
//            $this->terrain->addTerrain(2412, 1, "forest");
//            $this->terrain->addTerrain(2412, 2, "forest");
//            $this->terrain->addTerrain(2413, 1, "forest");
//            $this->terrain->addTerrain(2413, 2, "forest");
//            $this->terrain->addTerrain(2414, 1, "forest");
//            $this->terrain->addTerrain(2414, 2, "forest");
//            $this->terrain->addTerrain(2415, 1, "forest");
//            $this->terrain->addTerrain(2415, 2, "forest");
//            $this->terrain->addTerrain(2416, 1, "forest");
//            $this->terrain->addTerrain(2416, 2, "forest");
//            $this->terrain->addTerrain(2417, 1, "forest");
//            $this->terrain->addTerrain(2417, 2, "forest");
//            $this->terrain->addTerrain(2418, 1, "forest");
//            $this->terrain->addTerrain(2509, 3, "forest");
//            $this->terrain->addTerrain(2510, 4, "forest");
//            $this->terrain->addTerrain(2510, 3, "forest");
//            $this->terrain->addTerrain(2511, 4, "forest");
//            $this->terrain->addTerrain(2511, 3, "forest");
//            $this->terrain->addTerrain(2512, 4, "forest");
//            $this->terrain->addTerrain(2512, 3, "forest");
//            $this->terrain->addTerrain(2513, 4, "forest");
//            $this->terrain->addTerrain(2513, 3, "forest");
//            $this->terrain->addTerrain(2514, 4, "forest");
//            $this->terrain->addTerrain(2514, 3, "forest");
//            $this->terrain->addTerrain(2515, 4, "forest");
//            $this->terrain->addTerrain(2515, 3, "forest");
//            $this->terrain->addTerrain(2516, 4, "forest");
//            $this->terrain->addTerrain(2516, 3, "forest");
//            $this->terrain->addTerrain(2517, 4, "forest");
//            $this->terrain->addTerrain(2517, 3, "forest");
//            $this->terrain->addTerrain(2518, 4, "forest");
//            $this->terrain->addTerrain(2518, 3, "forest");
//            $this->terrain->addTerrain(2508, 1, "forest");
//            $this->terrain->addTerrain(2508, 1, "road");
//            $this->terrain->addTerrain(2508, 2, "forest");
//            $this->terrain->addTerrain(2509, 1, "forest");
//            $this->terrain->addTerrain(2509, 2, "forest");
//            $this->terrain->addTerrain(2510, 1, "forest");
//            $this->terrain->addTerrain(2510, 2, "forest");
//            $this->terrain->addTerrain(2511, 1, "forest");
//            $this->terrain->addTerrain(2511, 2, "forest");
//            $this->terrain->addTerrain(2512, 1, "forest");
//            $this->terrain->addTerrain(2512, 2, "forest");
//            $this->terrain->addTerrain(2513, 1, "forest");
//            $this->terrain->addTerrain(2513, 2, "forest");
//            $this->terrain->addTerrain(2514, 1, "forest");
//            $this->terrain->addTerrain(2514, 2, "forest");
//            $this->terrain->addTerrain(2515, 1, "forest");
//            $this->terrain->addTerrain(2515, 2, "forest");
//            $this->terrain->addTerrain(2516, 1, "forest");
//            $this->terrain->addTerrain(2516, 2, "forest");
//            $this->terrain->addTerrain(2517, 1, "forest");
//            $this->terrain->addTerrain(2517, 2, "forest");
//            $this->terrain->addTerrain(2518, 1, "forest");
//            $this->terrain->addTerrain(2206, 4, "town");
//            $this->terrain->addTerrain(2206, 4, "river");
//            $this->terrain->addTerrain(2206, 4, "trail");
//            $this->terrain->addTerrain(2201, 1, "forest");
//            $this->terrain->addTerrain(2201, 1, "road");
//            $this->terrain->addTerrain(2301, 3, "forest");
//            $this->terrain->addTerrain(2301, 3, "road");
//            $this->terrain->addTerrain(2302, 4, "forest");
//            $this->terrain->addTerrain(2301, 1, "forest");
//            $this->terrain->addTerrain(2301, 1, "road");
//            $this->terrain->addTerrain(2301, 2, "forest");
//            $this->terrain->addTerrain(2302, 1, "forest");
//            $this->terrain->addTerrain(2401, 4, "forest");
//            $this->terrain->addTerrain(2401, 3, "forest");
//            $this->terrain->addTerrain(2402, 4, "forest");
//            $this->terrain->addTerrain(2401, 1, "forest");
//            $this->terrain->addTerrain(2401, 2, "forest");
//            $this->terrain->addTerrain(2402, 1, "forest");
//            $this->terrain->addTerrain(2501, 3, "forest");
//            $this->terrain->addTerrain(2502, 4, "forest");
//            $this->terrain->addTerrain(2502, 3, "forest");
//            $this->terrain->addTerrain(2503, 4, "forest");
//            $this->terrain->addTerrain(2501, 1, "forest");
//            $this->terrain->addTerrain(2501, 2, "forest");
//            $this->terrain->addTerrain(2502, 1, "forest");
//            $this->terrain->addTerrain(2502, 2, "forest");
//            $this->terrain->addTerrain(2503, 1, "forest");
//            $this->terrain->addTerrain(1803, 1, "forest");
//            $this->terrain->addTerrain(1803, 1, "road");
//            $this->terrain->addReinforceZone(1803, 'B');
//            $this->terrain->addTerrain(1803, 2, "forest");
//            $this->terrain->addTerrain(1803, 2, "road");
//            $this->terrain->addTerrain(505, 4, "forest");
//            $this->terrain->addTerrain(505, 4, "road");
//            $this->terrain->addTerrain(1118, 4, "forest");
//            $this->terrain->addTerrain(1118, 4, "road");
//            $this->terrain->addTerrain(1301, 3, "blocked");
//            $this->terrain->addTerrain(1301, 2, "blocked");
//            $this->terrain->addTerrain(1401, 3, "blocked");
//            $this->terrain->addTerrain(1401, 2, "blocked");
//            $this->terrain->addTerrain(1502, 3, "blocked");
//            $this->terrain->addTerrain(1502, 2, "blocked");
//            $this->terrain->addTerrain(1602, 3, "blocked");
//            $this->terrain->addTerrain(1602, 2, "blocked");
//            $this->terrain->addTerrain(1703, 3, "blocked");
//            $this->terrain->addTerrain(1704, 4, "blocked");
//            $this->terrain->addTerrain(1704, 3, "blocked");
//            $this->terrain->addTerrain(1704, 2, "blocked");
//            $this->terrain->addTerrain(1804, 3, "blocked");
//            $this->terrain->addTerrain(1805, 4, "blocked");
//            $this->terrain->addTerrain(1805, 3, "blocked");
//            $this->terrain->addTerrain(1805, 2, "blocked");
//            $this->terrain->addTerrain(1906, 4, "blocked");
//            $this->terrain->addTerrain(1905, 2, "blocked");
//            $this->terrain->addTerrain(2005, 4, "blocked");
//            $this->terrain->addTerrain(2004, 2, "blocked");
//            $this->terrain->addTerrain(2105, 3, "blocked");
//            $this->terrain->addTerrain(2205, 2, "blocked");
//            $this->terrain->addTerrain(2306, 3, "blocked");
//            $this->terrain->addTerrain(2306, 2, "blocked");
//            $this->terrain->addTerrain(2406, 3, "blocked");
//            $this->terrain->addTerrain(2406, 2, "blocked");
//            $this->terrain->addTerrain(2507, 3, "blocked");
//            $this->terrain->addTerrain(2507, 2, "blocked");
//            $this->terrain->addTerrain(2105, 2, "blocksnonroad");
//            $this->terrain->addTerrain(2105, 2, "road");
//            $this->terrain->addTerrain(2205, 3, "blocksnonroad");
//            $this->terrain->addTerrain(2205, 3, "road");
//            $this->terrain->addTerrain(2207, 3, "river");
//            $this->terrain->addTerrain(2208, 4, "river");
//            $this->terrain->addTerrain(2208, 3, "river");
//            $this->terrain->addTerrain(2209, 4, "river");
//            $this->terrain->addTerrain(2109, 2, "river");
//            $this->terrain->addTerrain(2110, 4, "river");
//            $this->terrain->addTerrain(2110, 3, "river");
//            $this->terrain->addTerrain(2111, 4, "river");
//            $this->terrain->addTerrain(2010, 2, "river");
//            $this->terrain->addTerrain(2011, 4, "river");
//            $this->terrain->addTerrain(2011, 3, "river");
//            $this->terrain->addTerrain(2012, 4, "river");
//            $this->terrain->addTerrain(2012, 3, "river");
//            $this->terrain->addTerrain(2013, 4, "river");
//            $this->terrain->addTerrain(1913, 2, "river");
//            $this->terrain->addTerrain(1914, 4, "river");
//            $this->terrain->addTerrain(1914, 3, "river");
//            $this->terrain->addTerrain(1915, 4, "river");
//            $this->terrain->addTerrain(1814, 2, "river");
//            $this->terrain->addTerrain(1815, 4, "river");
//            $this->terrain->addTerrain(1715, 2, "river");
//            $this->terrain->addTerrain(1716, 4, "river");
//            $this->terrain->addTerrain(1716, 3, "river");
//            $this->terrain->addTerrain(1717, 4, "river");
//            $this->terrain->addTerrain(1616, 2, "river");
//            $this->terrain->addTerrain(1617, 4, "river");
//            $this->terrain->addTerrain(1517, 2, "river");
//            $this->terrain->addTerrain(1518, 4, "river");
//            $this->terrain->addTerrain(1518, 3, "river");
//            $this->terrain->addTerrain(2111, 3, "river");
//            $this->terrain->addTerrain(2111, 2, "river");
//            $this->terrain->addTerrain(2211, 2, "river");
//            $this->terrain->addTerrain(2211, 2, "road");
//            $this->terrain->addTerrain(2312, 4, "river");
//            $this->terrain->addTerrain(2311, 2, "river");
//            $this->terrain->addTerrain(2411, 4, "river");
//            $this->terrain->addTerrain(2411, 3, "river");
//            $this->terrain->addTerrain(1702, 4, "road");
//            $this->terrain->addTerrain(1702, 1, "road");
//            $this->terrain->addReinforceZone(1702, 'B');
//            $this->terrain->addTerrain(1802, 4, "road");
//            $this->terrain->addTerrain(1802, 1, "road");
//            $this->terrain->addReinforceZone(1802, 'B');
//            $this->terrain->addTerrain(1802, 2, "road");
//            $this->terrain->addTerrain(1904, 3, "road");
//            $this->terrain->addTerrain(1904, 1, "road");
//            $this->terrain->addReinforceZone(1904, 'B');
//            $this->terrain->addTerrain(2004, 4, "road");
//            $this->terrain->addTerrain(2004, 1, "road");
//            $this->terrain->addReinforceZone(2004, 'B');
//            $this->terrain->addTerrain(2105, 4, "road");
//            $this->terrain->addTerrain(2105, 1, "road");
//            $this->terrain->addReinforceZone(2105, 'B');
//            $this->terrain->addTerrain(2104, 2, "road");
//            $this->terrain->addTerrain(2104, 1, "road");
//            $this->terrain->addReinforceZone(2104, 'B');
//            $this->terrain->addTerrain(2103, 2, "road");
//            $this->terrain->addTerrain(2103, 1, "road");
//            $this->terrain->addReinforceZone(2103, 'B');
//            $this->terrain->addTerrain(2102, 2, "road");
//            $this->terrain->addTerrain(2102, 1, "road");
//            $this->terrain->addReinforceZone(2102, 'B');
//            $this->terrain->addTerrain(2201, 3, "road");
//            $this->terrain->addTerrain(2205, 1, "road");
//            $this->terrain->addTerrain(2305, 3, "road");
//            $this->terrain->addTerrain(2305, 1, "road");
//            $this->terrain->addTerrain(2405, 4, "road");
//            $this->terrain->addTerrain(2405, 1, "road");
//            $this->terrain->addTerrain(2506, 4, "road");
//            $this->terrain->addTerrain(2506, 1, "road");
//            $this->terrain->addTerrain(2106, 3, "road");
//            $this->terrain->addTerrain(2006, 1, "road");
//            $this->terrain->addReinforceZone(2006, 'B');
//            $this->terrain->addTerrain(2006, 4, "road");
//            $this->terrain->addTerrain(1906, 1, "road");
//            $this->terrain->addReinforceZone(1906, 'B');
//            $this->terrain->addTerrain(1906, 3, "road");
//            $this->terrain->addTerrain(1806, 1, "road");
//            $this->terrain->addReinforceZone(1806, 'B');
//            $this->terrain->addTerrain(1806, 4, "road");
//            $this->terrain->addTerrain(1706, 1, "road");
//            $this->terrain->addReinforceZone(1706, 'B');
//            $this->terrain->addTerrain(1706, 4, "road");
//            $this->terrain->addTerrain(1605, 1, "road");
//            $this->terrain->addReinforceZone(1605, 'B');
//            $this->terrain->addTerrain(1605, 4, "road");
//            $this->terrain->addTerrain(1505, 1, "road");
//            $this->terrain->addReinforceZone(1505, 'B');
//            $this->terrain->addTerrain(1505, 4, "road");
//            $this->terrain->addTerrain(1404, 1, "road");
//            $this->terrain->addTerrain(1404, 4, "road");
//            $this->terrain->addTerrain(1304, 1, "road");
//            $this->terrain->addTerrain(1304, 4, "road");
//            $this->terrain->addTerrain(1203, 1, "road");
//            $this->terrain->addTerrain(1203, 4, "road");
//            $this->terrain->addTerrain(1103, 4, "road");
//            $this->terrain->addTerrain(1002, 1, "road");
//            $this->terrain->addTerrain(1002, 4, "road");
//            $this->terrain->addTerrain(1404, 3, "road");
//            $this->terrain->addTerrain(1305, 1, "road");
//            $this->terrain->addTerrain(1305, 3, "road");
//            $this->terrain->addTerrain(1205, 1, "road");
//            $this->terrain->addTerrain(1205, 4, "road");
//            $this->terrain->addTerrain(1105, 1, "road");
//            $this->terrain->addTerrain(1105, 3, "road");
//            $this->terrain->addTerrain(1005, 1, "road");
//            $this->terrain->addTerrain(1005, 3, "road");
//            $this->terrain->addTerrain(906, 1, "road");
//            $this->terrain->addTerrain(906, 3, "road");
//            $this->terrain->addTerrain(806, 4, "road");
//            $this->terrain->addTerrain(706, 1, "road");
//            $this->terrain->addTerrain(706, 4, "road");
//            $this->terrain->addTerrain(605, 1, "road");
//            $this->terrain->addTerrain(605, 4, "road");
//            $this->terrain->addTerrain(604, 3, "road");
//            $this->terrain->addTerrain(604, 1, "road");
//            $this->terrain->addTerrain(704, 3, "road");
//            $this->terrain->addTerrain(704, 1, "road");
//            $this->terrain->addTerrain(803, 3, "road");
//            $this->terrain->addTerrain(803, 1, "road");
//            $this->terrain->addReinforceZone(803, 'A');
//            $this->terrain->addTerrain(904, 4, "road");
//            $this->terrain->addTerrain(904, 1, "road");
//            $this->terrain->addTerrain(1003, 3, "road");
//            $this->terrain->addTerrain(1003, 1, "road");
//            $this->terrain->addTerrain(1103, 3, "road");
//            $this->terrain->addTerrain(806, 2, "road");
//            $this->terrain->addTerrain(807, 1, "road");
//            $this->terrain->addReinforceZone(807, 'A');
//            $this->terrain->addTerrain(807, 3, "road");
//            $this->terrain->addTerrain(708, 1, "road");
//            $this->terrain->addTerrain(708, 2, "road");
//            $this->terrain->addTerrain(709, 1, "road");
//            $this->terrain->addTerrain(709, 3, "road");
//            $this->terrain->addTerrain(609, 1, "road");
//            $this->terrain->addTerrain(609, 2, "road");
//            $this->terrain->addTerrain(610, 1, "road");
//            $this->terrain->addTerrain(610, 3, "road");
//            $this->terrain->addTerrain(511, 1, "road");
//            $this->terrain->addTerrain(511, 3, "road");
//            $this->terrain->addTerrain(411, 4, "road");
//            $this->terrain->addTerrain(311, 1, "road");
//            $this->terrain->addTerrain(311, 4, "road");
//            $this->terrain->addTerrain(411, 2, "road");
//            $this->terrain->addTerrain(412, 3, "road");
//            $this->terrain->addTerrain(313, 1, "road");
//            $this->terrain->addTerrain(313, 2, "road");
//            $this->terrain->addTerrain(314, 1, "road");
//            $this->terrain->addTerrain(314, 3, "road");
//            $this->terrain->addTerrain(214, 3, "road");
//            $this->terrain->addTerrain(115, 1, "road");
//            $this->terrain->addTerrain(513, 4, "road");
//            $this->terrain->addTerrain(513, 1, "road");
//            $this->terrain->addTerrain(513, 2, "road");
//            $this->terrain->addTerrain(514, 1, "road");
//            $this->terrain->addTerrain(514, 2, "road");
//            $this->terrain->addTerrain(717, 3, "road");
//            $this->terrain->addTerrain(617, 1, "road");
//            $this->terrain->addTerrain(617, 3, "road");
//            $this->terrain->addTerrain(518, 1, "road");
//            $this->terrain->addTerrain(518, 3, "road");
//            $this->terrain->addTerrain(418, 1, "road");
//            $this->terrain->addTerrain(817, 4, "road");
//            $this->terrain->addTerrain(817, 1, "road");
//            $this->terrain->addReinforceZone(817, 'A');
//            $this->terrain->addTerrain(918, 4, "road");
//            $this->terrain->addTerrain(1117, 2, "road");
//            $this->terrain->addTerrain(1117, 1, "road");
//            $this->terrain->addReinforceZone(1117, 'A');
//            $this->terrain->addTerrain(1216, 3, "road");
//            $this->terrain->addTerrain(1216, 1, "road");
//            $this->terrain->addReinforceZone(1216, 'A');
//            $this->terrain->addTerrain(1215, 2, "road");
//            $this->terrain->addTerrain(1315, 3, "road");
//            $this->terrain->addTerrain(1315, 1, "road");
//            $this->terrain->addReinforceZone(1315, 'A');
//            $this->terrain->addTerrain(1414, 3, "road");
//            $this->terrain->addTerrain(1414, 1, "road");
//            $this->terrain->addTerrain(1514, 3, "road");
//            $this->terrain->addTerrain(1514, 1, "road");
//            $this->terrain->addTerrain(1613, 3, "road");
//            $this->terrain->addTerrain(1613, 1, "road");
//            $this->terrain->addReinforceZone(1613, 'B');
//            $this->terrain->addTerrain(1612, 2, "road");
//            $this->terrain->addTerrain(1612, 1, "road");
//            $this->terrain->addReinforceZone(1612, 'B');
//            $this->terrain->addTerrain(1712, 3, "road");
//            $this->terrain->addTerrain(1712, 1, "road");
//            $this->terrain->addReinforceZone(1712, 'B');
//            $this->terrain->addTerrain(1811, 3, "road");
//            $this->terrain->addTerrain(1810, 2, "road");
//            $this->terrain->addTerrain(1810, 1, "road");
//            $this->terrain->addReinforceZone(1810, 'B');
//            $this->terrain->addTerrain(1809, 2, "road");
//            $this->terrain->addTerrain(1809, 1, "road");
//            $this->terrain->addReinforceZone(1809, 'B');
//            $this->terrain->addTerrain(1909, 3, "road");
//            $this->terrain->addTerrain(1909, 1, "road");
//            $this->terrain->addReinforceZone(1909, 'B');
//            $this->terrain->addTerrain(1908, 2, "road");
//            $this->terrain->addTerrain(1908, 1, "road");
//            $this->terrain->addReinforceZone(1908, 'B');
//            $this->terrain->addTerrain(2007, 3, "road");
//            $this->terrain->addTerrain(2007, 1, "road");
//            $this->terrain->addReinforceZone(2007, 'B');
//            $this->terrain->addTerrain(2107, 3, "road");
//            $this->terrain->addTerrain(1908, 4, "road");
//            $this->terrain->addTerrain(1807, 1, "road");
//            $this->terrain->addReinforceZone(1807, 'B');
//            $this->terrain->addTerrain(1807, 3, "road");
//            $this->terrain->addTerrain(1708, 4, "road");
//            $this->terrain->addTerrain(1607, 1, "road");
//            $this->terrain->addReinforceZone(1607, 'B');
//            $this->terrain->addTerrain(1607, 4, "road");
//            $this->terrain->addTerrain(1507, 1, "road");
//            $this->terrain->addReinforceZone(1507, 'B');
//            $this->terrain->addTerrain(1507, 3, "road");
//            $this->terrain->addTerrain(1407, 4, "road");
//            $this->terrain->addTerrain(1307, 1, "road");
//            $this->terrain->addTerrain(1307, 3, "road");
//            $this->terrain->addTerrain(1207, 1, "road");
//            $this->terrain->addTerrain(1207, 3, "road");
//            $this->terrain->addTerrain(1108, 1, "road");
//            $this->terrain->addTerrain(1108, 3, "road");
//            $this->terrain->addTerrain(909, 3, "road");
//            $this->terrain->addTerrain(809, 1, "road");
//            $this->terrain->addReinforceZone(809, 'A');
//            $this->terrain->addTerrain(809, 4, "road");
//            $this->terrain->addTerrain(2307, 4, "road");
//            $this->terrain->addTerrain(2307, 1, "road");
//            $this->terrain->addTerrain(2407, 4, "road");
//            $this->terrain->addTerrain(2407, 1, "road");
//            $this->terrain->addTerrain(2508, 4, "road");
//            $this->terrain->addTerrain(2307, 2, "road");
//            $this->terrain->addTerrain(2308, 1, "road");
//            $this->terrain->addTerrain(2308, 2, "road");
//            $this->terrain->addTerrain(2309, 1, "road");
//            $this->terrain->addTerrain(2309, 2, "road");
//            $this->terrain->addTerrain(2310, 1, "road");
//            $this->terrain->addTerrain(2310, 2, "road");
//            $this->terrain->addTerrain(2311, 1, "road");
//            $this->terrain->addTerrain(2311, 3, "road");
//            $this->terrain->addTerrain(2212, 1, "road");
//            $this->terrain->addTerrain(2212, 2, "road");
//            $this->terrain->addTerrain(2213, 1, "road");
//            $this->terrain->addTerrain(2213, 2, "road");
//            $this->terrain->addTerrain(2214, 1, "road");
//            $this->terrain->addTerrain(2214, 2, "road");
//            $this->terrain->addTerrain(2215, 1, "road");
//            $this->terrain->addTerrain(2215, 2, "road");
//            $this->terrain->addTerrain(2216, 1, "road");
//            $this->terrain->addTerrain(2216, 2, "road");
//            $this->terrain->addTerrain(2217, 1, "road");
//            $this->terrain->addTerrain(2217, 2, "road");
//            $this->terrain->addTerrain(2218, 1, "road");
//            $this->terrain->addTerrain(2108, 1, "swamp");
//            $this->terrain->addReinforceZone(2108, 'B');
//            $this->terrain->addTerrain(2008, 1, "swamp");
//            $this->terrain->addReinforceZone(2008, 'B');
//            $this->terrain->addTerrain(2009, 1, "swamp");
//            $this->terrain->addReinforceZone(2009, 'B');
//            $this->terrain->addTerrain(2010, 1, "swamp");
//            $this->terrain->addReinforceZone(2010, 'B');
//            $this->terrain->addTerrain(2011, 1, "swamp");
//            $this->terrain->addReinforceZone(2011, 'B');
//            $this->terrain->addTerrain(2012, 1, "swamp");
//            $this->terrain->addReinforceZone(2012, 'B');
//            $this->terrain->addTerrain(2111, 1, "swamp");
//            $this->terrain->addReinforceZone(2111, 'B');
//            $this->terrain->addTerrain(2110, 1, "swamp");
//            $this->terrain->addReinforceZone(2110, 'B');
//            $this->terrain->addTerrain(2109, 1, "swamp");
//            $this->terrain->addReinforceZone(2109, 'B');
//            $this->terrain->addTerrain(1912, 1, "swamp");
//            $this->terrain->addReinforceZone(1912, 'B');
//            $this->terrain->addTerrain(1913, 1, "swamp");
//            $this->terrain->addReinforceZone(1913, 'B');
//            $this->terrain->addTerrain(1914, 1, "swamp");
//            $this->terrain->addReinforceZone(1914, 'B');
//            $this->terrain->addTerrain(1813, 1, "swamp");
//            $this->terrain->addReinforceZone(1813, 'B');
//            $this->terrain->addTerrain(1814, 1, "swamp");
//            $this->terrain->addReinforceZone(1814, 'B');
//            $this->terrain->addTerrain(1815, 1, "swamp");
//            $this->terrain->addReinforceZone(1815, 'B');
//            $this->terrain->addTerrain(1816, 1, "swamp");
//            $this->terrain->addTerrain(1817, 1, "swamp");
//            $this->terrain->addTerrain(1818, 1, "swamp");
//            $this->terrain->addTerrain(1718, 1, "swamp");
//            $this->terrain->addTerrain(1717, 1, "swamp");
//            $this->terrain->addTerrain(1716, 1, "swamp");
//            $this->terrain->addTerrain(1715, 1, "swamp");
//            $this->terrain->addReinforceZone(1715, 'B');
//            $this->terrain->addTerrain(1616, 1, "swamp");
//            $this->terrain->addTerrain(1617, 1, "swamp");
//            $this->terrain->addTerrain(1618, 1, "swamp");
//            $this->terrain->addTerrain(1518, 1, "swamp");
//            $this->terrain->addTerrain(1418, 1, "swamp");
//            $this->terrain->addReinforceZone(801, 'A');
//            $this->terrain->addReinforceZone(802, 'A');
//            $this->terrain->addReinforceZone(804, 'A');
//            $this->terrain->addReinforceZone(805, 'A');
//            $this->terrain->addReinforceZone(810, 'A');
//            $this->terrain->addReinforceZone(811, 'A');
//            $this->terrain->addReinforceZone(912, 'A');
//            $this->terrain->addReinforceZone(1012, 'A');
//            $this->terrain->addReinforceZone(1113, 'A');
//            $this->terrain->addReinforceZone(1114, 'A');
//            $this->terrain->addReinforceZone(1214, 'A');
//            $this->terrain->addReinforceZone(1316, 'A');
//            $this->terrain->addReinforceZone(1317, 'A');
//            $this->terrain->addReinforceZone(1217, 'A');
//            $this->terrain->addReinforceZone(1218, 'A');
//            $this->terrain->addReinforceZone(1116, 'A');
//            $this->terrain->addReinforceZone(1115, 'A');
//            $this->terrain->addReinforceZone(1013, 'A');
//            $this->terrain->addReinforceZone(1014, 'A');
//            $this->terrain->addReinforceZone(1015, 'A');
//            $this->terrain->addReinforceZone(1018, 'A');
//            $this->terrain->addReinforceZone(917, 'A');
//            $this->terrain->addReinforceZone(916, 'A');
//            $this->terrain->addReinforceZone(915, 'A');
//            $this->terrain->addReinforceZone(914, 'A');
//            $this->terrain->addReinforceZone(913, 'A');
//            $this->terrain->addReinforceZone(812, 'A');
//            $this->terrain->addReinforceZone(813, 'A');
//            $this->terrain->addReinforceZone(815, 'A');
//            $this->terrain->addReinforceZone(816, 'A');
//            $this->terrain->addReinforceZone(818, 'A');
//            $this->terrain->addReinforceZone(1501, 'B');
//            $this->terrain->addReinforceZone(1502, 'B');
//            $this->terrain->addReinforceZone(1503, 'B');
//            $this->terrain->addReinforceZone(1504, 'B');
//            $this->terrain->addReinforceZone(1506, 'B');
//            $this->terrain->addReinforceZone(1509, 'B');
//            $this->terrain->addReinforceZone(1510, 'B');
//            $this->terrain->addReinforceZone(1511, 'B');
//            $this->terrain->addReinforceZone(1611, 'B');
//            $this->terrain->addReinforceZone(2016, 'B');
//            $this->terrain->addReinforceZone(2017, 'B');
//            $this->terrain->addReinforceZone(2118, 'B');
//            $this->terrain->addReinforceZone(1602, 'B');
//            $this->terrain->addReinforceZone(1603, 'B');
//            $this->terrain->addReinforceZone(1604, 'B');
//            $this->terrain->addReinforceZone(1606, 'B');
//            $this->terrain->addReinforceZone(1609, 'B');
//            $this->terrain->addReinforceZone(1610, 'B');
//            $this->terrain->addReinforceZone(1701, 'B');
//            $this->terrain->addReinforceZone(1703, 'B');
//            $this->terrain->addReinforceZone(1704, 'B');
//            $this->terrain->addReinforceZone(1705, 'B');
//            $this->terrain->addReinforceZone(1707, 'B');
//            $this->terrain->addReinforceZone(1709, 'B');
//            $this->terrain->addReinforceZone(1710, 'B');
//            $this->terrain->addReinforceZone(1711, 'B');
//            $this->terrain->addReinforceZone(1801, 'B');
//            $this->terrain->addReinforceZone(1805, 'B');
//            $this->terrain->addReinforceZone(1808, 'B');
//            $this->terrain->addReinforceZone(1901, 'B');
//            $this->terrain->addReinforceZone(1902, 'B');
//            $this->terrain->addReinforceZone(1903, 'B');
//            $this->terrain->addReinforceZone(1905, 'B');
//            $this->terrain->addReinforceZone(1907, 'B');
//            $this->terrain->addReinforceZone(2001, 'B');
//            $this->terrain->addReinforceZone(2002, 'B');
//            $this->terrain->addReinforceZone(2003, 'B');
//            $this->terrain->addReinforceZone(2005, 'B');
//            $this->terrain->addReinforceZone(2013, 'B');
//            $this->terrain->addReinforceZone(2015, 'B');
//            $this->terrain->addReinforceZone(2117, 'B');
//            $this->terrain->addReinforceZone(2116, 'B');
//            $this->terrain->addReinforceZone(2115, 'B');
//            $this->terrain->addReinforceZone(2101, 'B');


//            for ($col = 2200; $col <= 2500; $col += 100) {
//                for ($row = 1; $row <= 18; $row++) {
//                    $this->terrain->addReinforceZone($col + $row, 'B');
//                }
//            }
//
//            for ($col = 100; $col <= 700; $col += 100) {
//                for ($row = 1; $row <= 18; $row++) {
//                    $this->terrain->addReinforceZone($col + $row, 'A');
//                }
//            }


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