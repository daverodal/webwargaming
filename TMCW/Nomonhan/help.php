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
?><style type="text/css">
    #header{
        /*display:none;*/
    }
    #gameRules{
        font-family:sans-serif;
    }
    #gameRules table, #gameRules th, #gameRules td{
        border:1px solid black;
    }
    #gameRules h1{
        color:#338833;
        font-size:60px;

    }
    #GR #credits h2{
        color:#338833;
    }
    #GR li{
        margin: 3px 0;
    }
    #GR h4{
        margin-bottom:5px;
    }
    #GR #credits h4{
        margin-bottom:0px;
    }
    #gameRules h4:hover{
        text-decoration: none;
    }
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Rules</h4>
    <div id="GR" style="display:none">
        <div class="close">X</div>
<div id="gameRules">
    <h1>The Martian Civil War</h1>
    <h2>Rules of Play</h2>
    <h2>Design Context</h2>
    <p>The Martian Civil War is not as much a game but and HTML 5 game framework for turn base wargames,
    such as those in the 70's that were done on paper maps and cardboard "counters" or units. In making this framework I strived
    to make the framework support as many different types of games, and fully support their idiosyncrasies.</p>
    <p> So towards that end, I created
    this game, "The Martian Civil War" as a first game and proof of concept. It strives to be like many of the WW II games I played
    with a dual movement phase and armor units.</p>
    <p>I chose the Martian Civil War to go out of my way to not infringe on any Copyrights.</p>
    <p>The end goal of this game is to support as many different type of hex based games as possible by as many game designers as possible</p>
    <H2>The Units</H2>
    <h4>Unit Colors</h4>
    <p>The units are in two colors.</p>
    Red is for Rebel. <div class="unit rebel" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"><section></section>
        <img src="<?=base_url();?>js/multiArmor.png" class="counter">
        <div>6 - 8</div>
    </div>
    <p></p>
    Blue if for Loyalist.
    <div class="unit loyalist" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;"><section></section>
        <img class="arrow" src="<?=base_url();?>js/short-red-arrow-md.png" style="opacity: 0;">
        <img src="<?=base_url();?>js/multiMech.png" class="counter">

        <div>9 - 6</div>

    </div>

    <p> The symbol above the numbers represents the unit type.</p>
    This is Armor (tanks).
    <div class="unit rebel" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;"><section></section>
        <img src="<?=base_url();?>js/multiArmor.png" class="counter">
        <div>6 - 8</div>
    </div>
    <p></p>This is Mechinized Infantry (soldiers in half tracks, with small arms).
    <div class="unit rebel" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);  position: relative;"><section></section>
        <img src="<?=base_url();?>js/multiMech.png" class="counter">
        <div>4 - 8</div>
    </div>
    <p></p>This is Infantry. (soldiers on foot, with small arms).
    <div class="unit rebel" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;"><section></section>
        <img src="<?=base_url();?>js/multiInf.png" class="counter">
        <div>2 - 8</div>
    </div>
    <p></p>
    The number on the left is the combat strength. The number on the right is the movement allowance
    <div class="unit loyalist" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); position: relative;"><section></section>
        <img src="<?=base_url();?>js/multiMech.png" class="counter">
        <div>9 - 6</div>
    </div>
    The above unit has a combat strength of 9 and a movenent allowance of 6.
    <p></p>
    If a units numbers are in white, that means this unit is at reduced strength and can receive replacements during the replacement phase.
    <div class="clear"></div>
    <div class="unit rebel" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left;  position: relative;"><section></section>
        <img src="<?=base_url();?>js/multiArmor.png" class="counter">
        <div><span class="reduced">3 - 8</span></div>
    </div>
    <div class="unit loyalist" alt="0" src="<?=base_url();?>js/short-red-arrow-md.png" style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204); float:left; position: relative;"><section></section>
        <img class="arrow" src="<?=base_url();?>js/short-red-arrow-md.png" style="opacity: 0;">
        <img src="<?=base_url();?>js/multiMech.png" class="counter">

        <div><span class="reduced">4 - 6</span></div>

    </div>

    <div class="clear">&nbsp;</div>
    <h2>Game Play</h2>
    <p>The game is made up of 7 Game turns, each Game turn consists of two player turns, Each player turn has several phases. These are described below in the sequence of play.</p>
    <h4>Sequence of Play.</h4>
    <ul>
        <li>
            <h4>Rebel Player Turn</h4>
            <ol>
                <li>
                    <h4>Replacement Phase</h4>
                    The phasing player may allocate as many replacements as they received. Rebel forces receive one replacement per turn. (There is no replacement phase
                    for the rebel player on turn one).
                </li>
                <li>
                    <h4>Movement Phase</h4>
                    The phasing player may move any or all of their units. Movement is voluntary.
                </li>
                <li>
                    <h4>Combat Phase</h4>
                    The phasing player may any and all units that adjacent to their units. Combat is voluntary.
                </li>
                <li>
                    <h4>Second Movement Phase</h4>
                    The phasing player may move any or all of their <strong>Armored</strong> or <strong>mechinized infantry</strong> units. Infantry units may <strong>not</strong> move in the second movement phase.
                </li>
            </ol>
        </li>
        <li>
            <h4>Loyalist Player Turn</h4>
            <ol>
                <li>
                    <h4>Replacement Phase</h4>
                    The phasing player may receive as many replacements as they are allocated. Loyalists receive 10 replacements per turn.
                </li>
                <li>
                    <h4>Movement Phase</h4>
                    The phasing player may move any or all of their units. Movement is voluntary.
                </li>
                <li>
                    <h4>Combat Phase</h4>
                    The phasing player may any and all units that adjacent to their units. Combat is voluntary.
                </li>
                <li>
                    <h4>Second Movement Phase</h4>
                    The phasing player may move any or all of their <strong>Armored</strong> or <strong>mechinized infantry</strong> units. Infantry units may <strong>not</strong> move in the second movement phase.
                </li>
            </ol>
        </li>
    </ul>
    <p>At the end of 7 game turns the game is over and victory is determined.</p>
    <h2>Movement</h2>
</div>
        </div></div>