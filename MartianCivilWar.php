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
    public $mapViewer;
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
    public function resize($small,$player){
        if($small){
            $this->mapViewer[$player]->setData(44,60, // originX, originY
                20, 20, // top hexagon height, bottom hexagon height
                12, 24 // hexagon edge width, hexagon center width
            );
            $this->playerData->${player}->mapWidth = "744px";
            $this->playerData->${player}->mapHeight = "425px";
            $this->playerData->${player}->unitSize = "32px";
            $this->playerData->${player}->unitFontSize = "12px";
            $this->playerData->${player}->unitMargin = "-21px";
        }else{
            $this->mapViewer[$player]->setData(57,84, // originX, originY
                28, 28, // top hexagon height, bottom hexagon height
                16, 32 // hexagon edge width, hexagon center width
            );
            $this->playerData->${player}->mapWidth = "996px";
            $this->playerData->${player}->mapHeight = "593px";
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
                $mapGrid = new MapGrid($this->mapViewer[$playerId]);
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
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]),new MapViewer($data->mapViewer[1]),new MapViewer($data->mapViewer[2]));
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
            $this->mapData->setData(20,10);
            $this->mapViewer = array(new MapViewer(),new MapViewer(),new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = array("","","");
            $this->playerData = new stdClass();
            for($player = 0;$player <= 2;$player++){
                $this->playerData->${player} = new stdClass();
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
            $this->mapViewer[0]->setData(44,60, // originX, originY
                20, 20, // top hexagon height, bottom hexagon height
                12, 24, // hexagon edge width, hexagon center width
                2010, 2010 // max right hexagon, max bottom hexagon
            );
            $this->mapViewer[1]->setData(44,60, // originX, originY
                20, 20, // top hexagon height, bottom hexagon height
                12, 24, // hexagon edge width, hexagon center width
                2010, 2010 // max right hexagon, max bottom hexagon
            );
            $this->mapViewer[2]->setData(44,60, // originX, originY
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
                $this->force->addUnit("infantry-1", RED_FORCE, 500+$i, "multiInf.png", 2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");

            }
            for($i = 7;$i<=10;$i+=2){
                $this->force->addUnit("infantry-1", RED_FORCE, 1000+$i, "multiInf.png",2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");

            }
            $this->force->addUnit("infantry-1", RED_FORCE, 1107, "multiInf.png",2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1206, "multiInf.png",2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1405, "multiInf.png",2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1501, "multiInf.png",2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1504, "multiInf.png",2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, 1505, "multiInf.png",2, 1, 4, true, STATUS_READY, "L", 1, 1, "loyalist");

            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn2", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn4", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn5", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");
            $this->force->addUnit("infantry-1", RED_FORCE, "gameTurn5", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "L", 1, 1, "loyalist");


            $i = 1;
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "R", 1, 1, "rebel");
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
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 3, 0, 1, true);
            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
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