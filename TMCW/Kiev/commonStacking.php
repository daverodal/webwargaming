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
 * User: David Markarian ROdal
 * Date: 2/1/15
 * Time: 5:07 PM
 */?>
<span class="big">Stacking</span>
<p>
    Units may stack up to 3 units in a hex.
</p><p class="ruleComment">
    If a user right clicks on a stack of units they can cycle through all the units in the stack.
</p>
<ol>
    <li>Units may stack up to 3 units in a hex at end of their movement. They pass through other friendly units in excess of that limit, provided
        they end the turn no more that 3 unit in a hex.</li>
    <li>If units are forced to retreat during combat, they must must not violate the 3 units per hex rule at any point in their retreat. Units unable to do so will take a
        one step reduction instead.</li>
    <li>Units may ignore zoc's of enemy units while retreating provided
        they are entering a hex already containing friendly units, and not in excess of the 3 units per hex rule.</li>

    <li>During combat, units stacked together may attack the same or different enemy hexes.</li>

</ol>