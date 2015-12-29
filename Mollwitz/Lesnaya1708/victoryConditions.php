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
?><li><span class="lessBig">Victory Conditions</span>
    <ol>

        <li>
        Each side is awarded one victory point for each hostile combat factor destroyed. And
        multiple victory points for locations occupied.
            These locations are marked with numbers in blue for Swedish objectives and red for Saxon Polish Objectives.
            <p class="ruleComment">
                Note: objectives start in the possession of the enemy, so they will have a label of the opposite color
                of their number at the beginning of the game. It will switch back and forth depending upon whoever last occupied the objective.
            </p>
            </li>

        <li><?= $playerOne?>: win at the end of any Game turn that they have 25 or more points by turn 4 inclusive or
            30 points by turn 6 inclusive.
        </li>

        <li> <?= $playerTwo?>: win at the end of any game turn that they have 30 points by turn 6 inclusive.
            Or swedes do not win by the end of turn 6.</li>

        <li>A draw occurs if both sides meet the above victory conditions on same turn.</li>
    </ol>
</li>