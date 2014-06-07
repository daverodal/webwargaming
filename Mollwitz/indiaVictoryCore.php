<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/19/14
 * Time: 12:45 PM
 */
class indiaVictoryCore extends victoryCore
{
    public function playerTurnChange($arg){

        /* @var Dubba1843 $battle */
        $battle = Battle::getBattle();
        $zocBlocksRetreat = $battle->scenario->zocBlocksRetreat;
        list($nextForceId) = $arg;
        if($nextForceId == BRITISH_FORCE ||  $zocBlocksRetreat ){
            $battle->moveRules->zocBlocksRetreat = true;
        }else{
            $battle->moveRules->zocBlocksRetreat = false;
        }
        return parent::playerTurnChange($arg);
    }
}
