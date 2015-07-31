<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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