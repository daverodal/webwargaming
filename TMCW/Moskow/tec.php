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
?><div class="dropDown" id="TECWrapper">
    <h4 class="WrapperLabel" title='Terrain Effects Chart'>TEC</h4>
    <DIV id="TEC" style="display:none;"><div class="close">X</div>
        <ul>
            <li>
                <div class="colOne blankHex">
                    <span>Clear</span>
                </div>
                <div class="colTwo">1 Movement Point</div>
                <div class="colThree">No Effect</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne forestHex">
                    <span>Forest</span>
                </div>
                <div class="colTwo">2 Movement Point</div>
                <div class="colThree">Shift one</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne forestHex">
                    <span>Defensive Positions (gray)</span>
                </div>
                <div class="colTwo">No Effect</div>
                <div class="colThree">Shift one for soviet defenders</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne mountainHex">
                    <span>City (red hexes)</span>
                </div>
                <div class="colTwo">No Effect</div>
                <div class="colThree">Shift One</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne riverHex">
                    <span>River Hexside</span>
                </div>
                <div class="colTwo">+1 Movement Point</div>
                <div class="colThree">Shift one if all attacks across river</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne roadHex">
                    <span>Road Hexside</span>
                </div>
                <div class="colTwo">1 Movement Point if across road hex side for german. 1/3 point for soviet units.</div>
                <div class="colThree">No Effect</div>
                <div class="clear"></div>
            </li>
            <!--    Empty one for the bottom border -->
            <li class="closer"></li>
        </ul>
    </div>
</div>