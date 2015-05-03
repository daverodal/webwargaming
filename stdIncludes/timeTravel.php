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
?><div class="dropDown"  id="TimeWrapper">
    <h4 class="WrapperLabel" title=Time Travel'>U<small>ndo</small></h4>
    <DIV id="Time" style="display:none;"><div class="close">X</div>

        Time you are viewing:
        <div id="clickCnt"></div><br>
<br>
        <button id="timeLive">Go to present - cancel</button><br>
        <button id="timeBranch">Branch viewed time to present</button><br>
        <button id="timeMachine">&lsaquo;</button>
        <button id="turnMachine" class="time-button">&laquo;</button>
        <button id="turnSurge" class="time-button">&raquo;</button>
        <button id="timeSurge">&rsaquo;</button><br>
        <div id="phaseClicks"></div><br>
    </div>
</div>