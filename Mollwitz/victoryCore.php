<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
class victoryCore
{
    public $victoryPoints;
    public $movementCache;
    public $gameOver;

    function __construct($data)
    {
        if($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
            $this->gameOver = $data->victory->gameOver;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
            $this->gameOver = false;
        }
    }

    public function save()
    {
        $ret = new stdClass();
        $ret->victoryPoints = $this->victoryPoints;
        $ret->movementCache = $this->movementCache;
        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        if($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength;
        } else {
            $victorId = 1;
            $this->victoryPoints[$victorId] += $unit->strength;
        }
    }

    public function phaseChange()
    {
    }

    protected function checkVictory($attackingId, $battle){
        global $force_name;
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $prussian = $austrianWin = false;
            if($this->victoryPoints[AUSTRIAN_FORCE] > 35){
                $austrianWin = true;
                $reason = "Win on kills";
            }
            if($this->victoryPoints[PRUSSIAN_FORCE] > 35){
                $prussianWin = true;
                $reason = "Win on kills";
            }
            if($turn > 1){
                if($attackingId == PRUSSIAN_FORCE &&  $this->isMollwitz()){
                    $prussianWin = true;
                    $reason = " Occupy Mollwitz";
                }
                if($attackingId == AUSTRIAN_FORCE &&  $this->isNeudorf()){
                    $austrianWin = true;
                    $reason = " Occupy Neudorf";
                }
            }
            if($austrianWin && $prussianWin){
                $this->winner = 0;
                $austrianWin = $prussianWin = false;
                $gameRules->flashMessages[] = "Tie Game";
            }
            if($austrianWin){
                $this->winner = AUSTRIAN_FORCE;
                $gameRules->flashMessages[] = $force_name[AUSTRIAN_FORCE]." $reason";
            }
            if($prussianWin){
                $this->winner = PRUSSIAN_FORCE;
                $gameRules->flashMessages[] = $force_name[PRUSSIAN_FORCE]. " $reason";
            }
            if($austrianWin || $prussianWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
        }
        return false;
    }
    private function isMollwitz(){
        $mollwitz = [602,702];
        $b = Battle::getBattle();
        $force = $b->force;
        $units = $force->units;
        foreach($units as $unit){
            if($unit->forceId == PRUSSIAN_FORCE && in_array($unit->hexagon->name, $mollwitz) ){
                return true;
            }
        }
        return false;
    }
    private function isNeudorf(){
        $neudorf = [911];
        $b = Battle::getBattle();
        $force = $b->force;
        $units = $force->units;
        foreach($units as $unit){
            if($unit->forceId == AUSTRIAN_FORCE && in_array($unit->hexagon->name, $neudorf) ){
                return true;
            }
        }
        return false;
    }

    public function playerTurnChange($arg){
        global $force_name;
        $attackingId = $arg[0];
        $battle = Battle::getBattle();

        /* @var GameRules $gameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $gameRules->flashMessages[] = "@hide crt";

        if($this->checkVictory($attackingId,$battle)){
            return;
        }

            $gameRules->flashMessages[] = $force_name[$attackingId]." Player Turn";


    }
    public function postCombatResults($args){
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $b = Battle::getBattle();
        foreach ($attackers as $attackerId => $val) {
            $unit = $b->force->units[$attackerId];
            if ($unit->class == "artillery" && $unit->status == STATUS_CAN_ADVANCE) {
                $unit->status = STATUS_ATTACKED;
            }
        }
    }
    public function calcFromAttackers(){
        $mapData = MapData::getInstance();

        $battle = Battle::getBattle();
        /* @var CombatRules $cR */
        $cR = $battle->combatRules;
        /* @var Force $force */
        $force = $battle->force;
        $force->clearRequiredCombats();
        $defenderForceId = $force->defendingForceId;
        foreach($cR->attackers as $attackId => $combatId){
            $mapHex = $mapData->getHex($force->getUnitHexagon($attackId)->name);
            $neighbors = $mapHex->neighbors;
            foreach($neighbors as $neighbor){
                /* @var MapHex $hex */
                $hex = $mapData->getHex($neighbor);
                if($hex->isOccupied($defenderForceId)){
                    $units = $hex->forces[$defenderForceId];
                    foreach($units as $unitId=>$unitVal){
                        $requiredVal = true;
                        $combatId = $cR->defenders->$unitId;
                        if($combatId !== null){
                            $attackers = $cR->combats->$combatId->attackers;
                            if($attackers){
                                if(count((array)$attackers) > 0){
                                    $requiredVal = false;
                                }
                            }

                        }

                        $force->requiredDefenses->$unitId = $requiredVal;
                    }
                }
            }
        }
    }
    public function postUnsetAttacker($args){
        $this->calcFromAttackers();
        list($unit) = $args;
        $id = $unit->id;
    }
    public function postUnsetDefender($args){
        $this->calcFromAttackers();

        list($unit) = $args;
    }
    public function postSetAttacker($args){
        $this->calcFromAttackers();

        list($unit) = $args;
    }
    public function postSetDefender($args){
        $this->calcFromAttackers();

    }
    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        if(($b->gameRules->phase == RED_MOVE_PHASE || $b->gameRules->phase == BLUE_MOVE_PHASE) && $unit->forceMarch){
            $unit->forceMarch = false;
        }
        if(($b->gameRules->phase == RED_COMBAT_PHASE || $b->gameRules->phase == BLUE_COMBAT_PHASE) && $unit->forceMarch){
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }
        if($unit->forceId == 1) {
            return;
        }
    }
}