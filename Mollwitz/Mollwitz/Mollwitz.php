<?php
use \UnitFactory;
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
define("PRUSSIAN_FORCE", 1);
define("AUSTRIAN_FORCE", 2);

global $force_name;
$force_name = array();
$force_name[1] = "Prussian";
$force_name[2] = "Austrian";

class Mollwitz extends \Mollwitz\JagCore
{

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "mollwitzHeader.php";
    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "Prussian";
        $deployOne = $playerTwo = "Austrian";
        @include_once "view.php";
    }

    function save()
    {
        $data = new stdClass();
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;

        return $data;
    }

    public function init(){

        $artRange = 3;
        $artStr = 2;
        UnitFactory::$injector = $this->force;

        if($this->scenario->artStr){
            $artStr = 3;
        }
        if($this->scenario->commandControl){
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'hq');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'hq');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'hq');
        }

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');

        if($this->scenario->commandControl) {
            UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "HQBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'hq');
            UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "HQBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'hq');
            UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "HQBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'hq');
        }
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        if($this->scenario->extraArt){
            UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        }


    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {

        } else {
            $this->victory = new Victory("Mollwitz/Mollwitz/mollwitzVictoryCore.php");

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->stickyZOC = false;

            // game data
            $this->gameRules->setMaxTurn(12);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }

    }
}