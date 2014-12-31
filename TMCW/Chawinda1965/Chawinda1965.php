<?php

define("INDIAN_FORCE", 1);
define("PAKISTANI_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Indian";
$force_name[2] = "Pakistani";

require_once "constants.php";

require_once "ModernLandBattle.php";


class Chawinda1965 extends ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>2];

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
    public $arg;
    public $scenario;

    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "chawinda1965Header.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    function terrainInit($terrainDoc)
    {
        parent::terrainInit($terrainDoc);
        $vp = count((array)$this->specialHexA);
        $this->victory->setInitialPakistaniVP($vp * 3);
    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 2, 0, 1, true);
        $this->terrain->addAltEntranceCost('swamp', 'mech', 3);
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 2, 1, true);
        $this->terrain->addAltEntranceCost("river", 'mech', 3);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);

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
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;

        return $data;
    }

    public function init()
    {


        $scenario = $this->scenario;

        for($i = 0; $i < 4;$i++){
            $this->force->addUnit("x", PAKISTANI_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "pakistani", true, "mech", $i+1);
        }

        for($i = 0; $i < 6;$i++){
            $this->force->addUnit("x", PAKISTANI_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "B", 1, 1, "pakistani", true, 'inf', $i+1);
        }

        for($i = 2; $i <= 8; $i++) {
            $this->force->addUnit("x", PAKISTANI_FORCE, "gameTurn$i", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_REINFORCE, "C", $i, 1, "pakistani", true, "mech", "T $i 1");
            $this->force->addUnit("x", PAKISTANI_FORCE, "gameTurn$i", "multiInf.png", 4, 2, 4, false, STATUS_CAN_REINFORCE, "C", $i, 1, "pakistani", true, "inf", "T $i 2");
            $this->force->addUnit("x", PAKISTANI_FORCE, "gameTurn$i", "multiInf.png", 4, 2, 4, false, STATUS_CAN_REINFORCE, "C", $i, 1, "pakistani", true, "inf", "T $i 3");
        }

        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "1");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "2");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "3");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "4");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "5");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "6");

        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 5, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "7");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "8");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 5, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "9");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "10");

        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "1");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "2");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "3");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "4");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "5");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "6");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "7");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "8");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "9");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "10");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "11");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "12");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "13");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "14");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "15");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "16");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "17");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "18");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "19");
        $this->force->addUnit("x", INDIAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "20");
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {


        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
            $this->scenario = $data->scenario;
            $this->terrainName = $data->terrainName;
            $this->victory = new Victory("TMCW/Chawinda1965/chawinda1965VictoryCore.php", $data);
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

            $this->victory = new Victory("TMCW/Chawinda1965/chawinda1965VictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->moveRules = new MoveRules($this->force, $this->terrain);

            $this->moveRules->enterZoc = 1;
            $this->moveRules->exitZoc = 2;
            $this->moveRules->noZocZocOneHex = false;
            $this->moveRules->stacking = 3;
            $this->moveRules->friendlyAllowsRetreat = true;
            $this->moveRules->blockedRetreatDamages = true;

            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->gameRules->legacyExchangeRule = false;
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);

            // game data
            $this->gameRules->setMaxTurn(8);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);

            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_MOVE_PHASE, MOVING_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, true);
        }
    }
}