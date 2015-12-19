<?php
namespace TMCW\Chawinda1965;
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

define("INDIAN_FORCE", 1);
define("PAKISTANI_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Indian";
$force_name[2] = "Pakistani";

require_once "constants.php";

//require_once "ModernLandBattle.php";

use UnitFactory;

class Chawinda1965 extends \ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>2];

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;
    public $arg;
    public $scenario;

    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "chawinda1965Header.php";
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

    function terrainInit($terrainDoc)
    {
        parent::terrainInit($terrainDoc);
        $vp = count((array)$this->specialHexA);
        $this->victory->setInitialPakistaniVP($vp * 3);


        $symbol = new \stdClass();
        $symbol->type = 'WestWall';
        $symbol->image = 'rowHex.svg';
        $symbol->class = 'row-hex';
        $symbols = new \stdClass();
//        foreach([609,610,611,712, 2404, 2304, 2105, 2005, 1905, 1805, 1806, 1707, 1606, 1506] as $id){
//            $symbols->$id = $symbol;
//        }
//        $this->mapData->setMapSymbols($symbols, "westwall");

    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 2, 0, 1, true);
        $this->terrain->addAltEntranceCost('swamp', 'mech', 3);
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 2, 1, true);
        $this->terrain->addAltEntranceCost("river", 'mech', 3);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);

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


        $scenario = $this->scenario;
        $infStrength = 3;
        $halfInfStrength = 3;
        $numPakistaniInf = 6;
        $defStrength = 7;
        $halfDefStrength = 7;
        if($scenario->bigPakistani){
            $numPakistaniInf += 6;
            $infStrength = 2;
            $halfInfStrength = 2;
            $defStrength = 5;
            $halfDefStrength = 5;
        }





        $unitNum = 100;
        UnitFactory::$injector = $this->force;


        for($i = 0; $i < 4;$i++){
            UnitFactory::create("x", PAKISTANI_FORCE, "deployBox", "multiArmor.png", 5, 5,5, 5, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "pakistani", true, "mech", $i+1);
//            $this->force->addUnit("x", PAKISTANI_FORCE, "deployBox", "multiArmor.png", 6, 3, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "pakistani", true, "mech", $i+1);
        }

        for($i = 0; $i < $numPakistaniInf;$i++){
            UnitFactory::create("x", PAKISTANI_FORCE, "deployBox", "multiInf.png", $infStrength, $halfInfStrength,$defStrength, $halfDefStrength, 4, false, STATUS_CAN_DEPLOY, "B", 1, 1, "pakistani", true, 'inf', $i+1);
        }

        for($i = 2; $i <= 8; $i++) {
            UnitFactory::create("x", PAKISTANI_FORCE, "gameTurn$i", "multiArmor.png", 5, 5,5, 5, 6, false, STATUS_CAN_REINFORCE, "C", $i, 1, "pakistani", true, "mech", "T $i 1");
            UnitFactory::create("x", PAKISTANI_FORCE, "gameTurn$i", "multiInf.png", $infStrength, $halfInfStrength,$defStrength, $halfDefStrength, 4, false, STATUS_CAN_REINFORCE, "C", $i, 1, "pakistani", true, "inf", "T $i 2");
            if(!$scenario->bigPakistani) {
                UnitFactory::create("x", PAKISTANI_FORCE, "gameTurn$i", "multiInf.png", $infStrength, $halfInfStrength,$defStrength, $halfDefStrength, 4, false, STATUS_CAN_REINFORCE, "C", $i, 1, "pakistani", true, "inf", "T $i 3");
            }
        }

        for($i = 0; $i < 6;$i++) {

            UnitFactory::create("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 5, 5,5, 5, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "A $i");
        }

        UnitFactory::create("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 4, 4,4, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "A 7");
        UnitFactory::create("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 4, 4,4, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "A 8");
        UnitFactory::create("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 4, 4,4, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "A 9");
        UnitFactory::create("x", INDIAN_FORCE, "deployBox", "multiArmor.png", 4, 4,4, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "mech", "A 10");

        for($i = 0; $i < 20;$i++) {

            UnitFactory::create("x", INDIAN_FORCE, "deployBox", "multiInf.png", $infStrength, $halfInfStrength,$infStrength, $halfInfStrength, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "indian", true, "inf", "$i");
        }

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);

        $crt = new \TMCW\Chawinda1965\CombatResultsTable();
        $this->combatRules->injectCrt($crt);

        $this->mapData = \MapData::getInstance();
        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
        } else {
            $this->victory = new \Victory("TMCW/Chawinda1965/chawinda1965VictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->moveRules->enterZoc = 1;
            $this->moveRules->exitZoc = 2;
            $this->moveRules->noZocZocOneHex = false;
            $this->moveRules->stacking = 3;
            $this->moveRules->friendlyAllowsRetreat = true;
            $this->moveRules->blockedRetreatDamages = true;

            $this->gameRules->legacyExchangeRule = false;

            // game data
            $this->gameRules->setMaxTurn(8);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);

            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBINE_PHASE, COMBINING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBINE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, BLUE_MECH_COMBINE_PHASE, COMBINING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_COMBINE_PHASE, RED_MOVE_PHASE, MOVING_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBINE_PHASE, COMBINING_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBINE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, RED_MECH_COMBINE_PHASE, COMBINING_MODE, PAKISTANI_FORCE, INDIAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_COMBINE_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, INDIAN_FORCE, PAKISTANI_FORCE, true);
        }

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            $limit = 0;
            $armyGroup = false;
            if($unit->isReduced !== true){
                $limit++;
            }
            $limit++;

            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if($this->force->units[$mKey]->isReduced !== true){
                    $limit++;
                }
                $limit++;
            }
            return $limit > 6;
        };
    }
}