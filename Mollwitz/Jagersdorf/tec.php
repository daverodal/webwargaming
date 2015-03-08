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
?><div class="dropDown"  id="TECWrapper">
    <h4 class="WrapperLabel" title='Terrain Effects Chart'>TEC</h4>
    <DIV id="TEC" style="display:none;"><div class="close">X</div>
        <br>
        <ul>
            <li>
                <strong>
                <div class="colOne">
                    <div class="hexWrapper"></div>
                    <span>Terrain Type</span>
                </div>
                <div class="colTwo">Movement Effect</div>
                <div class="colThree">Combat Effect</div>
                <div class="clear"></div>
                    </strong>
            </li>
            <li>
                <div class="colOne blankHex">
                    <div class="hexWrapper"></div>
                    <span>Clear</span>
                </div>
                <div class="colTwo">1 Movement Point</div>
                <div class="colThree">Prussian infantry are +1 when defending in or attacking into clear.</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne forestHex">
                   <div class="hexWrapper"></div>
                    <span>Forest</span>
                </div>
                <div class="colTwo">2 MP's Infantry, 3 MP's artillery/cavalry</div>
                <div class="colThree">Russians Infantry +1 when defending in or attacking into forest, cavalry halved
                    when attacking to forest. Forest blocks artillery bombardment. Cavalry loses combined arms bonus.</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne mountainHex">
                    <div class="hexWrapper"></div>
                    <span>Hill</span>
                </div>
                <div class="colTwo">1 Movement Point</div>
                <div class="colThree">All units are doubled when defending on a hill. Cavalry attacking a hill
                    are halved in addition. Hills block artillery bombardment. Cavalry loses combined arms bonus.</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne townHex">
                    <div class="hexWrapper"></div>
                    <span>Town</span>
                </div>
                <div class="colTwo">1 Movement Point</div>
                <div class="colThree">Russian Infantry +1 when defending in or attacking into town,
                    Infantry and artillery units are doubled when defending on a town.
                    this happens AFTER any +1 for russian infantry.
                    Cavalry attacking a town are halved. Towns block artillery bombardment. Cavalry loses combined arms bonus.</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne riverHex">
                    <div class="hexWrapper"></div>
                    <span>River Hexside</span>
                </div>
                <div class="colTwo">+1 Movement Point</div>
                <div class="colThree">Infantry and Cavalry halved attacking across river. Cavalry loses combined arms bonus.</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne roadHex">
                    <div class="hexWrapper"></div>
                    <span>Road Hexside</span>
                </div>
                <div class="colTwo">1/2 Movement Point if across road hex side, while in force march mode. (see movement)</div>
                <div class="colThree">No Effect</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne bridgeHex">
                    <div class="hexWrapper"></div>
                    <span>Bridge Hexside</span>
                </div>
                <div class="colTwo">Ignore terrain</div>
                <div class="colThree">Infantry and Cavalry halved attacking across river. Cavalry loses combined arms bonus.</div>
                <div class="clear"></div>
            </li>
            <!--    Empty one for the bottom border -->
            <li class="closer"></li>
        </ul>
    </div>
</div>