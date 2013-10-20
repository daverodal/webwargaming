<?php

set_include_path(__DIR__ . "/Jagersdorf". PATH_SEPARATOR . __DIR__ . "/stdIncludes" . PATH_SEPARATOR .  get_include_path());

/* comment */
require_once "constants.php";
global $force_name,$phase_name,$mode_name, $event_name, $status_name, $results_name,$combatRatio_name;
$force_name[1] = "Prussian";
$force_name[2] = "Russian";
define("PRUSSIAN_FORCE",1);
define("RUSSIAN_FORCE",2);
$phase_name = array();
$phase_name[1] = "Prussian Move";
$phase_name[2] = "Prussian Combat";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "Russian Move";
$phase_name[5] = "Russian Combat";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "Prussian Deploy";
$phase_name[9] = "Prussian Mech";
$phase_name[10] = "Prussian Replacement";
$phase_name[11] = "Russian Mech";
$phase_name[12] = "Russian Replacement";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "Russian deploy phase";


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



class Jagersdorf extends Battle {

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

    static function enterMulti(){
        echo "hi";
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
        $data->terrainName = "terrain-Jagersdorf";
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
            $this->victory = new Victory("Jagersdorf",$data);
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
            $this->victory = new Victory("Jagersdorf");

             $this->mapData->setData(21,22,"js/ColorGrossMain.jpg");

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
                if($arg == 1){
                    $this->mapViewer[$player]->setData(62,80, // originX, originY
                        26.5, 26.5, // top hexagon height, bottom hexagon height
                        15, 30// hexagon edge width, hexagon center width
                    );
                }
                if($arg == 0){
                    $this->mapViewer[$player]->setData(66,103, // originX, originY
                        27.5, 27.5, // top hexagon height, bottom hexagon height
                        16, 32// hexagon edge width, hexagon center width
                    );
                }
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

            if($arg == 0){
//
//                for($i = 1;$i<= 11;$i++){
//                    $this->force->addUnit("infantry-1", RED_FORCE, 1305+$i, "RusInf.png",2,2,3, true, STATUS_READY, "R", 1, 1, "Russian");
//
//                }
//                for($i = 1;$i<= 12;$i++){
//                    $this->force->addUnit("infantry-1", RED_FORCE, 1505+$i, "RusInf.png",2,2,3, true, STATUS_READY, "R", 1, 1, "Russian");
//
//                }
                $russianZones = array(1505,1605,1508,1706,1805,1807,1707,1607,1904,1905,1908,1909,2009,2010,2011,1912,1812,1714,1813,1914,2013,2113,1913,2114,2012,2111,2104,2105,2106,2107,2108,2109,2110,2112,2004,2005,2006,2007,2008,1906,1806,1907);
                foreach ($russianZones as $zone) {
                    $this->terrain->addReinforceZone($zone, "R");
                }
                $russianCavZones = array(1003, 1004, 1005, 1006, 1715, 1716, 1717);
                foreach ($russianCavZones as $zone) {
                    $this->terrain->addReinforceZone($zone, "RC");
                }

                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInf.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');

                $this->force->addUnit("infantry-1", RED_FORCE, 807, "RusArt.png",4, 4, 3, true, STATUS_READY, "R", 1, 2, "Russian",false,'artillery');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArt.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "R", 1, 2, "Russian",false,'artillery');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArt.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "R", 1, 2, "Russian",false,'artillery');



                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCav.png",4, 4, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCav.png",4, 4, 5, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCav.png",2, 2, 5, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCav.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');

                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCav.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCav.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCav.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');

                $this->force->addUnit("infantry-1", BLUE_FORCE, 306, "PruCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 307, "PruCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 405, "PruCav.png", 3, 3, 6, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 406, "PruCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 407, "PruCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');

                $this->force->addUnit("infantry-1", BLUE_FORCE, 412, "PruArt.png", 3, 3, 3, true, STATUS_READY, "B", 1, 2, "Prussian",false,'artillery');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 312, "PruArt.png", 3, 3, 3, true, STATUS_READY, "B", 1, 2, "Prussian",false,'artillery');

                $this->force->addUnit("infantry-1", BLUE_FORCE, 512, "PruInf.png", 5, 5, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 513, "PruInf.png", 5, 5, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 311, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 411, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 413, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 314, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 214, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 114, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 211, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 110, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 210, "PruInf.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');

                $this->force->addUnit("infantry-1", BLUE_FORCE, 115, "PruCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 215, "PruCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 316, "PruCav.png", 3, 3, 6, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
                $this->force->addUnit("infantry-1", BLUE_FORCE, 416, "PruCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');


                $i=0;
            }
            // end unit data -------------------------------------------
            if($arg == 1){
                $this->force->addUnit("infantry-1", RED_FORCE, 501, "multiInf.png", 5, 2, 3, true, STATUS_READY, "R", 1, 1, "Russian");

                $this->force->addUnit("infantry-1", BLUE_FORCE, 101, "multiCav.png", 6, 3, 5, false, STATUS_READY, "B", 1, 1, "Prussian");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 102, "multiCav.png", 5, 3, 5, false, STATUS_READY, "B", 1, 1, "Prussian");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 103, "multiCav.png", 5, 2, 5, false, STATUS_READY, "B", 1, 1, "Prussian");
            }
            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
            $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
            $this->terrain->addTerrainFeature("river", "river", "v", 0, 1,0, false);
            $this->terrain->addAltEntranceCost('forest','artillery',3);
            $this->terrain->addAltEntranceCost('forest','cavalry',3);
            $this->terrain->addReinforceZone("101","B");



//            $deployZones = array(103,104,106,107,201,202,203,204,205,206,209,210,305,306,307,309,310,406,407,408,409,410);
//            for($i = 1;$i <= 10;$i++){
//                for($j= 1; $j<=2;$j++){
//                    $this->terrain->addReinforceZone($j*100 + $i,"B");
//
//                }
//            }

            for ($col = 100; $col <= 2100; $col += 100) {
                for ($row = 1; $row <= 22; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }

            $this->terrain->addTerrain(803,HEXAGON_CENTER,'town');

            $this->terrain->addTerrain(408,HEXAGON_CENTER,'town');
            $this->terrain->addTerrain(408,LOWER_LEFT_HEXSIDE,'town');
            $this->terrain->addTerrain(309,HEXAGON_CENTER,'town');
            $this->terrain->addTerrain(2105,HEXAGON_CENTER,'town');
            $this->terrain->addTerrain(213,HEXAGON_CENTER,'town');
            $this->terrain->addTerrain(213,UPPER_LEFT_HEXSIDE,'town');
            $this->terrain->addTerrain(113,HEXAGON_CENTER,'town');
            $this->terrain->addTerrain(915,HEXAGON_CENTER,'town');
            $this->terrain->addTerrain(1015,HEXAGON_CENTER,'town');
            $this->terrain->addTerrain(1015,UPPER_LEFT_HEXSIDE,'town');
            $this->terrain->addTerrain(1916,HEXAGON_CENTER,'town');


            $this->terrain->addTerrain(807,HEXAGON_CENTER,'hill');
            $this->terrain->addTerrain(807,BOTTOM_HEXSIDE,'hill');

            $offMap = array(101,301,501,701,801,901,1001,1101,1201,1301,1302,1401,1402,1501,1502,1503,1601,1602,1603,1701,1702,1703,1801,1802,1803,1901,1902,1903,2001,2002,2003,2020,2021,2022,2101,2102,2103,2115,2116,2117,2118,2119,2120,2121,2122);
            foreach($offMap as $off){
                $this->terrain->addTerrain($off, HEXAGON_CENTER, "offmap");
            }

            $woods = array(203,302,303,304,402,403,404,503,504,120,121,122,220,221,222,320,321,322,419,420,421,422,520,521,522,808,908,909,1007,1009,1010,1108,1110,1111,1208,1209,1210,1211,1309,1310,1311,1312,1408,1409,1410,1411,1509,1510,1511,1512,1609,1610);
            foreach($woods as $woodsHex){
                $this->terrain->addTerrain($woodsHex, HEXAGON_CENTER, "forest");

            }

            $this->terrain->addTerrain(220, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(220, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(220, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(221, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(221, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(221, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(222, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(301, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(302, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(302, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(320, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(320, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(321, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(321, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(321, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(322, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(322, LOWER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(402, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(402, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(402, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(403, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(403, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(403, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(404, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(419, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(419, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(420, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(420, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(420, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(421, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(421, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(421, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(422, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(503, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(503, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(503, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(504, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(504, LOWER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(520, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(520, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(520, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(521, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(521, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(521, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(522, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(522, LOWER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(908, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(908, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(908, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(909, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1007, LOWER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1009, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1009, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1108, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1110, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1110, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1110, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1111, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1208, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1208, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1209, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1209, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1210, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1210, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1210, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1211, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1309, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1309, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1309, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1310, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1310, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1310, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1311, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1311, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1311, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1312, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1408, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1408, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1409, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1409, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1409, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1410, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1410, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1410, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1411, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1411, LOWER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1509, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1509, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1509, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1510, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1510, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1510, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1511, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1511, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1511, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1512, UPPER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1609, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1609, LOWER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1609, BOTTOM_HEXSIDE, "forest");

            $this->terrain->addTerrain(1610, UPPER_LEFT_HEXSIDE, "forest");
            $this->terrain->addTerrain(1610, LOWER_LEFT_HEXSIDE, "forest");

            $this->terrain->addTerrain(1902, BOTTOM_HEXSIDE,"road");
            $this->terrain->addTerrain(1903, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1903, LOWER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1904, UPPER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(102, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(202, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(202, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(303, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(303, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(403, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(403, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(403, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(404, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(505, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(505, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(605, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(605, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(706, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(706, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(806, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(806, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(806, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(907, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(907, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1007, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1007, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1107, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1107, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1207, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1207, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1308, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1308, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1408, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1408, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1508, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1508, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1607, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1607, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1707, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1707, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1807, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1807, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1907, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1907, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(2006, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(2006, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2006, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(2107, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(2107, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(707, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(707, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(607, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(607, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(508, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(310, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(310, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(311, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(311, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(312, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(312, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(212, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(114, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(114, BOTTOM_HEXSIDE, "road");

            $this->terrain->addTerrain(115, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(215, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(215, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(316, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(316, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(416, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(416, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(616, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(616, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(716, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(716, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(815, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(815, LOWER_LEFT_HEXSIDE,"road");


            $this->terrain->addTerrain(1404, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1505, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1505, UPPER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1605, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1605, UPPER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1706, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1706, UPPER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1805, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1805, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1906, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1906, UPPER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(2006, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(2006, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2006, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(2107, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(2107, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2108, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2108, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(2109, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2109, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(2110, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2110, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(2010, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2010, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(2011, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2011, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1912, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1912, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1812, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1812, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1813, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1813, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1914, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1914, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(2013, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2013, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(2012, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2012, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(2112, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(2112, LOWER_LEFT_HEXSIDE,"road");



            $this->terrain->addTerrain(213, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(213, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(313, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(412, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(412, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(512, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(512, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(612, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(612, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(712, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(712, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(812, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(812, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(912, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(912, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1012, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1012, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1113, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1113, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1212, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1212, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1313, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1313, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1413, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1413, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1513, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1513, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1613, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1613, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1714, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1714, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1714, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1715, LOWER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1715, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1715, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1716, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1716, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1717, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1717, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1816, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1816, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1718, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1718, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1719, LOWER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1719, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1619, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1619, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1619, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1620, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1620, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1621, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1621, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1622, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1622, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1014, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1115, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1115, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1214, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1214, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1315, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1315, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1415, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1415, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1516, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1516, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1615, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1615, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(1016, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1016, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1017, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1017, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1018, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1018, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1019, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1019, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1020, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1020, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1021, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1021, BOTTOM_HEXSIDE,"road");

            $this->terrain->addTerrain(1022, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1022, BOTTOM_HEXSIDE,"road");


            $this->terrain->addTerrain(1117, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1117, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1217, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1217, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1318, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1318, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1418, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1418, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(1519, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(1519, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(119, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(119, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(218, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(218, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(318, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(318, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(417, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(417, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(517, UPPER_LEFT_HEXSIDE,"road");
            $this->terrain->addTerrain(517, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(517, LOWER_LEFT_HEXSIDE,"road");

            $this->terrain->addTerrain(114, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(214, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(214, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(314, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(315, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(413, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(414, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(513, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(514, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(612, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(613, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(712, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(713, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(812, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(812, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(913, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(913, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1012, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1013, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1113, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1113, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1212, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1213, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1313, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1313, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1413, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1413, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1513, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1514, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1613, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1613, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1714, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1714, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1813, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1814, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1914, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1914, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(2013, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(2014, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(2114, LOWER_LEFT_HEXSIDE,"river");


            $this->terrain->addTerrain(119, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(219, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(219, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(319, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(320, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(418, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(419, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(519, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(519, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(619, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(619, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(720, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(720, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(820, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(820, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(920, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(921, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1019, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1020, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1120, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1120, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1219, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1220, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1319, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1320, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1418, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1419, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1518, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1519, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1618, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1618, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1719, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1719, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1819, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1819, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1920, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1920, BOTTOM_HEXSIDE,"river");



//            foreach($trains as $train){
//            }
//            $this->terrain->addTerrain(606, HEXAGON_CENTER, "forest");
//            $this->terrain->addTerrain(408, HEXAGON_CENTER, "rough");
//
//            $this->terrain->addTerrain(405, BOTTOM_HEXSIDE, "river");
//            $this->terrain->addTerrain(406, UPPER_LEFT_HEXSIDE, "river");
//            $this->terrain->addTerrain(406, LOWER_LEFT_HEXSIDE, "river");
//            $this->terrain->addTerrain(406, BOTTOM_HEXSIDE, "river");
//            $this->terrain->addTerrain(505, LOWER_LEFT_HEXSIDE, "river");
//            $this->terrain->addTerrain(506, UPPER_LEFT_HEXSIDE, "river");
//            $this->terrain->addTerrain(507, LOWER_LEFT_HEXSIDE, "river");
//            $this->terrain->addTerrain(508, UPPER_LEFT_HEXSIDE, "river");
//
//            $this->terrain->addTerrain(705, HEXAGON_CENTER, "town");
//            $this->terrain->addTerrain(1202, HEXAGON_CENTER, "town");
//            $this->terrain->addTerrain(1605, HEXAGON_CENTER, "newrichmond");
//            $this->terrain->addTerrain(1705, HEXAGON_CENTER, "newrichmond");
//            $this->terrain->addTerrain(1706, HEXAGON_CENTER, "newrichmond");
//
//            for($i = 1; $i <= 10;$i++){
//                $this->terrain->addTerrain(2000+$i, HEXAGON_CENTER, "eastedge");
//            }


            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType(1910,HEXAGON_CENTER);
            $hexpart->setXYwithNameAndType(2010,HEXAGON_CENTER);
            $terrain = $this->terrain;

//            $this->terrain->addTerrain(1007, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1008, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1009, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1010, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1107, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1206, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1306, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1307, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1308, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1309, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1310, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1405, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1501, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1502, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1503, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1504, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1505, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1506, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1507, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1508, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1509, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1510, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1604, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1606, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1702, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1703, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1704, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1707, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1801, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1803, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1804, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1806, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(1903, HEXAGON_CENTER, "fortified");
//            $this->terrain->addTerrain(2002, HEXAGON_CENTER, "fortified");


            // end terrain data ----------------------------------------

        }
    }
}