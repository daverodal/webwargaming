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



//require_once "Battle.php";
require_once "crtTraits.php";
require_once "CombatRules.php";
require_once "crt.php";
require_once "AreaForce.php";
require_once "gameRules.php";
require_once "hexagon.php";
require_once "hexpart.php";
require_once "los.php";
require_once "AreaData.php";
require_once "AreaTerrain.php";
require_once "moveRules.php";
require_once "display.php";
require_once "victory.php";
require_once "victoryCore.php";
require_once "UnitFactory.php";
require_once "AreaMoveRules.php";




class Area1
{
    public function poke($event, $id, $x, $y, $user, $click)
    {

        $playerId = $this->gameRules->attackingForceId;
        if ($this->players[$this->gameRules->attackingForceId] != $user) {
            return false;
        }

        switch ($event) {
            case SELECT_MAP_EVENT:
                $mapGrid = new MapGrid($this->mapViewer[0]);
                $mapGrid->setPixels($x, $y);
                return $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(), $click);
                break;

            case SELECT_COUNTER_EVENT:
                /* fall through */
            case SELECT_SHIFT_COUNTER_EVENT:
                /* fall through */
            case COMBAT_PIN_EVENT:

                return $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id), $click);

                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0, $click);
                break;

            case KEYPRESS_EVENT:
                $this->gameRules->processEvent(KEYPRESS_EVENT, $id, null, $click);
                break;

        }
        return true;
    }


    static function transformChanges($doc, $last_seq, $user){
        global $mode_name, $phase_name;

        $chatsIndex = 0;/* remove me */
        $click = $doc->_rev;
        $matches = array();
        preg_match("/^([0-9]+)-/", $click, $matches);
        $click = $matches[1];
        $games = $doc->games;
        $chats = array_slice($doc->chats, $chatsIndex);
        $chatsIndex = count($doc->chats);
        $users = $doc->users;
        $clock = $doc->clock;
        $players = $doc->wargame->players;
        $player = array_search($user, $players);
        if ($player === false) {
            $player = 0;
        }
        $force = $doc->wargame->force;
        $wargame = $doc->wargame;
        $gameName = $doc->gameName;
        $gameRules = $wargame->gameRules;
        $fogDeploy = false;
        if($wargame->scenario->fogDeploy && $doc->playerStatus == "multi"){
            $fogDeploy = true;
        }

//        $revs = $doc->_revs_info;
        Battle::loadGame($gameName, $doc->wargame->arg);
//Battle::getHeader();
        if (isset($doc->wargame->mapViewer)) {
            $playerData = $doc->wargame->mapViewer[0];
        } else {
            $playerData = $doc->wargame->mapData[0];
        }
        $mapUnits = array();
        $moveRules = $doc->wargame->moveRules;
        $combatRules = $doc->wargame->combatRules;
        $display = $doc->wargame->display;
        $units = $force->units;
        $attackingId = $doc->wargame->gameRules->attackingForceId;
        foreach ($units as $unit) {
            $unit = UnitFactory::build($unit);
            $mapUnit = $unit->fetchData();

            if($fogDeploy && ($gameRules->phase == RED_DEPLOY_PHASE || $gameRules->phase == BLUE_DEPLOY_PHASE) &&  $unit->forceId !== $player){
                if($unit->hexagon->parent == "gameImages"){
                    $mapUnit = new stdClass();
                }
            }
            $mapUnits[] = $mapUnit;
        }
        $turn = $doc->wargame->gameRules->turn;
        foreach ($units as $i => $unit) {
            $u = new StdClass();
            $u->status = $unit->status;
            $u->moveAmountUsed = $unit->moveAmountUsed;
            $u->maxMove = $unit->maxMove;
            $u->forceId = $unit->forceId;
            $u->forceMarch = $unit->forceMarch;
            $u->isDisrupted = $unit->isDisrupted;
            if ($unit->reinforceTurn > $turn) {
                $u->reinforceTurn = $unit->reinforceTurn;
            }
            $units[$i] = $u;
        }
        if($fogDeploy) {
            if ($gameRules->phase == BLUE_DEPLOY_PHASE && $player === RED_FORCE) {
                $moveRules->moves = new stdClass();
            }
            if ($gameRules->phase == RED_DEPLOY_PHASE && $player === BLUE_FORCE) {
                $moveRules->moves = new stdClass();
            }
        }
        if ($moveRules->moves) {
            foreach ($moveRules->moves as $k => $move) {
                unset($moveRules->moves->$k->isValid);
            }
            if ($moveRules->path) {
                foreach ($moveRules->path as $hexName) {
                    $moveRules->hexPath[] = $path;
                }
            }
        }
        $force->units = $units;
        $gameRules = $wargame->gameRules;
        $gameRules->display = $display;
        $gameRules->phase_name = $phase_name;
        $gameRules->mode_name = $mode_name;
        $gameRules->exchangeAmount = $force->exchangeAmount;
        $newSpecialHexes = new stdClass();
        $phaseClicks = $gameRules->phaseClicks;
        if ($doc->wargame->mapData->specialHexes) {
            $specialHexes = $doc->wargame->mapData->specialHexes;
            foreach ($specialHexes as $k => $v) {
                $hex = new Hexagon($k);
                $mapGrid->setHexagonXY($hex->x, $hex->y);

                $path = new stdClass();
                $newSpecialHexes->{"x" . intval($mapGrid->getPixelX()) . "y" . intval($mapGrid->getPixelY())} = $v;
            }
        }
        $sentBreadcrumbs = new stdClass();
        if ($doc->wargame->mapData->breadcrumbs) {
            $breadcrumbs = $doc->wargame->mapData->breadcrumbs;
            $breadcrumbKey = "/$turn"."t".$attackingId."a/";

            foreach($breadcrumbs as $key => $crumbs){
                if(!preg_match($breadcrumbKey, $key)){
                    continue;
                }
                $matches = array();
                preg_match("/m(\d*)$/",$key,$matches);
                if(strlen($matches[1]) < 1){
                    continue;
                }
                $unitId = $matches[1];
                if(!isset($sentBreadcrumbs->$unitId)){
                    $sentBreadcrumbs->$unitId = [];
                }
                $sentMoves = $sentBreadcrumbs->$unitId;
                foreach($crumbs as $crumb){
                    if(!isset($crumb->type)){
                        $type = "move";
                    }else{
                        $type = $crumb->type;
                    }
                    switch($type){
                        case "move":
                            if($crumb->fromHex === "0000"){
                                continue;
                            }
                            $fromHex = new Hexagon($crumb->fromHex);
                            $mapGrid->setHexagonXY($fromHex->x, $fromHex->y);
                            $crumb->fromX = intval($mapGrid->getPixelX());
                            $crumb->fromY = intval($mapGrid->getPixelY());

                            $toHex = new Hexagon($crumb->toHex);
                            $mapGrid->setHexagonXY($toHex->x, $toHex->y);
                            $crumb->toX = intval($mapGrid->getPixelX());
                            $crumb->toY = intval($mapGrid->getPixelY());
                            break;
                        case "combatResult":
                            if($crumb->hex){
                                $hex = new Hexagon($crumb->hex);
                                $mapGrid->setHexagonXY($hex->x, $hex->y);
                                $crumb->hexX = intval($mapGrid->getPixelX());
                                $crumb->hexY = intval($mapGrid->getPixelY());
                            }

                            break;
                    }


                    $sentMoves[] = $crumb;
                }
                $sentBreadcrumbs->$unitId = $sentMoves;
            }
        }
        $specialHexes = $newSpecialHexes;
        $newSpecialHexesChanges = new stdClass();
        if ($doc->wargame->mapData->specialHexesChanges) {
            $specialHexesChanges = $doc->wargame->mapData->specialHexesChanges;
            foreach ($specialHexesChanges as $k => $v) {
                $hex = new Hexagon($k);
                $mapGrid->setHexagonXY($hex->x, $hex->y);

                $path = new stdClass();
                $newSpecialHexesChanges->{"x" . intval($mapGrid->getPixelX()) . "y" . intval($mapGrid->getPixelY())} = $v;
            }
        }
        $newSpecialHexesVictory = new stdClass();

        if ($doc->wargame->mapData->specialHexesVictory) {
            $specialHexesVictory = $doc->wargame->mapData->specialHexesVictory;
            foreach ($specialHexesVictory as $k => $v) {
                $hex = new Hexagon($k);
                $mapGrid->setHexagonXY($hex->x, $hex->y);

                $path = new stdClass();
                $newSpecialHexesVictory->{"x" . intval($mapGrid->getPixelX()) . "y" . intval($mapGrid->getPixelY())} = $v;
            }
        }
        $vp = $doc->wargame->victory->victoryPoints;
        $flashMessages = $gameRules->flashMessages;
        if (count($flashMessages)) {

        }
//        $flashMessages = array("Victory","Is","Mine");
        $specialHexesChanges = $newSpecialHexesChanges;
        $specialHexesVictory = $newSpecialHexesVictory;
        $gameRules->playerStatus = $doc->playerStatus;
        $clock = "The turn is " . $gameRules->turn . ". The Phase is " . $phase_name[$gameRules->phase] . ". The mode is " . $mode_name[$gameRules->mode];
        return compact("sentBreadcrumbs", "phaseClicks", "click", "revs", "vp", "flashMessages", "specialHexesVictory", "specialHexes", "specialHexesChanges", "combatRules", 'force', 'seq', 'chats', 'chatsIndex', 'last_seq', 'users', 'games', 'clock', 'mapUnits', 'moveRules', 'gameRules');

    }

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
;
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        @include_once "playMulti.php";
    }

    function terrainInit($terrainDoc ){
        $areas = $terrainDoc->terrain->areas;
        $this->players = array("", "", "");

        foreach($areas as $aName => $aValue){
            $this->areaData->addArea($aName);
        }

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
        $data->terrain = $this->terrain;
        $data->areaData = $this->areaData;
        $data->display = $this->display;
        $data->moveRules = $this->moveRules->save();
        $data->gameRules = $this->gameRules->save();
        $data->force = $this->force;
        $data->players = $this->players;
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

        $unitNum = 1;
        UnitFactory::$injector = $this->force;
        UnitFactory::create("xxx", LOYALIST_FORCE, "a1", "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "loyalist",  'inf', $unitNum++);
        UnitFactory::create("xxx", LOYALIST_FORCE, "a2", "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "loyalist",  'inf', $unitNum++);
        UnitFactory::create("xxx", LOYALIST_FORCE, "b1", "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "loyalist",  'inf', $unitNum++);

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



    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {





        $this->areaData = AreaData::getInstance();
        $this->mapData = $this->areaData;


        if ($data) {

            $this->force = new AreaForce($data->force);
            $this->terrain = new AreaTerrain($data->terrain);


            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->moveRules = new AreaMoveRules($this->force, $this->terrain, $data->moveRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->victory = new Victory($data);

            $this->players = $data->players;
        } else {

            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;
            $this->display = new stdClass();


//            $this->display = new Display();
//            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());

            $this->force = new AreaForce();

            $this->terrain = new AreaTerrain();
            $this->moveRules = new AreaMoveRules($this->force, $this->terrain);


            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
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