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

define("GERMAN_FORCE", 1);
define("SOVIET_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "German";
$force_name[2] = "Soviet";

class Moskow extends ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];


    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "moskowHeader.php";
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
        $this->terrain->addTerrainFeature("road", "road", "r", 1, 0, 0, false);
        $this->terrain->addNatAltEntranceCost('road','soviet','inf',.3);
        $this->terrain->addNatAltEntranceCost('road','soviet','mudinf',1./12.);
        $this->terrain->addNatAltEntranceCost('road','sovietguard','inf',.3);
        $this->terrain->addNatAltEntranceCost('road','sovietguard','mudinf',1./12.);

    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    function save()
    {

        $data = parent::save();
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;
        return $data;
    }

    public function init()
    {

        UnitFactory::$injector = $this->force;

        $scenario = $this->scenario;

        for($i = 0; $i < 20;$i++){
            UnitFactory::create("xxx", SOVIET_FORCE, "deployBox", "multiInf.png", 6, 3, 4, true, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, 'inf');
        }

        for($i = 0; $i < 4;$i++){
            UnitFactory::create("xxx", SOVIET_FORCE, "deadpile", "multiInf.png", 6, 3, 4, true, STATUS_ELIMINATED, "B", 1, 1, "soviet", true, 'inf');
        }

        UnitFactory::create("guard", SOVIET_FORCE, "gameTurn6", "multiInf.png", 8, 4, 4, true, STATUS_CAN_REINFORCE, "B",6, 1, "sovietguard", true, 'inf');


        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 9, 5, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 9, 5, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 9, 5, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 9, 5, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");

        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");

        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 7, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 7, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 7, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");

        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");

        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");

        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");

        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");

        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");

        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 3, 1, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 3, 1, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        UnitFactory::create("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 3, 1, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        $crt = new \TMCW\Moskow\CombatResultsTable();
        $this->combatRules->injectCrt($crt);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
        } else {
            $this->victory = new Victory("TMCW\\Moskow\\moskowVictoryCore");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = 0;
            $this->moveRules->noZocZocOneHex = false;

            // game data
            $this->gameRules->setMaxTurn(11);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);

            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, GERMAN_FORCE, SOVIET_FORCE, true);
        }
    }
}