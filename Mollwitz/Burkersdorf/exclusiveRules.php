<?php
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
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
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Exclusive Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div id="gameRules">
            <H1>
                <?=$name?>
            </H1>
            <h2 class="exclusive"> EXCLUSIVE RULES
            </h2>
            <h2>Special Rules</h2>
            <h2><?= $playerOne ?> Movement Phase</h2>
            <ul>
                <li>On the first movement phase all of Austrian units have a movement allowance of two. This is for
                    the Austrian's first movement phase only.
                </li>

            </ul>

            <ul>
                <?php if($scenario->angloCavBonus){?>
                <li>
                    <h4>Terrain Effects on Combat</h4>
                    <ul>
                        <li >Anglo Allied Cavalry units are +1 to their combat factor when Attacking into
                            clear, unless they are attacking across a creek or bridge or redoubt.
                        </li>
                    </ul>
                </li>
                <?php } ?>


            </ul>
            <ol class="ExclusiveRules">
                <?php include "victoryConditions.php"?>
            </ol>
            <div id="credits">
                <h2><cite><?=$name?></cite></h2>
                <h4>Design Credits</h4>

                <h4>Game Design:</h4>
                Lance Runolfsson
                <h4>Graphics and Rules:</h4>
                <site>Lance Runolfsson</site>
                <h4>HTML 5 Version:</h4>
                David M. Rodal
            </div>
        </div>
    </div>
</div>