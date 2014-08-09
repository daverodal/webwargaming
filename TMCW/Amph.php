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

require_once "ModernLandBattle.php";


class Amph extends ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

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

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "amphHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    function terrainGen($hexDocId)
    {
        parent::terrainGen($hexDocId);
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
        $data->playerData = $this->playerData;
        $data->display = $this->display;
        $data->specialHexA = $this->specialHexA;
        $data->victory = $this->victory->save();
        $data->terrainName = "terrain-" . get_class($this);
        $data->genTerrain = $this->genTerrain;
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
    }

    public function init()
    {

        $scenario = $this->scenario;

        $this->force->addUnit("lll", LOYALIST_FORCE, 305, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 803, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, 907, "multiHeavy.png", 10, 5, 5, false, STATUS_READY, "B", 5, 1, "loyalGuards", true, 'heavy');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1205, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1405, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');

        $this->force->addUnit("lll", LOYALIST_FORCE, 1705, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1904, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1809, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1004, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 604, "multiGor.png", 5, 2, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, 1810, "multiInf.png", 6, 3, 5, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn2", "multiInf.png", 4, 2, 5, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3", "multiPara.png", 6, 3, 5, false, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3", "multiPara.png", 6, 3, 5, false, STATUS_CAN_REINFORCE, "D", 3, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "E", 4, 1, "loyalGuards", true, 'shock');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'heavy');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'heavy');


        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 8, 4, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 8, 4, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "rebel", true, "para");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiPara.png", 8, 4, 5, false, STATUS_CAN_REINFORCE, "C", 2, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn4", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn4", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn5", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn5", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {


        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->specialHexA = $data->specialHexA;
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

            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
        }
    }
}