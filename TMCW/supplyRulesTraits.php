<?php

trait modernSupplyRules
{
    protected function restoreAllCombatEffects($force){
        foreach($this->combatCache as $id => $strength){
            $unit = $force->getUnit($id);
            $unit->removeAdjustment('supply');
            unset($this->combatCache->$id);
        }
    }

    protected function unitSupplyEffects($unit, $goal, $bias, $supplyLen){

        $b = Battle::getBattle();
        $id = $unit->id;

        if ($b->gameRules->mode == REPLACING_MODE) {
            if ($unit->status == STATUS_CAN_UPGRADE) {
                $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
                if (!$unit->supplied) {
                    /* TODO: make this not cry  (call a method) */
                    $unit->status = STATUS_STOPPED;
                }
            }
            return;
        }
        if ($unit->status == STATUS_READY || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {
            $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
        } else {
            return;
        }
        if (!$unit->supplied && !isset($this->movementCache->$id)) {
            $this->movementCache->$id = $unit->maxMove;
            $unit->maxMove = floor($unit->maxMove / 2);
        }
        if ($unit->supplied && isset($this->movementCache->$id)) {
            $unit->maxMove = $this->movementCache->$id;
            unset($this->movementCache->$id);
        }

        if ($unit->status == STATUS_READY || $unit->status == STATUS_DEFENDING || $unit->status == STATUS_UNAVAIL_THIS_PHASE) {

            $unit->supplied = $b->moveRules->calcSupply($unit->id, $goal, $bias, $supplyLen);
        } else {
            return;
        }
        if (($this->unsuppliedDefenderHalved || $unit->forceId == $b->gameRules->attackingForceId) && !$unit->supplied && !isset($this->combatCache->$id)) {
            $this->combatCache->$id = true;
            $unit->addAdjustment('supply','half');
//                    $unit->strength = floor($unit->strength / 2);
        }
        if ($unit->supplied && isset($this->combatCache->$id)) {
            $unit->removeAdjustment('supply');
//                    $unit->strength = $this->combatCache->$id;
            unset($this->combatCache->$id);
        }
    }
}