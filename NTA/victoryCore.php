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
        $ret->gameOver = $this->gameOver;
        return $ret;
    }


    public function phaseChange()
    {
    }

    public function playerTurnChange($arg){
        $attackingId = $arg[0];
        $battle = Battle::getBattle();

        /* @var GameRules $gameRules */
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if($gameRules->phase == BLUE_MOVE_PHASE || $gameRules->phase ==  RED_MOVE_PHASE){
            $gameRules->flashMessages[] = "@hide crt";
        }

        if ($turn > $gameRules->maxTurn){
            return;
        }
        if($attackingId == BLUE_FORCE){
            $gameRules->flashMessages[] = "Red Player Turn";
        }
        if($attackingId  == RED_FORCE){
            $gameRules->flashMessages[] = "Blue Player Turn";
        }
    }
    public function postRecoverUnits($args){
        $b = Battle::getBattle();
//        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
//            $b->gameRules->flashMessages[] = "French Movement halved this turn.";
//        }

    }
    public function gameOver(){

        $battle = Battle::getBattle();

        $ownerObj = $battle->mapData->specialHexes;
        foreach($ownerObj as $owner){
            break;
        }
        if($owner == 0){
            $name = "Nobody Wins";
        }
        if($owner == 1){
            $name = "<span class='rebelFace'>Red Wins </span>";
        }
        if($owner == 2){
            $name = "<span class='loyalistFace'>Blue Wins </span>";
        }
        $battle->gameRules->flashMessages[] = $name;
        $this->gameOver = true;
    }
    public function postRecoverUnit($args)
    {
        return $args;
        $unit = $args[0];
        if($unit->forceId == 1) {
            return;
        }
        $b = Battle::getBattle();
        $id = $unit->id;
//
//        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE && $unit->status == STATUS_READY) {
//            $this->movementCache->$id = $unit->maxMove;
//            $unit->maxMove = floor($unit->maxMove / 2);
//        }
//        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_COMBAT_PHASE && isset($this->movementCache->$id)) {
//            $unit->maxMove = $this->movementCache->$id;
//            unset($this->movementCache->$id);
//        }
    }
}