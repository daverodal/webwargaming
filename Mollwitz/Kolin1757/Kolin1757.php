<?php
use \UnitFactory;
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */

define("PRUSSIAN_FORCE", 1);
define("AUSTRIAN_FORCE", 2);

global $force_name;
$force_name[AUSTRIAN_FORCE] = "Austrian";
$force_name[PRUSSIAN_FORCE] = "Prussian";

class Kolin1757 extends \Mollwitz\JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>0];

    public
    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "Kolin1757Header.php";

    }


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        $deployTwo = $playerOne = "Prussian";
        $deployOne = $playerTwo = "Austrian";
        @include_once "playMulti.php";
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
        $data->roadHex = $this->roadHex;
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;

        return $data;
    }

    public function init()
    {

        $artRange = 3;
        $coinFlip = floor(2 * (rand() / getrandmax()));
        $prussianDeploy = ($coinFlip == 1 ? "B": "C");
        UnitFactory::$injector = $this->force;


        /* Austrian */
        for ($i = 0; $i < 5; $i++) {
            UnitFactory::create("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        }
        for ($i = 0; $i < 16; $i++) {
            UnitFactory::create("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        }
        for ($i = 0; $i < 5; $i++) {
            UnitFactory::create("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 8; $i++) {
            UnitFactory::create("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            UnitFactory::create("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, 3, "Austrian", false, 'artillery');
        }
        for ($i = 0; $i < 3; $i++) {
            UnitFactory::create("infantry-1", AUSTRIAN_FORCE, "deployBox", "AustrianArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "A", 1, 3, "Austrian", false, 'artillery');
        }

        if($this->scenario->fredBringsMore){
            /* Prussian */
            for ($i = 0; $i < 5; $i++) {
                UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'infantry');
            }
            for ($i = 0; $i < 16; $i++) {
                UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'infantry');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
            }
            for ($i = 0; $i < 8; $i++) {
                UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
            }
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
            }
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 3, "Prussian", false, 'artillery');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 3, "Prussian", false, 'artillery');
            }
        }else{
            if($this->scenario->fredBringsALittleMore){
                /* Prussian */
                for ($i = 0; $i < 4; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'infantry');
                }
                for ($i = 0; $i < 14; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'infantry');
                }
                for ($i = 0; $i < 3; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
                }
                for ($i = 0; $i < 6; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
                }
                for ($i = 0; $i < 2; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
                }
                for ($i = 0; $i < 5; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 3, "Prussian", false, 'artillery');
                }
            }else{
                /* Prussian */
                for ($i = 0; $i < 4; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'infantry');
                }
                for ($i = 0; $i < 12; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'infantry');
                }
                for ($i = 0; $i < 3; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
                }
                for ($i = 0; $i < 5; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
                }
                for ($i = 0; $i < 2; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 1, "Prussian", false, 'cavalry');
                }
                for ($i = 0; $i < 5; $i++) {
                    UnitFactory::create("infantry-1", PRUSSIAN_FORCE, "deployBox", "PrussianArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, $prussianDeploy, 1, 3, "Prussian", false, 'artillery');
                }
            }
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->roadHex = $data->roadHex;
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
        } else {
            $this->victory = new Victory("Mollwitz/Kolin1757/kolin1757VictoryCore.php");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->zocBlocksRetreat = true;

            // game data

            $this->gameRules->setMaxTurn(14);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

//            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

//            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}