<?php
// moveRules.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.


$numWalks = 0;
class MoveRules{
    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;

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
    public $noZocZocOneHex = false;
    function save(){
        $data = new StdClass();
        foreach($this as $k => $v){
            if(is_object($v) && $k != "path" && $k != "moves"){
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

        if($data){
            foreach($data as $k => $v){
                $this->$k = $v;
            }
        }else{
            $this->movingUnitId = NONE;
            $this->anyUnitIsMoving = false;
            $this->storm = false;
            $this->railMove = false;
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
                $movingUnit = $this->force->units[$this->movingUnitId];
                if ($movingUnit->unitIsMoving() == true) {
                    $newHex = $hexagon->name;
                    if($this->moves->$newHex){
                        $this->path = $this->moves->$newHex->pathToHere;

                        foreach($this->path as $moveHex){
                            $hexStep = new Hexagon($moveHex);
                            $this->move($movingUnit, $hexStep);

                        }
                        $movesLeft = $this->moves->$newHex->pointsLeft;
                        $this->moves = new stdClass();

                        $startHex = new Hexagon($newHex);
                        $this->move($movingUnit, $startHex);
                        $this->path = array();
                        if($this->anyUnitIsMoving){
                            $this->moveQueue = array();
                            $hexPath = new HexPath();
                            $hexPath->name = $startHex->name;
                            $hexPath->pointsLeft = $movesLeft;
                            $hexPath->pathToHere = array();
                            $hexPath->firstHex = false;
                            $hexPath->isOccupied = true;
                            $this->moveQueue[] = $hexPath;
                            $this->bfsMoves();

                            $movesAvail = 0;
                            foreach($this->moves as $move){
                                if($move->isOccupied || !$move->isValid){
                                    continue;
                                }
                                $movesAvail++;
                            }

                            if($movesAvail === 0){
                                $this->stopMove($movingUnit);
                            }
                        }
                        $dirty = true;
                    }
                }
                if ($this->force->unitIsReinforcing($this->movingUnitId) == true) {
                    $this->reinforce($this->movingUnitId, $hexagon);
                    $this->calcMove($id);
//                    $this->startMoving($id);
                    $dirty = true;
                }
            }
        }
        else
            // click on a unit
        {
            if ($this->anyUnitIsMoving == true) {
                if ($id == $this->movingUnitId) {
                    $movingUnit = $this->force->units[$id];
                    // clicked on moving or reinforcing unit
                    if ($movingUnit->unitIsMoving() == true) {
                        $this->stopMove($movingUnit);
                        $dirty = true;
                    }
                    if ($this->force->unitIsReinforcing($id) == true) {
                        $this->stopReinforcing($id);
                        $dirty = true;



                    }
                }
                else
                {
                    // clicked on another unit
                    return;
                    $this->moveOver($this->movingUnitId, $id, $hexagon);
                }
            }
            else
            {
                // no one is moving, so start new move
                if ($this->force->unitCanMove($id) == true) {
                    $this->calcMove($id);
                    $this->startMoving($id);
                    $dirty = true;
                }
                if ($this->force->unitCanReinforce($id) == true) {
                     $this->startReinforcing($id, $turn);
                     $dirty = true;
                }
            }
        }
        return $dirty;
    }

    function calcMove($id){
        global $numWalks;
        global $numBangs;
        $numWalks = 0;
        $numBangs = 0;
        $startHex = $this->force->units[$id]->hexagon;
        $movesLeft = $this->force->units[$id]->maxMove - $this->force->units[$id]->moveAmountUsed;
        $this->moves = new stdClass();
        $depth = $movesLeft;
        $depth = 1;
        while($depth--){
//            $this->walkMoves($startHex,$movesLeft,array(),true,$depth);

        }
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
    function bfsMoves(){
        $hist = array();
        $cnt = 0;

        while(count($this->moveQueue) > 0){
            $cnt++;
            $hexPath = array_shift($this->moveQueue);
            $hexNum = $hexPath->name;
//            $hist[$hexNum]++;
            $movePoints = $hexPath->pointsLeft;
            if(!$hexNum){
                continue;
            }
            if(!isset($this->moves->$hexNum)) {
                /* first time here */
                $this->moves->$hexNum = $hexPath;
//                $theHex = new Hexagon($hexNum);
//                if(!$theHex->name){/* dunno if this still happens */
//                    $this->moves->$hexNum->isValid = false;
//                    continue;
//                }
            }else{
                /* invalid hex */
                if($this->moves->$hexNum->isValid === false){
                    continue;
                    throw new Exception("This shouldn 't happen bfsMoves");
                    return;
                }
                /* already been here with more points */
                if($this->moves->$hexNum->pointsLeft >= $movePoints){
                        continue;

                }
            }
            $theHex = new Hexagon($hexNum);
            $mapHex = $this->mapData->getHex($hexNum);

            if($this->force->mapHexIsOccupied($mapHex)){
                $this->moves->$hexNum->isOccupied = true;
            }
            if($this->force->mapHexIsOccupiedEnemy($mapHex)){
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            $this->moves->$hexNum->pointsLeft = $movePoints;
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            if($this->moves->$hexNum->isZoc == NULL){
                $this->moves->$hexNum->isZoc = $this->force->mapHexIsZOC($mapHex);
            }
            $exitCost = 0;
            if($this->moves->$hexNum->isZoc){
                if(is_numeric($this->exitZoc)){
                    $exitCost += $this->exitZoc;
                }
//                $this->moves->$hexNum->pointsLeft = 0;
                if(!$hexPath->firstHex){
                    if($this->enterZoc == 'stop'){
                        continue;
                    }
                }

            }
            $path = $hexPath->pathToHere;
            $path[] = $hexNum;

            for($i = 1; $i <= 6; $i++){
                $newHex = new Hexagon($mapHex->neighbors[$i - 1]);
//                $newHex = new Hexagon($hexNum);
//                $newHex->getAdjacentHexagon($i);
                $newHexNum = $newHex->name;
                if(!$newHexNum){
                    continue;
                }

                $moveAmount = $this->terrain->getTerrainMoveCost($theHex, $newHex, $this->railMove) + $exitCost;
                $newMapHex = $this->mapData->getHex($newHexNum);
                $isZoc = $this->force->mapHexIsZOC($newMapHex);
                if($isZoc && is_numeric($this->enterZoc)){
                    $moveAmount += $this->enterZoc;
                }
//            $moveAmount = .5;
                if($moveAmount <= 0){
                    $moveAmount = 1;
                }
                if($this->noZocZoc && $isZoc && $hexPath->isZoc){
                    continue;
                }
                if($movePoints - $moveAmount >= 0 || (!($isZoc && $hexPath->isZoc && $this->noZocZocOneHex)) && $hexPath->firstHex === true){
                    $head = false;
                    if(isset($this->moves->$newHexNum)){
                        if($this->moves->$newHexNum->pointsLeft > ($movePoints - $moveAmount) ){
                            continue;
                        }
                        $head = true;
                    }
                    $newPath = new HexPath();
                    $newPath->name = $newHex->name;
                    $newPath->pathToHere = $path;
                    $newPath->pointsLeft = $movePoints - $moveAmount;
                    if($head){
                        array_unshift($this->moveQueue, $newPath);
                    }else{
                        $this->moveQueue[] = $newPath;

                    }
                }
            }

        }
        return;
    }
    function walkMoves($startHex, $movePoints, $path, $firstHex = false, $depth = false){

        throw new Exception("NO MORE WALKING MOVES");
        global $numWalks;
        $numWalks++;
        $hexNum = $startHex->number;
        if(!$this->moves->$hexNum) {
            /* first time here */
            $this->moves->$hexNum = new HexPath($hexNum);
            if(!$startHex->name){
                  $this->moves->$hexNum->isValid = false;
                return;
            }
        }else{
            /* invalid hex */
            if($this->moves->$hexNum->isValid == false){
                return;
            }
            /* already been here with more points */
            if($this->moves->$hexNum->pointsLeft >= $movePoints){
                if($this->moves->$hexNum->pointsLeft == $movePoints){
                    if($this->moves->$hexNum->depth > $depth){
                        $this->moves->$hexNum->depth = $depth;
                    }else{
                        return;
                    }
                }else{
                    return;
                }
            }
        }

        if($this->force->hexagonIsOccupied($startHex)){
            $this->moves->$hexNum->isOccupied = true;
        }
        if($this->force->hexagonIsEnemyOccupied($startHex)){
            $this->moves->$hexNum->isValid = false;
            return;
        }
        $this->moves->$hexNum->pointsLeft = $movePoints;
        $this->moves->$hexNum->depth = $depth;
        $this->moves->$hexNum->pathToHere = $path;
        if(count($path) > 0){
            $chkPath = $path;
//            $lastHex = new Hexagon(array_pop($chkPath));

//            if($this->moveHasCausedStop($lastHex,$startHex,$movePoints)){
//                $this->moves->$hexNum->pointsLeft = 0;
//                return;
//            }
        }
        if($this->moves->$hexNum->isZoc || $this->force->hexIsZOC($startHex,1)){
            $this->moves->$hexNum->pointsLeft = 0;
            $this->moves->$hexNum->isZoc = true;
           if(!$firstHex){
               return;
           }
        }
        if($movePoints - $depth <= 0){
            return;
        }
        $path[] = $hexNum;

        global $numBangs;
        $numBangs++;
        for($i = 1; $i <= 6; $i++){
            $newHex = new Hexagon($hexNum);
            $newHex->getAdjacentHexagon($i);
            $moveAmount = $this->terrain->getTerrainMoveCost($startHex, $newHex, $this->railMove);
//            $moveAmount = 1;
            if($moveAmount <= 0){
                $moveAmount = 1;
            }
            if($movePoints - $moveAmount >= 0){
            $this->walkMoves($newHex,$movePoints - $moveAmount,$path, false, $depth);
            }
        }
    }
    function startMoving($id)
    {
        /*
         * remove the true || to make Active Locking zoc's
         */
        if (!$this->stickyZOC || $this->force->unitIsZOC($id) == false) {
            if ($this->force->units[$id]->setStatus( STATUS_MOVING) == true) {
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function move(unit $movingUnit, $hexagon)
    {
        if ($movingUnit->unitIsMoving()
            && $this->moveIsValid($movingUnit, $hexagon)
        ) {
            $this->updateMoveData($movingUnit, $hexagon);
        }
    }

    function moveOver($id, $moveOverUnitId, $hexagon)
    {
        if ($this->force->unitIsFriendly($moveOverUnitId) == true) {
            if ($this->moveIsValid($id, $hexagon) == true) {
                if ($this->moveWillCauseStop($id, $moveOverUnitId, $hexagon) == false) {
                    $this->updateMoveData($this->force->units[$id], $hexagon);
                }
                else
                {
                    alert("unit cannot end move in hexagon with another unit");
                }
            }
        }
    }

    function stopMove(unit $movingUnit)
    {
        $this->moves = new stdClass();
        if ($movingUnit->unitIsMoving() == true) {
            if ($movingUnit->unitHasNotMoved()) {
                $movingUnit->setStatus( STATUS_READY);
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = NONE;
            } else {
                if ($movingUnit->setStatus( STATUS_STOPPED) == true) {
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = NONE;
                }
            }
        }

    }

    function eexit($id)
    {
        if ($this->force->units[$id]->unitIsMoving() == true) {
            if ($this->force->units[$id]->setStatus( STATUS_EXITED) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
            }
        }
    }

    function moveWillCauseStop($id, $moveOverUnitId, $hexagon)
    {
        $willCauseStop = false;
        $moveAmount = $this->terrain->getTerrainMoveCost($this->force->getUnitHexagon($id), $hexagon, $this->railMove);

        // out of moves stop
        if ($this->force->unitWillUseMaxMove($id, $moveAmount) == true) {
            $willCauseStop = true;
        }

        // zone of control stop
        if ($this->force->unitIsZOC($moveOverUnitId) == true) {
            $willCauseStop = true;
        }

        // if using 'can always move one hexagon' and stop rule
        if ($this->force->units[$id]->unitHasNotMoved() == true) {
            if (($this->force->units[$id]->unitHasMoveAmountAvailable($moveAmount) == false)) {
                $willCauseStop = true;
            }
        }

        // if moving across river stop
//        if (($this->moveIsAcrossRiverNoBridge($id, $hexagon) == true) && ($this->force->unitHasNotMoved($id) == true)) {
//            $willCauseStop = true;
//        }

        return $willCauseStop;
    }
    function moveHasCausedStop( $startHex, $endHex, $movePoints)
    {
        $willCauseStop = false;
        $moveAmount = $this->terrain->getTerrainMoveCost($startHex, $endHex, $this->railMove);

        // out of moves stop
        if ($moveAmount > $movePoints) {
            $willCauseStop = true;
        }

        $hexPart = new Hexpart();
        $hexPart->setXYwithNameAndType($endHex->name,HEXAGON_CENTER);
        if($this->terrain->terrainIs($hexPart,"fortified")){
//            $willCauseStop = true;
        }
        // zone of control stop
//        if ($this->force->hexIsZOC($endHex) == true) {
//            $willCauseStop = true;
//        }

        // if using 'can always move one endHex' and stop rule
//        if ($this->force->unitHasNotMoved($id) == true) {
//            if (($this->force->unitHasMoveAmountAvailable($id, $moveAmount) == false)) {
//                $willCauseStop = true;
//            }
//        }

        // if moving across river stop
//        if (($this->moveIsAcrossRiverNoBridge($id, $endHex) == true) && ($this->force->unitHasNotMoved($id) == true)) {
//            $willCauseStop = true;
//        }

        return $willCauseStop;
    }

    function moveIsValid(unit $movingUnit, $hexagon, $startHex = false, $firstHex = false)
    {
        // all 4 conditions must be true, so any one that is false
        //    will make the move invalid

        $isValid = true;

        if($startHex === false){
            $startHex = $movingUnit->getUnitHexagon();
        }
        // condition 1
        // can only move to nearby hexagon
        if ($this->rangeIsOneHexagon($startHex, $hexagon) == false) {
            $isValid = false;
        }
        // condition 2
        // check if unit has enough move points
        $moveAmount = $this->terrain->getTerrainMoveCost($startHex, $hexagon, $this->railMove);

        // need move points, but can always move at least one hexagon
        //  can always move at least one hexagon
        //  only check move amount if unit has been moving
        if ($firstHex == false) {
            if ($movingUnit->unitHasMoveAmountAvailable($moveAmount) == false) {
                $isValid = false;
            }
        }

        // condition 3
        // can only move across river hexside if at start of move
        if (($this->isAlongRail($startHex, $hexagon) == false) && $this->railMove) {
            $isValid = false;
        }

        // condition 4
        // can not exit
        if (($this->terrain->isExit($hexagon) == true)) {
            $isValid = false;
        }
        return $isValid;
    }

    function isAlongRail($fromHex, $toHex)
    {
        if($this->terrain->moveIsInto($toHex, "fortified") || $this->terrain->moveIsInto($toHex, "newrichmond")){

            return true;
        }
        return false;
    }


    function updateMoveData(unit $movingUnit, Hexagon $hexagon)
    {
        $moveAmount = $this->terrain->getTerrainMoveCost($movingUnit->getUnitHexagon(), $hexagon, $this->railMove);
        if ($this->force->hexagonIsZOC($movingUnit->id, $hexagon) == true) {
            if(is_numeric($this->enterZoc)){
                $moveAmount += $this->enterZoc;
            }
        }
        $movingUnit->updateMoveStatus($hexagon, $moveAmount);

        if(($this->storm && !$this->railMove) && !$movingUnit->unitHasNotMoved()){
            $this->stopMove($movingUnit);
        }
        if ($movingUnit->unitHasUsedMoveAmount() == true) {
            $this->stopMove($movingUnit);
        }

        if ($this->force->unitIsZOC($movingUnit->id) == true) {
            if($this->enterZoc == "stop"){
                $this->stopMove($movingUnit);
            }
        }

        if ($this->terrain->isExit($hexagon)) {
            $this->eexit($movingUnit->id);
        }
    }

    function rangeIsOneHexagon(Hexagon $startHexagon,Hexagon $endHexagon)
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
            if ($this->force->units[$id]->setStatus( STATUS_REINFORCING) == true) {
                $movesLeft = $this->force->units[$id]->maxMove;
                $zoneName = $this->force->units[$id]->reinforceZone;
                $zones = $this->terrain->getReinforceZones($zoneName);
                foreach($zones as $zone){
//                    echo "Start $startHex ".$startHex->name;
//                    die("starting to rein ");
                    $startHex = $zone->hexagon->name;
                    $hexPath = new HexPath();
                    $hexPath->name = $startHex;
                    $hexPath->pointsLeft = $movesLeft;
                    $hexPath->pathToHere = array();
                    $hexPath->firstHex = true;
                    $this->moves->$startHex =$hexPath;
                }
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function startReplacing($id)
    {
            if ($this->force->units[$id]->setStatus( STATUS_CAN_REPLACE) == true) {
                $movesLeft = 0;
                $zones = $this->terrain->getReinforceZones($this->force->getUnitReinforceZone($id));
                foreach($zones as $zone){
                    $startHex = $zone->hexagon->name;
                    $hexPath = new HexPath();
                    $hexPath->name = $startHex->name;
                    $hexPath->pointsLeft = $movesLeft;
                    $hexPath->pathToHere = array();
                    $hexPath->firstHex = true;
                    $this->moves->$startHex =$hexPath;
                }
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
    }

    function stopReplacing()
    {
        $this->moves = new stdClass();
//        if ($this->force->units[$id]->setStatus( STATUS_REPLACED) == true) {

            $this->anyUnitIsMoving = false;
            $this->movingUnitId = false;
//        }
    }
    function reinforce($id, $hexagon)
    {

        if ($this->force->unitIsReinforcing($id) == true) {
            if ($this->force->getUnitReinforceZone($id) == $this->terrain->getReinforceZone($hexagon)) {
                // get move cost

                // override move cost if road movement
                $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());

                    $movingUnit = $this->force->units[$id];
                    if ($movingUnit->setStatus( STATUS_MOVING) == true) {
                        $this->force->updateMoveStatus($id, $hexagon, 0);
//                        $this->stopMove($movingUnit);
                    }

            }
        }
    }

    function stopReinforcing($id)
    {
        if ($this->force->unitIsReinforcing($id) == true) {
            if ($this->force->units[$id]->setStatus( STATUS_CAN_REINFORCE) == true) {
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
            echo "HI again ";
            if ($this->anyUnitIsMoving == true) {
                echo "hi for the last time ";
                $this->retreat($this->movingUnitId, $hexagon);
            }
        }
        else
        {
            // id will be retreating unit id if counter event
            if ($this->anyUnitIsMoving == false) {
                if ($this->force->unitCanRetreat($id) == true) {
                    $this->startRetreating($id);
                }
            }else{
                $this->retreat($this->movingUnitId, $hexagon);
            }
        }
    }

    function startRetreating($id)
    {
        $movingUnit = $this->force->units[$id];
        if ($movingUnit->setStatus( STATUS_RETREATING) == true) {
            if ($this->retreatIsBlocked($id) == true) {

                $hexagon = $movingUnit->getUnitHexagon();

                $this->force->addToRetreatHexagonList($id, $hexagon);

                $this->stopMove($movingUnit);
                $this->force->eliminateUnit($id);
            }
            else
            {
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function retreatIsBlocked($id)
    {
        $isBlocked = true;

        $adjacentHexagonXadjustment = array(0, 2, 2, 0, -2, -2);
        $adjacentHexagonYadjustment = array(-4, -2, 2, 4, 2, -2);

        $hexagon = $this->force->units[$id]->getUnitHexagon();
        $hexagonX = $hexagon->getX($id);
        $hexagonY = $hexagon->getY($id);
        for ($eachHexagon = 0; $eachHexagon < 6; $eachHexagon++)
        {
            $adjacentHexagonX = $hexagonX + $adjacentHexagonXadjustment[$eachHexagon];
            $adjacentHexagonY = $hexagonY + $adjacentHexagonYadjustment[$eachHexagon];
            $adjacentHexagon = new Hexagon($adjacentHexagonX, $adjacentHexagonY);
            //alert("testing " + adjacentHexagon->getName());

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

        if(!$hexagon->name){
            return true;
            /* off map hexes have no name */
        }
        $unitHexagon = $this->force->units[$id]->getUnitHexagon();

        $hexsideX = ($hexagon->getX() + $unitHexagon->getX($id)) / 2;
        $hexsideY = ($hexagon->getY() + $unitHexagon->getY($id)) / 2;

        $hexpart = new Hexpart($hexsideX, $hexsideY);
        // make sure hexagon is not ZOC
        if (($this->force->hexagonIsZOC($id, $hexagon) == true)) {
            $isBlocked = true;
        }
        // make sure hexagon is not occupied
        if ($this->force->hexagonIsOccupiedEnemy($hexagon, $id) == true) {
            $isBlocked = true;
        }
        // make sure hexagon is not retreating across a river

//        if ($this->terrain->terrainIs($hexpart, "river") == true) {
//            $isBlocked = true;
//        }
        if ($this->terrain->isExit($hexagon) == true) {
            $isBlocked = true;
        }
        //alert(unitHexagon->getName() + " to " + hexagon->getName() + " zoc: " + $this->force->hexagonIsZOC(id, hexagon) + " occ: " + $this->force->hexagonIsOccupied(hexagon)  + " river: " + $this->terrain->terrainIs(hexpart, "river"));
        return $isBlocked;
    }

    function retreat($id, $hexagon)
    {
        /* @var  unit */
        $movingUnit = $this->force->units[$id];
        echo "call retreat ";
        if($this->retreatIsBlocked($id)){

            $hexagon = $movingUnit->getUnitHexagon();

            $this->force->addToRetreatHexagonList($id, $hexagon);

            $this->stopMove($movingUnit);
            $this->force->eliminateUnit($id);
        }
        echo "still calling ";
        var_dump($hexagon);
        var_dump($this->rangeIsOneHexagon($movingUnit->getUnitHexagon(), $hexagon));
        if ($this->rangeIsOneHexagon($movingUnit->getUnitHexagon(), $hexagon)
            && $this->hexagonIsBlocked($id, $hexagon) === false
            && $this->terrain->isExit($hexagon) === false
        ) {
            echo "why this much";

            $this->force->addToRetreatHexagonList($id, $movingUnit->getUnitHexagon());
            // set move amount to 0
            $occupied = $this->force->hexagonIsOccupied($hexagon);
            $movingUnit->updateMoveStatus($hexagon, 0);


            // check crt retreat count required to how far the unit has retreated
            if ($this->force->unitHasMetRetreatCountRequired($id) && !$occupied) {
                // stop if unit has retreated the required amount
                if ($movingUnit->setStatus( STATUS_STOPPED) == true) {
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
        }
        else
        {
            if (($this->anyUnitIsMoving == true) && ($id == $this->movingUnitId)) {
                $this->stopAdvance($this->movingUnitId);
            }
            else
            {
                if ($this->force->unitCanAdvance($id) == true) {
                    $this->startAdvancing($id);
                    $this->force->resetRemainingNonAdvancingUnits();
                }
            }
        }
    }

    function startAdvancing($id)
    {
            $hexagon = $this->force->getFirstRetreatHex($id);
            $startHex = $hexagon->name;
            $hexPath = new HexPath();
            $hexPath->name = $startHex->name;
            $hexPath->pointsLeft = $this->force->units[$id]->maxMove;
            $hexPath->pathToHere = array();
            $hexPath->firstHex = true;
            $this->moves->$startHex = $hexPath;

        if ($this->force->units[$id]->setStatus( STATUS_ADVANCING) == true) {
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
        if ($this->force->units[$id]->setStatus( STATUS_ADVANCED) == true) {
            $this->moves = new stdClass();
            $this->force->resetRemainingAdvancingUnits();
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
        }
    }

    function advanceIsValid($id, $hexagon)
    {
        $isValid = false;

        $startHexagon = $this->force->units[$id]->getUnitHexagon();
        if ($this->force->advanceIsOnRetreatList($id, $hexagon) == true && $this->rangeIsOneHexagon($startHexagon, $hexagon) == true) {
            //alert("retreat list: true");
            $isValid = true;
        }
        else
        {
            //alert("retreat list: false");
        }

        return $isValid;
    }
}
