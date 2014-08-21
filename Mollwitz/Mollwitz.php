<?php
define("PRUSSIAN_FORCE", 1);
define("AUSTRIAN_FORCE", 2);

global $force_name;
$force_name = array();
$force_name[1] = "Prussian";
$force_name[2] = "Austrian";

require_once "JagCore.php";

class Mollwitz extends JagCore
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


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "mollwitzHeader.php";
    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "Prussian";
        $deployOne = $playerTwo = "Austrian";
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

    public function init(){

        $artRange = 3;
        $artStr = 2;
        if($this->scenario->artStr){
            $artStr = 3;
        }

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');

        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');

        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        if($this->scenario->extraArt){
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
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
            $this->victory = new Victory("Mollwitz", $data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
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
            $this->victory = new Victory("Mollwitz");

//            $this->mapData->setData(19, 14, "js/Mollwitz2.jpg");

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
            $this->moveRules->stickyZOC = false;
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
//                $this->mapViewer[$player]->setData(53.17499999999998, 87.30000000000001, // originX, originY
//                    29.1, 29.1, // top hexagon height, bottom hexagon height
//                    16.7, 33.65// hexagon edge width, hexagon center width
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
//            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
//            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
//            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
//            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
//            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
//            $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
//            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
//
//
//            for ($col = 100; $col <= 1900; $col += 100) {
//                for ($row = 1; $row <= 14; $row++) {
//                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");
//
//                }
//            }
//
//            $this->terrain->addTerrain(502, 1, "town");
//            $this->terrain->addTerrain(602, 1, "town");
//            $this->terrain->addTerrain(702, 1, "town");
//            $this->terrain->addTerrain(802, 1, "town");
//            $this->terrain->addTerrain(1502, 1, "town");
//            $this->terrain->addTerrain(1807, 1, "town");
//            $this->terrain->addTerrain(1908, 1, "town");
//            $this->terrain->addTerrain(911, 1, "town");
//            $this->terrain->addTerrain(612, 1, "town");
//            $this->terrain->addTerrain(513, 1, "town");
//            $this->terrain->addTerrain(701, 1, "road");
//            $this->terrain->addTerrain(701, 3, "road");
//            $this->terrain->addTerrain(601, 1, "road");
//            $this->terrain->addTerrain(603, 1, "road");
//            $this->terrain->addReinforceZone(603, 'A');
//            $this->terrain->addTerrain(603, 3, "road");
//            $this->terrain->addTerrain(504, 1, "road");
//            $this->terrain->addReinforceZone(504, 'A');
//            $this->terrain->addTerrain(504, 2, "road");
//            $this->terrain->addTerrain(505, 1, "road");
//            $this->terrain->addReinforceZone(505, 'A');
//            $this->terrain->addTerrain(505, 2, "road");
//            $this->terrain->addTerrain(506, 1, "road");
//            $this->terrain->addTerrain(606, 4, "road");
//            $this->terrain->addTerrain(506, 3, "road");
//            $this->terrain->addTerrain(506, 3, "river");
//            $this->terrain->addTerrain(406, 1, "road");
//            $this->terrain->addTerrain(406, 1, "forest");
//            $this->terrain->addTerrain(406, 2, "road");
//            $this->terrain->addTerrain(407, 1, "road");
//            $this->terrain->addTerrain(407, 2, "road");
//            $this->terrain->addTerrain(408, 1, "road");
//            $this->terrain->addTerrain(408, 2, "road");
//            $this->terrain->addTerrain(408, 4, "road");
//            $this->terrain->addTerrain(308, 1, "road");
//            $this->terrain->addTerrain(307, 2, "road");
//            $this->terrain->addTerrain(307, 1, "road");
//            $this->terrain->addTerrain(307, 4, "road");
//            $this->terrain->addTerrain(206, 1, "road");
//            $this->terrain->addTerrain(206, 4, "road");
//            $this->terrain->addTerrain(106, 1, "road");
//            $this->terrain->addTerrain(409, 1, "road");
//            $this->terrain->addTerrain(510, 4, "road");
//            $this->terrain->addTerrain(510, 1, "road");
//            $this->terrain->addTerrain(510, 2, "road");
//            $this->terrain->addTerrain(511, 1, "road");
//            $this->terrain->addTerrain(511, 2, "road");
//            $this->terrain->addTerrain(512, 1, "road");
//            $this->terrain->addTerrain(514, 1, "road");
//            $this->terrain->addTerrain(1014, 1, "road");
//            $this->terrain->addReinforceZone(1014, 'B');
//            $this->terrain->addTerrain(1013, 2, "road");
//            $this->terrain->addTerrain(1013, 1, "road");
//            $this->terrain->addTerrain(1013, 1, "forest");
//            $this->terrain->addReinforceZone(1013, 'B');
//            $this->terrain->addTerrain(1012, 2, "road");
//            $this->terrain->addTerrain(1012, 1, "road");
//            $this->terrain->addReinforceZone(1012, 'B');
//            $this->terrain->addTerrain(1011, 2, "road");
//            $this->terrain->addTerrain(1011, 1, "road");
//            $this->terrain->addReinforceZone(1011, 'B');
//            $this->terrain->addTerrain(1010, 2, "road");
//            $this->terrain->addTerrain(1010, 1, "road");
//            $this->terrain->addTerrain(1010, 4, "road");
//            $this->terrain->addTerrain(910, 1, "road");
//            $this->terrain->addTerrain(909, 2, "road");
//            $this->terrain->addTerrain(909, 1, "road");
//            $this->terrain->addTerrain(909, 4, "road");
//            $this->terrain->addTerrain(808, 1, "road");
//            $this->terrain->addTerrain(807, 2, "road");
//            $this->terrain->addTerrain(807, 1, "road");
//            $this->terrain->addTerrain(807, 4, "road");
//            $this->terrain->addTerrain(707, 1, "road");
//            $this->terrain->addTerrain(707, 4, "road");
//            $this->terrain->addTerrain(606, 1, "road");
//            $this->terrain->addTerrain(903, 1, "road");
//            $this->terrain->addReinforceZone(903, 'A');
//            $this->terrain->addTerrain(1003, 4, "road");
//            $this->terrain->addTerrain(1003, 1, "road");
//            $this->terrain->addReinforceZone(1003, 'A');
//            $this->terrain->addTerrain(1104, 4, "road");
//            $this->terrain->addTerrain(1104, 1, "road");
//            $this->terrain->addReinforceZone(1104, 'A');
//            $this->terrain->addTerrain(1204, 4, "road");
//            $this->terrain->addTerrain(1204, 1, "road");
//            $this->terrain->addReinforceZone(1204, 'A');
//            $this->terrain->addTerrain(1305, 4, "road");
//            $this->terrain->addTerrain(1305, 1, "road");
//            $this->terrain->addTerrain(1405, 4, "road");
//            $this->terrain->addTerrain(1405, 1, "road");
//            $this->terrain->addTerrain(1506, 4, "road");
//            $this->terrain->addTerrain(1506, 1, "road");
//            $this->terrain->addTerrain(1606, 4, "road");
//            $this->terrain->addTerrain(1606, 1, "road");
//            $this->terrain->addTerrain(1707, 4, "road");
//            $this->terrain->addTerrain(1707, 1, "road");
//            $this->terrain->addTerrain(1605, 2, "road");
//            $this->terrain->addTerrain(1605, 1, "road");
//            $this->terrain->addTerrain(1604, 2, "road");
//            $this->terrain->addTerrain(1604, 1, "road");
//            $this->terrain->addTerrain(1604, 4, "road");
//            $this->terrain->addTerrain(1504, 1, "road");
//            $this->terrain->addTerrain(1503, 2, "road");
//            $this->terrain->addTerrain(1503, 1, "road");
//            $this->terrain->addTerrain(1602, 1, "road");
//            $this->terrain->addTerrain(1703, 4, "road");
//            $this->terrain->addTerrain(1703, 1, "road");
//            $this->terrain->addTerrain(1803, 4, "road");
//            $this->terrain->addTerrain(1803, 1, "road");
//            $this->terrain->addTerrain(1904, 4, "road");
//            $this->terrain->addTerrain(1904, 1, "road");
//            $this->terrain->addTerrain(1401, 1, "road");
//            $this->terrain->addReinforceZone(1401, 'A');
//            $this->terrain->addTerrain(1401, 3, "road");
//            $this->terrain->addTerrain(1302, 1, "road");
//            $this->terrain->addReinforceZone(1302, 'A');
//            $this->terrain->addTerrain(1302, 3, "road");
//            $this->terrain->addTerrain(1202, 1, "road");
//            $this->terrain->addReinforceZone(1202, 'A');
//            $this->terrain->addTerrain(1202, 4, "road");
//            $this->terrain->addTerrain(1102, 1, "road");
//            $this->terrain->addReinforceZone(1102, 'A');
//            $this->terrain->addTerrain(1102, 3, "road");
//            $this->terrain->addTerrain(1002, 1, "road");
//            $this->terrain->addReinforceZone(1002, 'A');
//            $this->terrain->addTerrain(205, 1, "forest");
//            $this->terrain->addTerrain(306, 1, "forest");
//            $this->terrain->addTerrain(507, 1, "forest");
//            $this->terrain->addTerrain(607, 1, "forest");
//            $this->terrain->addTerrain(709, 1, "forest");
//            $this->terrain->addTerrain(208, 1, "forest");
//            $this->terrain->addTerrain(209, 1, "forest");
//            $this->terrain->addTerrain(310, 1, "forest");
//            $this->terrain->addTerrain(311, 1, "forest");
//            $this->terrain->addTerrain(812, 1, "forest");
//            $this->terrain->addTerrain(912, 1, "forest");
//            $this->terrain->addReinforceZone(912, 'B');
//            $this->terrain->addTerrain(813, 1, "forest");
//            $this->terrain->addTerrain(914, 1, "forest");
//            $this->terrain->addReinforceZone(914, 'B');
//            $this->terrain->addTerrain(1113, 1, "forest");
//            $this->terrain->addReinforceZone(1113, 'B');
//            $this->terrain->addTerrain(104, 2, "river");
//            $this->terrain->addTerrain(204, 3, "river");
//            $this->terrain->addTerrain(204, 2, "river");
//            $this->terrain->addTerrain(305, 3, "river");
//            $this->terrain->addTerrain(305, 2, "river");
//            $this->terrain->addTerrain(405, 3, "river");
//            $this->terrain->addTerrain(405, 2, "river");
//            $this->terrain->addTerrain(506, 2, "river");
//            $this->terrain->addTerrain(606, 3, "river");
//            $this->terrain->addTerrain(606, 2, "river");
//            $this->terrain->addTerrain(707, 3, "river");
//            $this->terrain->addTerrain(708, 4, "river");
//            $this->terrain->addTerrain(708, 3, "river");
//            $this->terrain->addTerrain(709, 4, "river");
//            $this->terrain->addTerrain(709, 3, "river");
//            $this->terrain->addTerrain(709, 2, "river");
//            $this->terrain->addTerrain(809, 3, "river");
//            $this->terrain->addTerrain(810, 4, "river");
//            $this->terrain->addTerrain(810, 3, "river");
//            $this->terrain->addTerrain(810, 3, "trail");
//            $this->terrain->addTerrain(810, 2, "river");
//            $this->terrain->addTerrain(911, 3, "river");
//            $this->terrain->addTerrain(810, 1, "trail");
//            $this->terrain->addTerrain(711, 1, "trail");
//            $this->terrain->addTerrain(912, 4, "river");
//            $this->terrain->addTerrain(912, 3, "river");
//            $this->terrain->addTerrain(912, 3, "forest");
//            $this->terrain->addTerrain(913, 4, "river");
//            $this->terrain->addTerrain(913, 3, "river");
//            $this->terrain->addTerrain(914, 4, "river");
//            $this->terrain->addTerrain(914, 3, "river");
//            $this->terrain->addTerrain(212, 4, "river");
//            $this->terrain->addTerrain(211, 2, "river");
//            $this->terrain->addTerrain(312, 4, "river");
//            $this->terrain->addTerrain(311, 3, "river");
//            $this->terrain->addTerrain(311, 4, "river");
//            $this->terrain->addTerrain(310, 3, "river");
//            $this->terrain->addTerrain(310, 4, "river");
//            $this->terrain->addTerrain(310, 4, "forest");
//            $this->terrain->addTerrain(309, 3, "river");
//            $this->terrain->addTerrain(208, 2, "river");
//            $this->terrain->addTerrain(208, 2, "forest");
//            $this->terrain->addTerrain(208, 3, "river");
//            $this->terrain->addTerrain(208, 4, "river");
//            $this->terrain->addTerrain(207, 3, "river");
//            $this->terrain->addTerrain(107, 2, "river");
//            $this->terrain->addTerrain(306, 4, "forest");
//            $this->terrain->addTerrain(406, 4, "forest");
//            $this->terrain->addTerrain(507, 4, "forest");
//            $this->terrain->addTerrain(607, 4, "forest");
//            $this->terrain->addTerrain(310, 2, "forest");
//            $this->terrain->addTerrain(812, 2, "forest");
//            $this->terrain->addTerrain(1013, 3, "forest");
//            $this->terrain->addTerrain(1113, 3, "forest");
//            $this->terrain->addReinforceZone(913, 'B');
//            $this->terrain->addReinforceZone(1111, 'B');
//            $this->terrain->addReinforceZone(1210, 'B');
//            $this->terrain->addReinforceZone(1310, 'B');
//            $this->terrain->addReinforceZone(1409, 'B');
//            $this->terrain->addReinforceZone(1509, 'B');
//            $this->terrain->addReinforceZone(1608, 'B');
//            $this->terrain->addReinforceZone(1709, 'B');
//            $this->terrain->addReinforceZone(1809, 'B');
//            $this->terrain->addReinforceZone(1910, 'B');
//            $this->terrain->addReinforceZone(1814, 'B');
//            $this->terrain->addReinforceZone(104, 'A');
//            $this->terrain->addReinforceZone(204, 'A');
//            $this->terrain->addReinforceZone(305, 'A');
//            $this->terrain->addReinforceZone(405, 'A');
//            $this->terrain->addReinforceZone(604, 'A');
//            $this->terrain->addReinforceZone(705, 'A');
//            $this->terrain->addReinforceZone(804, 'A');
//            $this->terrain->addReinforceZone(905, 'A');
//            $this->terrain->addReinforceZone(1004, 'A');
//            $this->terrain->addReinforceZone(1105, 'A');
//            $this->terrain->addReinforceZone(1304, 'A');
//            $this->terrain->addReinforceZone(1403, 'A');
//            $this->terrain->addReinforceZone(1402, 'A');
//            $this->terrain->addReinforceZone(103, 'A');
//            $this->terrain->addReinforceZone(203, 'A');
//            $this->terrain->addReinforceZone(304, 'A');
//            $this->terrain->addReinforceZone(404, 'A');
//            $this->terrain->addReinforceZone(704, 'A');
//            $this->terrain->addReinforceZone(803, 'A');
//            $this->terrain->addReinforceZone(904, 'A');
//            $this->terrain->addReinforceZone(1301, 'A');
//            $this->terrain->addReinforceZone(1303, 'A');
//            $this->terrain->addReinforceZone(1203, 'A');
//            $this->terrain->addReinforceZone(102, 'A');
//            $this->terrain->addReinforceZone(202, 'A');
//            $this->terrain->addReinforceZone(303, 'A');
//            $this->terrain->addReinforceZone(403, 'A');
//            $this->terrain->addReinforceZone(503, 'A');
//            $this->terrain->addReinforceZone(703, 'A');
//            $this->terrain->addReinforceZone(1103, 'A');
//            $this->terrain->addReinforceZone(1201, 'A');
//            $this->terrain->addReinforceZone(1114, 'B');
//            $this->terrain->addReinforceZone(1112, 'B');
//            $this->terrain->addReinforceZone(1211, 'B');
//            $this->terrain->addReinforceZone(1311, 'B');
//            $this->terrain->addReinforceZone(1410, 'B');
//            $this->terrain->addReinforceZone(1510, 'B');
//            $this->terrain->addReinforceZone(1609, 'B');
//            $this->terrain->addReinforceZone(1710, 'B');
//            $this->terrain->addReinforceZone(1810, 'B');
//            $this->terrain->addReinforceZone(1911, 'B');
//            $this->terrain->addReinforceZone(1912, 'B');
//            $this->terrain->addReinforceZone(1811, 'B');
//            $this->terrain->addReinforceZone(1711, 'B');
//            $this->terrain->addReinforceZone(1610, 'B');
//            $this->terrain->addReinforceZone(1511, 'B');
//            $this->terrain->addReinforceZone(1411, 'B');
//            $this->terrain->addReinforceZone(1312, 'B');
//            $this->terrain->addReinforceZone(1212, 'B');
//            $this->terrain->addReinforceZone(1213, 'B');
//            $this->terrain->addReinforceZone(1214, 'B');
//            $this->terrain->addReinforceZone(1313, 'B');
//            $this->terrain->addReinforceZone(1314, 'B');
//            $this->terrain->addReinforceZone(1413, 'B');
//            $this->terrain->addReinforceZone(1412, 'B');
//            $this->terrain->addReinforceZone(1414, 'B');
//            $this->terrain->addReinforceZone(1514, 'B');
//            $this->terrain->addReinforceZone(1513, 'B');
//            $this->terrain->addReinforceZone(1611, 'B');
//            $this->terrain->addReinforceZone(1512, 'B');
//            $this->terrain->addReinforceZone(1612, 'B');
//            $this->terrain->addReinforceZone(1613, 'B');
//            $this->terrain->addReinforceZone(1614, 'B');
//            $this->terrain->addReinforceZone(1714, 'B');
//            $this->terrain->addReinforceZone(1713, 'B');
//            $this->terrain->addReinforceZone(1712, 'B');
//            $this->terrain->addReinforceZone(1812, 'B');
//            $this->terrain->addReinforceZone(1913, 'B');
//            $this->terrain->addReinforceZone(1914, 'B');
//            $this->terrain->addReinforceZone(1813, 'B');

            // end terrain data ----------------------------------------

        }

    }
}