<?php
set_include_path(__DIR__ . "/Burkersdorf" . PATH_SEPARATOR . get_include_path());
define("AUSTRIAN_FORCE", 1);
define("PRUSSIAN_FORCE", 2);

global $force_name;
$force_name[PRUSSIAN_FORCE] = "Prussian";
$force_name[AUSTRIAN_FORCE] = "Austrian";
require_once "JagCore.php";

class Burkersdorf extends JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>1, 'SpecialHexC'=>2];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
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
    public $cities;
    public $loc;


    public $players;

    static function playMulti($name, $wargame, $arg = false)
    {
        $deployOne = $playerOne = "Austrian";
        $deployTwo = $playerTwo = "Prussian";

        @include_once "playMulti.php";
    }

    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "BurkersdorfHeader.php";

    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployOne = $playerOne = "Austrian";
        $deployTwo = $playerTwo = "Prussian";
        @include_once "view.php";
    }


    public function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
        $this->cities = $this->specialHexA;
        $this->loc = $this->specialHexB;
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
        $data->terrainName = "terrain-".get_class($this);
        $data->genTerrain = $this->genTerrain;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;
        $data->cities = $this->cities;
        $data->loc = $this->loc;
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }


    public function init()
    {

        $artRange = 3;

        if($this->scenario->bigAustrian){
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'infantry');
            }
            for ($i = 0; $i < 25; $i++) {
                $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'infantry');
            }
        }else{
            for ($i = 0; $i < 31; $i++) {
                $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'infantry');
            }
        }

        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 5, 5, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 8; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Austrian", false, 'artillery');
        }


        for ($i = 0; $i < 14; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 10; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 6, 6, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 7; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Prussian", false, 'artillery');
        }
         for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Prussian", false, 'artillery');
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->cities = $data->cities;
            $this->loc = $data->loc;
            $this->game = $data->game;
            $this->genTerrain = false;
            $this->victory = new Victory("Mollwitz/Burkersdorf/burkersdorfVictoryCore.php", $data);
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
            $this->genTerrain = true;
            $this->victory = new Victory("Mollwitz/Burkersdorf/burkersdorfVictoryCore.php");

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
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);


            // game data
            $this->gameRules->setMaxTurn(12);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);


        }
    }
}