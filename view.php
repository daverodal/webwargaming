<body xmlns="http://www.w3.org/1999/html">
<fieldset style="float:right;">
    <legend>Comlink</legend>
    <div id="comlink"></div>
</fieldset>
<h2>Welcome {user} to <span style="font-family:'Great Vibes';">The Martian Civil War</span><span style="font-style: italic;">&ldquo;{wargame}&rdquo;</span>
</h2>

<div style="clear:both"></div>
<a href="<?=site_url("wargame/changeWargame/{wargame}/1");?>">As Rebel</a>
<a href="<?=site_url("wargame/changeWargame/{wargame}/2");?>">As Loyalist</a>
<a href="<?=site_url("wargame/leaveGame");?>">Go To Lobby</a>
<a href="<?=site_url("wargame/resize/0");?>">BIG</a>
<a href="<?=site_url("wargame/resize/1");?>">small</a>
<a href="<?=site_url("wargame/createWargame");?>">Create Wargame</a>
<a href="<?=site_url("users/logout");?>">logout</a>
{games}
<a href="<?=site_url("wargame/unitInit/{name}/{arg}");?>">{name} {arg}</a>
{/games}<!--<a href="--><?//=site_url("wargame/resize/0");?><!--">BIG</a>-->
<a href="#" onclick="seeUnits();return false;">See Units</a>
<a href="#" onclick="seeBoth();return false;">See Both</a>
<a href="#" onclick="seeMap();return false;">See Map</a>

<div id="content">

    <div id="leftcol">
        <?php global $results_name;?>

        <div id="crtWrapper">
            <h4>View CRT</h4>
            <div id="crt">
                <h3>Combat Odds</h3>

                <div id="odds">
                    <span class="col0">&nbsp;</span>
                    <?php
                        $crt = new CombatResultsTable();

    $i = 1;
                        foreach($crt->combatResultsHeader as $odds){
                            ?>
                            <span class="col<?=$i++?>"><?=$odds?></span>
                       <?php } ?>
                </div>
                <?php
                    $rowNum = 1;$odd = ($rowNum & 1) ? "odd" : "even";
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
        </div>

        <!-- <div id="chatDiv">
            <form onsubmit="doit();return false;" id="chatform" method="post">

                <input id="mychat" name="chats" type="text">
                <input name="submit" type="submit">
                <fieldset>
                    <legend>Chats
                    </legend>
                    <div id="chats"></div>
                </fieldset>
            </form>
        </div>-->
        <div id="OBCWrapper">
            <h4>Order of Battle</h4>
        <div id="OBC" style="display:none;">
            <fieldset>
                <legend>turn 1</legend>
                <div id="gameTurn1">
                    <div id="turnCounter">Game Turn</div>
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 2</legend>
                <div id="gameTurn2">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 3</legend>
                <div id="gameTurn3">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 4</legend>
                <div id="gameTurn4">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 5</legend>
                <div id="gameTurn5">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 6</legend>
                <div id="gameTurn6">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 7</legend>
                <div id="gameTurn7">
                </div>
            </fieldset>
            <div style="clear:both"></div>
        </div>



    </div>
        </div>
    <div style="clear:both;"></div>
    <div id="rightCol">
        <div id="deployWrapper">
            <div style="margin-right:3px;" class="left">Deploy/Staging area</div>
            <div id="deployBox"></div>
            <div style="clear:both;"></div>
        </div>

        <div id="gameViewer" style="position:relative;">
            <div id="gameImages" class="ui-widget-content">
                <img id="map" alt="map" src="<?php echo base_url();?>js/Martian.png"
                     style="position: relative;visibility: visible;z-index: 0;">
                <?php $id = 0;?>
                {units}
                <div class="unit {class}" id="{id}" alt="0"><section style="height:100%;width:100%;position:absolute;background:transparent;"></section>
                    <img class="arrow" src="<?php echo base_url();?>js/short-red-arrow-md.png" class="counter">
                    <img src="<?php echo base_url();?>js/{image}" class="counter">

                    <div>5 - 4</div>

                </div>
                {/units}
            </div>

            <!-- end gameImages -->
        </div>
        <div style="clear:both;height:20px;"></div>
        <button id="nextPhaseButton">Next Phase</button>

        <fieldset id="phaseDiv">
            <legend>Phase Mode
            </legend>
            <div id="clock"></div>
        </fieldset>
        <fieldset id="statusDiv">
            <legend>Status
            </legend>
            <div id="status"></div>
        </fieldset>
        <div style="clear:both;height:20px;"></div>

        <div style="position:relative;" id="deadpile">
            <div style="right:10px;font-size:50px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">
                Retired Units
            </div>
        </div>
    </div>

<script type="text/javascript">
//    $( "#crtWrapper" ).accordion({
//        collapsible: true,
//        active: false,
//    });
//    $( "#OBCWrapper").accordion({
//        collapsible: true,
//        active: false
//
//    })
$( "#OBCWrapper h4" ).click(function() {
    $( "#OBC" ).toggle({effect:"blind",direction:"up"});
});
$( "#crtWrapper h4" ).click(function() {
    $( "#crt" ).toggle({effect:"blind",direction:"left"});
});
$( "#TECWrapper h4" ).click(function() {
    $( "#TEC" ).toggle({effect:"blind",direction:"up"});
});
</script>
</body></html>