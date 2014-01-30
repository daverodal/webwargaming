<?php
set_include_path(__DIR__ . "/Manchuria1976". PATH_SEPARATOR .  get_include_path());

require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Soviet";
$force_name[2] = "Chinese";

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

define("SOVIET_FORCE",BLUE_FORCE);
define("PRC_FORCE",RED_FORCE);

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


class Manchuria1976 extends Battle
{
    /* a comment */

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    public $playerData;
    /* @var Force */
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
        @include_once "Manchuria1976Header.php";
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

    static function playMulti($name, $wargame){
        @include_once "playMulti.php";
    }

    public function resize($small, $player)
    {
        if ($small) {
            $this->mapViewer[$player]->setData(60, 76, // originX, originY
                25, 25, // top hexagon height, bottom hexagon height
                15, 30 // hexagon edge width, hexagon center width
            );
            $this->playerData->${player}->mapWidth = "auto";
            $this->playerData->${player}->mapHeight = "auto";
            $this->playerData->${player}->unitSize = "32px";
            $this->playerData->${player}->unitFontSize = "12px";
            $this->playerData->${player}->unitMargin = "-21px";
        } else {
            $this->mapViewer[$player]->setData(60, 76, // originX, originY
                25, 25, // top hexagon height, bottom hexagon height
                15, 30 // hexagon edge width, hexagon center width
            );
            $this->playerData->${player}->mapWidth = "auto";
            $this->playerData->${player}->mapHeight = "auto";
            $this->playerData->${player}->unitSize = "40px";
            $this->playerData->${player}->unitFontSize = "16px";
            $this->playerData->${player}->unitMargin = "-23px";
        }
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
        $data->terrainName = "terrain-".get_class($this);
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
                return $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id), $click);

                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0, $click);

        }
        return true;
    }

    public function init(){

        $scenario = $this->scenario;

        for($i = 0; $i < 15; $i++){
            $this->force->addUnit("infantry-1", PRC_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 1, "prc", true, "inf");
        }
        $this->force->addUnit("infantry-1", PRC_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "prc", true, "mech");




        for($i = 0;$i < 5;$i++){
            $this->force->addUnit("infantry-1", SOVIET_FORCE, "deployBox", "multiArmor.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }
        for($i = 0;$i < 10;$i++){
            $this->force->addUnit("infantry-1", SOVIET_FORCE, "deployBox", "multiMech.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }
        for($i = 0;$i < 15;$i++){
            $this->force->addUnit("infantry-1", SOVIET_FORCE, "deployBox", "multiMotInf.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }

        for($i = 0;$i < 4;$i++){
            $this->force->addUnit("infantry-1", SOVIET_FORCE, "deployBox", "multiArt.png", 3, 1, 6, false, STATUS_CAN_DEPLOY, "B", 1, 2, "soviet", true, "mech");
        }
    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->genTerrain = false;
            $this->victory = new Victory("TMCW/Manchuria1976", $data);
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
            $this->victory = new Victory("TMCW/Manchuria1976");
            $this->display = new Display();
            $this->mapData->setData(39, 33, "js/Manchuria1976Small.png");

//            if ($scenario && $scenario->supply === true) {
//                $this->mapData->setSpecialHexes(array(407 => RED_FORCE, 1909 => RED_FORCE, 1515 => RED_FORCE, 516 => RED_FORCE,
//                    2414 => RED_FORCE, 2415 => RED_FORCE, 2515 => RED_FORCE, 1508 => RED_FORCE,
//                    2615 => RED_FORCE, 2615 => RED_FORCE, 2716 => RED_FORCE, 2816 => RED_FORCE,
//                    2917 => RED_FORCE, 3017 => RED_FORCE));
//
//            } else {
//                $this->mapData->setSpecialHexes(array(407 => RED_FORCE, 1909 => RED_FORCE, 1515 => RED_FORCE, 516 => RED_FORCE,
//                    2414 => RED_FORCE, 2415 => RED_FORCE, 2515 => RED_FORCE, 1508 => RED_FORCE, 2514 => RED_FORCE, 2614 => RED_FORCE,
//                    2615 => RED_FORCE, 2416 => RED_FORCE, 2516 => RED_FORCE, 2615 => RED_FORCE, 2716 => RED_FORCE, 2816 => RED_FORCE,
//                    2917 => RED_FORCE, 3017 => RED_FORCE));
//
//            }
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->terrain->setMaxHex("3020");
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

            for($i = 0; $i < 3;$i++){
                $this->mapViewer[$i]->setData(49.29999999999999 , 78.33199777230246, // originX, originY
                    26.11066592410082, 26.11066592410082, // top hexagon height, bottom hexagon height
                    15.075, 30.15// hexagon edge width, hexagon center width
                );
            }
            // game data
            $this->gameRules->setMaxTurn(12);


            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->attackingForceId); /* so object oriented */




            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data
            //$this->force->setEliminationTrayXY(900);

            // unit data -----------------------------------------------
            //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )
          // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 3, 0, 2, true);
            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
            $this->terrain->addTerrainFeature("newrichmond", "New Richmond", "m", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("westedge", "West Edge", "m", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("mountain", "mountain", "g", 2, 0, 2, true);
            $this->terrain->addAltEntranceCost('mountain', 'mech', 6);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);


            /*
             * First put clear everywhere, hexes and hex sides
             */
            for ($col = 100; $col <= 3900; $col += 100) {
                for ($row = 1; $row <= 33; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
            $specialHexA = [];

            $this->terrain->addTerrain(703 ,1 , "town");
            $specialHexA[] = 703;
            $this->terrain->addTerrain(609 ,1 , "town");
            $specialHexA[] = 609;
            $this->terrain->addTerrain(510 ,1 , "town");
            $specialHexA[] = 510;
            $this->terrain->addTerrain(610 ,1 , "town");
            $specialHexA[] = 610;
            $this->terrain->addTerrain(411 ,1 , "town");
            $specialHexA[] = 411;
            $this->terrain->addTerrain(1220 ,1 , "town");
            $specialHexA[] = 1220;
            $this->terrain->addTerrain(519 ,1 , "town");
            $specialHexA[] = 519;
            $this->terrain->addTerrain(1722 ,1 , "town");
            $specialHexA[] = 1722;
            $this->terrain->addTerrain(2223 ,1 , "town");
            $this->terrain->addReinforceZone(2223,'A');
            $specialHexA[] = 2223;
            $this->terrain->addTerrain(2812 ,1 , "town");
            $this->terrain->addReinforceZone(2812,'A');
            $specialHexA[] = 2812;
            $this->terrain->addTerrain(202 ,1 , "mountain");
            $this->terrain->addTerrain(302 ,1 , "mountain");
            $this->terrain->addTerrain(303 ,1 , "mountain");
            $this->terrain->addTerrain(305 ,1 , "mountain");
            $this->terrain->addTerrain(402 ,1 , "mountain");
            $this->terrain->addTerrain(306 ,1 , "mountain");
            $this->terrain->addTerrain(403 ,1 , "mountain");
            $this->terrain->addTerrain(404 ,1 , "mountain");
            $this->terrain->addTerrain(404 ,1 , "road");
            $this->terrain->addTerrain(405 ,1 , "mountain");
            $this->terrain->addTerrain(406 ,1 , "mountain");
            $this->terrain->addTerrain(503 ,1 , "mountain");
            $this->terrain->addTerrain(504 ,1 , "mountain");
            $this->terrain->addTerrain(505 ,1 , "mountain");
            $this->terrain->addTerrain(505 ,1 , "road");
            $this->terrain->addTerrain(506 ,1 , "mountain");
            $this->terrain->addTerrain(506 ,1 , "road");
            $this->terrain->addTerrain(507 ,1 , "mountain");
            $this->terrain->addTerrain(508 ,1 , "mountain");
            $this->terrain->addTerrain(606 ,1 , "mountain");
            $this->terrain->addTerrain(606 ,1 , "road");
            $this->terrain->addTerrain(607 ,1 , "mountain");
            $this->terrain->addTerrain(603 ,1 , "mountain");
            $this->terrain->addTerrain(707 ,1 , "mountain");
            $this->terrain->addTerrain(707 ,1 , "road");
            $this->terrain->addTerrain(708 ,1 , "mountain");
            $this->terrain->addTerrain(708 ,1 , "road");
            $this->terrain->addTerrain(807 ,1 , "mountain");
            $this->terrain->addTerrain(808 ,1 , "mountain");
            $this->terrain->addTerrain(809 ,1 , "mountain");
            $this->terrain->addTerrain(810 ,1 , "mountain");
            $this->terrain->addTerrain(909 ,1 , "mountain");
            $this->terrain->addTerrain(910 ,1 , "mountain");
            $this->terrain->addTerrain(911 ,1 , "mountain");
            $this->terrain->addTerrain(912 ,1 , "mountain");
            $this->terrain->addTerrain(812 ,1 , "mountain");
            $this->terrain->addTerrain(713 ,1 , "mountain");
            $this->terrain->addTerrain(714 ,1 , "mountain");
            $this->terrain->addTerrain(813 ,1 , "mountain");
            $this->terrain->addTerrain(814 ,1 , "mountain");
            $this->terrain->addTerrain(914 ,1 , "mountain");
            $this->terrain->addTerrain(913 ,1 , "mountain");
            $this->terrain->addTerrain(915 ,1 , "mountain");
            $this->terrain->addTerrain(916 ,1 , "mountain");
            $this->terrain->addTerrain(916 ,1 , "road");
            $this->terrain->addTerrain(917 ,1 , "mountain");
            $this->terrain->addTerrain(818 ,1 , "mountain");
            $this->terrain->addTerrain(718 ,1 , "mountain");
            $this->terrain->addTerrain(719 ,1 , "mountain");
            $this->terrain->addTerrain(719 ,1 , "road");
            $this->terrain->addTerrain(618 ,1 , "mountain");
            $this->terrain->addTerrain(618 ,1 , "road");
            $this->terrain->addTerrain(619 ,1 , "mountain");
            $this->terrain->addTerrain(518 ,1 , "mountain");
            $this->terrain->addTerrain(417 ,1 , "mountain");
            $this->terrain->addTerrain(720 ,1 , "mountain");
            $this->terrain->addTerrain(819 ,1 , "mountain");
            $this->terrain->addTerrain(819 ,1 , "road");
            $this->terrain->addTerrain(820 ,1 , "mountain");
            $this->terrain->addTerrain(919 ,1 , "mountain");
            $this->terrain->addTerrain(919 ,1 , "road");
            $this->terrain->addTerrain(921 ,1 , "mountain");
            $this->terrain->addTerrain(821 ,1 , "mountain");
            $this->terrain->addTerrain(922 ,1 , "mountain");
            $this->terrain->addTerrain(922 ,1 , "road");
            $this->terrain->addTerrain(1022 ,1 , "mountain");
            $this->terrain->addTerrain(1023 ,1 , "mountain");
            $this->terrain->addTerrain(1124 ,1 , "mountain");
            $this->terrain->addTerrain(1224 ,1 , "mountain");
            $this->terrain->addTerrain(1325 ,1 , "mountain");
            $this->terrain->addTerrain(1324 ,1 , "mountain");
            $this->terrain->addTerrain(1223 ,1 , "mountain");
            $this->terrain->addTerrain(1123 ,1 , "mountain");
            $this->terrain->addTerrain(1122 ,1 , "mountain");
            $this->terrain->addTerrain(1222 ,1 , "mountain");
            $this->terrain->addTerrain(1021 ,1 , "mountain");
            $this->terrain->addTerrain(1021 ,1 , "road");
            $this->terrain->addTerrain(1020 ,1 , "mountain");
            $this->terrain->addTerrain(1019 ,1 , "mountain");
            $this->terrain->addTerrain(1019 ,1 , "road");
            $this->terrain->addTerrain(920 ,1 , "mountain");
            $this->terrain->addTerrain(1018 ,1 , "mountain");
            $this->terrain->addTerrain(1119 ,1 , "mountain");
            $this->terrain->addTerrain(1120 ,1 , "mountain");
            $this->terrain->addTerrain(1120 ,1 , "road");
            $this->terrain->addTerrain(1017 ,1 , "mountain");
            $this->terrain->addTerrain(1017 ,1 , "road");
            $this->terrain->addTerrain(1118 ,1 , "mountain");
            $this->terrain->addTerrain(1118 ,1 , "road");
            $this->terrain->addTerrain(1218 ,1 , "mountain");
            $this->terrain->addTerrain(1218 ,1 , "road");
            $this->terrain->addTerrain(1217 ,1 , "mountain");
            $this->terrain->addTerrain(1117 ,1 , "mountain");
            $this->terrain->addTerrain(1016 ,1 , "mountain");
            $this->terrain->addTerrain(1016 ,1 , "road");
            $this->terrain->addTerrain(1015 ,1 , "mountain");
            $this->terrain->addTerrain(1013 ,1 , "mountain");
            $this->terrain->addTerrain(1012 ,1 , "mountain");
            $this->terrain->addTerrain(1011 ,1 , "mountain");
            $this->terrain->addTerrain(1014 ,1 , "mountain");
            $this->terrain->addTerrain(1010 ,1 , "mountain");
            $this->terrain->addTerrain(1009 ,1 , "mountain");
            $this->terrain->addTerrain(1008 ,1 , "mountain");
            $this->terrain->addTerrain(1111 ,1 , "mountain");
            $this->terrain->addTerrain(1112 ,1 , "mountain");
            $this->terrain->addTerrain(1113 ,1 , "mountain");
            $this->terrain->addTerrain(1114 ,1 , "mountain");
            $this->terrain->addTerrain(1115 ,1 , "mountain");
            $this->terrain->addTerrain(1116 ,1 , "mountain");
            $this->terrain->addTerrain(1211 ,1 , "mountain");
            $this->terrain->addTerrain(1212 ,1 , "mountain");
            $this->terrain->addTerrain(1313 ,1 , "mountain");
            $this->terrain->addTerrain(1213 ,1 , "mountain");
            $this->terrain->addTerrain(1411 ,1 , "mountain");
            $this->terrain->addTerrain(1312 ,1 , "mountain");
            $this->terrain->addTerrain(1412 ,1 , "mountain");
            $this->terrain->addTerrain(1511 ,1 , "mountain");
            $this->terrain->addTerrain(1512 ,1 , "mountain");
            $this->terrain->addTerrain(1513 ,1 , "mountain");
            $this->terrain->addTerrain(1514 ,1 , "mountain");
            $this->terrain->addTerrain(1611 ,1 , "mountain");
            $this->terrain->addTerrain(1612 ,1 , "mountain");
            $this->terrain->addTerrain(1613 ,1 , "mountain");
            $this->terrain->addTerrain(1614 ,1 , "mountain");
            $this->terrain->addTerrain(1713 ,1 , "mountain");
            $this->terrain->addTerrain(1714 ,1 , "mountain");
            $this->terrain->addTerrain(1715 ,1 , "mountain");
            $this->terrain->addTerrain(1813 ,1 , "mountain");
            $this->terrain->addTerrain(1814 ,1 , "mountain");
            $this->terrain->addTerrain(1815 ,1 , "mountain");
            $this->terrain->addTerrain(1914 ,1 , "mountain");
            $this->terrain->addTerrain(1915 ,1 , "mountain");
            $this->terrain->addTerrain(2012 ,1 , "mountain");
            $this->terrain->addTerrain(2013 ,1 , "mountain");
            $this->terrain->addTerrain(2014 ,1 , "mountain");
            $this->terrain->addTerrain(2015 ,1 , "mountain");
            $this->terrain->addTerrain(2113 ,1 , "mountain");
            $this->terrain->addReinforceZone(2113,'A');
            $this->terrain->addTerrain(2114 ,1 , "mountain");
            $this->terrain->addReinforceZone(2114,'A');
            $this->terrain->addTerrain(2115 ,1 , "mountain");
            $this->terrain->addReinforceZone(2115,'A');
            $this->terrain->addTerrain(2116 ,1 , "mountain");
            $this->terrain->addReinforceZone(2116,'A');
            $this->terrain->addTerrain(2213 ,1 , "mountain");
            $this->terrain->addReinforceZone(2213,'A');
            $this->terrain->addTerrain(2214 ,1 , "mountain");
            $this->terrain->addReinforceZone(2214,'A');
            $this->terrain->addTerrain(2215 ,1 , "mountain");
            $this->terrain->addReinforceZone(2215,'A');
            $this->terrain->addTerrain(2216 ,1 , "mountain");
            $this->terrain->addReinforceZone(2216,'A');
            $this->terrain->addTerrain(2314 ,1 , "mountain");
            $this->terrain->addReinforceZone(2314,'A');
            $this->terrain->addTerrain(2315 ,1 , "mountain");
            $this->terrain->addReinforceZone(2315,'A');
            $this->terrain->addTerrain(2316 ,1 , "mountain");
            $this->terrain->addReinforceZone(2316,'A');
            $this->terrain->addTerrain(2413 ,1 , "mountain");
            $this->terrain->addReinforceZone(2413,'A');
            $this->terrain->addTerrain(2414 ,1 , "mountain");
            $this->terrain->addReinforceZone(2414,'A');
            $this->terrain->addTerrain(2415 ,1 , "mountain");
            $this->terrain->addReinforceZone(2415,'A');
            $this->terrain->addTerrain(2416 ,1 , "mountain");
            $this->terrain->addReinforceZone(2416,'A');
            $this->terrain->addTerrain(2514 ,1 , "mountain");
            $this->terrain->addReinforceZone(2514,'A');
            $this->terrain->addTerrain(2515 ,1 , "mountain");
            $this->terrain->addReinforceZone(2515,'A');
            $this->terrain->addTerrain(2516 ,1 , "mountain");
            $this->terrain->addReinforceZone(2516,'A');
            $this->terrain->addTerrain(2613 ,1 , "mountain");
            $this->terrain->addReinforceZone(2613,'A');
            $this->terrain->addTerrain(2615 ,1 , "mountain");
            $this->terrain->addReinforceZone(2615,'A');
            $this->terrain->addTerrain(2616 ,1 , "mountain");
            $this->terrain->addTerrain(2616 ,1 , "road");
            $this->terrain->addReinforceZone(2616,'A');
            $this->terrain->addTerrain(2715 ,1 , "mountain");
            $this->terrain->addReinforceZone(2715,'A');
            $this->terrain->addTerrain(2716 ,1 , "mountain");
            $this->terrain->addTerrain(2716 ,1 , "road");
            $this->terrain->addReinforceZone(2716,'A');
            $this->terrain->addTerrain(2717 ,1 , "mountain");
            $this->terrain->addReinforceZone(2717,'A');
            $this->terrain->addTerrain(2817 ,1 , "mountain");
            $this->terrain->addReinforceZone(2817,'A');
            $this->terrain->addTerrain(2816 ,1 , "mountain");
            $this->terrain->addReinforceZone(2816,'A');
            $this->terrain->addTerrain(2815 ,1 , "mountain");
            $this->terrain->addTerrain(2815 ,1 , "road");
            $this->terrain->addReinforceZone(2815,'A');
            $this->terrain->addTerrain(2814 ,1 , "mountain");
            $this->terrain->addTerrain(2814 ,1 , "road");
            $this->terrain->addReinforceZone(2814,'A');
            $this->terrain->addTerrain(2914 ,1 , "mountain");
            $this->terrain->addReinforceZone(2914,'A');
            $this->terrain->addTerrain(2915 ,1 , "mountain");
            $this->terrain->addReinforceZone(2915,'A');
            $this->terrain->addTerrain(2916 ,1 , "mountain");
            $this->terrain->addReinforceZone(2916,'A');
            $this->terrain->addTerrain(2917 ,1 , "mountain");
            $this->terrain->addReinforceZone(2917,'A');
            $this->terrain->addTerrain(2918 ,1 , "mountain");
            $this->terrain->addReinforceZone(2918,'A');
            $this->terrain->addTerrain(3018 ,1 , "mountain");
            $this->terrain->addReinforceZone(3018,'A');
            $this->terrain->addTerrain(3017 ,1 , "mountain");
            $this->terrain->addReinforceZone(3017,'A');
            $this->terrain->addTerrain(3016 ,1 , "mountain");
            $this->terrain->addReinforceZone(3016,'A');
            $this->terrain->addTerrain(3015 ,1 , "mountain");
            $this->terrain->addReinforceZone(3015,'A');
            $this->terrain->addTerrain(3014 ,1 , "mountain");
            $this->terrain->addReinforceZone(3014,'A');
            $this->terrain->addTerrain(3114 ,1 , "mountain");
            $this->terrain->addReinforceZone(3114,'A');
            $this->terrain->addTerrain(3115 ,1 , "mountain");
            $this->terrain->addReinforceZone(3115,'A');
            $this->terrain->addTerrain(3116 ,1 , "mountain");
            $this->terrain->addReinforceZone(3116,'A');
            $this->terrain->addTerrain(3117 ,1 , "mountain");
            $this->terrain->addReinforceZone(3117,'A');
            $this->terrain->addTerrain(3118 ,1 , "mountain");
            $this->terrain->addReinforceZone(3118,'A');
            $this->terrain->addTerrain(3119 ,1 , "mountain");
            $this->terrain->addReinforceZone(3119,'A');
            $this->terrain->addTerrain(3219 ,1 , "mountain");
            $this->terrain->addReinforceZone(3219,'A');
            $this->terrain->addTerrain(3218 ,1 , "mountain");
            $this->terrain->addReinforceZone(3218,'A');
            $this->terrain->addTerrain(3216 ,1 , "mountain");
            $this->terrain->addReinforceZone(3216,'A');
            $this->terrain->addTerrain(3217 ,1 , "mountain");
            $this->terrain->addReinforceZone(3217,'A');
            $this->terrain->addTerrain(3215 ,1 , "mountain");
            $this->terrain->addReinforceZone(3215,'A');
            $this->terrain->addTerrain(3214 ,1 , "mountain");
            $this->terrain->addReinforceZone(3214,'A');
            $this->terrain->addTerrain(3213 ,1 , "mountain");
            $this->terrain->addReinforceZone(3213,'A');
            $this->terrain->addTerrain(3212 ,1 , "mountain");
            $this->terrain->addReinforceZone(3212,'A');
            $this->terrain->addTerrain(3112 ,1 , "mountain");
            $this->terrain->addReinforceZone(3112,'A');
            $this->terrain->addTerrain(3111 ,1 , "mountain");
            $this->terrain->addReinforceZone(3111,'B');
            $this->terrain->addTerrain(3110 ,1 , "mountain");
            $this->terrain->addTerrain(3109 ,1 , "mountain");
            $this->terrain->addTerrain(3207 ,1 , "mountain");
            $this->terrain->addTerrain(3108 ,1 , "mountain");
            $this->terrain->addTerrain(3208 ,1 , "mountain");
            $this->terrain->addTerrain(3209 ,1 , "mountain");
            $this->terrain->addTerrain(3210 ,1 , "mountain");
            $this->terrain->addTerrain(3211 ,1 , "mountain");
            $this->terrain->addReinforceZone(3211,'B');
            $this->terrain->addTerrain(3206 ,1 , "mountain");
            $this->terrain->addTerrain(3205 ,1 , "mountain");
            $this->terrain->addTerrain(3205 ,1 , "road");
            $this->terrain->addTerrain(3204 ,1 , "mountain");
            $this->terrain->addTerrain(3203 ,1 , "mountain");
            $this->terrain->addTerrain(3202 ,1 , "mountain");
            $this->terrain->addTerrain(3201 ,1 , "mountain");
            $this->terrain->addTerrain(3101 ,1 , "mountain");
            $this->terrain->addTerrain(3102 ,1 , "mountain");
            $this->terrain->addTerrain(3103 ,1 , "mountain");
            $this->terrain->addTerrain(3104 ,1 , "mountain");
            $this->terrain->addTerrain(3001 ,1 , "mountain");
            $this->terrain->addTerrain(3002 ,1 , "mountain");
            $this->terrain->addTerrain(3003 ,1 , "mountain");
            $this->terrain->addTerrain(2903 ,1 , "mountain");
            $this->terrain->addTerrain(2902 ,1 , "mountain");
            $this->terrain->addTerrain(2901 ,1 , "mountain");
            $this->terrain->addTerrain(2801 ,1 , "mountain");
            $this->terrain->addTerrain(2802 ,1 , "mountain");
            $this->terrain->addTerrain(3301 ,1 , "mountain");
            $this->terrain->addTerrain(3301 ,1 , "road");
            $this->terrain->addTerrain(3302 ,1 , "mountain");
            $this->terrain->addTerrain(3302 ,1 , "road");
            $this->terrain->addTerrain(3303 ,1 , "mountain");
            $this->terrain->addTerrain(3303 ,1 , "road");
            $this->terrain->addTerrain(3304 ,1 , "mountain");
            $this->terrain->addTerrain(3304 ,1 , "road");
            $this->terrain->addTerrain(3305 ,1 , "mountain");
            $this->terrain->addTerrain(3305 ,1 , "road");
            $this->terrain->addTerrain(3306 ,1 , "mountain");
            $this->terrain->addTerrain(3306 ,1 , "road");
            $this->terrain->addTerrain(3307 ,1 , "mountain");
            $this->terrain->addTerrain(3308 ,1 , "mountain");
            $this->terrain->addTerrain(3309 ,1 , "mountain");
            $this->terrain->addTerrain(3310 ,1 , "mountain");
            $this->terrain->addTerrain(3311 ,1 , "mountain");
            $this->terrain->addTerrain(3312 ,1 , "mountain");
            $this->terrain->addReinforceZone(3312,'B');
            $this->terrain->addTerrain(3313 ,1 , "mountain");
            $this->terrain->addReinforceZone(3313,'A');
            $this->terrain->addTerrain(3314 ,1 , "mountain");
            $this->terrain->addReinforceZone(3314,'A');
            $this->terrain->addTerrain(3315 ,1 , "mountain");
            $this->terrain->addReinforceZone(3315,'A');
            $this->terrain->addTerrain(3316 ,1 , "mountain");
            $this->terrain->addReinforceZone(3316,'A');
            $this->terrain->addTerrain(3317 ,1 , "mountain");
            $this->terrain->addReinforceZone(3317,'A');
            $this->terrain->addTerrain(3318 ,1 , "mountain");
            $this->terrain->addReinforceZone(3318,'A');
            $this->terrain->addTerrain(3319 ,1 , "mountain");
            $this->terrain->addReinforceZone(3319,'A');
            $this->terrain->addTerrain(3419 ,1 , "mountain");
            $this->terrain->addReinforceZone(3419,'A');
            $this->terrain->addTerrain(3418 ,1 , "mountain");
            $this->terrain->addReinforceZone(3418,'A');
            $this->terrain->addTerrain(3417 ,1 , "mountain");
            $this->terrain->addReinforceZone(3417,'A');
            $this->terrain->addTerrain(3416 ,1 , "mountain");
            $this->terrain->addReinforceZone(3416,'A');
            $this->terrain->addTerrain(3415 ,1 , "mountain");
            $this->terrain->addReinforceZone(3415,'A');
            $this->terrain->addTerrain(3414 ,1 , "mountain");
            $this->terrain->addReinforceZone(3414,'A');
            $this->terrain->addTerrain(3413 ,1 , "mountain");
            $this->terrain->addReinforceZone(3413,'A');
            $this->terrain->addTerrain(3412 ,1 , "mountain");
            $this->terrain->addReinforceZone(3412,'A');
            $this->terrain->addTerrain(3411 ,1 , "mountain");
            $this->terrain->addReinforceZone(3411,'B');
            $this->terrain->addTerrain(3410 ,1 , "mountain");
            $this->terrain->addTerrain(3409 ,1 , "mountain");
            $this->terrain->addTerrain(3408 ,1 , "mountain");
            $this->terrain->addTerrain(3407 ,1 , "mountain");
            $this->terrain->addTerrain(3407 ,1 , "road");
            $this->terrain->addTerrain(3406 ,1 , "mountain");
            $this->terrain->addTerrain(3406 ,1 , "road");
            $this->terrain->addTerrain(3405 ,1 , "mountain");
            $this->terrain->addTerrain(3403 ,1 , "mountain");
            $this->terrain->addTerrain(3402 ,1 , "mountain");
            $this->terrain->addTerrain(3401 ,1 , "mountain");
            $this->terrain->addTerrain(3404 ,1 , "mountain");
            $this->terrain->addTerrain(3501 ,1 , "mountain");
            $this->terrain->addTerrain(3502 ,1 , "mountain");
            $this->terrain->addTerrain(3503 ,1 , "mountain");
            $this->terrain->addTerrain(3504 ,1 , "mountain");
            $this->terrain->addTerrain(3505 ,1 , "mountain");
            $this->terrain->addTerrain(3506 ,1 , "mountain");
            $this->terrain->addTerrain(3507 ,1 , "mountain");
            $this->terrain->addTerrain(3508 ,1 , "mountain");
            $this->terrain->addTerrain(3508 ,1 , "road");
            $this->terrain->addTerrain(3509 ,1 , "mountain");
            $this->terrain->addTerrain(3510 ,1 , "mountain");
            $this->terrain->addTerrain(3511 ,1 , "mountain");
            $this->terrain->addTerrain(3512 ,1 , "mountain");
            $this->terrain->addReinforceZone(3512,'B');
            $this->terrain->addTerrain(3513 ,1 , "mountain");
            $this->terrain->addReinforceZone(3513,'A');
            $this->terrain->addTerrain(3514 ,1 , "mountain");
            $this->terrain->addReinforceZone(3514,'A');
            $this->terrain->addTerrain(3515 ,1 , "mountain");
            $this->terrain->addReinforceZone(3515,'A');
            $this->terrain->addTerrain(3516 ,1 , "mountain");
            $this->terrain->addReinforceZone(3516,'A');
            $this->terrain->addTerrain(3517 ,1 , "mountain");
            $this->terrain->addReinforceZone(3517,'A');
            $this->terrain->addTerrain(3518 ,1 , "mountain");
            $this->terrain->addReinforceZone(3518,'A');
            $this->terrain->addTerrain(3519 ,1 , "mountain");
            $this->terrain->addReinforceZone(3519,'A');
            $this->terrain->addTerrain(3618 ,1 , "mountain");
            $this->terrain->addReinforceZone(3618,'A');
            $this->terrain->addTerrain(3619 ,1 , "mountain");
            $this->terrain->addReinforceZone(3619,'A');
            $this->terrain->addTerrain(3617 ,1 , "mountain");
            $this->terrain->addReinforceZone(3617,'A');
            $this->terrain->addTerrain(3616 ,1 , "mountain");
            $this->terrain->addReinforceZone(3616,'A');
            $this->terrain->addTerrain(3615 ,1 , "mountain");
            $this->terrain->addReinforceZone(3615,'A');
            $this->terrain->addTerrain(3614 ,1 , "mountain");
            $this->terrain->addReinforceZone(3614,'A');
            $this->terrain->addTerrain(3613 ,1 , "mountain");
            $this->terrain->addReinforceZone(3613,'A');
            $this->terrain->addTerrain(3612 ,1 , "mountain");
            $this->terrain->addReinforceZone(3612,'A');
            $this->terrain->addTerrain(3611 ,1 , "mountain");
            $this->terrain->addReinforceZone(3611,'B');
            $this->terrain->addTerrain(3610 ,1 , "mountain");
            $this->terrain->addTerrain(3609 ,1 , "mountain");
            $this->terrain->addTerrain(3608 ,1 , "mountain");
            $this->terrain->addTerrain(3607 ,1 , "mountain");
            $this->terrain->addTerrain(3607 ,1 , "road");
            $this->terrain->addTerrain(3606 ,1 , "mountain");
            $this->terrain->addTerrain(3605 ,1 , "mountain");
            $this->terrain->addTerrain(3604 ,1 , "mountain");
            $this->terrain->addTerrain(3603 ,1 , "mountain");
            $this->terrain->addTerrain(3602 ,1 , "mountain");
            $this->terrain->addTerrain(3601 ,1 , "mountain");
            $this->terrain->addTerrain(1528 ,1 , "mountain");
            $this->terrain->addTerrain(1628 ,1 , "mountain");
            $this->terrain->addTerrain(1729 ,1 , "mountain");
            $this->terrain->addTerrain(1730 ,1 , "mountain");
            $this->terrain->addTerrain(1731 ,1 , "mountain");
            $this->terrain->addTerrain(1830 ,1 , "mountain");
            $this->terrain->addTerrain(1829 ,1 , "mountain");
            $this->terrain->addTerrain(1828 ,1 , "mountain");
            $this->terrain->addTerrain(1826 ,1 , "mountain");
            $this->terrain->addTerrain(1827 ,1 , "mountain");
            $this->terrain->addTerrain(1925 ,1 , "mountain");
            $this->terrain->addTerrain(1926 ,1 , "mountain");
            $this->terrain->addTerrain(1927 ,1 , "mountain");
            $this->terrain->addTerrain(1928 ,1 , "mountain");
            $this->terrain->addTerrain(1929 ,1 , "mountain");
            $this->terrain->addTerrain(1930 ,1 , "mountain");
            $this->terrain->addTerrain(2030 ,1 , "mountain");
            $this->terrain->addTerrain(2029 ,1 , "mountain");
            $this->terrain->addTerrain(2028 ,1 , "mountain");
            $this->terrain->addTerrain(2027 ,1 , "mountain");
            $this->terrain->addTerrain(2026 ,1 , "mountain");
            $this->terrain->addTerrain(2025 ,1 , "mountain");
            $this->terrain->addTerrain(2024 ,1 , "mountain");
            $this->terrain->addTerrain(2023 ,1 , "mountain");
            $this->terrain->addTerrain(2123 ,1 , "mountain");
            $this->terrain->addTerrain(2123 ,1 , "road");
            $this->terrain->addReinforceZone(2123,'A');
            $this->terrain->addTerrain(2124 ,1 , "mountain");
            $this->terrain->addReinforceZone(2124,'A');
            $this->terrain->addTerrain(2125 ,1 , "mountain");
            $this->terrain->addReinforceZone(2125,'A');
            $this->terrain->addTerrain(2126 ,1 , "mountain");
            $this->terrain->addReinforceZone(2126,'A');
            $this->terrain->addTerrain(2127 ,1 , "mountain");
            $this->terrain->addReinforceZone(2127,'A');
            $this->terrain->addTerrain(2128 ,1 , "mountain");
            $this->terrain->addReinforceZone(2128,'A');
            $this->terrain->addTerrain(2129 ,1 , "mountain");
            $this->terrain->addReinforceZone(2129,'A');
            $this->terrain->addTerrain(2130 ,1 , "mountain");
            $this->terrain->addReinforceZone(2130,'A');
            $this->terrain->addTerrain(2229 ,1 , "mountain");
            $this->terrain->addReinforceZone(2229,'A');
            $this->terrain->addTerrain(2230 ,1 , "mountain");
            $this->terrain->addReinforceZone(2230,'A');
            $this->terrain->addTerrain(2228 ,1 , "mountain");
            $this->terrain->addReinforceZone(2228,'A');
            $this->terrain->addTerrain(2226 ,1 , "mountain");
            $this->terrain->addReinforceZone(2226,'A');
            $this->terrain->addTerrain(2225 ,1 , "mountain");
            $this->terrain->addTerrain(2225 ,1 , "road");
            $this->terrain->addReinforceZone(2225,'A');
            $this->terrain->addTerrain(2227 ,1 , "mountain");
            $this->terrain->addReinforceZone(2227,'A');
            $this->terrain->addTerrain(2224 ,1 , "mountain");
            $this->terrain->addTerrain(2224 ,1 , "road");
            $this->terrain->addReinforceZone(2224,'A');
            $this->terrain->addTerrain(2324 ,1 , "mountain");
            $this->terrain->addReinforceZone(2324,'A');
            $this->terrain->addTerrain(2325 ,1 , "mountain");
            $this->terrain->addReinforceZone(2325,'A');
            $this->terrain->addTerrain(2326 ,1 , "mountain");
            $this->terrain->addTerrain(2326 ,1 , "road");
            $this->terrain->addReinforceZone(2326,'A');
            $this->terrain->addTerrain(2327 ,1 , "mountain");
            $this->terrain->addReinforceZone(2327,'A');
            $this->terrain->addTerrain(2328 ,1 , "mountain");
            $this->terrain->addReinforceZone(2328,'A');
            $this->terrain->addTerrain(2329 ,1 , "mountain");
            $this->terrain->addReinforceZone(2329,'A');
            $this->terrain->addTerrain(2330 ,1 , "mountain");
            $this->terrain->addReinforceZone(2330,'A');
            $this->terrain->addTerrain(2430 ,1 , "mountain");
            $this->terrain->addReinforceZone(2430,'A');
            $this->terrain->addTerrain(2428 ,1 , "mountain");
            $this->terrain->addReinforceZone(2428,'A');
            $this->terrain->addTerrain(2427 ,1 , "mountain");
            $this->terrain->addReinforceZone(2427,'A');
            $this->terrain->addTerrain(2429 ,1 , "mountain");
            $this->terrain->addReinforceZone(2429,'A');
            $this->terrain->addTerrain(2426 ,1 , "mountain");
            $this->terrain->addTerrain(2426 ,1 , "road");
            $this->terrain->addReinforceZone(2426,'A');
            $this->terrain->addTerrain(2425 ,1 , "mountain");
            $this->terrain->addReinforceZone(2425,'A');
            $this->terrain->addTerrain(2424 ,1 , "mountain");
            $this->terrain->addReinforceZone(2424,'A');
            $this->terrain->addTerrain(2525 ,1 , "mountain");
            $this->terrain->addReinforceZone(2525,'A');
            $this->terrain->addTerrain(2526 ,1 , "mountain");
            $this->terrain->addReinforceZone(2526,'A');
            $this->terrain->addTerrain(2527 ,1 , "mountain");
            $this->terrain->addTerrain(2527 ,1 , "road");
            $this->terrain->addReinforceZone(2527,'A');
            $this->terrain->addTerrain(2529 ,1 , "mountain");
            $this->terrain->addReinforceZone(2529,'A');
            $this->terrain->addTerrain(2530 ,1 , "mountain");
            $this->terrain->addReinforceZone(2530,'A');
            $this->terrain->addTerrain(2531 ,1 , "mountain");
            $this->terrain->addReinforceZone(2531,'A');
            $this->terrain->addTerrain(2625 ,1 , "mountain");
            $this->terrain->addReinforceZone(2625,'A');
            $this->terrain->addTerrain(2626 ,1 , "mountain");
            $this->terrain->addReinforceZone(2626,'A');
            $this->terrain->addTerrain(2725 ,1 , "mountain");
            $this->terrain->addReinforceZone(2725,'A');
            $this->terrain->addTerrain(2726 ,1 , "mountain");
            $this->terrain->addReinforceZone(2726,'A');
            $this->terrain->addTerrain(2727 ,1 , "mountain");
            $this->terrain->addReinforceZone(2727,'A');
            $this->terrain->addTerrain(2825 ,1 , "mountain");
            $this->terrain->addReinforceZone(2825,'A');
            $this->terrain->addTerrain(2826 ,1 , "mountain");
            $this->terrain->addReinforceZone(2826,'A');
            $this->terrain->addTerrain(2827 ,1 , "mountain");
            $this->terrain->addReinforceZone(2827,'A');
            $this->terrain->addTerrain(2828 ,1 , "mountain");
            $this->terrain->addReinforceZone(2828,'A');
            $this->terrain->addTerrain(2925 ,1 , "mountain");
            $this->terrain->addReinforceZone(2925,'A');
            $this->terrain->addTerrain(2926 ,1 , "mountain");
            $this->terrain->addReinforceZone(2926,'A');
            $this->terrain->addTerrain(2927 ,1 , "mountain");
            $this->terrain->addReinforceZone(2927,'A');
            $this->terrain->addTerrain(2928 ,1 , "mountain");
            $this->terrain->addReinforceZone(2928,'B');
            $this->terrain->addTerrain(2929 ,1 , "mountain");
            $this->terrain->addReinforceZone(2929,'B');
            $this->terrain->addTerrain(2930 ,1 , "mountain");
            $this->terrain->addTerrain(2930 ,1 , "road");
            $this->terrain->addReinforceZone(2930,'B');
            $this->terrain->addTerrain(2931 ,1 , "mountain");
            $this->terrain->addReinforceZone(2931,'A');
            $this->terrain->addTerrain(3030 ,1 , "mountain");
            $this->terrain->addTerrain(3030 ,1 , "road");
            $this->terrain->addReinforceZone(3030,'B');
            $this->terrain->addTerrain(3031 ,1 , "mountain");
            $this->terrain->addReinforceZone(3031,'B');
            $this->terrain->addTerrain(3029 ,1 , "mountain");
            $this->terrain->addReinforceZone(3029,'B');
            $this->terrain->addTerrain(3028 ,1 , "mountain");
            $this->terrain->addReinforceZone(3028,'B');
            $this->terrain->addTerrain(3027 ,1 , "mountain");
            $this->terrain->addReinforceZone(3027,'B');
            $this->terrain->addTerrain(3025 ,1 , "mountain");
            $this->terrain->addReinforceZone(3025,'A');
            $this->terrain->addTerrain(3026 ,1 , "mountain");
            $this->terrain->addReinforceZone(3026,'A');
            $this->terrain->addTerrain(3126 ,1 , "mountain");
            $this->terrain->addReinforceZone(3126,'A');
            $this->terrain->addTerrain(3127 ,1 , "mountain");
            $this->terrain->addReinforceZone(3127,'B');
            $this->terrain->addTerrain(3128 ,1 , "mountain");
            $this->terrain->addReinforceZone(3128,'B');
            $this->terrain->addTerrain(3129 ,1 , "mountain");
            $this->terrain->addReinforceZone(3129,'B');
            $this->terrain->addTerrain(3130 ,1 , "mountain");
            $this->terrain->addReinforceZone(3130,'B');
            $this->terrain->addTerrain(3131 ,1 , "mountain");
            $this->terrain->addTerrain(3131 ,1 , "road");
            $this->terrain->addReinforceZone(3131,'B');
            $this->terrain->addTerrain(3132 ,1 , "mountain");
            $this->terrain->addReinforceZone(3132,'B');
            $this->terrain->addTerrain(3232 ,1 , "mountain");
            $this->terrain->addTerrain(3232 ,1 , "road");
            $this->terrain->addReinforceZone(3232,'B');
            $this->terrain->addTerrain(3231 ,1 , "mountain");
            $this->terrain->addTerrain(3231 ,1 , "road");
            $this->terrain->addReinforceZone(3231,'B');
            $this->terrain->addTerrain(3230 ,1 , "mountain");
            $this->terrain->addTerrain(3230 ,1 , "road");
            $this->terrain->addReinforceZone(3230,'B');
            $this->terrain->addTerrain(3229 ,1 , "mountain");
            $this->terrain->addTerrain(3229 ,1 , "road");
            $this->terrain->addReinforceZone(3229,'B');
            $this->terrain->addTerrain(3227 ,2 , "road");
            $this->terrain->addTerrain(3227 ,1 , "mountain");
            $this->terrain->addTerrain(3227 ,1 , "road");
            $this->terrain->addReinforceZone(3227,'B');
            $this->terrain->addTerrain(3226 ,1 , "mountain");
            $this->terrain->addTerrain(3226 ,1 , "road");
            $this->terrain->addReinforceZone(3226,'B');
            $this->terrain->addTerrain(3228 ,1 , "mountain");
            $this->terrain->addTerrain(3228 ,1 , "road");
            $this->terrain->addReinforceZone(3228,'B');
            $this->terrain->addTerrain(3327 ,1 , "mountain");
            $this->terrain->addTerrain(3328 ,1 , "mountain");
            $this->terrain->addTerrain(3329 ,1 , "mountain");
            $this->terrain->addTerrain(3330 ,1 , "mountain");
            $this->terrain->addTerrain(3331 ,1 , "mountain");
            $this->terrain->addTerrain(3332 ,1 , "mountain");
            $this->terrain->addTerrain(3333 ,1 , "mountain");
            $this->terrain->addTerrain(3433 ,1 , "mountain");
            $this->terrain->addTerrain(3432 ,1 , "mountain");
            $this->terrain->addTerrain(3431 ,1 , "mountain");
            $this->terrain->addTerrain(3430 ,1 , "mountain");
            $this->terrain->addTerrain(3429 ,1 , "mountain");
            $this->terrain->addTerrain(3428 ,1 , "mountain");
            $this->terrain->addTerrain(3427 ,1 , "mountain");
            $this->terrain->addTerrain(3426 ,1 , "mountain");
            $this->terrain->addTerrain(3527 ,1 , "mountain");
            $this->terrain->addTerrain(3528 ,1 , "mountain");
            $this->terrain->addTerrain(3529 ,1 , "mountain");
            $this->terrain->addTerrain(3530 ,1 , "mountain");
            $this->terrain->addTerrain(3531 ,1 , "mountain");
            $this->terrain->addTerrain(3532 ,1 , "mountain");
            $this->terrain->addTerrain(3533 ,1 , "mountain");
            $this->terrain->addTerrain(3633 ,1 , "mountain");
            $this->terrain->addTerrain(3632 ,1 , "mountain");
            $this->terrain->addTerrain(3631 ,1 , "mountain");
            $this->terrain->addTerrain(3630 ,1 , "mountain");
            $this->terrain->addTerrain(3629 ,1 , "mountain");
            $this->terrain->addTerrain(3628 ,1 , "mountain");
            $this->terrain->addTerrain(3627 ,1 , "mountain");
            $this->terrain->addTerrain(3626 ,1 , "mountain");
            $this->terrain->addTerrain(3727 ,1 , "mountain");
            $this->terrain->addTerrain(3728 ,1 , "mountain");
            $this->terrain->addTerrain(3729 ,1 , "mountain");
            $this->terrain->addTerrain(3730 ,1 , "mountain");
            $this->terrain->addTerrain(3731 ,1 , "mountain");
            $this->terrain->addTerrain(3732 ,1 , "mountain");
            $this->terrain->addTerrain(3733 ,1 , "mountain");
            $this->terrain->addTerrain(3833 ,1 , "mountain");
            $this->terrain->addTerrain(3832 ,1 , "mountain");
            $this->terrain->addTerrain(3831 ,1 , "mountain");
            $this->terrain->addTerrain(3830 ,1 , "mountain");
            $this->terrain->addTerrain(3829 ,1 , "mountain");
            $this->terrain->addTerrain(3828 ,1 , "mountain");
            $this->terrain->addTerrain(3827 ,1 , "mountain");
            $this->terrain->addTerrain(3826 ,1 , "mountain");
            $this->terrain->addTerrain(3927 ,1 , "mountain");
            $this->terrain->addTerrain(3928 ,1 , "mountain");
            $this->terrain->addTerrain(3929 ,1 , "mountain");
            $this->terrain->addTerrain(3930 ,1 , "mountain");
            $this->terrain->addTerrain(3931 ,1 , "mountain");
            $this->terrain->addTerrain(3932 ,1 , "mountain");
            $this->terrain->addTerrain(3933 ,1 , "mountain");
            $this->terrain->addTerrain(3701 ,1 , "mountain");
            $this->terrain->addTerrain(3702 ,1 , "mountain");
            $this->terrain->addTerrain(3703 ,1 , "mountain");
            $this->terrain->addTerrain(3704 ,1 , "mountain");
            $this->terrain->addTerrain(3705 ,1 , "mountain");
            $this->terrain->addTerrain(3706 ,1 , "mountain");
            $this->terrain->addTerrain(3707 ,1 , "mountain");
            $this->terrain->addTerrain(3708 ,1 , "mountain");
            $this->terrain->addTerrain(3708 ,1 , "road");
            $this->terrain->addTerrain(3709 ,1 , "mountain");
            $this->terrain->addTerrain(3710 ,1 , "mountain");
            $this->terrain->addTerrain(3711 ,1 , "mountain");
            $this->terrain->addReinforceZone(3711,'B');
            $this->terrain->addTerrain(3712 ,1 , "mountain");
            $this->terrain->addReinforceZone(3712,'A');
            $this->terrain->addTerrain(3713 ,1 , "mountain");
            $this->terrain->addReinforceZone(3713,'A');
            $this->terrain->addTerrain(3714 ,1 , "mountain");
            $this->terrain->addReinforceZone(3714,'A');
            $this->terrain->addTerrain(3715 ,1 , "mountain");
            $this->terrain->addReinforceZone(3715,'A');
            $this->terrain->addTerrain(3716 ,1 , "mountain");
            $this->terrain->addReinforceZone(3716,'A');
            $this->terrain->addTerrain(3717 ,1 , "mountain");
            $this->terrain->addReinforceZone(3717,'A');
            $this->terrain->addTerrain(3718 ,1 , "mountain");
            $this->terrain->addReinforceZone(3718,'B');
            $this->terrain->addTerrain(3818 ,1 , "mountain");
            $this->terrain->addTerrain(3719 ,1 , "mountain");
            $this->terrain->addReinforceZone(3719,'B');
            $this->terrain->addTerrain(3817 ,1 , "mountain");
            $this->terrain->addReinforceZone(3817,'B');
            $this->terrain->addTerrain(3816 ,1 , "mountain");
            $this->terrain->addReinforceZone(3816,'B');
            $this->terrain->addTerrain(3815 ,1 , "mountain");
            $this->terrain->addReinforceZone(3815,'B');
            $this->terrain->addTerrain(3814 ,1 , "mountain");
            $this->terrain->addReinforceZone(3814,'B');
            $this->terrain->addTerrain(3813 ,1 , "mountain");
            $this->terrain->addReinforceZone(3813,'B');
            $this->terrain->addTerrain(3812 ,1 , "mountain");
            $this->terrain->addReinforceZone(3812,'B');
            $this->terrain->addTerrain(3811 ,1 , "mountain");
            $this->terrain->addReinforceZone(3811,'B');
            $this->terrain->addTerrain(3810 ,1 , "mountain");
            $this->terrain->addTerrain(3809 ,1 , "mountain");
            $this->terrain->addTerrain(3809 ,1 , "road");
            $this->terrain->addTerrain(3808 ,1 , "mountain");
            $this->terrain->addTerrain(3808 ,1 , "road");
            $this->terrain->addTerrain(3805 ,1 , "mountain");
            $this->terrain->addTerrain(3804 ,1 , "mountain");
            $this->terrain->addTerrain(3803 ,1 , "mountain");
            $this->terrain->addTerrain(3806 ,1 , "mountain");
            $this->terrain->addTerrain(3807 ,1 , "mountain");
            $this->terrain->addTerrain(3802 ,1 , "mountain");
            $this->terrain->addTerrain(3801 ,1 , "mountain");
            $this->terrain->addTerrain(3902 ,1 , "mountain");
            $this->terrain->addTerrain(3901 ,1 , "mountain");
            $this->terrain->addTerrain(3903 ,1 , "mountain");
            $this->terrain->addTerrain(3904 ,1 , "mountain");
            $this->terrain->addTerrain(3905 ,1 , "mountain");
            $this->terrain->addTerrain(3906 ,1 , "mountain");
            $this->terrain->addTerrain(3907 ,1 , "mountain");
            $this->terrain->addTerrain(3908 ,1 , "mountain");
            $this->terrain->addTerrain(3909 ,1 , "mountain");
            $this->terrain->addTerrain(3910 ,1 , "mountain");
            $this->terrain->addTerrain(3910 ,1 , "road");
            $this->terrain->addTerrain(3911 ,1 , "mountain");
            $this->terrain->addTerrain(3911 ,1 , "road");
            $this->terrain->addTerrain(3912 ,1 , "mountain");
            $this->terrain->addTerrain(3912 ,1 , "road");
            $this->terrain->addTerrain(3913 ,1 , "mountain");
            $this->terrain->addTerrain(3913 ,1 , "road");
            $this->terrain->addTerrain(3914 ,1 , "mountain");
            $this->terrain->addTerrain(3914 ,1 , "road");
            $this->terrain->addTerrain(3915 ,1 , "mountain");
            $this->terrain->addTerrain(3915 ,1 , "road");
            $this->terrain->addTerrain(3916 ,1 , "mountain");
            $this->terrain->addTerrain(3916 ,1 , "road");
            $this->terrain->addTerrain(3917 ,1 , "mountain");
            $this->terrain->addTerrain(3917 ,1 , "road");
            $this->terrain->addTerrain(3917 ,2 , "road");
            $this->terrain->addTerrain(3918 ,1 , "mountain");
            $this->terrain->addTerrain(3918 ,1 , "road");
            $this->terrain->addTerrain(103 ,1 , "road");
            $this->terrain->addTerrain(203 ,4 , "road");
            $this->terrain->addTerrain(203 ,1 , "road");
            $this->terrain->addTerrain(304 ,4 , "road");
            $this->terrain->addTerrain(304 ,1 , "road");
            $this->terrain->addTerrain(404 ,4 , "road");
            $this->terrain->addTerrain(505 ,4 , "road");
            $this->terrain->addTerrain(605 ,4 , "road");
            $this->terrain->addTerrain(605 ,1 , "road");
            $this->terrain->addTerrain(705 ,3 , "road");
            $this->terrain->addTerrain(705 ,1 , "road");
            $this->terrain->addTerrain(704 ,2 , "road");
            $this->terrain->addTerrain(704 ,1 , "road");
            $this->terrain->addTerrain(703 ,2 , "road");
            $this->terrain->addTerrain(702 ,2 , "road");
            $this->terrain->addTerrain(702 ,1 , "road");
            $this->terrain->addTerrain(701 ,2 , "road");
            $this->terrain->addTerrain(701 ,1 , "road");
            $this->terrain->addTerrain(804 ,4 , "road");
            $this->terrain->addTerrain(804 ,1 , "road");
            $this->terrain->addTerrain(905 ,4 , "road");
            $this->terrain->addTerrain(905 ,1 , "road");
            $this->terrain->addTerrain(1004 ,3 , "road");
            $this->terrain->addTerrain(1004 ,1 , "road");
            $this->terrain->addTerrain(1104 ,3 , "road");
            $this->terrain->addTerrain(1104 ,1 , "road");
            $this->terrain->addTerrain(1203 ,3 , "road");
            $this->terrain->addTerrain(1203 ,1 , "road");
            $this->terrain->addTerrain(1303 ,3 , "road");
            $this->terrain->addTerrain(1303 ,1 , "road");
            $this->terrain->addTerrain(605 ,3 , "road");
            $this->terrain->addTerrain(606 ,4 , "road");
            $this->terrain->addTerrain(707 ,4 , "road");
            $this->terrain->addTerrain(707 ,2 , "road");
            $this->terrain->addTerrain(708 ,2 , "road");
            $this->terrain->addTerrain(709 ,1 , "road");
            $this->terrain->addTerrain(709 ,3 , "road");
            $this->terrain->addTerrain(610 ,2 , "road");
            $this->terrain->addTerrain(510 ,2 , "road");
            $this->terrain->addTerrain(511 ,1 , "road");
            $this->terrain->addTerrain(511 ,3 , "road");
            $this->terrain->addTerrain(411 ,4 , "road");
            $this->terrain->addTerrain(311 ,1 , "road");
            $this->terrain->addTerrain(311 ,3 , "road");
            $this->terrain->addTerrain(211 ,1 , "road");
            $this->terrain->addTerrain(211 ,4 , "road");
            $this->terrain->addTerrain(111 ,1 , "road");
            $this->terrain->addTerrain(611 ,1 , "road");
            $this->terrain->addTerrain(611 ,2 , "road");
            $this->terrain->addTerrain(612 ,1 , "road");
            $this->terrain->addTerrain(612 ,2 , "road");
            $this->terrain->addTerrain(613 ,1 , "road");
            $this->terrain->addTerrain(613 ,2 , "road");
            $this->terrain->addTerrain(614 ,1 , "road");
            $this->terrain->addTerrain(715 ,4 , "road");
            $this->terrain->addTerrain(715 ,1 , "road");
            $this->terrain->addTerrain(815 ,4 , "road");
            $this->terrain->addTerrain(815 ,1 , "road");
            $this->terrain->addTerrain(916 ,4 , "road");
            $this->terrain->addTerrain(1016 ,4 , "road");
            $this->terrain->addTerrain(1016 ,2 , "road");
            $this->terrain->addTerrain(1118 ,4 , "road");
            $this->terrain->addTerrain(1218 ,4 , "road");
            $this->terrain->addTerrain(1218 ,2 , "road");
            $this->terrain->addTerrain(1219 ,1 , "road");
            $this->terrain->addTerrain(1219 ,2 , "road");
            $this->terrain->addTerrain(1220 ,4 , "road");
            $this->terrain->addTerrain(1120 ,4 , "road");
            $this->terrain->addTerrain(1019 ,4 , "road");
            $this->terrain->addTerrain(919 ,3 , "road");
            $this->terrain->addTerrain(819 ,4 , "road");
            $this->terrain->addTerrain(719 ,4 , "road");
            $this->terrain->addTerrain(618 ,3 , "road");
            $this->terrain->addTerrain(1220 ,3 , "road");
            $this->terrain->addTerrain(1121 ,1 , "road");
            $this->terrain->addTerrain(1121 ,3 , "road");
            $this->terrain->addTerrain(1021 ,3 , "road");
            $this->terrain->addTerrain(922 ,2 , "blocked");
            $this->terrain->addTerrain(1320 ,3 , "road");
            $this->terrain->addTerrain(1320 ,1 , "road");
            $this->terrain->addTerrain(1420 ,4 , "road");
            $this->terrain->addTerrain(1420 ,1 , "road");
            $this->terrain->addTerrain(1521 ,4 , "road");
            $this->terrain->addTerrain(1521 ,1 , "road");
            $this->terrain->addTerrain(1621 ,4 , "road");
            $this->terrain->addTerrain(1621 ,1 , "road");
            $this->terrain->addTerrain(1722 ,4 , "road");
            $this->terrain->addTerrain(1822 ,4 , "road");
            $this->terrain->addTerrain(1822 ,1 , "road");
            $this->terrain->addTerrain(1922 ,3 , "road");
            $this->terrain->addTerrain(1922 ,1 , "road");
            $this->terrain->addTerrain(2022 ,4 , "road");
            $this->terrain->addTerrain(2022 ,1 , "road");
            $this->terrain->addTerrain(2123 ,4 , "road");
            $this->terrain->addTerrain(2222 ,3 , "road");
            $this->terrain->addTerrain(2222 ,1 , "road");
            $this->terrain->addReinforceZone(2222,'A');
            $this->terrain->addTerrain(2222 ,2 , "road");
            $this->terrain->addTerrain(2322 ,3 , "road");
            $this->terrain->addTerrain(2322 ,1 , "road");
            $this->terrain->addReinforceZone(2322,'A');
            $this->terrain->addTerrain(2421 ,3 , "road");
            $this->terrain->addTerrain(2421 ,1 , "road");
            $this->terrain->addReinforceZone(2421,'A');
            $this->terrain->addTerrain(2521 ,3 , "road");
            $this->terrain->addTerrain(2521 ,1 , "road");
            $this->terrain->addReinforceZone(2521,'A');
            $this->terrain->addTerrain(2520 ,2 , "road");
            $this->terrain->addTerrain(2520 ,1 , "road");
            $this->terrain->addReinforceZone(2520,'A');
            $this->terrain->addTerrain(2519 ,2 , "road");
            $this->terrain->addTerrain(2519 ,1 , "road");
            $this->terrain->addReinforceZone(2519,'A');
            $this->terrain->addTerrain(2518 ,2 , "road");
            $this->terrain->addTerrain(2518 ,1 , "road");
            $this->terrain->addReinforceZone(2518,'A');
            $this->terrain->addTerrain(2517 ,2 , "road");
            $this->terrain->addTerrain(2517 ,1 , "road");
            $this->terrain->addReinforceZone(2517,'A');
            $this->terrain->addTerrain(2616 ,3 , "road");
            $this->terrain->addTerrain(2716 ,3 , "road");
            $this->terrain->addTerrain(2815 ,3 , "road");
            $this->terrain->addTerrain(2814 ,2 , "road");
            $this->terrain->addTerrain(2813 ,2 , "road");
            $this->terrain->addTerrain(2813 ,1 , "road");
            $this->terrain->addReinforceZone(2813,'A');
            $this->terrain->addTerrain(2812 ,2 , "road");
            $this->terrain->addTerrain(2811 ,2 , "road");
            $this->terrain->addTerrain(2811 ,1 , "road");
            $this->terrain->addReinforceZone(2811,'A');
            $this->terrain->addTerrain(2810 ,2 , "road");
            $this->terrain->addTerrain(2810 ,1 , "road");
            $this->terrain->addReinforceZone(2810,'A');
            $this->terrain->addTerrain(2809 ,2 , "road");
            $this->terrain->addTerrain(2809 ,1 , "road");
            $this->terrain->addReinforceZone(2809,'A');
            $this->terrain->addTerrain(2909 ,3 , "road");
            $this->terrain->addTerrain(2909 ,1 , "road");
            $this->terrain->addReinforceZone(2909,'B');
            $this->terrain->addTerrain(2908 ,2 , "road");
            $this->terrain->addTerrain(2908 ,1 , "road");
            $this->terrain->addReinforceZone(2908,'B');
            $this->terrain->addTerrain(3007 ,3 , "road");
            $this->terrain->addTerrain(3007 ,1 , "road");
            $this->terrain->addTerrain(3107 ,3 , "road");
            $this->terrain->addTerrain(3107 ,1 , "road");
            $this->terrain->addTerrain(3106 ,2 , "road");
            $this->terrain->addTerrain(3106 ,1 , "road");
            $this->terrain->addTerrain(3205 ,3 , "road");
            $this->terrain->addTerrain(3305 ,3 , "road");
            $this->terrain->addTerrain(3304 ,2 , "road");
            $this->terrain->addTerrain(3303 ,2 , "road");
            $this->terrain->addTerrain(3302 ,2 , "road");
            $this->terrain->addTerrain(3301 ,2 , "road");
            $this->terrain->addTerrain(3406 ,4 , "road");
            $this->terrain->addTerrain(3406 ,2 , "road");
            $this->terrain->addTerrain(3508 ,4 , "road");
            $this->terrain->addTerrain(3607 ,3 , "road");
            $this->terrain->addTerrain(3708 ,4 , "road");
            $this->terrain->addTerrain(3808 ,4 , "road");
            $this->terrain->addTerrain(3808 ,2 , "road");
            $this->terrain->addTerrain(3910 ,4 , "road");
            $this->terrain->addTerrain(3910 ,2 , "road");
            $this->terrain->addTerrain(3911 ,2 , "road");
            $this->terrain->addTerrain(3912 ,2 , "road");
            $this->terrain->addTerrain(3913 ,2 , "road");
            $this->terrain->addTerrain(3914 ,2 , "road");
            $this->terrain->addTerrain(3915 ,2 , "road");
            $this->terrain->addTerrain(3916 ,2 , "road");
            $this->terrain->addTerrain(3918 ,2 , "road");
            $this->terrain->addTerrain(3919 ,1 , "road");
            $this->terrain->addTerrain(3919 ,2 , "road");
            $this->terrain->addTerrain(3920 ,1 , "road");
            $this->terrain->addTerrain(3920 ,3 , "road");
            $this->terrain->addTerrain(3820 ,1 , "road");
            $this->terrain->addTerrain(3820 ,2 , "road");
            $this->terrain->addTerrain(3821 ,1 , "road");
            $this->terrain->addTerrain(3821 ,3 , "road");
            $this->terrain->addTerrain(3722 ,1 , "road");
            $this->terrain->addTerrain(3722 ,3 , "road");
            $this->terrain->addTerrain(3622 ,1 , "road");
            $this->terrain->addTerrain(3622 ,3 , "road");
            $this->terrain->addTerrain(3523 ,1 , "road");
            $this->terrain->addTerrain(3523 ,3 , "road");
            $this->terrain->addTerrain(3423 ,1 , "road");
            $this->terrain->addTerrain(3423 ,2 , "road");
            $this->terrain->addTerrain(3424 ,1 , "road");
            $this->terrain->addTerrain(3424 ,3 , "road");
            $this->terrain->addTerrain(3325 ,1 , "road");
            $this->terrain->addTerrain(3325 ,3 , "road");
            $this->terrain->addTerrain(3225 ,1 , "road");
            $this->terrain->addReinforceZone(3225,'B');
            $this->terrain->addTerrain(3225 ,2 , "road");
            $this->terrain->addTerrain(3226 ,2 , "road");
            $this->terrain->addTerrain(3228 ,2 , "road");
            $this->terrain->addTerrain(3229 ,2 , "road");
            $this->terrain->addTerrain(3230 ,2 , "road");
            $this->terrain->addTerrain(3231 ,2 , "road");
            $this->terrain->addTerrain(3232 ,2 , "road");
            $this->terrain->addTerrain(3233 ,1 , "road");
            $this->terrain->addReinforceZone(3233,'B');
            $this->terrain->addTerrain(3231 ,4 , "road");
            $this->terrain->addTerrain(3131 ,4 , "road");
            $this->terrain->addTerrain(3030 ,4 , "road");
            $this->terrain->addTerrain(2930 ,4 , "road");
            $this->terrain->addTerrain(2829 ,1 , "road");
            $this->terrain->addReinforceZone(2829,'A');
            $this->terrain->addTerrain(2829 ,4 , "road");
            $this->terrain->addTerrain(2729 ,1 , "road");
            $this->terrain->addReinforceZone(2729,'A');
            $this->terrain->addTerrain(2729 ,4 , "road");
            $this->terrain->addTerrain(2628 ,1 , "road");
            $this->terrain->addReinforceZone(2628,'A');
            $this->terrain->addTerrain(2627 ,2 , "road");
            $this->terrain->addTerrain(2627 ,1 , "road");
            $this->terrain->addReinforceZone(2627,'A');
            $this->terrain->addTerrain(2627 ,4 , "road");
            $this->terrain->addTerrain(2527 ,4 , "road");
            $this->terrain->addTerrain(2426 ,4 , "road");
            $this->terrain->addTerrain(2326 ,4 , "road");
            $this->terrain->addTerrain(2224 ,2 , "road");
            $this->terrain->addTerrain(2223 ,2 , "road");
            $this->terrain->addTerrain(1301 ,3 , "blocked");
            $this->terrain->addTerrain(1301 ,2 , "blocked");
            $this->terrain->addTerrain(1401 ,3 , "blocked");
            $this->terrain->addTerrain(1402 ,4 , "blocked");
            $this->terrain->addTerrain(1402 ,3 , "blocked");
            $this->terrain->addTerrain(1402 ,2 , "blocked");
            $this->terrain->addTerrain(1503 ,4 , "blocked");
            $this->terrain->addTerrain(1502 ,2 , "blocked");
            $this->terrain->addTerrain(1602 ,4 , "blocked");
            $this->terrain->addTerrain(1601 ,2 , "blocked");
            $this->terrain->addTerrain(1702 ,3 , "blocked");
            $this->terrain->addTerrain(1703 ,4 , "blocked");
            $this->terrain->addTerrain(1703 ,3 , "blocked");
            $this->terrain->addTerrain(1704 ,4 , "blocked");
            $this->terrain->addTerrain(1704 ,3 , "blocked");
            $this->terrain->addTerrain(1705 ,4 , "blocked");
            $this->terrain->addTerrain(1705 ,3 , "blocked");
            $this->terrain->addTerrain(1705 ,2 , "blocked");
            $this->terrain->addTerrain(1805 ,3 , "blocked");
            $this->terrain->addTerrain(1805 ,2 , "blocked");
            $this->terrain->addTerrain(1906 ,3 , "blocked");
            $this->terrain->addTerrain(1907 ,4 , "blocked");
            $this->terrain->addTerrain(1907 ,3 , "blocked");
            $this->terrain->addTerrain(1907 ,2 , "blocked");
            $this->terrain->addTerrain(2007 ,3 , "blocked");
            $this->terrain->addTerrain(2008 ,4 , "blocked");
            $this->terrain->addTerrain(2008 ,3 , "blocked");
            $this->terrain->addTerrain(2008 ,2 , "blocked");
            $this->terrain->addTerrain(2109 ,3 , "blocked");
            $this->terrain->addTerrain(2109 ,2 , "blocked");
            $this->terrain->addTerrain(2209 ,3 , "blocked");
            $this->terrain->addTerrain(2210 ,4 , "blocked");
            $this->terrain->addTerrain(2210 ,3 , "blocked");
            $this->terrain->addTerrain(2211 ,4 , "blocked");
            $this->terrain->addTerrain(2211 ,3 , "blocked");
            $this->terrain->addTerrain(2212 ,4 , "blocked");
            $this->terrain->addTerrain(2212 ,3 , "blocked");
            $this->terrain->addTerrain(2212 ,2 , "blocked");
            $this->terrain->addTerrain(2313 ,3 , "blocked");
            $this->terrain->addTerrain(2313 ,2 , "blocked");
            $this->terrain->addTerrain(2412 ,3 , "blocked");
            $this->terrain->addTerrain(2413 ,4 , "blocked");
            $this->terrain->addTerrain(2412 ,4 , "blocked");
            $this->terrain->addTerrain(2411 ,2 , "blocked");
            $this->terrain->addTerrain(2512 ,4 , "blocked");
            $this->terrain->addTerrain(2511 ,3 , "blocked");
            $this->terrain->addTerrain(2511 ,4 , "blocked");
            $this->terrain->addTerrain(2510 ,3 , "blocked");
            $this->terrain->addTerrain(2510 ,4 , "blocked");
            $this->terrain->addTerrain(2509 ,3 , "blocked");
            $this->terrain->addTerrain(2509 ,4 , "blocked");
            $this->terrain->addTerrain(2508 ,3 , "blocked");
            $this->terrain->addTerrain(2407 ,2 , "blocked");
            $this->terrain->addTerrain(2407 ,3 , "blocked");
            $this->terrain->addTerrain(2407 ,4 , "blocked");
            $this->terrain->addTerrain(2406 ,2 , "blocked");
            $this->terrain->addTerrain(2507 ,4 , "blocked");
            $this->terrain->addTerrain(2506 ,2 , "blocked");
            $this->terrain->addTerrain(2606 ,3 , "blocked");
            $this->terrain->addTerrain(2606 ,2 , "blocked");
            $this->terrain->addTerrain(2707 ,3 , "blocked");
            $this->terrain->addTerrain(2707 ,2 , "blocked");
            $this->terrain->addTerrain(2807 ,3 , "blocked");
            $this->terrain->addTerrain(2807 ,2 , "blocked");
            $this->terrain->addTerrain(2908 ,4 , "blocked");
            $this->terrain->addTerrain(2907 ,2 , "blocked");
            $this->terrain->addTerrain(3007 ,4 , "blocked");
            $this->terrain->addTerrain(3006 ,3 , "blocked");
            $this->terrain->addTerrain(3006 ,4 , "blocked");
            $this->terrain->addTerrain(3005 ,3 , "blocked");
            $this->terrain->addTerrain(3005 ,4 , "blocked");
            $this->terrain->addTerrain(3004 ,3 , "blocked");
            $this->terrain->addTerrain(3004 ,4 , "blocked");
            $this->terrain->addTerrain(3003 ,3 , "blocked");
            $this->terrain->addTerrain(2903 ,2 , "blocked");
            $this->terrain->addTerrain(2903 ,3 , "blocked");
            $this->terrain->addTerrain(2802 ,2 , "blocked");
            $this->terrain->addTerrain(2802 ,3 , "blocked");
            $this->terrain->addTerrain(2801 ,3 , "blocked");
            $this->terrain->addTerrain(2802 ,4 , "blocked");
            $this->terrain->addTerrain(2801 ,4 , "blocked");
            $this->terrain->addTerrain(821 ,2 , "blocked");
            $this->terrain->addTerrain(922 ,3 , "blocked");
            $this->terrain->addTerrain(1022 ,3 , "blocked");
            $this->terrain->addTerrain(1023 ,4 , "blocked");
            $this->terrain->addTerrain(1023 ,3 , "blocked");
            $this->terrain->addTerrain(1023 ,2 , "blocked");
            $this->terrain->addTerrain(1124 ,3 , "blocked");
            $this->terrain->addTerrain(1124 ,2 , "blocked");
            $this->terrain->addTerrain(1224 ,3 , "blocked");
            $this->terrain->addTerrain(1224 ,2 , "blocked");
            $this->terrain->addTerrain(1325 ,3 , "blocked");
            $this->terrain->addTerrain(1326 ,4 , "blocked");
            $this->terrain->addTerrain(1326 ,3 , "blocked");
            $this->terrain->addTerrain(1327 ,4 , "blocked");
            $this->terrain->addTerrain(1327 ,3 , "blocked");
            $this->terrain->addTerrain(1327 ,2 , "blocked");
            $this->terrain->addTerrain(1427 ,4 , "blocked");
            $this->terrain->addTerrain(1426 ,2 , "blocked");
            $this->terrain->addTerrain(1527 ,3 , "blocked");
            $this->terrain->addTerrain(1528 ,4 , "blocked");
            $this->terrain->addTerrain(1528 ,3 , "blocked");
            $this->terrain->addTerrain(1528 ,2 , "blocked");
            $this->terrain->addTerrain(1628 ,3 , "blocked");
            $this->terrain->addTerrain(1628 ,2 , "blocked");
            $this->terrain->addTerrain(1729 ,3 , "blocked");
            $this->terrain->addTerrain(1730 ,4 , "blocked");
            $this->terrain->addTerrain(1730 ,3 , "blocked");
            $this->terrain->addTerrain(1731 ,4 , "blocked");
            $this->terrain->addReinforceZone(3033,'A');
            $this->terrain->addReinforceZone(3032,'A');
            $this->terrain->addReinforceZone(2932,'A');
            $this->terrain->addReinforceZone(2830,'A');
            $this->terrain->addReinforceZone(3125,'A');
            $this->terrain->addReinforceZone(3124,'A');
            $this->terrain->addReinforceZone(3123,'A');
            $this->terrain->addReinforceZone(3122,'A');
            $this->terrain->addReinforceZone(3221,'A');
            $this->terrain->addReinforceZone(3321,'A');
            $this->terrain->addReinforceZone(3420,'A');
            $this->terrain->addReinforceZone(3520,'A');
            $this->terrain->addReinforceZone(3011,'A');
            $this->terrain->addReinforceZone(2911,'A');
            $this->terrain->addReinforceZone(2808,'A');
            $this->terrain->addReinforceZone(2910,'B');
            $this->terrain->addReinforceZone(3010,'B');
            $this->terrain->addReinforceZone(3720,'B');
            $this->terrain->addReinforceZone(3620,'B');
            $this->terrain->addReinforceZone(3521,'B');
            $this->terrain->addReinforceZone(3421,'B');
            $this->terrain->addReinforceZone(3322,'B');
            $this->terrain->addReinforceZone(3222,'B');
            $this->terrain->addReinforceZone(3223,'B');
            $this->terrain->addReinforceZone(3224,'B');
            $this->terrain->addReinforceZone(3133,'B');
            $this->terrain->addReinforceZone(2933,'A');
            $this->terrain->addReinforceZone(2833,'A');
            $this->terrain->addReinforceZone(2832,'A');
            $this->terrain->addReinforceZone(2831,'A');
            $this->terrain->addReinforceZone(2733,'A');
            $this->terrain->addReinforceZone(2732,'A');
            $this->terrain->addReinforceZone(2731,'A');
            $this->terrain->addReinforceZone(2730,'A');
            $this->terrain->addReinforceZone(2728,'A');
            $this->terrain->addReinforceZone(2724,'A');
            $this->terrain->addReinforceZone(2722,'A');
            $this->terrain->addReinforceZone(2822,'A');
            $this->terrain->addReinforceZone(2723,'A');
            $this->terrain->addReinforceZone(2823,'A');
            $this->terrain->addReinforceZone(2824,'A');
            $this->terrain->addReinforceZone(3024,'A');
            $this->terrain->addReinforceZone(2924,'A');
            $this->terrain->addReinforceZone(3023,'A');
            $this->terrain->addReinforceZone(2923,'A');
            $this->terrain->addReinforceZone(3022,'A');
            $this->terrain->addReinforceZone(2922,'A');
            $this->terrain->addReinforceZone(2633,'A');
            $this->terrain->addReinforceZone(2632,'A');
            $this->terrain->addReinforceZone(2631,'A');
            $this->terrain->addReinforceZone(2630,'A');
            $this->terrain->addReinforceZone(2629,'A');
            $this->terrain->addReinforceZone(2624,'A');
            $this->terrain->addReinforceZone(2623,'A');
            $this->terrain->addReinforceZone(2622,'A');
            $this->terrain->addReinforceZone(2621,'A');
            $this->terrain->addReinforceZone(2620,'A');
            $this->terrain->addReinforceZone(2619,'A');
            $this->terrain->addReinforceZone(2618,'A');
            $this->terrain->addReinforceZone(2617,'A');
            $this->terrain->addReinforceZone(2522,'A');
            $this->terrain->addReinforceZone(2523,'A');
            $this->terrain->addReinforceZone(2524,'A');
            $this->terrain->addReinforceZone(2528,'A');
            $this->terrain->addReinforceZone(2532,'A');
            $this->terrain->addReinforceZone(2533,'A');
            $this->terrain->addReinforceZone(2708,'A');
            $this->terrain->addReinforceZone(2709,'A');
            $this->terrain->addReinforceZone(2710,'A');
            $this->terrain->addReinforceZone(2711,'A');
            $this->terrain->addReinforceZone(2712,'A');
            $this->terrain->addReinforceZone(2713,'A');
            $this->terrain->addReinforceZone(2714,'A');
            $this->terrain->addReinforceZone(2912,'A');
            $this->terrain->addReinforceZone(2913,'A');
            $this->terrain->addReinforceZone(3012,'A');
            $this->terrain->addReinforceZone(3013,'A');
            $this->terrain->addReinforceZone(3113,'A');
            $this->terrain->addReinforceZone(3320,'A');
            $this->terrain->addReinforceZone(3220,'A');
            $this->terrain->addReinforceZone(3120,'A');
            $this->terrain->addReinforceZone(3121,'A');
            $this->terrain->addReinforceZone(3021,'A');
            $this->terrain->addReinforceZone(3020,'A');
            $this->terrain->addReinforceZone(3019,'A');
            $this->terrain->addReinforceZone(2919,'A');
            $this->terrain->addReinforceZone(2920,'A');
            $this->terrain->addReinforceZone(2921,'A');
            $this->terrain->addReinforceZone(2821,'A');
            $this->terrain->addReinforceZone(2820,'A');
            $this->terrain->addReinforceZone(2819,'A');
            $this->terrain->addReinforceZone(2818,'A');
            $this->terrain->addReinforceZone(2718,'A');
            $this->terrain->addReinforceZone(2719,'A');
            $this->terrain->addReinforceZone(2720,'A');
            $this->terrain->addReinforceZone(2721,'A');
            $this->terrain->addReinforceZone(2614,'A');
            $this->terrain->addReinforceZone(2612,'A');
            $this->terrain->addReinforceZone(2611,'A');
            $this->terrain->addReinforceZone(2610,'A');
            $this->terrain->addReinforceZone(2609,'A');
            $this->terrain->addReinforceZone(2608,'A');
            $this->terrain->addReinforceZone(2607,'A');
            $this->terrain->addReinforceZone(2507,'A');
            $this->terrain->addReinforceZone(2508,'A');
            $this->terrain->addReinforceZone(2509,'A');
            $this->terrain->addReinforceZone(2510,'A');
            $this->terrain->addReinforceZone(2511,'A');
            $this->terrain->addReinforceZone(2512,'A');
            $this->terrain->addReinforceZone(2513,'A');
            $this->terrain->addReinforceZone(2407,'A');
            $this->terrain->addReinforceZone(2412,'A');
            $this->terrain->addReinforceZone(2417,'A');
            $this->terrain->addReinforceZone(2418,'A');
            $this->terrain->addReinforceZone(2419,'A');
            $this->terrain->addReinforceZone(2420,'A');
            $this->terrain->addReinforceZone(2422,'A');
            $this->terrain->addReinforceZone(2423,'A');
            $this->terrain->addReinforceZone(2431,'A');
            $this->terrain->addReinforceZone(2432,'A');
            $this->terrain->addReinforceZone(2433,'A');
            $this->terrain->addReinforceZone(2333,'A');
            $this->terrain->addReinforceZone(2331,'A');
            $this->terrain->addReinforceZone(2332,'A');
            $this->terrain->addReinforceZone(2323,'A');
            $this->terrain->addReinforceZone(2321,'A');
            $this->terrain->addReinforceZone(2320,'A');
            $this->terrain->addReinforceZone(2319,'A');
            $this->terrain->addReinforceZone(2318,'A');
            $this->terrain->addReinforceZone(2317,'A');
            $this->terrain->addReinforceZone(2217,'A');
            $this->terrain->addReinforceZone(2218,'A');
            $this->terrain->addReinforceZone(2219,'A');
            $this->terrain->addReinforceZone(2220,'A');
            $this->terrain->addReinforceZone(2221,'A');
            $this->terrain->addReinforceZone(2231,'A');
            $this->terrain->addReinforceZone(2232,'A');
            $this->terrain->addReinforceZone(2233,'A');
            $this->terrain->addReinforceZone(2133,'A');
            $this->terrain->addReinforceZone(2132,'A');
            $this->terrain->addReinforceZone(2131,'A');
            $this->terrain->addReinforceZone(2122,'A');
            $this->terrain->addReinforceZone(2121,'A');
            $this->terrain->addReinforceZone(2120,'A');
            $this->terrain->addReinforceZone(2118,'A');
            $this->terrain->addReinforceZone(2117,'A');
            $this->terrain->addReinforceZone(2119,'A');
            // end terrain data ----------------------------------------

            foreach($specialHexA as $specialHexId){
                $specialHexes[$specialHexId] = PRC_FORCE;
            }
            $this->mapData->setSpecialHexes($specialHexes);


        }
    }
}