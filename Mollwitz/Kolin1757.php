<?php
set_include_path(__DIR__ . "/Kolin1757" . PATH_SEPARATOR . get_include_path());
require_once "IndiaCore.php";
/* comment */
define("PRUSSIAN_FORCE", 1);
define("AUSTRIAN_FORCE", 2);

$force_name[AUSTRIAN_FORCE] = "Austrian";
$force_name[PRUSSIAN_FORCE] = "Prussian";
$phase_name = array();
$phase_name[1] = "<span class='playerOneFace'>{$force_name[1]}</span> Move";
$phase_name[2] = "<span class='playerOneFace'>{$force_name[1]}</span> Combat";
$phase_name[3] = "";
$phase_name[4] = "<span class='playerTwoFace'>{$force_name[2]}</span> Move";
$phase_name[5] = "<span class='playerTwoFace'>{$force_name[2]}</span> Combat";
$phase_name[6] = "";
$phase_name[7] = "Victory";
$phase_name[8] = "<span class='playerOneFace'>{$force_name[1]}</span> Deploy";
$phase_name[9] = "";
$phase_name[10] = "";
$phase_name[11] = "";
$phase_name[12] = "";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "<span class='playerTwoFace'>{$force_name[2]}</span> deploy phase";


$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class Kolin1757 extends JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>0];

    public
    static function getHeader($name, $playerData, $arg = false)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "Kolin1757Header.php";

    }


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        $deployTwo = $playerOne = "Austrian";
        $deployOne = $playerTwo = "Prussian";
        @include_once "playMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "Austrian";
        $deployOne = $playerTwo = "Prussian";
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
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }

    public function init()
    {

        $artRange = 3;
        $coinFlip = floor(2 * (rand() / getrandmax()));
        $prussianDeploy = ($coinFlip == 1 ? "B": "C");

        /* Austrian */
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        }
        for ($i = 0; $i < 16; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 8; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, 3, "Austrian", false, 'artillery');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "A", 1, 3, "Austrian", false, 'artillery');
        }

        /* Prussian */
        for ($i = 0; $i < 4; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 12; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 3, "Prussian", false, 'artillery');
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
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->victory = new Victory("Mollwitz/Kolin1757/kolin1757VictoryCore.php", $data);
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
            $this->victory = new Victory("Mollwitz/Kolin1757/kolin1757VictoryCore.php");

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

        }
    }
}