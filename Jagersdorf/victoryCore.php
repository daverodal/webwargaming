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
    private $movementCache;

    function __construct($data)
    {
        if($data) {
            $this->movementCache = $data->victory->movementCache;
            $this->victoryPoints = $data->victory->victoryPoints;
        } else {
            $this->victoryPoints = array(0, 0, 0);
            $this->movementCache = new stdClass();
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

    public function playerTurnChange($arg){
        $attackingId = $arg[0];
        $battle = Battle::getBattle();

        /* @var GameRules $gameRules */
        $gameRules = $battle->gameRules;
        $attackingId = $gameRules->attackingForceId;
        $turn = $gameRules->turn;
        $gameRules->flashMessages[] = "@hide crt";


        if($attackingId == BLUE_FORCE){
            $gameRules->flashMessages[] = "Russian Player Turn";
        }
        if($attackingId  == RED_FORCE){
            $gameRules->flashMessages[] = "Prussian Player Turn";
        }


    }
    public function postRecoverUnits($args){
        $b = Battle::getBattle();
        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Russian Movement halved this turn.";
        }

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
        var_dump($cR->attackers);
        $defenderForceId = $force->defendingForceId;
        var_dump($defenderForceId);
        foreach($cR->attackers as $attackId => $combatId){
            $mapHex = $mapData->getHex($force->getUnitHexagon($attackId)->name);
            $neighbors = $mapHex->neighbors;
            foreach($neighbors as $neighbor){
                /* @var MapHex $hex */
                $hex = $mapData->getHex($neighbor);
                if($hex->isOccupied($defenderForceId)){
                    $units = $hex->forces[$defenderForceId];
                    var_dump($units);
                    foreach($units as $unitId=>$unitVal){
                        $requiredVal = true;
                        echo "unit id $unitId ";
                        var_dump($cR->defenders);
                        $combatId = $cR->defenders->$unitId;
                        if($combatId){
                            echo "hii ";
                            echo "combat id $combatId ";
                            $attackers = $cR->combats->$combatId->attackers;
                            if($attackers){
                                if(count((array)$attackers) > 0){
                                    $requiredVal = false;
                                }
                            }

                        }

                        echo "her ";
                        $force->requiredDefenses->$unitId = $requiredVal;
                        echo " aaare ";
                    }
                }
            }
        }
    }
    public function postUnsetAttacker($args){
        echo "Post Unset Attacker";
        $this->calcFromAttackers();
        list($unit) = $args;
        $id = $unit->id;
    }
    public function postUnsetDefender($args){
        echo "Post Unset Defender ";
        $this->calcFromAttackers();

        list($unit) = $args;
    }
    public function postSetAttacker($args){
        echo "Post Attacker ";
        $this->calcFromAttackers();

        list($unit) = $args;
    }
    public function postSetDefender($args){
        echo "Post set Defender ";
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
        $id = $unit->id;

        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->status == STATUS_READY) {
            $this->movementCache->$id = $unit->maxMove;
            $unit->maxMove = 2;
        }
        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_COMBAT_PHASE && isset($this->movementCache->$id)) {
            $unit->maxMove = $this->movementCache->$id;
            unset($this->movementCache->$id);
        }
    }
}