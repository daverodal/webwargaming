<?php
//set_include_path(dirname(__DIR__) . "/Amph" . PATH_SEPARATOR . get_include_path());

define("REBEL_FORCE", 1);
define("LOYALIST_FORCE", 2);
define("GREEN_FORCE",3);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";
$force_name[3] = "Green";

require_once "constants.php";
require_once "ModernLandBattle.php";

class Multi extends ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "multiHeader.php";
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

        $this->force->addUnit("lll", LOYALIST_FORCE, 305, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 803, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, 907, "multiHeavy.png", 10, 5, 5, false, STATUS_READY, "B", 5, 1, "loyalGuards", true, 'heavy');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1205, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1405, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');

        $this->force->addUnit("lll", LOYALIST_FORCE, 1705, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1904, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1809, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 1004, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, 604, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, 1810, "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_READY, "B", 1, 1, "loyalGuards", true, 'mountain');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn2", "multiInf.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalGuards", true, 'mountain');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "D", 3, 1, "loyalGuards", true, 'mountain');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "E", 4, 1, "loyalGuards", true, 'shock');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'heavy');

        if(!$scenario->weakerLoyalist) {
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'heavy');
        }

        /* Rebel Units */

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
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn4", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn5", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn5", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn5", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn6", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn6", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn6", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn7", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn7", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn7", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "inf");
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
            $this->specialHexA = $data->specialHexA;

        } else {
            $this->victory = new Victory("TMCW/Multi/multiVictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
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
            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, GREEN_MOVE_PHASE, MOVING_MODE, GREEN_FORCE, RED_FORCE, true);
            $this->gameRules->addPhaseChange(GREEN_MOVE_PHASE, GREEN_COMBAT_PHASE, COMBAT_SETUP_MODE, GREEN_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(GREEN_COMBAT_PHASE, GREEN_MECH_PHASE, MOVING_MODE, GREEN_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(GREEN_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
        }
    }
}