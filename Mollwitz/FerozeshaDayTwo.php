<?php
set_include_path(__DIR__ . "/Ferozesha" . PATH_SEPARATOR . get_include_path());
require_once "JagCore.php";
/* comment */
define("SIKH_FORCE", 1);
define("BRITISH_FORCE", 2);
require_once "Ferozesha.php";

$force_name[SIKH_FORCE] = "Sikh";
$force_name[BRITISH_FORCE] = "British";
$phase_name = array();
$phase_name[1] = "<span class='playerTwoFace'>British</span> Move";
$phase_name[2] = "<span class='playerTwoFace'>British</span> Combat";
$phase_name[3] = "";
$phase_name[4] = "<span class='playerOneFace'>Sikh</span> Move";
$phase_name[5] = "<span class='playerOneFace'>Sikh</span> Combat";
$phase_name[6] = "";
$phase_name[7] = "Victory";
$phase_name[8] = "<span class='playerTwoFace'>British</span> Deploy";
$phase_name[9] = "";
$phase_name[10] = "";
$phase_name[11] = "";
$phase_name[12] = "";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "<span class='playerOneFace'>Sikh</span> deploy phase";

//var_dump(SIKH_FORCE);
//var_dump(BRITISH_FORCE);
//var_dump($force_name);die();
// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class FerozeshaDayTwo extends Ferozesha
{

    public function init()
    {

        $artRange = 3;


        if(!$this->scenario->dayTwo){
            /* Sikh */
            for ($i = 0; $i < 21; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Sikh", false, 'infantry');
            }
            for ($i = 0; $i < 10; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Sikh", false, 'cavalry');
            }
            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 3, "Sikh", false, 'artillery');
            }
            for ($i = 0; $i < 2; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 3, "Sikh", false, 'artillery');
            }

            /* British */
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritInfBadge.png", 7, 7, 4, true, STATUS_CAN_DEPLOY, "A", 1, 1, "British", false, 'infantry');
            }
            for ($i = 0; $i < 15; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeInfBadge.png", 6, 6, 4, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Native", false, 'infantry');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritCavBadge.png", 7, 7, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "British", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeCavBadge.png", 6, 6, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Native", false, 'cavalry');
            }
             for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritArtBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 4, "British", false, 'artillery');
            }
            for ($i = 0; $i < 2; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritHorArtBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 3, "British", false, 'horseartillery');
            }
        }else{
            /* Sikh */
            for ($i = 0; $i < 16; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Sikh", false, 'infantry');
            }
            for ($i = 0; $i < 9; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Sikh", false, 'cavalry');
            }
            for ($i = 0; $i < 3; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 2, "Sikh", false, 'artillery');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", SIKH_FORCE, "deployBox", "SikhArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 3, "Sikh", false, 'artillery');
            }


            /* British */
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritInfBadge.png", 5, 5, 4, true, STATUS_CAN_DEPLOY, "A", 1, 1, "British", false, 'infantry');
            }
            for ($i = 0; $i < 15; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeInfBadge.png", 4, 4, 4, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Native", false, 'infantry');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritCavBadge.png", 5, 5, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "British", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "NativeCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Native", false, 'cavalry');
            }
            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 4, "British", false, 'artillery');
            }
            for ($i = 0; $i < 2; $i++) {
                $this->force->addUnit("infantry-1", BRITISH_FORCE, "deployBox", "BritHorArtBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 3, "British", false, 'horseartillery');
            }
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