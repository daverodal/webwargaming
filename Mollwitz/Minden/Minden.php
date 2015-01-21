<?php

define("FRENCH_FORCE", 1);
define("ANGLO_FORCE", 2);

global $force_name;
$force_name = array();
$force_name[1] = "French";
$force_name[2] = "Anglo Allied";

require_once "JagCore.php";

/* comment */



class Minden extends JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>1];
    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;
    public $angloSpecialHexes;
    public $frenchSpecialHexes;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "MindenHeader.php";

    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "French";
        $deployOne = $playerTwo = "AngloAllied";
        @include_once "view.php";
    }


    function save()
    {
        $data = new stdClass();
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;
        $data->angloSpecialHexes = $this->angloSpecialHexes;
        $data->frenchSpecialHexes = $this->frenchSpecialHexes;

        return $data;
    }

    public function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
        $specialHexes = $this->mapData->specialHexes;
        foreach($specialHexes as $hexId => $forceId){
            if($forceId == ANGLO_FORCE){
                $this->angloSpecialHexes[] = $hexId;
            }else{
                $this->frenchSpecialHexes[] = $hexId;
            }
        }
    }
    public function terrainGen($mapDoc, $terrainDoc){
        parent::terrainGen($mapDoc, $terrainDoc);

        for ($col = 2200; $col <= 2500; $col += 100) {
            for ($row = 1; $row <= 18; $row++) {
                $this->terrain->addReinforceZone($col + $row, 'B');
            }
        }

        for ($col = 100; $col <= 700; $col += 100) {
            for ($row = 1; $row <= 18; $row++) {
                $this->terrain->addReinforceZone($col + $row, 'A');
            }
        }


    }

    public function init()
    {

        $artRange = 3;

        for ($i = 0; $i < 19; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
        }
        for ($i = 0; $i < 12; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'cavalry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'cavalry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "French", false, 'artillery');
        }
        $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 5, 5, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "French", false, 'artillery');


        for ($i = 0; $i < 20; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
        }
        for ($i = 0; $i < 6; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
        }
        $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "AngloAllied", false, 'artillery');
        }

        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", ANGLO_FORCE, "deployBox", "AngArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "AngloAllied", false, 'artillery');
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->arg = $data->arg;
            $this->angloSpecialHexes = $data->angloSpecialHexes;
            $this->frenchSpecialHexes = $data->frenchSpecialHexes;
        } else {
            $this->victory = new Victory("Mollwitz/Minden/mindenVictoryCore.php");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;


            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;

            // game data
            $this->gameRules->setMaxTurn(14);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}