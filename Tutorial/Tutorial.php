<?php
/**
 *
 * Copyright 2012-2015 David Rodal
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

require_once "crtTraits.php";
require_once "constants.php";
require_once "CombatRules.php";
require_once "crt.php";
require_once "force.php";
require_once "gameRules.php";
require_once "hexagon.php";
require_once "hexpart.php";
require_once "los.php";
require_once "mapgrid.php";
require_once "moveRules.php";
require_once "terrain.php";
require_once "display.php";
require_once "victory.php";


class Tutorial extends Battle {

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
    public $arg;
    public $argTwo;

    public $players;
    static function getHeader($name,$playerData){

        @include_once "globalHeader.php";
        @include_once "header.php";

    }
    static function getView($name, $mapUrl,$player = 0,$arg = false, $argTwo = false){
        global $force_name;
        $player = $force_name[$player];
        @include_once "view.php";
    }
    static function playAs($name, $wargame){
        @include_once "playAs.php";
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
        $data->terrainName = "terrain-".get_class($this).$this->arg;
        $data->genTerrain = $this->genTerrain;
        if($this->genTerrain){
            $data->terrain = $this->terrain;
        }
        $data->arg = $this->arg;
        $data->argTwo = $this->argTwo;
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
                $this->gameRules->processEvent(SELECT_COUNTER_EVENT, $id, $this->force->getUnitHexagon($id),$click);
                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0,$click );

        }
        return true;
    }
    function __construct($data = null, $arg = false, $argTwo = false)
    {
        $this->arg = $arg;
        $this->argTwo = $argTwo;
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->genTerrain = false;
            $this->victory = new Victory("Tutorial",$data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]),new MapViewer($data->mapViewer[1]),new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->players = $data->players;
        } else {
            $this->genTerrain = true;
            $this->victory = new Victory("Tutorial");
            $this->display = new Display();
            $this->mapData->setData(7,7 , "js/tut1.png");
//            $this->mapData->setSpecialHexes(array(404=>RED_FORCE));
            $this->mapViewer = array(new MapViewer(),new MapViewer(),new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->terrain->setMaxHex("0808");
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->players = array("","","");



            for($player = 0;$player <= 2;$player++){

                    $this->mapViewer[$player]->setData(62,80, // originX, originY
                        26.5, 26.5, // top hexagon height, bottom hexagon height
                        15, 30// hexagon edge width, hexagon center width
                    );
            }


            // game data
            $this->gameRules->setMaxTurn(2);
            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE,MOVING_MODE);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);



    if($arg){
            $this->force->addUnit("infantry-1", RED_FORCE, 501, "multiInf.png", 5, 2, 3, true, STATUS_READY, "R", 0, 1, "loyalist");
    }

            $this->force->addUnit("infantry-1", BLUE_FORCE, 101, "multiCav.png", 3, 3, 5, false, STATUS_READY, "B", 0, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 102, "multiArt.png", 4, 3, 3, false, STATUS_READY, "B", 0, 1, "rebel");
            $this->force->addUnit("infantry-1", BLUE_FORCE, 103, "multiInf.png", 6, 2, 4, false, STATUS_READY, "B", 0, 1, "rebel");


            // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
            $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
            $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
            $this->terrain->addTerrainFeature("rough", "rough", "g", 3, 0, 1, true);
            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
            $this->terrain->addTerrainFeature("newrichmond", "New Richmond", "m", 0, 0, 1, false);
            $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);
            $this->terrain->addTerrainFeature("westedge", "West Edge", "m", 0, 0, 0, false);


//            for($i = 6;$i <= 10;$i++){
//                    $this->terrain->addReinforceZone(300 + $i,"R");
//
//            }
            /*
             * First put clear everywhere, hexes and hex sides
             */
            for($col = 100; $col <= 700; $col += 100){
                for($row = 1; $row <= 7;$row++){
                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

                }
            }

            /*
             * Next put terrain like rough and forest because they are exclusive and will cancel what else is there.
             */





            $hexes = array(404);

            foreach($hexes as $hex){
                $this->terrain->addTerrain($hex, HEXAGON_CENTER, "newpaloalto");
            }

            $this->gameRules->flashMessages[] = "Movement";
            $this->gameRules->flashMessages[] = "moving your units";

            /*
             * Now put the roads and trails on top of verything else
             */



            // end terrain data ----------------------------------------

        }
    }
}