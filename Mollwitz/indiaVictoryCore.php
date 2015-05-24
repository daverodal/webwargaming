<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
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

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $mult = 1;
        if ($unit->nationality == "British") {
            $mult = 2;
        }
        $this->scoreKills($unit, $mult);
    }
}
