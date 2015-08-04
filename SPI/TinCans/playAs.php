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
<link href='http://fonts.googleapis.com/css?family=Special+Elite' rel='stylesheet' type='text/css'><style>
    <?php @include "playAs.css";?>
    body{
        background:url("<?=base_url("js/German_Fletcher_class_destroyers_in_1960.JPG")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;
    }
    h1{
        font-size:30px;
        font-family: 'Special Elite', sans-serif;
        text-align: center;
    }
    h2{
        font-size:140px;
        font-family: 'Special Elite', sans-serif;
        margin: 50px 0;
    }</style>

<h1 style="text-align:center;font-size:30px;font-family:'Special Elite'"> Welcome to</h1>
<h2 style="text-align:center;font-size:140px;font-family:'Special Elite'">Tin Cans</h2>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a>
    <div class="attribution">
        By S.L.A. Marshall, U.S. Army [Public domain], <a target="_blank" href="https://commons.wikimedia.org/wiki/File%3AGerman_Fletcher_class_destroyers_in_1960.JPG">via Wikimedia Commons</a>

    </div>
</fieldset>
