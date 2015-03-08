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
?><style type="text/css">

    #TECWrapper #TECImage {
        float: left;
    }
</style>
<div class="dropDown" id="TECWrapper">
    <h4 class="WrapperLabel" title='Terrain Effects Chart'>TEC</h4>

    <DIV id="TEC" style="display:none;">
        <div class="close">X</div>
        <div class="left">
            <ul>
                <li class="row-one">
                    <div class="column-image"></div>
                    <div class="column-one">Clear</div>
                    <div class="column-two">1 Movement Point</div>
                    <div class="column-three">No Effect</div>
                </li>
                <li class="row-two">
                    <div class="column-image"></div>

                    <div class="column-one">
                        Beach
                    </div>
                    <div class="column-two">1 Movement Point</div>
                    <div class="column-three">No Effect</div>
                </li>
                <li class="row-three">
                    <div class="column-image"></div>

                    <div class="column-one">
                        Forest
                    </div>
                    <div class="column-two">2 Movement Points</div>
                    <div class="column-three">Shift one</div>
                </li>
                <li class="row-four">
                    <div class="column-image"></div>

                    <div class="column-one">
                        Swamp
                    </div>
                    <div class="column-two">3 Movement Points</div>
                    <div class="column-three">Shift one</div>
                </li>
                <li class="row-five">
                    <div class="column-image"></div>

                    <div class="column-one">
                        Mountain
                    </div>
                    <div class="column-two">3 Movement (Mountain 2)</div>
                    <div class="column-three">Shift two (shift one if mountain attacking)</div>
                </li>
                <li class="row-six">
                    <div class="column-image"></div>
                    <div class="column-one">
                        Road hexside
                    </div>
                    <div class="column-two">1/2 if crossing road hexside</div>
                    <div class="column-three">No Effect</div>
                </li>
                <li class="row-seven">
                    <div class="column-image"></div>
                    <div class="column-one">
                        <span>Town</span>
                    </div>
                    <div class="column-two">No Effect</div>
                    <div class="column-three">Shift one</div>
                </li>
                <li class="row-eight">
                    <div class="column-image"></div>

                    <div class="column-one">
                        <span>River</span>
                    </div>
                    <div class="column-two">+1 Movement Point to cross</div>
                    <div class="column-three">Shift one, if all attacking across river.</div>
                </li>
                <li class="row-nine">
                    <div class="column-image"></div>

                    <div class="column-one">
                        <span>Ocean</span>
                    </div>
                    <div class="column-two">Movement Prohibited</div>
                    <div class="column-three">Combat Prohibited</div>
                </li>
                <!--    Empty one for the bottom border -->
                <li class="closer"></li>
            </ul>
        </div>
    </div>
</div>