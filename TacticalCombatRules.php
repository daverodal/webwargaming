<?php
// combatRules->js

// Copyright (c) 2009-2011 Mark Butler
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

class TacticalCombatRules extends CombatRules
{

    public function sighted($hexName){

        $battle = Battle::getBattle();
        $symbol = new stdClass();
        $symbol->type = 'Spotted';
        $symbol->image = 'spotted.svg';
        $symbol->class = 'row-hex';
        $symbols = new stdClass();
        foreach([$hexName] as $id){
            $symbols->$id = $symbol;
        }
        $battle->mapData->setMapSymbols($symbols, "spotted");
    }

    protected function isSighted($hexName){

        $battle = Battle::getBattle();
        return $battle->mapData->getMapSymbols($hexName) !== false;
    }

    function setupCombat($id, $shift = false)
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $unit = $battle->force->units[$id];

        $cd = $this->currentDefender;

        if ($this->force->unitIsEnemy($id) == true) {

            $isHidden = false;
            $hexagon = $battle->force->units[$id]->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            $isHidden |= $battle->terrain->terrainIs($hexpart, 'town');
            $isHidden |= $battle->terrain->terrainIs($hexpart, 'forest');
            if($isHidden && !$battle->force->unitIsAdjacent($id) && !$this->isSighted($hexagon->name)){
                return false;
            }

//            $this->sighted($hexagon->name);


            // defender is already in combatRules, so make it currently selected
//            if(isset($this->defenders->$id)){
//                $id = $this->defenders->$id;
//            }
            $combats = $this->combats->$id;
            $combatId = $id;
            if (isset($this->defenders->$id)) {
                $combatId = $this->defenders->$id;
//                $cd = $this->defenders->$id;
                $combats = $this->combats->$combatId;
            }
            if ($combats) {
//            if(count($this->combats->$this->currnetDefender->attackers) == 0){
//                unset($this->currnetDefender[$id]);
//            }
                if ($this->currentDefender === false) {
                    $this->currentDefender = $this->defenders->$id;
                } else {
                    if ($shift) {
                        if (isset($this->defenders->$id)) {
                            if ($combatId === $this->currentDefender) {
                                foreach ($combats->attackers as $attackerId => $attacker) {
                                    $this->force->undoAttackerSetup($attackerId);
                                    unset($this->attackers->$attackerId);
                                    $victory->postUnsetAttacker($this->units[$attackerId]);
                                }
                                foreach ($combats->defenders as $defenderId => $defender) {
                                    $unit = $this->force->getUnit($defenderId);
                                    $unit->setStatus( STATUS_READY);
                                    unset($this->defenders->$defenderId);
                                    $victory->postUnsetDefender($unit);
                                }
                                unset($this->combats->{$combatId});
                                $this->currentDefender = false;
                            } else {
                                $this->currentDefender = $combatId;
                            }
                        }
                    } else {
                        if ($combatId === $this->currentDefender) {
                            $this->currentDefender = false;
                        } else {
                            $this->currentDefender = $combatId;
                        }
                    }
                }
            } else {
                if ($shift) {
                    if ($this->currentDefender !== false) {
                        foreach ($this->combats->{$this->currentDefender}->attackers as $attackerId => $attacker) {
                            $this->force->undoAttackerSetup($attackerId);
                            unset($this->attackers->$attackerId);
                            unset($this->combats->$cd->attackers->$attackerId);
                            unset($this->combats->$cd->thetas->$attackerId);
                            $victory->postUnsetAttacker($this->units[$attackerId]);
                        }
                        $this->defenders->$id = $this->currentDefender;
                    } else {
                        $this->currentDefender = $id;
                        $this->defenders->$id = $id;
                    }
                } else {
                    $mapHex = $battle->mapData->getHex($unit->hexagon->getName());
                    $forces = $mapHex->getForces($unit->forceId);

                    $this->currentDefender = $id;
                    foreach($forces as $force){
                        if($this->force->units[$force]->class !== "air" &&  ($battle->gameRules->phase == RED_AIR_COMBAT_PHASE || $battle->gameRules->phase == BLUE_AIR_COMBAT_PHASE )) {
                            continue;
                        }
                        $this->defenders->$force = $id;
                        if($force != $id){
                            $cd = $this->currentDefender;
                            $this->force->setupDefender($force);
                            if (!$this->combats) {
                                $this->combats = new  stdClass();
                            }
                            if (!$this->combats->$cd) {
                                $this->combats->$cd = new Combat();
                            }
                            $this->combats->$cd->defenders->$force = $id;
                        }
                    }
                }
                $cd = $this->currentDefender;
//                $this->defenders->{$this->currentDefender} = $id;
                $this->force->setupDefender($id);
                if (!$this->combats) {
                    $this->combats = new  stdClass();
                }
                if (!$this->combats->$cd) {
                    $this->combats->$cd = new Combat();
                }
                $this->combats->$cd->defenders->$id = $id;
//                $victory->postSetDefender($this->force->units[$id]);
            }
        } else // attacker
        {

            if ($this->currentDefender !== false && $this->force->units[$id]->status != STATUS_UNAVAIL_THIS_PHASE) {
                if ($this->combats->$cd->attackers->$id !== false && $this->attackers->$id === $cd) {
                    $this->force->undoAttackerSetup($id);
                    unset($this->attackers->$id);
                    unset($this->combats->$cd->attackers->$id);
                    unset($this->combats->$cd->thetas->$id);
                    $victory->postUnsetAttacker($this->units[$id]);
                    $this->crt->setCombatIndex($cd);
                } else {
                    $good = true;
                    foreach ($this->combats->{$this->currentDefender}->defenders as $defenderId => $defender) {
                        $los = new Los();

                        $los->setOrigin($this->force->getUnitHexagon($id));
                        $los->setEndPoint($this->force->getUnitHexagon($defenderId));
                        $range = $los->getRange();
                        if ($range > $unit->getRange($id)) {
                            $good = false;
                            break;
                        }
                        if ($range > 1) {
                            $good = $this->checkBlocked($los,$id);
                            if($good) {
                                $isHidden = false;

                                $hexagon = $this->force->getUnitHexagon($defenderId);
                                $hexpart = new Hexpart();
                                $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
                                $isHidden |= $battle->terrain->terrainIs($hexpart, 'town');
                                $isHidden |= $battle->terrain->terrainIs($hexpart, 'forest');
                                if ($isHidden && !$this->isSighted($hexagon->name)) {
                                    /* confirm observer is in los too */
                                    $bad = true;
                                    $adjacentUnits = $this->force->getAdjacentUnits($defenderId);
                                    $observerLos = new Los();
                                    $observerLos->setOrigin($this->force->getUnitHexagon($id));
                                    foreach ($adjacentUnits as $adjacentUnitId => $v) {
                                        $observerLos->setEndPoint($this->force->getUnitHexagon($adjacentUnitId));
                                        if ($this->checkBlocked($observerLos,$adjacentUnitId)){
                                            $bad = false;
                                            break;
                                        }
                                    }
                                    if($bad){
                                        $good = false;
                                    }
                                }
                            }
                        }



                        if ($range == 1) {
                            if ($this->terrain->terrainIsHexSide($this->force->getUnitHexagon($id)->name, $this->force->getUnitHexagon($defenderId)->name, "blocked" )
                                && !($unit->class === "artillery" || $unit->class === "horseartillery") ) {
                                $good = false;
                            }
                        }

                    }
                    if ($good) {
                        foreach ($this->combats->{$this->currentDefender}->defenders as $defenderId => $defender) {
                            $los = new Los();

                            $los->setOrigin($this->force->getUnitHexagon($id));
                            $los->setEndPoint($this->force->getUnitHexagon($defenderId));
                            $range = $los->getRange();
                            $bearing = $los->getBearing();
                            if ($range <= $unit->getRange($id)) {
                                $this->force->setupAttacker($id, $range);
                                if (isset($this->attackers->$id) && $this->attackers->$id !== $cd) {
                                    /* move unit to other attack */
                                    $oldCd = $this->attackers->$id;
                                    unset($this->combats->$oldCd->attackers->$id);
                                    unset($this->combats->$oldCd->thetas->$id);
                                    $this->crt->setCombatIndex($oldCd);
                                    $this->checkBombardment($oldCd);

                                }
                                $this->attackers->$id = $cd;
                                $this->combats->$cd->attackers->$id = $bearing;
                                $this->combats->$cd->defenders->$defenderId = $bearing;
                                if (!is_object($this->combats->$cd->thetas->$id)) {
                                    $this->combats->$cd->thetas->$id = new stdClass();
                                }
                                $this->combats->$cd->thetas->$id->$defenderId = $bearing;
                                $victory->postSetDefender($this->force->units[$defenderId]);
                                $this->crt->setCombatIndex($cd);
                            }
                        }
                        $victory->postSetAttacker($this->force->units[$id]);
                    }
                }
                $this->checkBombardment();
            }
        }
        $this->cleanUpAttacklessDefenders();
    }

    function checkBlocked($los, $id){
        $mapData = MapData::getInstance();

        $good = true;
        $hexParts = $los->getlosList();
        // remove first and last hexPart

        $src = array_shift($hexParts);
        $target = array_pop($hexParts);
        $srcElevated = $targetElevated = $srcElevated2 = $targetElevated2 = false;

        if ($this->terrain->terrainIs($src, "elevation2")) {
            $srcElevated2 = true;
        }
        if ($this->terrain->terrainIs($target, "elevation2")) {
            $targetElevated2 = true;
        }
        if ($this->terrain->terrainIs($src, "elevation1")) {
            $srcElevated = true;
        }

        $localLos = new Los();
        $localLos->originX = $los->originX;
        $localLos->originY = $los->originY;
        $range = $los->getRange();
        $hasElevated1 = $hasElevated2 = false;
        foreach ($hexParts as $hexPart) {
            if ($this->terrain->terrainIs($hexPart, "blocksRanged") && ($srcElevated2 || $targetElevated2)) {
                $localLos->setEndPoint($hexPart);
                $localRange = $localLos->getRange();
                if($targetElevated2 && $localRange > $range/2){
                    continue;
                }
                if($srcElevated2 && $localRange < $range/2){
                    continue;
                }
            }
            if ($this->terrain->terrainIs($hexPart, "blocksRanged") && (!$srcElevated2 || !$targetElevated2)) {
                return false;

            }
            if ($this->terrain->terrainIs($hexPart, "elevation2")) {
                $hasElevated2 = true;
                continue;
            }

        }
        /* don't do elevation check if non elevation (1) set. This deals with case of coming up
         * back side of not circular hill
         */
        if($hasElevated2 || $targetElevated || $srcElevated){

            if ($hasElevated2 && (!$srcElevated2 || !$targetElevated2)) {
                $good = false;
            }


        }

        if ($good === false) {
            return false;
        }
        return $good;
    }

    function checkBombardment($cd = false)
    {
        if($cd === false){
            $cd = $this->currentDefender;
        }
        $attackers = $this->combats->{$cd}->attackers;
        $defenders = $this->combats->{$cd}->defenders;
        foreach ($defenders as $defenderId => $defender) {
            foreach ($this->combats->{$cd}->attackers as $attackerId => $attacker) {
                $los = new Los();

                $los->setOrigin($this->force->getUnitHexagon($attackerId));
                $los->setEndPoint($this->force->getUnitHexagon($defenderId));
                $range = $los->getRange();
                if ($range == 1) {
                    $this->combats->{$cd}->isBombardment = false;
                    return;
                }
            }
        }
        $this->combats->{$cd}->isBombardment = true;
        $this->combats->{$cd}->useDetermined = false;
    }



}
