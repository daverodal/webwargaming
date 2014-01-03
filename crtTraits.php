<?php
/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 8/24/13
 * Time: 5:44 PM
 * To change this template use File | Settings | File Templates.
 */

trait divCombatShiftTerrain
{
    function setCombatIndex($defenderId)
    {
        $battle = Battle::getBattle();
        $combatRules = $battle->combatRules;
        $combats = $battle->combatRules->combats->$defenderId;
        /* @var Force $force */
        $force = $battle->force;
        $hexagon = $battle->force->units[$defenderId]->hexagon;
        $hexpart = new Hexpart();
        $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);

        if (count((array)$combats->attackers) == 0) {
            $combats->index = null;
            $combats->attackStrength = null;
            $combats->defenseStrength = null;
            $combats->terrainCombatEffect = null;
            return;
        }

        $defenders = $combats->defenders;
        $attackStrength = 0;

        foreach ($combats->attackers as $id => $v) {
            $attackStrength += $force->units[$id]->strength;
        }
        $defenseStrength = 0;
        foreach ($defenders as $defId => $defender) {
            $defenseStrength += $force->getDefenderStrength($defId);
        }
        $combatIndex = $this->getCombatIndex($attackStrength, $defenseStrength);
        /* Do this before terrain effects */
        if ($combatIndex >= $this->maxCombatIndex) {
            $combatIndex = $this->maxCombatIndex;
        }


        /* @var $combatRules CombatRules */
        $terrainCombatEffect = $combatRules->getDefenderTerrainCombatEffect($defenderId);

        $combatIndex -= $terrainCombatEffect;

        $combats->attackStrength = $attackStrength;
        $combats->defenseStrength = $defenseStrength;
        $combats->terrainCombatEffect = $terrainCombatEffect;
        $combats->index = $combatIndex;
//    $this->force->storeCombatIndex($defenderId, $combatIndex);
    }
}