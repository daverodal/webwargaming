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
//set_include_path(dirname(__DIR__) . "/Area1" . PATH_SEPARATOR . get_include_path());

define("REBEL_FORCE", 1);
define("LOYALIST_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

require_once "constants.php";
//require_once "ModernLandBattle.php";



require_once "Battle.php";
require_once "crtTraits.php";
require_once "combatRules.php";
require_once "crt.php";
require_once "force.php";
require_once "gameRules.php";
require_once "hexagon.php";
require_once "hexpart.php";
require_once "los.php";
require_once "mapgrid.php";
require_once "moveRules.php";
require_once "areaTerrain.php";
require_once "display.php";
require_once "victory.php";
require_once "victoryCore.php";
require_once "UnitFactory.php";



class Area1
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>1];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "area1Header.php";
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

    static function playAs($name, $wargame, $arg = false)
    {
        echo " jejej ";
        echo "play As ";
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        @include_once "playMulti.php";
    }

    function terrainInit($terrainDoc ){

    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
    }
    function save()
    {
//        $data = parent::save();
        $data = new stdClass();
        $data->specialHexA = $this->specialHexA;
        $data->arg = 'main';
        $data->terrainName = "terrain-Area1";
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

//        echo " about to ";
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a1", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        echo 'one ' ;
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a1", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("x", LOYALIST_FORCE, "a1", "multiHeavy.png", 10, 5, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a1", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a1", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a1", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("lll", LOYALIST_FORCE, "a2", "multiGor.png", $baseValue, $reducedBaseValue, 4, false, STATUS_CAN_DEPLOY, "F", 1, 1, "loyalist", true, 'inf');
//        $this->force->addUnit("x", LOYALIST_FORCE, "a2", "multiMountain.png", $baseValue+1, $reducedBaseValue+1, 5, false, STATUS_UNAVAIL_THIS_PHASE, "F", 1, 1, "loyalGuards", true, 'mountain');

//
//        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
//        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
//        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
//        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiInf.png", 9, 4, 5, false, STATUS_CAN_DEPLOY, "A", 1, 1, "rebel", true, "inf");
//
//        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 8, 4, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "rebel", true, "para");
//        $this->force->addUnit("lll", BLUE_FORCE, "deployBox", "multiPara.png", 8, 4, 5, false, STATUS_CAN_DEPLOY, "C", 1, 1, "rebel", true, "para");
//
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiPara.png", 8, 4, 5, false, STATUS_CAN_REINFORCE, "C", 2, 1, "rebel", true, "para");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn2", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 2, 1, "rebel", true, "inf");
//
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn3", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 3, 1, "rebel", true, "inf");
//
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn4", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "mech");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn4", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "inf");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn4", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 4, 1, "rebel", true, "inf");
//
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn5", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn5", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "mech");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn5", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 5, 1, "rebel", true, "inf");
//
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn6", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "mech");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn6", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "mech");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn6", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 6, 1, "rebel", true, "inf");
//
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn7", "multiArmor.png", 12, 6, 8, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "mech");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn7", "multiMech.png", 10, 5, 8, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "mech");
//        $this->force->addUnit("lll", BLUE_FORCE, "gameTurn7", "multiInf.png", 9, 4, 5, false, STATUS_CAN_REINFORCE, "A", 7, 1, "rebel", true, "inf");
    }

    static function transformChanges($doc, $last_seq, $user)
    {
        return compact("last_seq");
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {





        echo "Construct_ ";
echo "constructing ";
        $this->mapData = MapData::getInstance();
        if ($data) {
            echo " is Data ";
//            $this->arg = $data->arg;
//            $this->scenario = $data->scenario;
//            $this->terrainName = $data->terrainName;
//            $this->game = $data->game;
//            $this->display = new Display($data->display);
//            $this->mapData->init($data->mapData);
//            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));
//            $this->force = new Force($data->force);
            $this->terrain = new AreaTerrain($data->terrain);

//            var_dump($this->terrain->areas);
//
            var_dump($this->terrain->getRoutes("a1"));
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->victory = new Victory($data);

            $this->players = $data->players;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;

//            $this->display = new Display();
//            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
//            $this->force = new Force();
            $this->terrain = new areaTerrain();
//            $this->moveRules = new MoveRules($this->force, $this->terrain);
//
//            $this->combatRules = new CombatRules($this->force, $this->terrain);
//            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
        }

















        if ($data) {
            $this->specialHexA = $data->specialHexA;

        } else {
//            $this->victory = new Victory("TMCW/Area1/area1VictoryCore.php");
//            if ($scenario->supplyLen) {
//                $this->victory->setSupplyLen($scenario->supplyLen);
//            }
//            $this->moveRules = new MoveRules($this->force, $this->terrain);
//            if ($scenario && $scenario->supply === true) {
//                $this->moveRules->enterZoc = 2;
//                $this->moveRules->exitZoc = 1;
//                $this->moveRules->noZocZocOneHex = true;
//            } else {
//                $this->moveRules->enterZoc = "stop";
//                $this->moveRules->exitZoc = 0;
//                $this->moveRules->noZocZocOneHex = false;
//            }
//            // game data
//            $this->gameRules->setMaxTurn(7);
//            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
//            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
//            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
//            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */
//
//            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
//            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
//            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);
        }
    }
}