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
    public $crt;
    public $currentDefender = 0;
    public $combats;
    public $attackers;

    function save()
    {
        $data = new StdClass();
        foreach ($this as $k => $v) {
            if ((is_object($v) && $k != "combats" && $k != "attackers") || $k == "crt" ) {
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

function getDefenderTerrainCombatEffect($combatNumber)
{

    $terrainCombatEffect = $this->terrain->getDefenderTerrainCombatEffect($this->force->getCombatHexagon($combatNumber));

    if ($this->allAreAttackingAcrossRiver($combatNumber)) {

        $terrainCombatEffect = $this->terrain->getAllAreAttackingAcrossRiverCombatEffect();
    }
    
	return $terrainCombatEffect;
}


function setCombatIndex($defemderId)
{
    $combats = $this->combats->$defemderId;
    var_dump($combats);
    var_dump($combats->attackers);
    if(count((array)$combats->attackers) == 0){
        $combats->index = null;
    }
    $attackStrength = $this->force->getAttackerStrength($combats->attackers);
    $defenseStrength = $this->force->getDefenderStrength($defemderId);

    $combatIndex = $attackStrength - $defenseStrength;

//    $terrainCombatEffect = $this->getDefenderTerrainCombatEffect($combatNumber);

//    $combatIndex -= $terrainCombatEffect;

    var_dump($combats);
    if ($combatIndex < 1) $combatIndex = 0;

    if ($combatIndex >= $this->crt->maxCombatIndex) {
        $combatIndex = $this->crt->maxCombatIndex;
    }
    $combats->index = $combatIndex;
    var_dump($combats);
//    $this->force->storeCombatIndex($defenderId, $combatIndex);
}

function resolveCombat( $id ) {
 
    // Math->random yields number between 0 and 1
    //  6 * Math->random yields number between 0 and 6
    //  Math->floor gives lower integer, which is now 0,1,2,3,4,5

    $Die = floor($this->crt->dieSideCount * (rand()/getrandmax()));
    $index = $this->force->getUnitCombatIndex($id);
    $combatResults = $this->crt->getCombatResults($Die, $index);
    /*
     * TODO: is force really supposed to be $this->force?????
     */
    $this->force->applyCRTresults($this->force->getUnitCombatNumber($id), $combatResults, $Die);
    echo "Resolved";
}

function resolveFireCombat( $id ) {
}

function allAreAttackingAcrossRiver($combatNumber) {

    $allAttackingAcrossRiver = true;

     $attackerHexagonList = array();
    $attackerHexagonList = $this->force->getAttackerHexagonList($combatNumber);

    $defenderHexagon = $this->force->getCombatHexagon($combatNumber);

    for ($i = 0; $i < count($attackerHexagonList); $i++) {

        $hexsideX = ($defenderHexagon->getX() + $attackerHexagonList[$i]->getX()) / 2;
        $hexsideY = ($defenderHexagon->getY() + $attackerHexagonList[$i]->getY()) / 2;
        
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
}