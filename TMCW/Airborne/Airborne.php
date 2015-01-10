<?php

define("REBEL_FORCE", 1);
define("LOYALIST_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

require_once "constants.php";
require_once "ModernLandBattle.php";

class Airborne extends ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

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
        @include_once "airborneHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
    }
    function save()
    {
        $data = parent::save();
        $data->specialHexA = $this->specialHexA;
        return $data;
    }

    public function init()
    {

        $scenario = $this->scenario;
        $baseValue = 6;
        $reducedBaseValue = 3;
        if($scenario->weakerLoyalist){
            $baseValue = 5;
            $reducedBaseValue = 2;
        }
        if($scenario->strongerLoyalist){
            $baseValue = 7;
        }
        /* Loyalists units */

        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');

        $this->force->addUnit("x", LOYALIST_FORCE, "deployBox", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_DEPLOY, "G", 1, 1, "loyalGuards", true, 'heavy');

        $this->force->addUnit("x", LOYALIST_FORCE, "deployBox", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_DEPLOY, "G", 1, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "deployBox", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_DEPLOY, "G", 1, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "deployBox", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_DEPLOY, "G", 1, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "deployBox", "multiShock.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "G", 1, 1, "loyalGuards", true, 'shock');

        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "deployBox", "multiInf.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn2", "multiInf.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "C", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3", "multiPara.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "C", 3, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3", "multiPara.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "D", 3, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "C", 4, 1, "loyalGuards", true, 'shock');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "C", 4, 1, "loyalGuards", true, 'shock');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "E", 4, 1, "loyalGuards", true, 'shock');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "C", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "C", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "C", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "C", 5, 1, "loyalGuards", true, 'heavy');

        if(!$scenario->weakerLoyalist) {
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "C", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "C", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "C", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "C", 6, 1, "loyalGuards", true, 'heavy');
        }

        /* Rebel Units */

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");


        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiPara.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiPara.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiPara.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "para");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiPara.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiPara.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "para");

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {


        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->specialHexA = $data->specialHexA;
            $this->scenario = $data->scenario;
            $this->terrainName = $data->terrainName;
            $this->victory = new Victory("TMCW/Airborne/airborneVictoryCore.php", $data);
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

            $this->victory = new Victory("TMCW/Airborne/airborneVictoryCore.php");
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
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);


            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE,  BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
        }
    }
}