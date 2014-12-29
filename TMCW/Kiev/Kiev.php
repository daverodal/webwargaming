<?php

define("GERMAN_FORCE", 1);
define("SOVIET_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "German";
$force_name[2] = "Soviet";

require_once "constants.php";

require_once "ModernLandBattle.php";


class Kiev extends ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

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
        @include_once "kievHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 2, 0, 1, true);
        $this->terrain->addAltEntranceCost('swamp', 'mech', 3);
        parent::terrainGen($mapDoc, $terrainDoc);
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

        for($i = 0; $i < 60;$i++){
            $this->force->addUnit("xxx", SOVIET_FORCE, "deployBox", "multiInf.png", 2, 1, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "soviet", true, 'inf');
        }

        for($i = 0; $i < 18;$i++){
            $this->force->addUnit("xxx", SOVIET_FORCE, "deadpile", "multiInf.png", 2, 1, 4, true, STATUS_ELIMINATED, "A", 1, 1, "soviet", true, 'inf');
        }

        /* Second panzer army */
        /* 21 corp */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "3");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "4");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "10");

        /* 47 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "17");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "18");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "29");

        /* 48 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "9");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "16");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "mech", "25");

        /* 35 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiCav.png", 2, 1, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "1 Cav");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "95");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "262");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "293");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "296");

        /* 34 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "45");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "german", true, "inf", "134");

        /* Second Army */
        /* 13 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "17");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "260");

        /* 53 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "31");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "56");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "167");

        /* 42 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "52");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "131");

        /* army reserve */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "secondArmy", true, "inf", "112");

        /* First panzer army */
        /* 3 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "13");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "14");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "25");

        /* 14 Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "9");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "SS AH");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "SS W");

        /* 48 Corps ? */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "11");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "16");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiMech.png", 5, 2, 8, false, STATUS_CAN_DEPLOY, "E", 1, 1, "firstPanzerArmy", true, "mech", "16");

        /*  Sixth Army */
        /* 17'th Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "56");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "62");

        /* 29'th Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "44");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "111");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "299");

        /* 44'th Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "9");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "297");

        /* 55'th Corps  */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "75");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "57");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf","168");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "D", 1, 1, "sixthArmy", true, "inf", "298");


        /* Seventeenth Army */
        /* 4'th Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "24");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "71");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "262");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "295");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "296");

        /* 49'th Mountain Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "69");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "257");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiMountain.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "mountain", "1 M");

        /* 59'th Corps */
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "101 L");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "97 L");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "seventeenthArmy", true, "inf", "100 L");



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
            $this->genTerrain = false;
            $this->victory = new Victory("TMCW/Kiev/kievVictoryCore.php", $data);
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

            $this->victory = new Victory("TMCW/Kiev/kievVictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->moveRules = new MoveRules($this->force, $this->terrain);

            $this->moveRules->enterZoc = 3;
            $this->moveRules->exitZoc = 2;
            $this->moveRules->noZocZocOneHex = true;
            $this->moveRules->stacking = 3;
            $this->moveRules->friendlyAllowsRetreat = true;
            $this->moveRules->blockedRetreatDamages = true;

            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->gameRules->legacyExchangeRule = false;
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);

            // game data
            $this->gameRules->setMaxTurn(11);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);

            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, GERMAN_FORCE, SOVIET_FORCE, true);
        }
    }
}