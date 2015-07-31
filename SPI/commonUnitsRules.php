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
?><span class="big">UNITS</span>

<p>The units are in two colors.</p>
<ol>
    <li>
        <?= $playerOne ?> units are this color.
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiArmor.png" class="counter">
            </div>

            <div class="unit-numbers">6 - 8</div>
        </div>
    </li>
    <li>
        <?= $playerTwo ?> units are this color.
        <div class="unit <?= strtolower($playerTwo) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiMech.png" class="counter">
            </div>
            <div class="unit-numbers">9 - 6</div>
        </div>
    </li>
    <li>
        The symbol above the numbers represents the unit type.
        This is Armor (tanks).
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;">

            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiArmor.png" class="counter">
            </div>

            <div class="unit-numbers">6 - 8</div>
        </div>
    </li>
    <li>
        This is Mechinized Infantry (soldiers in half tracks, with small arms).
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiMech.png" class="counter">
            </div>
            <div class="unit-numbers">4 - 8</div>
        </div>
    </li>
    <li>
        This is Infantry. (soldiers on foot, with small arms).
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiInf.png" class="counter">
            </div>

            <div class="unit-numbers">2 - 8</div>
        </div>
    </li>
    <li>
        The number on the left is the combat strength. The number on the right is the movement allowance
        <div class="unit <?= strtolower($playerTwo) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiMech.png" class="counter">
            </div>

            <div class="unit-numbers">9 - 6</div>
        </div>
        <p class="ruleComment">
            The above unit has a combat strength of 9 and a movenent allowance of 6.</p>
    </li>
    <li>
        If a units numbers are in white, that means this unit is at reduced strength and can receive
        replacements
        during the replacement phase.
        <div class="clear"></div>
        <div class="unit <?= strtolower($playerOne) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiArmor.png" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">3 - 8</span></div>
        </div>
        <div class="unit <?= strtolower($playerTwo) ?>" alt="0"
             src="<?= base_url(); ?>js/short-red-arrow-md.png"
             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;">
            <div class="unitSize">xx</div>
            <div class="counterWrapper">
                <img src="<?= base_url(); ?>js/multiMech.png" class="counter">
            </div>

            <div class="unit-numbers"><span class="reduced">4 - 6</span></div>

        </div>

        <div class="clear">&nbsp;</div>
    </li>
</ol>