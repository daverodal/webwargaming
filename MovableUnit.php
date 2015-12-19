<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/19/15
 * Time: 10:21 AM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class MovableUnit{
    public $id;
    public $forceId;
    public $name;
    /* @var Hexagon */
    public $hexagon;
    public $image;
    public $maxMove;
    public $status;
    public $moveAmountUsed;
    public $reinforceZone;
    public $reinforceTurn;

    public $nationality;
    public $forceMarch = false;
    public $class;
    public $dirty;
    public $unitDesig;
    public $moveAmountUnused;

    function unitHasMoveAmountAvailable($moveAmount)
    {
        if ($this->moveAmountUsed + $moveAmount <= $this->getMaxMove()) {
            $canMove = true;
        } else {
            $canMove = false;
        }
        return $canMove;
    }

    public function getMaxMove(){
        $maxMove = $this->maxMove;
        foreach ($this->adjustments as $name => $adjustment) {
            if($name === 'movement') {
                switch ($adjustment) {
                    case 'floorHalfMovement':
                        $maxMove = floor($maxMove / 2);
                        break;
                    case 'halfMovement':
                        $maxMove = $maxMove / 2;
                        break;
                    case 'oneMovement':
                        $maxMove = 1;
                        break;
                }
            }
        }
        return $maxMove;
    }

    function unitHasNotMoved()
    {
        if ($this->moveAmountUsed == 0) {
            $hasMoved = true;
        } else {
            $hasMoved = false;
        }
        return $hasMoved;
    }

    function unitIsMoving()
    {
        $isMoving = false;
        if ($this->status == STATUS_MOVING) {
            $isMoving = true;
        }
        return $isMoving;
    }

    function unitHasUsedMoveAmount()
    {
        // moveRules amount used can be larger if can always moveRules at least one hexagon
        if ($this->moveAmountUsed >= $this->getMaxMove()) {
            $maxMove = true;
        } else {
            $maxMove = false;
        }
        return $maxMove;
    }

    function getUnitHexagon()
    {

        return $this->hexagon;
    }

    function updateMoveStatus($hexagon, $moveAmount)
    {

        $battle = Battle::getBattle();
        $gameRules = $battle->gameRules;
        /* @var MapData $mapData */
        $mapData = $battle->mapData;
        $attackingForceId = $battle->force->attackingForceId;
//        $mapData = MapData::getInstance();
        /* @var MapHex $mapHex */
        $fromHex = $this->hexagon->getName();
        $toHex = $hexagon->getName();
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->unsetUnit($this->forceId, $this->id);
        }

        $this->hexagon = $hexagon;
        $this->dirty = true;
        $mapData->breadcrumbMove($this->id, $attackingForceId, $gameRules->turn, $gameRules->phase, $gameRules->mode, $fromHex, $toHex);
        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
            $mapHexName = $mapHex->name;
            if (isset($mapData->specialHexes->$mapHexName)) {

                if ($mapData->specialHexes->$mapHexName >= 0 && $mapData->specialHexes->$mapHexName != $this->forceId) {
                    $victory = $battle->victory;
                    $mapData->specialHexesChanges->$mapHexName = true;
                    $victory->specialHexChange($mapHexName, $this->forceId);
                    $mapData->alterSpecialHex($mapHexName, $this->forceId);
                }
            }
            if ($mapData->getMapSymbols($mapHexName) !== false) {
                $victory = $battle->victory;
                $victory->enterMapSymbol($mapHexName, $this);
            }
        }
        $this->moveCount++;
        $this->moveAmountUsed = $this->moveAmountUsed + $moveAmount;
    }

    function isDeploy(){
        return $this->hexagon->parent == "deployBox";
    }

    function getReplacing( $hexagon)
    {
        if ($this->status == STATUS_REPLACING) {
            $hexagon = new Hexagon($hexagon);
            $this->status = STATUS_REPLACED;
            $this->updateMoveStatus($hexagon, 0);
            return $this->id;
        }
        return false;
    }

    function unitIsReinforcing()
    {
        if ($this->status == STATUS_REINFORCING) {
            $isReinforcing = true;
        } else {
            $isReinforcing = false;
        }
        return $isReinforcing;
    }

    function unitIsDeploying()
    {
        if ($this->status == STATUS_DEPLOYING) {
            $isDeploying = true;
        } else {
            $isDeploying = false;
        }
        return $isDeploying;
    }


    function getUnitReinforceTurn()
    {
        return $this->reinforceTurn;
    }

    function getUnitReinforceZone()
    {
        return $this->reinforceZone;
    }


}
