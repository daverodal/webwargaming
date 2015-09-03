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


?><span class="big">Exclusive Rules</span>
    <ol>
        <li class="big"><span> Setting Up</span>
            <ol>
                <li> The Loyalist players units are already setup.</li>
                <li>The Rebel Player places their units on the first turn. They will appear in a box labeled Deploy/Staging.
                    The placement of the non airborne unit will determine where the beachheads appear. Beachhead hexes are
                    where supply and non airborne reinforcements appear.</li>
                <li>
                    Airborne units once places will create a air landing zone. Air landing zones are where airborne supply and
                    reinforcements appear.
                </li>
            </ol>

        </li>
        <?php if($scenario->two){?>
            <li><span class="big">Victory Conditions</span>
                <ol>
                    <li>    <span class="big">The Game lasts 15 turns. </span>
                    </li>
                    <li>
                        Victory points are awarded by the following schedule:
                        <ol>
                            <li>
                                CA - Sunk 10
                            </li>
                            <li>
                                CA - Dead in Water 5
                            </li>
                            <li>
                                CA - Damaged 2
                            </li>
                            <li>
                                CL or DD Sunk or Dead in Water 2
                            </li>
                            <li>
                                CL or DD damaged 1
                            </li>
                        </ol>
                    </li>
                    <li>
                        Victory is awarded in levels depending up the ratio of victory points.
                        <ol>
                            <li>
                                Major IJN Victory 8.0 or more to 1
                            </li>
                            <li>
                                Minor IJN Victory 5.0 through 7.99 to 1
                            </li>
                            <li>
                                Draw 2.0 through 4.99 to 1
                            </li>
                        </ol>
                    </li>
                    <li >
                        The USN Player must prevent the IJN player from exiting any ships off the east edge of the map without power damage.
                    </li>
                    <li>
                        The IJN player must exit two ships off the east edge without power damage.
                    </li>
                    <li>Any other result is a draw.</li>
                </ol>
            </li>
        <? }?>
        <?php if($scenario->eight){?>
            <li><span class="big">Victory Conditions</span>
                <ol>
                    <li>    <span class="big">The Game lasts 20 turns. </span>
                    </li>
                    <li >
                       The USN Player must prevent the IJN player from exiting any ships off the east edge of the map without power damage.
                        </li>
                        <li>
                            The IJN player must exit two ships off the east edge without power damage.
                        </li>
                    <li>Any other result is a draw.</li>
                </ol>
            </li>
       <? }?>

        <?php if($scenario->seven){?>
            <li><span class="big">Victory Conditions</span>
                <ol>
                    <li>    <span class="big">The Game lasts 15 turns. </span>
                    </li>
                    <li >
                        The IJN Player must prevent the USN player from exiting any ships off the west edge of the map without power damage.
                    </li>
                    <li>
                        The USN player must 1 CL and 2 other ships  off the west edge without power damage.
                    </li>
                    <li>Any other result is a draw.</li>
                </ol>
            </li>
        <? }?>
        <li>
            <span class="big">Design Credits</span>
            <h2><cite><?=$name?></cite></h2>

            <h4>Game Design:</h4>
            David M. Rodal
            <h4>Graphics and Rules:</h4>
            <site>David M. Rodal</site>
            <h4>HTML 5 Version:</h4>
            David M. Rodal
        </li>
    </ol>



