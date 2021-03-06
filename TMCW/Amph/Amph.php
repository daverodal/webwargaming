<?php
namespace TMCW\Amph;
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

define("REBEL_FORCE", 1);
define("LOYALIST_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

class Amph extends \ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "amphHeader.php";
    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
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
    }
    function save()
    {
        $data = parent::save();
        $data->specialHexA = $this->specialHexA;
        return $data;
    }

    public function init()
    {
        UnitFactory::$injector = $this->force;


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

        UnitFactory::create("lll", LOYALIST_FORCE, 305, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, 803, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("x", LOYALIST_FORCE, 907, "multiHeavy.png", 10, 5, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, 1205, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, 1405, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');

        UnitFactory::create("lll", LOYALIST_FORCE, 1705, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, 1904, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, 1809, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, 1004, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, 604, "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        UnitFactory::create("x", LOYALIST_FORCE, 1810, "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'mountain');

        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn2", "multiInf.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("lll", LOYALIST_FORCE, "gameTurn2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalist", true, 'inf');
        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn3", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalGuards", true, 'mountain');
        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn3", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_CAN_REINFORCE, "D", 3, 1, "loyalGuards", true, 'mountain');
        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalGuards", true, 'shock');
        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn4", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "E", 4, 1, "loyalGuards", true, 'shock');

        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn5", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn5", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'mech');
        UnitFactory::create("x", LOYALIST_FORCE, "gameTurn5", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalGuards", true, 'heavy');

        if(!$scenario->weakerLoyalist) {
            UnitFactory::create("x", LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            UnitFactory::create("x", LOYALIST_FORCE, "gameTurn6", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            UnitFactory::create("x", LOYALIST_FORCE, "gameTurn6", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'mech');
            UnitFactory::create("x", LOYALIST_FORCE, "gameTurn6", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalGuards", true, 'heavy');
        }

        /* Rebel Units */

        UnitFactory::create("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "deployBox", "multiPara.png", 8, 4, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "rebel", true, "para");
        UnitFactory::create("lll", BLUE_FORCE, "deployBox", "multiPara.png", 8, 4, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "rebel", true, "para");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn2", "multiPara.png", 8, 4, 5, false, STATUS_CAN_REINFORCE, "C", 2, 1, "rebel", true, "para");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn4", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn4", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "inf");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn4", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn5", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn5", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn5", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn6", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn6", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn6", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "inf");

        UnitFactory::create("lll", BLUE_FORCE, "gameTurn7", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn7", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "mech");
        UnitFactory::create("lll", BLUE_FORCE, "gameTurn7", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "inf");
    }

    public static function myName(){
        echo __CLASS__;
    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        $crt = new \TMCW\CombatResultsTable();
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;

        } else {

            $this->victory = new \Victory("TMCW\\Amph\\amphVictoryCore");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->moveRules = new \MoveRules($this->force, $this->terrain);
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
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
        }
    }
}