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
 * Copyright davidrodal.com
 * User: david
 * Date: 2/1/15
 * Time: 4:02 PM
 */
?>
<span class="big">Zones of Control</span>

<p>
    The six hexes surrounding a unit constitute it's Zone of Control or <abbr
        title="Zone Of Control">ZOC</abbr>.
    <abbr title="Zone Of Control">ZOC</abbr>'s affect the movement of enemy units. The affect is
    dependant upon
    many factors.
</p>
<ol>
    <li><span class="big">Effects on Movement</span>

        <ol>
        <li>When a unit enters a hostile <abbr title="Zone Of Control">ZOC</abbr> it must stop and move no further that turn.</li>
            <li> If a unit starts it turn in an enemy <abbr title="Zone Of Control">ZOC</abbr> it may exit that hex without penalty but must
                again stop upon entering another enemy <abbr title="Zone Of Control">ZOC</abbr>.</li>
            <li>A unit may always move at least one hex, even if directly from one <abbr title="Zone Of Control">ZOC</abbr> to another.</li>
        </ol>
    </li>
    <li><span class="big">Effects on Combat</span>
        <p>If a unit is forced to retreat and cannot do so without entering an enemy <abbr title="Zone Of Control">ZOC</abbr>, that unit is eliminated instead.</p>
    </li>
</ol>