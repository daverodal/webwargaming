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
    #header{
        /*display:none;*/
    }
    #gameRules{
        font-family:sans-serif;
    }
    #gameRules table, #gameRules th, #gameRules td{
        border:1px solid black;
    }
    #gameRules h1{
        color:#338833;
        font-size:60px;

    }
    #GR #credits h2{
        color:#338833;
    }
    #GR li{
        margin: 3px 0;
    }
    #GR h4{
        margin-bottom:5px;
    }
    #GR #credits h4{
        margin-bottom:0px;
    }
    #gameRules h4:hover{
        text-decoration: none;
    }
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Rules</h4>
    <div id="GR" style="display:none">
        <div class="close">X</div>
<div id="gameRules">Napoleon On Mars</div>
        </div>
    </div>