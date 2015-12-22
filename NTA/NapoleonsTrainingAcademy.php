<?php
use \UnitFactory;
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
global $force_name;
$force_name = array();
$force_name[0] = "unknown";
$force_name[1] = "Red";
$force_name[2] = "Blue";

global $phase_name,$mode_name, $event_name, $status_name, $results_name,$combatRatio_name;



class NapoleonsTrainingAcademy extends ModernLandBattle{

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;
    public $genTerrain;


    public $players;
    static function getHeader($name, $playerData, $arg = false){
        @include_once "globalHeader.php";
        @include_once "header.php";
    }

    static function getView($name, $mapUrl,$player = 0, $arg = false, $argTwo = false){
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = $force_name[1];
        $deployOne = $playerTwo = $force_name[2];
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
            $hexagon = "";
            if (strpos($id, "Hex")) {
                $matchId = array();
                preg_match("/^[^H]*/", $id, $matchId);
                $matchHex = array();
                preg_match("/Hex(.*)/", $id, $matchHex);
                $id = $matchId[0];
                $hexagon = $matchHex[1];
                if($event === SELECT_COUNTER_EVENT){
                    $event = SELECT_MAP_EVENT;
                }
            }
            /* fall through */

                $this->gameRules->processEvent($event, $id, $hexagon,$click);
                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0,$click );


        }
        return true;
    }

    public function init(){
        // unit data -----------------------------------------------
        //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )
        UnitFactory::$injector = $this->force;


        UnitFactory::create("infantry-1", BLUE_FORCE, 204, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 205, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 206, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "red",false,"cavalry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 104, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
        UnitFactory::create("infantry-1", BLUE_FORCE, 105, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
        UnitFactory::create("infantry-1", BLUE_FORCE, 106, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "red",false,"artillery");
        UnitFactory::create("infantry-1", BLUE_FORCE, 202, "multiInf.png", 4, 4, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 208, "multiInf.png", 5, 5, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 207, "multiInf.png", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 203, "multiInf.png", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");
        UnitFactory::create("infantry-1", BLUE_FORCE, 209, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "red",false,"infantry");


        UnitFactory::create("infantry-1", RED_FORCE, 1804, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
        UnitFactory::create("infantry-1", RED_FORCE, 1805, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
        UnitFactory::create("infantry-1", RED_FORCE, 1806, "multiCav.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "blue",false,"cavalry");
        UnitFactory::create("infantry-1", RED_FORCE, 1904, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
        UnitFactory::create("infantry-1", RED_FORCE, 1905, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
        UnitFactory::create("infantry-1", RED_FORCE, 1906, "multiArt.png", 7, 7, 3, true, STATUS_READY, "B", 1, 2, "blue",false,"artillery");
        UnitFactory::create("infantry-1", RED_FORCE, 1802, "multiInf.png", 4, 4, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
        UnitFactory::create("infantry-1", RED_FORCE, 1808, "multiInf.png", 5, 5, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
        UnitFactory::create("infantry-1", RED_FORCE, 1807, "multiInf.png", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
        UnitFactory::create("infantry-1", RED_FORCE, 1803, "multiInf.png", 7, 7, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");
        UnitFactory::create("infantry-1", RED_FORCE, 1809, "multiInf.png", 6, 6, 4, true, STATUS_READY, "B", 1, 1, "blue",false,"infantry");

    }

    function __construct($data = null, $arg = false, $scenario = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->genTerrain = false;
            $this->victory = new Victory("NTA\\victoryCore",$data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]),new MapViewer($data->mapViewer[1]),new MapViewer($data->mapViewer[2]));
            $units = $data->force->units;
            unset($data->force->units);
            $this->force = new Force($data->force);
            foreach($units as $unit){
                $this->force->injectUnit(static::buildUnit($unit));
            }
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->moveRules->stickyZOC = true;
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->players = $data->players;
        } else {

            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->genTerrain = true;
            $this->victory = new Victory("NTA\\victoryCore");

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
            $this->players = array("","","");
            for($player = 0;$player <= 2;$player++){
                $this->mapViewer[$player]->setData(65,85, // originX, originY
                    27.5, 27.5, // top hexagon height, bottom hexagon height
                    16, 32,// hexagon edge width, hexagon center width
                1070);
            }


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
        $crt = new \NTA\CombatResultsTable();
        $this->combatRules->injectCrt($crt);
    }

    /*
  * terrainGen() gets called when a map is "published" from the map editor. It's not
  * related to a game start or a game file. It just generates the terrain info that gets saved to the
  * file terrain-Gamename
  */
    function terrainGen($mapDoc, $terrainDoc)
    {
        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
        $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
        $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
        $this->terrain->addTerrainFeature("mountain", "mountain", "g", 3, 0, 2, true);
        $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
        $this->terrain->addTerrainFeature("newrichmond", "New Richmond", "m", 0, 0, 1, false);
        $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);
        $this->terrain->addTerrainFeature("westedge", "West Edge", "m", 0, 0, 0, false);
        /* handle fort's in crtTraits */
        $this->terrain->addTerrainFeature("forta", "forta", "f", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("fortb", "fortb", "f", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("mine", "mine", "m", 0, 0, 0, false);
        $this->terrain->addNatAltEntranceCost("mine", "rebel", 'mech', 2);
        $this->terrain->addNatAltEntranceCost("mine", "rebel", 'inf', 1);


        $terrainArr = json_decode($terrainDoc->hexStr->hexEncodedStr);

        $map = $mapDoc->map;
        $this->terrain->mapUrl = $mapUrl = $map->mapUrl;
        $this->terrain->maxCol = $maxCol = $map->numX;
        $this->terrain->maxRow = $maxRow = $map->numY;
        $this->terrain->mapWidth = $map->mapWidth;
        $this->mapData->setData($maxCol, $maxRow, $mapUrl);

        Hexagon::setMinMax();
        $this->terrain->setMaxHex();
        $a = $map->a;
        $b = $map->b;
        $c = $map->c;
        $this->terrain->a = $a;
        $this->terrain->b = $b;
        $this->terrain->c = $c;
        $this->terrain->originY = $b * 3 - $map->y;
        $xOff = ($a + $c) * 2 - ($c / 2 + $a);
        $this->terrain->originX = $xOff - $map->x;


        for ($col = 100; $col <= $maxCol * 100; $col += 100) {
            for ($row = 1; $row <= $maxRow; $row++) {
                $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

            }
        }
        foreach ($terrainArr as $terrain) {
            foreach ($terrain->type as $terrainType) {
                $name = $terrainType->name;
                $matches = [];
                if (preg_match("/SpecialHex/", $name)) {
                    $this->terrain->addSpecialHex($terrain->number, $name);
                } else if (preg_match("/^ReinforceZone(.*)$/", $name, $matches)) {
                    $this->terrain->addReinforceZone($terrain->number, $matches[1]);
                } else {
                    $tNum = sprintf("%04d", $terrain->number);
                    $this->terrain->addTerrain($tNum, $terrain->hexpartType, strtolower($name));
                }
            }
        }
    }
}
