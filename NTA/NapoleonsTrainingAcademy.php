<?php

require_once "constants.php";
global $force_name,$phase_name,$mode_name, $event_name, $status_name, $results_name,$combatRatio_name;
$force_name[1] = "Red";
$force_name[2] = "Blue";
$phase_name = array();
$phase_name[1] = "Red Move";
$phase_name[2] = "Red Combat";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "Blue Move";
$phase_name[5] = "Blue Combat";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "Red Deploy";
$phase_name[9] = "Red Mech";
$phase_name[10] = "Red Replacement";
$phase_name[11] = "Blue Mech";
$phase_name[12] = "blue Replacement";
$phase_name[13] = "";
$phase_name[14] = "";

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

// battleforallencreek.js

// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;



class NapoleonsTrainingAcademy extends Battle {

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
        @include_once "commonHeader.php";
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
        $data->terrain = $this->terrain;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->playerData = $this->playerData;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = "terrain-NapOnMars";
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

                $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id),$click);
                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0,$click );


        }
        return true;
    }
    function __construct($data = null, $arg = false, $scenario = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->genTerrain = false;
            $this->victory = new Victory("NTA",$data);
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

            $this->genTerrain = true;
            $this->victory = new Victory("NTA");

                $this->mapData->setData(19,9,"js/centre.png");

            $this->mapData->setSpecialHexes(array(1005=>0));
            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(),new MapViewer(),new MapViewer());
            $this->force = new Force();
            $this->force->combatRequired = true;
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

                    $this->mapViewer[$player]->setData(65,85, // originX, originY
                        27.5, 27.5, // top hexagon height, bottom hexagon height
                        16, 32// hexagon edge width, hexagon center width
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
            $this->gameRules->setMaxTurn(7);
                $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE,MOVING_MODE);


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


                $this->force->addUnit("infantry-1", BLUE_FORCE, 204, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 205, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 206, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 104, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 105, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 106, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 202, "multiInf.png", 4, 4, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 208, "multiInf.png", 5, 5, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 207, "multiInf.png", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 203, "multiInf.png", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
                $this->force->addUnit("infantry-1", BLUE_FORCE, 209, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
//
//                $this->force->addUnit("infantry-1", BLUE_FORCE, 103, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "austrian");
//                $this->force->addUnit("infantry-1", BLUE_FORCE, 104, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "austrian");
//                $this->force->addUnit("infantry-1", BLUE_FORCE, 105, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "austrian");
//                $this->force->addUnit("infantry-1", BLUE_FORCE, 106, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "austrian");
//                $this->force->addUnit("infantry-1", BLUE_FORCE, 107, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "austrian");

            $this->force->addUnit("infantry-1", RED_FORCE, 1804, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
            $this->force->addUnit("infantry-1", RED_FORCE, 1805, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
            $this->force->addUnit("infantry-1", RED_FORCE, 1806, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
            $this->force->addUnit("infantry-1", RED_FORCE, 1904, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
            $this->force->addUnit("infantry-1", RED_FORCE, 1905, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
            $this->force->addUnit("infantry-1", RED_FORCE, 1906, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
            $this->force->addUnit("infantry-1", RED_FORCE, 1802, "multiInf.png", 4, 4, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
            $this->force->addUnit("infantry-1", RED_FORCE, 1808, "multiInf.png", 5, 5, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
            $this->force->addUnit("infantry-1", RED_FORCE, 1807, "multiInf.png", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
            $this->force->addUnit("infantry-1", RED_FORCE, 1803, "multiInf.png", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
            $this->force->addUnit("infantry-1", RED_FORCE, 1809, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");

//            $this->force->addUnit("infantry-1", BLUE_FORCE, 1903, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "french");
//            $this->force->addUnit("infantry-1", BLUE_FORCE, 1904, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "french");
//            $this->force->addUnit("infantry-1", BLUE_FORCE, 1905, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "french");
//            $this->force->addUnit("infantry-1", BLUE_FORCE, 1906, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "french");
//            $this->force->addUnit("infantry-1", BLUE_FORCE, 1907, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "french");
            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 3, 0, 1, true);
            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, false);
            $this->terrain->addTerrainFeature("newrichmond", "New Richmond", "m", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);



            for ($col = 100; $col <= 1900; $col += 100) {
                for ($row = 1; $row <= 9; $row++) {
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }
          // end terrain data ----------------------------------------

        }
    }
}