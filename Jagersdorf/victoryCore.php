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

        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;

        if($this->phase == BLUE_MOVE_PHASE || $this->phase ==  RED_MOVE_PHASE){
            $gameRules->flashMessages[] = "@hide crt";
        }
        if($attackingId == BLUE_FORCE){
            $gameRules->flashMessages[] = "Prussian Player Turn";
            $gameRules->replacementsAvail = 1;
        }
        if($attackingId  == RED_FORCE){
            $gameRules->flashMessages[] = "Russian Player Turn";
            $gameRules->replacementsAvail = 10;
        }

        if($this->phase == BLUE_MOVE_PHASE || $this->phase ==  RED_MOVE_PHASE){
            $this->flashMessages[] = "@hide crt";
            if($this->force->reinforceTurns->$turn->$attackingId){
                $this->flashMessages[] = "You have reinforcements.";
                $this->flashMessages[] = "@show OBC";

            }
        }

    }
    public function postRecoverUnits($args){
        $b = Battle::getBattle();
        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Russian Movement halved this turn.";
        }

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