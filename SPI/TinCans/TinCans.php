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
        if($scenario->one){
            $force_name[1] = "RN";
            $force_name[2] = "IJN";
        }
        if($scenario->eight){
            $force_name[1] = "USN";
            $force_name[2] = "IJN";
        }
        $player = $force_name[$player];
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = $force_name[1];
        $deployOne = $playerTwo = $force_name[2];
        @include_once "view.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        global $force_name;
        $scenario = $arg;
        if($arg === "one"){
            $force_name[1] = "RN";
            $force_name[2] = "IJN";
        }
        if($arg === "eight"){
            $force_name[1] = "USN";
            $force_name[2] = "IJN";
        }

        $deployTwo = $playerOne = $force_name[1];
        $deployOne = $playerTwo = $force_name[2];
        @include_once "playMulti.php";
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

        if($scenario->one){
            UnitFactory::create("BB-5", BLUE_FORCE, 4404, "multiInf.png", 30, 17, 23, 0, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "bb");
            UnitFactory::create("BC-w", BLUE_FORCE, 4505, "multiInf.png", 22, 15, 17, 0, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "bc");
            UnitFactory::create("DD-5", BLUE_FORCE, 4708, "multiInf.png", 2, 8, 2, 5, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "dd");
            UnitFactory::create("DD-5", BLUE_FORCE, 4808, "multiInf.png", 2, 8, 2, 5, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "dd");
            UnitFactory::create("DD-5", BLUE_FORCE, 4909, "multiInf.png", 2, 8, 2, 5, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "rn",  "dd");

            UnitFactory::create("BC-2", RED_FORCE, 716, "multiInf.png", 23, 20, 18, 0, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "bc");
            UnitFactory::create("BC-2", RED_FORCE, 717, "multiInf.png", 23, 20, 18, 0, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "bc");

            UnitFactory::create("DD-2", RED_FORCE, 710, "multiInf.png", 3, 12, 2, 22, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-2", RED_FORCE, 1209, "multiInf.png", 3, 12, 2, 22, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-2", RED_FORCE, 1022, "multiInf.png", 3, 12, 2, 22, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-2", RED_FORCE, 316, "multiInf.png", 3, 12, 2, 22, 2, North,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

        }

        if($scenario->two){
            /* IJN */
            UnitFactory::create("CA-1", BLUE_FORCE, 5326, "multiInf.png", 9, 14, 5, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 5624, "multiInf.png", 9, 14, 5, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 5525, "multiInf.png", 9, 14, 5, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 5425, "multiInf.png", 9, 14, 5, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");

            UnitFactory::create("CA-2", BLUE_FORCE, 5724, "multiInf.png", 14, 14, 7, 39, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");

            UnitFactory::create("CL-1", BLUE_FORCE, 5226, "multiInf.png", 3, 12, 2, 15, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("CL-3", BLUE_FORCE, 5127, "multiInf.png", 5, 12, 2, 10, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-1", BLUE_FORCE, 5027, "multiInf.png", 1, 12, 2, 20, 6, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            /* USN Groups 1 */

            UnitFactory::create("CA-4", RED_FORCE, 6015, "multiInf.png", 12, 14, 6, 12, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("CA-2", RED_FORCE, 6115, "multiInf.png", 13, 15, 5, 0, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-3", RED_FORCE, 6017, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 6113, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

            /* USN Group 2 */

            UnitFactory::create("CA-2", RED_FORCE, 4206, "multiInf.png", 13, 15, 5, 0, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CA-2", RED_FORCE, 4306, "multiInf.png", 13, 15, 5, 0, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CA-2", RED_FORCE, 4405, "multiInf.png", 13, 15, 5, 0, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-3", RED_FORCE, 4004, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 4407, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");


            UnitFactory::create("DD-3", RED_FORCE, 6119, "multiInf.png", 2, 8, 2, 16, 2, SouthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

        }

        if($scenario->three){
            UnitFactory::create("CA-1", BLUE_FORCE, 2110, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 2009, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-1", BLUE_FORCE, 1909, "multiInf.png", 9, 14, 5, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("DD-2", BLUE_FORCE, 2107, "multiInf.png", 3, 12, 2, 22, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-2", BLUE_FORCE, 2113, "multiInf.png", 3, 12, 2, 22, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("CA-1", RED_FORCE, 3221, "multiInf.png", 14, 15, 5, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CA-2", RED_FORCE, 3420, "multiInf.png", 13, 15, 5, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-3", RED_FORCE, 3321, "multiInf.png", 11, 12, 6, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-3", RED_FORCE, 3122, "multiInf.png", 11, 12, 6, 0, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-5", RED_FORCE, 3719, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 3619, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 3520, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 2923, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 3022, "multiInf.png", 2, 8, 2, 5, 3, NorthEast,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
        }

        if($scenario->seven){

            /* IJN froces */
            UnitFactory::create("CL-2", BLUE_FORCE, 3116, "multiInf.png", 5, 12, 3, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "cl");

            UnitFactory::create("DD-1", BLUE_FORCE, 3216, "multiInf.png", 1, 12, 2, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-4", BLUE_FORCE, 3015, "multiInf.png", 3, 12, 2, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-4", BLUE_FORCE, 2915, "multiInf.png", 3, 12, 2, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-4", BLUE_FORCE, 2814, "multiInf.png", 3, 12, 2, 20, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-2", BLUE_FORCE, 2714, "multiInf.png", 3, 12, 2, 22, 4, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            /* USN forces */


            UnitFactory::create("CL-3", RED_FORCE, 4725, "multiInf.png", 11, 12, 6, 0, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-3", RED_FORCE, 4926, "multiInf.png", 11, 12, 6, 0, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("CL-6", RED_FORCE, 4825, "multiInf.png", 6, 12, 4, 12, 3, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-6", RED_FORCE, 4222, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", RED_FORCE, 4323, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", RED_FORCE, 4423, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", RED_FORCE, 4524, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", RED_FORCE, 4624, "multiInf.png", 3, 8, 2, 10, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

            UnitFactory::create("DD-3", RED_FORCE, 5026, "multiInf.png", 2, 8, 2, 16, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 5127, "multiInf.png", 2, 8, 2, 16, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 5227, "multiInf.png", 2, 8, 2, 16, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

            UnitFactory::create("DD-5", RED_FORCE, 5529, "multiInf.png", 2, 8, 2, 5, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 5328, "multiInf.png", 2, 8, 2, 5, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-5", RED_FORCE, 5428, "multiInf.png", 2, 8, 2, 5, 2, NorthWest,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");


        }


        if($scenario->eight){

            /* IJN froces */
            UnitFactory::create("CA-2", RED_FORCE, 2211, "multiInf.png", 14, 14, 7, 39, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");
            UnitFactory::create("CA-2", RED_FORCE, 2111, "multiInf.png", 14, 14, 7, 39, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "ca");

            UnitFactory::create("CL-2", RED_FORCE, 2310, "multiInf.png", 5, 12, 3, 20, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "cl");

            UnitFactory::create("CL-4", RED_FORCE, 2013, "multiInf.png", 7, 16, 3, 19, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "cl");

            UnitFactory::create("DD-3", RED_FORCE, 2209, "multiInf.png", 3, 12, 2, 19, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 2109, "multiInf.png", 3, 12, 2, 19, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-3", RED_FORCE, 2008, "multiInf.png", 3, 12, 2, 19, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-5", RED_FORCE, 1712, "multiInf.png", 3, 8, 2, 10, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            UnitFactory::create("DD-4", RED_FORCE, 1913, "multiInf.png", 3, 12, 2, 20, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");
            UnitFactory::create("DD-4", RED_FORCE, 1812, "multiInf.png", 3, 12, 2, 20, 5, SouthEast,  STATUS_CAN_DEPLOY, "A", 1, "ijn",  "dd");

            /* USN forces */

            UnitFactory::create("CL-5", BLUE_FORCE, 3925, "multiInf.png", 9, 12, 6, 0, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-5", BLUE_FORCE, 3922, "multiInf.png", 9, 12, 6, 0, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-5", BLUE_FORCE, 3923, "multiInf.png", 9, 12, 6, 0, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");
            UnitFactory::create("CL-5", BLUE_FORCE, 3924, "multiInf.png", 9, 12, 6, 0, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "ca");

            UnitFactory::create("DD-6", BLUE_FORCE, 3822, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 3823, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 3825, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 3824, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 4022, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 4023, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 4024, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");
            UnitFactory::create("DD-6", BLUE_FORCE, 4025, "multiInf.png", 3, 8, 2, 10, 3, North,  STATUS_CAN_DEPLOY, "A", 1, "usn",  "dd");

        }



    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
            $this->specialHexA = $data->specialHexA;

        } else {
            $this->victory = new Victory("SPI/TinCans/TinCansVictoryCore.php");

            // game data
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

            if($scenario->one){
                $this->gameRules->setMaxTurn(20);
                $this->combatRules->dayTime = true;
            }

            if($scenario->two){
                $this->gameRules->setMaxTurn(15);
            }
            if($scenario->three){
                $this->gameRules->setMaxTurn(15);
            }
            if($scenario->seven){
                $this->gameRules->setMaxTurn(15);
            }
            if($scenario->eight){
                $this->gameRules->setMaxTurn(20);
            }
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