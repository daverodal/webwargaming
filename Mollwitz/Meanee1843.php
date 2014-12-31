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


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
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
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;

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
            $this->terrainName = $data->terrainName;
            $this->game = $data->game;
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
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;
            $this->victory = new Victory("Mollwitz/Meanee1843/meanee1843VictoryCore.php");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;


            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->zocBlocksRetreat = true;
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            // game data

            $this->gameRules->setMaxTurn(12);
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

        }
    }
}