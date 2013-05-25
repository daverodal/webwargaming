<?php
require_once "constants.php";
global $force_name,$phase_name,$mode_name, $event_name, $status_name, $results_name,$combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

$phase_name = array();
$phase_name[1] = "Rebel Move";
$phase_name[2] = "Rebel Combat";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "Loyalist Move";
$phase_name[5] = "Loyalist Combat";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "Rebel Deploy";
$phase_name[9] = "Rebel Mech";
$phase_name[10] = "Rebel Replacement";
$phase_name[11] = "Loyalist Mech";
$phase_name[12] = "Loyalist Replacement";
$phase_name[13] = "";
$phase_name[14] = "";

require_once "combatRules.php";
require_once "TMCW/crt.php";
require_once "force.php";
require_once "gameRules.php";
require_once "hexagon.php";
require_once "hexpart.php";
require_once "los.php";
require_once "mapgrid.php";
require_once "moveRules.php";
require_once "prompt.php";
require_once "terrain.php";
require_once "display.php";
require_once "victory.php";
// battlefforallencreek.js

// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class MartianCivilWar extends Battle {

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
    public $genTerrain = false;

    public $players;
    static function getHeader($name,$playerData){
        global $force_name;

        $playerData = array_shift($playerData);
        foreach($playerData as $k => $v){
            $$k = $v;
        }
        @include_once "commonHeader.php";
        @include_once "TMCW/header.php";
    }
    static function getView($mapUrl,$player = 0, $player = 0, $arg = false){
        global $force_name;
//        $player = $force_name[$player];
        @include_once "TMCW/view.php";
    }
    static function playAs($name, $wargame){

        @include_once "TMCW/playAs.php";
    }
    public function resize($small,$player){
        if($small){
            $this->mapViewer[$player]->setData(60,76, // originX, originY
                25, 25, // top hexagon height, bottom hexagon height
                15, 30 // hexagon edge width, hexagon center width
            );
            $this->playerData->${player}->mapWidth = "auto";
            $this->playerData->${player}->mapHeight = "auto";
            $this->playerData->${player}->unitSize = "32px";
            $this->playerData->${player}->unitFontSize = "12px";
            $this->playerData->${player}->unitMargin = "-21px";
        }else{
            $this->mapViewer[$player]->setData(60,76, // originX, originY
                25, 25, // top hexagon height, bottom hexagon height
                15, 30 // hexagon edge width, hexagon center width
            );
            $this->playerData->${player}->mapWidth = "auto";
            $this->playerData->${player}->mapHeight = "auto";
            $this->playerData->${player}->unitSize = "40px";
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
        $data->terrainName = "terrain-MartianCivilWar";
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
                return $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(), $click );
                break;

            case SELECT_COUNTER_EVENT:
                return $this->gameRules->processEvent(SELECT_COUNTER_EVENT, $id, $this->force->getUnitHexagon($id),$click);

                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0,$click );

        }
        return true;
    }
    function __construct($data = null)
    {

        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->genTerrain = false;
            $this->victory = new Victory("TMCW",$data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]),new MapViewer($data->mapViewer[1]),new MapViewer($data->mapViewer[2]));
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
            $this->genTerrain = true;
            $this->victory = new Victory("TMCW");
            $this->display = new Display();
            $this->mapData->setData(30,20,"js/Martian.png");
            $this->mapData->setSpecialHexes(array(407=>RED_FORCE,1909=>RED_FORCE,1515=>RED_FORCE,516=>RED_FORCE,2414=>RED_FORCE,2415=>RED_FORCE,2515=>RED_FORCE,1508=>RED_FORCE));
            $this->mapViewer = array(new MapViewer(),new MapViewer(),new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->terrain->setMaxHex("3020");
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
            $this->mapViewer[0]->setData(60,76, // originX, originY
                25, 25, // top hexagon height, bottom hexagon height
                15, 30, // hexagon edge width, hexagon center width
                3020, 3020 // max right hexagon, max bottom hexagon
            );
            $this->mapViewer[1]->setData(60,76, // originX, originY
                25, 25, // top hexagon height, bottom hexagon height
                15, 30, // hexagon edge width, hexagon center width
                3020, 3020 // max right hexagon, max bottom hexagon
            );
            $this->mapViewer[2]->setData(60,76, // originX, originY
                25, 25, // top hexagon height, bottom hexagon height
                15, 30, // hexagon edge width, hexagon center width
                3020, 3020 // max right hexagon, max bottom hexagon
            );

            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE,MOVING_MODE);
//            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE,RED_REPLACEMENT_PHASE , REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE , MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE,BLUE_REPLACEMENT_PHASE, REPLACING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data
            //$this->force->setEliminationTrayXY(900);

            // unit data -----------------------------------------------
            //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )
            $this->force->addUnit("infantry-1", RED_FORCE, 407, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 516, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1515, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1612, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1316, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 2207, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 2210, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 208, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 508, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 512, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1909, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 914, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");

//
//            for($i = 1;$i<= 10;$i+=2){
//                $this->force->addUnit("infantry-1", RED_FORCE, 500+$i, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
//
//            }

            for($i = 7;$i<=10;$i+=2){
                $this->force->addUnit("infantry-1", RED_FORCE, 1000+$i, "multiInf.png",2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");

            }

            $this->force->addUnit("infantry-1", RED_FORCE, 2415, "multiMech.png",5, 2, 9, false, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 2416, "multiMech.png",5, 2, 9, false, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 2417, "multiMech.png",5, 2, 9, false, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 2515, "multiMech.png",9, 4, 6, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 2516, "multiArmor.png",7, 3, 6, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 2517, "multiArmor.png",7, 3, 6, true, STATUS_READY, "L", 1, 1, "loyalist");

            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 2, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 2, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 3, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 3, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 3, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 4, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 4, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn5", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 5, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn5", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 5, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 2, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 2, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 3, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 3, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 3, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 4, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 4, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn5", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 5, 1, "loyalist");
//            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn5", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 5, 1, "loyalist");


            $i = 1;
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $j = $i;
            $i=0;
            $j=11;
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");

             // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("trail", "trail", "r", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 3, 0, 2, true);
            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
            $this->terrain->addTerrainFeature("newrichmond", "New Richmond", "m", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("westedge", "West Edge", "m", 0, 0, 0, false);


            $deployZones = array(103,104,106,107,201,202,203,204,205,206,209,210,305,306,307,309,310,406,407,408,409,410);
            for($i = 1;$i <= 1;$i++){
                for($j= 1; $j<=10;$j++){
                    $this->terrain->addReinforceZone($j*100 + $i,"R");

                }
            }
//            for($i = 6;$i <= 10;$i++){
//                    $this->terrain->addReinforceZone(300 + $i,"R");
//
//            }
                $loyalReinf = array(3016,3017,3018,3019,3020,2320,2420,2520,2620,2720,2820,2920);
                foreach($loyalReinf as $zone){
                    $this->terrain->addReinforceZone($zone,"L");

                }
            /*
             * First put clear everywhere, hexes and hex sides
             */
            for($col = 100; $col <= 3000; $col += 100){
                for($row = 1; $row <= 20;$row++){
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }

            /*
             * Next put terrain like rough and forest because they are exclusive and will cancel what else is there.
             */
            $hexes = array(907,908,909,910,1006,1007,1010,1106,1107,1108,1111,1205,1207,1210,
                1305,1307,1311,1406,1410,1506,1507,1510,1511,1607,1609,1707,1708,1709);

            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "rough");
            }

            $hexes = array(219,220,319,418,517,518,615,616,715,
                1008,1009,1109,1110,1208,1209,1308,1309,1310,1407,1408,1409,1508,1509,1608,
                1804,1903,1904,2002,2003,2102,2201,2202,2301,2302);


            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "forest");
            }

            $hexes = array("0407","0516",1515,1909);

            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "town");
            }
            $hexes = array(2414,2415,2515);

            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "newrichmond");
            }

            for($i = 3001;$i <= 3020;$i++){
                $this->terrain->addTerrain($i, HEXAGON_CENTER, "eastedge");

            }

            for($i = 101;$i <= 120;$i++){
                $this->terrain->addTerrain($i, HEXAGON_CENTER, "westedge");

            }

            $this->terrain->addTerrain(813, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(814, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(814, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(814, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(815, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(815, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(815, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(714, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(715, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(614, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(615, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(615, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(616, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(516, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(516, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(516, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(515, LOWER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(414, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(414, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(414, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(416, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(417, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(317, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(318, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(217, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(218, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(118, BOTTOM_HEXSIDE,"river");



            $this->terrain->addTerrain(911, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(911, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(912, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(912, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(913, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(913, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(914, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(914, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(915, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(915, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(916, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1011, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1012, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1012, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1013, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1013, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1014, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1014, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1112, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1112, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1113, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1113, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1114, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1114, LOWER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1112, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1404, BOTTOM_HEXSIDE,"river");

            $this->terrain->addTerrain(1504, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1505, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1603, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1604, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1604, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1605, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1703, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1704, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1704, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1705, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1802, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1803, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1803, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1804, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(1902, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(1903, UPPER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1903, LOWER_LEFT_HEXSIDE,"river");
            $this->terrain->addTerrain(1904, UPPER_LEFT_HEXSIDE,"river");


            $this->terrain->addTerrain(2001, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(2002, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(2101, BOTTOM_HEXSIDE,"river");
            $this->terrain->addTerrain(2102, UPPER_LEFT_HEXSIDE,"river");

            $this->terrain->addTerrain(2201, UPPER_LEFT_HEXSIDE,"river");

            /*
             * Now put the roads and trails on top of verything else
             */
            for($i = 401;$i <= 410;$i++){
                $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");

            }
            $this->terrain->addTerrain(411, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(512, UPPER_LEFT_HEXSIDE, "road");

            for($i = 512;$i <= 515;$i++){
                $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");

            }

            $this->terrain->addTerrain(516, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(516, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(416, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(416, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(317, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(317, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(217, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(217, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(118, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(118, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(119, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(119, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(120, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(120, BOTTOM_HEXSIDE, "road");


            $hexes = array(612,713,813,914,1014,1115,1215,1316);
            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($hex, UPPER_LEFT_HEXSIDE, "road");

            }
            $hexes = array(1415,1515,1614,1714,1813,1913,2012,2615);
            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($hex, LOWER_LEFT_HEXSIDE, "road");

            }

            for($i = 2401;$i <= 2405;$i++){
                $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");

            }
            $hexes = array(2406,2307,2207,2108,2008,1909,1809,1710,
            2516,2413);
            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($hex, LOWER_LEFT_HEXSIDE, "road");
            }
            for($i = 1610;$i <= 1614;$i++){
                $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");

            }

            for($i = 2411;$i <= 2413;$i++){
                $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");

            }

            $hexes = array(2009,2110,2210,2311,2411,2113,2213,2214,2414,2515,2616,2716,2816,2917,3017
            ,2314,2514,2614,2416,2615);
            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($hex, UPPER_LEFT_HEXSIDE, "road");
            }
            $this->terrain->addTerrain(2614, BOTTOM_HEXSIDE, "road");

            for($i = 2314;$i <= 2315;$i++){
                $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
                $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");

            }
            $this->terrain->addTerrain(2316, HEXAGON_CENTER, "road");

            $hexes = array(505,605,706,806,907,1007,1108,1208,1309,1409,1808,1909);
            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "trail");
                $this->terrain->addTerrain($hex, UPPER_LEFT_HEXSIDE, "trail");

            }
            $hexes = array(1509,1608,1708);
            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "trail");
                $this->terrain->addTerrain($hex, LOWER_LEFT_HEXSIDE, "trail");

            }


            // end terrain data ----------------------------------------

        }
    }
}