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
        The units are pre deployed

    </li>
    <li><span class="big">Victory Conditions</span>

        <?php if ($scenario->two) { ?>
            <ol>
                <li><span class="big">The Game lasts 15 turns. </span>
                </li>
                <li>Special Rules: No USN ship of one group may spot for a ship in the other group.(movement spotting only)</li>
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
                        <li>
                            Minor USN Victory 1 through 1.99 to 1
                        </li>
                        <li>
                            Major USN Victory less than 1 to 1
                        </li>
                    </ol>
                </li>
            </ol>
        <? } ?>
        <?php if ($scenario->three) { ?>
            <ol>

                <li><span class="big">The Game lasts 15 turns. </span>
                </li>
                <li>No ship may change heading until the first spotting occurs.</li>
                <li>Any IJN ship not exited off the south map edge my the end of game turn 15 is considered sunk.</li>
                <li>The IJN player can score an automatic victory if one undamaged CA is exited off the east map edge.
                    If this does not occur victory is by the rules below.
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
                            Major IJN Victory 3.0 or more to 1
                        </li>
                        <li>
                            Minor IJN Victory 1.5 through 2.99 to 1
                        </li>
                        <li>
                            Draw .75 through 1.49 to 1
                        </li>
                        <li>
                            Minor USN victory .25 through .74 to 1
                        </li>
                        <li>
                            Major USN victory less than .25 to 1
                        </li>
                    </ol>
                </li>
            </ol>
        <? } ?>
        <?php if ($scenario->eight) { ?>
            <ol>
                <li><span class="big">The Game lasts 20 turns. </span>
                </li>
                <li>
                    The USN Player must prevent the IJN player from exiting any ships off the east edge of the map
                    without
                    power damage.
                </li>
                <li>
                    The IJN player must exit two ships off the east edge without power damage.
                </li>
                <li>Any other result is a draw.</li>
            </ol>
        <? } ?>

        <?php if ($scenario->seven) { ?>
            <ol>
                <li><span class="big">The Game lasts 15 turns. </span>
                </li>
                <li>
                    The IJN Player must prevent the USN player from exiting any ships off the west edge of the map
                    without
                    power damage.
                </li>
                <li>
                    The USN player must 1 CL and 2 other ships off the west edge without power damage.
                </li>
                <li>Any other result is a draw.</li>
            </ol>
        <? } ?>
    </li>
    <li>
        <span class="big">Design Credits</span>

        <h2><cite><?= $name ?></cite></h2>
        <h4>HTML 5 Version:</h4>
        David M. Rodal
    </li>
</ol>



