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
$force_name[1] = "US";
$force_name[2] = "Arab";

require_once "constants.php";
require_once "ModernLandBattle.php";

class ClashOverCrude extends ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "ClashOverCrudeHeader.php";
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
        /* Loyalists units */

//        $this->force->addUnit("lll", LOYALIST_FORCE, 305, "jetPlane.svg", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf', 'air');
//        $this->force->addUnit("lll", LOYALIST_FORCE, 803, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("x", LOYALIST_FORCE, 907, "multiHeavy.png", 10, 5, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, 1205, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, 1405, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//
//        $this->force->addUnit("lll", LOYALIST_FORCE, 1705, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, 1904, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, 1809, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, 1004, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, 604, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("x", LOYALIST_FORCE, 1810, "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'mountain');
//
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn2", "multiInf.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalGuards", true, 'mountain');
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "D", 3, 1, "loyalGuards", true, 'mountain');
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "E", 4, 1, "loyalGuards", true, 'shock');
//
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
//        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'heavy');
//
//        if(!$scenario->weakerLoyalist) {
//            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
//            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
//            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
//            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'heavy');
//        }



        /* Rebel Units */

        UnitFactory::$injector = $this->force;
        /* Saudi Arabian */
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 4, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 4, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiMech.png", 6, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "mech");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane4.svg", 2, 2, 25,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "air", "167");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane2.svg", 2, 3, 24,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "air", "f5");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane2.svg", 0, 5, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "air", "Lt");

        UnitFactory::create("lll", BLUE_FORCE, 510, "multiPara.png", 7, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "israeli",  "para");
        UnitFactory::create("lll", BLUE_FORCE, 511, "multiPara.png", 7, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "israeli",  "para");
        UnitFactory::create("lll", BLUE_FORCE, 307, "multiMech.png", 9, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "israeli",  "mech");

        UnitFactory::create("lll", BLUE_FORCE, 303, "jetPlane.svg", 7, 4, 9,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "air", "f4");

        UnitFactory::create("lll", BLUE_FORCE, 302, "jetPlane.svg", 7, 4, 9,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, 404, "jetPlane2.svg", 6, 12, 9,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "air", "f111");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane2.svg", 6, 12, 8,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "air", "a7");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane3.svg", 6, 3, 8,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "air", "lgtn");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane3.svg", 6, 3, 8,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "air", "lgtn");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane4.svg", 6, 3, 8,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "air", "f5");



        UnitFactory::create("lll", BLUE_FORCE, 412, "multiPara.png", 7, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "eec",  "para");
        UnitFactory::create("lll", BLUE_FORCE, 413, "multiPara.png", 7, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "eec",  "para");



        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiPara.png", 7, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "para");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiPara.png", 7, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "para");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiPara.png", 7, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "para");


        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiInf.png", 8, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "inf");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiInf.png", 8, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "us",  "inf");

        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiMech.png", 9, false, 10,  STATUS_CAN_REINFORCE, "A", 1, 1, "us",  "mech");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiArmor.png", 10, false, 10,  STATUS_CAN_REINFORCE, "A", 1, 1, "us",  "mech");

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
            $this->specialHexA = $data->specialHexA;

        } else {
            $this->victory = new Victory("SPI/ClashOverCrude/ClashOverCrudeVictoryCore.php");
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
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_AIR_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_AIR_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
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