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
?><head>
    <meta charset="UTF-8">
    <link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Petit+Formal+Script' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Monsieur+La+Doulaise' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Pinyon+Script' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
    <style>
        body{
            background:#000;
            background:url("<?=base_url("js/The_Duke_of_Marlborough_at_the_Battle_of_Oudenaarde_(1708)_by_John_Wootton.jpg")?>") #333 no-repeat;
            background-position:center 0;
            background-size:100%;

        }
        h2{
            color:#f66;
            text-shadow: 0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,0 0 3px black,
            0 0 3px black,0 0 3px black;
        }
        h1{
            text-align:center;
            font-size:90px;
            font-family:'Pinyon Scrip';
            color:#f66;
            margin-top:0px;
            text-shadow: 0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
            0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
            0 0 5px black,0 0 5px black,0 0 5px black, 0 0 5px black,0 0 5px black,0 0 5px black,0 0 5px black,
            0 0 5px black,0 0 5px black,0 0 5px black;
        }
        .link{
            font-size:40px;
            text-decoration: none;
            color:#f66;
            text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
        }
        legend   {
            text-decoration: none;
            color:#f66;
            text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
        }
        fieldset{
            text-align: center;
            width:30%;
            margin:0px;
            position:absolute;
            top:300px;
            left:50%;
            margin-left:-15%;
            background-color: rgba(255,255,255,.4);
        }
        .attribution{
            background: rgba(255,255,255,.6);
        }
        .attribution a{
            color:red;
            text-shadow: 1px 1px 1px black;
        }

    </style>
</head>
<body>

<div class="backBox">
<h2 style="text-align:center;font-size:30px;font-family:'Monsieur La Doulaise'"> Welcome to</h2>
    <h1 style=""><span>Oudenarde 1708</span></h1>
</div>
<div style="clear:both"></div>
<fieldset ><Legend>Play As </Legend>
    <a  class="link" href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>/">Play Hotseat</a><br>
    <a  class="link" href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>/">Play Multi</a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a><br>
    <div class="attribution">
        John Wootton [Public domain], <a target="blank" href="http://commons.wikimedia.org/wiki/File%3AThe_Duke_of_Marlborough_at_the_Battle_of_Oudenaarde_(1708)_by_John_Wootton.jpg">via Wikimedia Commons</a></fieldset>
