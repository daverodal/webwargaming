<?php
// combatRules->js

// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version-> 

Class Combat{
    public $attackers;
    public $index;
    public $attackStrength;
    public $defenseStrength;
    public $Die;
    public $combatResult;
    public function __construct(){
        $this->attackers = new StdClass;
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
    public $combats;
    public $combatsToResolve;
    public $attackers;
    public $resolvedCombats;
    public $lastResolvedCombat;

    function save()
    {
        $data = new StdClass();
        foreach ($this as $k => $v) {
            if ((is_object($v) && $k != "lastResolvedCombat" && $k != "resolvedCombats" && $k != "combats" && $k != "attackers" && $k != "combatsToResolve") || $k == "crt" ) {
                continue;
            }
            $data->$k = $v;
        }
        return $data;
    }
    function __construct($Force, $Terrain, $data = null){
    $this->force = $Force;
    $this->terrain = $Terrain;

        if($data){
            foreach($data as $k => $v){
                $this->$k = $v;
            }
        }else{
            $this->combats = new StdClass();
    $this->currentDefender = false;
    }
        $this->crt = new CombatResultsTable();
    }


function setupCombat( $id ) {

    $cd = $this->currentDefender;

    if ($this->force->unitIsEnemy($id) == true)
    {
        echo "Is the enemy";
        // defender is already in combatRules, so make it currently selected
        if ($this->combats->$id)
        {
//            if(count($this->combats->$this->currnetDefender->attackers) == 0){
//                unset($this->currnetDefender[$id]);
//            }
            if($this->currentDefender === false){
                $this->currentDefender = $id;
            }else{
                if($id === $this->currentDefender){
                    $this->currentDefender = false;
                }else{
                $this->currentDefender = $id;
                }
            }
        }
        else
        {
            echo "Not in combat already";
            $this->currentDefender = $id;
echo "here";
            $this->force->setupDefender($id);
            echo "HHEREE";
            $this->combats->$id = new Combat();

            var_dump($this->combats);
        }
    }
    else
    // attacker
    {
        echo "Current Defender ".$this->currentDefender;

        if ($this->currentDefender !== false)
        {
            echo "In ther";
            $los = new Los();
            echo "gotta los";
            $los->setOrigin($this->force->getUnitHexagon($id));
            echo "Set";
            $los->setEndPoint($this->force->getUnitHexagon($this->currentDefender));
            echo "setEndPoint";
            $range = $los->getRange();
            echo "RANGE $range";
            if ($range == 1)
            {
                echo "Inrange";
                var_dump($this->attackers);
                var_dump($this->combats->{$this->currentDefender});
                if ($this->combats->${cd}->attackers->$id === true && $this->attackers->$id === $cd)
                {
                    echo "unisetup";
                    $this->force->undoAttackerSetup($id);
                    unset($this->attackers->${id});
                    unset($this->combats->${cd}->attackers->$id);
                    $this->setCombatIndex($cd);
                }
                else
                {
                    echo "setup";
                    $this->force->setupAttacker($id);
                    echo "Alsosetup";
                    if(isset($this->attackers->$id) && $this->attackers->$id !== $cd){
                        /* move unit to other attack */
                        $oldCd = $this->attackers->${id};
                        echo "stealing from $oldCd to $cd for $id";
                        unset($this->combats->${oldCd}->attackers->$id);
                        $this->setCombatIndex($oldCd);
                    }
                    $this->attackers->${id} = $cd;
                    $this->combats->${cd}->attackers->$id = true;
                    $this->setCombatIndex($cd);
                }
            }
            echo "out";
        }
    }
}

function setupFireCombat( $id ){
}

function getDefenderTerrainCombatEffect($defenderId)
{

    $terrainCombatEffect = $this->terrain->getDefenderTerrainCombatEffect($this->force->getCombatHexagon($defenderId));

    echo "xxx Defender $terrainCombatEffect DEFENDER xxx";
    if ($this->allAreAttackingAcrossRiver($defenderId)) {

        $terrainCombatEffect = $this->terrain->getAllAreAttackingAcrossRiverCombatEffect();
        echo "xxx RRIIVVEERR $terrainCombatEffect RIVER xxx";

    }
    
	return $terrainCombatEffect;
}


function setCombatIndex($defenderId)
{
    $combats = $this->combats->$defenderId;
    var_dump($combats);
    var_dump($combats->attackers);
    if(count((array)$combats->attackers) == 0){
        echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
        $combats->index = null;
        $combats->attackStrength = null;
        $combats->defenseStrength = null;
        $combats->terrainCombatEffect = null;
        return;
    }
    $attackStrength = $this->force->getAttackerStrength($combats->attackers);
    $defenseStrength = $this->force->getDefenderStrength($defenderId);

    $combatIndex = $attackStrength - $defenseStrength;

    $terrainCombatEffect = $this->getDefenderTerrainCombatEffect($defenderId);

    $combatIndex -= $terrainCombatEffect;

    var_dump($combats);
    if ($combatIndex < 1) $combatIndex = 0;

    if ($combatIndex >= $this->crt->maxCombatIndex) {
        $combatIndex = $this->crt->maxCombatIndex;
    }
    $combats->attackStrength = $attackStrength;
    $combats->defenseStrength = $defenseStrength;
    $combats->terrainCombatEffect = $terrainCombatEffect;
    $combats->index = $combatIndex;
    var_dump($combats);
//    $this->force->storeCombatIndex($defenderId, $combatIndex);
}
function cleanUp(){
    unset($this->combats);
    unset($this->resolvedCombats);
    unset($this->lastResolvedCombat);
    unset($this->combatsToResolve);
    $this->currentDefender = false;
    $this->attackers = new StdClass();
}
function resolveCombat( $id ) {
    global $results_name;
    var_dump($id);
    if($this->force->unitIsEnemy($id) && !isset($this->combatsToResolve->${id})){
        return;
    }
    if($this->force->unitIsFriendly($id)){
        if(isset($this->attackers->$id)){
            var_dump($this->attackers->$id);
            $id = $this->attackers->$id;
        }
        else{return;}
    }
    $this->currentDefender = $id;
    // Math->random yields number between 0 and 1
    //  6 * Math->random yields number between 0 and 6
    //  Math->floor gives lower integer, which is now 0,1,2,3,4,5

    $Die = floor($this->crt->dieSideCount * (rand()/getrandmax()));
    echo "The Die is $Die\n";
//    $index = $this->force->getUnitCombatIndex($id);
    $index = $this->combatsToResolve->${id}->index;

//    var_dump($this->crt);
    $combatResults = $this->crt->getCombatResults($Die, $index);
    var_dump($combatResults);echo "really";
    var_dump( $results_name[$combatResults]);
    $this->combatsToResolve->${id}->Die = $Die + 1;
    $this->combatsToResolve->${id}->combatResult = $results_name[$combatResults];
    /*
     * TODO: is force really supposed to be $this->force?????
     */
    $this->force->applyCRTresults($id, $this->combatsToResolve->{$id}->attackers, $combatResults, $Die);
    $this->lastResolvedCombat = $this->combatsToResolve->$id;
        $this->resolvedCombats->$id = $this->combatsToResolve->$id;
        unset($this->combatsToResolve->$id);
    foreach($this->lastResolvedCombat->attackers as $attacker =>$v){
        unset($this->attackers->$attacker);
    }
    echo "Resolved";
}

function resolveFireCombat( $id ) {
}

function allAreAttackingAcrossRiver($defenderId) {

    $allAttackingAcrossRiver = true;

//     $attackerHexagonList = array();
//    $attackerHexagonList = $this->force->getAttackerHexagonList($combatNumber);
    $attackerHexagonList = $this->combats->$defenderId->attackers;
    $defenderHexagon = $this->force->getCombatHexagon($defenderId);
echo "attackehalist";
    var_dump($attackerHexagonList);
    foreach ($attackerHexagonList as $attackerHexagonId => $val) {
        $attackerHexagon = $this->force->getCombatHexagon($attackerHexagonId);

        $hexsideX = ($defenderHexagon->getX() + $attackerHexagon->getX()) / 2;
        $hexsideY = ($defenderHexagon->getY() + $attackerHexagon->getY()) / 2;
        
        $hexside = new Hexpart($hexsideX, $hexsideY);
        
        if ($this->terrain->terrainIs($hexside, "river") == false) {

            $allAttackingAcrossRiver = false;
        }
    }
    
    return $allAttackingAcrossRiver;
}

function getCombatOddsList($combatIndex)
{
   return $this->crt->getCombatOddsList($combatIndex);
}
    function undoDefendersWithoutAttackers()
    {
        $this->currentDefender = false;

        foreach ($this->combats as $defenderId => $combat)
        {
            if(count((array)$combat->attackers) == 0){
                unset($this->combats->$defenderId);
                $this->force->setStatus($defenderId,STATUS_READY);
            }
        }
        $this->combatsToResolve = $this->combats;
        unset($this->combats);
    }

}