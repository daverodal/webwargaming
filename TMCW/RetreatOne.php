<?php
set_include_path(__DIR__ . "/RetreatOne" . PATH_SEPARATOR . get_include_path());

require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Laconians";
$force_name[2] = "Caprolians";

$phase_name = array();
$phase_name[1] = "<span class='laconiansFace'>Laconians</span> Movement Phase";
$phase_name[2] = "<span class='laconiansFace'>Laconians</span>";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "<span class='caproliansFace'>Caprolians</span> Movement Phase";
$phase_name[5] = "<span class='caproliansFace'>Caprolians</span>";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "<span class='laconiansFace'>Laconians</span> Deploy Phase";
$phase_name[9] = "<span class='laconiansFace'>Laconians</span> Mech Movement Phase";
$phase_name[10] = "<span class='laconiansFace'>Laconians</span> Replacement Phase";
$phase_name[11] = "<span class='caproliansFace'>Caprolians</span> Mech Movement Phase";
$phase_name[12] = "<span class='caproliansFace'>Caprolians</span> Replacement Phase";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "<span class='caprolianFace'>Caprolians</span> Deploy Phase";

$mode_name[17] = "";


$mode_name[3] = "Combat Setup Phase";
$mode_name[4] = "Combat Resolution Phase";
$mode_name[19] = "";

$mode_name[1] = "";
$mode_name[2] = "";

define("LACONIANS_FORCE", BLUE_FORCE);
define("CAPROLIANS_FORCE", RED_FORCE);

require_once "ModernLandBattle.php";



class RetreatOne extends ModernLandBattle
{
    /* a comment */

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    public $playerData;
    /* @var Force $force */
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

        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "retreatOneHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    function terrainGen($hexDocId){
        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("secondaryroad", "secondaryroad", "r", .75, 0, 0, false);
        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
        $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
        $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
        $this->terrain->addTerrainFeature("roughone", "roughone", "g", 2, 0, 2, true);
        $this->terrain->addTerrainFeature("roughtwo", "roughtwo", "g", 3, 0, 2, true);
        $this->terrain->addTerrainFeature("swamp", "swamp", "f", 3, 0, 1, true);
        $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
        $this->terrain->addTerrainFeature("wadi", "wadi", "w", 0, 1, 1, false);
        $this->terrain->addAltEntranceCost("roughone", "mech", 6);
        $this->terrain->addAltEntranceCost("roughtwo", "mech", "blocked");
        parent::terrainGen($hexDocId);
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

        for($i = 0;$i < 16;$i++){
            $this->force->addUnit("xx", CAPROLIANS_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 1, "caprolians", true, 'inf');
        }
        for($i = 0;$i < 4;$i++){
            $this->force->addUnit("xx", CAPROLIANS_FORCE, "deployBox", "multiMotInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "caprolians", true, 'mech');
        }
        for($i = 0;$i < 4;$i++){
            $this->force->addUnit("xx", CAPROLIANS_FORCE, "deployBox", "multiMotArt.png", 3, 1, 6, false, STATUS_CAN_DEPLOY, "A", 1, 5, "caprolians", true, 'mech');
        }
        for($i = 0;$i < 4;$i++){
            $this->force->addUnit("xx", CAPROLIANS_FORCE, "deployBox", "multiArmor.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "caprolians", true, 'mech');
        }
        for($i = 0;$i < 3;$i++){
            $this->force->addUnit("xx", CAPROLIANS_FORCE, "deployBox", "multiRecon.png", 2, 1, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "caprolians", true, 'mech');
        }

        for($i = 0;$i < 12;$i++){
            $this->force->addUnit("xx", LACONIANS_FORCE, "deployBox", "multiMotInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "laconians", true, "mech");
        }
        for($i = 0;$i < 4;$i++){
            $this->force->addUnit("xx", LACONIANS_FORCE, "deployBox", "multiMotArt.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "B", 1, 5, "laconians", true, "mech");
        }
        for($i = 0;$i < 4;$i++){
            $this->force->addUnit("xx", LACONIANS_FORCE, "deployBox", "multiArmor.png", 4, 2, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "laconians", true, "mech");
        }
        for($i = 0;$i < 2;$i++){
            $this->force->addUnit("xx", LACONIANS_FORCE, "deployBox", "multiArmor.png", 6, 3, 3, false, STATUS_CAN_DEPLOY, "B", 1, 1, "laconians", true, "mech");
        }
        for($i = 0;$i < 4;$i++){
            $this->force->addUnit("xx", LACONIANS_FORCE, "deployBox", "multiRecon.png", 2, 1, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "laconians", true, "mech");
        }
        $mapData = $this->mapData;
        /* @var MapHex $mapHex */
        $mapHex = $mapData->getHex(3807);
        $mapHex->setZoc(LACONIANS_FORCE, 'air1');
        $mapHex = $mapData->getHex(3306);
        $mapHex->setZoc(LACONIANS_FORCE, 'air2');
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->genTerrain = false;
            $this->victory = new Victory("TMCW/RetreatOne/retreatOneVictoryCore.php", $data);
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
            $this->victory = new Victory("TMCW/RetreatOne/retreatOneVictoryCore.php");

            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->display = new Display();
//            $this->mapData->setData(60, 30, "js/Retreat1Small.png");

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
//            $this->players = array("", "", "");
//            $this->playerData = new stdClass();
//            for ($player = 0; $player <= 2; $player++) {
//                $this->playerData->${player} = new stdClass();
//                $this->playerData->${player}->mapWidth = "auto";
//                $this->playerData->${player}->mapHeight = "auto";
//                $this->playerData->${player}->unitSize = "32px";
//                $this->playerData->${player}->unitFontSize = "12px";
//                $this->playerData->${player}->unitMargin = "-21px";
//            }
//
//            for($player = 0;$player < 3;$player++){
//                $this->mapViewer[$player]->setData(52.72 , 85.37278430506997, // originX, originY
//                    28.457594768356657, 28.457594768356657, // top hexagon height, bottom hexagon height
//                    16.43, 32.86// hexagon edge width, hexagon center width
//                );
//            }
            // game data
            $this->gameRules->setMaxTurn(15);

            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */




            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
            // unit terrain data----------------------------------------

//            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
//            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
//            $this->terrain->addTerrainFeature("secondaryroad", "secondaryroad", "r", .75, 0, 0, false);
//            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
//            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
//            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
//            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
//            $this->terrain->addTerrainFeature("roughone", "roughone", "g", 2, 0, 2, true);
//            $this->terrain->addTerrainFeature("roughtwo", "roughtwo", "g", 3, 0, 2, true);
//            $this->terrain->addTerrainFeature("swamp", "swamp", "f", 3, 0, 1, true);
//            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
//            $this->terrain->addTerrainFeature("wadi", "wadi", "w", 0, 1, 1, false);
//            $this->terrain->addAltEntranceCost("roughone", "mech", 6);
//            $this->terrain->addAltEntranceCost("roughtwo", "mech", "blocked");

            /* handle fort's in crtTraits */

            /*
             * First put clear everywhere, hexes and hex sides
             */
            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
//            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
//            $this->terrain->addTerrainFeature("secondaryroad", "secondaryroad", "r", .75, 0, 0, false);
//            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
//            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
//            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
//            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
//            $this->terrain->addTerrainFeature("roughone", "roughone", "g", 2, 0, 2, true);
//            $this->terrain->addTerrainFeature("roughtwo", "roughtwo", "g", 3, 0, 2, true);
//            $this->terrain->addTerrainFeature("swamp", "swamp", "f", 3, 0, 1, true);
//            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
//            $this->terrain->addTerrainFeature("wadi", "wadi", "w", 0, 1, 1, false);
//            $this->terrain->addAltEntranceCost("roughone", "mech", 6);
//            $this->terrain->addAltEntranceCost("roughtwo", "mech", "blocked");

            // end terrain data ----------------------------------------

        }
    }
}
