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


Class Combat
{
    public $attackers;
    public $defenders;
    public $index;
    public $attackStrength;
    public $defenseStrength;
    public $Die;
    public $combatResult;
    public $thetas;
    public $useAlt = false;
    public $useDetermined = false;
    public $isBombardment = false;
    public $pinCRT = false;
    public $dieShift = 0;

    public function __construct()
    {
        $this->attackers = new stdClass();
        $this->defenders = new stdClass();
        $this->thetas = new stdClass();
    }
}

class CombatRules
{
    // Class references
    /* @var Force */
    public $force;
    /* @var Terrain */
    public $terrain;

    // local publiciables
    /* @var CombatResultsTable */
    public $crt;
    public $currentDefender = false;
    public $defenders;
    public $combats;
    public $combatsToResolve;
    public $attackers;
    public $resolvedCombats;
    public $lastResolvedCombat;

    /*
     * TODO
     * This is how we serialized data in the ancient days...
     */
    function save()
    {
        $data = new StdClass();
        foreach ($this as $k => $v) {
            if ((is_object($v) && $k != "defenders" && $k != "lastResolvedCombat" && $k != "resolvedCombats" && $k != "combats" && $k != "attackers" && $k != "combatsToResolve") || $k == "crt") {
                continue;
            }
            $data->$k = $v;
        }
        return $data;
    }

    function __construct($Force, $Terrain, $data = null)
    {
        $this->force = $Force;
        $this->terrain = $Terrain;

        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        } else {
            /*
             * TODO all this sucks and needs to be initialized
             */
            $this->combats = new stdClass();
            $this->attackers = new stdClass();
            $this->defenders = new stdClass();
            $this->currentDefender = false;
        }
        $this->crt = new CombatResultsTable();
    }

    function pinCombat($pinVal)
    {
        $pinVal--; /* make 1 based 0 based */
        $cd = $this->currentDefender;
        if ($cd !== false) {
            if ($pinVal >= $this->combats->$cd->index || $this->combats->$cd->pinCRT === $pinVal) {
                $this->combats->$cd->pinCRT = false;
            } else {
                $this->combats->$cd->pinCRT = $pinVal;
            }
            $this->crt->setCombatIndex($cd);
        }
    }

    function noMoreAttackers(){

        $cd = $this->currentDefender;
        if ($cd !== false) {
            $attackers = $this->resolvedCombats->$cd->attackers;
            $battle = Battle::getBattle();
            foreach($attackers as $attackId => $val){
                if($battle->force->units[$attackId]->status === STATUS_CAN_EXCHANGE){
                    return false;
                }
            }
        }
        return true;
    }

    function clearCurrentCombat(){

        $cd = $this->currentDefender;
        if($cd === false){
            return false;
        }

        $battle = Battle::getBattle();
        $victory = $battle->victory;

        foreach ($this->combats->{$this->currentDefender}->attackers as $attackerId => $attacker) {
            $this->force->undoAttackerSetup($attackerId);
            unset($this->attackers->$attackerId);
            unset($this->combats->$cd->attackers->$attackerId);
            unset($this->combats->$cd->thetas->$attackerId);
            $victory->postUnsetAttacker($this->units[$attackerId]);
        }
        $this->combats->$cd->useDetermined = false;

        $this->crt->setCombatIndex($cd);

    }

    function setupCombat($id, $shift = false)
    {
        $mapData = MapData::getInstance();
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        $unit = $battle->force->units[$id];

        $cd = $this->currentDefender;

        if ($this->force->unitIsEnemy($id) == true) {
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
                                    $this->force->setStatus($defenderId, STATUS_READY);
                                    unset($this->defenders->$defenderId);
                                    $victory->postUnsetDefender($this->units[$defenderId]);
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
                        if ($range > $this->force->getUnitRange($id)) {
                            $good = false;
                            break;
                        }
                        if ($range > 1) {
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
                            if ($this->terrain->terrainIs($target, "elevation1")) {
                                $targetElevated = true;
                            }
                            $hasElevated1 = $hasElevated2 = false;
                            foreach ($hexParts as $hexPart) {
                                if ($this->terrain->terrainIs($hexPart, "blocksRanged") && (!$srcElevated2 || !$targetElevated2)) {
                                    $good = false;
                                    break;
                                }
                                if ($this->terrain->terrainIs($hexPart, "elevation2")) {
                                    $hasElevated2 = true;
                                    continue;
                                }

                                if ($this->terrain->terrainIs($hexPart, "elevation1")) {
                                    $hasElevated1 = true;
                                    break;
                                }
                            }
                            /* don't do elevation check if non elevation (1) set. This deals with case of coming up
                             * back side of not circular hill
                             */
                            if($hasElevated1 || $targetElevated || $srcElevated){

                                /*
                                 * Ugly if statement. If elevation1 both src and target MUST be elevation1 OR either src or target can be elevation2.
                                 * otherwise, blocked.
                                 */
                                if($hasElevated1 && !(($srcElevated && $targetElevated) || ($targetElevated2 || $srcElevated2))){
                                    $good = false;
                                }
                                if ($hasElevated2 && (!$srcElevated2 || !$targetElevated2)) {
                                    $good = false;
                                }


                            }

                            if ($good === false) {
                                break;
                            }
                            $mapHex = $mapData->getHex($this->force->getUnitHexagon($id)->name);
                            if ($this->force->mapHexIsZOC($mapHex)) {
                                $good = false;
                                break;
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
                            if ($range <= $this->force->getUnitRange($id)) {
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

    function useDetermined(){
        $cd = $this->currentDefender;
        if($cd !== false){
            if($this->combats->$cd->useAlt === false && $this->combats->$cd->isBombardment === false){
                $this->combats->$cd->useDetermined = $this->combats->$cd->useDetermined ? false : true;
            }
        }
    }
    function cleanUpAttacklessDefenders()
    {
        $battle = Battle::getBattle();
        $victory = $battle->victory;
        if (!$this->combats) {
            $this->combats = new stdClass();
        }
        foreach ($this->combats as $id => $combat) {
            if ($id === $this->currentDefender) {
                continue;
            }
            if (count((array)$combat->attackers) == 0) {
                foreach ($combat->defenders as $defenderId => $defender) {
                    $this->force->setStatus($defenderId, STATUS_READY);
                    unset($this->defenders->$defenderId);
                    $victory->postUnsetDefender($this->force->units[$defenderId]);
                }
                unset($this->combats->$id);
            }
        }
    }

    function setupFireCombat($id)
    {
    }

    function getDefenderTerrainCombatEffect($defenderId)
    {
        $defenders = $this->combats->$defenderId->defenders;
        $bestDefenderTerrainEffect = 0;
        foreach ($defenders as $defId => $def) {
            $unit = $this->force->getUnit($defId);
            $terrainCombatEffect = $this->terrain->getDefenderTerrainCombatEffect($unit->getUnitHexagon());
            if ($this->allAreAttackingAcrossRiver($defId)) {
                $riverCombatEffect = $this->terrain->getAllAreAttackingAcrossRiverCombatEffect();
                if ($riverCombatEffect > $terrainCombatEffect) {
                    $terrainCombatEffect = $riverCombatEffect;
                }
            }
            if ($terrainCombatEffect > $bestDefenderTerrainEffect) {
                $bestDefenderTerrainEffect = $terrainCombatEffect;
            }
        }
        return $bestDefenderTerrainEffect;
    }

    function cleanUp()
    {
        unset($this->combats);
        unset($this->resolvedCombats);
        unset($this->lastResolvedCombat);
        unset($this->combatsToResolve);
        $this->currentDefender = false;
        $this->attackers = new stdClass();
        $this->defenders = new stdClass();
    }

    function resolveCombat($id)
    {
        $battle = Battle::getBattle();
        global $results_name;
        if ($this->force->unitIsEnemy($id) && !isset($this->combatsToResolve->$id)) {
            if (isset($this->defenders->$id)) {
                $id = $this->defenders->$id;
            } else {
                return false;
            }
        }
        if ($this->force->unitIsFriendly($id)) {
            if (isset($this->attackers->$id)) {
                $id = $this->attackers->$id;
            } else {
                return false;
            }
        }
        if (!isset($this->combatsToResolve->$id)) {
            return false;
        }
        $this->currentDefender = $id;
        // Math->random yields number between 0 and 1
        //  6 * Math->random yields number between 0 and 6
        //  Math->floor gives lower integer, which is now 0,1,2,3,4,5

        $Die = floor($this->crt->dieSideCount * (rand() / getrandmax()));
//        $Die = 5;
        $index = $this->combatsToResolve->$id->index;
        if ($this->combatsToResolve->$id->pinCRT !== false) {
            if ($index > ($this->combatsToResolve->$id->pinCRT)) {
                $index = $this->combatsToResolve->$id->pinCRT;
            }
        }
        $combatResults = $this->crt->getCombatResults($Die, $index, $this->combatsToResolve->$id);
        $this->combatsToResolve->$id->Die = $Die + 1;
        $this->combatsToResolve->$id->combatResult = $results_name[$combatResults];
        $this->force->clearRetreatHexagonList();
        $this->force->clearExchangeAmount();

        /* determine which units are defending */
        $defenders = $this->combatsToResolve->{$id}->defenders;
        $defendingHexes = [];
        /* others is units not in combat, but in hex, combat results apply to them too */
        $others = [];

        foreach($defenders as $defenderId => $defender){
            $unit = $this->force->units[$defenderId];
            $hex = $unit->hexagon;
            if(!isset($defendingHexes[$hex->name])){
                $mapHex = $battle->mapData->getHex($hex->getName());
                $hexDefenders = $mapHex->getForces($unit->forceId);
                foreach($hexDefenders as $hexDefender){
                    if(isset($defenders->$hexDefender)){
                        continue;
                    }
                    $others[] = $hexDefender;
                }
            }
        }
        /* apply combat results to defenders */
        foreach ($defenders as $defenderId => $defender) {
            $this->force->applyCRTresults($defenderId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die);
        }
        /* apply combat results to other units in defending hexes */
        foreach ($others as $otherId) {
            $this->force->applyCRTresults($otherId, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die);
        }
        $this->lastResolvedCombat = $this->combatsToResolve->$id;
        if (!$this->resolvedCombats) {
            $this->resolvedCombats = new stdClass();
        }
        $this->resolvedCombats->$id = $this->combatsToResolve->$id;
        unset($this->combatsToResolve->$id);
        foreach ($this->lastResolvedCombat->attackers as $attacker => $v) {
            unset($this->attackers->$attacker);
        }
        return $Die;
    }

    function resolveFireCombat($id)
    {
    }

    function allAreAttackingAcrossRiver($defenderId)
    {

        $defenderId = $this->defenders->$defenderId;
        $allAttackingAcrossRiver = true;
        $attackerHexagonList = $this->combats->$defenderId->attackers;
        /* @var Hexagon $defenderHexagon */
        $unit = $this->force->getUnit($defenderId);
        $defenderHexagon = $unit->getUnitHexagon();
        foreach ($attackerHexagonList as $attackerHexagonId => $val) {
            /* @var Hexagon $attackerHexagon */
            $attackerUnit = $this->force->getUnit($attackerHexagonId);
            $attackerHexagon = $attackerUnit->getUnitHexagon();

            $hexsideX = ($defenderHexagon->getX() + $attackerHexagon->getX()) / 2;
            $hexsideY = ($defenderHexagon->getY() + $attackerHexagon->getY()) / 2;

            $hexside = new Hexpart($hexsideX, $hexsideY);

            if ($this->terrain->terrainIs($hexside, "river") === false && $this->terrain->terrainIs($hexside, "wadi") === false) {

                $allAttackingAcrossRiver = false;
            }
        }

        return $allAttackingAcrossRiver;
    }


    function allAreAttackingThisAcrossRiver($defenderId)
    {

        $combatId = $this->defenders->$defenderId;
        $allAttackingAcrossRiver = true;
        $attackerHexagonList = $this->combats->$combatId->attackers;
        /* @var Hexagon $defenderHexagon */
        $unit = $this->force->getUnit($defenderId);
        $defenderHexagon = $unit->getUnitHexagon();
        foreach ($attackerHexagonList as $attackerHexagonId => $val) {
            /* @var Hexagon $attackerHexagon */
            $attackerUnit = $this->force->getUnit($attackerHexagonId);
            $attackerHexagon = $attackerUnit->getUnitHexagon();

            $hexsideX = ($defenderHexagon->getX() + $attackerHexagon->getX()) / 2;
            $hexsideY = ($defenderHexagon->getY() + $attackerHexagon->getY()) / 2;

            $hexside = new Hexpart($hexsideX, $hexsideY);

            if ($this->terrain->terrainIs($hexside, "river") === false && $this->terrain->terrainIs($hexside, "wadi") === false) {

                $allAttackingAcrossRiver = false;
            }
        }

        return $allAttackingAcrossRiver;
    }

    function thisAttackAcrossRiver($defenderId, $attackerId)
    {


//     $attackerHexagonList = array();
//    $attackerHexagonList = $this->force->getAttackerHexagonList($combatNumber);
        $attackerUnit = $this->force->getUnit($attackerId);
        $attackerHexagon = $attackerUnit->getUnitHexagon();
        /* @var Hexagon $defenderHexagon */
        $defenderUnit = $this->force->getUnit($defenderId);
        $defenderHexagon = $defenderUnit->getUnitHexagon();


        $hexsideX = ($defenderHexagon->getX() + $attackerHexagon->getX()) / 2;
        $hexsideY = ($defenderHexagon->getY() + $attackerHexagon->getY()) / 2;

        $hexside = new Hexpart($hexsideX, $hexsideY);

        if ($this->terrain->terrainIs($hexside, "river") === true) {
            return true;
        }
        if ($this->terrain->terrainIs($hexside, "wadi") === true) {
            return true;
        }
        return false;
    }

    function thisAttackAcrossType($defenderId, $attackerId, $type){
        $los = new Los();

        $los->setOrigin($this->force->getUnitHexagon($defenderId));
        $los->setEndPoint($this->force->getUnitHexagon($attackerId));

        $hexParts = $los->getlosList();
        /*
         *  defender is located in hexParts[0],
         * x first hexside adjacent to defender is in $hexParts[1]
         */
        return ($this->terrain->terrainIs($hexParts[1], $type));

    }

    function thisAttackAcrossTwoType($defenderId, $attackerId, $type){
        $los = new Los();

        $los->setOrigin($this->force->getUnitHexagon($defenderId));
        $los->setEndPoint($this->force->getUnitHexagon($attackerId));

        $hexParts = $los->getlosList();
        /*
         *  defender is located in hexParts[0],
         * x first hexside adjacent to defender is in $hexParts[1] need to check second hexside for elevations checks from behind.
         */
        return ($this->terrain->terrainIs($hexParts[1], $type) || $this->terrain->terrainIs($hexParts[2], $type) );

    }

    function tthisAttackAcrossType($defenderId, $attackerId, $type)
    {

        $this->isAttackAcrossType($defenderId, $attackerId, $type);

//     $attackerHexagonList = array();
//    $attackerHexagonList = $this->force->getAttackerHexagonList($combatNumber);
        $attackerHexagon = $this->force->getCombatHexagon($attackerId);
        /* @var Hexagon $defenderHexagon */
        $defenderHexagon = $this->force->getCombatHexagon($defenderId);


        $hexsideX = ($defenderHexagon->getX() + $attackerHexagon->getX()) / 2;
        $hexsideY = ($defenderHexagon->getY() + $attackerHexagon->getY()) / 2;

        $hexside = new Hexpart($hexsideX, $hexsideY);

        if ($this->terrain->terrainIs($hexside, $type) === false) {
            return false;
        }
        return true;
    }


    function getCombatOddsList($combatIndex)
    {
        return $this->crt->getCombatOddsList($combatIndex);
    }

    function undoDefendersWithoutAttackers()
    {
        $this->currentDefender = false;
        if ($this->combats) {
            $battle = Battle::getBattle();
            $victory = $battle->victory;
            foreach ($this->combats as $defenderId => $combat) {
                if (count((array)$combat->attackers) == 0) {
                    foreach ($combat->defenders as $defId => $def) {
                        $this->force->setStatus($defId, STATUS_READY);
                        $victory->postUnsetDefender($this->force->units[$defId]);
                    }
                    unset($this->combats->$defenderId);
                    continue;
                }
                if ($combat->index < 0) {
                    if ($combat->attackers) {
                        foreach ($combat->attackers as $attackerId => $attacker) {
                            unset($this->attackers->$attackerId);
                            $this->force->setStatus($attackerId, STATUS_READY);
                            $victory->postUnsetAttacker($this->force->units[$attackerId]);
                        }
                    }
                    foreach ($combat->defenders as $defId => $def) {
                        $this->force->setStatus($defId, STATUS_READY);
                        $victory->postUnsetDefender($this->force->units[$defId]);
                    }
                    unset($this->combats->$defenderId);
                    continue;
                }
            }
        }
    }

    function combatResolutionMode()
    {
        $this->combatsToResolve = $this->combats;
        unset($this->combats);
    }

}
