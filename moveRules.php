<?php
// moveRules.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.


$numWalks = 0;
class MoveRules
{
    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;

    /* @var MapData */
    public $mapData;
    // local variables
    public $movingUnitId;
    public $anyUnitIsMoving;
    public $railMove;
    public $storm;
    private $moves;
    private $path;
    private $moveQueue;
    public $stickyZOC;
    public $enterZoc = "stop";
    public $exitZoc = 0;
    public $noZocZoc = false;
    public $noZocZocOneHex = true;
    public $oneHex = true;
    public $zocBlocksRetreat = true;

    function save()
    {
        $data = new StdClass();
        foreach ($this as $k => $v) {
            if (is_object($v) && $k != "path" && $k != "moves") {
                continue;
            }
            $data->$k = $v;
        }
        return $data;
    }

    function __construct($Force, $Terrain, $data = null)
    {
        // Class references

        $this->mapData = MapData::getInstance();
        $this->moves = new stdClass();
        $this->path = new stdClass();
        $this->force = $Force;
        $this->terrain = $Terrain;

        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        } else {
            $this->movingUnitId = NONE;
            $this->anyUnitIsMoving = false;
            $this->storm = false;
            $this->railMove = true;
            $this->stickyZOC = false;
        }
    }

// id will be map if map event, id will be unit id if counter event
    function moveUnit($eventType, $id, $hexagon, $turn)
    {
        $dirty = false;
        if ($eventType == SELECT_MAP_EVENT) {
            if ($this->anyUnitIsMoving) {
                // click on map, so try to move
                /* @var Unit $movingUnit */
                $movingUnit = $this->force->units[$this->movingUnitId];
                if ($movingUnit->unitIsMoving() == true) {
                    $newHex = $hexagon;
                    if ($this->moves->$newHex) {
                        $this->path = $this->moves->$newHex->pathToHere;

                        foreach ($this->path as $moveHex) {
                            $this->move($movingUnit, $moveHex);

                        }
                        $movesLeft = $this->moves->$newHex->pointsLeft;
                        $this->moves = new stdClass();

                        $this->move($movingUnit, $newHex);
                        $this->path = array();
                        if ($this->anyUnitIsMoving) {
                            $this->moveQueue = array();
                            $hexPath = new HexPath();
                            $hexPath->name = $newHex; //$startHex->name;
                            $hexPath->pointsLeft = $movesLeft;
                            $hexPath->pathToHere = array();
                            $hexPath->firstHex = false;
                            $hexPath->isOccupied = true;
                            $this->moveQueue[] = $hexPath;
                            $this->bfsMoves();

                            $movesAvail = 0;
                            foreach ($this->moves as $move) {
                                if ($move->isOccupied || !$move->isValid) {
                                    continue;
                                }
                                $movesAvail++;
                            }

                            if ($movesAvail === 0) {
                                $this->stopMove($movingUnit);
                            }
                        }
                        $dirty = true;
                    }
                }
                if ($this->force->unitIsReinforcing($this->movingUnitId) == true) {
                    $this->reinforce($this->movingUnitId, new Hexagon($hexagon));
                    $this->calcMove($id);
                    $dirty = true;
                }
                if ($this->force->unitIsDeploying($this->movingUnitId) == true) {
                    $this->deploy($this->movingUnitId, new Hexagon($hexagon));
                    $dirty = true;
                }
            }
        } else // click on a unit
        {
            if ($this->anyUnitIsMoving == true) {
                if ($id == $this->movingUnitId) {
                    $movingUnit = $this->force->units[$id];
                    // clicked on moving or reinforcing unit
                    /* @var Unit $movingUnit */
                    if ($movingUnit->unitIsMoving() == true) {
                        $this->stopMove($movingUnit);
                        $dirty = true;
                    }
                    if ($this->force->unitIsReinforcing($id) == true) {
                        $this->stopReinforcing($id);
                        $dirty = true;
                    }
                    if ($this->force->unitIsDeploying($id) == true) {
                        $this->stopDeploying($id);
                        $dirty = true;
                    }
                } else {
                    /* @var Unit $movingUnit */
                    $movingUnit = $this->force->units[$this->movingUnitId];
                    $movingUnitId = $this->movingUnitId;
                    if ($movingUnit->unitIsMoving() == true) {
                        $this->stopMove($movingUnit);
                        $dirty = true;
                    }
                    if ($this->force->unitIsReinforcing($movingUnitId) == true) {
                        $this->stopReinforcing($movingUnitId);
                        $dirty = true;
                    }
                    if ($this->force->unitIsDeploying($movingUnitId) == true) {
                        $this->stopDeploying($movingUnitId);
                        $dirty = true;
                    }

                    if ($eventType == KEYPRESS_EVENT) {
                        if ($this->force->unitCanMove($movingUnitId) == true) {
                            $this->startMoving($movingUnitId);
                            $this->calcMove($movingUnitId);
                            $dirty = true;
                        }
                    } else {
                        if ($this->force->unitCanMove($id) == true) {
                            $this->startMoving($id);
                            $this->calcMove($id);
                            $dirty = true;
                        }
                        if ($this->force->unitCanReinforce($id) == true) {
                            $this->startReinforcing($id, $turn);
                            $dirty = true;
                        }
                        if ($this->force->unitCanDeploy($id) == true) {
                            $this->startDeploying($id, $turn);
                            $dirty = true;
                        }
                    }
                    // clicked on another unit
                    return $dirty;
//                    $this->moveOver($this->movingUnitId, $id, $hexagon);
                }
            } else {
                // no one is moving, so start new move
                if ($this->force->unitCanMove($id) == true) {
                    $this->startMoving($id);
//                    $this->calcSupply($id);
                    $this->calcMove($id);
                    $dirty = true;
                }
                if ($this->force->unitCanReinforce($id) == true) {
                    $this->startReinforcing($id, $turn);
                    $dirty = true;
                }
                if ($this->force->unitCanDeploy($id) == true) {
                    $this->startDeploying($id, $turn);
                    $dirty = true;
                }
            }
        }
        return $dirty;
    }

    function calcSupplyHex($startHex, $goal, $bias = array(), $attackingForceId = false, $maxHex = false)
    {
        $this->moves = new stdClass();
        $this->moveQueue = array();
        $hexPath = new HexPath();
        $hexPath->name = $startHex;
        $hexPath->pathToHere = array();
        $hexPath->firstHex = true;
        $hexPath->isOccupied = true;
        if($maxHex !== false){
            $hexPath->pointsLeft = $maxHex;
            $maxHex = true;
        }
        $this->moveQueue[] = $hexPath;
        $ret = $this->bfsCommunication($goal, $bias, $attackingForceId, $maxHex);
        $this->moves = new stdClass();
        $this->moveQueue = array();
        return $ret;
    }
    function calcRoadSupply($forceId, $goal, $bias = array())
    {
        $attackingForceId = $forceId;

        $this->moves = new stdClass();
        $this->moveQueue = array();
        if(!is_array($goal)){
            $goals = [$goal];
        }else{
            $goals = $goal;
        }
        foreach($goals as $aGoal){
            $hexPath = new HexPath();
            $hexPath->name = $aGoal;
            $hexPath->pathToHere = array();
            $hexPath->firstHex = true;
            $hexPath->isOccupied = true;
            $this->moveQueue[] = $hexPath;
        }
        $ret = $this->bfsRoadTrace($goal, $bias, $attackingForceId);
        $moves = $this->moves;
        $goal = [];
        foreach($moves as $hex => $move){
            $goal[] = $hex;
        }
        $this->moves = new stdClass();
        $this->moveQueue = array();
        return $goal;
    }
    function calcSupply($id, $goal, $bias = array(), $maxHex = false)
    {
        global $numWalks;
        global $numBangs;
        $attackingForceId = $this->force->units[$id]->forceId;
        $startHex = $this->force->units[$id]->hexagon;
        return $this->calcSupplyHex($startHex->name, $goal, $bias, $attackingForceId, $maxHex);
    }

    function calcMove($id)
    {
        global $numWalks;
        global $numBangs;
        $numWalks = 0;
        $numBangs = 0;
        $startHex = $this->force->units[$id]->hexagon;
        $movesLeft = $this->force->units[$id]->maxMove - $this->force->units[$id]->moveAmountUsed;
        $this->moves = new stdClass();
        $this->moveQueue = array();
        $hexPath = new HexPath();
        $hexPath->name = $startHex->name;
        $hexPath->pointsLeft = $movesLeft;
        $hexPath->pathToHere = array();
        $hexPath->firstHex = true;
        $hexPath->isOccupied = true;
        $this->moveQueue[] = $hexPath;
        $this->bfsMoves();

    }

    function calcRetreat($id)
    {
        echo "CalcRet $id";
        global $numWalks;
        global $numBangs;
        $numWalks = 0;
        $numBangs = 0;
        $startHex = $this->force->units[$id]->hexagon;
        $movesLeft = $this->force->units[$id]->retreatCountRequired;
        $this->moves = new stdClass();
        $this->moveQueue = array();
        $hexPath = new HexPath();
        $hexPath->name = $startHex->name;
        $hexPath->pointsLeft = $movesLeft;
        $hexPath->pathToHere = array();
        $hexPath->firstHex = true;
        $hexPath->isOccupied = true;
        $this->moveQueue[] = $hexPath;
        $this->bfsRetreat();
        $moves = $this->moves;
        foreach($moves as $key => $val){
            if($moves->$key->pointsLeft){
                unset($moves->$key);
            }
        }

    }

    function bfsMoves()
    {
        $hist = array();
        $cnt = 0;
        $unit = $this->force->units[$this->movingUnitId];
        while (count($this->moveQueue) > 0) {
            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
            $movePoints = $hexPath->pointsLeft;
            if (!$hexNum) {
                continue;
            }
            if (!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;
            } else {
                /* invalid hex */
                if ($this->moves->$hexNum->isValid === false) {
                    continue;
                }
                /* already been here with more points */
                if ($this->moves->$hexNum->pointsLeft >= $movePoints) {
                    continue;

                }
            }
            /* @var MapHex $mapHex */
            $mapHex = $this->mapData->getHex($hexNum);

            if ($mapHex->isOccupied($this->force->attackingForceId)) {
                $this->moves->$hexNum->isOccupied = true;
            }
            if ($mapHex->isOccupied($this->force->defendingForceId)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            $this->moves->$hexNum->pointsLeft = $movePoints;
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            if ($this->moves->$hexNum->isZoc == NULL) {
                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex);
            }
            $exitCost = 0;
            if ($this->moves->$hexNum->isZoc) {
                if (is_numeric($this->exitZoc)) {
                    $exitCost += $this->exitZoc;
                }
                if (!$hexPath->firstHex) {
                    if ($this->enterZoc == 'stop') {
                        continue;
                    }
                }

            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            for ($i = 1; $i <= 6; $i++) {
                $newHexNum = $mapHex->neighbors[$i - 1];
                $gnuHex = Hexagon::getHexPartXY($newHexNum);
                if (!$gnuHex) {
                    continue;
                }

                /* This can and should be dealt with by the "blocked" moveAmount below
                 * History, can't live with it can't live with it
                 */
                if ($this->terrain->terrainIsHexSide($hexNum, $newHexNum, "blocked")) {
                    continue;
                }
                if (!$unit->forceMarch && $this->terrain->terrainIsHexSide($hexNum, $newHexNum, "blocksnonroad")) {
                    continue;
                }
                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {

                    continue;
                }
                $moveAmount = $this->terrain->getTerrainMoveCost($hexNum, $newHexNum, $unit->forceMarch, $unit);
                if ($moveAmount === "blocked") {
                    continue;
                }
                $moveAmount += $exitCost;
                $newMapHex = $this->mapData->getHex($newHexNum);
                if ($newMapHex->isOccupied($this->force->defendingForceId)) {
                    continue;
                }
                $isZoc = $this->force->mapHexIsZOC($newMapHex);
                if ($isZoc && is_numeric($this->enterZoc)) {
                    $moveAmount += (int)$this->enterZoc;
                }
                if ($moveAmount <= 0) {
                    $moveAmount = 1;
                }
                if ($this->noZocZoc && $isZoc && $hexPath->isZoc) {
                    continue;
                }
                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if ($movePoints - $moveAmount >= 0 || (($isZoc && $hexPath->isZoc && !$this->noZocZocOneHex) && $hexPath->firstHex === true) || ($hexPath->firstHex === true && $this->oneHex === true && !($isZoc && $hexPath->isZoc && !$this->noZocZoc))) {
                    $head = false;
                    if (isset($this->moves->$newHexNum)) {
                        if ($this->moves->$newHexNum->pointsLeft > ($movePoints - $moveAmount)) {
                            continue;
                        }
                        $head = true;
                    }
                    $newPath = new HexPath();
                    $newPath->name = $newHexNum;
                    $newPath->pathToHere = $path;
                    $newPath->pointsLeft = $movePoints - $moveAmount;
                    if ($newPath->pointsLeft < 0) {
                        $newPath->pointsLeft = 0;
                    }
                    if ($this->exitZoc === "stop" && $hexPath->isZoc) {
                        $newPath->pointsLeft = 0;
                    }
                    if ($head) {
                        array_unshift($this->moveQueue, $newPath);
                    } else {
                        $this->moveQueue[] = $newPath;

                    }
                }
            }

        }
        return;
    }

    function bfsRetreat()
    {

        $unit = $this->force->units[$this->movingUnitId];

        /* Reverse attack and defender for retreats (retreating units are moving) */
        $defendingForceId = $this->force->attackingForceId;
        $attackingForceId = $this->force->defendingForceId;


        $cnt = 0;
        while (count($this->moveQueue) > 0) {


            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
            $movePoints = $hexPath->pointsLeft;

            if (!$hexNum) {
                continue;
            }

            if (!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;

            } else {
                /* invalid hex */
                if ($this->moves->$hexNum->isValid === false) {
                    continue;
                }
                /* been here, done that */
                continue;
            }
            /* @var MapHex $mapHex */
            $mapHex = $this->mapData->getHex($hexNum);

            if ($mapHex->isOccupied($attackingForceId)) {
                $this->moves->$hexNum->isOccupied = true;
            }
            if ($mapHex->isOccupied($defendingForceId)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            $this->moves->$hexNum->pointsLeft = $movePoints;
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            if ($this->moves->$hexNum->isZoc == NULL) {
                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex, $defendingForceId);
            }
            if ((!$hexPath->firstHex) && $this->moves->$hexNum->isZoc) {
                continue;
            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            for ($i = 1; $i <= 6; $i++) {
                $newHexNum = $mapHex->neighbors[$i - 1];
                $gnuHex = Hexagon::getHexPartXY($newHexNum);
                if (!$gnuHex) {
                    continue;
                }
                if ($this->terrain->terrainIsHexSide($hexNum, $newHexNum, "blocked")) {
                    continue;
                }

                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {
                    continue;
                }
                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "blocked")) {
                    continue;
                }
                $newMapHex = $this->mapData->getHex($newHexNum);
                if ($newMapHex->isOccupied($defendingForceId)) {
                    continue;
                }
                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if($movePoints - 1 < 0){
                    continue;
                }
                $head = false;

                if (isset($this->moves->$newHexNum)) {
                    if($this->moves->$newHexNum->pointsLeft > ($movePoints - 1) ){
                        continue;
                    }
                }
                $newPath = new HexPath();
                $newPath->name = $newHexNum;
                $newPath->pathToHere = $path;
                $newPath->pointsLeft = $movePoints - 1;
                $this->moveQueue[] = $newPath;
            }
        }
        return false;
    }

    function bfsCommunication($goal, $bias, $attackingForceId = false, $maxHex = false)
    {
        $goalArray = array();
        if (is_array($goal)) {
            foreach ($goal as $key => $val) {
                $goalArray[$val] = true;
            }
        } else {
            $goalArray[$goal] = true;
        }
        if ($attackingForceId !== false) {
            $defendingForceId = $this->force->Enemy($attackingForceId);
        } else {
            $attackingForceId = $this->force->attackingForceId;
            $defendingForceId = $this->force->defendingForceId;
        }

        $cnt = 0;
        while (count($this->moveQueue) > 0) {

            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
            if($maxHex !== false){
                $movePoints = $hexPath->pointsLeft;
            }
            if (!$hexNum) {
                continue;
            }
            if ($goalArray[$hexNum]) {
                return true;
            }
            if (!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;

            } else {
                /* invalid hex */
                if ($this->moves->$hexNum->isValid === false) {
                    continue;
                }
                /* been here, done that */
                continue;
            }
            /* @var MapHex $mapHex */
            $mapHex = $this->mapData->getHex($hexNum);

            if ($mapHex->isOccupied($attackingForceId)) {
                $this->moves->$hexNum->isOccupied = true;
            }
            if ($mapHex->isOccupied($defendingForceId)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            if($maxHex !== false){
                $this->moves->$hexNum->pointsLeft = $movePoints;
            }
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            if ($this->moves->$hexNum->isZoc == NULL) {
                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex, $defendingForceId);
            }
            if ($this->moves->$hexNum->isZoc) {
                if (!$this->moves->$hexNum->isOccupied) {
                    continue;
                }

            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            for ($i = 1; $i <= 6; $i++) {
                $newHexNum = $mapHex->neighbors[$i - 1];
                $gnuHex = Hexagon::getHexPartXY($newHexNum);
                if (!$gnuHex) {
                    continue;
                }
                if ($this->terrain->terrainIsHexSide($hexNum, $newHexNum, "blocked")) {
                    continue;
                }

                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {
                    continue;
                }
                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "blocked")) {
                    continue;
                }
                $newMapHex = $this->mapData->getHex($newHexNum);
                if ($newMapHex->isOccupied($defendingForceId)) {
                    continue;
                }
                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if($maxHex !== false && $movePoints - 1 < 0){
                    continue;
                }
                $head = false;
                if ($bias[$i]) {
                    $head = true;
                }
                if (isset($this->moves->$newHexNum)) {
                        if($maxHex !== false){
                            if($this->moves->$newHexNum->pointsLeft > ($movePoints - 1) ){
                                continue;
                            }
                        }else{
                            continue;
                        }
                }
                $newPath = new HexPath();
                $newPath->name = $newHexNum;
                $newPath->pathToHere = $path;
                if($maxHex !== false){
                    $newPath->pointsLeft = $movePoints - 1;
                }
                if ($head) {
                    array_unshift($this->moveQueue, $newPath);
                } else {
                    $this->moveQueue[] = $newPath;

                }
            }
        }
        return false;
    }

    function bfsRoadTrace($goal, $bias, $attackingForceId = false, $maxHex = false)
    {
        $goalArray = array();
        if (is_array($goal)) {
            foreach ($goal as $key => $val) {
                $goalArray[$val] = true;
            }
        } else {
            $goalArray[$goal] = true;
        }
        if ($attackingForceId !== false) {
            $defendingForceId = $this->force->Enemy($attackingForceId);
        } else {
            $attackingForceId = $this->force->attackingForceId;
            $defendingForceId = $this->force->defendingForceId;
        }

        $cnt = 0;
        while (count($this->moveQueue) > 0) {

            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
            if($maxHex !== false){
                $movePoints = $hexPath->pointsLeft;
            }
            if (!$hexNum) {
                continue;
            }

            if (!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;

            } else {
                /* invalid hex */
                if ($this->moves->$hexNum->isValid === false) {
                    continue;
                }
                /* been here, done that */
                continue;
            }
            /* @var MapHex $mapHex */
            $mapHex = $this->mapData->getHex($hexNum);

            if ($mapHex->isOccupied($attackingForceId)) {
                $this->moves->$hexNum->isOccupied = true;
            }
            if ($mapHex->isOccupied($defendingForceId)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            if($maxHex !== false){
                $this->moves->$hexNum->pointsLeft = $movePoints;
            }
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            if ($this->moves->$hexNum->isZoc == NULL) {
                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex, $defendingForceId);
            }
            if ($this->moves->$hexNum->isZoc) {
                if (!$this->moves->$hexNum->isOccupied) {
                    unset($this->moves->$hexNum);
//                    $this->moves->$hexNum->isValid = false;
                    continue;
                }

            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            for ($i = 1; $i <= 6; $i++) {
                $newHexNum = $mapHex->neighbors[$i - 1];
                $gnuHex = Hexagon::getHexPartXY($newHexNum);
                if (!$gnuHex) {
                    continue;
                }
                if (!($this->terrain->terrainIsHexSide($hexNum, $newHexNum, "road") || $this->terrain->terrainIsHexSide($hexNum, $newHexNum, "trail")
                    || $this->terrain->terrainIsHexSide($hexNum, $newHexNum, "secondaryroad"))) {
                    continue;
                }

                if ($this->terrain->terrainIsXY($gnuHex[0], $gnuHex[1], "offmap")) {
                    continue;
                }
                $newMapHex = $this->mapData->getHex($newHexNum);
                if ($newMapHex->isOccupied($defendingForceId)) {
                    continue;
                }
                /*
                 * TODO order is important in if statement check if doing zoc zoc move first then if just one hex move.
                 * Then check if oneHex and firstHex
                 */
                if($maxHex !== false && $movePoints - 1 < 0){
                    continue;
                }
                $head = false;
                if ($bias[$i]) {
                    $head = true;
                }
                if (isset($this->moves->$newHexNum)) {
                    if($maxHex !== false){
                        if($this->moves->$newHexNum->pointsLeft > ($movePoints - 1) ){
                            continue;
                        }
                    }else{
                        continue;
                    }
                }
                $newPath = new HexPath();
                $newPath->name = $newHexNum;
                $newPath->pathToHere = $path;
                if($maxHex !== false){
                    $newPath->pointsLeft = $movePoints - 1;
                }
                if ($head) {
                    array_unshift($this->moveQueue, $newPath);
                } else {
                    $this->moveQueue[] = $newPath;

                }
            }
        }
        return false;
    }
    function startMoving($id)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        $victory->preStartMovingUnit($unit);

        /*
         * Don't think this is important test. Unit will be STATUS_STOPPED if cannot move.
         */
        if (!$this->stickyZOC || $this->force->unitIsZOC($id) == false) {
            if ($unit->setStatus(STATUS_MOVING) == true) {
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
        $victory->postStartMovingUnit($unit);
    }

    function move(unit $movingUnit, $hexagon)
    {
        if ($movingUnit->unitIsMoving()
            && $this->moveIsValid($movingUnit, $hexagon)
        ) {
            $this->updateMoveData($movingUnit, $hexagon);
        }
    }

    function stopMove(unit $movingUnit)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $victory->preStopMovingUnit($movingUnit);

        $this->moves = new stdClass();
        if ($movingUnit->unitIsMoving() == true) {
            if ($movingUnit->unitHasNotMoved()) {
                $movingUnit->setStatus(STATUS_READY);
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
            } else {
                if ($movingUnit->setStatus(STATUS_STOPPED) == true) {
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = NONE;
                }
            }
        }
        $victory->preStopMovingUnit($movingUnit);
    }

    function exitUnit($id)
    {
        /* @var Unit $unit */
        $unit = $this->force->units[$id];
        if ($unit->unitIsMoving() == true) {
            $battle = Battle::getBattle();
            $victory = $battle->victory;
            $ret = $victory->isExit($unit);
            if($ret === false){
                return;
            }
            if ($unit->setStatus(STATUS_EXITED) == true) {
                /* TODO: awful. probably don't need to set $id for Hexagon name */
                $hexagon = new Hexagon($id);
                $hexagon->parent = 'exitBox';
                $this->force->updateMoveStatus($unit->id, $hexagon, 1);
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
                $this->moves = new stdClass();
            }

            return;
        }
    }


    function moveIsValid(unit $movingUnit, $hexagon, $startHex = false, $firstHex = false)
    {
        // all 4 conditions must be true, so any one that is false
        //    will make the move invalid

        $isValid = true;

        if ($startHex === false) {
            $startHex = $movingUnit->getUnitHexagon()->name;
        }
        if ($firstHex === false) {
            $firstHex = $movingUnit->unitHasNotMoved();
        }
        // condition 1
        // can only move to nearby hexagon
        if ($this->rangeIsOneHexagon($startHex, $hexagon) == false) {
            $isValid = false;
        }
        // condition 2
        // check if unit has enough move points
        $moveAmount = $this->terrain->getTerrainMoveCost($startHex, $hexagon, $movingUnit->forceMarch, $movingUnit);

        // need move points, but can always move at least one hexagon
        //  can always move at least one hexagon if this->oneHex is true
        //  only check move amount if unit has been moving
        if (!($firstHex == true && $this->oneHex)) {
            if ($movingUnit->unitHasMoveAmountAvailable($moveAmount) == false) {
                $isValid = false;
            }
        }

        // condition 3
        // can only move across river hexside if at start of move
//        if (($this->isAlongRail($startHex, $hexagon) == false) && $this->railMove) {
//            $isValid = false;
//        }

        // condition 4
        // can not exit
        if (($this->terrain->isExit($hexagon) == true)) {
            $isValid = false;
        }
        return $isValid;
    }

    function updateMoveData(unit $movingUnit, $hexagon)
    {
        $battle = Battle::getBattle();
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $fromHex = $movingUnit->hexagon;
        $moveAmount = $this->terrain->getTerrainMoveCost($movingUnit->getUnitHexagon()->name, $hexagon, $movingUnit->forceMarch, $movingUnit);
        /* @var MapHex $mapHex */
        $mapHex = $mapData->getHex($hexagon);
        if ($mapHex->isZoc($this->force->defendingForceId) == true) {
            if (is_numeric($this->enterZoc)) {
                $moveAmount += $this->enterZoc;
            }
        }
        $fromMapHex = $mapData->getHex($fromHex->name);
        if ($fromMapHex->isZoc($this->force->defendingForceId) == true) {
            if (is_numeric($this->exitZoc)) {
                $moveAmount += $this->exitZoc;
            }
        }

        $movingUnit->updateMoveStatus(new Hexagon($hexagon), $moveAmount);

        if (($this->storm && !$this->railMove) && !$movingUnit->unitHasNotMoved()) {
            $this->stopMove($movingUnit);
        }
        if ($movingUnit->unitHasUsedMoveAmount() == true) {
            $this->stopMove($movingUnit);
        }

        if ($mapHex->isZoc($this->force->defendingForceId) == true) {
            if ($this->enterZoc == "stop") {
                $this->stopMove($movingUnit);
            }
        }

        if ($this->terrain->isExit($hexagon)) {
            $this->eexit($movingUnit->id);
        }
    }

    function rangeIsOneHexagon($startHexagon, $endHexagon)
    {
        $rangeIsOne = false;

        $los = new Los();
        $los->setOrigin($startHexagon);
        $los->setEndPoint($endHexagon);
        if ($los->getRange() == 1) {
            $rangeIsOne = true;
        }

        return $rangeIsOne;
    }

    function startReinforcing($id, $turn)
    {
        if ($this->force->getUnitReinforceTurn($id) <= $turn) {
            /* @var Unit $unit */
            $unit = $this->force->getUnit($id);

            $battle = Battle::getBattle();
            $victory = $battle->victory;
            /* @var Unit $unit */
            $victory->preStartMovingUnit($unit);

            if ($unit->setStatus(STATUS_REINFORCING) == true) {
                $movesLeft = $unit->maxMove;
                $zoneName = $unit->reinforceZone;
                $zones = $this->terrain->getReinforceZonesByName($zoneName);
                list($zones) = $battle->victory->postReinforceZones($zones, $unit);
                foreach ($zones as $zone) {
                    if ($this->force->hexagonIsOccupied($zone->hexagon)) {
                        continue;
                    }
                    $startHex = $zone->hexagon->name;
                    $hexPath = new HexPath();
                    $hexPath->name = $startHex;
                    $hexPath->pointsLeft = $movesLeft;
                    $hexPath->pathToHere = array();
                    $hexPath->firstHex = true;
                    $this->moves->$startHex = $hexPath;
                }
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function startDeploying($id, $turn)
    {
        if ($this->force->getUnitReinforceTurn($id) <= $turn) {
            /* @var Unit $unit */
            $unit = $this->force->getUnit($id);
            if ($unit->setStatus(STATUS_DEPLOYING) == true) {
                $battle = Battle::getBattle();
                $victory = $battle->victory;
                $movesLeft = 0;
                $zoneName = $unit->reinforceZone;
                $zones = $this->terrain->getReinforceZonesByName($zoneName);
                list($zones) = $battle->victory->postDeployZones($zones, $unit);
                foreach ($zones as $zone) {
                    $startHex = $zone->hexagon->name;
                    if ($this->force->hexagonIsOccupied($zone->hexagon)) {
                        continue;
                    }
                    $hexPath = new HexPath();
                    $hexPath->name = $startHex;
                    $hexPath->pointsLeft = $movesLeft;
                    $hexPath->pathToHere = array();
                    $hexPath->firstHex = true;
                    $this->moves->$startHex = $hexPath;
                }
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function startReplacing($id)
    {
        $battle = Battle::getBattle();
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->setStatus(STATUS_CAN_REPLACE) == true) {
            $movesLeft = 0;
            $zones = $this->terrain->getReinforceZonesByName($this->force->getUnitReinforceZone($id));
            list($zones) = $battle->victory->postReinforceZones($zones, $unit);
            foreach ($zones as $zone) {
                if ($this->force->hexagonIsOccupied($zone->hexagon)) {
                    continue;
                }
                $startHex = $zone->hexagon->name;
                $hexPath = new HexPath();
                $hexPath->name = $startHex->name;
                $hexPath->pointsLeft = $movesLeft;
                $hexPath->pathToHere = array();
                $hexPath->firstHex = true;
                $this->moves->$startHex = $hexPath;
            }
            $this->anyUnitIsMoving = true;
            $this->movingUnitId = $id;
        }
    }

    function stopReplacing()
    {
        $this->moves = new stdClass();

        $this->anyUnitIsMoving = false;
        $this->movingUnitId = false;
    }

    function reinforce($id, Hexagon $hexagon)
    {

        if ($this->force->unitIsReinforcing($id) == true) {
            if (in_array($this->force->getUnitReinforceZone($id) , $this->terrain->getReinforceZoneList($hexagon))) {
                /* @var Unit $movingUnit */
                $movingUnit = $this->force->getUnit($id);
                if ($movingUnit->setStatus(STATUS_MOVING) == true) {
                    $battle = Battle::getBattle();
                    $victory = $battle->victory;
                    $victory->reinforceUnit($movingUnit, $hexagon);
                    $this->force->updateMoveStatus($id, $hexagon, 0);
                }

            }
        }
    }

    function deploy($id, $hexagon)
    {
        if ($this->force->unitIsDeploying($id) == true) {
            if (in_array($this->force->getUnitReinforceZone($id), $this->terrain->getReinforceZoneList($hexagon))) {
                /* @var Unit $movingUnit */
                $movingUnit = $this->force->units[$id];
                if ($movingUnit->setStatus(STATUS_CAN_DEPLOY) == true) {
                    $this->force->updateMoveStatus($id, $hexagon, 0);
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = NONE;
                    $this->moves = new stdClass();
                }

            }
        }
    }

    function stopReinforcing($id)
    {
        if ($this->force->unitIsReinforcing($id) == true) {
            /* @var Unit $unit */
            $unit = $this->force->getUnit($id);
            if ($unit->setStatus(STATUS_CAN_REINFORCE) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
                $this->moves = new stdClass();
            }
        }
    }

    function stopDeploying($id)
    {
        if ($this->force->unitIsDeploying($id) == true) {
            /* @var Unit $unit */
            $unit = $this->force->getUnit($id);
            if ($unit->setStatus(STATUS_CAN_DEPLOY) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
                $this->moves = new stdClass();
            }
        }
    }

// retreat rules

// gameRules has cleared retreat list

    function retreatUnit($eventType, $id, $hexagon)
    {
        // id will be map if map event
        if ($eventType == SELECT_MAP_EVENT) {
            if ($this->anyUnitIsMoving == true) {
                $this->retreat($this->movingUnitId, $hexagon);
            }
        } else {
            // id will be retreating unit id if counter event
            if ($this->anyUnitIsMoving == false) {
                if ($this->force->unitCanRetreat($id) == true) {
                    $this->startRetreating($id);
                }
            } else {
                $this->retreat($this->movingUnitId, $hexagon);
            }
        }
    }

// retreat rules

// gameRules has cleared retreat list

//    function retreatUnit($eventType, $id, $hexagon)
//    {
//        // id will be map if map event
//        if ($eventType == SELECT_MAP_EVENT) {
//            if ($this->anyUnitIsMoving == true) {
//                $retreatingUnit = $this->force->units[$id];
//                if (true || $retreatingUnit->unitIsretreating() == true) {
//                    $newHex = $hexagon;
//                    $newHexName = $newHex->name;
//
//                    if ($this->moves->{$newHexName}) {
//                        $this->path = $this->moves->$newHexName->pathToHere;
//
//                        foreach ($this->path as $retreatHex) {
//                            $this->retreat($id, new Hexagon($retreatHex));
//
//                        }
////                        $retreatsLeft = $this->moves->$newHex->pointsLeft;
//                        $this->moves = new stdClass();
//
//                        $this->retreat($id, $newHex);
//                        $this->path = array();
//                        $dirty = true;
//                    }
//                }
//                $this->retreat($this->movingUnitId, $hexagon);
//            }
//        } else {
//            // id will be retreating unit id if counter event
//            if ($this->anyUnitIsMoving == false) {
//                if ($this->force->unitCanRetreat($id) == true) {
//                    $this->startRetreating($id);
//                    $this->calcRetreat($id);
//                }
//            } else {
//                $this->retreat($this->movingUnitId, $hexagon);
//            }
//        }
//    }


    function startRetreating($id)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;

        /* @var Unit $movingUnit */
        $movingUnit = $this->force->getUnit($id);
        $victory->preStartMovingUnit($movingUnit);
        if ($movingUnit->setStatus(STATUS_RETREATING) == true) {
            if ($this->retreatIsBlocked($id) == true) {

                $hexagon = $movingUnit->getUnitHexagon();

                $this->force->addToRetreatHexagonList($id, $hexagon);

                $this->stopMove($movingUnit);
                $this->force->eliminateUnit($id);
            } else {
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
        $victory->postStartMovingUnit($movingUnit);
    }

    function retreatIsBlocked($id)
    {
        $isBlocked = true;

        $adjacentHexagonXadjustment = array(0, 2, 2, 0, -2, -2);
        $adjacentHexagonYadjustment = array(-4, -2, 2, 4, 2, -2);

        /* @var Hexagon $hexagon */
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        $hexagon = $unit->getUnitHexagon();
        $hexagonX = $hexagon->getX($id);
        $hexagonY = $hexagon->getY($id);
        for ($eachHexagon = 0; $eachHexagon < 6; $eachHexagon++) {
            $adjacentHexagonX = $hexagonX + $adjacentHexagonXadjustment[$eachHexagon];
            $adjacentHexagonY = $hexagonY + $adjacentHexagonYadjustment[$eachHexagon];
            $adjacentHexagon = new Hexagon($adjacentHexagonX, $adjacentHexagonY);

            if ($this->hexagonIsBlocked($id, $adjacentHexagon) == false) {
                $isBlocked = false;
                break;
            }

        }

        return $isBlocked;
    }

    function hexagonIsBlocked($id, $hexagon)
    {
        $isBlocked = false;

        if (!$hexagon->name) {
            return true;
            /* off map hexes have no name */
        }

        // make sure hexagon is not ZOC
        $startHex = $this->force->units[$id]->hexagon;
        $unit = $this->force->units[$id];
        if ($this->terrain->terrainIsHexSide($startHex->name, $hexagon->name, "blocked")) {
            $isBlocked = true;
        }
        if ($this->terrain->getTerrainMoveCost($startHex->name, $hexagon->name, $unit->forceMarch, $unit) == "blocked") {
            $isBlocked = true;
        }

        if ($this->zocBlocksRetreat === true && ($this->force->hexagonIsZOC($id, $hexagon) == true)) {
            $isBlocked = true;
        }
        // make sure hexagon is not occupied
        if ($this->force->hexagonIsOccupiedEnemy($hexagon, $id) == true) {
            $isBlocked = true;
        }

        if ($this->terrain->isExit($hexagon) == true) {
            $isBlocked = true;
        }
        //alert(unitHexagon->getName() + " to " + hexagon->getName() + " zoc: " + $this->force->hexagonIsZOC(id, hexagon) + " occ: " + $this->force->hexagonIsOccupied(hexagon)  + " river: " + $this->terrain->terrainIs(hexpart, "river"));
        return $isBlocked;
    }

    function retreat($id, Hexagon $hexagon)
    {
        /* @var  Unit $movingUnit */
        $movingUnit = $this->force->units[$id];
        if ($this->retreatIsBlocked($id)) {

            $hexagon = $movingUnit->getUnitHexagon();

            $this->force->addToRetreatHexagonList($id, $hexagon);

            $this->stopMove($movingUnit);
            $this->force->eliminateUnit($id);
        }
        if ($this->rangeIsOneHexagon($movingUnit->getUnitHexagon()->name, $hexagon)
            && $this->hexagonIsBlocked($id, $hexagon) === false
            && $this->terrain->isExit($hexagon) === false
        ) {

            $this->force->addToRetreatHexagonList($id, $movingUnit->getUnitHexagon());
            // set move amount to 0
            $occupied = $this->force->hexagonIsOccupied($hexagon);
            $movingUnit->updateMoveStatus($hexagon, 0);


            // check crt retreat count required to how far the unit has retreated
            if ($this->force->unitHasMetRetreatCountRequired($id) && !$occupied) {
                // stop if unit has retreated the required amount
                if ($movingUnit->setStatus(STATUS_STOPPED) == true) {
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = NONE;
                }
            }
        }
        // if forced to retreat offmap, unit is eliminated
        if ($this->terrain->isExit($hexagon) == true) {
            $this->stopMove($movingUnit);
            $this->force->eliminateUnit($id);
        }
    }

// advancing rules

    function advanceUnit($eventType, $id, $hexagon)
    {
        if ($eventType == SELECT_MAP_EVENT) {
            if ($this->anyUnitIsMoving == true) {
                //alert("advance");
                $this->advance($this->movingUnitId, $hexagon);
            }
        } else {
            if (($this->anyUnitIsMoving == true) && ($id == $this->movingUnitId)) {
                $this->stopAdvance($this->movingUnitId);
            } else {
                if ($this->force->unitCanAdvance($id) == true) {
                    $this->startAdvancing($id);
                    $this->force->resetRemainingNonAdvancingUnits();
                }
            }
        }
    }

    function startAdvancing($id)
    {
        /* @var Hexagon $hexagon */
        $hexagon = $this->force->getFirstRetreatHex($id);
        $hexes = $this->force->getAllFirstRetreatHexes($id);
        foreach ($hexes as $hexagon) {
            $startHex = $hexagon->name;
            $hexPath = new HexPath();
            $hexPath->name = $startHex;
            $hexPath->pointsLeft = $this->force->units[$id]->maxMove;
            $hexPath->pathToHere = array();
            $hexPath->firstHex = true;
            $this->moves->$startHex = $hexPath;
        }

        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->setStatus(STATUS_ADVANCING) == true) {
            $this->anyUnitIsMoving = true;
            $this->movingUnitId = $id;
        }
    }

    function advance($id, $hexagon)
    {
        if ($this->advanceIsValid($id, $hexagon) == true) {
            // set move amount to 0

            $this->force->updateMoveStatus($id, $hexagon, 0);
            $this->stopAdvance($id);
        }
    }

    function stopAdvance($id)
    {
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->setStatus(STATUS_ADVANCED) == true) {
            $this->moves = new stdClass();
            $this->force->resetRemainingAdvancingUnits();
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
        }
    }

    function advanceIsValid($id, $hexagon)
    {
        $isValid = false;

        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        $startHexagon = $unit->getUnitHexagon();
        if ($this->force->advanceIsOnRetreatList($id, $hexagon) == true && $this->rangeIsOneHexagon($startHexagon, $hexagon) == true) {
            $isValid = true;
        } else {
        }

        return $isValid;
    }
}
