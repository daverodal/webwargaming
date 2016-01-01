<?php
namespace TMCW\Chawinda1965;

    use \Hexagon;
    use \stdClass;
    use \MapData;
    use \Battle;

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
    class ChawindaUnit extends \BaseUnit implements \JsonSerializable
    {


//    private $maxMove;
        public $isReduced;
        public $supplied = true;


        public $secondaryId;

        public $unitStrength;
        public $secondUnitStrength;
        public $unitDefStrength;
        public $secondUnitDefStrength;

        function __construct($data = null)
        {
            if ($data) {
                foreach ($data as $k => $v) {
                    if ($k == "hexagon") {
                        $this->hexagon = new Hexagon($v);
                        continue;
                    }
                    $this->$k = $v;
                }
                $this->dirty = false;
            } else {
                $this->adjustments = new stdClass();
            }
        }


        public function jsonSerialize()
        {
            if (is_object($this->hexagon)) {
                if ($this->hexagon->name) {
                    $this->hexagon = $this->hexagon->getName();

                } else {
                    $this->hexagon = $this->hexagon->parent;
                }
            }
            return $this;
        }


        public function getUnmodifiedStrength()
        {
            if ($this->isReduced) {
                $strength = $this->unitStrength;
            } else {
                $strength = $this->unitStrength + $this->secondUnitStrength;
            }
            return $strength;
        }


        public function getUnmodifiedDefStrength()
        {
            if ($this->isReduced) {
                $strength = $this->unitDefStrength;
            } else {
                $strength = $this->unitDefStrength + $this->secondUnitDefStrength;
            }
            return $strength;
        }

        public function getMaxMove()
        {
            $maxMove = $this->maxMove;
            foreach ($this->adjustments as $name => $adjustment) {
                if ($name === 'movement') {
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


        public function __get($name)
        {
            if ($name !== "strength" && $name !== "defStrength" && $name !== "attStrength") {
                return false;
            }


            if ($this->isReduced) {
                $strength = floor($this->unitStrength);
            } else {
                $strength = $this->unitStrength + $this->secondUnitStrength + 2;
            }
            if ($name === "defStrength") {
                if ($this->isReduced) {
                    $strength = floor($this->unitDefStrength);
                } else {
                    $strength = $this->unitDefStrength + $this->secondUnitDefStrength + 2;
                }
            }

            foreach ($this->adjustments as $name => $adjustment) {
                switch ($adjustment) {
                    case 'floorHalf':
                        $strength = floor($strength / 2);
                        break;
                    case 'half':
                        $strength = floor($strength / 2);
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

            if ($this->isReduced || $kill) {
                $this->status = STATUS_ELIMINATING;
                $this->exchangeAmount = $this->getUnmodifiedStrength();
                $this->defExchangeAmount = $this->getUnmodifiedDefStrength();
                return true;
            } else {
                $this->damage = $this->secondUnitStrength;
                $battle->victory->reduceUnit($this);
                $this->isReduced = true;
                $this->secondaryId = false;
                $this->secondUnitStrength = false;
                $this->secondUnitDefStrength = false;
                $this->exchangeAmount = $this->damage;
                $this->defExchangeAmount = $this->damage;
            }
            return false;
        }


        public function fetchData()
        {
            $mapUnit = new stdClass();
            $mapUnit->isReduced = $this->isReduced;
            $mapUnit->parent = $this->hexagon->parent;
            $mapUnit->moveAmountUsed = $this->moveAmountUsed;
            $mapUnit->maxMove = $this->getMaxMove();
            $mapUnit->strength = $this->strength;
            $mapUnit->supplied = $this->supplied;
            $mapUnit->reinforceZone = $this->reinforceZone;
            $mapUnit->forceId = $this->forceId;
            $mapUnit->defStrength = $this->defStrength;
            $mapUnit->status = $this->status;

            return $mapUnit;
        }

        public function combine($secondUnit)
        {
            if ($this->isReduced !== true) {
                return false;
            }
            if ($secondUnit->isReduced !== true) {
                return false;
            }
            if ($this->status === STATUS_MOVING) {
                if ($this->moveAmountUsed !== 0 || $secondUnit->moveAmountUsed !== 0) {
                    return false;
                }
            }
            $b = Battle::getBattle();
            $mapData = $b->mapData;
            $this->isReduced = false;
            $this->secondUnitStrength = $secondUnit->unitStrength;
            $this->secondUnitDefStrength = $secondUnit->unitDefStrength;
            $secondUnit->hexagon = 'combined-box';
            $secondUnit->status = STATUS_COMBINED;
            $secondUnit->isReduced = true;
            $this->secondaryId = $secondUnit->id;
            $mapHex = $mapData->getHex($this->hexagon->name);
            $mapHex->unsetUnit($secondUnit->forceId, $secondUnit->id);
            return true;
        }

        public function split()
        {
            if ($this->isReduced === true) {
                return false;
            }
            if ($this->status === STATUS_MOVING) {
                if ($this->moveAmountUsed !== 0) {
                    return false;
                }
            }
            $b = Battle::getBattle();
            $secondUnit = $b->force->getUnit($this->secondaryId);
            $this->isReduced = true;
            $secondUnit->hexagon = $this->hexagon;
            $secondUnit->status = STATUS_CAN_DEPLOY;
            if ($this->status === STATUS_MOVING) {
                $secondUnit->status = STATUS_READY;
                $secondUnit->moveAmountUsed = $this->moveAmountUsed;
                $secondUnit->moveAmountUnused = $this->moveAmountUnused;
                $secondUnit->supplied = $this->supplied;
                $secondUnit->adjustments = $this->adjustments;
            }
            $this->secondUnitStrength = false;
            $this->secondUnitDefStrength = false;
            $secondUnit->isReduced = true;
            $this->secondaryId = false;
            $mapHex = $b->mapData->getHex($this->hexagon->name);
            $mapHex->setUnit($secondUnit->forceId, $secondUnit);
            return true;
        }

        function set($unitId, $unitName, $unitForceId, $unitHexagon, $unitImage, $firstUnitStrength, $secondUnitStrength, $firstUnitDefstrength, $secondUnitDefStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $forceMarch, $class, $unitDesig)
        {
            $this->dirty = true;
            $this->id = $unitId;
            $this->name = $unitName;
            $this->forceId = $unitForceId;
            $this->class = $class;
            $this->hexagon = new Hexagon($unitHexagon);
            /* blah! this can get called from the constructor of Battle. so we can't get ourselves while creating ourselves */
//        $battle = Battle::getBattle();
//        $mapData = $battle->mapData;
            $mapData = MapData::getInstance();
            $mapHex = $mapData->getHex($this->hexagon->getName());
            if ($mapHex) {
                $mapHex->setUnit($this->forceId, $this);
            }
            $this->image = $unitImage;
//        $this->strength = $isReduced ? $unitMinStrength : $unitMaxStrength;
            $this->maxMove = $unitMaxMove;
            $this->moveAmountUnused = $unitMaxMove;
            $this->unitStrength = $firstUnitStrength;
            $this->secondUnitStrength = $secondUnitStrength;
            $this->isReduced = $isReduced;
            $this->status = $unitStatus;
            $this->moveAmountUsed = 0;
            $this->reinforceZone = $unitReinforceZone;
            $this->reinforceTurn = $unitReinforceTurn;
            $this->combatNumber = 0;
            $this->combatIndex = 0;
            $this->combatOdds = "";
            $this->moveCount = 0;
            $this->retreatCountRequired = 0;
            $this->combatResults = NR;
            $this->range = $range;
            $this->nationality = $nationality;
            $this->forceMarch = $forceMarch;
            $this->unitDesig = $unitDesig;
            $this->unitDefStrength = $firstUnitDefstrength;
            $this->secondUnitDefStrength = $secondUnitDefStrength;
        }

        public function getSecId()
        {
            return $this->secondaryId;
        }

        public function getRange()
        {
            return 1;
        }
    }


    class UnitFactory
    {
        public static $id = 0;
        public static $injector;

        public static function build($data = false)
        {

            $sU = new \TMCW\Chawinda1965\ChawindaUnit($data);
            if ($data === false) {
                $sU->id = self::$id++;
            }
            return $sU;
        }

        public static function create($unitName, $unitForceId, $unitHexagon, $unitImage, $firstUnitStrength, $secondUnitStrength, $firstUnitDefstrength, $secondUnitDefStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range = 1, $nationality = "neutral", $forceMarch = true, $class = false, $unitDesig = false)
        {
            $unit = self::build();
            $id = $unit->id;
            $unit->set($id, $unitName, $unitForceId, $unitHexagon, $unitImage, $firstUnitStrength, $secondUnitStrength, $firstUnitDefstrength, $secondUnitDefStrength, $unitMaxMove, $isReduced, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig);
            self::$injector->injectUnit($unit);
            if ($isReduced === false) {
                $secondUnit = self::build();
                $unit->secondaryId = $secondUnit->id;
                $secondUnit->set($id, $unitName, $unitForceId, 'combined-box', $unitImage, $secondUnitStrength, false, $secondUnitDefStrength, false, $unitMaxMove, true, STATUS_COMBINED, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $forceMarch, $class, $unitDesig . "/2");

                self::$injector->injectUnit($secondUnit);
            }
        }

    }
