<?php
// moveRules.js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

class MoveRules{
    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;

    // local variables
    public $movingUnitId;
    public $anyUnitIsMoving;


    function save(){
        $data = new StdClass();
        foreach($this as $k => $v){
            if(is_object($v)){
                continue;
            }
            $data->$k = $v;
        }
        return $data;
    }
    function __construct($Force, $Terrain, $data = null)
    {
        // Class references

        $this->force = $Force;
        $this->terrain = $Terrain;

        if($data){
            foreach($data as $k => $v){
                $this->$k = $v;
            }
        }else{
        $this->movingUnitId = NONE;
        $this->anyUnitIsMoving = false;
        }
    }

// id will be map if map event, id will be unit id if counter event
    function moveUnit($eventType, $id, $hexagon, $turn)
    {
        if ($eventType == SELECT_MAP_EVENT) {
            echo "MoveUnit";
            if ($this->anyUnitIsMoving) {
                // click on map, so try to move
                if ($this->force->unitIsMoving($this->movingUnitId) == true) {
                    echo "unit is moving";
                    $this->move($this->movingUnitId, $hexagon);
                }
                if ($this->force->unitIsReinforcing($this->movingUnitId) == true) {
                    echo "ReinUnit";

                    $this->reinforce($this->movingUnitId, $hexagon);
                    echo "ReindUnit";
                }
            }
        }
        else
            // click on a unit
        {
            if ($this->anyUnitIsMoving == true) {
                if ($id == $this->movingUnitId) {
                    // clicked on moving or reinforcing unit
                    if ($this->force->unitIsMoving($id) == true) {
                        $this->stopMove($id);
                    }
                    if ($this->force->unitIsReinforcing($id) == true) {
                        $this->stopReinforcing($id);
                    }
                }
                else
                {
                    // clicked on another unit
                    $this->moveOver($this->movingUnitId, $id, $hexagon);
                }
            }
            else
            {
                // no one is moving, so start new move
                if ($this->force->unitCanMove($id) == true) {
                    $this->startMoving($id);
                }
                if ($this->force->unitCanReinforce($id) == true) {
                     $this->startReinforcing($id, $turn);
                }
            }
        }
    }

    function startMoving($id)
    {
        if ($this->force->unitIsZOC($id) == false) {
            if ($this->force->setStatus($id, STATUS_MOVING) == true) {
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function move($id, $hexagon)
    {
        echo "Mov???";
        var_dump( $this->moveIsValid($id, $hexagon));
        if ($this->force->unitIsMoving($this->movingUnitId)
            && $this->moveIsValid($id, $hexagon)
        ) {
            echo "move is valid";
            $this->updateMoveData($id, $hexagon);
        }
    }

    function moveOver($id, $moveOverUnitId, $hexagon)
    {
        if ($this->force->unitIsFriendly($moveOverUnitId) == true) {
            if ($this->moveIsValid($id, $hexagon) == true) {
                if ($this->moveWillCauseStop($id, $moveOverUnitId, $hexagon) == false) {
                    $this->updateMoveData($id, $hexagon);
                }
                else
                {
                    alert("unit cannot end move in hexagon with another unit");
                }
            }
        }
    }

    function stopMove($id)
    {
        if ($this->force->unitIsMoving($id) == true) {
            if ($this->force->setStatus($id, STATUS_STOPPED) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = 0;
            }
        }
    }

    function eexit($id)
    {
        if ($this->force->unitIsMoving($id) == true) {
            if ($this->force->setStatus($id, STATUS_EXITED) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = 0;
            }
        }
    }

    function moveWillCauseStop($id, $moveOverUnitId, $hexagon)
    {
        $willCauseStop = false;
        $moveAmount = $this->terrain->getTerrainMoveCost($this->force->getUnitHexagon($id), hexagon, $this->force->getUnitMaximumMoveAmount($id));

        // out of moves stop
        if ($this->force->unitWillUseMaxMove($id, $moveAmount) == true) {
            $willCauseStop = true;
        }

        // zone of control stop
        if ($this->force->unitIsZOC($moveOverUnitId) == true) {
            $willCauseStop = true;
        }

        // if using 'can always move one hexagon' and stop rule
        if ($this->force->unitHasNotMoved($id) == true) {
            if (($this->force->unitHasMoveAmountAvailable($id, $moveAmount) == false)) {
                $willCauseStop = true;
            }
        }

        // if moving across river stop
        if (($this->moveIsAcrossRiverNoBridge($id, $hexagon) == true) && ($this->force->unitHasNotMoved($id) == true)) {
            $willCauseStop = true;
        }

        return $willCauseStop;
    }

    function moveIsValid($id, $hexagon)
    {
        echo "InVAlie $id";
        // all 4 conditions must be true, so any one that is false
        //    will make the move invalid

        $isValid = true;
        var_dump($this->force->units[5]->hexagon);
var_dump($this->force->getUnitHexagon($id));
        var_dump($hexagon);

        // condition 1
        // can only move to nearby hexagon
        if ($this->rangeIsOneHexagon($this->force->getUnitHexagon($id), $hexagon) == false) {
            $isValid = false;
        }
echo "really???";
        // condition 2
        // check if unit has enough move points
        $moveAmount = $this->terrain->getTerrainMoveCost($this->force->getUnitHexagon($id), $hexagon, $this->force->getUnitMaximumMoveAmount($this->movingUnitId));

        // need move points, but can always move at least one hexagon
        //  can always move at least one hexagon
        //  only check move amount if unit has been moving
        if ($this->force->unitHasNotMoved($id) == false) {
            if ($this->force->unitHasMoveAmountAvailable($id, $moveAmount) == false) {
                $isValid = false;
            }
        }

        // condition 3
        // can only move across river hexside if at start of move
        if (($this->moveIsAcrossRiverNoBridge($id, $hexagon) == true) && ($this->force->unitHasNotMoved($id) == false)) {
            $isValid = false;
        }

        // condition 4
        // can not exit
        if (($this->terrain->isExit($hexagon) == true)) {
            $isValid = false;
        }
        return $isValid;
    }

    function moveIsAcrossRiverNoBridge($id, $hexagon)
    {
        $moveIsAcrossRiverNoBridge = false;

        $hexpart = new Hexpart($this->force->getUnitHexagon($id)->getX(), $this->force->getUnitHexagon($id)->getY());

        // check for river without a bridge
        if (($this->terrain->terrainIs($hexpart, "river") == true)
            && ($this->terrain->terrainIs($hexpart, "road") == false)
        ) {
            $moveIsAcrossRiverNoBridge = true;
        }
        return $moveIsAcrossRiverNoBridge;
    }

    function updateMoveData($id, $hexagon)
    {
        $moveAmount = $this->terrain->getTerrainMoveCost($this->force->getUnitHexagon($id), $hexagon, $this->force->getUnitMaximumMoveAmount($id));

        $this->force->updateMoveStatus($id, $hexagon, $moveAmount);

        if ($this->force->unitHasUsedMoveAmount($id) == true) {
            $this->stopMove($id);
        }

        if ($this->force->unitIsZOC($id) == true) {
            $this->stopMove($id);
        }

        if ($this->terrain->isExit($hexagon)) {
            $this->eexit($id);
        }
    }

    function rangeIsOneHexagon(Hexagon $startHexagon,Hexagon $endHexagon)
    {
        $rangeIsOne = false;

        $los = new Los();
        $los->setOrigin($startHexagon);
        $los->setEndPoint($endHexagon);
echo "In  Range Is On";
        if ($los->getRange() == 1) {
            $rangeIsOne = true;
        }

        return $rangeIsOne;
    }

    function startReinforcing($id, $turn)
    {echo "startRein";
        if ($this->force->getUnitReinforceTurn($id) <= $turn) {
            if ($this->force->setStatus($id, STATUS_REINFORCING) == true) {
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function reinforce($id, $hexagon)
    {
        echo "Reinfor".$hexagon->number;
        var_dump($this->force->units[$id]);

        if ($this->force->unitIsReinforcing($id) == true) {
            echo "isRe doing I";
            echo $this->force->getUnitReinforceZone($id);echo " x ";
            echo $this->terrain->getReinforceZone($hexagon);
            if ($this->force->getUnitReinforceZone($id) == $this->terrain->getReinforceZone($hexagon)) {
                echo "movementCost";
                // get move cost
                $moveAmount = $this->terrain->getTerrainEntranceMoveCost($hexagon);

                // override move cost if road movement
                $hexpart = new Hexpart($hexagon->getX(), $hexagon->getY());

                if ($this->terrain->terrainIs($hexpart, "road") == true) {
                    $moveAmount = $this->terrain->getTerrainTraverseCostFor("road");
                }

                // set status
                if ($this->force->unitHasMoveAmountAvailable($id, $moveAmount) == true) {
                    if ($this->force->setStatus($id, STATUS_MOVING) == true) {
                        $this->force->updateMoveStatus($id, $hexagon, $moveAmount);
                    }
                }

                // stop if out of moves
                if ($this->force->unitHasUsedMoveAmount($id) == true) {
                    $this->stopMove($id);
                }
            }
        }
    }

    function stopReinforcing($id)
    {
        if ($this->force->unitIsReinforcing($id) == true) {
            if ($this->force->setStatus($id, STATUS_CAN_REINFORCE) == true) {
                $this->anyUnitIsMoving = false;
                $this->movingUnitId = 0;
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
        }
        else
        {
            echo "Counter event of the close kind ";
            // id will be retreating unit id if counter event
            if ($this->anyUnitIsMoving == false) {
                if ($this->force->unitCanRetreat($id) == true) {
                    echo "Start";
                    $this->startRetreating($id);
                    echo "dieid";
                }
            }
        }
    }

    function startRetreating($id)
    {
        if ($this->force->setStatus($id, STATUS_RETREATING) == true) {
            echo "It is tru! ";
            if ($this->retreatIsBlocked($id) == true) {
                echo("reteat is blocked");

                $hexagon = $this->force->getUnitHexagon($id);

                $this->force->addToRetreatHexagonList($id, $hexagon);

                $this->stopMove($id);
                $this->force->eliminateUnit($id);
            }
            else
            {
                echo "any unit IS moving $id";
                $this->anyUnitIsMoving = true;
                $this->movingUnitId = $id;
            }
        }
    }

    function retreatIsBlocked($id)
    {
        echo "is the retreat blocked?";
        $isBlocked = true;

        $adjacentHexagonXadjustment = array(0, 2, 2, 0, -2, -2);
        $adjacentHexagonYadjustment = array(-4, -2, 2, 4, 2, -2);

        $hexagon = $this->force->getUnitHexagon($id);
        $hexagonX = $hexagon->getX($id);
        $hexagonY = $hexagon->getY($id);
echo "enter the for";
        for ($eachHexagon = 0; $eachHexagon < 6; $eachHexagon++)
        {
            echo "$eachHexagon \n";
            $adjacentHexagonX = $hexagonX + $adjacentHexagonXadjustment[$eachHexagon];
            $adjacentHexagonY = $hexagonY + $adjacentHexagonYadjustment[$eachHexagon];
            $adjacentHexagon = new Hexagon($adjacentHexagonX, $adjacentHexagonY);
            //alert("testing " + adjacentHexagon->getName());
            echo "///// $eachHexagon \n";

            if ($this->hexagonIsBlocked($id, $adjacentHexagon) == false) {
                $isBlocked = false;
                break;
            }
            echo "wwww $eachHexagon \n";

        }

        return $isBlocked;
    }

    function hexagonIsBlocked($id, $hexagon)
    {
        $isBlocked = false;

        $unitHexagon = $this->force->getUnitHexagon($id);

        $hexsideX = ($hexagon->getX() + $unitHexagon->getX($id)) / 2;
        $hexsideY = ($hexagon->getY() + $unitHexagon->getY($id)) / 2;

        $hexpart = new Hexpart($hexsideX, $hexsideY);
        // make sure hexagon is not ZOC
        if (($this->force->hexagonIsZOC($id, $hexagon) == true)) {
            $isBlocked = true;
        }
        // make sure hexagon is not occupied

        if ($this->force->hexagonIsOccupied($hexagon) == true) {
            $isBlocked = true;
        }
        // make sure hexagon is not retreating across a river

        if ($this->terrain->terrainIs($hexpart, "river") == true) {
            $isBlocked = true;
        }

        if ($this->terrain->isExit($hexagon) == true) {
            $isBlocked = true;
        }
        echo "hexagonIsblocked"; var_dump($isBlocked);
        //alert(unitHexagon->getName() + " to " + hexagon->getName() + " zoc: " + $this->force->hexagonIsZOC(id, hexagon) + " occ: " + $this->force->hexagonIsOccupied(hexagon)  + " river: " + $this->terrain->terrainIs(hexpart, "river"));
        return $isBlocked;
    }

    function retreat($id, $hexagon)
    {
        if ($this->rangeIsOneHexagon($this->force->getUnitHexagon($id), $hexagon)
            && $this->hexagonIsBlocked($id, $hexagon) == false
            && $this->terrain->isExit($hexagon) == false
        ) {
            echo "retreating ";
            $this->force->addToRetreatHexagonList($id, $this->force->getUnitHexagon($id));
echo " and more retreating ";
            // set move amount to 0
            $this->force->updateMoveStatus($id, $hexagon, 0);

            // check crt retreat count required to how far the unit has retreated
            if ($this->force->unitHasMetRetreatCountRequired($id)) {
                // stop if unit has retreated the required amount
                if ($this->force->setStatus($id, STATUS_STOPPED) == true) {
                    $this->anyUnitIsMoving = false;
                    $this->movingUnitId = 0;
                }
            }
        }

        // if forced to retreat offmap, unit is eliminated
        if ($this->terrain->isExit($hexagon) == true) {
            $this->stopMove($id);
            $this->force->eliminateUnit($id);
        }
    }

// advancing rules

    function advanceUnit($eventType, $id, $hexagon)
    {
        echo "advanceUnit ";
        if ($eventType == SELECT_MAP_EVENT) {
            echo "MapEvent";
            if ($this->anyUnitIsMoving == true) {
                echo "yes its' moving ";
                //alert("advance");
                $this->advance($this->movingUnitId, $hexagon);
                echo "die id move?";
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
                }
            }
        }
    }

    function startAdvancing($id)
    {
        if ($this->force->setStatus($id, STATUS_ADVANCING) == true) {
            $this->anyUnitIsMoving = true;
            $this->movingUnitId = $id;
        }
    }

    function advance($id, $hexagon)
    {
        echo "advance it ";
        if ($this->advanceIsValid($id, $hexagon) == true) {
            echo "momsaidicoulddoit";
            // set move amount to 0
            $this->force->updateMoveStatus($id, $hexagon, 0);
            $this->stopAdvance($id);
        }
    }

    function stopAdvance($id)
    {
        if ($this->force->setStatus($id, STATUS_ADVANCED) == true) {
            $this->force->resetRemainingAdvancingUnits();
            $this->anyUnitIsMoving = false;
            $this->movingUnitId = NONE;
        }
    }

    function advanceIsValid($id, $hexagon)
    {
        $isValid = false;

        $startHexagon = $this->force->getUnitHexagon($id);
echo "onrlist? ";
        if ($this->force->advanceIsOnRetreatList($id, $hexagon) == true && $this->rangeIsOneHexagon($startHexagon, $hexagon) == true) {
            echo "True? ";
            //alert("retreat list: true");
            $isValid = true;
        }
        else
        {
            echo "false?";
            //alert("retreat list: false");
        }

        return $isValid;
    }
}