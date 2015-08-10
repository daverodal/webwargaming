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


        /* Arab Player Units */

        UnitFactory::$injector = $this->force;

        /* Iranian */
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 8, false, 10,  STATUS_CAN_DEPLOY, "I", 1, 1, "iranian",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 8, false, 10,  STATUS_CAN_DEPLOY, "I", 1, 1, "iranian",  "inf");

        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiArmor.png", 7, false, 10,  STATUS_CAN_DEPLOY, "I", 1, 1, "iranian",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiArmor.png", 7, false, 10,  STATUS_CAN_DEPLOY, "I", 1, 1, "iranian",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiArmor.png", 7, false, 10,  STATUS_CAN_DEPLOY, "I", 1, 1, "iranian",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiArmor.png", 7, false, 10,  STATUS_CAN_DEPLOY, "I", 1, 1, "iranian",  "inf");

        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 6, false, 10,  STATUS_CAN_DEPLOY, "I", 1, 1, "iranian",  "inf");

        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane.svg", 6, 5, 38,  STATUS_CAN_DEPLOY, "J", 1, 1, "iranian",  "air", "f4", true);
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane.svg", 6, 5, 38,  STATUS_CAN_DEPLOY, "J", 1, 1, "iranian",  "air", "f4", true);

        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane4.svg", 3, 4, 24,  STATUS_CAN_DEPLOY, "J", 1, 1, "iranian",  "air", "f5", true);
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane4.svg", 3, 4, 24,  STATUS_CAN_DEPLOY, "J", 1, 1, "iranian",  "air", "f5", true);


        /* Saudi Arabian */
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 4, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 4, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiMech.png", 6, false, 10,  STATUS_CAN_DEPLOY, "A", 1, 1, "saudi",  "mech");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane4.svg", 2, 2, 25,  STATUS_CAN_DEPLOY, "B", 1, 1, "saudi",  "air", "167", true);
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane4.svg", 2, 3, 24,  STATUS_CAN_DEPLOY, "B", 1, 1, "saudi",  "air", "f5", true);
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane3.svg", 0, 5, 18,  STATUS_CAN_DEPLOY, "B", 1, 1, "saudi",  "air", "lgtn", true);

        /* Iraqi */
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiMech.png", 4, false, 10,  STATUS_CAN_DEPLOY, "C", 1, 1, "iraqi",  "mech");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiMech.png", 4, false, 10,  STATUS_CAN_DEPLOY, "C", 1, 1, "iraqi",  "mech");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiMech.png", 4, false, 10,  STATUS_CAN_DEPLOY, "C", 1, 1, "iraqi",  "mech");

        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 3, false, 10,  STATUS_CAN_DEPLOY, "C", 1, 1, "iraqi",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 3, false, 10,  STATUS_CAN_DEPLOY, "C", 1, 1, "iraqi",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane.svg", 0, 5, 18,  STATUS_CAN_DEPLOY, "D", 1, 1, "iraqi",  "air", "m21", true);
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane3.svg", 1, 2, 14,  STATUS_CAN_DEPLOY, "D", 1, 1, "iraqi",  "air", "su7", true);

        /* Kuwati */
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 1, false, 10,  STATUS_CAN_DEPLOY, "E", 1, 1, "kuwati",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 1, false, 10,  STATUS_CAN_DEPLOY, "E", 1, 1, "kuwati",  "inf");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiArmor.png", 2, false, 10,  STATUS_CAN_DEPLOY, "E", 1, 1, "kuwati",  "mech");
        UnitFactory::create("lll", RED_FORCE, "deployBox", "jetPlane3.svg", 0, 3, 18,  STATUS_CAN_DEPLOY, "F", 1, 1, "kuwati",  "air", "lgtn");

        /* Quatar and Baharain */
        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 1, false, 10,  STATUS_CAN_DEPLOY, "G", 1, 1, "quatar",  "inf");

        UnitFactory::create("lll", RED_FORCE, "deployBox", "multiInf.png", 1, false, 10,  STATUS_CAN_DEPLOY, "H", 1, 1, "bahrain",  "inf");


        /* American Player */

        /* Israeli */
        UnitFactory::create("lll", BLUE_FORCE, "israel", "multiPara.png", 7, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "israeli",  "para");
        UnitFactory::create("lll", BLUE_FORCE, "israel", "multiPara.png", 7, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "israeli",  "para");
        UnitFactory::create("lll", BLUE_FORCE, "israel", "multiMech.png", 9, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "israeli",  "mech");

        UnitFactory::create("lll", BLUE_FORCE, "israel", "jetPlane.svg", 2, 6, 38,  STATUS_CAN_REINFORCE, "O", 1, 1, "israeli",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "israel", "jetPlane.svg", 2, 6, 38,  STATUS_CAN_REINFORCE, "O", 1, 1, "israeli",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "israel", "jetPlane.svg", 2, 6, 30,  STATUS_CAN_REINFORCE, "O", 1, 1, "israeli",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "israel", "jetPlane.svg", 2, 6, 30,  STATUS_CAN_REINFORCE, "O", 1, 1, "israeli",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "israel", "jetPlane2.svg", 14, 6, 30,  STATUS_CAN_REINFORCE, "O", 1, 1, "us",  "air", "f111");
        UnitFactory::create("lll", BLUE_FORCE, "israel", "jetPlane2.svg", 14, 6, 30,  STATUS_CAN_REINFORCE, "O", 1, 1, "us",  "air", "f111");

        /* Germany */
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiPara.png", 7, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "eec",  "para");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiPara.png", 7, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "eec",  "para");



        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiPara.png", 7, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "us",  "para");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiPara.png", 7, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "us",  "para");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiPara.png", 7, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "us",  "para");


        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiInf.png", 8, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "us",  "inf");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiInf.png", 8, false, 10,  STATUS_CAN_REINFORCE, "O", 1, 1, "us",  "inf");

        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiMech.png", 9, false, 10,  STATUS_CAN_REINFORCE, "Q", 1, 1, "us",  "mech");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "multiArmor.png", 10, false, 10,  STATUS_CAN_REINFORCE, "Q", 1, 1, "us",  "mech");

        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane.svg", 7, 6, 38,  STATUS_CAN_REINFORCE, "Q", 1, 1, "us",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane.svg", 7, 6, 38,  STATUS_CAN_REINFORCE, "Q", 1, 1, "us",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane.svg", 7, 6, 38,  STATUS_CAN_REINFORCE, "Q", 1, 1, "us",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane.svg", 7, 6, 38,  STATUS_CAN_REINFORCE, "Q", 1, 1, "us",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane.svg", 7, 6, 38,  STATUS_CAN_REINFORCE, "Q", 1, 1, "us",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "germany", "jetPlane.svg", 7, 6, 38,  STATUS_CAN_REINFORCE, "Q", 1, 1, "us",  "air", "f4");

        /* Gulf of Oman */
        UnitFactory::create("lll", BLUE_FORCE, "oman", "jetPlane.svg", 7, 6, 38,  STATUS_CAN_REINFORCE, "O", 1, 1, "usn",  "air", "f4");
        UnitFactory::create("lll", BLUE_FORCE, "oman", "jetPlane.svg", 6, 3, 15,  STATUS_CAN_REINFORCE, "O", 1, 1, "usn",  "air", "a6");
        UnitFactory::create("lll", BLUE_FORCE, "oman", "jetPlane.svg", 5, 4, 15,  STATUS_CAN_REINFORCE, "O", 1, 1, "usn",  "air", "f4");



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