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
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

require_once "constants.php";
require_once "ModernLandBattle.php";

class Airborne extends \ModernLandBattle
{

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "airborneHeader.php";
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
        $this->terrain->addTerrainFeature("roughone", "roughone", "r", 3, 0, 1, true);
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

        $scenario = $this->scenario;
        $baseValue = 3;
        $reducedBaseValue = 2;

        /* Loyalists units */

        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');

        $this->force->addUnit("x", LOYALIST_FORCE, "deployBox", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_DEPLOY, "G", 1, 1, "loyalGuards", true, 'heavy');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn2C", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "G", 2, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn2C", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "G", 2, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3C", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_DEPLOY, "G", 3, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3D", "multiShock.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "G", 3, 1, "loyalGuards", true, 'shock');

        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "deployBox", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "deployBox", "multiInf.png", 7, 3, 5, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalGuards", true, 'inf');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn2C", "multiInf.png", 7, 3, 5, false, STATUS_CAN_REINFORCE, "C", 2, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2D", "multiInf.png", 6, 3, 5, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2D", "multiInf.png", 6, 3, 5, false, STATUS_CAN_REINFORCE, "D", 2, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2E", "multiInf.png", 6, 3, 5, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("lll", LOYALIST_FORCE, "gameTurn2E", "multiInf.png", 6, 3, 5, false, STATUS_CAN_REINFORCE, "E", 2, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3C", "multiPara.png", 7, 3, 5, false, STATUS_CAN_REINFORCE, "C", 3, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3D", "multiPara.png", 7, 3, 5, false, STATUS_CAN_REINFORCE, "D", 3, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn3D", "multiInf.png", 6, 3, 5, false, STATUS_CAN_REINFORCE, "D", 3, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4E", "multiInf.png", 6, 3, 5, false, STATUS_CAN_REINFORCE, "E", 4, 1, "loyalGuards", true, 'inf');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4C", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "C", 4, 1, "loyalGuards", true, 'shock');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4C", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "C", 4, 1, "loyalGuards", true, 'shock');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn4E", "multiShock.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "E", 4, 1, "loyalGuards", true, 'shock');

        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5C", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "C", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5C", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "C", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5C", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "C", 5, 1, "loyalGuards", true, 'mech');
        $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn5C", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "C", 5, 1, "loyalGuards", true, 'heavy');

        if(!$scenario->weakerLoyalist) {
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6C", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "C", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6C", "multiArmor.png", 13, 6, 8, false, STATUS_CAN_REINFORCE, "C", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6C", "multiMech.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "C", 6, 1, "loyalGuards", true, 'mech');
            $this->force->addUnit("x", LOYALIST_FORCE, "gameTurn6C", "multiHeavy.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "C", 6, 1, "loyalGuards", true, 'heavy');
        }

        /* Rebel Units */

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "para");

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");

        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiMech.png", 10, 5, 8, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "B", 1, 1, "rebel", true, "inf");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiGlider.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiGlider.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiPara.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "para");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiGlider.png", 10, 5, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "para");
        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiPara.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "para");

        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn4", "multiPara.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "para");

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
        } else {
            $this->victory = new Victory("TMCW/Airborne/airborneVictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            if ($scenario && $scenario->supply === true) {
                $this->moveRules->enterZoc = 2;
                $this->moveRules->exitZoc = 1;
                $this->moveRules->noZocZocOneHex = true;
                $this->moveRules->blockedRetreatDamages = true;
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
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE,  BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
        }
    }
}