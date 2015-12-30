<?php
use \TMCW\UnitFactory;
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

define("LACONIANS_FORCE", 1);
define("CAPROLIANS_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Laconians";
$force_name[2] = "Caprolians";

$mode_name[17] = "";


$mode_name[3] = "Combat Setup Phase";
$mode_name[4] = "Combat Resolution Phase";
$mode_name[19] = "";

$mode_name[1] = "";
$mode_name[2] = "";

class RetreatOne extends ModernLandBattle
{

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "retreatOneHeader.php";
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

    function terrainGen($mapDoc, $terrainDoc){
        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("secondaryroad", "secondaryroad", "r", .75, 0, 0, false);
        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
        $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
        $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
        $this->terrain->addTerrainFeature("roughone", "roughone", "g", 2, 0, 2, true);
        $this->terrain->addTerrainFeature("roughtwo", "roughtwo", "g", 3, 0, 2, true);
        $this->terrain->addTerrainFeature("swamp", "swamp", "f", 3, 0, 1, true);
        $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
        $this->terrain->addTerrainFeature("wadi", "wadi", "w", 0, 1, 1, false);
        $this->terrain->addAltEntranceCost("roughone", "mech", 6);
        $this->terrain->addAltEntranceCost("roughtwo", "mech", "blocked");
        parent::terrainGen($mapDoc, $terrainDoc);
    }
    function save()
    {
        $data = parent::save();
        return $data;
    }


    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    public function init()
    {

        UnitFactory::$injector = $this->force;

        UnitFactory::create("hq", CAPROLIANS_FORCE, "5015", "multiInf.png", 1, 1, 5, false, STATUS_READY, "A", 1, 1, "caprolians", true, 'hq');

        UnitFactory::create("hq", CAPROLIANS_FORCE, "4307", "multiInf.png", 1, 1, 5, false, STATUS_READY, "A", 1, 1, "caprolians", true, 'hq');

        UnitFactory::create("hq", CAPROLIANS_FORCE, "116", "multiInf.png", 1, 1, 5, false, STATUS_READY, "A", 1, 1, "caprolians", true, 'hq');

        for($i = 0;$i < 16;$i++){
            UnitFactory::create("xx", CAPROLIANS_FORCE, "deployBox", "multiInf.png", 3, 1, 3, false, STATUS_CAN_DEPLOY, "A", 1, 1, "caprolians", true, 'inf');
        }
        for($i = 0;$i < 4;$i++){
            UnitFactory::create("xx", CAPROLIANS_FORCE, "deployBox", "multiMotInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "caprolians", true, 'mech');
        }
        for($i = 0;$i < 4;$i++){
            UnitFactory::create("xx", CAPROLIANS_FORCE, "deployBox", "multiMotArt.png", 3, 1, 6, false, STATUS_CAN_DEPLOY, "A", 1, 5, "caprolians", true, 'mech');
        }
        for($i = 0;$i < 4;$i++){
            UnitFactory::create("xx", CAPROLIANS_FORCE, "deployBox", "multiArmor.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "caprolians", true, 'mech');
        }
        for($i = 0;$i < 3;$i++){
            UnitFactory::create("xx", CAPROLIANS_FORCE, "deployBox", "multiRecon.png", 2, 1, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "caprolians", true, 'mech');
        }

        for($i = 0;$i < 12;$i++){
            UnitFactory::create("xx", LACONIANS_FORCE, "deployBox", "multiMotInf.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "laconians", true, "mech");
        }
        for($i = 0;$i < 4;$i++){
            UnitFactory::create("xx", LACONIANS_FORCE, "deployBox", "multiMotArt.png", 4, 2, 6, false, STATUS_CAN_DEPLOY, "B", 1, 5, "laconians", true, "mech");
        }
        for($i = 0;$i < 4;$i++){
            UnitFactory::create("xx", LACONIANS_FORCE, "deployBox", "multiArmor.png", 4, 2, 5, false, STATUS_CAN_DEPLOY, "B", 1, 1, "laconians", true, "mech");
        }
        for($i = 0;$i < 2;$i++){
            UnitFactory::create("xx", LACONIANS_FORCE, "deployBox", "multiArmor.png", 6, 3, 3, false, STATUS_CAN_DEPLOY, "B", 1, 1, "laconians", true, "mech");
        }
        for($i = 0;$i < 4;$i++){
            UnitFactory::create("xx", LACONIANS_FORCE, "deployBox", "multiRecon.png", 2, 1, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "laconians", true, "mech");
        }
        $mapData = $this->mapData;
        /* @var MapHex $mapHex */
//        $mapHex = $mapData->getHex(3807);
//        $mapHex->setZoc(LACONIANS_FORCE, 'air1');
//        $mapHex = $mapData->getHex(3306);
//        $mapHex->setZoc(LACONIANS_FORCE, 'air2');
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);

        $crt = new \TMCW\CombatResultsTable();
        $this->combatRules->injectCrt($crt);

        if ($data) {

        } else {
            $this->victory = new Victory("TMCW\\RetreatOne\\retreatOneVictoryCore");

            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            if ($scenario && $scenario->supply === true) {
                $this->moveRules->enterZoc = 2;
                $this->moveRules->exitZoc = 1;
                $this->moveRules->noZocZocOneHex = true;
            } else {
                $this->moveRules->enterZoc = "stop";
                $this->moveRules->exitZoc = 0;
                $this->moveRules->noZocZocOneHex = false;
            }
            // game data
            $this->gameRules->setMaxTurn(15);

            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */




            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);


        }
    }
}
