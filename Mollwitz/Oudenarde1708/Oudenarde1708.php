<?php
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


define("ANGLO_ALLIED_FORCE", 1);
define("FRENCH_FORCE", 2);

global $force_name;
$force_name[ANGLO_ALLIED_FORCE] = "Anglo Allied";
$force_name[FRENCH_FORCE] = "French";

require_once "JagCore.php";

class Oudenarde1708 extends JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>1, 'SpecialHexC'=>0];

    public
    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "Oudenarde1708Header.php";

    }


    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        $deployTwo = $playerOne = "AngloAllied";
        $deployOne = $playerTwo = "French";
        @include_once "playMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "AngloAllied";
        $deployOne = $playerTwo = "French";
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
        $prussianDeploy = "B";
        $scenario = $this->scenario;
//        var_dump($scenario);die("peace");

        if($scenario->slightlyBiggerFrench){
            $numThreeThrees = 11;
        }

//        $unitSets = [];
//        $unitSet = new stdClass();
//        $unitSet->num = 4;
//        $unitSet->forceId = ANGLO_ALLIED_FORCE;
//        $unitSet->combat = 6;
//        $unitSet->movement = 3;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "AngloAllied";
//        $unitSet->class = "infantry";
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = 12;
//        $unitSet->forceId = ANGLO_ALLIED_FORCE;
//        $unitSet->combat = 4;
//        $unitSet->movement = 3;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "AngloAllied";
//        $unitSet->class = "infantry";
//
//        $unitSets[] = $unitSet;
//
//
//        $unitSet = new stdClass();
//        $unitSet->num = 5;
//        $unitSet->forceId = ANGLO_ALLIED_FORCE;
//        $unitSet->combat = 4;
//        $unitSet->movement = 5;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "AngloAllied";
//        $unitSet->class = "cavalry";
//
//        $unitSets[] = $unitSet;
//
//
//        $unitSet = new stdClass();
//        $unitSet->num = 2;
//        $unitSet->forceId = ANGLO_ALLIED_FORCE;
//        $unitSet->combat = 5;
//        $unitSet->movement = 5;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "AngloAllied";
//        $unitSet->class = "cavalry";
//
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = 5;
//        $unitSet->forceId = ANGLO_ALLIED_FORCE;
//        $unitSet->combat = 3;
//        $unitSet->movement = 2;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 3;
//        $unitSet->nationality = "AngloAllied";
//        $unitSet->class = "artillery";
//
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = 3;
//        $unitSet->forceId = FRENCH_FORCE;
//        $unitSet->combat = 5;
//        $unitSet->movement = 3;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "French";
//        $unitSet->class = "infantry";
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = 7;
//        $unitSet->forceId = FRENCH_FORCE;
//        $unitSet->combat = 4;
//        $unitSet->movement = 3;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "French";
//        $unitSet->class = "infantry";
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = $numThreeThrees;
//        $unitSet->forceId = FRENCH_FORCE;
//        $unitSet->combat = 3;
//        $unitSet->movement = 3;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "French";
//        $unitSet->class = "infantry";
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = 2;
//        $unitSet->forceId = FRENCH_FORCE;
//        $unitSet->combat = 5;
//        $unitSet->movement = 5;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "French";
//        $unitSet->class = "cavalry";
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = 4;
//        $unitSet->forceId = FRENCH_FORCE;
//        $unitSet->combat = 4;
//        $unitSet->movement = 5;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 1;
//        $unitSet->nationality = "French";
//        $unitSet->class = "cavalry";
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = 3;
//        $unitSet->forceId = FRENCH_FORCE;
//        $unitSet->combat = 3;
//        $unitSet->movement = 2;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 3;
//        $unitSet->nationality = "French";
//        $unitSet->class = "artillery";
//        $unitSets[] = $unitSet;
//
//        $unitSet = new stdClass();
//        $unitSet->num = 2;
//        $unitSet->forceId = FRENCH_FORCE;
//        $unitSet->combat = 2;
//        $unitSet->movement = 2;
//        $unitSet->reinforce = "A";
//        $unitSet->range = 3;
//        $unitSet->nationality = "French";
//        $unitSet->class = "artillery";
//        $unitSets[] = $unitSet;


        $unitSets = $scenario->units;


        foreach($unitSets as $unitSet) {
            for ($i = 0; $i < $unitSet->num; $i++) {
                $this->force->addUnit("infantry-1", $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->combat, $unitSet->movement, true, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class);
            }
        }
                /* AngloAllied */
//        for ($i = 0; $i < 4; $i++) {
//            $this->force->addUnit("infantry-1", ANGLO_ALLIED_FORCE, "deployBox", "", 6, 6, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
//        }
//        for ($i = 0; $i < 12; $i++) {
//            $this->force->addUnit("infantry-1", ANGLO_ALLIED_FORCE, "deployBox", "AngloAlliedInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'infantry');
//        }
//        for ($i = 0; $i < 5; $i++) {
//            $this->force->addUnit("infantry-1", ANGLO_ALLIED_FORCE, "deployBox", "AngloAlliedCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
//        }
//        for ($i = 0; $i < 2; $i++) {
//            $this->force->addUnit("infantry-1", ANGLO_ALLIED_FORCE, "deployBox", "AngloAlliedCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "AngloAllied", false, 'cavalry');
//        }
//        for ($i = 0; $i < 5; $i++) {
//            $this->force->addUnit("infantry-1", ANGLO_ALLIED_FORCE, "deployBox", "AngloAlliedArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "A", 1, 3, "AngloAllied", false, 'artillery');
//        }
//

        /* French */
//        for ($i = 0; $i < 3; $i++) {
//            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
//        }
//        for ($i = 0; $i < 7; $i++) {
//            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
//        }
//        for ($i = 0; $i < $numThreeThrees; $i++) {
//            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'infantry');
//        }
//        for ($i = 0; $i < 2; $i++) {
//            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'cavalry');
//        }
//        for ($i = 0; $i < 4; $i++) {
//            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "French", false, 'cavalry');
//        }
//        for ($i = 0; $i < 3; $i++) {
//            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "B", 1, 3, "French", false, 'artillery');
//        }
//
//        for ($i = 0; $i < 2; $i++) {
//            $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, 3, "French", false, 'artillery');
//        }

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->roadHex = $data->roadHex;
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
        } else {
            $this->victory = new Victory("Mollwitz/Oudenarde1708/oudenarde1708VictoryCore.php");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->zocBlocksRetreat = true;

            // game data

            $this->gameRules->setMaxTurn(10);
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