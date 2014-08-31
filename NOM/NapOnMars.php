<?php

define("AUSTRIAN_FORCE", 1);
define("FRENCH_FORCE", 2);

global $force_name;
$force_name = array();
$force_name[1] = "Austrian";
$force_name[2] = "French";

require_once "constants.php";
global $phase_name,$mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
require_once "crtTraits.php";
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



class NapOnMars extends Battle {

    /* @var Mapdata */
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
    public $genTerrain;


    public $players;
    static function getHeader($name, $playerData){
        $playerData = array_shift($playerData);
        foreach($playerData as $k => $v){
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "header.php";
    }
    static function playAs($name, $wargame){
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame){
        @include_once "playMulti.php";
    }

    static function getView($name, $mapUrl,$player = 0, $arg = false, $argTwo = false){

        @include_once "view.php";
    }

    function save()
    {
        $data = new stdClass();
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->terrain = $this->terrain;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->playerData = $this->playerData;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = "terrain-".get_class($this);
        $data->genTerrain = $this->genTerrain;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;

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

                $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id),$click);
                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0,$click );


        }
        return true;
    }

    public function init(){
        if($this->arg == 0){
            for($i = 1;$i<= 10;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, 800+$i, "multiCav.png", 2, 2, 5, true, STATUS_READY, "R", 1, 1, "french");
                $i++;

            }
            for($i = 8;$i<= 10;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, 1300+$i, "multiCav.png",4, 5, 5, true, STATUS_READY, "R", 1, 1, "french");

            }
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiInf.png",8, 8, 4, true, STATUS_CAN_REINFORCE, "R", 2, 1, "french");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiInf.png",10, 10, 4, true, STATUS_CAN_REINFORCE, "R", 2, 1, "french");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiInf.png",8, 8, 4, true, STATUS_CAN_REINFORCE, "R", 2, 1, "french");

            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiInf.png",8, 8, 4, true, STATUS_CAN_REINFORCE, "R", 3, 1, "french");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiInf.png",8, 8, 4, true, STATUS_CAN_REINFORCE, "R", 3, 1, "french");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiInf.png",8, 8, 4, true, STATUS_CAN_REINFORCE, "R", 3, 1, "french");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiCav.png",7, 7, 6, true, STATUS_CAN_REINFORCE, "R", 3, 1, "french");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiCav.png",6, 6, 6, true, STATUS_CAN_REINFORCE, "R", 3, 1, "french");

            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiArt.png",10, 10, 3, true, STATUS_CAN_REINFORCE, "R", 4, 2, "french");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiArt.png",12, 12, 3, true, STATUS_CAN_REINFORCE, "R", 4, 2, "french");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiArt.png",7, 7, 4, true, STATUS_CAN_REINFORCE, "R", 4, 2, "french");
            $i = 0;

            $this->force->addUnit("infantry-1", BLUE_FORCE, 202, "multiCav.png", 6, 6, 5, true, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 201, "multiCav.png", 5, 5, 5, true, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 210, "multiCav.png", 5, 5, 5, true, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 208, "multiArt.png", 6, 6, 3, true, STATUS_READY, "B", 1, 2, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 206, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 204, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 102, "multiArt.png", 10, 10, 3, true, STATUS_READY, "B", 1, 2, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 108, "multiInf.png", 8, 8, 4, true, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 207, "multiInf.png", 8, 8, 4, true, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 203, "multiInf.png", 8, 8, 4, true, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 209, "multiInf.png", 8, 8, 4, true, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 205, "multiInf.png", 8, 8, 4, true, STATUS_READY, "B", 1, 1, "austrian");
            $j = $i;
            $i=0;
            $this->force->addUnit("infantry-1", BLUE_FORCE, 103, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "russian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 104, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "russian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 105, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "russian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 106, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "russian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 107, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "russian");
        }
        // end unit data -------------------------------------------
        if($arg == 1){
            $this->force->addUnit("infantry-1", RED_FORCE, 501, "multiInf.png", 5, 2, 3, true, STATUS_READY, "R", 1, 1, "french");

            $this->force->addUnit("infantry-1", BLUE_FORCE, 101, "multiCav.png", 6, 3, 5, false, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 102, "multiCav.png", 5, 3, 5, false, STATUS_READY, "B", 1, 1, "austrian");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 103, "multiCav.png", 5, 2, 5, false, STATUS_READY, "B", 1, 1, "austrian");
        }
        // unit terrain data----------------------------------------

    }
    function __construct($data = null, $arg = false, $scenario = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->genTerrain = false;
            $this->victory = new Victory("NOM",$data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]),new MapViewer($data->mapViewer[1]),new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->moveRules->stickyZOC = true;
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
            $this->victory = new Victory("NOM");

            if($arg == 0){
                $this->mapData->setData(20,10,"js/Lourdes.png");
            }
            if($arg == 1){
                $this->mapData->setData(7,7 , "js/tut1.png");

            }
            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(),new MapViewer(),new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
//            $this->terrain->setMaxHex("2010");
            $this->moveRules = new MoveRules($this->force, $this->terrain);
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
                    $this->mapViewer[$player]->setData(65,85, // originX, originY
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
            $this->gameRules->setMaxTurn(7);
            if($arg == 0){
                $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE,MOVING_MODE);

            }
            if($arg == 1){
                $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE,DISPLAY_MODE);

            }

            if($arg == 0){
                $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            }

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

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 3, 0, 1, true);
            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, false);
            $this->terrain->addTerrainFeature("newrichmond", "New Richmond", "m", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);
            $this->terrain->addReinforceZone("101","B");
            $this->terrain->addReinforceZone("2010","R");


            $deployZones = array(103,104,106,107,201,202,203,204,205,206,209,210,305,306,307,309,310,406,407,408,409,410);
            for($i = 1;$i <= 10;$i++){
                for($j= 1; $j<=2;$j++){
                    $this->terrain->addReinforceZone($j*100 + $i,"B");

                }
            }
            if ($arg == 1) {
                for ($col = 100; $col <= 700; $col += 100) {
                    for ($row = 1; $row <= 7; $row++) {
                        $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                        $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                        $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                        $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                    }
                }
                return;
            }
            for ($col = 100; $col <= 2000; $col += 100) {
                for ($row = 1; $row <= 10; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
            $trains = array(102,201,302,401,502,601);
            foreach($trains as $train){
            }
            $this->terrain->addTerrain(606, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(408, HEXAGON_CENTER, "rough");

            $this->terrain->addTerrain(405, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(406, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(406, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(406, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(505, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(506, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(507, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(508, UPPER_LEFT_HEXSIDE, "river");

            $this->terrain->addTerrain(705, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(1202, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(1605, HEXAGON_CENTER, "newrichmond");
            $this->terrain->addTerrain(1705, HEXAGON_CENTER, "newrichmond");
            $this->terrain->addTerrain(1706, HEXAGON_CENTER, "newrichmond");

            for($i = 1; $i <= 10;$i++){
                $this->terrain->addTerrain(2000+$i, HEXAGON_CENTER, "eastedge");
            }


            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType(1910,HEXAGON_CENTER);
            $hexpart->setXYwithNameAndType(2010,HEXAGON_CENTER);
            $terrain = $this->terrain;

            $this->terrain->addTerrain(1007, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1008, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1009, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1010, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1107, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1206, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1306, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1307, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1308, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1309, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1310, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1404, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1405, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1501, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1502, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1503, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1504, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1505, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1506, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1507, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1508, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1509, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1510, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1604, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1606, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1702, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1703, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1704, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1707, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1801, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1803, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1804, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1806, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1903, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(2002, HEXAGON_CENTER, "fortified");


            // end terrain data ----------------------------------------

        }
    }
}