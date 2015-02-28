<?php

require_once "ModernCombatResultsTable.php";

class CombatResultsTable extends ModernCombatResultsTable
{
    use divCombatHalfDoubleTerrain;
    public $aggressorId = INDIAN_FORCE;

    function __construct(){
        $this->combatResultsHeader = array("1:1","2:1","3:1","4:1","5:1","6:1");
        $this->combatResultsTable = array(
            array(AL, AL, AR, DR, DR, DR),
            array(AL, AR, DR, DR, DE, DE),
            array(AR, AR, DR, DE, DE, DE),
            array(AR, DR, DR, DR, DE, DE),
            array(DR, DR, EX0, EX0, DE, DE),
            array(NE, EX0, EX0, EX0, EX0, DE),
        );

        $this->combatResultsTableDetermined = array(
            array(AL,   AL,   AR,   DR,    DE,  DE),
            array(AL,   AR,   DR,   DE,    DE,  DE),
            array(AR,   DR,   DE,   DE,    DE,  DE),
            array(AR,   EX03, EX02, EX02,  DE,  DE),
            array(EX03, EX02, EX0,  EX0,   EX0, DE),
            array(EX02, EX0,  EX0,  EX0,   EX0, EX0),
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

        $this->combatIndexCount = 6;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
        $this->combatResultCount = 5;

        $this->setCombatOddsTable();
    }

    function getCombatResults($Die, $index, $combat)
    {
        if($combat->useDetermined){
            return $this->combatResultsTableDetermined[$Die][$index];
        }
        return $this->combatResultsTable[$Die][$index];
    }
}