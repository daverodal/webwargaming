<?php

require_once "ModernCombatResultsTable.php";

class CombatResultsTable extends ModernCombatResultsTable
{
    use divCombatShiftTerrain;
    public $aggressorId = INDIAN_FORCE;

    function __construct(){
        $this->combatResultsHeader = array("1:1","2:1","3:1","4:1","5:1","6:1");
        $this->combatResultsTable = array(
            array(AE, AE, AR, DR, DR, DR),
            array(AE, AR, DR, DR, DE, DE),
            array(AR, AR, DR, DE, DE, DE),
            array(AR, DR, DR, DR, DE, DE),
            array(DR, DR, EX0, EX0, DE, DE),
            array(EX0, EX0, EX0, EX0, EX0, DE),
        );

        $this->combatOddsTable = array(
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
        );

        $this->combatIndexCount = 11;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
        $this->combatResultCount = 10;

        $this->setCombatOddsTable();
    }
}