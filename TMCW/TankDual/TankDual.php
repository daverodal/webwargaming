<?php
//set_include_path(__DIR__ . "/TankDual" . PATH_SEPARATOR . get_include_path());

define("REBEL_FORCE", 1);
define("LOYALIST_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

require_once "constants.php";

$mode_name[3] = "Combat Setup Phase";
$mode_name[4] = "Combat Resolution Phase";
$mode_name[19] = "";

$mode_name[1] = "";
$mode_name[2] = "";


require_once "ModernLandBattle.php";


class TankDual extends ModernLandBattle
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

    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "tankDualHeader.php";
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
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
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

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMountain.png", 5, 2, 3, false, STATUS_CAN_DEPLOY, "A", 1, 6, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiGor.png", 7, 3, 3, false, STATUS_CAN_DEPLOY, "A", 1, 12, "rebel", true, "inf");

        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");
        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");
        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");
        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");
        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");
        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");
        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");
        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");

        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiMountain.png", 5, 2, 3, false, STATUS_CAN_DEPLOY, "B", 1, 6, "loyalist", true, "inf");
        $this->force->addUnit("lll", RED_FORCE, "deployBox", "multiGor.png", 7, 3, 3, false, STATUS_CAN_DEPLOY, "B", 1, 12, "loyalist", true, "inf");

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
            $this->victory = new Victory("TMCW/TankDual/tankDualVictoryCore.php", $data);
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
            $this->victory = new Victory("TMCW/TankDual/tankDualVictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->display = new Display();
            $this->mapData->setData(36, 24, "js/tankDual1Small.png");

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

            for($player = 0;$player < 3;$player++){
                $this->mapViewer[$player]->setData(64 , 82.5, // originX, originY
                    27.5, 27.5, // top hexagon height, bottom hexagon height
                    16, 32// hexagon edge width, hexagon center width
                );
            }
            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
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
            for ($col = 100; $col <= 3600; $col += 100) {
                for ($row = 1; $row <= 24; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }

            for ($col = 100; $col <= 3600; $col += 100) {
                for ($row = 1; $row <= 6; $row++) {
                $this->terrain->addReinforceZone($row+$col,'A');
                }
            }

            for ($col = 100; $col <= 3600; $col += 100) {
                for ($row = 19; $row <= 24; $row++) {
                    $this->terrain->addReinforceZone($row+$col,'B');
                }
            }

            /*
             * Next put terrain like rough and forest because they are exclusive and will cancel what else is there.
             */
            // end terrain data ----------------------------------------

        }
    }
}