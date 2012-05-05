<?php
require_once "constants.php";
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
require_once "terrain.php";
// battlefforallencreek.js

// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;



class MartianCivilWar extends Battle {

    /* @var Mapdata */
    public $mapData;
    public $playerData;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;

    public $players;
    static function getHeader($playerData){
        $playerData = array_shift($playerData);
        foreach($playerData as $k => $v){
            $$k = $v;
        }
        @include_once "header.php";
    }
    static function getView(){
        @include_once "view.php";
    }
    static function playAs($wargame){
        @include_once "playAs.php";
    }
    function save()
    {
        $data = new stdClass();
        $data->mapData = $this->mapData;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->terrain = $this->terrain;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->playerData = $this->playerData;
        return $data;
    }

    function poke($event, $id, $x, $y, $user){
        echo $user;
        $playerId = $this->gameRules->attackingForceId;
        if($this->players[$this->gameRules->attackingForceId] != $user){
            echo "Nope $user";
            return "nope";
        }

        switch($event){
            case SELECT_MAP_EVENT:
                $mapGrid = new MapGrid($this->mapData[$playerId]);
                $mapGrid->setPixels($x, $y);
                echo "mapevent $x $y";
                $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon() );
                break;

            case SELECT_COUNTER_EVENT:
                echo "COUNTER $id";

                $this->gameRules->processEvent(SELECT_COUNTER_EVENT, $id, $this->force->getUnitHexagon($id));

                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0,0 );


        }

    }
    function __construct($data = null)
    {
        if ($data) {
            $this->mapData = array(new MapData($data->mapData[0]),new MapData($data->mapData[1]),new MapData($data->mapData[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $data->gameRules);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = $data->players;
            $this->playerData = $data->playerData;
        } else {
            $this->mapData = array(new MapData(),new MapData(),new MapData());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = array("","","");
            for($player = 0;$player <= 2;$player++){
            $this->playerData->${player}->mapWidth = "744px";
            $this->playerData->${player}->mapHeight = "425px";
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
            $this->mapData[0]->setData(44,60, // originX, originY
                20, 20, // top hexagon height, bottom hexagon height
                12, 24, // hexagon edge width, hexagon center width
                2010, 2010 // max right hexagon, max bottom hexagon
            );
            $this->mapData[1]->setData(44,60, // originX, originY
                20, 20, // top hexagon height, bottom hexagon height
                12, 24, // hexagon edge width, hexagon center width
                2010, 2010 // max right hexagon, max bottom hexagon
            );
            $this->mapData[2]->setData(44,60, // originX, originY
                20, 20, // top hexagon height, bottom hexagon height
                12, 24, // hexagon edge width, hexagon center width
                2010, 2010 // max right hexagon, max bottom hexagon
            );

            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
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

            for($i = 1;$i<= 10;$i+=2){
                $this->force->addUnit("infantry-1", RED_FORCE, 500+$i, "loyalInf.png", 8, 4, 4, true, STATUS_READY, "L", 1, 1, "loyalist");

            }
            for($i = 7;$i<=10;$i+=2){
                $this->force->addUnit("infantry-1", RED_FORCE, 1000+$i, "loyalInf.png",8, 4, 4, true, STATUS_READY, "L", 1, 1, "loyalist");

            }
            $this->force->addUnit("infantry-1", RED_FORCE, 1107, "loyalInf.png",8, 4, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1206, "loyalInf.png",8, 4, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1405, "loyalInf.png",8, 4, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1501, "loyalInf.png",8, 4, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1504, "loyalInf.png",8, 4, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1505, "loyalInf.png",8, 4, 4, true, STATUS_READY, "L", 1, 1, "loyalist");

            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "loyalArmor.png",15, 7, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "loyalArmor.png",15, 7, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "loyalMech.png",18, 9, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "loyalMech.png",18, 9, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "loyalMech.png",18, 9, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "loyalMech.png",18, 9, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "loyalMech.png",18, 9, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
             $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn5", "loyalArmor.png",15, 7, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn5", "loyalArmor.png",15, 7, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");


            $i = 1;
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "rebelArmor.png", 24, 12, 6, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "rebelArmor.png", 24, 12, 6, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "rebelArmor.png", 24, 12, 6, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "rebelArmor.png", 22, 1, 6, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "rebelMech.png", 16, 8, 6, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "rebelMech.png", 16, 8, 6, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "rebelMech.png", 16, 8, 6, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $j = $i;
            $i=0;
            $j=11;
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "sympthInf.png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "R", 1, 1, "sympth");

             // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, false);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 3, 0, 1, false);
            $this->terrain->addTerrainFeature("river", "Martian River", "v", 1, 0, 1, false);
            $this->terrain->addTerrainFeature("newrichmond", "New Richmond", "m", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);


            $deployZones = array(103,104,106,107,201,202,203,204,205,206,209,210,305,306,307,309,310,406,407,408,409,410);
            for($i = 1;$i <= 10;$i++){
                for($j= 1; $j<=2;$j++){
                    $this->terrain->addReinforceZone($j*100 + $i,"R");

                }
            }
            for($i = 6;$i <= 10;$i++){
                    $this->terrain->addReinforceZone(300 + $i,"R");

            }

            for($col = 100; $col <= 2000; $col += 100){
                for($row = 1; $row <= 10;$row++){
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
            $this->terrain->addTerrain(509, UPPER_LEFT_HEXSIDE, "river");

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

   /*         $this->terrain->addTerrain(105, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(204, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(205, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(305, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(305, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(404, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(405, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(503, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(504, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(504, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(505, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(505, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(603, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(603, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(604, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(604, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(704, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(705, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(705, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(706, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(805, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(805, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(904, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(906, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(906, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(907, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(907, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(908, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(908, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(908, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(1004, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1004, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(1005, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(1006, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1008, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1009, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1009, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1010, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1010, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1101, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1101, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1102, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1102, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1103, UPPER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1103, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1103, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(1203, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1203, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(1304, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1304, BOTTOM_HEXSIDE, "river");
            $this->terrain->addTerrain(1404, LOWER_LEFT_HEXSIDE, "river");
            $this->terrain->addTerrain(1404, BOTTOM_HEXSIDE, "river");

            $this->terrain->addTerrain(102, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(201, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(201, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(302, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(302, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(401, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(401, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(502, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(502, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(601, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(601, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(601, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(702, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(702, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(802, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(802, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(901, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(901, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(902, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(902, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1001, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1001, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1001, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1002, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1002, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(105, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(105, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(110, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(110, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(204, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(204, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(204, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(205, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(209, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(209, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(305, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(305, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(306, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(306, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(306, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(307, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(310, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(310, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(404, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(404, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(407, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(407, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(409, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(409, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(504, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(504, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(508, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(508, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(508, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(509, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(509, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(509, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(510, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(510, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(501, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(502, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(503, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(504, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(505, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(506, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(507, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(801, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(802, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(803, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(804, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(805, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1001, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1002, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1003, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1004, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1102, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1104, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1105, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1106, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1107, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(1203, HEXAGON_CENTER, "fortified");
            $this->terrain->addTerrain(602, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(602, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(603, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(603, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(608, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(608, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(609, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(609, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(704, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(704, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(707, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(707, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(708, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(708, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(710, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(710, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(803, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(803, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(805, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(805, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(806, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(806, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(809, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(809, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(809, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(810, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(810, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(903, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(903, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(904, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(904, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(905, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(905, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(909, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(909, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1003, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1003, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1003, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1008, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1008, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1102, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1102, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1103, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1103, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1103, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1103, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1104, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1104, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1105, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1105, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1106, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1106, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1107, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1107, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1108, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1108, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1201, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1201, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1202, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1202, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1203, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1203, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1203, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1204, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1301, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1301, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1302, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1302, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1303, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1303, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1304, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1304, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1305, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1305, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1401, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1401, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1402, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1402, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1403, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1403, LOWER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1405, HEXAGON_CENTER, "road");
            $this->terrain->addTerrain(1405, UPPER_LEFT_HEXSIDE, "road");
            $this->terrain->addTerrain(1405, BOTTOM_HEXSIDE, "road");
            $this->terrain->addTerrain(1406, HEXAGON_CENTER, "road");

            $this->terrain->addTerrain(201, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(202, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(207, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(208, HEXAGON_CENTER, "forest");

            $this->terrain->addTerrain(301, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(308, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(401, HEXAGON_CENTER, "forest");


            $this->terrain->addTerrain(405, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(408, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(409, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(510, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(604, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(701, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(702, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(703, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(704, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(708, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(806, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(901, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(902, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(905, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(906, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(1201, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(1303, HEXAGON_CENTER, "forest");
            $this->terrain->addTerrain(1404, HEXAGON_CENTER, "forest");*/



            /*
          $this->terrain->addTerrain("0100", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0300", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0300", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0500", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0101", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0200", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0301", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0400", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0501", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0600", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0101", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0200", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0301", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0301", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0400", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0400", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0501", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0101", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0201", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0301", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0401", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0401", UPPER_LEFT_HEXSIDE, "river");
          $this->terrain->addTerrain("0501", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0601", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0101", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0201", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0201", HEXAGON_CENTER, "forest");
          $this->terrain->addTerrain("0301", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0301", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0401", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0401", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0501", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0102", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0201", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0201", LOWER_LEFT_HEXSIDE, "forest");
          $this->terrain->addTerrain("0302", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0401", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0401", LOWER_LEFT_HEXSIDE, "river");
          $this->terrain->addTerrain("0502", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0601", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0102", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0102", HEXAGON_CENTER, "forest");
          $this->terrain->addTerrain("0201", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0201", BOTTOM_HEXSIDE, "forest");
          $this->terrain->addTerrain("0302", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0302", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0401", BOTTOM_HEXSIDE, "rough");
          $this->terrain->addTerrain("0401", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0502", HEXAGON_CENTER, "rough");
          $this->terrain->addTerrain("0102", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0202", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0202", UPPER_LEFT_HEXSIDE, "forest");
          $this->terrain->addTerrain("0302", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0402", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0402", UPPER_LEFT_HEXSIDE, "river");
          $this->terrain->addTerrain("0502", LOWER_LEFT_HEXSIDE, "rough");
          $this->terrain->addTerrain("0602", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0102", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0102", BOTTOM_HEXSIDE, "forest");
          $this->terrain->addTerrain("0202", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0202", HEXAGON_CENTER, "forest");
          $this->terrain->addTerrain("0302", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0302", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0402", HEXAGON_CENTER, "rough");
          $this->terrain->addTerrain("0402", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0502", BOTTOM_HEXSIDE, "rough");
          $this->terrain->addTerrain("0103", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0202", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0202", LOWER_LEFT_HEXSIDE, "forest");
          $this->terrain->addTerrain("0303", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0402", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0402", LOWER_LEFT_HEXSIDE, "river");
          $this->terrain->addTerrain("0503", UPPER_LEFT_HEXSIDE, "rough");
          $this->terrain->addTerrain("0503", UPPER_LEFT_HEXSIDE, "road");
          $this->terrain->addTerrain("0602", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0103", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0103", HEXAGON_CENTER, "forest");
          $this->terrain->addTerrain("0202", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0202", BOTTOM_HEXSIDE, "forest");
          $this->terrain->addTerrain("0303", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0303", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0402", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0503", HEXAGON_CENTER, "rough");
          $this->terrain->addTerrain("0503", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0103", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0203", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0203", UPPER_LEFT_HEXSIDE, "forest");
          $this->terrain->addTerrain("0303", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0403", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0403", UPPER_LEFT_HEXSIDE, "road");
          $this->terrain->addTerrain("0403", UPPER_LEFT_HEXSIDE, "river");
          $this->terrain->addTerrain("0503", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0603", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0103", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0103", BOTTOM_HEXSIDE, "forest");
          $this->terrain->addTerrain("0203", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0203", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0203", HEXAGON_CENTER, "forest");
          $this->terrain->addTerrain("0303", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0403", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0403", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0403", HEXAGON_CENTER, "town");
          $this->terrain->addTerrain("0503", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0503", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0104", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0203", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0203", LOWER_LEFT_HEXSIDE, "forest");
          $this->terrain->addTerrain("0304", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0304", UPPER_LEFT_HEXSIDE, "road");
          $this->terrain->addTerrain("0403", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0403", LOWER_LEFT_HEXSIDE, "road");
          $this->terrain->addTerrain("0403", LOWER_LEFT_HEXSIDE, "river");
          $this->terrain->addTerrain("0504", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0603", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0104", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0104", HEXAGON_CENTER, "forest");
          $this->terrain->addTerrain("0203", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0203", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0203", BOTTOM_HEXSIDE, "forest");
          $this->terrain->addTerrain("0304", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0304", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0403", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0403", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0403", BOTTOM_HEXSIDE, "river");
          $this->terrain->addTerrain("0504", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0504", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0104", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0204", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0204", UPPER_LEFT_HEXSIDE, "forest");
          $this->terrain->addTerrain("0304", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0404", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0504", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0504", LOWER_LEFT_HEXSIDE, "river");
          $this->terrain->addTerrain("0604", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0104", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0204", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0204", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0204", HEXAGON_CENTER, "forest");
          $this->terrain->addTerrain("0304", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0404", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0404", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0404", HEXAGON_CENTER, "town");
          $this->terrain->addTerrain("0504", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0504", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0105", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0105", UPPER_LEFT_HEXSIDE, "road");
          $this->terrain->addTerrain("0204", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0204", LOWER_LEFT_HEXSIDE, "road");
          $this->terrain->addTerrain("0305", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0404", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0505", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0505", UPPER_LEFT_HEXSIDE, "river");
          $this->terrain->addTerrain("0604", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0105", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0105", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0204", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0305", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0404", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0404", BOTTOM_HEXSIDE, "road");
          $this->terrain->addTerrain("0505", HEXAGON_CENTER, "clear");
          $this->terrain->addTerrain("0505", HEXAGON_CENTER, "road");
          $this->terrain->addTerrain("0105", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0205", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0305", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0405", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0505", LOWER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0605", UPPER_LEFT_HEXSIDE, "clear");
          $this->terrain->addTerrain("0605", UPPER_LEFT_HEXSIDE, "road");
          $this->terrain->addTerrain("0105", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0305", BOTTOM_HEXSIDE, "clear");
          $this->terrain->addTerrain("0505", BOTTOM_HEXSIDE, "clear");*/

            // end terrain data ----------------------------------------

        }
    }
}