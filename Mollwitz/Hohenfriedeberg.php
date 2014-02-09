<?php
set_include_path(__DIR__ . "/Hohenfriedeberg". PATH_SEPARATOR .  get_include_path());

/* comment */
require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
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


class Hohenfriedeberg extends Battle
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

    static function getHeader($name, $playerData)
    {
        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "commonHeader.php";
        @include_once "header.php";
        @include_once "HohenfriedebergHeader.php";

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
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;
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

    public function init(){

        $artRange = 3;

        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');

        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');

        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');

        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');

        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');

        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');


    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->game = $data->game;
            $this->genTerrain = false;
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
            $this->victory = new Victory("Mollwitz/Hohenfriedeberg/hohenfriedebergVictoryCore.php", $data);
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
            $this->victory = new Victory("Mollwitz/Hohenfriedeberg/hohenfriedebergVictoryCore.php");

            $this->mapData->setData(24, 24, "js/Hohenfriedeberg3AustrianObjectivesSmall.png");

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
            $this->gameRules->setMaxTurn(15);
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



            for ($col = 100; $col <= 2400; $col += 100) {
                for ($row = 1; $row <= 24; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
            $specialHexA = [];
            $specialHexB = [];
            $specialHexC = [];
            $this->terrain->addTerrain(801 ,1 , "forest");
            $this->terrain->addReinforceZone(801,'A');
            $this->terrain->addTerrain(801 ,2 , "forest");
            $this->terrain->addTerrain(802 ,1 , "forest");
            $this->terrain->addReinforceZone(802,'A');
            $this->terrain->addTerrain(902 ,4 , "forest");
            $this->terrain->addTerrain(902 ,3 , "forest");
            $this->terrain->addTerrain(903 ,4 , "forest");
            $this->terrain->addTerrain(902 ,1 , "forest");
            $this->terrain->addReinforceZone(902,'A');
            $this->terrain->addTerrain(902 ,2 , "forest");
            $this->terrain->addTerrain(903 ,1 , "forest");
            $this->terrain->addReinforceZone(903,'A');
            $this->terrain->addTerrain(1002 ,4 , "forest");
            $this->terrain->addTerrain(1002 ,3 , "forest");
            $this->terrain->addTerrain(1002 ,1 , "forest");
            $this->terrain->addReinforceZone(1002,'A');
            $this->terrain->addTerrain(1102 ,3 , "forest");
            $this->terrain->addTerrain(1103 ,4 , "forest");
            $this->terrain->addTerrain(1102 ,1 , "forest");
            $this->terrain->addReinforceZone(1102,'A');
            $this->terrain->addTerrain(1102 ,2 , "forest");
            $this->terrain->addTerrain(1103 ,1 , "forest");
            $this->terrain->addReinforceZone(1103,'A');
            $this->terrain->addTerrain(1202 ,4 , "forest");
            $this->terrain->addTerrain(1202 ,3 , "forest");
            $this->terrain->addTerrain(1202 ,1 , "forest");
            $this->terrain->addReinforceZone(1202,'A');
            $this->terrain->addTerrain(404 ,1 , "forest");
            $this->terrain->addReinforceZone(404,'A');
            $this->terrain->addTerrain(404 ,3 , "forest");
            $this->terrain->addTerrain(504 ,3 , "forest");
            $this->terrain->addTerrain(505 ,4 , "forest");
            $this->terrain->addTerrain(504 ,1 , "forest");
            $this->terrain->addReinforceZone(504,'A');
            $this->terrain->addTerrain(504 ,1 , "road");
            $this->terrain->addTerrain(504 ,2 , "forest");
            $this->terrain->addTerrain(505 ,1 , "forest");
            $this->terrain->addReinforceZone(505,'A');
            $this->terrain->addTerrain(604 ,4 , "forest");
            $this->terrain->addTerrain(604 ,4 , "road");
            $this->terrain->addTerrain(604 ,3 , "forest");
            $this->terrain->addTerrain(604 ,1 , "forest");
            $this->terrain->addReinforceZone(604,'A');
            $this->terrain->addTerrain(604 ,1 , "road");
            $this->terrain->addTerrain(604 ,2 , "forest");
            $this->terrain->addTerrain(605 ,1 , "forest");
            $this->terrain->addReinforceZone(605,'A');
            $this->terrain->addTerrain(706 ,4 , "forest");
            $this->terrain->addTerrain(706 ,1 , "forest");
            $this->terrain->addReinforceZone(706,'A');
            $this->terrain->addTerrain(111 ,1 , "forest");
            $this->terrain->addReinforceZone(111,'A');
            $this->terrain->addTerrain(110 ,2 , "forest");
            $this->terrain->addTerrain(110 ,1 , "forest");
            $this->terrain->addReinforceZone(110,'A');
            $this->terrain->addTerrain(210 ,4 , "forest");
            $this->terrain->addTerrain(210 ,3 , "forest");
            $this->terrain->addTerrain(210 ,1 , "forest");
            $this->terrain->addReinforceZone(210,'A');
            $this->terrain->addTerrain(611 ,1 , "forest");
            $this->terrain->addReinforceZone(611,'A');
            $this->terrain->addTerrain(611 ,1 , "road");
            $this->terrain->addTerrain(204 ,1 , "town");
            $this->terrain->addReinforceZone(204,'A');
            $this->terrain->addTerrain(305 ,4 , "town");
            $this->terrain->addTerrain(305 ,1 , "town");
            $this->terrain->addReinforceZone(305,'A');
            $this->terrain->addTerrain(305 ,2 , "town");
            $this->terrain->addTerrain(306 ,1 , "town");
            $this->terrain->addReinforceZone(306,'A');
            $this->terrain->addTerrain(1005 ,1 , "town");
            $this->terrain->addReinforceZone(1005,'A');
            $this->terrain->addTerrain(1105 ,3 , "town");
            $this->terrain->addTerrain(1105 ,1 , "town");
            $this->terrain->addReinforceZone(1105,'A');
            $this->terrain->addTerrain(1204 ,3 , "town");
            $this->terrain->addTerrain(1204 ,1 , "town");
            $this->terrain->addReinforceZone(1204,'A');
            $this->terrain->addTerrain(310 ,1 , "town");
            $this->terrain->addReinforceZone(310,'A');
            $this->terrain->addTerrain(410 ,4 , "town");
            $this->terrain->addTerrain(410 ,1 , "town");
            $this->terrain->addReinforceZone(410,'A');
            $this->terrain->addTerrain(511 ,4 , "town");
            $this->terrain->addTerrain(511 ,1 , "town");
            $this->terrain->addReinforceZone(511,'A');
            $this->terrain->addTerrain(2008 ,1 , "town");
            $this->terrain->addReinforceZone(2008,'B');
            $this->terrain->addTerrain(2008 ,2 , "town");
            $this->terrain->addTerrain(2009 ,1 , "town");
            $this->terrain->addReinforceZone(2009,'B');
            $this->terrain->addTerrain(2009 ,2 , "town");
            $this->terrain->addTerrain(2010 ,1 , "town");
            $this->terrain->addReinforceZone(2010,'B');
            $this->terrain->addTerrain(2010 ,2 , "town");
            $this->terrain->addTerrain(2011 ,1 , "town");
            $this->terrain->addReinforceZone(2011,'B');
            $specialHexB[] = 2011;
            $this->terrain->addTerrain(2011 ,3 , "town");
            $this->terrain->addTerrain(1912 ,1 , "town");
            $this->terrain->addReinforceZone(1912,'B');
            $this->terrain->addTerrain(1912 ,3 , "town");
            $this->terrain->addTerrain(1812 ,1 , "town");
            $this->terrain->addReinforceZone(1812,'B');
            $specialHexB[] = 1812;
            $this->terrain->addTerrain(1913 ,4 , "town");
            $this->terrain->addTerrain(1913 ,4 , "river");
            $this->terrain->addTerrain(1913 ,1 , "town");
            $this->terrain->addReinforceZone(1913,'B');
            $this->terrain->addTerrain(2012 ,3 , "town");
            $this->terrain->addTerrain(2012 ,1 , "town");
            $this->terrain->addReinforceZone(2012,'B');
            $this->terrain->addTerrain(2011 ,2 , "town");
            $this->terrain->addTerrain(2011 ,2 , "river");
            $this->terrain->addTerrain(2112 ,3 , "town");
            $this->terrain->addTerrain(2112 ,1 , "town");
            $this->terrain->addReinforceZone(2112,'B');
            $specialHexB[] = 2112;
            $this->terrain->addTerrain(2111 ,2 , "town");
            $this->terrain->addTerrain(2111 ,2 , "river");
            $this->terrain->addTerrain(2111 ,2 , "trail");
            $this->terrain->addTerrain(2111 ,1 , "town");
            $this->terrain->addReinforceZone(2111,'B');
            $specialHexB[] = 2111;
            $this->terrain->addTerrain(2110 ,2 , "town");
            $this->terrain->addTerrain(2110 ,1 , "town");
            $this->terrain->addReinforceZone(2110,'B');
            $this->terrain->addTerrain(2110 ,4 , "town");
            $this->terrain->addTerrain(2110 ,3 , "town");
            $this->terrain->addTerrain(2111 ,4 , "town");
            $this->terrain->addTerrain(2111 ,3 , "town");
            $this->terrain->addTerrain(2012 ,4 , "town");
            $this->terrain->addTerrain(2012 ,4 , "river");
            $this->terrain->addTerrain(1912 ,2 , "town");
            $this->terrain->addTerrain(1912 ,2 , "river");
            $this->terrain->addTerrain(2112 ,4 , "town");
            $this->terrain->addTerrain(2112 ,4 , "river");
            $this->terrain->addTerrain(2112 ,4 , "trail");
            $this->terrain->addTerrain(716 ,1 , "town");
            $this->terrain->addReinforceZone(716,'A');
            $this->terrain->addTerrain(815 ,3 , "town");
            $this->terrain->addTerrain(815 ,1 , "town");
            $this->terrain->addReinforceZone(815,'A');
            $this->terrain->addTerrain(915 ,3 , "town");
            $this->terrain->addTerrain(915 ,1 , "town");
            $this->terrain->addReinforceZone(915,'A');
            $this->terrain->addTerrain(120 ,1 , "town");
            $this->terrain->addReinforceZone(120,'A');
            $this->terrain->addTerrain(120 ,2 , "town");
            $this->terrain->addTerrain(121 ,1 , "town");
            $this->terrain->addReinforceZone(121,'A');
            $specialHexA[] = 121;
            $this->terrain->addTerrain(220 ,4 , "town");
            $this->terrain->addTerrain(220 ,3 , "town");
            $this->terrain->addTerrain(220 ,1 , "town");
            $this->terrain->addReinforceZone(220,'A');
            $this->terrain->addTerrain(220 ,2 , "town");
            $this->terrain->addTerrain(221 ,1 , "town");
            $this->terrain->addReinforceZone(221,'A');
            $this->terrain->addTerrain(321 ,4 , "town");
            $this->terrain->addTerrain(321 ,3 , "town");
            $this->terrain->addTerrain(321 ,1 , "town");
            $this->terrain->addReinforceZone(321,'A');
            $this->terrain->addTerrain(321 ,2 , "town");
            $this->terrain->addTerrain(321 ,2 , "river");
            $this->terrain->addTerrain(321 ,2 , "trail");
            $this->terrain->addTerrain(322 ,1 , "town");
            $this->terrain->addTerrain(620 ,1 , "town");
            $this->terrain->addReinforceZone(620,'A');
            $this->terrain->addTerrain(720 ,3 , "town");
            $this->terrain->addTerrain(720 ,1 , "town");
            $this->terrain->addReinforceZone(720,'A');
            $specialHexA[] = 720;
            $this->terrain->addTerrain(820 ,4 , "town");
            $this->terrain->addTerrain(820 ,1 , "town");
            $this->terrain->addReinforceZone(820,'A');
            $this->terrain->addTerrain(1120 ,1 , "town");
            $this->terrain->addTerrain(1219 ,3 , "town");
            $this->terrain->addTerrain(1219 ,1 , "town");
            $this->terrain->addTerrain(1218 ,2 , "town");
            $this->terrain->addTerrain(1218 ,1 , "town");
            $this->terrain->addTerrain(724 ,1 , "town");
            $this->terrain->addTerrain(824 ,4 , "town");
            $this->terrain->addTerrain(824 ,1 , "town");
            $this->terrain->addTerrain(924 ,3 , "town");
            $this->terrain->addTerrain(924 ,3 , "river");
            $this->terrain->addTerrain(924 ,3 , "trail");
            $this->terrain->addTerrain(924 ,1 , "town");
            $this->terrain->addTerrain(322 ,4 , "town");
            $this->terrain->addTerrain(322 ,4 , "river");
            $this->terrain->addTerrain(221 ,4 , "town");
            $this->terrain->addTerrain(405 ,1 , "swamp");
            $this->terrain->addReinforceZone(405,'A');
            $this->terrain->addTerrain(406 ,1 , "swamp");
            $this->terrain->addReinforceZone(406,'A');
            $this->terrain->addTerrain(506 ,1 , "swamp");
            $this->terrain->addReinforceZone(506,'A');
            $this->terrain->addTerrain(507 ,1 , "swamp");
            $this->terrain->addReinforceZone(507,'A');
            $this->terrain->addTerrain(606 ,1 , "swamp");
            $this->terrain->addReinforceZone(606,'A');
            $this->terrain->addTerrain(607 ,1 , "swamp");
            $this->terrain->addReinforceZone(607,'A');
            $this->terrain->addTerrain(608 ,1 , "swamp");
            $this->terrain->addReinforceZone(608,'A');
            $this->terrain->addTerrain(608 ,1 , "road");
            $this->terrain->addTerrain(708 ,1 , "swamp");
            $this->terrain->addTerrain(708 ,1 , "road");
            $this->terrain->addReinforceZone(708,'A');
            $this->terrain->addTerrain(709 ,1 , "swamp");
            $this->terrain->addTerrain(809 ,1 , "swamp");
            $this->terrain->addTerrain(910 ,1 , "swamp");
            $this->terrain->addTerrain(1312 ,4 , "road");
            $this->terrain->addTerrain(1211 ,1 , "swamp");
            $this->terrain->addTerrain(1211 ,1 , "road");
            $this->terrain->addTerrain(1212 ,1 , "swamp");
            $this->terrain->addTerrain(1312 ,1 , "swamp");
            $this->terrain->addTerrain(1312 ,1 , "road");
            $this->terrain->addTerrain(1311 ,1 , "swamp");
            $this->terrain->addTerrain(1410 ,1 , "swamp");
            $this->terrain->addTerrain(2109 ,1 , "swamp");
            $this->terrain->addReinforceZone(2109,'B');
            $this->terrain->addTerrain(2208 ,1 , "swamp");
            $this->terrain->addReinforceZone(2208,'B');
            $this->terrain->addTerrain(2209 ,1 , "swamp");
            $this->terrain->addReinforceZone(2209,'B');
            $this->terrain->addTerrain(2309 ,1 , "swamp");
            $this->terrain->addReinforceZone(2309,'B');
            $this->terrain->addTerrain(123 ,1 , "swamp");
            $this->terrain->addTerrain(222 ,1 , "swamp");
            $this->terrain->addTerrain(222 ,1 , "road");
            $this->terrain->addTerrain(323 ,1 , "swamp");
            $this->terrain->addTerrain(124 ,1 , "swamp");
            $this->terrain->addTerrain(124 ,1 , "road");
            $specialHexA[] = 124;
            $this->terrain->addTerrain(223 ,1 , "swamp");
            $this->terrain->addTerrain(223 ,1 , "road");
            $this->terrain->addTerrain(324 ,1 , "swamp");
            $this->terrain->addTerrain(324 ,1 , "road");
            $this->terrain->addTerrain(224 ,1 , "swamp");
            $this->terrain->addReinforceZone(101,'A');
            $this->terrain->addReinforceZone(201,'A');
            $this->terrain->addReinforceZone(301,'A');
            $this->terrain->addReinforceZone(401,'A');
            $this->terrain->addReinforceZone(501,'A');
            $this->terrain->addReinforceZone(601,'A');
            $this->terrain->addReinforceZone(701,'A');
            $this->terrain->addReinforceZone(901,'A');
            $this->terrain->addReinforceZone(1001,'A');
            $this->terrain->addReinforceZone(1101,'A');
            $this->terrain->addReinforceZone(1201,'A');
            $this->terrain->addReinforceZone(102,'A');
            $this->terrain->addTerrain(102 ,1 , "road");
            $specialHexA[] = 102;
            $this->terrain->addReinforceZone(202,'A');
            $this->terrain->addTerrain(202 ,1 , "road");
            $this->terrain->addReinforceZone(302,'A');
            $this->terrain->addReinforceZone(402,'A');
            $this->terrain->addReinforceZone(502,'A');
            $this->terrain->addReinforceZone(602,'A');
            $this->terrain->addReinforceZone(702,'A');
            $this->terrain->addReinforceZone(103,'A');
            $this->terrain->addReinforceZone(203,'A');
            $this->terrain->addReinforceZone(303,'A');
            $this->terrain->addTerrain(303 ,1 , "road");
            $this->terrain->addReinforceZone(403,'A');
            $this->terrain->addTerrain(403 ,1 , "road");
            $this->terrain->addReinforceZone(503,'A');
            $this->terrain->addReinforceZone(603,'A');
            $this->terrain->addReinforceZone(703,'A');
            $this->terrain->addReinforceZone(803,'A');
            $this->terrain->addReinforceZone(1003,'A');
            $this->terrain->addReinforceZone(1203,'A');
            $this->terrain->addReinforceZone(104,'A');
            $specialHexA[] = 104;
            $this->terrain->addReinforceZone(304,'A');
            $this->terrain->addReinforceZone(704,'A');
            $this->terrain->addReinforceZone(804,'A');
            $this->terrain->addReinforceZone(904,'A');
            $this->terrain->addReinforceZone(1004,'A');
            $this->terrain->addReinforceZone(1104,'A');
            $this->terrain->addReinforceZone(105,'A');
            $this->terrain->addReinforceZone(205,'A');
            $this->terrain->addReinforceZone(705,'A');
            $this->terrain->addTerrain(705 ,1 , "road");
            $this->terrain->addReinforceZone(805,'A');
            $this->terrain->addTerrain(805 ,1 , "road");
            $this->terrain->addReinforceZone(905,'A');
            $this->terrain->addReinforceZone(106,'A');
            $specialHexA[] = 106;
            $this->terrain->addReinforceZone(206,'A');
            $this->terrain->addReinforceZone(806,'A');
            $this->terrain->addReinforceZone(906,'A');
            $this->terrain->addTerrain(906 ,1 , "road");
            $this->terrain->addReinforceZone(107,'A');
            $this->terrain->addReinforceZone(207,'A');
            $this->terrain->addReinforceZone(307,'A');
            $this->terrain->addReinforceZone(407,'A');
            $this->terrain->addReinforceZone(707,'A');
            $this->terrain->addReinforceZone(807,'A');
            $this->terrain->addTerrain(807 ,1 , "road");
            $this->terrain->addReinforceZone(907,'A');
            $this->terrain->addTerrain(907 ,1 , "road");
            $this->terrain->addReinforceZone(108,'A');
            $specialHexA[] = 108;
            $this->terrain->addReinforceZone(208,'A');
            $this->terrain->addReinforceZone(308,'A');
            $this->terrain->addReinforceZone(408,'A');
            $this->terrain->addReinforceZone(508,'A');
            $this->terrain->addReinforceZone(109,'A');
            $this->terrain->addReinforceZone(209,'A');
            $this->terrain->addReinforceZone(309,'A');
            $this->terrain->addReinforceZone(409,'A');
            $this->terrain->addReinforceZone(509,'A');
            $this->terrain->addReinforceZone(609,'A');
            $this->terrain->addTerrain(609 ,1 , "road");
            $this->terrain->addReinforceZone(510,'A');
            $this->terrain->addReinforceZone(610,'A');
            $this->terrain->addTerrain(610 ,1 , "road");
            $this->terrain->addReinforceZone(211,'A');
            $this->terrain->addReinforceZone(311,'A');
            $this->terrain->addReinforceZone(411,'A');
            $this->terrain->addReinforceZone(711,'A');
            $this->terrain->addTerrain(711 ,1 , "road");
            $this->terrain->addReinforceZone(112,'A');
            $this->terrain->addReinforceZone(212,'A');
            $this->terrain->addReinforceZone(312,'A');
            $this->terrain->addReinforceZone(412,'A');
            $this->terrain->addReinforceZone(512,'A');
            $this->terrain->addReinforceZone(612,'A');
            $this->terrain->addReinforceZone(712,'A');
            $this->terrain->addTerrain(712 ,1 , "road");
            $this->terrain->addReinforceZone(113,'A');
            $this->terrain->addReinforceZone(213,'A');
            $this->terrain->addReinforceZone(313,'A');
            $this->terrain->addReinforceZone(413,'A');
            $this->terrain->addReinforceZone(513,'A');
            $this->terrain->addReinforceZone(613,'A');
            $this->terrain->addReinforceZone(713,'A');
            $this->terrain->addTerrain(713 ,1 , "road");
            $this->terrain->addReinforceZone(114,'A');
            $this->terrain->addReinforceZone(214,'A');
            $this->terrain->addReinforceZone(314,'A');
            $this->terrain->addReinforceZone(414,'A');
            $this->terrain->addReinforceZone(514,'A');
            $this->terrain->addReinforceZone(614,'A');
            $this->terrain->addReinforceZone(714,'A');
            $this->terrain->addReinforceZone(814,'A');
            $this->terrain->addTerrain(814 ,1 , "road");
            $this->terrain->addReinforceZone(813,'A');
            $this->terrain->addTerrain(813 ,1 , "road");
            $this->terrain->addReinforceZone(115,'A');
            $this->terrain->addReinforceZone(215,'A');
            $this->terrain->addReinforceZone(315,'A');
            $this->terrain->addReinforceZone(415,'A');
            $this->terrain->addReinforceZone(515,'A');
            $this->terrain->addReinforceZone(615,'A');
            $this->terrain->addReinforceZone(715,'A');
            $this->terrain->addReinforceZone(116,'A');
            $this->terrain->addReinforceZone(216,'A');
            $this->terrain->addReinforceZone(316,'A');
            $this->terrain->addReinforceZone(416,'A');
            $this->terrain->addReinforceZone(516,'A');
            $this->terrain->addReinforceZone(616,'A');
            $this->terrain->addTerrain(616 ,1 , "road");
            $this->terrain->addReinforceZone(117,'A');
            $this->terrain->addTerrain(117 ,1 , "road");
            $specialHexA[] = 117;
            $this->terrain->addReinforceZone(217,'A');
            $this->terrain->addTerrain(217 ,1 , "road");
            $this->terrain->addReinforceZone(317,'A');
            $this->terrain->addReinforceZone(417,'A');
            $this->terrain->addTerrain(417 ,1 , "road");
            $this->terrain->addReinforceZone(517,'A');
            $this->terrain->addTerrain(517 ,1 , "road");
            $this->terrain->addReinforceZone(617,'A');
            $this->terrain->addReinforceZone(717,'A');
            $this->terrain->addReinforceZone(118,'A');
            $this->terrain->addReinforceZone(218,'A');
            $this->terrain->addReinforceZone(318,'A');
            $this->terrain->addTerrain(318 ,1 , "road");
            $this->terrain->addReinforceZone(418,'A');
            $this->terrain->addTerrain(418 ,1 , "road");
            $this->terrain->addReinforceZone(518,'A');
            $this->terrain->addReinforceZone(618,'A');
            $this->terrain->addReinforceZone(718,'A');
            $this->terrain->addReinforceZone(119,'A');
            $this->terrain->addReinforceZone(219,'A');
            $this->terrain->addReinforceZone(319,'A');
            $this->terrain->addReinforceZone(419,'A');
            $this->terrain->addReinforceZone(519,'A');
            $this->terrain->addTerrain(519 ,1 , "road");
            $this->terrain->addReinforceZone(619,'A');
            $this->terrain->addTerrain(619 ,1 , "road");
            $this->terrain->addReinforceZone(719,'A');
            $this->terrain->addReinforceZone(320,'A');
            $this->terrain->addReinforceZone(420,'A');
            $this->terrain->addTerrain(420 ,1 , "road");
            $this->terrain->addReinforceZone(520,'A');
            $this->terrain->addTerrain(520 ,1 , "road");
            $this->terrain->addReinforceZone(122,'A');
            $this->terrain->addTerrain(1312 ,3 , "river");
            $this->terrain->addTerrain(1312 ,2 , "river");
            $this->terrain->addTerrain(1412 ,3 , "river");
            $this->terrain->addTerrain(1412 ,2 , "river");
            $this->terrain->addTerrain(1513 ,4 , "river");
            $this->terrain->addTerrain(1513 ,4 , "road");
            $this->terrain->addTerrain(1512 ,2 , "river");
            $this->terrain->addTerrain(1612 ,3 , "river");
            $this->terrain->addTerrain(1612 ,3 , "road");
            $this->terrain->addTerrain(1612 ,2 , "river");
            $this->terrain->addTerrain(1713 ,3 , "river");
            $this->terrain->addTerrain(1713 ,2 , "river");
            $this->terrain->addTerrain(1813 ,4 , "river");
            $this->terrain->addTerrain(1812 ,2 , "river");
            $this->terrain->addTerrain(1812 ,2 , "road");
            $this->terrain->addTerrain(2211 ,4 , "river");
            $this->terrain->addTerrain(2210 ,2 , "river");
            $this->terrain->addTerrain(2311 ,4 , "river");
            $this->terrain->addTerrain(2310 ,2 , "river");
            $this->terrain->addTerrain(2410 ,3 , "river");
            $this->terrain->addTerrain(2410 ,2 , "river");
            $this->terrain->addTerrain(122 ,2 , "river");
            $this->terrain->addTerrain(222 ,4 , "river");
            $this->terrain->addTerrain(221 ,2 , "river");
            $this->terrain->addTerrain(221 ,2 , "road");
            $this->terrain->addTerrain(421 ,4 , "river");
            $this->terrain->addTerrain(420 ,2 , "river");
            $this->terrain->addTerrain(521 ,3 , "river");
            $this->terrain->addTerrain(521 ,2 , "river");
            $this->terrain->addTerrain(621 ,4 , "river");
            $this->terrain->addTerrain(620 ,2 , "river");
            $this->terrain->addTerrain(721 ,3 , "river");
            $this->terrain->addTerrain(721 ,2 , "river");
            $this->terrain->addTerrain(821 ,4 , "river");
            $this->terrain->addTerrain(820 ,2 , "river");
            $this->terrain->addTerrain(921 ,4 , "river");
            $this->terrain->addTerrain(921 ,4 , "road");
            $this->terrain->addTerrain(920 ,3 , "river");
            $this->terrain->addTerrain(920 ,4 , "river");
            $this->terrain->addTerrain(919 ,2 , "river");
            $this->terrain->addTerrain(1019 ,4 , "river");
            $this->terrain->addTerrain(1018 ,2 , "river");
            $this->terrain->addTerrain(1119 ,4 , "river");
            $this->terrain->addTerrain(1118 ,2 , "river");
            $this->terrain->addTerrain(1218 ,4 , "river");
            $this->terrain->addTerrain(1218 ,4 , "road");
            $this->terrain->addTerrain(1217 ,2 , "river");
            $this->terrain->addTerrain(1318 ,4 , "river");
            $this->terrain->addTerrain(1317 ,3 , "river");
            $this->terrain->addTerrain(1317 ,4 , "river");
            $this->terrain->addTerrain(1316 ,2 , "river");
            $this->terrain->addTerrain(1416 ,4 , "river");
            $this->terrain->addTerrain(1415 ,2 , "river");
            $this->terrain->addTerrain(1516 ,4 , "river");
            $this->terrain->addTerrain(1515 ,2 , "river");
            $this->terrain->addTerrain(1615 ,4 , "river");
            $this->terrain->addTerrain(1614 ,2 , "river");
            $this->terrain->addTerrain(1715 ,4 , "river");
            $this->terrain->addTerrain(1714 ,2 , "river");
            $this->terrain->addTerrain(1814 ,4 , "river");
            $this->terrain->addTerrain(1813 ,3 , "river");
            $this->terrain->addTerrain(224 ,4 , "river");
            $this->terrain->addTerrain(223 ,2 , "river");
            $this->terrain->addTerrain(324 ,4 , "river");
            $this->terrain->addTerrain(324 ,4 , "road");
            $this->terrain->addTerrain(323 ,3 , "river");
            $this->terrain->addTerrain(323 ,4 , "river");
            $this->terrain->addTerrain(322 ,3 , "river");
            $this->terrain->addTerrain(424 ,4 , "river");
            $this->terrain->addTerrain(423 ,3 , "river");
            $this->terrain->addTerrain(423 ,3 , "road");
            $this->terrain->addTerrain(423 ,4 , "river");
            $this->terrain->addTerrain(422 ,3 , "river");
            $this->terrain->addTerrain(422 ,4 , "river");
            $this->terrain->addTerrain(421 ,3 , "river");
            $this->terrain->addTerrain(924 ,4 , "river");
            $this->terrain->addTerrain(923 ,3 , "river");
            $this->terrain->addTerrain(923 ,4 , "river");
            $this->terrain->addTerrain(922 ,2 , "river");
            $this->terrain->addTerrain(1022 ,4 , "river");
            $this->terrain->addTerrain(1021 ,2 , "river");
            $this->terrain->addTerrain(1122 ,4 , "river");
            $this->terrain->addTerrain(1121 ,2 , "river");
            $this->terrain->addTerrain(1221 ,4 , "river");
            $this->terrain->addTerrain(1220 ,3 , "river");
            $this->terrain->addTerrain(1220 ,4 , "river");
            $this->terrain->addTerrain(1219 ,2 , "river");
            $this->terrain->addTerrain(1320 ,4 , "river");
            $this->terrain->addTerrain(1319 ,3 , "river");
            $this->terrain->addTerrain(1319 ,4 , "river");
            $this->terrain->addTerrain(1318 ,3 , "river");
            $this->terrain->addTerrain(1318 ,3 , "road");
            $this->terrain->addTerrain(1615 ,3 , "river");
            $this->terrain->addTerrain(1615 ,3 , "road");
            $this->terrain->addTerrain(1616 ,4 , "river");
            $this->terrain->addTerrain(1616 ,3 , "river");
            $this->terrain->addTerrain(1616 ,2 , "river");
            $this->terrain->addTerrain(1717 ,3 , "river");
            $this->terrain->addTerrain(1717 ,2 , "river");
            $this->terrain->addTerrain(1817 ,3 , "river");
            $this->terrain->addTerrain(1818 ,4 , "river");
            $this->terrain->addTerrain(1818 ,3 , "river");
            $this->terrain->addTerrain(1819 ,4 , "river");
            $this->terrain->addTerrain(1819 ,3 , "river");
            $this->terrain->addTerrain(1820 ,4 , "river");
            $this->terrain->addTerrain(1820 ,3 , "river");
            $this->terrain->addTerrain(1821 ,4 , "river");
            $this->terrain->addTerrain(1721 ,2 , "river");
            $this->terrain->addTerrain(1722 ,4 , "river");
            $this->terrain->addTerrain(1722 ,3 , "river");
            $this->terrain->addTerrain(1723 ,4 , "river");
            $this->terrain->addTerrain(1723 ,3 , "river");
            $this->terrain->addTerrain(1724 ,4 , "river");
            $this->terrain->addTerrain(1623 ,2 , "river");
            $this->terrain->addTerrain(1624 ,4 , "river");
            $this->terrain->addTerrain(1814 ,3 , "river");
            $this->terrain->addTerrain(1814 ,3 , "road");
            $this->terrain->addTerrain(1814 ,2 , "river");
            $this->terrain->addTerrain(1814 ,2 , "road");
            $this->terrain->addTerrain(1915 ,3 , "river");
            $this->terrain->addTerrain(1915 ,2 , "river");
            $this->terrain->addTerrain(2015 ,4 , "river");
            $this->terrain->addTerrain(2015 ,4 , "road");
            $this->terrain->addTerrain(2014 ,2 , "river");
            $this->terrain->addTerrain(2115 ,3 , "river");
            $this->terrain->addTerrain(202 ,4 , "road");
            $this->terrain->addTerrain(303 ,4 , "road");
            $this->terrain->addTerrain(403 ,4 , "road");
            $this->terrain->addTerrain(504 ,4 , "road");
            $this->terrain->addTerrain(705 ,4 , "road");
            $this->terrain->addTerrain(805 ,4 , "road");
            $this->terrain->addTerrain(906 ,4 , "road");
            $this->terrain->addTerrain(1005 ,3 , "road");
            $this->terrain->addTerrain(906 ,2 , "road");
            $this->terrain->addTerrain(907 ,3 , "road");
            $this->terrain->addTerrain(807 ,3 , "road");
            $this->terrain->addTerrain(708 ,3 , "road");
            $this->terrain->addTerrain(608 ,2 , "road");
            $this->terrain->addTerrain(609 ,2 , "road");
            $this->terrain->addTerrain(610 ,3 , "road");
            $this->terrain->addTerrain(711 ,4 , "road");
            $this->terrain->addTerrain(810 ,3 , "road");
            $this->terrain->addTerrain(810 ,1 , "road");
            $this->terrain->addTerrain(911 ,4 , "road");
            $this->terrain->addTerrain(911 ,1 , "road");
            $this->terrain->addTerrain(1010 ,3 , "road");
            $this->terrain->addTerrain(1010 ,1 , "road");
            $this->terrain->addTerrain(1111 ,4 , "road");
            $this->terrain->addTerrain(1111 ,1 , "road");
            $this->terrain->addTerrain(1211 ,4 , "road");
            $this->terrain->addTerrain(1412 ,4 , "road");
            $this->terrain->addTerrain(1412 ,1 , "road");
            $this->terrain->addTerrain(1513 ,1 , "road");
            $this->terrain->addTerrain(1612 ,1 , "road");
            $this->terrain->addTerrain(1713 ,1 , "road");
            $this->terrain->addReinforceZone(1713,'B');
            $this->terrain->addTerrain(1812 ,3 , "road");
            $this->terrain->addTerrain(1813 ,1 , "road");
            $this->terrain->addReinforceZone(1813,'B');
            $this->terrain->addTerrain(1813 ,2 , "road");
            $this->terrain->addTerrain(1814 ,1 , "road");
            $this->terrain->addReinforceZone(1814,'B');
            $this->terrain->addTerrain(1815 ,1 , "road");
            $this->terrain->addReinforceZone(1815,'B');
            $this->terrain->addTerrain(1815 ,2 , "road");
            $this->terrain->addTerrain(1816 ,1 , "road");
            $this->terrain->addReinforceZone(1816,'B');
            $this->terrain->addTerrain(1816 ,2 , "road");
            $this->terrain->addTerrain(1817 ,1 , "road");
            $this->terrain->addReinforceZone(1817,'B');
            $this->terrain->addTerrain(1817 ,2 , "road");
            $this->terrain->addTerrain(1818 ,1 , "road");
            $this->terrain->addReinforceZone(1818,'B');
            $this->terrain->addTerrain(1818 ,2 , "road");
            $this->terrain->addTerrain(1819 ,1 , "road");
            $this->terrain->addReinforceZone(1819,'B');
            $this->terrain->addTerrain(1819 ,2 , "road");
            $this->terrain->addTerrain(1820 ,1 , "road");
            $this->terrain->addReinforceZone(1820,'B');
            $this->terrain->addTerrain(1820 ,2 , "road");
            $this->terrain->addTerrain(1821 ,1 , "road");
            $this->terrain->addReinforceZone(1821,'B');
            $this->terrain->addTerrain(1821 ,2 , "road");
            $this->terrain->addTerrain(1822 ,1 , "road");
            $this->terrain->addReinforceZone(1822,'B');
            $this->terrain->addTerrain(1822 ,2 , "road");
            $this->terrain->addTerrain(1823 ,1 , "road");
            $this->terrain->addReinforceZone(1823,'B');
            $this->terrain->addTerrain(1823 ,2 , "road");
            $this->terrain->addTerrain(1824 ,1 , "road");
            $this->terrain->addReinforceZone(1824,'B');
            $this->terrain->addTerrain(1915 ,4 , "road");
            $this->terrain->addTerrain(1915 ,1 , "road");
            $this->terrain->addReinforceZone(1915,'B');
            $this->terrain->addTerrain(2015 ,1 , "road");
            $this->terrain->addReinforceZone(2015,'B');
            $this->terrain->addTerrain(2116 ,4 , "road");
            $this->terrain->addTerrain(2116 ,1 , "road");
            $this->terrain->addReinforceZone(2116,'B');
            $this->terrain->addTerrain(2216 ,4 , "road");
            $this->terrain->addTerrain(2216 ,1 , "road");
            $this->terrain->addReinforceZone(2216,'B');
            $this->terrain->addTerrain(2317 ,4 , "road");
            $this->terrain->addTerrain(2317 ,1 , "road");
            $this->terrain->addReinforceZone(2317,'B');
            $this->terrain->addTerrain(2417 ,4 , "road");
            $this->terrain->addTerrain(2417 ,1 , "road");
            $this->terrain->addReinforceZone(2417,'B');
            $specialHexC[] = 2417;
            $this->terrain->addTerrain(1715 ,1 , "road");
            $this->terrain->addTerrain(1715 ,3 , "road");
            $this->terrain->addTerrain(1615 ,1 , "road");
            $this->terrain->addTerrain(1516 ,1 , "road");
            $this->terrain->addTerrain(1516 ,3 , "road");
            $this->terrain->addTerrain(1416 ,1 , "road");
            $this->terrain->addTerrain(1416 ,2 , "road");
            $this->terrain->addTerrain(1417 ,1 , "road");
            $this->terrain->addTerrain(1417 ,3 , "road");
            $this->terrain->addTerrain(1318 ,1 , "road");
            $this->terrain->addTerrain(1118 ,1 , "road");
            $this->terrain->addTerrain(1118 ,4 , "road");
            $this->terrain->addTerrain(1017 ,1 , "road");
            $this->terrain->addTerrain(1017 ,4 , "road");
            $this->terrain->addTerrain(917 ,1 , "road");
            $this->terrain->addTerrain(917 ,4 , "road");
            $this->terrain->addTerrain(816 ,1 , "road");
            $this->terrain->addTerrain(816 ,4 , "road");
            $this->terrain->addTerrain(716 ,3 , "road");
            $this->terrain->addTerrain(616 ,3 , "road");
            $this->terrain->addTerrain(517 ,3 , "road");
            $this->terrain->addTerrain(417 ,2 , "road");
            $this->terrain->addTerrain(519 ,4 , "road");
            $this->terrain->addTerrain(619 ,4 , "road");
            $this->terrain->addTerrain(720 ,4 , "road");
            $this->terrain->addTerrain(417 ,3 , "road");
            $this->terrain->addTerrain(318 ,4 , "road");
            $this->terrain->addTerrain(217 ,4 , "road");
            $this->terrain->addTerrain(620 ,4 , "road");
            $this->terrain->addTerrain(520 ,3 , "road");
            $this->terrain->addTerrain(420 ,3 , "road");
            $this->terrain->addTerrain(223 ,3 , "road");
            $this->terrain->addTerrain(423 ,1 , "road");
            $this->terrain->addTerrain(523 ,3 , "road");
            $this->terrain->addTerrain(523 ,1 , "road");
            $this->terrain->addTerrain(622 ,3 , "road");
            $this->terrain->addTerrain(622 ,1 , "road");
            $this->terrain->addTerrain(722 ,3 , "road");
            $this->terrain->addTerrain(722 ,1 , "road");
            $this->terrain->addTerrain(821 ,3 , "road");
            $this->terrain->addTerrain(821 ,1 , "road");
            $this->terrain->addTerrain(921 ,3 , "road");
            $this->terrain->addTerrain(921 ,1 , "road");
            $this->terrain->addTerrain(1020 ,3 , "road");
            $this->terrain->addTerrain(1020 ,1 , "road");
            $this->terrain->addTerrain(1120 ,3 , "road");
            $this->terrain->addTerrain(722 ,2 , "road");
            $this->terrain->addTerrain(723 ,1 , "road");
            $this->terrain->addTerrain(723 ,2 , "road");
            $this->terrain->addTerrain(2007 ,2 , "road");
            $this->terrain->addTerrain(2007 ,1 , "road");
            $this->terrain->addReinforceZone(2007,'B');
            $this->terrain->addTerrain(2006 ,2 , "road");
            $this->terrain->addTerrain(2006 ,1 , "road");
            $this->terrain->addReinforceZone(2006,'B');
            $this->terrain->addTerrain(2005 ,2 , "road");
            $this->terrain->addTerrain(2005 ,1 , "road");
            $this->terrain->addReinforceZone(2005,'B');
            $this->terrain->addTerrain(2004 ,2 , "road");
            $this->terrain->addTerrain(2004 ,1 , "road");
            $this->terrain->addReinforceZone(2004,'B');
            $this->terrain->addTerrain(2003 ,2 , "road");
            $this->terrain->addTerrain(2003 ,1 , "road");
            $this->terrain->addReinforceZone(2003,'B');
            $this->terrain->addTerrain(2002 ,2 , "road");
            $this->terrain->addTerrain(2002 ,1 , "road");
            $this->terrain->addReinforceZone(2002,'B');
            $this->terrain->addTerrain(2001 ,2 , "road");
            $this->terrain->addTerrain(2001 ,1 , "road");
            $this->terrain->addReinforceZone(2001,'B');
            $this->terrain->addTerrain(2000 ,2 , "road");
            $this->terrain->addTerrain(2005 ,4 , "road");
            $this->terrain->addTerrain(1905 ,1 , "road");
            $this->terrain->addTerrain(1905 ,4 , "road");
            $this->terrain->addTerrain(1804 ,1 , "road");
            $this->terrain->addTerrain(1804 ,3 , "road");
            $this->terrain->addTerrain(1705 ,1 , "road");
            $this->terrain->addTerrain(1705 ,3 , "road");
            $this->terrain->addTerrain(1605 ,1 , "road");
            $this->terrain->addTerrain(1605 ,4 , "road");
            $this->terrain->addTerrain(1505 ,1 , "road");
            $this->terrain->addTerrain(1505 ,3 , "road");
            $this->terrain->addTerrain(1405 ,1 , "road");
            $this->terrain->addTerrain(1405 ,4 , "road");
            $this->terrain->addTerrain(1305 ,1 , "road");
            $this->terrain->addTerrain(1305 ,4 , "road");
            $this->terrain->addReinforceZone(1908,'B');
            $this->terrain->addReinforceZone(1909,'B');
            $this->terrain->addReinforceZone(1910,'B');
            $this->terrain->addReinforceZone(1911,'B');
            $this->terrain->addReinforceZone(1811,'B');
            $this->terrain->addReinforceZone(1712,'B');
            $this->terrain->addReinforceZone(2101,'B');
            $this->terrain->addReinforceZone(2102,'B');
            $this->terrain->addReinforceZone(2103,'B');
            $this->terrain->addReinforceZone(2104,'B');
            $this->terrain->addReinforceZone(2105,'B');
            $this->terrain->addReinforceZone(2106,'B');
            $this->terrain->addReinforceZone(2107,'B');
            $this->terrain->addReinforceZone(2108,'B');
            $this->terrain->addReinforceZone(2013,'B');
            $this->terrain->addReinforceZone(2014,'B');
            $this->terrain->addReinforceZone(2016,'B');
            $this->terrain->addReinforceZone(2017,'B');
            $this->terrain->addReinforceZone(2018,'B');
            $this->terrain->addReinforceZone(2019,'B');
            $this->terrain->addReinforceZone(2020,'B');
            $this->terrain->addReinforceZone(2021,'B');
            $this->terrain->addReinforceZone(2022,'B');
            $this->terrain->addReinforceZone(2023,'B');
            $this->terrain->addReinforceZone(2024,'B');
            $this->terrain->addReinforceZone(1924,'B');
            $this->terrain->addReinforceZone(1923,'B');
            $this->terrain->addReinforceZone(1922,'B');
            $this->terrain->addReinforceZone(1921,'B');
            $this->terrain->addReinforceZone(1920,'B');
            $this->terrain->addReinforceZone(1919,'B');
            $this->terrain->addReinforceZone(1918,'B');
            $this->terrain->addReinforceZone(1917,'B');
            $this->terrain->addReinforceZone(1916,'B');
            $this->terrain->addReinforceZone(1914,'B');
            $this->terrain->addReinforceZone(2201,'B');
            $this->terrain->addReinforceZone(2202,'B');
            $this->terrain->addReinforceZone(2203,'B');
            $this->terrain->addReinforceZone(2204,'B');
            $this->terrain->addReinforceZone(2205,'B');
            $this->terrain->addReinforceZone(2206,'B');
            $this->terrain->addReinforceZone(2207,'B');
            $this->terrain->addReinforceZone(2210,'B');
            $this->terrain->addReinforceZone(2211,'B');
            $this->terrain->addReinforceZone(2212,'B');
            $this->terrain->addReinforceZone(2213,'B');
            $this->terrain->addReinforceZone(2214,'B');
            $this->terrain->addReinforceZone(2215,'B');
            $this->terrain->addReinforceZone(2217,'B');
            $this->terrain->addReinforceZone(2218,'B');
            $this->terrain->addReinforceZone(2219,'B');
            $this->terrain->addReinforceZone(2220,'B');
            $this->terrain->addReinforceZone(2221,'B');
            $this->terrain->addReinforceZone(2222,'B');
            $this->terrain->addReinforceZone(2223,'B');
            $this->terrain->addReinforceZone(2224,'B');
            $this->terrain->addReinforceZone(2124,'B');
            $this->terrain->addReinforceZone(2123,'B');
            $this->terrain->addReinforceZone(2122,'B');
            $this->terrain->addReinforceZone(2121,'B');
            $this->terrain->addReinforceZone(2120,'B');
            $this->terrain->addReinforceZone(2119,'B');
            $this->terrain->addReinforceZone(2118,'B');
            $this->terrain->addReinforceZone(2117,'B');
            $this->terrain->addReinforceZone(2115,'B');
            $this->terrain->addReinforceZone(2114,'B');
            $this->terrain->addReinforceZone(2113,'B');
            $this->terrain->addReinforceZone(2301,'B');
            $this->terrain->addReinforceZone(2302,'B');
            $this->terrain->addReinforceZone(2303,'B');
            $this->terrain->addReinforceZone(2304,'B');
            $this->terrain->addReinforceZone(2305,'B');
            $this->terrain->addReinforceZone(2306,'B');
            $this->terrain->addReinforceZone(2307,'B');
            $this->terrain->addReinforceZone(2308,'B');
            $this->terrain->addReinforceZone(2310,'B');
            $this->terrain->addReinforceZone(2311,'B');
            $this->terrain->addReinforceZone(2312,'B');
            $this->terrain->addReinforceZone(2313,'B');
            $this->terrain->addReinforceZone(2314,'B');
            $this->terrain->addReinforceZone(2315,'B');
            $this->terrain->addReinforceZone(2316,'B');
            $this->terrain->addReinforceZone(2318,'B');
            $this->terrain->addReinforceZone(2319,'B');
            $this->terrain->addReinforceZone(2320,'B');
            $this->terrain->addReinforceZone(2321,'B');
            $this->terrain->addReinforceZone(2322,'B');
            $this->terrain->addReinforceZone(2323,'B');
            $this->terrain->addReinforceZone(2324,'B');
            $this->terrain->addReinforceZone(2424,'B');
            $this->terrain->addReinforceZone(2423,'B');
            $this->terrain->addReinforceZone(2422,'B');
            $this->terrain->addReinforceZone(2421,'B');
            $this->terrain->addReinforceZone(2420,'B');
            $this->terrain->addReinforceZone(2419,'B');
            $this->terrain->addReinforceZone(2418,'B');
            $this->terrain->addReinforceZone(2416,'B');
            $this->terrain->addReinforceZone(2415,'B');
            $this->terrain->addReinforceZone(2414,'B');
            $this->terrain->addReinforceZone(2413,'B');
            $this->terrain->addReinforceZone(2412,'B');
            $this->terrain->addReinforceZone(2411,'B');
            $this->terrain->addReinforceZone(2410,'B');
            $this->terrain->addReinforceZone(2409,'B');
            $this->terrain->addReinforceZone(2408,'B');
            $this->terrain->addReinforceZone(2407,'B');
            $this->terrain->addReinforceZone(2406,'B');
            $this->terrain->addReinforceZone(2405,'B');
            $this->terrain->addReinforceZone(2404,'B');
            $this->terrain->addReinforceZone(2403,'B');
            $this->terrain->addReinforceZone(2402,'B');
            $this->terrain->addReinforceZone(2401,'B');
            $this->terrain->addTerrain(611 ,4 , "road");
            $this->terrain->addTerrain(712 ,4 , "road");
            $this->terrain->addTerrain(712 ,2 , "road");
            $this->terrain->addTerrain(813 ,4 , "road");
            $this->terrain->addTerrain(813 ,2 , "road");
            $this->terrain->addTerrain(915 ,4 , "road");
            $this->terrain->addTerrain(222 ,2 , "road");
            $this->terrain->addTerrain(1713 ,4 , "road");

            foreach($specialHexA as $specialHexId){
                $specialHexes[$specialHexId] = AUSTRIAN_FORCE;
            }
            foreach($specialHexB as $specialHexId){
                $specialHexes[$specialHexId] = PRUSSIAN_FORCE;
            }
            foreach($specialHexC as $specialHexId){
                $specialHexes[$specialHexId] = PRUSSIAN_FORCE;
            }
            $this->mapData->setSpecialHexes($specialHexes);
            $this->specialHexA = $specialHexA;
            $this->specialHexB = $specialHexB;
            $this->specialHexC = $specialHexC;


            // end terrain data ----------------------------------------

        }
    }
}