<body>
<fieldset style="float:right;"><legend>Comlink</legend><div id="comlink"></div></fieldset>
<h2>Welcome {user} to<strong style="font-family:'Great Vibes'"> Napoleon on Mars</strong> <span style="font-style: italic;">&ldquo;{wargame}&rdquo;</span></h2>
<div style="clear:both"></div>
<a href="<?=site_url("wargame/changeWargame/{wargame}/1");?>">As Rebel</a>
<a href="<?=site_url("wargame/changeWargame/{wargame}/2");?>">As Loyalist</a>
<a href="<?=site_url("wargame/leaveGame");?>">Go To Lobby</a>
<a href="<?=site_url("wargame/resize/0");?>">BIG</a>
<a href="<?=site_url("wargame/resize/1");?>">small</a>
<a href="<?=site_url("wargame/createWargame");?>">Create Wargame</a>
<a href="<?=site_url("wargame/logout");?>">logout</a>
{games}
<a href="<?=site_url("wargame/unitInit/{name}");?>">{name}</a>
{/games}<!--<a href="--><?//=site_url("wargame/resize/0");?><!--">BIG</a>-->
<a href="#" onclick="seeUnits();return false;">See Units</a>
<a href="#" onclick="seeBoth();return false;">See Both</a>
<a href="#" onclick="seeMap();return false;">See Map</a>

<div id="content">

<div id="leftcol">
    <?php global $results_name;?>

    <div id="crt" class="ui-widget-content">
        <h3>Combat Odds</h3>
        <div id="odds"><span class="col0">&nbsp;</span></span><span class="col1">1:1</span> <span
            class="col2">2:1</span> <span class="col3">3:1</span> <span class="col4">4:1</span> <span
            class="col5">5:1</span> <span class="col6">6:1</span></div>
        <?php
        $crt = new CombatResultsTable();
        $rowNum = 1;$odd = ($rowNum & 1) ? "odd":"even";
        foreach ($crt->combatResultsTable as $row) {
            ?>
            <div class="roll <?="row$rowNum $odd"?>">
                <span class="col0"><?=$rowNum++?></span>
                <?php $col = 1;foreach ($row as $cell) { ?>
                <span class="col<?=$col++?>"><?=$results_name[$cell]?></span>

                <?php }?>
            </div>
            <?php }?>
    </div>
    <div id="gameturnContainer">
        <div class="turn" id="gameTurn1">1</div>
        <div class="turn" id="gameTurn2">2</div>
        <div class="turn" id="gameTurn3">3</div>
        <div class="turn" id="gameTurn4">4</div>
        <div class="turn" id="gameTurn5">5</div>
        <div class="turn" id="gameTurn6">6</div>
        <div class="turn" id="gameTurn7">7</div>
        <div id="turnCounter">Game Turn</div>
    </div>
    <button id="nextPhaseButton">Next Phase</button>
    <div style="clear:both;"></div>

    <fieldset style="">
        <legend>Phase Mode
        </legend>
        <div id="clock"></div>
    </fieldset>
    <fieldset style="">
        <legend>Status
        </legend>
        <div id="status"></div>
    </fieldset>
    <fieldset style="display:none;">
        <legend>Users
        </legend>
        <div id="users"></div>
    </fieldset>
    <div style="clear:both;"></div>
    <fieldset style="display:none;">
        <legend>Games
        </legend>
        <div id="games"></div>
    </fieldset>
    <div style="float:left;margin-left: 80px">
        <form onsubmit="doit();return false;" id="chatform" method="post">

            <input id="mychat" name="chats" type="text">
            <input name="submit" type="submit">
            <fieldset>
                <legend>Chats
                </legend>
                <div id="chats"></div>
            </fieldset>
        </form>
    </div>


    <div style="clear:both;"></div>

</div>
<div id="rightCol">
    <div id="deployWrapper">
        <div style="margin-right:3px;" class="left">deploy on turn one </div>
<!--        <div style="float:left">Deploy on turn 1</div>-->
    <div id="deployBox"></div>
        <div style="clear:both;"></div>
    </div>

    <div id="gameViewer" style="position:relative;">
    <div id="gameImages" class="ui-widget-content ui-draggable" >
    <img id="map" alt="map" src="<?php echo base_url();?>js/mcw.png" style="position: relative;visibility: visible;z-index: 0;">
    <?php $id = 0;?>
    {units}
    <div  class="unit {class}" id="{id}" alt="0" style="position: absolute; left: 180px; top: 140px;">
        <img  class="arrow" src="<?php echo base_url();?>js/red_right_arrow.png" class="counter" >
        <img  src="<?php echo base_url();?>js/{image}" class="counter" >
        <div>5 - 4</div>
        <!-- working with the darkened counters -->
        <!--<p style="position:absolute;top:0px;background:rgba(0,0,0,.3);z-index:102;width:32px;height:32px;"></p>-->
    </div>
    {/units}
</div>
        </div>

<!-- end gameImages -->

<div style="clear:both;"> </div>
<div style="position:relative;" id="deadpile"><div style="right:10px;font-size:20px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">Retired Units</div></div>
</div>
</div>
</body>
</html>