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
    width:<?=$mapWidth;?>;/*really*/
    height:<?=$mapHeight;?>;
        /*width:787px;*/
        /*height:481px;*/
        }
    .unit{
        width:64px;
        height:64px;
        width:48px;
        height:49px;
    width:<?=$unitSize?>;
    height:<?=$unitSize?>;
        /*width:32px;*/
        /*height:32px;*/
        }
    .unit div {
        text-align:center;
    margin-top:<?=$unitMargin?>;
    color:white;
        /*text-indent:3px;*/
    font-size:<?=$unitFontSize?>;
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
<script>
x = new Sync("<?=site_url("wargame/fetch/");?>");
x.register("chats", function(chats) {
    var str;
    for (i in chats) {
        str = "<li>" + chats[i] + "</li>";
        str = str.replace(/:/,":<span>");
        str = str.replace(/$/,"</span>");
        $("#chats").prepend(str);
    }
});
x.register("users", function(users) {
    var str;
    $("#users").html("");
    for (i in users) {
        str = "<li>" + users[i] + "</li>";
        $("#users").append(str);
    }
});
x.register("gameRules", function(gameRules) {
    turn = gameRules.turn;
    var pix = turn  + (turn - 1) * 36 + 1;
    $("#turnCounter").css("left",pix+"px");
    if(gameRules.attackingForceId == 1){
        $("#turnCounter").css("background","rgb(0,128,0)");
        $("#turnCounter").css("color","white");
    }else{
        $("#turnCounter").css("background","rgb(0,128,255)");
        $("#turnCounter").css("color","white");
    }

    var html;
    html = gameRules.phase_name[gameRules.phase] + " - " + gameRules.mode_name[gameRules.mode];
    if(gameRules.storm){
        html += "<br><strong>Sand Storm rules in effect</strong>";
    }
    switch(gameRules.phase){
        case <?=BLUE_REPLACEMENT_PHASE?>:
        case <?=RED_REPLACEMENT_PHASE?>:
            if(gameRules.replacementsAvail !== false && gameRules.replacementsAvail != null){
                html += "<br>There are "+gameRules.replacementsAvail+" available";
            }
            break;
    }
    switch(gameRules.mode){
        case <?=EXCHANGING_MODE?>:
        case <?=ATTACKER_LOSING_MODE?>:
                html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
            break;
        case <?=ADVANCING_MODE?>:
            html += "<br>Click on one of the pink units to advance it.<br>then  click on a hex to advance, or the unit to stay put.";
            break;
    }
    $("#clock").html(html);
});
x.register("games", function(games) {
    var str;
    $("#games").html("");
    for (i in games) {
        str = "<li>" + games[i] + "</li>";
        $("#games").append(str);
    }
});
x.register("clock", function(clock) {
    //$("#clock").html(clock);
});
x.register("mapUnits", function(mapUnits) {
    var str;
    var isStacked = new Array();
    var fudge;
    var x,y;
    for (i in mapUnits) {
        width = $("#"+i).width();
        height = $("#"+i).height();
        x =  [mapUnits[i].x];
        y = mapUnits[i].y;
        if(isStacked[x] === undefined){
            isStacked[x] = new Array();
        }
        if(isStacked[x][y] === undefined){
            isStacked[x][y] = 0;
        }
        fudge = 0;
        if(isStacked[x][y]++){
            fudge = isStacked[x][y] * 2;
        }

         $("#"+i).css({left: -1+mapUnits[i].x-width/2-fudge+"px",top:-1+mapUnits[i].y-height/2-fudge+"px"});
        var img = $("#"+i+" img").attr("src");
        if(mapUnits[i].isReduced){
            img = img.replace(/(.*[0-9])(\.png)/,"$1reduced.png");
        }else{
            img = img.replace(/([0-9])reduced\.png/,"$1.png");
        }
        var  move = mapUnits[i].maxMove - mapUnits[i].moveAmountUsed;
        var str = mapUnits[i].strength;
        $("#"+i+" div").html(str + " - "+move);
        $("#"+i).attr("src",img);

    }
});
x.register("moveRules", function(moveRules) {
    var str;
    $("#status").html("");
    if(moveRules.movingUnitId){
        $("#status").html("Unit #:"+moveRules.movingUnitId+" is currently moving");
//            alert($("#"+moveRules.movingUnitId).css('opacity',.5));
    }
});
x.register("force", function(force) {
//        if(this.animate !== false){
//            self.clearInterval(this.animate);
//            this.animate = false;
//            $("#"+this.animateId).stop(true);
//        }
    var units = force.units;

    var status = "";
    for (i in units) {
        color = "transparent";
        $("#"+i).css({zIndex: 100});
        switch(units[i].status){
            case <?=STATUS_CAN_REINFORCE?>:
                if(units[i].forceId === force.attackingForceId && units[i].forceId == <?=BLUE_FORCE?>){

                    color = "turquoise";
                }
                break;
           case 1:
                if(units[i].forceId === force.attackingForceId){

                    color = "#1af";
                }
                break;
            case <?=STATUS_REINFORCING?>:
            case <?=STATUS_MOVING?>:
                color = "orange";
                $("#"+i).css({zIndex: 101});
//               var top =  $("#"+i).css("top");
//                var left =  $("#"+i).css("left");
//                 $("#"+i).css({top:top-5});
//                $("#"+i).css({left:left-5});



//                status += " "+units[i].moveAmountUsed+" MF's Used";
                break;
            case 6:
                color = "#666";
                break;
            case 8:
                color = "orange";
                break;
            case 9:
            case <?=STATUS_BOMBARDING?>:
                color = "DarkRed";
                break;
            case 13:
                color = "purple";
                break;
            case 14:
                color = "yellow";
                break;
            case 16:
                color = "pink";
                break;
            case 17:
                color = "cyan";
                break;
            case <?=STATUS_CAN_EXCHANGE?>:
            case <?=STATUS_CAN_ATTACK_LOSE?>:

                color = "red";
                break;
            case 29:
                color = "blue";
                break;
            case 30:
                color = "orange";
                break;
            case <?=STATUS_CAN_UPGRADE?>:
                case <?=STATUS_ELIMINATED?>:
                    if(units[i].forceId === force.attackingForceId){

                color = "white";
                    }
                break;


        }
        $("#status").html(status);
        $("#"+i).css({borderColor: color});

    }
});
x.register("combatRules", function(combatRules) {
    for(var combatCol = 1;combatCol <= 6;combatCol++){
        $(".col"+combatCol).css({background:"transparent"});
//            $(".odd .col"+combatCol).css({color:"white"});
//            $(".even .col"+combatCol).css({color:"black"});

    }
    var title = "Combat Results ";
    var cdLine = "";
    str = ""
    if(combatRules ){
        cD = combatRules.currentDefender;
            if(combatRules.combats && Object.keys(combatRules.combats).length > 0){
                if(cD !== false){
                $("#"+cD).css({borderColor: "yellow"});
               if(Object.keys(combatRules.combats[cD].attackers).length != 0){
                    combatCol = combatRules.combats[cD].index + 1;
                   if(combatCol >= 1){
                    $(".col"+combatCol).css('background-color',"rgba(255,255,1,.6)");
                    if(combatRules.combats[cD].Die !== false){
                        $(".row"+combatRules.combats[cD].Die+" .col"+combatCol).css('font-size',"110%");
                        $(".row"+combatRules.combats[cD].Die+" .col"+combatCol).css('background',"#eee");
                    }
                   }
               }
            }
                   var str = "<fieldset><legend>other combats</legend>";
                    cdLine = "";
                    for(i in combatRules.combats){
//                        if(combatRules.combats[i].Die){
//                            str += " Die "+combatRules.combats[i].Die + " result "+combatRules.combats[i].combatResult;
//                        }
                        if(combatRules.combats[i].index !== null){
                            var atk = combatRules.combats[i].attackStrength;
                            var atkDisp = atk;
                            if(combatRules.storm){
                                atkDisp = atk*2 + " halved for storm = "+atk;
                            }
                            var def = combatRules.combats[i].defenseStrength;
                            var ter = combatRules.combats[i].terrainCombatEffect;
                            var idx = combatRules.combats[i].index+ 1;
                            var odds = Math.floor(atk/def);
                            var oddsDisp = odds + " : 1";
                            if(odds < 1){
                                oddsDisp = "No effect";
                            }
                            var idxDisp = idx + " : 1";
                            if(idx < 1){
                                idxDisp = "No effect";
                            }

                            newLine =  "Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>odds = "+ oddsDisp +"<br>Terrain Shift left "+ter+ " = "+idxDisp+"<br><br>";
                            if(cD !== false && cD == i){
                                cdLine = "<fieldset><legend>Current Combat</legend><strong>"+newLine+"</strong></fieldset>";
                                newLine = "";
                            }
                            str += newLine;
                        }

                    }
                if(cD !== false){
                    attackers = combatRules.combats[cD].attackers;
                    for(var i in attackers){
                        $("#"+i).css({borderColor: "crimson"});

                    }
                }
                str += "</fieldset>";
                $("#status").html(cdLine+str);

            }

        var lastCombat = "";
        if(combatRules.combatsToResolve){
            if(combatRules.lastResolvedCombat){
                title += "<strong style='margin-left:20px;font-size:150%'>"+combatRules.lastResolvedCombat.Die+" "+combatRules.lastResolvedCombat.combatResult+"</strong>";
                combatCol = combatRules.lastResolvedCombat.index + 1;
                combatRoll = combatRules.lastResolvedCombat.Die;
                $(".col"+combatCol).css('background-color',"rgba(255,255,1,.6)");
                $(".row"+combatRoll+" .col"+combatCol).css('background-color',"cyan");
//                    $(".row"+combatRoll+" .col"+combatCol).css('color',"white");
            }
            str += "<fieldset><legend>Combats to Resolve</legend>";
            if(Object.keys(combatRules.combatsToResolve) == 0){
                str += "there are no combats to resolve<br>";
            }
            for(i in combatRules.combatsToResolve){
                if(combatRules.combatsToResolve[i].index !== null){
                     var atk = combatRules.combatsToResolve[i].attackStrength;
                    var atkDisp = atk;;
                    if(combatRules.storm){
                        atkDisp = atk*2 + " halved for storm "+atk;
                    }
                    var def = combatRules.combatsToResolve[i].defenseStrength;
                    var ter = combatRules.combatsToResolve[i].terrainCombatEffect;
                    var idx = combatRules.combatsToResolve[i].index+ 1;
                    newLine = " Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>odds = "+Math.floor(atk/def)+" : 1<br>Terrain Shift left "+ter+ " = "+idx+" : 1<br><br>";
//                    if(combatRules.lastResolveCombat === i){
//                        lastCombat = "<strong>"+newLine+"</strong>";
//                        newLine = "";
//                    }
                    str += newLine;
                }

            }
            str += "</fieldset><fieldset><legend>Resolved Combats</legend>";
            for(i in combatRules.resolvedCombats){
                if(combatRules.resolvedCombats[i].index !== null){
                     atk = combatRules.resolvedCombats[i].attackStrength;
                     atkDisp = atk;;
                    if(combatRules.storm){
                        atkDisp = atk*2 + " halved for storm "+atk;
                    }
                    def = combatRules.resolvedCombats[i].defenseStrength;
                     ter = combatRules.resolvedCombats[i].terrainCombatEffect;
                     idx = combatRules.resolvedCombats[i].index+ 1;
                    newLine = "";
                    if(combatRules.resolvedCombats[i].Die){
                        newLine += " Die "+combatRules.resolvedCombats[i].Die + " result "+combatRules.resolvedCombats[i].combatResult+"<br>";
                    }
                    newLine += " Attack = "+atkDisp +" / Defender "+def+ " odds = " + atk/def +"<br>= "+Math.floor(atk/def)+" : 1<br>Terrain Shift left "+ter+ " = "+idx+" : 1<br><br>";
                    if(cD === i){
                        lastCombat = "<fieldset><legend>Last Resolve Combat</legend><strong>"+newLine+"</strong></fieldset>";
                        newLine = "";
                    }
                    str += newLine;
               }

            }
            str += "</fieldset>";
            $("#status").html(lastCombat+str);

        }

//        $("#status").html(str);
//            alert(attackers);

    }
    $("#crt h3").html(title);
});

x.fetch(0);

function seeMap(){
    $(".unit").css("opacity",.0);
}
function seeUnits(){
    $(".unit").css("opacity",1.);
}
function seeBoth(){
    $(".unit").css("opacity",.2);
}
function doit() {
    var mychat = $("#mychat").attr("value");
    $.ajax({url: "<?=site_url("wargame/add/");?>",
        type: "POST",
        data:{chat:mychat,
    },
    success:function(data, textstatus) {
    }
});
$("#mychat").attr("value", "");
}
function doitUnit(id) {
    var mychat = $("#mychat").attr("value");
    $.ajax({url: "<?=site_url("wargame/poke");?>/",
        type: "POST",
        data:{id:id,event : <?=SELECT_COUNTER_EVENT?>},
    success:function(data, textstatus) {
    }
});
$("#mychat").attr("value", "");
}
function doitMap(x,y) {
    $.ajax({url: "<?=site_url("wargame/poke/");?>/",
        type: "POST",
        data:{x:x,
        y:y,
            event : <?=SELECT_MAP_EVENT?>
    },
    success:function(data, textstatus) {
    }
});

}
function doitNext() {
    $.ajax({url: "<?=site_url("wargame/poke/");?>/",
        type: "POST",
        data:{event: <?=SELECT_BUTTON_EVENT?>},
        success:function(data, textstatus) {
    }
});

}




// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

// main classes for wargame
var mapData;
var force;
var terrain;
var moveRules;
var combatRules;
var gameRules;
var prompt

// map image data
var mapOffsetX;
var mapOffsetY;

// mapGrid values
// global constants
var OriginX;
var OriginY;
var TopHexagonHeight;
var BottomHexagonHeight;
var HexagonEdgeWidth;
var HexagonCenterWidth;
var MaxRightHexagon;
var MaxBottomHexagon;
// counter image values
var oneHalfImageWidth;
var oneHalfImageHeight;

var maxTurn;

// textboxes on form
var gameStatusText;
var hexagonText;
var terrainText;
var counterText;
var promptText;

// ------- initialize classes ------------------------------
//mapData = new MapData();
//force = new Force();
//terrain = new Terrain();
//moveRules = new MoveRules(force, terrain);
//combatRules = new CombatRules(force, terrain);
//gameRules = new GameRules(moveRules, combatRules, force);
//prompt = new Prompt(gameRules, moveRules, combatRules, force, terrain);
// ------- end initialize classes --------------------------

//function mapMouseMove(event) {
//
//    var mapGrid;
//    mapGrid = new MapGrid(mapData);
//    var hexagon;
//
//    var pixelX, pixelY;
//    // get pixel coordinates
//    // this for Netscape browsers
//    if ( document.addEventListener ) {
//        pixelX = event.pageX - event.target.offsetLeft;
//        pixelY = event.pageY - event.target.offsetTop;
//    }
//    // this for IE browsers
//    else {
//        pixelX =  event.offsetX;
//        pixelY =  event.offsetY;
//    }
//
//    // update form text
//    mapGrid.setPixels( pixelX, pixelY );
//
//    hexagonText.innerHTML = "&nbsp;";
//    terrainText.innerHTML = "&nbsp;";
//
//    if( terrain.terrainIs(mapGrid.hexagon, "offmap") == false)
//    {
//        hexagonText.innerHTML = mapGrid.hexagon.getName();
//        hexagonText.innerHTML += "&nbsp;";
//
//        terrainText.innerHTML = terrain.getTerrainDisplayName(mapGrid.getHexpart());
//        terrainText.innerHTML += terrain.getTownName(mapGrid.getHexagon());
//        terrainText.innerHTML +=  "&nbsp;"
//    }
//    promptText.innerHTML = prompt.getPrompt(OVER_MAP_EVENT, MAP, mapGrid.getHexagon());
//    promptText.innerHTML += "&nbsp;";
//    counterText.innerHTML = "&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;";
//}

function mapMouseDown(event) {


    var pixelX, pixelY;
    // get pixel coordinates
    // this for Netscape browsers
    if ( document.addEventListener ) {
        pixelX = event.pageX - event.target.offsetLeft;
        pixelY = event.pageY - event.target.offsetTop;
    }
    // this for IE browsers
    else {
        pixelX =  event.offsetX;
        pixelY =  event.offsetY;
    }
    var p;
    p = $("#map").offset();
    pixelX -= p.left;
    pixelY -= p.top;
//    alert("PixelX "+ pixelX+ " PixelY "+pixelY);

    doitMap(pixelX,pixelY);

}

//function mapMouseOut(event) {
//
//    // this for all browsers
//    hexagonText.innerHTML = "&nbsp;";
//    terrainText.innerHTML = "&nbsp;";
//    counterText.innerHTML = "&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;";
//    promptText.innerHTML = "&nbsp;";
//    window.defaultStatus = "";
//}

//function counterMouseMove(event) {
//
//    var id;
//
//    if (document.addEventListener) {
//        id = event.target.id.toString();
//    }
//    // this for IE browsers
//    else {
//        id = event.srcElement.id.toString();
//    }
//
//    // update form text
//    var hexagon = force.getUnitHexagon(id);
//    var hexpart = new Hexpart(hexagon.getX(), hexagon.getY());
//
//    hexagonText.innerHTML = "&nbsp;";
//    terrainText.innerHTML = "&nbsp;";
//
//    if ( terrain.isOnMap(hexagon) == true && force.units[id].status != STATUS_CAN_REINFORCE ) {
//        hexagonText.innerHTML = hexagon.getName();
//        hexagonText.innerHTML += "&nbsp;";
//
//        terrainText.innerHTML = terrain.getTerrainDisplayName(hexpart);
//
//        terrainText.innerHTML += terrain.getTownName(hexagon);
//        terrainText.innerHTML +=  "&nbsp;"
//    }
//
//    counterText.innerHTML = force.getUnitInfo( id );
//    promptText.innerHTML = prompt.getPrompt(OVER_COUNTER_EVENT, id, hexagon);
//    promptText.innerHTML += "&nbsp;";
//}

function counterMouseDown(event) {
    var id;
    if ( document.addEventListener ) {
       id = $(event.target).parent().attr("id");
    }
    // this for IE browsers
    else {
        id = event.srcElement.id.toString();
        alert("downdown");
    }
    doitUnit(id);
}

function nextPhaseMouseDown(event) {
    doitNext();

}

function attachMouseEventsToMap(objectName) {

    object = document.getElementById(objectName);
    // this for Netscape browsers
    if ( object.addEventListener ) {
//        object.addEventListener("mousemove", mapMouseMove, true);
        object.addEventListener("mousedown", mapMouseDown, true);
//        object.addEventListener("mouseout", mapMouseOut, true);
    }
    // this for IE browsers
    else {
//        object.attachEvent("onmousemove", mapMouseMove);
        object.attachEvent("onmousedown", mapMouseDown);
//        object.attachEvent("onmouseout", mapMouseOut);
    }

    mapOffsetX = parseInt(object.style.left);
    mapOffsetY = parseInt(object.style.top);

    return true;
}

function attachMouseEventsToCounter(objectName) {

    var id;
    id = parseInt(objectName, 10);
    object  = document.getElementById(objectName);

    // this for Netscape browsers
    if ( object.addEventListener ) {
//        object.addEventListener("mousemove", counterMouseMove, true);
        object.addEventListener("mousedown", counterMouseDown, true);
//        object.addEventListener("mouseout", mapMouseOut, true);
        return true;
    }
    // this for IE browsers
    else {
//        object.attachEvent("onmousemove", counterMouseMove);
        object.attachEvent("onmousedown", counterMouseDown);
//        object.attachEvent("onmouseout", mapMouseOut);
        return true;
    }
}

function attachMouseEventsToButton(objName) {

    obj = document.getElementById(objName);
    if ( obj.addEventListener != null ) {
        obj.addEventListener("mousedown", this.nextPhaseMouseDown, true);
        return true;
    }
    else {
        obj.attachEvent("onmousedown", this.nextPhaseMouseDown);
        return true;
    }
}

function moveCounter(id) {

    var mapGrid;
    mapGrid = new MapGrid(mapData);

    var counterObj;
    counterObj = document.getElementById( id );

    mapGrid.setHexagonXY( this.force.getUnitHexagon(id).getX(), this.force.getUnitHexagon(id).getY());

    var x = mapGrid.getPixelX() - (document.getElementById("map").width) - (parseInt(id) * document.getElementById(id).width) - (document.getElementById(id).width / 2);
    //x = - document.getElementById("map").width;
    //if (id == 0) alert(x);
    //x = 0;
    counterObj.style.left = x + "px";
    var y = mapGrid.getPixelY() - (document.getElementById(id).height / 2);
    //y = 0;
    counterObj.style.top = y + "px";
}

function updateForm() {
    var id;

    for ( id = 0; id < force.units.length; id++ ) {
        this.moveCounter( id );
    }
    gameStatusText.innerHTML = gameRules.getInfo();
}

function createImage(id, src, x, y)
{
    var newImage = document.createElement("img");
    newImage.setAttribute("id", id);
    newImage.setAttribute("alt", id);
    newImage.setAttribute("src", src);
    newImage.setAttribute("class", "counter");
    newImage.style.position = "relative";
    newImage.style.left = x + "px";
    newImage.style.top = y + "px";

    document.getElementById("gameImages").appendChild(newImage);
}

function initialize() {

    // setup events --------------------------------------------
    this.attachMouseEventsToMap("map");

    var id;
    for(id = 0;id < 39;id++){
        this.attachMouseEventsToCounter(id);
    }
//    for ( id = 0; id < force.units.length; id++ ) {
//        createImage( id, force.units[id].image, 0, 0 );
//        this.attachMouseEventsToCounter( id );
//    }

    attachMouseEventsToButton("nextPhaseButton");
    // end setup events ----------------------------------------

    // for web browsers that have addEventListener
    //if ( document.addEventListener ) {
    gameStatusText = document.getElementById("gameStatusText");
    hexagonText = document.getElementById("hexagonText");
    terrainText = document.getElementById("terrainText");
    counterText = document.getElementById("counterText");
    promptText = document.getElementById("promptText");
    //}

    counterText.innerHTML = "counter&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;";

    //mapText.innerHTML = this.terrain.getTerrainList();

    updateForm();
}
$(function(){initialize();});
</script>