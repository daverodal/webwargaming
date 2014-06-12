<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/19/14
 * Time: 12:45 PM
 */
class indiaVictoryCore extends victoryCore
{
    public function preStartMovingUnit($arg){
        /* @var unit $unit */
        list($unit) = $arg;
        /* @var Dubba1843 $battle */
        $battle = Battle::getBattle();
        $zocBlocksRetreat = $battle->scenario->zocBlocksRetreat;
        if($unit->forceId !== BRITISH_FORCE || $zocBlocksRetreat ){
            $battle->moveRules->zocBlocksRetreat = true;
        }else{
            $battle->moveRules->zocBlocksRetreat = false;
        }
    }
}
