<body>
<fieldset style="float:right;"><legend>Comlink</legend><div id="comlink"></div></fieldset>
<h1>Welcome {user} To the The Martian Civil War<span style="font-style: italic;">&ldquo;{wargame}&rdquo;"</span></h1>
<div style="clear:both"></div>
{lobbies}
<a href="<?=site_url("wargame/changeWargame");?>/{id}/1">{name} As Rebel</a>
<a href="<?=site_url("wargame/changeWargame");?>/{id}/2">{name}As Loyalist</a>
{/lobbies}
<a href="<?=site_url("wargame/resize/0");?>">BIG</a>
<a href="<?=site_url("wargame/resize/1");?>">small</a>
<a href="<?=site_url("wargame/createWargame");?>">Create Wargame</a>
<a href="<?=site_url("wargame/logout");?>">logout</a>
<a href="<?=site_url("wargame/unitInit/MartianCivilWar");?>">New Game</a>
<a href="<?=site_url("wargame/unitInit/BattleForAllenCreek");?>">Battle For Allen Creek</a>
<a href="#" onclick="seeUnits();return false;">See Units</a>
<a href="#" onclick="seeBoth();return false;">See Both</a>
<a href="#" onclick="seeMap();return false;">See Map</a>

<div id="content">
<style type="text/css">
    body{
        background:#eee;
        color:#333;
    }
    #status{
        text-align:right;
    }
    #status legend{
        text-align:left;
    }
    fieldset{
        background:white;
        border-radius:9px;
    }
    #crt{
        border-radius:15px;
        border:10px solid #1AF;
    //position:relative;
        width:308px;
        background:#fff;color:black;
        font-weight:bold;
        padding:1px 5px 10px 15px;
    }
    #crt h3{
        height:40px;
        margin-bottom:5px;
        vertical-align:bottom;
    }
    #crt span{
        width:32px;
    // position:absolute;
    }
    .col1{
        left:20px;
    }
    .col2{
        left:60px;
    }
    .col3{
        left:100px;
    }
    .col4{
        left:140px;
    }
    .col5{
        left:180px;
    }
    .col6{
        left:220px;
    }
    .roll, #odds{
        height:20px;
        background :#1af;
        margin-right:14px
    }
    #odds{
        background:white;
    }
    .even{
        color:black;
    }
    .odd{
        color:black;
    }
    .row1{
        top:80px;

    }
    .row2{
        top:100px;
        background:white;
    }
    .row3 {
        top:120px;
    }
    .row4{
        top:140px;
        background:white;
    }
    .row5{
        top:160px;
    }
    .row6{
        top:180px;
        background:white;
    }
    .roll span, #odds span{
        margin-right:10px;
        float:left;
        display:block;
        width:32px;
    }
    #gameImages{
        float:left;
        margin-left:50px;
        position: relative;
        border:10px solid #1af;
        border-radius:10px;
        height:425px;
    }
    #leftcol {
        float:left;
        width:360px;
    }
    #gameturnContainer{
        height:38px;
        position:relative;
        float:left;
    }
    #gameturnContainer div{
        float:left;
        height:36px;
        width:36px;
        border:solid black;
        border-width:1px 1px 1px 0;
        font-size:18px;
        text-indent:2px;
    }
    .storm {
        font-size:50%;
    }
    #gameturnContainer #turn1{
        border-width:1px;
    }
    #gameturnContainer #turnCounter{
        position:absolute;
        z-index:20;
        width:32px;
        height:32px;
        color:black;
        background-color:rgb(101,200,85);
        font-size:11px;
        text-indent:0px;
        top:2px;
        left:2px;
        text-align:center;
        border-width:1px;
    }

    #map {
        width:1044px;
        height:850px;
        width:783px;
        height:638px;
    width:{mapWidth};
    height:{mapHeight};
    /*width:787px;*/
    /*height:481px;*/
        }
    .unit{
        width:64px;
        height:64px;
        width:48px;
        height:49px;
    width:{unitSize};
    height:{unitSize};
    /*width:32px;*/
    /*height:32px;*/
        }
    .unit div {
        text-align:center;
        margin-top:{unitMargin};
        color:white;
        /*text-indent:3px;*/
        font-size:{unitFontSize};
        font-weight:bold;
    }
    .rebel div{
        color:green;
    }
    .sympth div{
        color:white;
    }
    .unit img {
        width:100%;
        height:100%;
    }
</style>
<div id="leftcol">
    <?php global $results_name;?>

    <div id="crt">
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
        <div id="turn1">1</div>
        <div id="turn2">2</div>
        <div id="turn3">3 <span class="storm">storm</span></div>
        <div id="turn4">4 <span class="storm">storm</span></div>
        <div id="turn5">5</div>
        <div id="turn6">6</div>
        <div id="turn7">7</div>
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
</body>

</div>
<div id="gameImages" >
    <img id="map" alt="map" src="<?php echo base_url();?>js/martianCivilWar.png" style="position: relative;visibility: visible;z-index: 0;">
    <div  class="unit" id="0" alt="0" style="position: absolute; left: 180px; top: 140px; z-index:100">
        <img  src="<?php echo base_url();?>js/loyalInf.png" class="counter" >
        <div>5 - 4</div>
    </div>

    <?php for($i = 1,$id=1 ; $i < 17;$i++){?>
    <div  class="unit" id="<?=$id++?>" alt="0" style="position: absolute; left: 180px; top: 140px; z-index:100">
    <img  src="<?php echo base_url();?>js/loyalInf.png" class="counter" >
    <div>5 - 4</div>
    </div>
    <?php }?>
    <?php for(; $id < 29;){?>
    <div  class="unit rebel" id="<?=$id++?>" alt="0" style="position: absolute; left: 180px; top: 140px; z-index:100">
        <img  src="<?php echo base_url();?>js/rebelInf.png" class="counter" >
        <div>7 - 5</div>
    </div>
    <?php }?>
    <?php for(; $id < 39;){?>
        <div  class="unit sympth" id="<?=$id++?>" alt="0" style="position: absolute; left: 180px; top: 140px; z-index:100">
            <img  src="<?php echo base_url();?>js/sympthInf.png" class="counter" >
            <div>7 - 5</div>
        </div>
        <?php }?>
</div>

<!-- end gameImages -->
</div>

<div style="clear:both;height:20px;"> </div>

</html>