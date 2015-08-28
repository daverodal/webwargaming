<?php
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

define("REBEL_FORCE", 1);
define("LOYALIST_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "IJN";
$force_name[2] = "USN";


define("North", 0);
define("NorthEast", 1);
define("SouthEast", 2);
define("South", 3);
define("SouthWest", 4);
define("NorthWest", 5);
require_once "constants.php";
require_once "ModernNavalBattle.php";

class TinCans extends ModernNavalBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "TinCansHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = $force_name[1];
        $deployOne = $playerTwo = $force_name[2];
        @include_once "view.php";
    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
        $this->terrain->addAltEntranceCost('blocked', 'air', 0);
        $this->terrain->addAltEntranceCost('blocked', 'air', 0);


    }
    function save()
    {
        $data = parent::save();
        $data->specialHexA = $this->specialHexA;
        return $data;
    }

    public function init()
    {

        $scenario = $this->scenario;
        $baseValue = 6;
        $reducedBaseValue = 3;
        if($scenario->weakerLoyalist){
            $baseValue = 5;
            $reducedBaseValue = 2;
        }
        if($scenario->strongerLoyalist){
            $baseValue = 7;
        }
        UnitFactory::$injector = $this->force;


        UnitFactory::create("CA-1", BLUE_FORCE, 2110, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
        UnitFactory::create("CA-1", BLUE_FORCE, 2009, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
        UnitFactory::create("CA-1", BLUE_FORCE, 1909, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
        UnitFactory::create("DD-2", BLUE_FORCE, 2107, "multiInf.png", 3, 12, 2, 22, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
        UnitFactory::create("DD-2", BLUE_FORCE, 2113, "multiInf.png", 3, 12, 2, 22, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
//        UnitFactory::create("DD-1", RED_FORCE, 506, "multiInf.png", 6, 12, 4, 0,7,   STATUS_CAN_DEPLOY, "A", 1, "saudi",  "dd");
//        UnitFactory::create("DD-1", RED_FORCE, 507, "multiInf.png", 11, 12, 6, 0,7,   STATUS_CAN_DEPLOY, "A", 1, "saudi",  "cl");
//        UnitFactory::create("DD-1", RED_FORCE, 508, "multiInf.png", 6, 12, 4, 0,6,   STATUS_CAN_DEPLOY, "A", 1, "saudi",  "bc");
//
//
        UnitFactory::create("CA-1", RED_FORCE, 3221, "multiInf.png", 14, 15, 5, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
        UnitFactory::create("CA-2", RED_FORCE, 3420, "multiInf.png", 12, 15, 5, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
        UnitFactory::create("CL-3", RED_FORCE, 3321, "multiInf.png", 11, 12, 6, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
        UnitFactory::create("CL-3", RED_FORCE, 3122, "multiInf.png", 11, 12, 6, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

        UnitFactory::create("DD-5", RED_FORCE, 3719, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
        UnitFactory::create("DD-5", RED_FORCE, 3619, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
        UnitFactory::create("DD-5", RED_FORCE, 3520, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
        UnitFactory::create("DD-5", RED_FORCE, 2923, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
        UnitFactory::create("DD-5", RED_FORCE, 3022, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");




    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
            $this->specialHexA = $data->specialHexA;

        } else {
            $this->victory = new Victory("SPI/TinCans/TinCansVictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            if ($scenario && $scenario->supply === true) {
                $this->moveRules->enterZoc = 2;
                $this->moveRules->exitZoc = 1;
                $this->moveRules->noZocZocOneHex = true;
            } else {
                $this->moveRules->enterZoc = "stop";
                $this->moveRules->exitZoc = 0;
                $this->moveRules->noZocZocOneHex = false;
            }
            $this->moveRules->enterZoc = 0;
            $this->moveRules->exitZoc = 0;
            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE);
            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_TORP_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_TORP_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_SPEED_PHASE, SPEED_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_SPEED_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);


            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_TORP_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_TORP_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_SPEED_PHASE, SPEED_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_SPEED_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, true);

        }
        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            $land = $air = 0;
            if($unit->class === "air"){
                if(count((array)$mapHex->forces[$forceId]) >= 1){
                    $air = 1;
                }
                foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                    if($this->force->units[$mKey]->class === "air"){
                        $air++;
                    }
                }
                return $air > 2;
            }else{
                if(count((array)$mapHex->forces[$forceId]) >= 1){
                    $land = 1;
                }
                foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                    if($this->force->units[$mKey]->class !== "air"){
                        $land++;
                    }
                }
                return $land > 2;
            }

        };

        $this->moveRules->enemyStackingLimit = function($mapHex, $forceId, $unit){
            $land = $air = 0;
            if($unit->class === "air"){
                if(count((array)$mapHex->forces[$forceId]) >= 1) {
                    foreach ($mapHex->forces[$forceId] as $mKey => $mVal) {
                        if ($this->force->units[$mKey]->class === "air") {
                            return true;
                        }
                    }
                }
                return false;
            }else{
                if(count((array)$mapHex->forces[$forceId]) >= 1) {

                    foreach ($mapHex->forces[$forceId] as $mKey => $mVal) {
                        if ($this->force->units[$mKey]->class !== "air") {
                            return true;
                        }
                    }
                }
                return false;
            }

        };
    }
}