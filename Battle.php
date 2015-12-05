<?php
// Copyright 2012-2015 David Rodal

// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version->

// This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

class LandBattle extends Battle{

    static function playAs($name, $wargame, $arg = false)
    {
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        @include_once "playMulti.php";
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
            $playerData = $doc->wargame->mapViewer[$player];
        } else {
            $playerData = $doc->wargame->mapData[0];
        }
        $mapGrid = new MapGrid($playerData);
        $mapUnits = array();
        $moveRules = $doc->wargame->moveRules;
        $combatRules = $doc->wargame->combatRules;
        $combats = $combatRules->combats;
        if(!$combats){
            $combats = $combatRules->combatsToResolve;
        }
        if($playerData->trueRows && $combats) {
            foreach ($combats as $combat) {
                foreach ($combat->thetas as $theta) {
                    foreach ($theta as $key => $val) {
                        $theta->$key += 90;
                    }
                }
            }
        }
        $combats = $combatRules->resolvedCombats;
        if($playerData->trueRows && $combats) {
            foreach ($combats as $combat) {
                foreach ($combat->thetas as $theta) {
                    foreach ($theta as $key => $val) {
                        $theta->$key += 90;
                    }
                }
            }
        }
        $display = $doc->wargame->display;
        $units = $force->units;
        $attackingId = $doc->wargame->gameRules->attackingForceId;
        foreach ($units as $unit) {
            $unit = UnitFactory::build($unit);
            if (is_object($unit->hexagon)) {
//                $unit->hexagon->parent = $unit->parent;
            } else {
                $unit->hexagon = new Hexagon($unit->hexagon);
            }
//            $unit->hexagon->parent = $unit->parent;
            $mapGrid->setHexagonXY($unit->hexagon->x, $unit->hexagon->y);
            $mapUnit = $unit->fetchData();

            $mapUnit->x = $mapGrid->getPixelX();
            $mapUnit->y = $mapGrid->getPixelY();

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
            $u->isImproved = $unit->isImproved;
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
                $hex = new Hexagon($k);
                $mapGrid->setHexagonXY($hex->getX(), $hex->getY());
                $n = new stdClass();
                $moveRules->moves->{$k}->pixX = $mapGrid->getPixelX();
                $moveRules->moves->{$k}->pixY = $mapGrid->getPixelY();
                $pointsLeft = sprintf("%.2f",$moveRules->moves->{$k}->pointsLeft);
                $pointsLeft = preg_replace("/\.0*$/",'',$pointsLeft);
                $pointsLeft = preg_replace("/(\.[1-9]*)0*/","$1",$pointsLeft);
                $moveRules->moves->{$k}->pointsLeft = $pointsLeft;
                unset($moveRules->moves->$k->isValid);
            }
            if (false && $moveRules->path) {
                foreach ($moveRules->path as $hexName) {
                    $hex = new Hexagon($hexName);
                    $mapGrid->setHexagonXY($hex->x, $hex->y);

                    $path = new stdClass();
                    $path->pixX = $mapGrid->getPixelX();
                    $path->pixY = $mapGrid->getPixelY();
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

        $newMapSymbols = new stdClass();
        if ($doc->wargame->mapData->mapSymbols) {
            $mapSymbols = $doc->wargame->mapData->mapSymbols;
            foreach ($mapSymbols as $k => $v) {
                $hex = new Hexagon($k);
                $mapGrid->setHexagonXY($hex->x, $hex->y);
                $newMapSymbols->{"x" . intval($mapGrid->getPixelX()) . "y" . intval($mapGrid->getPixelY())} = $v;
            }
        }
        $mapSymbols = $newMapSymbols;

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
            foreach($flashMessages as $key=>$mess){
                $match = [];
                if(preg_match("/@hex (\d*)/", $mess, $match)){
                    $hex = new Hexagon($match[1]);
                    $mapGrid->setHexagonXY($hex->x, $hex->y);
                    $flashMessages[$key] = "@hex x" . intval($mapGrid->getPixelX()) . "y" . intval($mapGrid->getPixelY());
                }
            }

        }
        $specialHexesChanges = $newSpecialHexesChanges;
        $specialHexesVictory = $newSpecialHexesVictory;
        $gameRules->playerStatus = $doc->playerStatus;
        $mapViewer = $playerData;
        $clock = "The turn is " . $gameRules->turn . ". The Phase is " . $phase_name[$gameRules->phase] . ". The mode is " . $mode_name[$gameRules->mode];
        return compact("mapSymbols", "mapViewer", "sentBreadcrumbs", "phaseClicks", "click", "revs", "vp", "flashMessages", "specialHexesVictory", "specialHexes", "specialHexesChanges", "combatRules", 'force', 'seq', 'chats', 'chatsIndex', 'last_seq', 'users', 'games', 'clock', 'mapUnits', 'moveRules', 'gameRules');

    }

    public function save()
    {
        $data = new stdClass();
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
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
        return $data;
    }

    function poke($event, $id, $x, $y, $user, $click)
    {

        $playerId = $this->gameRules->attackingForceId;
        if ($this->players[$this->gameRules->attackingForceId] != $user) {
            if($event !== SELECT_ALT_COUNTER_EVENT){
                return false;
            }
        }

        switch ($event) {
            case SELECT_MAP_EVENT:
                $mapGrid = new MapGrid($this->mapViewer[0]);
                $mapGrid->setPixels($x, $y);
                return $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(), $click);
                break;

            case SELECT_COUNTER_EVENT:
            case SELECT_ALT_COUNTER_EVENT:

            if (strpos($id, "Hex")) {
                    $matchId = array();
                    preg_match("/^[^H]*/", $id, $matchId);
                    $matchHex = array();
                    preg_match("/Hex(.*)/", $id, $matchHex);
                    $id = $matchId[0];
                    $hexagon = $matchHex[1];
                    if($event === SELECT_COUNTER_EVENT){
                        $event = SELECT_MAP_EVENT;
                    }
                }
                /* fall through */
            case SELECT_SHIFT_COUNTER_EVENT:
            /* fall through */
            case COMBAT_PIN_EVENT:

            return $this->gameRules->processEvent($event, $id, $hexagon, $click);

                break;

            case SELECT_BUTTON_EVENT:
                return $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0, $click);
                break;

            case KEYPRESS_EVENT:
                return $this->gameRules->processEvent(KEYPRESS_EVENT, $id, null, $click);
                break;

        }
        return true;
    }
}
