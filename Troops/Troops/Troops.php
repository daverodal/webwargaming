<?php
use \Troops\UnitFactory;
/**
 *
 * Copyright 2012-2015 David Rodal
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
define("BRITISH_FORCE", 2);
define("GERMAN_FORCE", 1);

global $force_name;
$force_name[2] = "PlayerTwo";


$force_name[1] = "PlayerOne";

class Troops extends TroopsCore
{

    public $specialHexesMap = ['SpecialHexA' => 2, 'SpecialHexB' => 1, 'SpecialHexC' => 1];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;
    /* @var MoveRules */
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;
    public $moodkee;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {

        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "TroopsHeader.php";

    }


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {

        global $force_name;
        $blood = "lust";
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = $force_name[1];
        $deployOne = $playerTwo = $force_name[2];
        if($scenario->playerOne){
            $deployTwo = $playerOne = $scenario->playerOne;
        }
        if($scenario->playerTwo){
            $deployOne = $playerTwo = $scenario->playerTwo;
        }
        @include_once "view.php";
    }

    public function terrainInit($terrainDoc)
    {
        parent::terrainInit($terrainDoc);
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


    public function init()
    {

        UnitFactory::$injector = $this->force;

        /* German */
        $scenario = $this->scenario;

        if($scenario->seven){
            for ($i = 0; $i < 24; $i++) {
                UnitFactory::create(GERMAN_FORCE, "deployBox", 7, 4, 5,  STATUS_CAN_DEPLOY, "A", 1,  "German", 'infantry');
            }
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create(GERMAN_FORCE, "deployBox", 17, 10, 3,  STATUS_CAN_DEPLOY, "A", 1,  "German", 'mg');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(GERMAN_FORCE, "deployBox", 10, 25, 4,  STATUS_CAN_DEPLOY, "A", 1,  "German", 'artillery');
            }
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create(GERMAN_FORCE, "deployBox", 9, 25, 4,  STATUS_CAN_DEPLOY, "A", 1,  "German", 'howitzer');
            }
            for ($i = 0; $i < 0; $i++) {
                UnitFactory::create(GERMAN_FORCE, "deployBox", 50, 15, 8,  STATUS_CAN_DEPLOY, "A", 1,  "German", 'armor');
            }

            /* Belgian */

            for ($i = 0; $i < 11; $i++) {
                UnitFactory::create(BRITISH_FORCE, "deployBox", 7, 4, 5,  STATUS_CAN_DEPLOY, "B", 1,  "Belgian", 'infantry');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(BRITISH_FORCE, "deployBox", 5, 10, 6,  STATUS_CAN_DEPLOY, "B", 1,  "Belgian", 'mg');
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create(BRITISH_FORCE, "deployBox", 11, 25, 4,  STATUS_CAN_DEPLOY, "B", 1,  "Belgian", 'artillery');
            }

        }elseif($scenario->one){
            
            /* German */
            for ($i = 0; $i < 12; $i++) {
                UnitFactory::create(2, "deployBox", 7, 4, 5,  STATUS_CAN_DEPLOY, "B", 1,  "German", 'infantry');
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create(2, "deployBox", 17, 10, 3,  STATUS_CAN_DEPLOY, "B", 1,  "German", 'mg');
            }
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create(2, "deployBox", 10, 25, 4,  STATUS_CAN_DEPLOY, "B", 1,  "German", 'artillery');
            }
            for ($i = 0; $i < 1; $i++) {
                UnitFactory::create(2, "deployBox", 9, 25, 4,  STATUS_CAN_DEPLOY, "B", 1,  "German", 'artillery');
            }


            /* French */

            for ($i = 0; $i < 24; $i++) {
                UnitFactory::create(1, "deployBox", 3, 3, 5,  STATUS_CAN_DEPLOY, "A", 1,  "French", 'infantry');
            }
            for ($i = 0; $i < 6; $i++) {
                UnitFactory::create(1, "deployBox", 4, 9, 5,  STATUS_CAN_DEPLOY, "A", 1,  "French", 'mg');
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create(1, "deployBox", 6, 26, 6,  STATUS_CAN_DEPLOY, "A", 1,  "French", 'artillery');
            }

        }elseif($scenario->six){

            /* Austro-Hungarian */
            for ($i = 0; $i < 16; $i++) {
                UnitFactory::create(1, "deployBox", 5, 4, 4,  STATUS_CAN_DEPLOY, "B", 1,  "Austro-Hungarian", 'infantry');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(1, "deployBox", 4, 9, 5,  STATUS_CAN_DEPLOY, "B", 1,  "Austro-Hungarian", 'mg');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(1, "deployBox", 4, 1, 8,  STATUS_CAN_DEPLOY, "B", 1,  "Austro-Hungarian", 'cavalry');
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create(1, "deployBox", 10, 25, 4,  STATUS_CAN_DEPLOY, "B", 1,  "Austro-Hungarian", 'artillery');
            }



            /* Russian */

            for ($i = 0; $i < 12; $i++) {
                UnitFactory::create(2, "deployBox", 4, 4, 4,  STATUS_CAN_DEPLOY, "A", 1,  "Russian", 'infantry');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(2, "deployBox", 4, 10, 5,  STATUS_CAN_DEPLOY, "A", 1,  "Russian", 'mg');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(2, "deployBox", 3, 1, 8,  STATUS_CAN_DEPLOY, "A", 1,  "Russian", 'cavalry');
            }
            for ($i = 0; $i < 2; $i++) {
                UnitFactory::create(2, "deployBox", 2, 1, 8,  STATUS_CAN_DEPLOY, "A", 1,  "Russian", 'cavalry');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(2, "deployBox", 11, 25, 4,  STATUS_CAN_DEPLOY, "A", 1,  "Russian", 'artillery');
            }
        }else {
            /* German */

            for ($i = 0; $i < 36; $i++) {
                UnitFactory::create(GERMAN_FORCE, "deployBox", 7, 4, 5,  STATUS_CAN_DEPLOY, "A", 1,  "German", 'infantry');
            }
            for ($i = 0; $i < 3; $i++) {
                UnitFactory::create(GERMAN_FORCE, "deployBox", 17, 10, 3,  STATUS_CAN_DEPLOY, "A", 1,  "German", 'mg');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(GERMAN_FORCE, "deployBox", 10, 25, 4,  STATUS_CAN_DEPLOY, "A", 1,  "German", 'artillery');
            }

            /* British */

            for ($i = 0; $i < 20; $i++) {
                UnitFactory::create(BRITISH_FORCE, "deployBox", 11, 6, 5,  STATUS_CAN_DEPLOY, "B", 1,  "British", 'infantry');
            }
            for ($i = 0; $i < 5; $i++) {
                UnitFactory::create(BRITISH_FORCE, "deployBox", 6, 10, 5,  STATUS_CAN_DEPLOY, "B", 1,  "British", 'mg');
            }
            for ($i = 0; $i < 4; $i++) {
                UnitFactory::create(BRITISH_FORCE, "deployBox", 11, 25, 4,  STATUS_CAN_DEPLOY, "B", 1,  "British", 'artillery');
            }
        }

    }


    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        $crt = new \Troops\CombatResultsTable();
        $this->combatRules->injectCrt($crt);
        if ($data) {

        } else {

            $this->victory = new Victory("Troops\\Troops\\troopsVictoryCore");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = 0;
            $this->moveRules->exitZoc = 0;
            $this->moveRules->noZocZoc = false;
            $this->moveRules->zocBlocksRetreat = false;
            $this->moveRules->oneHex = false;

            $this->gameRules->gameHasCombatResolutionMode = false;
            // game data
            if($scenario->seven){
                $this->gameRules->setMaxTurn(12);
            }elseif($scenario->one){
                $this->gameRules->setMaxTurn(18);
            }else{
                $this->gameRules->setMaxTurn(15);
            }

            if($scenario->one) {

                $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
                $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
                $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
                $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


                $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, RED_FORCE, BLUE_FORCE, false);
                $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            }else{
                $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
                $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
                $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
                $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


                $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
                $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            }

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_FIRST_COMBAT_PHASE, RED_FIRST_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_FIRST_COMBAT_PHASE, BLUE_COMBAT_RES_PHASE, COMBAT_RESOLUTION_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_COMBAT_RES_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE,BLUE_FORCE , false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_SECOND_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_SECOND_COMBAT_PHASE, BLUE_SECOND_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_SECOND_COMBAT_PHASE, RED_COMBAT_RES_PHASE, COMBAT_RESOLUTION_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_COMBAT_RES_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}