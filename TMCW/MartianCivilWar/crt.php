<?php

require_once "ModernCombatResultsTable.php";

class CombatResultsTable extends ModernCombatResultsTable
{
    use divMCWCombatShiftTerrain;
    public $aggressorId = REBEL_FORCE;
}