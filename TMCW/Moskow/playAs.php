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
        background:url("<?=base_url("js/lossy-page1-1280px-German_troops_in_Russia_-_NARA_-_540156.tif.jpg")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;
    }
    @font-face{
        font-family: OctoberGuard;
        src: url('<?=base_url("js/octoberguard.ttf");?>');
    }
    @font-face{
        font-family: Ussr;
        src: url('<?=base_url("js/Back_In_the_USSR_DL_k.ttf");?>');
    }
    @font-face{
        font-family: Kremlin;
        src: url('<?=base_url("js/kremlin.ttf");?>');
    }
    .guard{
        font-family:OctoberGuard;
    }
    #playastitle{
        font-family: Ussr;
    }
    #welcome{
        font-family: Kremlin;
    }
</style>
<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>

<h2 id="welcome" style="text-align:center;font-size:30px;">Komrad,  Welcome to</h2>
<h1 id='playastitle' style="text-align:center;font-size:90px;"><span class="guard">MOSKOW</span> &#167;</h1>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a>
    <div class="attribution">
        By Unknown or not provided [Public domain], <a target="blank" href="http://commons.wikimedia.org/wiki/File%3AGerman_troops_in_Russia_-_NARA_-_540156.tif">via Wikimedia Commons</a>
    </div>
</fieldset>
