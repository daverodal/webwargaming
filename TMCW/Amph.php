<?php
set_include_path(__DIR__ . "/Amph" . PATH_SEPARATOR . get_include_path());

require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

$phase_name = array();
$phase_name[1] = "<span class='rebelFace'>Rebel</span> Movement Phase";
$phase_name[2] = "<span class='rebelFace'>Rebel</span>";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "<span class='loyalistFace'>Loyalist</span> Movement Phase";
$phase_name[5] = "<span class='loyalistFace'>Loyalist</span>";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "<span class='rebelFace'>Rebel</span> Deploy Phase";
$phase_name[9] = "<span class='rebelFace'>Rebel</span> Mech Movement Phase";
$phase_name[10] = "<span class='rebelFace'>Rebel</span> Replacement Phase";
$phase_name[11] = "<span class='loyalistFace'>Loyalist</span> Mech Movement Phase";
$phase_name[12] = "<span class='loyalistFace'>Loyalist</span> Replacement Phase";
$phase_name[13] = "";
$phase_name[14] = "";

$mode_name[3] = "Combat Setup Phase";
$mode_name[4] = "Combat Resolution Phase";
$mode_name[19] = "";

$mode_name[1] = "";
$mode_name[2] = "";

define("REBEL_FORCE", BLUE_FORCE);
define("LOYALIST_FORCE", RED_FORCE);

require_once "crtTraits.php";
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
require_once "terrain.php";
require_once "display.php";
require_once "victory.php";

// battlefforallencreek.js

// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class Amph extends Battle
{
    /* a comment */

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    public $playerData;
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

    public $players;

    static function getHeader($name, $playerData)
    {
        global $force_name;

        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "commonHeader.php";
        @include_once "header.php";
        @include_once "amphHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    static function playAs($name, $wargame)
    {

        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame)
    {
        @include_once "playMulti.php";
    }


    function save()
    {
        $data = new stdClass();
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
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
        $data->terrainName = "terrain-" . get_class($this);
        $data->genTerrain = $this->genTerrain;
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
                $mapGrid->setPixels($x, $y);
                return $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(), $click);
                break;

            case SELECT_COUNTER_EVENT:
                /* fall through */
            case SELECT_SHIFT_COUNTER_EVENT:
            /* fall through */
            case COMBAT_PIN_EVENT:

            return $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id), $click);

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

    public function init()
    {

        $scenario = $this->scenario;
        if(!$scenario->hardCuneiform){
            $this->oldInit();
            return;
        }

        $this->force->addUnit("lll", LOYALIST_FORCE, 305, "multiGor.png", 3, 1, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 803, "multiGor.png", 3, 1, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 907, "multiPara.png", 8, 4, 5, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1205, "multiGor.png", 3, 1, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1405, "multiGor.png", 3, 1, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');

        $this->force->addUnit("lll", LOYALIST_FORCE, 1705, "multiGor.png", 3, 1, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1904, "multiGor.png", 3, 1, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1809, "multiGor.png", 3, 1, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');

        $this->force->addUnit("x", RED_FORCE, "gameTurn1", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 1, 1, "loyalist", true, 'shock');
        $this->force->addUnit("x", RED_FORCE, "gameTurn2", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'shock');
        $this->force->addUnit("x", RED_FORCE, "gameTurn3", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist", true, 'shock');
        $this->force->addUnit("x", RED_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist", true, 'shock');
        $this->force->addUnit("x", RED_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist", true, 'shock');

        $this->force->addUnit("x", RED_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, 'mech');
        $this->force->addUnit("x", RED_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, 'mech');
        $this->force->addUnit("x", RED_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("x", RED_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, 'heavy');


        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");

//
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMountain.png", 3, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "mountain");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMountain.png", 3, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "mountain");
//
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");
//        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "sympth", true, "inf");


    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->genTerrain = false;
            $this->victory = new Victory("TMCW/Amph/amphVictoryCore.php", $data);
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
            $this->genTerrain = true;
            $this->victory = new Victory("TMCW/Amph/amphVictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->display = new Display();
            $this->mapData->setData(20, 10, "js/amph2Small.png");

            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
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
            $this->playerData = new stdClass();
            for ($player = 0; $player <= 2; $player++) {
                $this->playerData->${player} = new stdClass();
                $this->playerData->${player}->mapWidth = "auto";
                $this->playerData->${player}->mapHeight = "auto";
                $this->playerData->${player}->unitSize = "32px";
                $this->playerData->${player}->unitFontSize = "12px";
                $this->playerData->${player}->unitMargin = "-21px";
            }


            // mapData
            /*            $this->mapData->setData(88,117, // originX, originY
                            40, 40, // top hexagon height, bottom hexagon height
                            24, 48, // hexagon edge width, hexagon center width
                            1410, 1410 // max right hexagon, max bottom hexagon
                        );*/
//            $this->mapData->setData(66,87, // originX, originY
//                30, 30, // top hexagon height, bottom hexagon height
//                18, 36, // hexagon edge width, hexagon center width
//                1410, 1410 // max right hexagon, max bottom hexagon
//            );

            for($player = 0;$player < 3;$player++){
                $this->mapViewer[$player]->setData(64 , 82.5, // originX, originY
                    27.5, 27.5, // top hexagon height, bottom hexagon height
                    16, 32// hexagon edge width, hexagon center width
                );
            }
            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data
            //$this->force->setEliminationTrayXY(900);

            // unit data -----------------------------------------------
            //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )
            // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", 1., 0, 0, false);
            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
            $this->terrain->addTerrainFeature("roughone", "roughone", "g", 3, 0, 2, true);
            $this->terrain->addTerrainFeature("swamp", "swamp", "f", 3, 0, 1, true);
            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
            /* handle fort's in crtTraits */

            /*
             * First put clear everywhere, hexes and hex sides
             */
            for ($col = 100; $col <= 2000; $col += 100) {
                for ($row = 1; $row <= 10; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }


            /*
             * Next put terrain like rough and forest because they are exclusive and will cancel what else is there.
             */
            $this->terrain->addTerrain(1504 ,1 , "swamp");
            $this->terrain->addTerrain(1504 ,1 , "road");
            $this->terrain->addTerrain(1604 ,1 , "swamp");
            $this->terrain->addTerrain(1604 ,1 , "road");
            $this->terrain->addTerrain(1704 ,1 , "swamp");
            $this->terrain->addTerrain(109 ,1 , "swamp");
            $this->terrain->addTerrain(209 ,1 , "swamp");
            $this->terrain->addTerrain(210 ,1 , "swamp");
            $this->terrain->addTerrain(103 ,1 , "forest");
            $this->terrain->addTerrain(104 ,1 , "forest");
            $this->terrain->addTerrain(203 ,1 , "forest");
            $this->terrain->addTerrain(204 ,1 , "forest");
            $this->terrain->addTerrain(304 ,1 , "forest");
            $this->terrain->addTerrain(404 ,1 , "forest");
            $this->terrain->addTerrain(403 ,1 , "forest");
            $this->terrain->addTerrain(504 ,1 , "forest");
            $this->terrain->addTerrain(506 ,1 , "forest");
            $this->terrain->addTerrain(605 ,1 , "forest");
            $this->terrain->addTerrain(705 ,1 , "forest");
            $this->terrain->addTerrain(805 ,1 , "forest");
            $this->terrain->addTerrain(606 ,1 , "forest");
            $this->terrain->addTerrain(707 ,1 , "forest");
            $this->terrain->addTerrain(807 ,1 , "forest");
            $this->terrain->addTerrain(708 ,1 , "forest");
            $this->terrain->addTerrain(808 ,1 , "forest");
            $this->terrain->addTerrain(909 ,1 , "forest");
            $this->terrain->addTerrain(909 ,1 , "road");
            $this->terrain->addTerrain(1008 ,1 , "forest");
            $this->terrain->addTerrain(1009 ,1 , "forest");
            $this->terrain->addTerrain(910 ,1 , "forest");
            $this->terrain->addTerrain(910 ,1 , "road");
            $this->terrain->addReinforceZone(910,'B');
            $this->terrain->addTerrain(1010 ,1 , "forest");
            $this->terrain->addTerrain(1010 ,1 , "road");
            $this->terrain->addReinforceZone(1010,'B');
            $this->terrain->addTerrain(1110 ,1 , "forest");
            $this->terrain->addReinforceZone(1110,'B');
            $this->terrain->addTerrain(1109 ,1 , "forest");
            $this->terrain->addTerrain(1207 ,1 , "forest");
            $this->terrain->addTerrain(1307 ,1 , "forest");
            $this->terrain->addTerrain(1308 ,1 , "forest");
            $this->terrain->addTerrain(1309 ,1 , "forest");
            $this->terrain->addTerrain(1310 ,1 , "forest");
            $this->terrain->addTerrain(1410 ,1 , "forest");
            $this->terrain->addTerrain(1409 ,1 , "forest");
            $this->terrain->addTerrain(1408 ,1 , "forest");
            $this->terrain->addTerrain(1407 ,1 , "forest");
            $this->terrain->addTerrain(1509 ,1 , "forest");
            $this->terrain->addTerrain(1510 ,1 , "forest");
            $this->terrain->addTerrain(1610 ,1 , "forest");
            $this->terrain->addReinforceZone(1610,'B');
            $this->terrain->addTerrain(1609 ,1 , "forest");
            $this->terrain->addTerrain(706 ,1 , "roughone");
            $this->terrain->addTerrain(806 ,1 , "roughone");
            $this->terrain->addTerrain(1006 ,1 , "roughone");
            $this->terrain->addTerrain(1007 ,1 , "roughone");
            $this->terrain->addTerrain(1203 ,4 , "river");
            $this->terrain->addTerrain(1203 ,4 , "road");
            $this->terrain->addTerrain(1203 ,3 , "river");
            $this->terrain->addTerrain(1204 ,4 , "river");
            $this->terrain->addTerrain(1204 ,3 , "river");
            $this->terrain->addTerrain(1205 ,4 , "river");
            $this->terrain->addTerrain(1205 ,3 , "river");
            $this->terrain->addTerrain(1206 ,4 , "river");
            $this->terrain->addTerrain(1206 ,3 , "river");
            $this->terrain->addTerrain(1207 ,4 , "river");
            $this->terrain->addTerrain(1207 ,3 , "river");
            $this->terrain->addTerrain(1207 ,2 , "river");
            $this->terrain->addTerrain(1308 ,3 , "river");
            $this->terrain->addTerrain(1309 ,4 , "river");
            $this->terrain->addTerrain(1309 ,3 , "river");
            $this->terrain->addTerrain(1310 ,4 , "river");
            $this->terrain->addTerrain(1310 ,3 , "river");
            $this->terrain->addTerrain(101 ,1 , "road");
            $this->terrain->addReinforceZone(101,'A');
            $this->terrain->addTerrain(101 ,2 , "road");
            $this->terrain->addTerrain(102 ,1 , "road");
            $this->terrain->addReinforceZone(102,'A');
            $this->terrain->addTerrain(202 ,4 , "road");
            $this->terrain->addTerrain(202 ,1 , "road");
            $this->terrain->addReinforceZone(202,'A');
            $this->terrain->addTerrain(303 ,4 , "road");
            $this->terrain->addTerrain(303 ,1 , "road");
            $this->terrain->addReinforceZone(303,'A');
            $this->terrain->addTerrain(402 ,3 , "road");
            $this->terrain->addTerrain(402 ,1 , "road");
            $this->terrain->addReinforceZone(402,'A');
            $this->terrain->addTerrain(503 ,4 , "road");
            $this->terrain->addTerrain(503 ,1 , "road");
            $this->terrain->addReinforceZone(503,'A');
            $this->terrain->addTerrain(603 ,4 , "road");
            $this->terrain->addTerrain(603 ,1 , "road");
            $this->terrain->addTerrain(704 ,4 , "road");
            $this->terrain->addTerrain(704 ,1 , "road");
            $this->terrain->addTerrain(803 ,3 , "road");
            $this->terrain->addTerrain(803 ,1 , "road");
            $this->terrain->addTerrain(904 ,4 , "road");
            $this->terrain->addTerrain(904 ,1 , "road");
            $this->terrain->addTerrain(904 ,2 , "road");
            $this->terrain->addTerrain(905 ,1 , "road");
            $this->terrain->addTerrain(905 ,2 , "road");
            $this->terrain->addTerrain(906 ,1 , "road");
            $this->terrain->addTerrain(906 ,2 , "road");
            $this->terrain->addTerrain(907 ,1 , "road");
            $this->terrain->addTerrain(907 ,2 , "road");
            $this->terrain->addTerrain(908 ,1 , "road");
            $this->terrain->addTerrain(908 ,2 , "road");
            $this->terrain->addTerrain(909 ,2 , "road");
            $this->terrain->addTerrain(1010 ,4 , "road");
            $this->terrain->addTerrain(1111 ,4 , "road");
            $this->terrain->addTerrain(903 ,3 , "road");
            $this->terrain->addTerrain(903 ,1 , "road");
            $this->terrain->addReinforceZone(903,'A');
            $this->terrain->addTerrain(1003 ,4 , "road");
            $this->terrain->addTerrain(1003 ,1 , "road");
            $this->terrain->addReinforceZone(1003,'A');
            $this->terrain->addTerrain(1103 ,3 , "road");
            $this->terrain->addTerrain(1103 ,1 , "road");
            $this->terrain->addReinforceZone(1103,'A');
            $this->terrain->addTerrain(1203 ,1 , "road");
            $this->terrain->addReinforceZone(1203,'A');
            $this->terrain->addTerrain(1303 ,3 , "road");
            $this->terrain->addTerrain(1303 ,1 , "road");
            $this->terrain->addReinforceZone(1303,'A');
            $this->terrain->addTerrain(1403 ,4 , "road");
            $this->terrain->addTerrain(1403 ,1 , "road");
            $this->terrain->addReinforceZone(1403,'A');
            $this->terrain->addTerrain(1504 ,4 , "road");
            $this->terrain->addTerrain(1604 ,4 , "road");
            $this->terrain->addTerrain(1705 ,4 , "road");
            $this->terrain->addTerrain(1705 ,1 , "road");
            $this->terrain->addTerrain(1805 ,4 , "road");
            $this->terrain->addTerrain(1805 ,1 , "road");
            $this->terrain->addTerrain(1805 ,2 , "road");
            $this->terrain->addTerrain(1806 ,1 , "road");
            $this->terrain->addTerrain(1806 ,2 , "road");
            $this->terrain->addTerrain(1807 ,1 , "road");
            $this->terrain->addTerrain(1807 ,2 , "road");
            $this->terrain->addTerrain(1808 ,1 , "road");
            $this->terrain->addTerrain(1808 ,2 , "road");
            $this->terrain->addTerrain(1809 ,1 , "road");
            $this->terrain->addTerrain(1809 ,2 , "road");
            $this->terrain->addTerrain(1810 ,1 , "road");
            $this->terrain->addReinforceZone(1810,'B');
            $this->terrain->addTerrain(1810 ,2 , "road");
            $this->terrain->addTerrain(1905 ,3 , "road");
            $this->terrain->addTerrain(1905 ,1 , "road");
            $this->terrain->addTerrain(2004 ,3 , "road");
            $this->terrain->addTerrain(2004 ,1 , "road");
            $this->terrain->addTerrain(2003 ,2 , "road");
            $this->terrain->addTerrain(2003 ,1 , "road");
            $this->terrain->addReinforceZone(2003,'A');
            $this->terrain->addTerrain(2104 ,4 , "road");
            $this->terrain->addTerrain(2003 ,4 , "road");
            $this->terrain->addTerrain(1903 ,1 , "road");
            $this->terrain->addReinforceZone(1903,'A');
            $this->terrain->addTerrain(1903 ,3 , "road");
            $this->terrain->addTerrain(1803 ,1 , "road");
            $this->terrain->addReinforceZone(1803,'A');
            $this->terrain->addTerrain(1803 ,4 , "road");
            $this->terrain->addTerrain(1703 ,1 , "road");
            $this->terrain->addReinforceZone(1703,'A');
            $this->terrain->addTerrain(1703 ,3 , "road");
            $this->terrain->addTerrain(1603 ,1 , "road");
            $this->terrain->addReinforceZone(1603,'A');
            $this->terrain->addTerrain(1603 ,4 , "road");
            $this->terrain->addTerrain(1503 ,1 , "road");
            $this->terrain->addReinforceZone(1503,'A');
            $this->terrain->addTerrain(1503 ,3 , "road");
            $this->terrain->addTerrain(201 ,1 , "blocked");
            $this->terrain->addTerrain(301 ,1 , "blocked");
            $this->terrain->addTerrain(302 ,1 , "blocked");
            $this->terrain->addTerrain(401 ,1 , "blocked");
            $this->terrain->addTerrain(502 ,1 , "blocked");
            $this->terrain->addTerrain(501 ,1 , "blocked");
            $this->terrain->addTerrain(601 ,1 , "blocked");
            $this->terrain->addTerrain(701 ,1 , "blocked");
            $this->terrain->addTerrain(702 ,1 , "blocked");
            $this->terrain->addTerrain(801 ,1 , "blocked");
            $this->terrain->addTerrain(901 ,1 , "blocked");
            $this->terrain->addTerrain(902 ,1 , "blocked");
            $this->terrain->addTerrain(1001 ,1 , "blocked");
            $this->terrain->addTerrain(1002 ,1 , "blocked");
            $this->terrain->addTerrain(1101 ,1 , "blocked");
            $this->terrain->addTerrain(1102 ,1 , "blocked");
            $this->terrain->addTerrain(1201 ,1 , "blocked");
            $this->terrain->addTerrain(1202 ,1 , "blocked");
            $this->terrain->addTerrain(1301 ,1 , "blocked");
            $this->terrain->addTerrain(1302 ,1 , "blocked");
            $this->terrain->addTerrain(1401 ,1 , "blocked");
            $this->terrain->addTerrain(1402 ,1 , "blocked");
            $this->terrain->addTerrain(1501 ,1 , "blocked");
            $this->terrain->addTerrain(1502 ,1 , "blocked");
            $this->terrain->addTerrain(1601 ,1 , "blocked");
            $this->terrain->addTerrain(1602 ,1 , "blocked");
            $this->terrain->addTerrain(1701 ,1 , "blocked");
            $this->terrain->addTerrain(1702 ,1 , "blocked");
            $this->terrain->addTerrain(1801 ,1 , "blocked");
            $this->terrain->addTerrain(1802 ,1 , "blocked");
            $this->terrain->addTerrain(1901 ,1 , "blocked");
            $this->terrain->addTerrain(1902 ,1 , "blocked");
            $this->terrain->addTerrain(2001 ,1 , "blocked");
            $this->terrain->addTerrain(2002 ,1 , "blocked");
            $this->terrain->addReinforceZone(602,'A');
            $this->terrain->addReinforceZone(703,'A');
            $this->terrain->addReinforceZone(802,'A');
            $this->terrain->addReinforceZone(810,'B');
            $this->terrain->addReinforceZone(1210,'B');
            $this->terrain->addReinforceZone(1710,'B');
            $this->terrain->addReinforceZone(1910,'B');
            $this->terrain->addReinforceZone(2010,'B');

            // end terrain data ----------------------------------------

        }
    }
}