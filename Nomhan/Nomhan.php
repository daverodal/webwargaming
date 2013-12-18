<?php
set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());

require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Japanese";
$force_name[2] = "Soviet";

$phase_name = array();
$phase_name[1] = "<span class='rebelFace'>Japanese</span> Movement Phase";
$phase_name[2] = "<span class='rebelFace'>Japanese</span>";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "<span class='loyalistFace'>Soviet</span> Movement Phase";
$phase_name[5] = "<span class='loyalistFace'>Soviet</span>";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "<span class='rebelFace'>Japanese</span> Deploy Phase";
$phase_name[9] = "<span class='rebelFace'>Japanese</span> Mech Movement Phase";
$phase_name[10] = "<span class='rebelFace'>Japanese</span> Replacement Phase";
$phase_name[11] = "<span class='loyalistFace'>Soviet</span> Mech Movement Phase";
$phase_name[12] = "<span class='loyalistFace'>Soviet</span> Replacement Phase";
$phase_name[13] = "";
$phase_name[14] = "";

$mode_name[3] = "Combat Setup Phase";
$mode_name[4] = "Combat Resolution Phase";
$mode_name[19] = "";

$mode_name[1] = "";
$mode_name[2] = "";

define("JAPANESE_FORCE", BLUE_FORCE);
define("SOVIET_FORCE", RED_FORCE);

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


class Nomhan extends Battle
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
    public $argTwo;

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
    }

    static function getView($name, $mapUrl, $player = 0, $player = 0, $arg = false, $argTwo = false)
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
        $data->argTwo = $this->argTwo;
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
        $data->terrainName = "terrain-Nomhan";
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

    function __construct($data = null, $arg = false, $argTwo = false)
    {

        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->argTwo = $data->argTwo;
            $this->genTerrain = false;
            $this->victory = new Victory("Nomhan", $data);
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
            $this->argTwo = $argTwo;
            $this->genTerrain = true;
            $this->victory = new Victory("Nomhan");
            $this->display = new Display();
            $this->mapData->setData(40, 25, "js/Nomhan.jpg");

            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->terrain->setMaxHex("4025");
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            if ($argTwo && $argTwo->supply === true) {
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
            $this->mapViewer[0]->setData(54, 79, // originX, originY
                25.5, 25.5, // top hexagon height, bottom hexagon height
                14.725, 29.45, // hexagon edge width, hexagon center width
                4025, 4025 // max right hexagon, max bottom hexagon
            );
            $this->mapViewer[1]->setData(54, 79, // originX, originY
                25.5, 25.5, // top hexagon height, bottom hexagon height
                14.725, 29.45, // hexagon edge width, hexagon center width
                4025, 4025 // max right hexagon, max bottom hexagon
            );
            $this->mapViewer[2]->setData(54, 79, // originX, originY
                25.5, 25.5, // top hexagon height, bottom hexagon height
                14.725, 29.45, // hexagon edge width, hexagon center width
                4025, 4025 // max right hexagon, max bottom hexagon
            );

            // game data
            $this->gameRules->setMaxTurn(20);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->attackingForceId); /* so object oriented */
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
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

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "loyalist", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "loyalist", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "loyalist", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "R", 1, 1, "loyalist", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "loyalist", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "loyalist", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "loyalist", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_DEPLOY, "R", 1, 1, "loyalist", false, 'infantry');

            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 12, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArmor.png", 7, 3, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiInf.png", 4, 2, 2, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiCav.png", 3, 1, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 1, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 12, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 12, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 12, "loyalist", true, "mech");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn6", "multiArt.png", 4, 2, 8, false, STATUS_CAN_REINFORCE,  "W", 6, 12, "loyalist", true, "mech");


            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiRecon.png", 2, 1, 12, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "mech");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "mech");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "mech");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "mech");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "inf");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiCav.png", 3, 1, 8, false, STATUS_CAN_REINFORCE, "J", 1, 1, "rebel", true, "cavalry");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArt.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 10, "rebel", true, "artillery");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArt.png", 4, 2, 6, false, STATUS_CAN_REINFORCE, "J", 1, 10, "rebel", true, "artillery");
            // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("marsh", "marsh", "m", 2, 0, 1, true);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 2, 0, 2, true);
            $this->terrain->addTerrainFeature("hills", "hills", "h", 4, 0, 2, true);
            $this->terrain->addTerrainFeature("river", "river", "v", 2, 2, 1, true);
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
            for ($i = 2; $i <= 4; $i++) {
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

        $hexes = array(1602, 1703, 1803, 1903, 2003, 2103, 2203);
        foreach ($hexes as $hex) {
            $this->terrain->addTerrain($hex, HEXAGON_CENTER, "marsh");
        }

        $hexes = array(1001, 812, 1109, 1407, 1508, 1322, 2021, 2417, 2617, 3812, 3912);
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

        // end terrain data ----------------------------------------
        }
    }
}