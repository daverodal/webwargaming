<?php
namespace SPI\FinalChapter;
use \Hexagon;
use \stdClass;
use \Battle;
use \BaseUnit;
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 12/21/15
 * Time: 2:09 PM
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



    class SimpleUnit extends BaseUnit implements \JsonSerializable
    {

        public $origStrength;
        public $unitDefStrength;


        public function getUnmodifiedStrength()
        {
            return $this->origStrength;
        }


        public function getUnmodifiedDefStrength()
        {
            return $this->unitDefStrength;
        }

        public function __get($name)
        {
            if ($name !== "strength" && $name !== "defStrength" && $name !== "attStrength") {
                return false;
            }
            $strength = $this->origStrength;

            if ($name === "defStrength") {
                $strength = $this->unitDefStrength;
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


        function set($unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $unitDefStrength, $unitMaxMove, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $range, $nationality = "neutral", $forceMarch, $class, $unitDesig)
        {
            $this->dirty = true;
            $this->name = $unitName;
            $this->forceId = $unitForceId;
            $this->class = $class;
            $this->hexagon = new Hexagon($unitHexagon);
            $this->unitDefStrength = $unitDefStrength;

            /* blah! this can get called from the constructor of Battle. so we can't get ourselves while creating ourselves */
//        $battle = Battle::getBattle();
//        $mapData = $battle->mapData;

            $battle = Battle::getBattle();
            $mapData = $battle->mapData;
//        $mapData = MapData::getInstance();

            $mapHex = $mapData->getHex($this->hexagon->getName());
            if ($mapHex) {
                $mapHex->setUnit($this->forceId, $this);
            }
            $this->image = $unitImage;


//        $this->strength = $isReduced ? $unitMinStrength : $unitMaxStrength;
            $this->maxMove = $unitMaxMove;
            $this->moveAmountUnused = $unitMaxMove;
            $this->origStrength = $unitStrength;
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

        }

        function damageUnit($kill = false)
        {
            $battle = Battle::getBattle();

            $this->status = STATUS_ELIMINATING;
            $this->exchangeAmount = $this->getUnmodifiedStrength();
            $this->defExchangeAmount = $this->getUnmodifiedDefStrength();
            return true;
        }

        function __construct($data = null)
        {
            if ($data) {
                foreach ($data as $k => $v) {
                    if ($k == "hexagon") {
                        $this->hexagon = new Hexagon($v);
//                    $this->hexagon->parent = $data->parent;
                        continue;
                    }
                    $this->$k = $v;
                }
                $this->dirty = false;
            } else {
                $this->adjustments = new stdClass();
            }
        }

        public function getRange()
        {
            return 1;
        }

        public function fetchData()
        {
            $mapUnit = new stdClass();
            $mapUnit->parent = $this->hexagon->parent;
            $mapUnit->moveAmountUsed = $this->moveAmountUsed;
            $mapUnit->maxMove = $this->maxMove;
            $mapUnit->strength = $this->strength;
            $mapUnit->supplied = $this->supplied;
            $mapUnit->defStrength = $this->unitDefStrength;
            $mapUnit->status = $this->status;
            $mapUnit->forceId = $this->forceId;
            return $mapUnit;
        }
    }
