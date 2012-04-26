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
//            alert(gameRules.turn);
    turn = gameRules.turn;
    var pix = turn  + (turn - 1) * 36 + 1;
    $("#turnCounter").css("left",pix+"px");
    if(gameRules.attackingForceId == 1){
        $("#turnCounter").css("background","#9ff");
    }else{
        $("#turnCounter").css("background","rgb(255,204,153)");

    }
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
    $("#clock").html(clock);
});
x.register("mapUnits", function(mapUnits) {
    var str;
    for (i in mapUnits) {
        width = $("#"+i).width();
        height = $("#"+i).height();
        $("#"+i).css({left: -1+mapUnits[i].x-width/2+"px",top:-1+mapUnits[i].y-height/2+"px"});
    }
});
x.register("moveRules", function(moveRules) {
    var str;
    $("#status").html("");
    if(moveRules.anyUnitIsMoving){
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

    for (i in units) {
        color = "transparent";
        switch(units[i].status){
            case 1:
            case 2:
                if(units[i].forceId === force.attackingForceId){

                    color = "green";
                }
                break;
            case 3:
            case 4:
                color = "orange";
                break;
            case 6:
                color = "black";
                break;
//                case 8:
            case 9:
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
        }
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
    str = ""
    if(combatRules ){
        cD = combatRules.currentDefender;
        if(cD !== false){
            if(combatRules.combats){

                $("#"+cD).css({borderColor: "#333"});
//                    $("#"+cD+"").animate({borderColor: "#333"}, 1400).animate({borderColor: "white"}, 1400);

//                   this.animate =self.setInterval(function(){
//                           this.animateid = cD;
//                            $("#"+cD+"").animate({borderColor: "#333"}, 1400).animate({borderColor: "white"}, 1400);
//
//                        }
//
//                        ,3000);

//                $("#"+cD).everyTime(3,function(){
//                        alert("hi");
//                    }
//                );
                if(Object.keys(combatRules.combats[cD].attackers).length != 0){
                    combatCol = combatRules.combats[cD].index + 1;
                    $(".col"+combatCol).css('background-color',"rgba(255,255,1,.6)");
                    if(combatRules.combats[cD].Die !== false){
                        $(".row"+combatRules.combats[cD].Die+" .col"+combatCol).css('font-size',"110%");
                        $(".row"+combatRules.combats[cD].Die+" .col"+combatCol).css('background',"#eee");
                    }

//                $(".odd .col"+combatCol).css('color',"white");
//                $(".even .col"+combatCol).css('color',"black");
                    for(i in combatRules.combats){
                        if(combatRules.combats[i].Die){
                            str += " Die "+combatRules.combats[i].Die + " result "+combatRules.combats[i].combatResult;
                        }
                        if(combatRules.combats[i].index !== null){
                            str += "Defendeer "+i+" A "+combatRules.combats[i].attackStrength+" - D "+combatRules.combats[i].defenseStrength+ " - T "+combatRules.combats[i].terrainCombatEffect+ " = "+combatRules.combats[i].index;
                            str += "<br>";
                        }

                    }
                    attackers = combatRules.combats[cD].attackers;
                    for(var i in attackers){
                        $("#"+i).css({borderColor: "crimson"});

                    }
                }

            }
        }
        if(combatRules.combatsToResolve){
            if(combatRules.lastResolvedCombat){
                title += "<strong style='margin-left:20px;font-size:150%'>"+combatRules.lastResolvedCombat.Die+" "+combatRules.lastResolvedCombat.combatResult+"</strong>";
                combatCol = combatRules.lastResolvedCombat.index + 1;
                combatRoll = combatRules.lastResolvedCombat.Die;
                $(".col"+combatCol).css('background-color',"rgba(255,255,1,.6)");
                $(".row"+combatRoll+" .col"+combatCol).css('background-color',"cyan");
//                    $(".row"+combatRoll+" .col"+combatCol).css('color',"white");
            }
            str += "Combats to Resolve<br>";
            if(Object.keys(combatRules.combatsToResolve) == 0){
                str += "there are no combats to resolve<br>";
            }
            for(i in combatRules.combatsToResolve){
                if(combatRules.combatsToResolve[i].Die){
                    str += " Die "+combatRules.combatsToResolve[i].Die + " result "+combatRules.combatsToResolve[i].combatResult;
                }
                if(combatRules.combatsToResolve[i].index !== null){
                    str += "Defendeer "+i+" A "+combatRules.combatsToResolve[i].attackStrength+" - D "+combatRules.combatsToResolve[i].defenseStrength+ " - T "+combatRules.combatsToResolve[i].terrainCombatEffect+ " = "+combatRules.combatsToResolve[i].index;
                    str += "<br>";
                }

            }
            str += "Resolved Combats<br>";
            for(i in combatRules.resolvedCombats){
                if(combatRules.resolvedCombats[i].Die){
                    str += " Die "+combatRules.resolvedCombats[i].Die + " result "+combatRules.resolvedCombats[i].combatResult;
                }
                if(combatRules.resolvedCombats[i].index !== null){
                    str += "Defendeer "+i+" A "+combatRules.resolvedCombats[i].attackStrength+" - D "+combatRules.resolvedCombats[i].defenseStrength+ " - T "+combatRules.resolvedCombats[i].terrainCombatEffect+ " = "+combatRules.resolvedCombats[i].index;
                    str += "<br>";
                }

            }
        }

        $("#status").html(str);
//            alert(attackers);

    }
    $("#crt h3").html(title);
});

x.fetch(0);

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
        id = event.target.id.toString();
    }
    // this for IE browsers
    else {
        id = event.srcElement.id.toString();
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
    for(id = 0;id < 6;id++){
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