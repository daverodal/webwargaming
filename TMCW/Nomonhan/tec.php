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
                <div class="colOne mountainHex">
                    <span>Mountain</span>
                </div>
                <div class="colTwo">3 Movement Point</div>
                <div class="colThree">Shift two</div>
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
                <div class="colTwo">1/2 Movement Point if across road hex side</div>
                <div class="colThree">No Effect</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne bridgeHex">
                    <span>Bridge Hexside</span>
                </div>
                <div class="colTwo">Ignore terrain</div>
                <div class="colThree">Shift one if all attacks across river/bridge</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne trailHex">
                    <span>Trail Hexside</span>
                </div>
                <div class="colTwo">1 Movement Point if across tail hex side</div>
                <div class="colThree">No Effect</div>
                <div class="clear"></div>
            </li>
            <!--    Empty one for the bottom border -->
            <li class="closer"></li>
        </ul>
    </div>
</div>