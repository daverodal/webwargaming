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
?><body>
<style>
    body{
        background:#000;

    }
    h1{
        color:#f66;
        text-shadow: 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white, 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white
    }
    .rebel{
        font-size:40px;
        color:#080;
    }
    .loyalist{
      font-size:40px;
        color:#18F;

    }
    legend   {
    color:white;
    }
   fieldset{

   }
</style>
<link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>

<h2 style="text-align:center;font-size:30px;font-family:'Great Vibes'"> Welcome to</h2>
    <h1 style="text-align:center;font-size:90px;font-family:'Great Vibes'">The Martian Civil War</h1>
<div style="clear:both"></div>
<fieldset style="text-align:center;width:30%;margin:0 auto;"><Legend>Play As </Legend>
    <a class="rebel"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="loyalist" href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>/">Play Multiplayer</a><br>
    <a class="loyalist" href="<?=site_url("wargame/leaveGame");?>/<?=$wargame?>/">Goto Lobby</a>

</fieldset>
