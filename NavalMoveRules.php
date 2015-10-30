<?php
// moveRules.js

// Copyright (c) 2009-2011 Mark Butler
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

require_once "moveRules.php";
$numWalks = 0;
class NavalMoveRules
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
    protected $moves;
    protected $path;
    protected $moveQueue;

    /* usually used for a closure, it's the amount of enemies or greater you CANNOT stack with
     * so 1 means you can't stack with even 1 enemy. Use a closure here to allow for air units stacking with
     * enemy land units only, for example. and vice a versa.
     */
    public $enemyStackingLimit = 1;

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



    function selectUnit($eventType, $id, $hexagon, $turn){
    // no one is moving, so start new move
        $dirty = false;
        if ($this->anyUnitIsMoving == true) {
            $movingUnit = $this->force->units[$id];
            // clicked on moving or reinforcing unit
            /* @var Unit $movingUnit */
            if ($movingUnit->unitIsMoving() == true) {
                $this->stopSpeed($movingUnit);
                $dirty = true;
            }else{
                $movingUnit = $this->force->units[$this->movingUnitId];
                $this->stopSpeed($movingUnit);
                $this->startSpeeding($id);
                $dirty = true;
            }
        }else{

            if ($this->force->unitCanMove($id) == true) {
                $this->startSpeeding($id);
                $dirty = true;
            }
        }

        return $dirty;

    }
    function turnLeft(){
        if ($this->anyUnitIsMoving) {
            $movingUnit = $this->force->units[$this->movingUnitId];
            $movesLeft = $movingUnit->maxMove - $movingUnit->moveAmountUsed;
            $turnCost = 1;
            if($movingUnit->class === "cl" || $movingUnit->class === "ca" ){
                $turnCost = 2;
            }
            if($movingUnit->class === "bb" || $movingUnit->class === "bc" ){
                $turnCost = 3;
            }
            if($movesLeft >= $turnCost){
                $movingUnit->facing--;
                if($movingUnit->facing < 0){
                    $movingUnit->facing += 6;
                }
                $movingUnit->moveAmountUsed += $turnCost;
                if($movingUnit->moveAmountUsed >= $movingUnit->maxMove){
                    $this->stopMove($movingUnit);
                    return;
                }
                $this->calcMove($this->movingUnitId);
            }
        }

    }

    function slower(){
        if ($this->anyUnitIsMoving) {
            $movingUnit = $this->force->units[$this->movingUnitId];
            if($movingUnit->newSpeed === false){
                $movingUnit->newSpeed = $movingUnit->maxMove;
            }
            $maxChange = 2;
            if($movingUnit->class === "cl" || $movingUnit->class === "ca" ){
                $maxChange = 2;
            }
            if($movingUnit->class === "bb" || $movingUnit->class === "bc" ){
                $maxChange = 1;
            }
            if($movingUnit->maxMove > 0 && ($movingUnit->newSpeed >  ($movingUnit->maxMove - $maxChange))){
                $movingUnit->newSpeed--;
            }

        }

    }

    function faster(){
        if ($this->anyUnitIsMoving) {
            $movingUnit = $this->force->units[$this->movingUnitId];
            if($movingUnit->newSpeed === false){
                $movingUnit->newSpeed = $movingUnit->maxMove;
            }
            $maxChange = 3;
            $maxSpeed = 7;
            if($movingUnit->class === "cl" || $movingUnit->class === "ca" ){
                $maxSpeed = 6;
                $maxChange = 2;
            }
            if($movingUnit->class === "bb" || $movingUnit->class === "bc" ){
                $maxSpeed = 6;
                $maxChange = 1;
            }
            if($movingUnit->pDamage === 1){
                $maxSpeed = floor($maxSpeed/2);
            }
            if($movingUnit->pDamage === 2){
                $maxSpeed = 0;
            }
            if($movingUnit->maxMove < $maxSpeed && ($movingUnit->newSpeed <  ($movingUnit->maxMove + $maxChange))){
                $movingUnit->newSpeed++;
            }
        }

    }

    function turnRight(){
        if ($this->anyUnitIsMoving) {
            $movingUnit = $this->force->units[$this->movingUnitId];
            $movesLeft = $movingUnit->maxMove - $movingUnit->moveAmountUsed;
            $turnCost = 1;
            if($movingUnit->class === "cl" || $movingUnit->class === "ca" ){
                $turnCost = 2;
            }
            if($movingUnit->class === "bb" || $movingUnit->class === "bc" ){
                $turnCost = 3;
            }
            if($movesLeft >= $turnCost){
                $movingUnit->facing++;
                if($movingUnit->facing >= 6){
                    $movingUnit->facing -= 6;
                }
                $movingUnit->moveAmountUsed += $turnCost;
                if($movingUnit->moveAmountUsed >= $movingUnit->maxMove){
                    $this->stopMove($movingUnit);
                    return;
                }
                $this->calcMove($this->movingUnitId);
            }
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
                        $facing = $this->moves->$newHex->facing;
                        $movingUnit->facing = $facing;
                        $this->moves = new stdClass();

                        $this->move($movingUnit, $newHex);
                        $this->path = array();
                        if ($this->anyUnitIsMoving) {
                            $this->moveQueue = array();
                            $hexPath = new HexPath();
                            $hexPath->name = $newHex; //$startHex->name;
                            $hexPath->pointsLeft = $movesLeft;
                            $hexPath->pathToHere = array();
                            $hexPath->firstHex = true;
                            $hexPath->isOccupied = true;
                            $hexPath->facing = $facing;
                            $movingUnit->facing = $facing;
                            $this->moveQueue[] = $hexPath;
                            $this->bfsMoves();

                            $movesAvail = 0;
                            foreach ($this->moves as $move) {
                                if ($move->isOccupied || !$move->isValid) {
                                    continue;
                                }
                                $movesAvail++;
                            }

                        }
                        $dirty = true;
                    }
                }
                if ($movingUnit->unitIsReinforcing() == true) {
                    $this->reinforce($this->movingUnitId, new Hexagon($hexagon));
                    $this->calcMove($id);
                    $dirty = true;
                }
                if ($movingUnit->unitIsDeploying() == true) {
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
                    if ($movingUnit->unitIsReinforcing() == true) {
                        $this->stopReinforcing($id);
                        $dirty = true;
                    }
                    if ($movingUnit->unitIsDeploying() == true) {
                        $this->stopDeploying($id);
                        $dirty = true;
                    }
                } else {
                    /* @var Unit $movingUnit */
                    $movingUnit = $this->force->units[$this->movingUnitId];
                    $movingUnitId = $this->movingUnitId;


                    if ($eventType == KEYPRESS_EVENT) {
                        if ($this->force->unitCanMove($movingUnitId) == true) {
                            $this->startMoving($movingUnitId);
                            $this->calcMove($movingUnitId);
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


    function calcMove($id)
    {
        global $numWalks;
        global $numBangs;
        $unit = $this->force->units[$id];
        $numWalks = 0;
        $numBangs = 0;
        $startHex = $unit->hexagon;
        $movesLeft = $unit->maxMove - $unit->moveAmountUsed;
        $this->moves = new stdClass();
        $this->moveQueue = array();
        $hexPath = new HexPath();
        $hexPath->name = $startHex->name;
        $hexPath->pointsLeft = $movesLeft;
        $hexPath->pathToHere = array();
        $hexPath->firstHex = true;
        $hexPath->isOccupied = true;
        $hexPath->facing = $this->force->units[$id]->facing;
        $this->moveQueue[] = $hexPath;
        $this->bfsMoves();

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

            if ($mapHex->isOccupied($this->force->attackingForceId, $this->stacking, $unit)) {
                $this->moves->$hexNum->isOccupied = true;
            }

            if ($mapHex->isOccupied($this->force->defendingForceId,$this->enemyStackingLimit, $unit)) {
                $this->moves->$hexNum->isValid = false;
                continue;
            }
            $this->moves->$hexNum->pointsLeft = $movePoints;
            $this->moves->$hexNum->pathToHere = $hexPath->pathToHere;

            $exitCost = 0;
//            if($this->moves->$hexNum->isClone){
//                continue;
//            }

            $path = $hexPath->pathToHere;
            $path[] = $hexNum;


            $neighbors = $mapHex->neighbors;
            if($hexPath->firstHex && isset($hexPath->facing)){
                $neighbors = array_slice(array_merge($mapHex->neighbors,$mapHex->neighbors), ($hexPath->facing + 6 - 1)%6, 3);
                $newFacing = ($hexPath->facing + 6 - 2);

            }else{
                $neighbors = [$neighbors[$hexPath->facing]];
                $newFacing = $hexPath->facing +6 -1;
            }
            /* we pre increment, so facing should be hex to the left, -1, hex forward 0, hex to the right +1
             *  Add 6 because -1 doesn't mod well
            */
            foreach ($neighbors as $neighbor) {
                $newFacing++;
                $newHexNum = $neighbor;
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

                if ($newMapHex->isOccupied($this->force->defendingForceId, $this->enemyStackingLimit, $unit)) {
                    continue;
                }

                if ($moveAmount <= 0) {
                    $moveAmount = 1;
                }


                if ($movePoints - $moveAmount >= 0) {
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
                        $newPath->facing = $newFacing % 6;
                    if ($newPath->pointsLeft < 0) {
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

    public function movesLeft(){
        foreach($this->force->units as $unit){
            if($unit->forceId !== $this->force->attackingForceId){
                continue;
            }
            if($unit->hexagon->parent === "gameImages" && $unit->maxMove > $unit->moveAmountUsed){
                return true;
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
                $this->moves->$hexNum->isZoc = $this->force->
                mapHexIsZOC($mapHex, $defendingForceId);
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

    function startSpeeding($id)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        $victory->preStartMovingUnit($unit);

        /*
         * Don't think this is important test. Unit will be STATUS_STOPPED if cannot move.
         */
        $this->anyUnitIsMoving = true;
        $this->movingUnitId = $id;
        if ($unit->setStatus(STATUS_MOVING) == true) {

        }
        $victory->postStartMovingUnit($unit);
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
        if ($unit->setStatus(STATUS_MOVING) == true) {
            $this->anyUnitIsMoving = true;
            $this->movingUnitId = $id;
        }
        $victory->postStartMovingUnit($unit);
    }

    function move(MovableUnit $movingUnit, $hexagon)
    {
        if ($movingUnit->unitIsMoving()
            && $this->moveIsValid($movingUnit, $hexagon)
        ) {
            $this->updateMoveData($movingUnit, $hexagon);
        }
    }

    function stopSpeed(MovableUnit $movingUnit)
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

    function stopMove(MovableUnit $movingUnit)
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


    function moveIsValid(MovableUnit $movingUnit, $hexagon, $startHex = false, $firstHex = false)
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

    function updateMoveData(MovableUnit $movingUnit, $hexagon)
    {
        $battle = Battle::getBattle();
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $fromHex = $movingUnit->hexagon;
        $moveAmount = $this->terrain->getTerrainMoveCost($movingUnit->getUnitHexagon()->name, $hexagon, $movingUnit->forceMarch, $movingUnit);
        /* @var MapHex $mapHex */
        $mapHex = $mapData->getHex($hexagon);
        $fromMapHex = $mapData->getHex($fromHex->name);

        $movingUnit->updateMoveStatus(new Hexagon($hexagon), $moveAmount);

        if ($movingUnit->unitHasUsedMoveAmount() == true) {
            $this->stopMove($movingUnit);
        }

        foreach($this->force->units as $unit){
            if($movingUnit->forceId === $unit->forceId){
                continue;
            }
            if($unit->hexagon->parent !== 'gameImages'){
                continue;
            }
            if($this->inRange($movingUnit->hexagon->name, $unit->hexagon->name, 8)){
                $movingUnit->spotted = true;
                break;
            }
        }

        if ($this->terrain->isExit($hexagon)) {
            $this->eexit($movingUnit->id);
        }
    }


    function inRange($startHexagon, $endHexagon, $range)
    {
        $los = new Los();
        $los->setOrigin($startHexagon);
        $los->setEndPoint($endHexagon);
        if ($los->getRange() <= $range) {
            return true;
        }

        return false;
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
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->getUnitReinforceTurn($id) <= $turn) {


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
                    if ($this->force->hexagonIsOccupied($zone->hexagon, $this->stacking, $unit)) {
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
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);
        if ($unit->getUnitReinforceTurn($id) <= $turn) {

            if ($unit->setStatus(STATUS_DEPLOYING) == true) {
                $battle = Battle::getBattle();
                $victory = $battle->victory;
                $movesLeft = 0;
                $zoneName = $unit->reinforceZone;
                $zones = $this->terrain->getReinforceZonesByName($zoneName);
                list($zones) = $battle->victory->postDeployZones($zones, $unit);
                foreach ($zones as $zone) {
                    $startHex = $zone->hexagon->name;
                    if ($this->force->hexagonIsOccupied($zone->hexagon, $this->stacking, $unit)) {
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
            $zones = $this->terrain->getReinforceZonesByName($unit->getUnitReinforceZone($id));
            list($zones) = $battle->victory->postReinforceZones($zones, $unit);
            foreach ($zones as $zone) {
                if ($this->force->hexagonIsOccupied($zone->hexagon, $this->stacking, $unit)) {
                    continue;
                }
                if(!$zone->hexagon || !$zone->hexagon->name){
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
        $this->moves = new stdClass();
    }

    function reinforce($id, Hexagon $hexagon)
    {

        $battle = Battle::getBattle();
        if ($this->force->unitIsReinforcing($id) == true) {

            list($zones) = $battle->victory->postReinforceZoneNames($this->terrain->getReinforceZoneList($hexagon), $battle->force->units[$id]);

            /* @var Unit $movingUnit */
            $movingUnit = $this->force->getUnit($id);
            if (in_array($movingUnit->getUnitReinforceZone($id) , $zones)) {

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
        /* @var Unit $movingUnit */
        $movingUnit = $this->force->units[$id];
        if ($movingUnit->unitIsDeploying($id) == true) {
            if (in_array($movingUnit->getUnitReinforceZone($id), $this->terrain->getReinforceZoneList($hexagon))) {

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
        /* @var Unit $unit */
        $unit = $this->force->getUnit($id);

        if ($unit->unitIsDeploying() == true) {
            $unit = $this->force->getUnit($id);
            if ($unit->setStatus(STATUS_CAN_DEPLOY) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = NONE;
                $this->moves = new stdClass();
            }
        }
    }

}
