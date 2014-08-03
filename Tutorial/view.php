<body xmlns="http://www.w3.org/1999/html">
<header id="header">
<div id="headerContent">
    <div id="rightHeader">
        <div id="mouseMove">mouse</div>
        <div id="comlinkWrapper" style="float:right;">
            <div id="comlink"></div>
        </div>
        <div id="menuWrapper"><h4 class="WrapperLabel" title="Game Menu">Menu</h4>
            <div id="menu"><div class="close">X</div>
                <ul>
                    <li><a id="muteButton">mute</a></li>
                    <li><a href="<?=site_url("wargame/leaveGame");?>">Go To Lobby</a></li>
                    <li><a href="<?=site_url("users/logout");?>">logout</a></li>
                    <!--                        <li><a href="--><?//=site_url("wargame/unitInit/MartianCivilWar");?><!--">Restart Game</a></li>-->
                    <li><a href="#" onclick="seeUnits();return false;">See Units</a></li>
                    <li><a href="#" onclick="seeBoth();return false;">See Both</a></li>
                    <li><a href="#" onclick="seeMap();return false;">See Map</a></li>
                    <li class="closer"></li>
                </ul>
            </div>
        </div>
        <div id="infoWrapper"><h4 class="WrapperLabel" title="Game Information">Info</h4>
            <div id="info"><div class="close">X</div>
                <ul>
                    <li>   Welcome {user}</li>
                    <li>you are playing as  <?=$player;?></li>
                    <li>
                        in <span style="font-family:'Nosifer';">The Martian Civil War</span></li>
                    <li> The file is called {wargame}</li>
                    <li>Game Designer: David Rodal</li>

                    <li class="closer"></li>
                </ul>
            </div>
        </div>
        <span id="clock"></span>
        <span id="status"></span>
        <span id="victory"></span>
    </div>
    <div style="clear:both;"></div>

    <!--            <div id="clickCnt"></div>-->
    <!--        <button id="timeMachine">Time Travel</button>-->
    <!--        <button id="timeSurge">Time Surge</button>-->
    <!--        <button id="timeLive">Live</button>-->
    <!--        <span id="phaseClicks"></span>-->
</div>
<?php global $results_name;?>
<div id="bottomHeader" style="clear:both;">
<button id="nextPhaseButton">Next Phase</button>
<div id="crtWrapper">
    <h4 class="WrapperLabel" title='Combat Results Table'><span class="goLeft">&laquo;</span>CRT<span class="goRight">&raquo;</span></h4>
</div>





</div>
</header>
<div id="content">

    <div id="leftcol">

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

        </div>
    <div style="clear:both;"></div>
    <div id="rightCol">

        <div id="gameViewer" style="position:relative;">
            <div id="gameImages" class="ui-widget-content">
                <img id="map" style="position: relative;visibility: visible;z-index: 0; alt="map" src="<?php echo base_url().$mapUrl;?>">
                <?php $id = 0;?>
                {units}
                <div class="unit {class}" id="{id}" alt="0"><div class="shadow_mask"></div>
                    <img class="arrow" src="<?php echo base_url();?>js/short-red-arrow-md.png" class="counter">
                    <img src="<?php echo base_url();?>js/{image}" class="counter">

                    <div>5 - 4</div>

                </div>
                {/units}
                <div id="floatMessage"><header></header><p></p></div>
            </div>

            <!-- end gameImages -->
        </div>
        <video  style="float:right;"  autoplay="true" width=500" src="<?=base_url().'js/Move.m4v'?>"></video>
        <audio class="pop"  src="<?=base_url().'js/pop.m4a'?>"></audio>
        <audio class="poop"  src="<?=base_url().'js/lowpop.m4a'?>"></audio>
        <div style="clear:both;height:20px;"></div>

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
var Player = 'Markarian';
$( "#OBCWrapper h4" ).click(function() {
    $( "#OBC" ).toggle({effect:"blind",direction:"up"});
});
$( "#TECWrapper h4" ).click(function() {
    $( "#TEC" ).toggle({effect:"blind",direction:"up"});
});
$("#crtWrapper h4 .goLeft").click(function(){
//    $("#crtWrapper").css("float","left");
    $("#crtWrapper").animate({left:0},300);

    return false;
});
$("#crtWrapper h4 .goRight").click(function(){
    var wrapWid = $("#crtWrapper").css('width').replace(/px/,"");
    var moveLeft = $("body").css('width').replace(/px/,"");
    $("#crtWrapper").animate({left:moveLeft - wrapWid},300);
    return false;
});
$( "#crtWrapper h4" ).click(function() {
    $( "#crt" ).toggle({effect:"blind",direction:"up"});
});
$("bodxy").bind("keypress",function(){
});
var up = 0;
$( "#hideShow" ).click(function() {
    up ^= 1;
    $( "#headerContent" ).toggle({effect:"blind",direction:"up"});
    if(up){
        $("#content").animate({marginTop:"30px"},"slow");
    }else{
        $("#content").animate({marginTop:"140px"},"slow");

    }
});
</script>
<div id="display"></div>
</body></html>