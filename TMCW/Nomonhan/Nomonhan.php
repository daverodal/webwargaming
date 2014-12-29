<?php
//set_include_path(__DIR__ . "/Nomonhan". PATH_SEPARATOR .  get_include_path());
define("JAPANESE_FORCE", 1);
define("SOVIET_FORCE", 2);
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Japanese";
$force_name[2] = "Soviet";

require_once "constants.php";
$phase_name[16] = "<span class='playerOneFace'>Japanese</span> surprise movement phase";

require_once "ModernLandBattle.php";
/* TODO: gag, we should NOT subclass MartianCivilWar */
$force_name[0] = "Neutral Observer";
$force_name[1] = "Japanese";
$force_name[2] = "Soviet";
require_once "nomonhanCrt.php";


/* TODO: do NOT subclass MCW */
class Nomonhan extends ModernLandBattle
{
    /* a comment */

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;
    public $display;
    public $victory;
    public $genTerrain = false;
    public $arg;
    public $scenario;
    public $game;

    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "nomonhanHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    function save()
    {
        $data = new stdClass();
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;
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
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }

    public function init(){
        // unit data -----------------------------------------------
        //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )

        // SOVIET Initial forces, can deploy on turn 1
        $this->force->addUnit("xx", SOVIET_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'inf');
        $this->force->addUnit("xx", SOVIET_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'inf');
        $this->force->addUnit("xx", SOVIET_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'inf');
        $this->force->addUnit("xx", SOVIET_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'inf');
        $this->force->addUnit("xx", SOVIET_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'cavalry');
        $this->force->addUnit("xx", SOVIET_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'cavalry');
        $this->force->addUnit("xx", SOVIET_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'cavalry');
        $this->force->addUnit("xx", SOVIET_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "soviet", true, 'cavalry');

        // Soviet Reinforcemenets, can deploy turn 6
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "inf");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "mech");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiCav.png", 3, 1, 8, false, STATUS_CAN_REINFORCE, "W", 6, 1, "soviet", true, "cavalry");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 12, "soviet", true, "artillery");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 12, "soviet", true, "artillery");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 12, "soviet", true, "artillery");
        $this->force->addUnit("xx", SOVIET_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "W", 6, 12, "soviet", true, "artillery");


        // Japanese Forces, all can enter on turn 1
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "mech");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "mech");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "mech");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "mech");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "inf");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "japanese", true, "cavalry");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiArt.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 10, "japanese", true, "artillery");
        $this->force->addUnit("xx", JAPANESE_FORCE, "deployBox", "multiArt.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 10, "japanese", true, "artillery");
        // end unit data -------------------------------------------


    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);

        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("marsh", "marsh", "m", 2, 0, 2, true);
        $this->terrain->addTerrainFeature("rough", "rough", "g", 2, 0, 2, true);
        $this->terrain->addTerrainFeature("hills", "hills", "h", 4, 0, 2, true);
        $this->terrain->addTerrainFeature("river", "river", "v", 2, 2, 1, false);
        $this->terrain->addTerrainFeature("ford", "ford", "v", 2, 1, 1, true);
        $this->terrain->addAltEntranceCost('marsh', 'artillery', 6);
        $this->terrain->addAltEntranceCost('marsh', 'mech', 6);


        for ($i = 1; $i <= 1; $i++) {
            for ($j = 1; $j <= 37; $j++) {
                if ($j == 10) {
                    continue;
                }
                $this->terrain->addReinforceZone($j * 100 + $i, "J");

            }
        }
        for ($i = 4; $i <= 25; $i++) {
            for ($j = 1; $j <= 40; $j++) {
                if ($i == 4 && ($j == 34 || $j == 35)) {
                    continue;
                }
                if ($i == 2 && ($j == 36 || $j == 37)) {
                    continue;
                }
                $this->terrain->addReinforceZone($j * 100 + $i, "R");
            }
        }
        $this->terrain->addReinforceZone(3603, "R");

        for ($i = 25; $i <= 25; $i++) {
            for ($j = 1; $j <= 40; $j++) {

                $this->terrain->addReinforceZone($j * 100 + $i, "W");

            }
        }

    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->genTerrain = false;
            $this->victory = new Victory("TMCW/Nomonhan/nomonhanVictoryCore.php", $data);
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
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->genTerrain = true;
            $this->victory = new Victory("TMCW/Nomonhan/nomonhanVictoryCore.php");
            $this->display = new Display();
            $this->mapData->setData(40, 25, "js/Nomonhan3Small.png");

            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->terrain->setMaxHex("4025");
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            if ($scenario && $scenario->supply === true) {
                $this->moveRules->enterZoc = 2;
                $this->moveRules->exitZoc = 1;
                $this->moveRules->noZocZocOneHex = true;
            } else {
                $this->moveRules->enterZoc = "stop";
                $this->moveRules->exitZoc = 0;
                $this->moveRules->noZocZocOneHex = false;
            }
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = array("", "", "");

            /* Observer, BLUE_FORCE, RED_FORCE */
            for($i = 0; $i < 3;$i++){
                $this->mapViewer[$i]->setData(54, 79, // originX, originY
                    25.5, 25.5, // top hexagon height, bottom hexagon height
                    14.725, 29.45, // hexagon edge width, hexagon center width
                    4025, 4025 // max right hexagon, max bottom hexagon
                );
            }

            // game data
            $this->gameRules->setMaxTurn(20);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_SURPRISE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_SURPRISE_MOVE_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
            $this->terrain->addTerrainFeature("marsh", "marsh", "m", 2, 0, 2, true);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 2, 0, 2, true);
            $this->terrain->addTerrainFeature("hills", "hills", "h", 4, 0, 2, true);
            $this->terrain->addTerrainFeature("river", "river", "v", 2, 2, 1, false);
            $this->terrain->addTerrainFeature("ford", "ford", "v", 2, 1, 1, true);
            $this->terrain->addAltEntranceCost('marsh', 'artillery', 6);
            $this->terrain->addAltEntranceCost('marsh', 'mech', 6);


            for ($i = 1; $i <= 1; $i++) {
                for ($j = 1; $j <= 37; $j++) {
                    if ($j == 10) {
                        continue;
                    }
                    $this->terrain->addReinforceZone($j * 100 + $i, "J");

                }
            }
            for ($i = 4; $i <= 25; $i++) {
                for ($j = 1; $j <= 40; $j++) {
                    if ($i == 4 && ($j == 34 || $j == 35)) {
                        continue;
                    }
                    if ($i == 2 && ($j == 36 || $j == 37)) {
                        continue;
                    }
                    $this->terrain->addReinforceZone($j * 100 + $i, "R");
                }
            }
            $this->terrain->addReinforceZone(3603, "R");

            for ($i = 25; $i <= 25; $i++) {
                for ($j = 1; $j <= 40; $j++) {

                    $this->terrain->addReinforceZone($j * 100 + $i, "W");

                }
            }

            /*
             * First put clear everywhere, hexes and hex sides
             */
            for ($col = 100; $col <= 4000; $col += 100) {
                for ($row = 1; $row <= 25; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
            /*
             * Next put terrain like rough and forest because they are exclusive and will cancel what else is there.
             */
            $hexes = array(2502, 2602, 2702, 2703, 2802, 1114, 1213, 1214, 1314, 1413, 1414, 1514, 1515, 1613, 1614, 1713,
                1714, 1715, 1813, 1814, 1914, 1915, 2013, 2014, 2211, 2213, 2314);
            foreach ($hexes as $hex) {
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "rough");
            }

            $hexes = array(1602, 1703, 1803, 1903, 2003, 2103, 2203, 820, 918, 919, 920, 1017, 2015, 3505, 3506, 3406, 3310, 3311, 3210, 3211, 2514, 2613, 2614,
                2714, 2813, 2913, 2914, 3012, 3013, 3111, 3112, 3113, 3210, 3211, 3310, 3311, 3409, 3410);
            foreach ($hexes as $hex) {
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "marsh");
            }

            $hexes = array(1001, 712, 1109, 1407, 1508, 1322, 2021, 2417, 2617, 3812, 3912);
            foreach ($hexes as $hex) {
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "blocked");
            }

            $hexes = array(1403, 3002, 3303, 1011, 1111, 1112, 1211, 1409, 1809, 1909, 1910, 2112, 2512, 2513, 2612,
                2207, 2305, 2407, 2707, 3108, 3109, 3214, 3215, 3315, 1318, 1518, 1417, 1418);
            foreach ($hexes as $hex) {
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "hills");
            }

            $this->terrain->addTerrain(1501, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1501, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(1601, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1601, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(1702, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1702, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(1802, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1802, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(1903, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1903, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(2003, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2003, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(2103, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(2104, UPPER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2203, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2203, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(2304, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2305, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2305, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2306, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2306, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2306, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(2406, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2407, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2407, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2408, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2408, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2409, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2409, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2409, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(2510, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2511, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2511, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2512, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2512, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2513, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(2513, LOWER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2514, UPPER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(2413, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(2414, UPPER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(121, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(121, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(221, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(221, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(322, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(322, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(421, BOTTOM_HEXSIDE, "blocked");
            $this->terrain->addTerrain(422, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(521, BOTTOM_HEXSIDE, "blocked");
            $this->terrain->addTerrain(522, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(621, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(621, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(721, BOTTOM_HEXSIDE, "blocked");
            $this->terrain->addTerrain(722, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(820, BOTTOM_HEXSIDE, "blocked");
            $this->terrain->addTerrain(821, UPPER_LEFT_HEXSIDE, "ford");

            $this->terrain->addTerrain(918, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(919, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(919, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(920, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(920, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(921, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1014, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1015, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1015, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1016, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1016, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1016, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1017, UPPER_LEFT_HEXSIDE, "ford");
            $this->terrain->addTerrain(1017, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1018, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1114, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1115, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1116, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1117, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1214, BOTTOM_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1214, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1215, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1216, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1314, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1315, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1316, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1316, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1414, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1414, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1415, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1416, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1515, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1515, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1516, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1516, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1614, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1615, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1615, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1616, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1715, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1715, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1716, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1716, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1814, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1815, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1815, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1816, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1915, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(1915, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(1916, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2015, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(2015, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2115, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2116, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2214, BOTTOM_HEXSIDE, "ford");
            $this->terrain->addTerrain(2215, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2314, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2315, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2414, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(2414, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2514, BOTTOM_HEXSIDE, "blocked");
            $this->terrain->addTerrain(2515, UPPER_LEFT_HEXSIDE, "ford");

            $this->terrain->addTerrain(2613, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2614, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2714, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(2714, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2813, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2814, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(2914, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(2914, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3013, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3014, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3113, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3114, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3210, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3211, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3211, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3212, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3212, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3213, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3310, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3311, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3403, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3404, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3404, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3404, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3405, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3405, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3406, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3406, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3407, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3407, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3407, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3408, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3408, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3408, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3409, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3409, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3410, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3503, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3504, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3504, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3505, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3601, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3602, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3602, LOWER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3602, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3603, UPPER_LEFT_HEXSIDE, "ford");
            $this->terrain->addTerrain(3603, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3604, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3701, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3702, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3702, BOTTOM_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3703, UPPER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3801, UPPER_LEFT_HEXSIDE, "blocked");
            $this->terrain->addTerrain(3801, LOWER_LEFT_HEXSIDE, "blocked");

            $this->terrain->addTerrain(3802, UPPER_LEFT_HEXSIDE, "blocked");

            // roads

            $this->terrain->addTerrain(104, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(203, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(203, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(303, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(303, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(402, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(402, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(502, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(502, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(601, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(601, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(701, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(701, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(602, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(602, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(702, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(702, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(802, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(802, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(902, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(902, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1002, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1002, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1102, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1102, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1202, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1202, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1302, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1302, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1402, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1402, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1502, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1502, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1502, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(1601, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1601, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1702, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1702, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1802, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1802, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1902, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1902, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(2002, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2002, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2102, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2102, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(2202, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2202, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2303, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2303, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2403, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2403, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2503, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2503, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(2603, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2603, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2704, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2704, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2803, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2803, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(2903, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2903, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(3003, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3003, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3103, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(3103, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(3202, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(3202, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(3302, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(3302, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(3401, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(3401, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(3501, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(3501, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1503, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1603, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1603, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1704, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1704, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1804, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1804, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1905, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1905, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2005, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2005, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2106, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2106, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2206, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2206, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2307, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2307, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2307, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(2308, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2308, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(2309, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2309, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(2310, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2310, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(2311, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2311, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(2312, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2312, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(2312, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(2314, HEXAGON_CENTER, "road");


            $this->terrain->addTerrain(111, HEXAGON_CENTER, "road");


            $this->terrain->addTerrain(211, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(211, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(312, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(312, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(412, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(412, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(412, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(512, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(512, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(612, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(612, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(713, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(713, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(812, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(812, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(912, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(912, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1012, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1012, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1113, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1113, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1212, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1212, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1312, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1312, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1411, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1411, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1512, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1512, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1611, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1611, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1712, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1712, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1812, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1812, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1912, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1912, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(2012, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2012, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2113, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2113, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2212, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2212, LOWER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(413, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(413, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(414, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(515, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(515, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(615, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(615, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(716, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(716, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(816, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(816, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(917, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(917, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1017, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1118, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1118, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1118, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(1119, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1119, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(1120, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1120, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(1121, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1121, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(1122, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1122, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(1123, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1123, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(1124, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1124, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(225, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(325, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(325, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(424, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(424, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(525, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(525, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(624, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(624, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(725, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(725, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(825, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(825, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(925, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(925, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1024, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1024, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1224, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1224, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1324, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1324, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1423, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1423, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1524, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1524, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1623, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1623, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1723, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1723, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1822, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1822, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1923, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1923, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2022, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2022, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2122, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2122, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2221, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2221, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2321, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2321, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2420, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2420, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2520, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2520, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2619, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2619, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2719, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2719, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2818, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2818, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2918, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(2918, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3017, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3017, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3117, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3117, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3217, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3217, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3317, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3317, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3416, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3416, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3517, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3517, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3617, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3617, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3717, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3717, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3816, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3816, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(3916, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(3916, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(4015, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(4015, HEXAGON_CENTER, "road");








            $this->terrain->addTerrain(1616, UPPER_LEFT_HEXSIDE, "road");

            $this->terrain->addTerrain(1715, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1715, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(0, HEXAGON_CENTER, "road");


            // end terrain data ----------------------------------------
        }
        $this->combatRules->crt = new NomonhanCombatResultsTable();
    }
}
