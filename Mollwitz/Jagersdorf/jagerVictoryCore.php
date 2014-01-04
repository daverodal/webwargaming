<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
require_once "victoryCore.php";



class jagerVictoryCore extends victoryCore
{
    public $victoryPoints;
    private $movementCache;
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

    private function checkVictory($attackingId, $battle){
        global $force_name;
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        if(!$this->gameOver){
            $prussianWin = $russianWin = false;
            if($this->victoryPoints[RUSSIAN_FORCE] > 20){
                $russianWin = true;
            }
            if($this->victoryPoints[PRUSSIAN_FORCE] > 25){
                $prussianWin = true;
            }
            if($russianWin && $prussianWin){
                $this->winner = 0;
                $russianWin = $prussianWin = false;
                $gameRules->flashMessages[] = "Tie Game";
            }
            if($russianWin){
                $this->winner = RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Russian Win on Kills";
            }
            if($prussianWin){
                $this->winner = RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Prussian Win on Kills";
            }
            if($russianWin || $prussianWin){
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
//                $gameRules->mode = GAME_OVER_MODE;
//                $gameRules->phase = GAME_OVER_PHASE;
                return true;
            }
        }
        return false;
    }

    public function postRecoverUnits($args){
        $b = Battle::getBattle();
        if($b->gameRules->turn == 1 && $b->gameRules->phase == RED_MOVE_PHASE) {
            $b->gameRules->flashMessages[] = "Russian Movement alowance 2 this turn.";
        }

    }


    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $id = $unit->id;

        parent::postRecoverUnit($args);
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