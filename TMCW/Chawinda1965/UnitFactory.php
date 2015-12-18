<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 6/14/15
 * Time: 5:37 PM
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

class ChawindaUnit extends unit implements JsonSerializable
{

    public $secondaryId;
    public function getSecId(){
        return $this->secondaryId;
    }

    public $unitDefStrength;
    public $unitMinDefStrength;



    public function getUnmodifiedDefStrength(){
        if ($this->isReduced){
            $strength = $this->unitMinDefStrength;
        }else{
            $strength = $this->unitDefStrength;
        }
        return  $strength;
    }

    public function __get($name)
    {
        if ($name !== "strength" && $name !== "defStrength" && $name !== "attStrength") {
            return false;
        }

        if ($this->isReduced) {
            $strength = $this->minStrength;
        } else {
            $strength = $this->maxStrength;
        }
        if($name === "defStrength"){
            if ($this->isReduced){
                $strength = $this->unitMinDefStrength;
            }else{
                $strength = $this->unitDefStrength;
            }
        }

        foreach ($this->adjustments as $adjustment) {
            switch ($adjustment) {
                case 'floorHalf':
                    $strength = floor($strength / 2);
                    break;
                case 'half':
                    $strength = $strength / 2;
                    break;
                case 'double':
                    $strength = $strength * 2;
                    break;
            }
        }
        return $strength;
    }

    function damageUnit($kill = false)
    {
        $battle = Battle::getBattle();

        $this->status = STATUS_ELIMINATING;
        $this->exchangeAmount = $this->getUnmodifiedStrength();
        $this->defExchangeAmount = $this->getUnmodifiedDefStrength();
        return true;
    }

    public function fetchData(){
        $mapUnit = parent::fetchData();
        $mapUnit->defStrength = $this->defStrength;
        return $mapUnit;
    }

    public function combine($secondUnit){
        if($this->isReduced !== true){
            return false;
        }
        if($secondUnit->isReduced !== true){
            return false;
        }
        if($this->status === STATUS_MOVING){
            if($this->moveAmountUsed !== 0 || $secondUnit->moveAmountUsed !== 0){
                return false;
            }
        }
        $b = Battle::getBattle();
        $mapData = $b->mapData;
        $this->isReduced = false;
        $secondUnit->hexagon = 'combined-box';
        $secondUnit->status = STATUS_COMBINED;
        $secondUnit->isReduced = true;
        $this->secondaryId = $secondUnit->id;
        $mapHex = $mapData->getHex($this->hexagon->name);
        $mapHex->unsetUnit($secondUnit->forceId, $secondUnit->id);
        return true;
    }

    public function split(){
        if($this->isReduced === true){
            return false;
        }
        if($this->status === STATUS_MOVING){
            if($this->moveAmountUsed !== 0){
                return false;
            }
        }
        $b = Battle::getBattle();
        $secondUnit = $b->force->getUnit($this->secondaryId);
        $this->isReduced = true;
        $secondUnit->hexagon = $this->hexagon;
        $secondUnit->status = STATUS_CAN_DEPLOY;
        if($this->status === STATUS_MOVING){
            $secondUnit->status = STATUS_READY;
            $secondUnit->moveAmountUsed = $this->moveAmountUsed;
            $secondUnit->moveAmountUnused = $this->moveAmountUnused;
        }
        $secondUnit->isReduced = true;
        $this->secondaryId = false;
        $mapHex = $b->mapData->getHex($this->hexagon->name);
        $mapHex->setUnit($secondUnit->forceId, $secondUnit);
        return true;
    }

    public function mySet($id, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength,$unitDefMaxStrength, $unitDefMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig){
        $this->set($id, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig);
        $this->unitDefStrength = $unitDefMaxStrength;
        $this->unitMinDefStrength = $unitDefMinStrength;
    }
}


class UnitFactory {
    public static $id = 0;
    public static $injector;
    public static function build($data = false){

        $sU =  new ChawindaUnit($data);
        if($data === false){
            $sU->id = self::$id++;
        }
        return $sU;
    }
    public static function create( $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength, $unitDefMaxStrength, $unitDefMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range = 1, $nationality = "neutral", $forceMarch = true, $class = false, $unitDesig = false){
        $unit = self::build();
        $id = $unit->id;
        $unit->mySet($id, $unitName, $unitForceId, $unitHexagon, $unitImage, $unitMaxStrength, $unitMinStrength,$unitDefMaxStrength, $unitDefMinStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig);
        self::$injector->injectUnit($unit);
        if($isReduced === false){
            $secondUnit = self::build();
            $unit->secondaryId = $secondUnit->id;
            $secondUnit->mySet($id, $unitName, $unitForceId, 'combined-box', $unitImage, $unitMaxStrength, $unitMinStrength,$unitDefMaxStrength, $unitDefMinStrength, $unitMaxMove, true, STATUS_COMBINED, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig."/2");

            self::$injector->injectUnit($secondUnit);
        }
    }

}