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
    <?php @include "playAs.css";?>
    body{
        background:url("<?=base_url("js/947px-United_States_bombing_raid_over_a_German_city_-_NARA_-_197269.jpg")?>") #333 no-repeat;
        background-position:center center;
        background-size:100%;
    }
</style>
<link href='http://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>

<h2 style="text-align:center;font-size:30px;font-family:'Great Vibes'"> Welcome to</h2>
<h1 style="text-align:center;font-size:90px;font-family:'Lobster'">Final Chapter</h1>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a>
    <div class="attribution">
        By Unknown or not provided (U.S. National Archives and Records Administration) [Public domain], <a href="https://commons.wikimedia.org/wiki/File%3AUnited_States_bombing_raid_over_a_German_city_-_NARA_-_197269.jpg">via Wikimedia Commons</a>

    </div>
</fieldset>
