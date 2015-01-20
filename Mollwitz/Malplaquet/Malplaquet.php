<?php
set_include_path(__DIR__ . "/Malplaquet" . PATH_SEPARATOR . get_include_path());

define("ANGLO_FORCE", 1);
define("FRENCH_FORCE", 2);

global $force_name;
$force_name[ANGLO_FORCE] = "AngloAllied";
$force_name[FRENCH_FORCE] = "French";

require_once "JagCore.php";

class Malplaquet extends JagCore
{

    public $specialHexesMap = ['SpecialHexA'=>FRENCH_FORCE, 'SpecialHexB'=>FRENCH_FORCE, 'SpecialHexC'=>FRENCH_FORCE];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;
    public $malplaquet;
    public $otherCities;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "MalplaquetHeader.php";

    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "AngloAllied";
        $deployOne = $playerTwo = "French";
        @include_once "view.php";
    }

    public function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
        $this->malplaquet = $this->specialHexA;
        $this->otherCities = $this->specialHexB;
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
        $data->malplaquet = $this->malplaquet;
        $data->otherCities = $this->otherCities;

        return $data;
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
        $nFiveThrees = 4;
        if($this->scenario->bigBritish){
            $nFiveThrees = 7;
        }
        for ($i = 0; $i < $nFiveThrees; $i++) {
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
            $this->terrainName = $data->terrainName;
            $this->malplaquet = $data->malplaquet;
            $this->otherCities = $data->otherCities;
            $this->game = $data->game;
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
            $this->players = $data->players;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;
            $this->victory = new Victory("Mollwitz/Malplaquet/malplaquetVictoryCore.php");

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
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
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