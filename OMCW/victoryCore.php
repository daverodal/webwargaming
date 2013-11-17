<?php
class victoryCore
{
    public function playerTurnChange($arg)
    {
        $attackingId = $arg[0];
        $battle = Battle::getBattle();
        $mapData = $battle->mapData;
        $vp = $this->victoryPoints;
        $specialHexes = $mapData->specialHexes;
        $gameRules = $battle->gameRules;

        if ($gameRules->phase == BLUE_MECH_PHASE || $gameRules->phase == RED_MECH_PHASE) {
            $gameRules->flashMessages[] = "@hide crt";
        }
        if ($attackingId == REBEL_FORCE) {
            $gameRules->flashMessages[] = "Rebel Player Turn";
            $gameRules->replacementsAvail = 1;
        }
        if ($attackingId == LOYALIST_FORCE) {
            $gameRules->flashMessages[] = "Loyalist Player Turn";
            $gameRules->replacementsAvail = 10;
        }

    }

    public function incrementTurn(){
        $battle = Battle::getBattle();

        $theUnits = $battle->force->units;
        foreach($theUnits as $id => $unit){

            if($unit->status == STATUS_CAN_REINFORCE && $unit->reinforceTurn <= $battle->gameRules->turn && $unit->hexagon->parent != "deployBox"){
                $theUnits[$id]->status = STATUS_ELIMINATED;
                $theUnits[$id]->hexagon->parent = "deadpile";
            }
        }
    }
}
