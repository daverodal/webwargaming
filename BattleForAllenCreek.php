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
// battleforallencreek.js

// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;



class BattleForAllenCreek {

    /* @var Mapdata */
    public $mapData;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;

    function save()
    {
        $data = new stdClass();
        $data->mapData = $this->mapData;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->terrain = $this->terrain;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        return $data;
    }

    function __construct($data = null)
    {
        if ($data) {
            $this->mapData = new MapData($data->mapData);
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $data->gameRules);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
        } else {
            $this->mapData = new MapData();
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);


            // mapData
/*            $this->mapData->setData(88,117, // originX, originY
                40, 40, // top hexagon height, bottom hexagon height
                24, 48, // hexagon edge width, hexagon center width
                1410, 1410 // max right hexagon, max bottom hexagon
            );*/
            $this->mapData->setData(66,87, // originX, originY
                30, 30, // top hexagon height, bottom hexagon height
                18, 36, // hexagon edge width, hexagon center width
                1410, 1410 // max right hexagon, max bottom hexagon
            );
 /*           $this->mapData->setData(44,58, // originX, originY
                20, 20, // top hexagon height, bottom hexagon height
                12, 24, // hexagon edge width, hexagon center width
                1410, 1410 // max right hexagon, max bottom hexagon
            );*/

            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_PANZER_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_PANZER_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE,RED_REPLACEMENT_PHASE , REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_RAILROAD_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_RAILROAD_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MOVE_PHASE , MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE,BLUE_PANZER_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data
            //$this->force->setEliminationTrayXY(900);

            // unit data -----------------------------------------------
            //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )
            for($i = 1;$i<= 4;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, 300+$i, "rusInf8->png", 8, 4, 4, false, STATUS_READY, "R", 1);

            }
            for($i = 4;$i<= 10;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, 500+$i, "rusInf8->png",8, 4, 4, true, STATUS_READY, "R", 1);

            }
            $this->force->addUnit("infantry-1", RED_FORCE, 803, "rusInf8->png",8, 4, 4, false, STATUS_READY, "R", 1);
            $this->force->addUnit("infantry-1", RED_FORCE, 405, "rusInf8->png",8, 4, 4, true, STATUS_READY, "R", 1);
            $this->force->addUnit("infantry-1", RED_FORCE, 400, "rusInf8->png",8, 4, 4, true, STATUS_ELIMINATED, "R", 1);
            $this->force->addUnit("infantry-1", RED_FORCE, 500, "rusInf8->png",8, 4, 4, true, STATUS_ELIMINATED, "R", 1);
            $this->force->addUnit("infantry-1", RED_FORCE, 600, "rusInf8->png",8, 4, 4, true, STATUS_ELIMINATED, "R", 1);


            $i = 0;
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 12, 6, 6, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 12, 6, 6, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 12, 6, 6, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 10, 5, 6, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 9, 4, 6, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 9, 4, 6, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 8, 4, 6, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 8, 4, 6, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 8, 4, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 8, 4, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 7, 4, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 7, 4, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 7, 4, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 6, 3, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 5, 2, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 4, 2, 4, false, STATUS_CAN_REINFORCE, "B", 1);
            $this->force->addUnit("infantry-1", BLUE_FORCE, 0+$i++, "gerInf8->png", 4, 2, 4, false, STATUS_CAN_REINFORCE, "B", 1);

             // end unit data -------------------------------------------

            // unit terrain data----------------------------------------
            $this->terrain->addTown("Marysville", 403);

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, false);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 2, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 2, false);
            $this->terrain->addTerrainFeature("river", "Allen Creek", "v", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("moscow", "Moscow", "m", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);

            $this->terrain->addReinforceZone(501, "R");
        /*    $this->terrain->addReinforceZone(103, "B");
            $this->terrain->addReinforceZone(104, "B");
            $this->terrain->addReinforceZone(105, "B");*/
            for($i = 1;$i <= 10;$i++){
                $this->terrain->addReinforceZone(100+$i,"B");
            }
            $deployZones = array(103,104,106,107,201,202,203,204,205,206,209,210,305,306,307,309,310,406,407,408,409,410);
            foreach($deployZones as $zone){
                $this->terrain->addReinforceZone($zone,"B");
            }
            for($col = 100; $col <= 1400; $col += 100){
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
            $this->terrain->addTerrain(204, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(307, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(509, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(601, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(603, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(803, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(805, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(809, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(1103, HEXAGON_CENTER, "moscow");
            $this->terrain->addTerrain(1107, HEXAGON_CENTER, "town");
            $this->terrain->addTerrain(1405, HEXAGON_CENTER, "town");

            for($i = 1; $i <= 10;$i++){
                $this->terrain->addTerrain(1400+$i, HEXAGON_CENTER, "eastedge");
            }


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
            $this->terrain->addTerrain($train, HEXAGON_CENTER, "road");
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