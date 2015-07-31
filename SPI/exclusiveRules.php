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

/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 6/19/13
 * Time: 12:21 PM added this
 * To change this template use File | Settings | File Templates.
 */
?>
<style type="text/css">
    #GR ul li{
        list-style:none;
    }
    #GR ol li{
        list-style:inherit;
    }
    #GR li {
        margin: 10px 0;
    }
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Ex Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div id="gameRules">
            <H1>
                <?=$name?>
            </H1>
            <h2 class="exclusive"> EXCLUSIVE RULES
            </h2>
            <?php include "victoryConditions.php" ?>

        </div>
    </div>
</div>