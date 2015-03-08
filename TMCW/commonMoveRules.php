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
?><span class="big">Movement</span>

<p>The Second Number on the counter is Movement Points <abbr title="Movement Points">(MP)</abbr>.</p>

<p>Units expend different amounts of <abbr title="Movement Points">MP</abbr> for different terrains.
    Also,
    different units may expend
    different amounts to enter certain hexes.</p>
<ol>
    <li>The number of movement points or <abbr title="Movement Points">MP</abbr>'s a unit expends to
        enter a
        hex is baseed
        up both the unit type and the hex being entered. It can also be affected by the hex side be
        traversed (example, a river hexside).
        Please see the Terrain Effects Chart or
        <abbr title="Terrain Effects Chart">TEC</abbr> for the effects of terrain on movement. The <abbr
            title="Terrain Effects Chart">TEC</abbr> may be found by pressing
        the button <abbr title="Terrain Effects Chart">TEC</abbr>.
    </li>


    <li>Road Movement
<p>        When a unit moves from a hex containing a road, to another hex containing a road, and a road
            traverses the hexside
            be traversed, the unit may be eligible for road movement. Road movement often requires less
            <abbr title="Movement Points">MP</abbr>'s than the other terrain in the hex.</p>

    </li>
    <?php @include "exclusiveMoveRules.php";?>
</ol>