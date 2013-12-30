<?php

set_include_path(__DIR__ .  PATH_SEPARATOR .  get_include_path());

/* comment */
require_once "constants.php";
global $force_name,$phase_name,$mode_name, $event_name, $status_name, $results_name,$combatRatio_name;
$force_name[1] = "Prussian";
$force_name[2] = "Austrian";
define("PRUSSIAN_FORCE",1);
define("AUSTRIAN_FORCE",2);
$phase_name = array();
$phase_name[1] = "Prussian Move";
$phase_name[2] = "Prussian Combat";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "Austrian Move";
$phase_name[5] = "Austrian Combat";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "Prussian Deploy";
$phase_name[9] = "Prussian Mech";
$phase_name[10] = "Prussian Replacement";
$phase_name[11] = "Russian Mech";
$phase_name[12] = "Russian Replacement";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "Austrian deploy phase";


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
require_once "display.php";
require_once "terrain.php";
require_once "victory.php";

// battleforallenriver.js

// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;



class Mollwitz extends Battle {

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $playerData;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;
    public $display;
    public $victory;
    public $genTerrain;


    public $players;
    static function getHeader($name, $playerData){
        $playerData = array_shift($playerData);
        foreach($playerData as $k => $v){
            $$k = $v;
        }
        @include_once "commonHeader.php";
        @include_once "header.php";
    }
    static function playAs($name, $wargame){
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame){
        @include_once "playMulti.php";
    }

    static function enterMulti(){
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl,$player = 0, $arg = false, $argTwo = false){

        @include_once "view.php";
    }
    public function resize($small,$player){
        if($small){
            $this->mapViewer[$player]->setData(57,83, // originX, originY
                27.5, 27.5, // top hexagon height, bottom hexagon height
                16, 32
            );
            $this->playerData->${player}->mapWidth = "auto";
            $this->playerData->${player}->mapHeight = "auto";
            $this->playerData->${player}->unitSize = "32px";
            $this->playerData->${player}->unitFontSize = "12px";
            $this->playerData->${player}->unitMargin = "-21px";
        }else{
            $this->mapViewer[$player]->setData(57,83, // originX, originY
                27.5, 27.5, // top hexagon height, bottom hexagon height
                16, 32
            );
            $this->playerData->${player}->mapWidth = "auto";
            $this->playerData->${player}->mapHeight = "auto";
            $this->playerData->${player}->unitSize = "42px";
            $this->playerData->${player}->unitFontSize = "16px";
            $this->playerData->${player}->unitMargin = "-23px";
        }
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
        $data->playerData = $this->playerData;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = "terrain-Mollwitz";
        $data->genTerrain = $this->genTerrain;
        if($this->genTerrain){
            $data->terrain = $this->terrain;
        }
        return $data;
    }

    function poke($event, $id, $x, $y, $user, $click){
        $playerId = $this->gameRules->attackingForceId;
        if($this->players[$this->gameRules->attackingForceId] != $user){
            return false;
        }

        switch($event){
            case SELECT_MAP_EVENT:
                $mapGrid = new MapGrid($this->mapViewer[$playerId]);
                $mapGrid->setPixels($x, $y);

                $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(),$click );
                break;

            case SELECT_COUNTER_EVENT:
                /* fall through */
            case SELECT_SHIFT_COUNTER_EVENT:

                return $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id),$click);
                break;


            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0,$click );
                break;

            case KEYPRESS_EVENT:
                $this->gameRules->processEvent(KEYPRESS_EVENT, $id,null, $click);
                break;


        }
        return true;
    }
    function __construct($data = null, $arg = false, $argTwo = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->genTerrain = false;
            $this->victory = new Victory("Mollwitz",$data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]),new MapViewer($data->mapViewer[1]),new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->moveRules->stickyZOC = false;
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = $data->players;
            $this->playerData = $data->playerData;
        } else {
            $this->genTerrain = true;
            $this->victory = new Victory("Mollwitz");

             $this->mapData->setData(19,14,"js/Mollwitz2.jpg");

            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(),new MapViewer(),new MapViewer());
            $this->force = new Force();
//            $this->force->combatRequired = true;
            $this->terrain = new Terrain();
//            $this->terrain->setMaxHex("2223");
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->moveRules->exitZoc = "stop";
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = array("","","");
            $this->playerData = new stdClass();
            for($player = 0;$player <= 2;$player++){
                $this->playerData->${player} = new stdClass();
                $this->playerData->${player}->mapWidth = "auto";
                $this->playerData->${player}->mapHeight = "auto";
                $this->playerData->${player}->unitSize = "32px";
                $this->playerData->${player}->unitFontSize = "12px";
                $this->playerData->${player}->unitMargin = "-21px";
                $this->mapViewer[$player]->setData(57,45-14 + 57, // originX, originY
                    29.1, 29.1, // top hexagon height, bottom hexagon height
                    16.85, 33.7// hexagon edge width, hexagon center width
                );
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
//            $this->mapViewer[0]->setData(57,83, // originX, originY
//                27.5, 27.5, // top hexagon height, bottom hexagon height
//                16, 32// hexagon edge width, hexagon center width
//            );
//            $this->mapViewer[1]->setData(57,83, // originX, originY
//                27.5, 27.5, // top hexagon height, bottom hexagon height
//                16, 32 // hexagon edge width, hexagon center width
//            );
//            $this->mapViewer[2]->setData(57,83, // originX, originY
//                27.5, 27.5, // top hexagon height, bottom hexagon height
//                16, 32 // hexagon edge width, hexagon center width
//            );

            // game data
            $this->gameRules->setMaxTurn(12);
//            if($arg == 0){
//                $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE,MOVING_MODE);
//
//            }
//            if($arg == 1){
//
//            }
//
//            if($arg == 0){
//                $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
//            }
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE,DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE;/* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE;/* object oriented! */
            $this->force->setAttackingForceId($this->attackingForceId);/* so object oriented */



            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE,BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data
            //$this->force->setEliminationTrayXY(900);

            // unit data -----------------------------------------------
            //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )

//
//                for($i = 1;$i<= 11;$i++){
//                    $this->force->addUnit("infantry-1", RED_FORCE, 1305+$i, "RusInf.png",2,2,3, true, STATUS_READY, "R", 1, 1, "Russian");
//
//                }
//                for($i = 1;$i<= 12;$i++){
//                    $this->force->addUnit("infantry-1", RED_FORCE, 1505+$i, "RusInf.png",2,2,3, true, STATUS_READY, "R", 1, 1, "Russian");
//
//                }

                $artRange = 2;
                if($argTwo && $argTwo->longRangeArt === true){
                    $artRange = 3;
                }
                $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",5, 5, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInf.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false, 'infantry');

            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCav.png",3, 3, 5, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCav.png",3, 3, 5, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCav.png",3, 3, 5, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false,'cavalry');

            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCav.png",2, 2, 5, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCav.png",2, 2, 5, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCav.png",2, 2, 5, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCav.png",2, 2, 5, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false,'cavalry');

            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCav.png",3, 3, 6, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prusian",false,'cavalry');

            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArt.png",2, 2, 2, true, STATUS_CAN_DEPLOY, "P", 1, $artRange, "Prusian",false,'artillery');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArt.png",2, 2, 2, true, STATUS_CAN_DEPLOY, "P", 1, $artRange, "Prusian",false,'artillery');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusInf.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'infantry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusCav.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false, 'cavalry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "AusArt.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Russian",false,'artillery');

                $i=0;

            // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("road", "road", "r", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, false, true);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, false, true);
            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, false, true);
            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1,0, false);
            $this->terrain->addAltEntranceCost('forest','artillery',3);
            $this->terrain->addAltEntranceCost('forest','cavalry',3);




            for ($col = 100; $col <= 1900; $col += 100) {
                for ($row = 1; $row <= 14; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }

            $this->terrain->addReinforceZone(104,'A');
            $this->terrain->addReinforceZone(204,'A');
            $this->terrain->addReinforceZone(305,'A');
            $this->terrain->addReinforceZone(405,'A');
            $this->terrain->addReinforceZone(603,'A');
            $this->terrain->addReinforceZone(504,'A');
            $this->terrain->addReinforceZone(505,'A');
            $this->terrain->addReinforceZone(1204,'A');
            $this->terrain->addReinforceZone(1401,'A');

            $this->terrain->addReinforceZone(604,'A');
            $this->terrain->addReinforceZone(705,'A');
            $this->terrain->addReinforceZone(804,'A');
            $this->terrain->addReinforceZone(905,'A');
            $this->terrain->addReinforceZone(1004,'A');
            $this->terrain->addReinforceZone(1105,'A');
            $this->terrain->addReinforceZone(1304,'A');
            $this->terrain->addReinforceZone(1403,'A');
            $this->terrain->addReinforceZone(1402,'A');
            $this->terrain->addReinforceZone(103,'A');
            $this->terrain->addReinforceZone(203,'A');
            $this->terrain->addReinforceZone(304,'A');
            $this->terrain->addReinforceZone(404,'A');
            $this->terrain->addReinforceZone(704,'A');
            $this->terrain->addReinforceZone(803,'A');
            $this->terrain->addReinforceZone(904,'A');
            $prussianZones = [ 912, 913, 914, 1011,1012,1013 ,1111,1112, 1113,1210, 1211, 1212 ,1310, 1311, 1312 ,1409, 1410, 1411 ,1509, 1510, 1511 ,1608, 1609, 1610 ,1709, 1710, 1711 ,1809 , 1810, 1811,1910, 1911, 1912 ];




            foreach ($prussianZones as $zone) {
                $this->terrain->addReinforceZone($zone, "P");
            }



            $this->terrain->addTerrain(502 ,1 , "town");
            $this->terrain->addTerrain(602 ,1 , "town");
            $this->terrain->addTerrain(702 ,1 , "town");
            $this->terrain->addTerrain(802 ,1 , "town");
            $this->terrain->addTerrain(1502 ,1 , "town");
            $this->terrain->addTerrain(1807 ,1 , "town");
            $this->terrain->addTerrain(1908 ,1 , "town");
            $this->terrain->addTerrain(911 ,1 , "town");
            $this->terrain->addTerrain(612 ,1 , "town");
            $this->terrain->addTerrain(513 ,1 , "town");
            $this->terrain->addTerrain(701 ,1 , "road");
            $this->terrain->addTerrain(701 ,3 , "road");
            $this->terrain->addTerrain(601 ,1 , "road");
            $this->terrain->addTerrain(603 ,1 , "road");
            $this->terrain->addTerrain(603 ,3 , "road");
            $this->terrain->addTerrain(504 ,1 , "road");
            $this->terrain->addTerrain(504 ,2 , "road");
            $this->terrain->addTerrain(505 ,1 , "road");
            $this->terrain->addTerrain(505 ,2 , "road");
            $this->terrain->addTerrain(506 ,1 , "road");
            $this->terrain->addTerrain(606 ,4 , "road");
            $this->terrain->addTerrain(506 ,3 , "road");
            $this->terrain->addTerrain(506 ,3 , "river");
            $this->terrain->addTerrain(406 ,1 , "road");
            $this->terrain->addTerrain(406 ,1 , "forest");
            $this->terrain->addTerrain(406 ,2 , "road");
            $this->terrain->addTerrain(407 ,1 , "road");
            $this->terrain->addTerrain(407 ,2 , "road");
            $this->terrain->addTerrain(408 ,1 , "road");
            $this->terrain->addTerrain(408 ,2 , "road");
            $this->terrain->addTerrain(408 ,4 , "road");
            $this->terrain->addTerrain(308 ,1 , "road");
            $this->terrain->addTerrain(307 ,2 , "road");
            $this->terrain->addTerrain(307 ,1 , "road");
            $this->terrain->addTerrain(307 ,4 , "road");
            $this->terrain->addTerrain(206 ,1 , "road");
            $this->terrain->addTerrain(206 ,4 , "road");
            $this->terrain->addTerrain(106 ,1 , "road");
            $this->terrain->addTerrain(409 ,1 , "road");
            $this->terrain->addTerrain(510 ,4 , "road");
            $this->terrain->addTerrain(510 ,1 , "road");
            $this->terrain->addTerrain(510 ,2 , "road");
            $this->terrain->addTerrain(511 ,1 , "road");
            $this->terrain->addTerrain(511 ,2 , "road");
            $this->terrain->addTerrain(512 ,1 , "road");
            $this->terrain->addTerrain(514 ,1 , "road");
            $this->terrain->addTerrain(1014 ,1 , "road");
            $this->terrain->addTerrain(1013 ,2 , "road");
            $this->terrain->addTerrain(1013 ,1 , "road");
            $this->terrain->addTerrain(1013 ,1 , "forest");
            $this->terrain->addTerrain(1012 ,2 , "road");
            $this->terrain->addTerrain(1012 ,1 , "road");
            $this->terrain->addTerrain(1011 ,2 , "road");
            $this->terrain->addTerrain(1011 ,1 , "road");
            $this->terrain->addTerrain(1010 ,2 , "road");
            $this->terrain->addTerrain(1010 ,1 , "road");
            $this->terrain->addTerrain(1010 ,4 , "road");
            $this->terrain->addTerrain(910 ,1 , "road");
            $this->terrain->addTerrain(909 ,2 , "road");
            $this->terrain->addTerrain(909 ,1 , "road");
            $this->terrain->addTerrain(909 ,4 , "road");
            $this->terrain->addTerrain(808 ,1 , "road");
            $this->terrain->addTerrain(807 ,2 , "road");
            $this->terrain->addTerrain(807 ,1 , "road");
            $this->terrain->addTerrain(807 ,4 , "road");
            $this->terrain->addTerrain(707 ,1 , "road");
            $this->terrain->addTerrain(707 ,4 , "road");
            $this->terrain->addTerrain(606 ,1 , "road");
            $this->terrain->addTerrain(903 ,1 , "road");
            $this->terrain->addTerrain(1003 ,4 , "road");
            $this->terrain->addTerrain(1003 ,1 , "road");
            $this->terrain->addTerrain(1104 ,4 , "road");
            $this->terrain->addTerrain(1104 ,1 , "road");
            $this->terrain->addTerrain(1204 ,4 , "road");
            $this->terrain->addTerrain(1204 ,1 , "road");
            $this->terrain->addTerrain(1305 ,4 , "road");
            $this->terrain->addTerrain(1305 ,1 , "road");
            $this->terrain->addTerrain(1405 ,4 , "road");
            $this->terrain->addTerrain(1405 ,1 , "road");
            $this->terrain->addTerrain(1506 ,4 , "road");
            $this->terrain->addTerrain(1506 ,1 , "road");
            $this->terrain->addTerrain(1606 ,4 , "road");
            $this->terrain->addTerrain(1606 ,1 , "road");
            $this->terrain->addTerrain(1707 ,4 , "road");
            $this->terrain->addTerrain(1707 ,1 , "road");
            $this->terrain->addTerrain(1605 ,2 , "road");
            $this->terrain->addTerrain(1605 ,1 , "road");
            $this->terrain->addTerrain(1604 ,2 , "road");
            $this->terrain->addTerrain(1604 ,1 , "road");
            $this->terrain->addTerrain(1604 ,4 , "road");
            $this->terrain->addTerrain(1504 ,1 , "road");
            $this->terrain->addTerrain(1503 ,2 , "road");
            $this->terrain->addTerrain(1503 ,1 , "road");
            $this->terrain->addTerrain(1602 ,1 , "road");
            $this->terrain->addTerrain(1703 ,4 , "road");
            $this->terrain->addTerrain(1703 ,1 , "road");
            $this->terrain->addTerrain(1803 ,4 , "road");
            $this->terrain->addTerrain(1803 ,1 , "road");
            $this->terrain->addTerrain(1904 ,4 , "road");
            $this->terrain->addTerrain(1904 ,1 , "road");
            $this->terrain->addTerrain(1401 ,1 , "road");
            $this->terrain->addTerrain(1401 ,3 , "road");
            $this->terrain->addTerrain(1302 ,1 , "road");
            $this->terrain->addTerrain(1302 ,3 , "road");
            $this->terrain->addTerrain(1202 ,1 , "road");
            $this->terrain->addTerrain(1202 ,4 , "road");
            $this->terrain->addTerrain(1102 ,1 , "road");
            $this->terrain->addTerrain(1102 ,3 , "road");
            $this->terrain->addTerrain(1002 ,1 , "road");
            $this->terrain->addTerrain(205 ,1 , "forest");
            $this->terrain->addTerrain(306 ,1 , "forest");
            $this->terrain->addTerrain(507 ,1 , "forest");
            $this->terrain->addTerrain(607 ,1 , "forest");
            $this->terrain->addTerrain(709 ,1 , "forest");
            $this->terrain->addTerrain(208 ,1 , "forest");
            $this->terrain->addTerrain(209 ,1 , "forest");
            $this->terrain->addTerrain(310 ,1 , "forest");
            $this->terrain->addTerrain(311 ,1 , "forest");
            $this->terrain->addTerrain(812 ,1 , "forest");
            $this->terrain->addTerrain(912 ,1 , "forest");
            $this->terrain->addTerrain(813 ,1 , "forest");
            $this->terrain->addTerrain(914 ,1 , "forest");
            $this->terrain->addTerrain(1113 ,1 , "forest");
            $this->terrain->addTerrain(104 ,2 , "river");
            $this->terrain->addTerrain(204 ,3 , "river");
            $this->terrain->addTerrain(204 ,2 , "river");
            $this->terrain->addTerrain(305 ,3 , "river");
            $this->terrain->addTerrain(305 ,2 , "river");
            $this->terrain->addTerrain(405 ,3 , "river");
            $this->terrain->addTerrain(405 ,2 , "river");
            $this->terrain->addTerrain(506 ,2 , "river");
            $this->terrain->addTerrain(606 ,3 , "river");
            $this->terrain->addTerrain(606 ,2 , "river");
            $this->terrain->addTerrain(707 ,3 , "river");
            $this->terrain->addTerrain(708 ,4 , "river");
            $this->terrain->addTerrain(708 ,3 , "river");
            $this->terrain->addTerrain(709 ,4 , "river");
            $this->terrain->addTerrain(709 ,3 , "river");
            $this->terrain->addTerrain(709 ,2 , "river");
            $this->terrain->addTerrain(809 ,3 , "river");
            $this->terrain->addTerrain(810 ,4 , "river");
            $this->terrain->addTerrain(810 ,3 , "river");
            $this->terrain->addTerrain(810 ,3 , "road");
            $this->terrain->addTerrain(810 ,2 , "river");
            $this->terrain->addTerrain(911 ,3 , "river");
            $this->terrain->addTerrain(810 ,1 , "road");
            $this->terrain->addTerrain(711 ,1 , "road");
            $this->terrain->addTerrain(912 ,4 , "river");
            $this->terrain->addTerrain(912 ,3 , "river");
            $this->terrain->addTerrain(912 ,3 , "forest");
            $this->terrain->addTerrain(913 ,4 , "river");
            $this->terrain->addTerrain(913 ,3 , "river");
            $this->terrain->addTerrain(914 ,4 , "river");
            $this->terrain->addTerrain(914 ,3 , "river");
            $this->terrain->addTerrain(212 ,4 , "river");
            $this->terrain->addTerrain(211 ,2 , "river");
            $this->terrain->addTerrain(312 ,4 , "river");
            $this->terrain->addTerrain(311 ,3 , "river");
            $this->terrain->addTerrain(311 ,4 , "river");
            $this->terrain->addTerrain(310 ,3 , "river");
            $this->terrain->addTerrain(310 ,4 , "river");
            $this->terrain->addTerrain(310 ,4 , "forest");
            $this->terrain->addTerrain(309 ,3 , "river");
            $this->terrain->addTerrain(208 ,2 , "river");
            $this->terrain->addTerrain(208 ,2 , "forest");
            $this->terrain->addTerrain(208 ,3 , "river");
            $this->terrain->addTerrain(208 ,4 , "river");
            $this->terrain->addTerrain(207 ,3 , "river");
            $this->terrain->addTerrain(107 ,2 , "river");
            $this->terrain->addTerrain(306 ,4 , "forest");
            $this->terrain->addTerrain(406 ,4 , "forest");
            $this->terrain->addTerrain(507 ,4 , "forest");
            $this->terrain->addTerrain(607 ,4 , "forest");
            $this->terrain->addTerrain(310 ,2 , "forest");
            $this->terrain->addTerrain(812 ,2 , "forest");
            $this->terrain->addTerrain(1013 ,3 , "forest");
            $this->terrain->addTerrain(1113 ,3 , "forest");


            // end terrain data ----------------------------------------

        }
    }
}