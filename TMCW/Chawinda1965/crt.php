<?php

require_once "ModernCombatResultsTable.php";

class CombatResultsTable extends ModernCombatResultsTable
{
    use divCombatShiftTerrain;
    public $aggressorId = INDIAN_FORCE;

    function __construct(){
        $this->combatResultsHeader = array("1:1","2:1","3:1","4:1","5:1","6:1", "7:1", "8:1", "9:1", "10:1", "11:1");
        $this->combatResultsTable = array(
            array(AE, AE, AE, AR, AR, NE, NE, EX, EX, EX, EX),
            array(AE, AE, AL, AR, NE, DR, EX, EX, DRL, DRL, DRL),
            array(AE, AL, AR, NE, DR, EX, EX, EX, DRL, DE, DE),
            array(AE, AL, NE, NE, DR, EX, EX, DRL, DE, DE, DE),
            array(AL, AR, NE, DR, EX, DRL, DRL, DRL, DE, DE, DE),
            array(AL, AR, DR, EX, EX, DRL, DE, DE, DE, DE, DE),
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