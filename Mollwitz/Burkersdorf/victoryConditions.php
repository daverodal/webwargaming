<?php /*
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
?><style>
    #exclusiveRules p {
        margin-left: 15px;
    }

    #exclusiveRules h1 {
        font-size: 24px;
    }

    #exclusiveRules h2 {
        font-size: 15px;
    }
</style>
<li class="exclusive">

    <span class="lessBig">Victory Conditions</span>
    <ol>
        <li><span class="lessBig">Winning the game.</span>
            <ol>
                 <li> The Prussian has won the game at the end of any Austrian turn that the Prussian has 70 victory
                    points
                    including at least two victory towns or the LOC hex.
                </li>
                <li> The Austrian Player wins at the end of any Austrian turn that he has 70 victory points.</li>
                <li> The Austrian Player wins if the Prussian has occupied no victory hexes by the end of the last turn
                    of the
                    game.
                </li>
                <li> All other cases are a draw.</li>

            </ol>
        </li>
        <li>
            <span class="lessBig">Victory points are awarded on the following basis.</span>
            <ol>
                <li>
                    For each infantry combat strength point eliminated, 1 point.
                </li>
                <li>
                    For each cavalry or artillery combat strength point eliminated, 2 point.
                </li>
                <li>
                    For each city labeled "Austrian", the Prussian player is awarded 10 points if they take it. If the
                    Austrian player retakes the city,
                    the Prussian player loses the awarded 10 points.
                </li>
                <li>If the Prussian player takes the LOC hex at the south edge of the map,
                    it's the same as taking a labeled city (see above), except there are 50 points awarded or lost.
                </li>
            </ol>
        </li>
    </ol>
</li>