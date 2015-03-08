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
global $force_name;
$playerOne = $force_name[1];
$playerTwo = $force_name[2];?>
<div>



    <h2>Victory Conditions</h2>
    <ol>
        <li> British win at 45 points</li>
        <li> All Beluchi losses are scored a face value</li>
        <li> Beluchi's win if they score 40 points or British don't win by turn 12.</li>
        <li> Beluchi's get 15 points as long as they occupy the road hex exiting the west edge of the map</li>
        <li> All Royal units are scored at double value including Inf.</li>
        <li>All Native units are scored at face value.</li>
        <li> Beluchi victory point hexes in Black</li>
    </ol>
</div>