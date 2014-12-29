<?php
set_include_path(__DIR__ . "/Manchuria1976". PATH_SEPARATOR .  get_include_path());

define("SOVIET_FORCE",1);
define("PRC_FORCE",2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Soviet";
$force_name[2] = "PRC";

require_once "constants.php";

require_once "ModernLandBattle.php";


class Manchuria1976 extends ModernLandBattle
{
    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>1];

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
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

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "Manchuria1976Header.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    static function playAs($name, $wargame, $arg = false)
    {

        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame, $arg = false){
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
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = "terrain-".get_class($this);
        $data->specialHexA = $this->specialHexA;
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

    function terrainGen($mapDoc, $terrainDoc){
        parent::TerrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 2, false);
        $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
        $this->terrain->addTerrainFeature("mountain", "mountain", "g", 1.5, 0, 2, true);
        $this->terrain->addAltEntranceCost('mountain', 'mech', 6);

    }

    public function init(){

        $scenario = $this->scenario;

        for($i = 0; $i < 30; $i++){
            $this->force->addUnit("xxxx", PRC_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 1, "prc", true, "inf");
        }
        $this->force->addUnit("xxx", PRC_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "prc", true, "mech");
        for($i = 2; $i <= 12;$i++){
            $this->force->addUnit("x", PRC_FORCE, "gameTurn$i", "multiGor.png", 1, 1, 1, true, STATUS_CAN_REINFORCE, "A", $i, 1, "prc", true, "gorilla");
            $this->force->addUnit("x", PRC_FORCE, "gameTurn$i", "multiGor.png", 1, 1, 1, true, STATUS_CAN_REINFORCE, "A", $i, 1, "prc", true, "gorilla");
            $this->force->addUnit("x", PRC_FORCE, "gameTurn$i", "multiGor.png", 1, 1, 1, true, STATUS_CAN_REINFORCE, "A", $i, 1, "prc", true, "gorilla");
        }




        for($i = 0;$i < 5;$i++){
            $this->force->addUnit("xxx", SOVIET_FORCE, "deployBox", "multiArmor.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }
        for($i = 0;$i < 10;$i++){
            $this->force->addUnit("xxx", SOVIET_FORCE, "deployBox", "multiMech.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }
        for($i = 0;$i < 15;$i++){
            $this->force->addUnit("xxx", SOVIET_FORCE, "deployBox", "multiMotInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, "mech");
        }

        for($i = 0;$i < 4;$i++){
            $this->force->addUnit("xxx", SOVIET_FORCE, "deployBox", "multiArt.png", 3, 1, 6, false, STATUS_CAN_DEPLOY, "B", 1, 2, "soviet", true, "mech");
        }
    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->specialHexA = $data->specialHexA;
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
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->genTerrain = true;
            $this->victory = new Victory("TMCW/Manchuria1976");
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
            $this->gameRules->setMaxTurn(12);


            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */




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

        }
    }
}