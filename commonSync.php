<script>
    /*
     Copyright 2012-2015 David Rodal

     This program is free software; you can redistribute it
     and/or modify it under the terms of the GNU General Public License
     as published by the Free Software Foundation;
     either version 2 of the License, or (at your option) any later version->

     This program is distributed in the hope that it will be useful,
     but WITHOUT ANY WARRANTY; without even the implied warranty of
     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     GNU General Public License for more details.

     You should have received a copy of the GNU General Public License
     along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */
    x = new Sync("<?=site_url("wargame/fetch/");?>");
x.register("sentBreadcrumbs", function(breadcrumbs,data) {
//    return;

    $('svg g').remove();
    var lastUnit = '';
    var lastMoves = '';
    var combatBreadcrumbs = [];
    for(var unitId in breadcrumbs){
        var g = $('svg').append('<g class="unitPath'+unitId+'">');
        for(var moves in breadcrumbs[unitId]){
            if(breadcrumbs[unitId][moves].type == "move" || breadcrumbs[unitId].fromHex){
                var path = "";
                if(breadcrumbs[unitId][moves-0+1]){
                    path += "<path stroke-width='15'";
                }else{
                    path += "<path marker-end='url(#head)' stroke-width='15'";
                }
                if(typeof breadcrumbs[unitId][moves].fromX == "undefined"){
                    continue;
                }
                var d = 'M'+breadcrumbs[unitId][moves].fromX+','+breadcrumbs[unitId][moves].fromY;
                d += ' L'+breadcrumbs[unitId][moves].toX+','+breadcrumbs[unitId][moves].toY;
                path += ' d="' + d + '"/>';
                var circle = '<circle cx="'+breadcrumbs[unitId][moves].toX+'" cy="'+breadcrumbs[unitId][moves].toY+'" r="7"/>';
                $('g.unitPath'+unitId).append(path);
                $('g.unitPath'+unitId).append(circle);
                lastMoves = moves;
            }
            if(breadcrumbs[unitId][moves].type == "combatResult"){
                var x = breadcrumbs[unitId][moves].hexX - 0;
                x -= 8;
                var y = breadcrumbs[unitId][moves].hexY - 0;
                var circle = '<circle cx="'+breadcrumbs[unitId][moves].hexX+'" cy="'+breadcrumbs[unitId][moves].hexY+'" r="20" stroke-width="5" fill="white"/>';
                var text = '<text x="'+x+'" y="'+y+'" font-family="sans-serif" font-size="12px" stroke="black" fill="black">'+breadcrumbs[unitId][moves].result+'</text>';
                y += 10;
                x += 4;
                text += '<text x="'+x+'" y="'+y+'" font-family="sans-serif" font-size="12px" stroke="black" fill="black">'+breadcrumbs[unitId][moves].dieRoll+'</text>';
                combatBreadcrumbs.push(circle);
                combatBreadcrumbs.push(text);
            }


        }

    }
    for(var i in combatBreadcrumbs){
        $('g.unitPath'+unitId).append(combatBreadcrumbs[i]);
    }

    var svgHtml = $('#svgWrapper').html();
    $('#svgWrapper').html(svgHtml);
});

x.register("mapUnits", function(mapUnits, data) {
    var str;
    var fudge;
    var x,y;
    var beforeDeploy = $("#deployBox").children().size();
    DR.stackModel = {};
    DR.stackModel.ids = {};

    var phasingForceId = data.gameRules.attackingForceId;

    var phasingUnitsLeft = 0;

    for (i in mapUnits) {
        if(typeof mapUnits[i].parent == "undefined"){
            $('#'+i).hide();
            continue;
        }else{
            $('#'+i).css("display","");
        }
        if(mapUnits[i].forceId === phasingForceId && mapUnits[i].parent === "deployBox"){
            phasingUnitsLeft++;
        }
        width = $("#"+i).width();
        height = $("#"+i).height();
        x =  mapUnits[i].x;
        y = mapUnits[i].y;
        if(DR.stackModel[x] === undefined){
            DR.stackModel[x] = {};
        }
        if(DR.stackModel[x][y] === undefined){
            DR.stackModel[x][y] = {count:0,ids: {}};
        }
        fudge = 0;
        if(DR.stackModel[x][y].count){
            fudge = DR.stackModel[x][y].count * 4;
        }
        DR.stackModel[x][y].count++;
        var zIndex = DR.stackModel[x][y].count;
        /* really looking at the keys so the value can be the same */
        DR.stackModel[x][y].ids[i] = i;
        DR.stackModel.ids[i] = {x: x, y: y};

        if(mapUnits[i].parent != $("#"+i).parent().attr("id")){
            $("#"+i).appendTo($("#"+mapUnits[i].parent));
            if(mapUnits[i].parent != "gameImages"){
                $("#"+ i).css({top:"0"});
                $("#"+ i).css({left:"0"});
                if(!mapUnits[i].parent.match(/^gameTurn/)){
                    $("#"+ i).css({float:"left"});
                }
                $("#"+ i).css({position:"relative"});
            }  else{
                $("#"+ i).css({float:"none"});
                $("#"+ i).css({position:"absolute"});

            }
        }
        width += 6;
        height += 6;
        if(mapUnits[i].parent == "gameImages"){

            $("#"+i).css({left:mapUnits[i].x-width/2-fudge+"px",top:mapUnits[i].y-height/2-fudge+"px", zIndex: zIndex});
        }
        var img = $("#"+i+" img").attr("src");

        if(mapUnits[i].isReduced){
            img = img.replace(/(.*[0-9])(\.png)/,"$1reduced.png");
        }else{
            img = img.replace(/([0-9])reduced\.png/,"$1.png");
        }
        var  move = mapUnits[i].maxMove - mapUnits[i].moveAmountUsed;
        move = move.toFixed(2);
        move = move.replace(/\.00$/,'');
        move = move.replace(/(\.[1-9])0$/,'$1');
        var str = mapUnits[i].strength;
        var reduced = mapUnits[i].isReduced;
        var reduceDisp = "<span>";
        if(reduced){
            reduceDisp = "<span class='reduced'>";
        }
        var symb = mapUnits[i].supplied !== false ? " - " : " <span class='reduced'>u</span> ";
//        symb = "-"+mapUnits[i].defStrength+"-";
        var html = reduceDisp + str + symb + move + "</span>";
        html = renderUnitNumbers(mapUnits[i]);
        if(html){
            $("#"+i+" .unit-numbers").html(html);
        }
        var len  = $("#"+i+" .unit-numbers").text().length;
        $("#"+i+" div.unit-numbers span ").addClass("infoLen"+len);
        $("#"+i+" .counterWrapper .guard-unit ").addClass("infoLen"+len);
        $("#"+i).attr("src",img);
    }
    var dpBox = $("#deployBox").children().size();
    if(dpBox != beforeDeploy){
        fixHeader();
        beforeDeploy = dpBox;

    }
    if((dpBox == 0 || (phasingUnitsLeft === 0 && data.gameRules.mode !== <?= DEPLOY_MODE?>)) && $("#deployBox").is(":visible")){
        $("#deployWrapper").hide({effect:"blind",direction:"up",complete:fixHeader});
    }

});
function renderUnitNumbers(unit, moveAmount){

    var  move = unit.maxMove - unit.moveAmountUsed;
    if(moveAmount !== undefined){
        move = moveAmount-0;
    }
    move = move.toFixed(2);
    move = move.replace(/\.00$/,'');
    move = move.replace(/(\.[1-9])0$/,'$1');
    var str = unit.strength;
    var reduced = unit.isReduced;
    var reduceDisp = "<span class='unit-info'>";
    if(reduced){
        reduceDisp = "<span class='unit-info reduced'>";
    }
    var symb = unit.supplied !== false ? " - " : " <span class='reduced'>u</span> ";
//        symb = "-"+unit.defStrength+"-";
    var html = reduceDisp + str + symb + move + "</span>";
    return html;



}

    function renderCrtDetails(combat){
        var atk = combat.attackStrength;
        var def = combat.defenseStrength;
        var div = atk / def;
        var ter = combat.terrainCombatEffect;
        var combatCol = combat.index + 1;

        var html = "<div id='crtDetails'>"+combat.combatLog+"</div><div>Attack = " + atk + " / Defender " + def + " = " + div + "<br>Combined Arms Shift  " + ter + " = " + $(".col" + combatCol).html() + "</div>"
        /*+ atk + " - Defender " + def + " = " + diff + "</div>";*/
        return html;
    }

x.register("force", function(force,data) {

    var units = force.units;

    var status = "";
    var boxShadow;
    var shadow;
    $("#floatMessage").hide();
    var showStatus = false;
    var totalAttackers = 0;
    var totalDefenders = 0;
    for (i in units) {
        color = "#ccc #666 #666 #ccc";
        style = "solid";
        $("#"+i + " .arrow").css({opacity: "0.0"});
        $("#"+i+" .arrowClone").remove();
        boxShadow = "none";
        shadow = true;
        if(units[i].forceId !== force.attackingForceId){
            shadow = false;
        }
        if(units[i].forceMarch){
            $("#"+i+" .forceMarch").show();
            $("#"+i+" .range").hide();
        }else{
            $("#"+i+" .forceMarch").hide();
            $("#"+i+" .range").show();
        }
        if(force.requiredDefenses[i] === true){

            color = "black";
            style = "dotted";
            totalDefenders++;
        }
        switch(units[i].status){
            case <?=STATUS_CAN_REINFORCE?>:
            case <?=STATUS_CAN_DEPLOY?>:
                color = "#ccc #666 #666 #ccc";
                shadow = false;
                if(units[i].reinforceTurn){
                    shadow = true;
                }
                break;
            case <?=STATUS_READY?>:
                if(units[i].forceId === force.attackingForceId){
                    $("#"+i + " .arrow").css({opacity: "0.0"});

                    shadow = false;
                }else{
                }
                if(force.requiredAttacks[i] === true){
                    color =  "black";
                    style = "dotted";
                    totalAttackers++;
                }
                break;
            case <?=STATUS_REINFORCING?>:
            case <?=STATUS_DEPLOYING?>:
                shadow = false;
                boxShadow = '5px 5px 5px #333';


                break;
            case <?=STATUS_MOVING?>:
                if(units[i].forceMarch){
                    $("#"+i+" .forceMarch").show();
                    $("#"+i+" .range").hide();

                    color = "#f00 #666 #666 #f00";
                }else{
                    $("#"+i+" .forceMarch").hide();
                    $("#"+i+" .range").show();

                    color = "#ccc #666 #666 #ccc";

                }
                $("#"+i).css({zIndex:4});
                color = "lightgreen";
                shadow = false;
                DR.lastMoved = i;
                break;

            case <?=STATUS_STOPPED?>:
                if(i === DR.lastMoved){
                    $("#"+i).css({zIndex:4});
                }
                color = "#ccc #666 #666 #ccc";
                break;
            case <?=STATUS_DEFENDING?>:
                color = "orange";

                break;
            case <?=STATUS_BOMBARDING?>:

            case <?=STATUS_ATTACKING?>:

                shadow = false;
                break;

            case <?=STATUS_CAN_RETREAT?>:
                if(data.gameRules.mode == <?=RETREATING_MODE?>){
                    status = "Click on the Purple Unit to start retreating";
                }
                color = "purple";
                break;
            case <?=STATUS_RETREATING?>:
                color = "yellow";
                if(data.gameRules.mode == <?=RETREATING_MODE?>){

                    status = "Now click on a green unit. The yellow unit will retreat there. ";
                }
                break;
            case <?=STATUS_CAN_ADVANCE?>:
                if(data.gameRules.mode == <?=ADVANCING_MODE?>){
                    status = 'Click on one of the black units to advance it.';
                }
                color = "black";
                shadow = false;

                break;
            case <?=STATUS_ADVANCING?>:
                if(data.gameRules.mode == <?=ADVANCING_MODE?>){

                    status = 'Now click on one of the turquoise units to advance or stay put..';
                }

                shadow = false;
                color = "cyan";
                break;
            case <?=STATUS_CAN_EXCHANGE?>:
                if(data.gameRules.mode == <?=EXCHANGING_MODE?>){
                    var result = data.combatRules.lastResolvedCombat.combatResult;
//                    $("#floatMessage header").html(result+' Exchanging Mode');
                    status = "Click on one of the red units to reduce it."
                }
            case <?=STATUS_CAN_ATTACK_LOSE?>:
                if(data.gameRules.mode == <?=ATTACKER_LOSING_MODE?>){
                    status = "Click on one of the red units to reduce it."
                }
                color = "red";
                break;
            case <?=STATUS_REPLACED?>:
                color = "blue";
                break;
            case <?=STATUS_CAN_REPLACE?>:
                color = "orange";
                break;
            case <?=STATUS_CAN_UPGRADE?>:
            case <?=STATUS_ELIMINATED?>:
                if(units[i].forceId === force.attackingForceId){
                    shadow = false;
                    color = "turquoise";
                }
                break;


        }
        if(status){
            showStatus = true;

            var x = $("#"+i).position().left;
            var y = $("#"+i).position().top;
            y /= DR.globalZoom;
            x /= DR.globalZoom;

            var mapWidth = $("body").width();
            var mapHeight = $("#gameViewer").height() / DR.globalZoom;


            var mapOffset  = $("#gameImages").position().top;

            if(mapOffset === "auto"){
                mapOffset = 0;
            }
            var moveAmt;

            if(mapOffset + y > 2*mapHeight/3){
                moveAmt = (100 + (mapOffset + y)/3);
                if(moveAmt > 250){
                    moveAmt = 250;
                }
                y -= moveAmt;


            }else{
                moveAmt = (mapHeight - (mapOffset + y ))/2;
                if(moveAmt > 200){
                    moveAmt = 200;
                }
                y += moveAmt;
            }

            if(DR.floatMessageDragged != true){
                DR.$floatMessagePanZoom.panzoom('reset');
                $("#floatMessage").css('top',y+"px");
                $("#floatMessage").css('left',x+"px");
            }
            $("#floatMessage").show();
            $("#floatMessage p").html(status);
            status = "";
        }

        $("#"+i).css({borderColor: color,borderStyle:style});
        if(shadow){
            $("#"+i+" .shadow-mask").addClass("shadowy");
        }else{
            $("#"+i+" .shadow-mask").removeClass("shadowy");
        }
        if(units[i].isDisrupted){
            if(units[i].isDisrupted == 17){
                $("#"+i+" .shadow-mask").addClass("red-shadowy").html(DR.playerOne);
            }else{
                $("#"+i+" .shadow-mask").addClass("red-shadowy").html(DR.playerTwo);
            }
        }else{
            $("#"+i+" .shadow-mask").removeClass("red-shadowy").html('');
        }
        $("#"+i).css({boxShadow: boxShadow});



    }
    if(totalAttackers || totalDefenders){
        $("#requiredCombats").html(totalAttackers+" attackers "+totalDefenders+" defenders required");
    }else{
        $("#requiredCombats").html('');
    }

    if(!showStatus){
        DR.floatMessageDragged = false;
    }

});
x.register("chats", function(chats) {
    var str;
    for (i in chats) {
        str = "<li>" + chats[i] + "</li>";
        str = str.replace(/:/,":<span>");
        str = str.replace(/$/,"</span>");
        $("#chats").prepend(str);
    }
});
x.register("phaseClicks",function(clicks, data){
    var str = "";
    var phaseClickNames = data.gameRules.phaseClickNames;
    if(x.timeTravel){
        clicks = DR.clicks;
        phaseClickNames = DR.phaseClickNames;
    }else{
        DR.phaseClickNames = phaseClickNames;
        DR.clicks = clicks;
        DR.maxClick = data.click;
        DR.playTurnClicks = data.gameRules.playTurnClicks;
    }
    var maxClick = DR.maxClick;

    var i;
    num = clicks.length;
    var ticker;
    ticker = clicks[0];
    var q = 0;
    for(i = 0;i < num;i++){
        str += '<div class="newPhase"><a class="phaseClick" data-click="'+ticker+'">';
        if(data.gameRules.phaseClickNames){
            str += phaseClickNames[q++];
            str += '</a><br><div class="newTick tickShim"></div>'   ;

        }
        if(i+1 < num){
            while(ticker < clicks[i+1]){
                str += '<div class="newTick" data-click="'+ticker+'"><a class="phaseClick" data-click="'+ticker+'">'+ticker+'</a></div>';
                ticker++;
            }
        }else{
            while(ticker <= maxClick){
                str += '<div class="newTick" data-click="'+ticker+'"><a class="phaseClick" data-click="'+ticker+'">'+ticker+'</a></div>';
                ticker++;
            }
            if(x.timeTravel){
                str += '<div class="newTick"><a class="phaseClick realtime" >realtime</a></div>';
            }
        }
        str += '</div>';

    }
    $("#phaseClicks").html(str);
    var click = data.click;
    if(x.timeTravel){
        $(".newTick[data-click='"+click+"']").addClass('activeTick');
    }
});
x.register("click",function(click){
    if(x.timeTravel){
        $("#clickCnt").html('time travel ' + click);
    }else{
        $("#clickCnt").html('realtime '+ click);
    }
    DR.currentClick = click;
});
x.register("users", function(users) {
    var str;
    $("#users").html("");
    for (i in users) {
        str = "<li>" + users[i] + "</li>";
        $("#users").append(str);
    }
});
x.register("gameRules", function(gameRules,data) {
    $(".dynamicButton").hide();
    if(gameRules.mode === <?= MOVING_MODE?>){
        $(".movementButton").show();
    }
    if(gameRules.mode === <?= COMBAT_SETUP_MODE?>){
        $(".combatButton").show();
    }
    if(gameRules.display) {
        if(gameRules.display.currentMessage){
            $("#display").html(gameRules.display.currentMessage+"<button onclick='doitNext()'>Next</button>").show();
        }else{
            $("#display").html("").hide();
        }
    }
    var status = "";
    var turn = gameRules.turn;
    var maxTurn = gameRules.maxTurn
    if("gameTurn"+turn != $("#turnCounter").parent().attr("id")){
        $("#gameTurn"+turn).prepend($("#turnCounter"));
    }

    var pix = turn  + (turn - 1) * 36 + 1;
    var playerName = "player"+DR.playerNameMap[gameRules.attackingForceId]+" player"+DR.players[gameRules.attackingForceId];
    var removeThese = "playerOne playerTwo playerThree playerFour";
    $("#header").removeClass().addClass(playerName);
    $("#turnCounter").css("background","rgb(0,128,0)");
    $("#turnCounter").css("color","white");

    $("#crt").removeClass(removeThese).addClass(playerName);
    $(".row1,.row3,.row5").removeClass(removeThese).addClass(playerName);

//    if(gameRules.attackingForceId == 1){
//        $("#header").removeClass(DR.playerTwo).addClass(DR.playerOne).removeClass('playerTwo').addClass('playerOne');
//        $("#turnCounter").css("background","rgb(0,128,0)");
//        $("#turnCounter").css("color","white");
//        $("#crt").removeClass(DR.playerTwo).addClass(DR.playerOne).removeClass('playerTwo').addClass('playerOne');
//        $(".row1,.row3,.row5").removeClass(DR.playerTwo).addClass(DR.playerOne).removeClass('playerTwo').addClass('playerOne');
//    }else{
//        $("#header").removeClass(DR.playerOne).removeClass('playerOne').addClass(DR.playerTwo).addClass('playerTwo');
//        $(".row1,.row3,.row5").removeClass(DR.playerOne).removeClass('playerOne').addClass(DR.playerTwo).addClass('playerTwo');
//        $("#crt").removeClass(DR.playerOne).removeClass('playerOne').addClass(DR.playerTwo).addClass('playerTwo');
//        $("#turnCounter").css("background","rgb(0,128,255)");
//        $("#turnCounter").css("color","white");
//
//    }

    var html = "<span id='turn'>Turn "+turn+" of "+maxTurn+"</span> ";
    var phase = gameRules.phase_name[gameRules.phase];
    phase = phase.replace(/fNameOne/,DR.playerOne);
    phase = phase.replace(/playerOneFace/,"player"+DR.playerOne+"Face");
    phase = phase.replace(/playerTwoFace/,"player"+DR.playerTwo+"Face");

    phase = phase.replace(/fNameTwo/,DR.playerTwo);
    phase = phase.replace(/fNameThree/,DR.playerThree);
    phase = phase.replace(/fNameFour/,DR.playerFour);
    html += "<span id='phase'>"+phase;
    if(gameRules.mode_name[gameRules.mode]){
        html += " "+gameRules.mode_name[gameRules.mode];
    }
    html += "</span>";

    switch(gameRules.phase){
        case <?=BLUE_REPLACEMENT_PHASE?>:
        case <?=RED_REPLACEMENT_PHASE?>:
        case <?=TEAL_REPLACEMENT_PHASE?>:
        case <?=PURPLE_REPLACEMENT_PHASE?>:
            if(gameRules.replacementsAvail !== false && gameRules.replacementsAvail != null){
                status = "There are "+gameRules.replacementsAvail+" available";
            }
            break;
    }
    switch(gameRules.mode){
        case <?=EXCHANGING_MODE?>:
            var result = data.combatRules.lastResolvedCombat.combatResult;

            $("#floatMessage header").html(result+": Exchanging Mode");

        case <?=ATTACKER_LOSING_MODE?>:
            var result = data.combatRules.lastResolvedCombat.combatResult;

            $("#floatMessage header").html(result+": Attacker Loss Mode.");
            var floatStat = $("#floatMessage p").html();

            floatStat = "Lose at least "+data.force.exchangeAmount+ " strength points<br>" + floatStat;
           $("#floatMessage p").html(floatStat);

//            html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
            break;
        case <?=ADVANCING_MODE?>:
//            html += "<br>Click on one of the black units to advance it.<br>then  click on a hex to advance, or the unit to stay put.";
            var result = data.combatRules.lastResolvedCombat.combatResult;

            $("#floatMessage header").html(result+": Advancing Mode");
            break;
        case <?=RETREATING_MODE?>:
            var result = data.combatRules.lastResolvedCombat.combatResult;

            $("#floatMessage header").html(result+": Retreating Mode");
            break;
    }
    $("#topStatus").html(html);
    if(status){
        $("#status").html(status);
        $("#status").show();

    }else{
        $("#status").html(status);
        $("#status").hide();

    }
});
x.register("vp", function(vp, data){

    var p1 = 'player'+DR.playerOne+'Face';
    var p2 = 'player'+DR.playerTwo+'Face';

    $("#victory").html(" Victory: <span class='playerOneFace "+p1+"'>"+DR.playerOne+" </span>"+vp[1]+ " <span class='playerTwoFace "+p2+"'>"+DR.playerTwo+" </span>"+vp[2]+"");
    if (typeof victoryExtend === 'function') {
        victoryExtend(vp,data);
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

var flashMessages;
x.register("flashMessages",function(messages,data){

    flashMessages = messages;
    flashMessage(data.gameRules.playerStatus);
});

function flashMessage(playerStatus){
    var x = 100;
    var y = 200;
    fixHeader();
    var mess = flashMessages.shift();
//    var mess = flashMessages.shift();
    $("#FlashMessage").remove();
    var fadeOut = 2800;
    while(mess){

        if(mess.match(/^@/)){
            if(mess.match(/^@show/)){
                game = mess.match(/^@show ([^,]*)/);
                id = game[1];
                $("#"+id).show({effect:"blind",direction:"up",complete:flashMessage});
                return;
            }
            if(mess.match(/^@hide/)){
                game = mess.match(/^@hide ([^,]*)/);
                id = game[1];
                $("#"+id).hide({effect:"blind",direction:"up",complete:flashMessage});
                return;
            }
            if(mess.match(/^@gameover/)){
                $("#gameViewer").append('<div id="FlashMessage" style="top:'+y+'px;left:'+x+'px;" class="flashMessage">'+"Game Over"+'</div>');
                $("#FlashMessage").animate({opacity:0},fadeOut,flashMessage);


                return;
            }
        }
        $("body").append('<div id="FlashMessage" style="top:'+y+'px;left:'+x+'px;" class="flashMessage">'+mess+'</div>');
        $("#FlashMessage").animate({opacity:0},fadeOut,flashMessage);
        return;
    }
}
x.register("specialHexes", function(specialHexes, data) {
    var lab = ['unowned','<?=strtolower($force_name[1])?>','<?=strtolower($force_name[2])?>','<?=strtolower($force_name[3])?>','<?=strtolower($force_name[4])?>'];
    for(var i in specialHexes){
        var newHtml = lab[specialHexes[i]];
        var curHtml = $("#special"+i).html();

        if(true || newHtml != curHtml){
            var hexPos = i.replace(/\.\d*/g,'');
            var x = hexPos.match(/x(\d*)y/)[1];
            var y = hexPos.match(/y(\d*)\D*/)[1];
            $("#special"+hexPos).remove();
            if(data.specialHexesChanges[i]){
                $("#gameImages").append('<div id="special'+hexPos+'" style="border-radius:30px;border:10px solid black;top:'+y+'px;left:'+x+'px;font-size:205px;z-index:1000;" class="'+lab[specialHexes[i]]+' specialHexes">'+lab[specialHexes[i]]+'</div>');
                $('#special'+hexPos).animate({fontSize:"16px",zIndex:0,borderWidth:"0px",borderRadius:"0px"},1900,function(){
                    var id = $(this).attr('id');
                    id = id.replace(/special/,'');


                    if(data.specialHexesVictory[id]){
                        var hexPos = id.replace(/\.\d*/g,'');

                        var x = hexPos.match(/x(\d*)y/)[1];
                        var y = hexPos.match(/y(\d*)\D*/)[1];
                        var newVp = $('<div style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').insertAfter('#special'+i);
                        $(newVp).animate({top:y-30,opacity:0.0},1900,function(){
                            $(this).remove();
                        });
                    }
                });

            }else{
                $("#gameImages").append('<div id="special'+i+'" class="specialHexes">'+lab[specialHexes[i]]+'</div>');
                $("#special"+i).css({top:y+"px", left:x+"px"}).addClass(lab[specialHexes[i]]);

            }

        }
    }
    for(var i in data.specialHexesVictory)
    {
        if(data.specialHexesChanges[i]){
            continue;
        }
        var id = i;
        var hexPos = id.replace(/\.\d*/g,'');
        var x = hexPos.match(/x(\d*)y/)[1];
        var y = hexPos.match(/y(\d*)\D*/)[1];
        var newVp = $('<div style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').appendTo('#gameImages');
        $(newVp).animate({top:y-30,opacity:0.0},1900,function(){
            $(this).remove();
        });
    }


});
x.register("moveRules", function(moveRules,data) {
    var str;
    $(".clone").remove();
    if(moveRules.movingUnitId >= 0){
        if(moveRules.hexPath){
            id = moveRules.movingUnitId;
            for( i in moveRules.hexPath){
                newId = id+"Hex"+i;

                $("#"+id).clone(true).attr('id',newId).appendTo('#gameImages');
                $("#"+newId+" .arrow").hide();
                $("#"+newId).addClass("clone");
                $("#"+newId).css("top",20);
                width = $("#"+newId).width();
                height = $("#"+newId).height();

                $("#"+newId).css("left",moveRules.hexPath[i].pixX - width/2 +"px");
                $("#"+newId).css("top",moveRules.hexPath[i].pixY - height/2 +"px");
                $("#"+newId+" div.unit-numbers span").html("24 - "+moveRules.hexPath[i].pointsLeft );
                $("#"+newId).css("opacity",.9);
                $("#"+newId).css("z-index",101);


            }
        }
        var opacity = .4;
        var borderColor = "#ccc #333 #333 #ccc";
        if(moveRules.moves){
            id = moveRules.movingUnitId;
            newId = "firstclone";
            width = $("#"+id).width();
            height = $("#"+id).height();

            var MYCLONE = $("#"+id).clone(true).detach();
            console.log($('MYCLONE').css('display'));
            MYCLONE.find(".arrow").hide();
            MYCLONE.addClass("clone");
            MYCLONE.find('.shadow-mask').css({backgroundColor:'transparent'});
            MYCLONE.hover(function(){
                    if(opacity != 1){
                        $(this).css("border-color","#fff");
                    }
                    $(this).css("opacity",1.0).css('box-shadow','#333 5px 5px 5px');
                    var path = $(this).attr("path");
                    var pathes = path.split(",");
                    for(i in pathes){
                        $("#"+id+"Hex"+pathes[i]).css("opacity",1.0).css("border-color","#fff").css('box-shadow','#333 5px 5px 5px');
                        $("#"+id+"Hex"+pathes[i]+".occupied").css("display","block");

                    }
                },
                function(){
                    if(opacity != 1){
                        $(this).css("border-color","#ccc #333 #333 #ccc");
                    }
                    $(this).css("opacity",opacity).css('box-shadow','none');
                    var path = $(this).attr("path");
                    var pathes = path.split(",");
                    for(i in pathes){
                        $("#"+id+"Hex"+pathes[i]).css("opacity",.4).css("border-color","#ccc #333 #333 #ccc").css('box-shadow','none');
                        $("#"+id+"Hex"+pathes[i]+".occupied").css("display","none");

                    }

                });

            var label = MYCLONE.find("div.unit-numbers span").html();
            if(data.gameRules.phase == <?=RED_COMBAT_PHASE;?> || data.gameRules.phase == <?=BLUE_COMBAT_PHASE;?> || data.gameRules.phase == <?=TEAL_COMBAT_PHASE;?> || data.gameRules.phase == <?=PURPLE_COMBAT_PHASE;?>){
                if(data.gameRules.mode == <?=ADVANCING_MODE;?>){
                    var unit = moveRules.movingUnitId;

                    thetas = data.combatRules.resolvedCombats[data.combatRules.currentDefender].thetas[unit]
                    for(k in thetas){
                        $("#"+unit+ " .arrow").clone().addClass('arrowClone').addClass('arrow'+k).insertAfter("#"+unit+ " .arrow").removeClass('arrow');
                        theta = thetas[k];
                        theta *= 15;
                        theta += 180;
                        $("#"+unit+ " .arrow"+k).css({opacity: "1.0"});
                        $("#"+unit+ " .arrow"+k).css({webkitTransform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                        $("#"+unit+ " .arrow"+k).css({transform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                    }
                }
                opacity = 1.;
                borderColor = "turquoise";
            }
           MYCLONE.css({opacity:opacity,
                zIndex:102,
                borderColor:borderColor,
                boxShadow:"none",
                position:"absolute"}
            );
            var diff = 0;
            var counter = 0;
            for( i in moveRules.moves){
                counter++;
                newId = id+"Hex"+i;

                var secondGenClone = MYCLONE.clone(true).attr(
                    {
                        id:newId,
                        path:moveRules.moves[i].pathToHere
                    }
                );

//                var newLabel = label.replace(/((?:<span[^>]*>)?[-+ru](?:<\/span>)?).*/,"$1 "+moveRules.moves[i].pointsLeft);
                var newLabel = renderUnitNumbers(data.mapUnits[id], moveRules.moves[i].pointsLeft, moveRules.moves[i], secondGenClone);
                var txt = secondGenClone.find('div.unit-numbers .unit-info').html(newLabel).text();
                secondGenClone.find('div.unit-numbers .unit-info').addClass('infoLen'+txt.length);
                secondGenClone.find('.counterWrapper .guard-unit').addClass('infoLen'+newLabel.length);
                if(moveRules.moves[i].isOccupied){
                    secondGenClone.addClass("occupied");
                    secondGenClone.css('display')


                }
                /* left and top need to be set after appendTo() */

                secondGenClone.appendTo('#gameImages').css({left:moveRules.moves[i].pixX - width/2 +"px",top:moveRules.moves[i].pixY - height/2 +"px"});
                /* apparently cloning attaches the mouse events */
            }

            $("#firstclone").remove();
        }

    }
});
    var chattyCrt = false;
    var cD; /* object oriented! */

    x.register("combatRules", function(combatRules, data){

        for(var combatCol = 1; combatCol <= 11; combatCol++){
            $(".col" + combatCol).css({background: "transparent"});
        }
        var title = "Combat Results ";
        var cdLine = "";
        var activeCombat = false;
        var activeCombatLine = "";
        str = "";
        var toResolveLog = "";

        if(combatRules){
            cD = combatRules.currentDefender;
            if(combatRules.combats && Object.keys(combatRules.combats).length > 0){
                if(cD !== false){
                    var defenders = combatRules.combats[cD].defenders;
                    if(combatRules.combats[cD].useAlt){
                        showCrtTable($('#cavalryTable'));
                    }else{
                        if(combatRules.combats[cD].useDetermined){
                            showCrtTable($('#determinedTable'));
                        }else{
                            showCrtTable($('#normalTable'));
                        }
                    }
                    for(var loop in defenders){
                        $("#" + loop).css({borderColor: "yellow"});
                    }
                    if(!chattyCrt){
                        $("#crt").show({effect:"blind",direction:"up"});
                        $("#crtWrapper").css('overflow', 'visible');
                        chattyCrt = true;
                    }
                    if(Object.keys(combatRules.combats[cD].attackers).length != 0){
                        if(combatRules.combats[cD].pinCRT !== false){
                            combatCol = combatRules.combats[cD].pinCRT + 1;
                            if(combatCol >= 1){
                                $(".col" + combatCol).css('background-color', "rgba(255,0,255,.6)");
                                if(combatRules.combats[cD].Die !== false){
                                    $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('font-size', "110%");
                                    $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('background', "#eee");
                                }
                            }
                        }
                        combatCol = combatRules.combats[cD].index + 1;
                        if(combatCol >= 1){
                            $(".col" + combatCol).css('background-color', "rgba(255,255,1,.6)");
                            if(combatRules.combats[cD].Die !== false){
                                $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('font-size', "110%");
                                $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('background', "#eee");
                            }
                        }
                    }
                }
                var str = "";
                cdLine = "";
                var combatIndex = 0;
                $('.unit').removeAttr('title');
                $('.unit .unitOdds').remove();
                for(i in combatRules.combats){
                    if(combatRules.combats[i].index !== null){


                        attackers = combatRules.combats[i].attackers;
                        defenders = combatRules.combats[i].defenders;
                        thetas = combatRules.combats[i].thetas;

                        var theta = 0;
                        for(var j in attackers){
                            var numDef = Object.keys(defenders).length;
                            for(k in defenders){
                                $("#" + j + " .arrow").clone().addClass('arrowClone').addClass('arrow' + k).insertAfter("#" + j + " .arrow").removeClass('arrow');
                                theta = thetas[j][k];
                                theta *= 15;
                                theta += 180;
                                $("#" + j + " .arrow" + k).css({opacity: "1.0"});
                                $("#" + j + " .arrow" + k).css({webkitTransform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(45px)"});
                                $("#" + j + " .arrow" + k).css({transform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(45px)"});
                            }
                        }

                        var atk = combatRules.combats[i].attackStrength;
                        var atkDisp = atk;
                        if(combatRules.storm){
                            atkDisp = atk * 2 + " halved for storm = " + atk;
                        }
                        var def = combatRules.combats[i].defenseStrength;
                        var ter = combatRules.combats[i].terrainCombatEffect;
                        var idx = combatRules.combats[i].index + 1;
                        var useAltColor = combatRules.combats[i].useAlt ? " altColor":"";
                        if(combatRules.combats[i].useDetermined){
                            useAltColor = " determinedColor";
                        }
                        var odds = Math.floor(atk / def);
                        var oddsDisp = $(".col" + combatCol).html();
                        var currentCombatCol = combatRules.combats[i].index + 1;
                        if(combatRules.combats[i].pinCRT !== false){
                            currentCombatCol = combatRules.combats[i].pinCRT + 1;
                            useAltColor = " pinnedColor";
                        }
                        var currentOddsDisp = $(".col" + currentCombatCol).html();
                        $("#"+i).attr('title',currentOddsDisp).prepend('<div class="unitOdds'+useAltColor+'">'+currentOddsDisp+'</div>');;

                        var details = renderCrtDetails(combatRules.combats[i]);
                        newLine = "<h5>odds = " + oddsDisp + " </h5>" +details;
                        if(cD !== false && cD == i){
                            activeCombat = combatIndex;
                            activeCombatLine = newLine;
                            /*cdLine = "<fieldset><legend>Current Combat</legend><strong>"+newLine+"</strong></fieldset>";
                             newLine = "";*/
                        }
                        combatIndex++;
//                            str += newLine;
                    }

                }
                str += "There are " + combatIndex + " Combats";
                if(cD !== false){
                    attackers = combatRules.combats[cD].attackers;
//                    var theta = 0;
//                    for(var i in attackers){
//                                      theta = attackers[i];
//                        theta *= 15;
//                        theta += 180;
//                        $("#"+i+ " .arrow").css({display: "block"});
//                        $("#"+i+ " .arrow").css({opacity: "1.0"});
//                        $("#"+i+ " .arrow").css({webkitTransform: 'scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
//
//
//                    }
                }
                str += "";
                $("#crtOddsExp").html(activeCombatLine);
                $("#status").html(cdLine + str);
                if(DR.crtDetails){
                    $("#crtDetails").toggle();
                }
                $("#status").show();

            }else{
                chattyCrt = false;
            }


            var lastCombat = "";
            if(combatRules.combatsToResolve){
                $('.unit').removeAttr('title');
                $('.unit .unitOdds').remove();
                if(combatRules.lastResolvedCombat){
                    toResolveLog = "Current Combat or Last Combat<br>";
                    title += "<strong style='margin-left:20px;font-size:150%'>" + combatRules.lastResolvedCombat.Die + " " + combatRules.lastResolvedCombat.combatResult + "</strong>";
                    combatCol = combatRules.lastResolvedCombat.index + 1;
                    $(".col" + combatCol).css('background-color', "rgba(255,255,1,.6)");
                    var pin = combatRules.lastResolvedCombat.pinCRT;
                    if(pin !== false){
                        pin++;
                        if(pin < combatCol){
                            combatCol = pin;
                            $(".col" + combatCol).css('background-color', "rgba(255, 0, 255, .6)");
                        }
                    }
                    combatRoll = combatRules.lastResolvedCombat.Die;
                    $(".row" + combatRoll + " .col" + combatCol).css('background-color', "cyan");
                    if(combatRules.lastResolvedCombat.useAlt){
                        showCrtTable($('#cavalryTable'));
                    }else{
                        if(combatRules.lastResolvedCombat.useDetermined){
                            showCrtTable($('#determinedTable'));
                        }else{
                            showCrtTable($('#normalTable'));
                        }
                    }
                    var atk = combatRules.lastResolvedCombat.attackStrength;
                    var atkDisp = atk;
                    ;

                    var def = combatRules.lastResolvedCombat.defenseStrength;
                    var ter = combatRules.lastResolvedCombat.terrainCombatEffect;
                    var idx = combatRules.lastResolvedCombat.index + 1;
                    var odds = Math.floor(atk / def);
                    var oddsDisp = $(".col" + combatCol).html();
                    var details = renderCrtDetails(combatRules.lastResolvedCombat);

                    newLine = "<h5>odds = " + oddsDisp + "</h5>"+details;

                    toResolveLog += newLine;
                    toResolveLog += "Roll: "+combatRules.lastResolvedCombat.Die + " result: " + combatRules.lastResolvedCombat.combatResult+"<br><br>";

                    $("#crtOddsExp").html(newLine);
//                    $(".row"+combatRoll+" .col"+combatCol).css('color',"white");
                }
                str += "";
                var noCombats = false;
                if(Object.keys(combatRules.combatsToResolve) == 0){
                    noCombats = true;
                    str += "0 combats to resolve";
                }
                var combatsToResolve = 0;
                toResolveLog += "Combats to Resolve<br>";
                for(i in combatRules.combatsToResolve){
                    combatsToResolve++;
                    if(combatRules.combatsToResolve[i].index !== null){
                        attackers = combatRules.combatsToResolve[i].attackers;
                        defenders = combatRules.combatsToResolve[i].defenders;
                        thetas = combatRules.combatsToResolve[i].thetas;

                        var theta = 0;
                        for(var j in attackers){
                            var numDef = Object.keys(defenders).length;
                            for(k in defenders){
                                $("#"+j+ " .arrow").clone().addClass('arrowClone').addClass('arrow'+k).insertAfter("#"+j+ " .arrow").removeClass('arrow');
                                theta = thetas[j][k];
                                theta *= 15;
                                theta += 180;
                                $("#"+j+ " .arrow"+k).css({opacity: "1.0"});
                                $("#"+j+ " .arrow"+k).css({webkitTransform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                                $("#"+j+ " .arrow"+k).css({transform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                            }
                        }

                        var atk = combatRules.combatsToResolve[i].attackStrength;
                        var atkDisp = atk;
                        ;

                        var def = combatRules.combatsToResolve[i].defenseStrength;
                        var ter = combatRules.combatsToResolve[i].terrainCombatEffect;
                        var combatCol = combatRules.combatsToResolve[i].index + 1;
                        var useAltColor = combatRules.combatsToResolve[i].useAlt ? " altColor":"";

                        if(combatRules.combatsToResolve[i].pinCRT !== false){
                            combatCol = combatRules.combatsToResolve[i].pinCRT;
                        }
                        var odds = Math.floor(atk / def);
                        var oddsDisp = $(".col" + combatCol).html();
                        var useAltColor = combatRules.combatsToResolve[i].useAlt ? " altColor":"";
                        if(combatRules.combatsToResolve[i].useDetermined){
                            useAltColor = " determinedColor";
                        }
                        if(combatRules.combatsToResolve[i].pinCRT !== false){
                            oddsDisp = combatRules.combatsToResolve[i].pinCRT + 1;
                            oddsDisp = $(".col" + oddsDisp).html();

                            useAltColor = " pinnedColor";
                        }
                        $("#"+i).attr('title',oddsDisp).prepend('<div class="unitOdds'+useAltColor+'">'+oddsDisp+'</div>');;
                        var details = renderCrtDetails(combatRules.combatsToResolve[i]);

                        newLine = "<h5>odds = " + oddsDisp + "</h5>" + details;
                        toResolveLog += newLine;
                    }

                }
                if(combatsToResolve){
//                str += "Combats To Resolve: " + combatsToResolve;
                }
                var resolvedCombats = 0;
                toResolveLog += "<br>Resolved Combats <br>";
                for(i in combatRules.resolvedCombats){
                    resolvedCombats++;
                    if(combatRules.resolvedCombats[i].index !== null){
                        atk = combatRules.resolvedCombats[i].attackStrength;
                        atkDisp = atk;
                        ;
                        if(combatRules.storm){
                            atkDisp = atk * 2 + " halved for storm " + atk;
                        }
                        def = combatRules.resolvedCombats[i].defenseStrength;
                        ter = combatRules.resolvedCombats[i].terrainCombatEffect;
                        idx = combatRules.resolvedCombats[i].index + 1;
                        newLine = "";
                        if(combatRules.resolvedCombats[i].Die){
                            var x = $("#" + cD).css('left').replace(/px/, "");
                            var mapWidth = $("body").css('width').replace(/px/, "");
                        }
                        var oddsDisp = $(".col" + combatCol).html()

                        newLine += " Attack = " + atkDisp + " / Defender " + def + atk / def + "<br>odds = " + Math.floor(atk / def) + " : 1<br>Combined Arms Shift " + ter + " = " + oddsDisp + "<br>";
                        newLine += "Roll: "+combatRules.resolvedCombats[i].Die + " result: " + combatRules.resolvedCombats[i].combatResult+"<br><br>";
                        if(cD === i){
                            newLine = "";
                        }
                        toResolveLog += newLine;
                    }

                }
                if(!noCombats){
                    str += "Combats: " + resolvedCombats + " of " + (resolvedCombats+combatsToResolve);
                }
                $("#status").html(lastCombat + str);
                $("#status").show();

            }
        }
        $("#CombatLog").html(toResolveLog);
        $("#crt h3").html(title);


    });


x.register("combatRulez", function(combatRules,data) {

    for(var combatCol = 1;combatCol <= 12;combatCol++){
        $(".col"+combatCol).css({background:"transparent"});

    }
    var title = "Combat Results ";
    var cdLine = "";
    var activeCombat = false;
    var activeCombatLine = "";
    str = ""
    if(combatRules ){
        cD = combatRules.currentDefender;
        if(combatRules.combats && Object.keys(combatRules.combats).length > 0){
            if(cD !== false){

                var defenders = combatRules.combats[cD].defenders;
                    if(combatRules.combats[cD].useDetermined){
                        $('.tableWrapper.main').hide();
                        $('.tableWrapper.determined').show();
                        $('#detTable').hide();
                        $('#mainTable').show();
                    }else{
                        $('.tableWrapper.main').show();
                        $('.tableWrapper.determined').hide();
                        $('#detTable').show();
                        $('#mainTable').hide();
                    }


                for(var loop in defenders){
                    $("#"+loop).css({borderColor: "yellow"});
                }
                if(!chattyCrt){
                    $("#crt").show({effect:"blind",direction:"up"});
                    $("#crtWrapper").css('overflow', 'visible');
                    chattyCrt = true;
                }
//                fixCrt();
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
            var str = "";
            cdLine = "";
            var combatIndex = 0;
            $('.unit').removeAttr('title');
            $('.unit .unitOdds').remove();

            for(i in combatRules.combats){
                if(combatRules.combats[i].index !== null){


                    attackers = combatRules.combats[i].attackers;
                    defenders = combatRules.combats[i].defenders;
                    thetas = combatRules.combats[i].thetas;

                    var theta = 0;
                    for(var j in attackers){
                        var numDef = Object.keys(defenders).length;
                        for(k in defenders){
                            $("#"+j+ " .arrow").clone().addClass('arrowClone').addClass('arrow'+k).insertAfter("#"+j+ " .arrow").removeClass('arrow');
                            theta = thetas[j][k];
                            theta *= 15;
                            theta += 180;
                            $("#"+j+ " .arrow"+k).css({opacity: "1.0"});
                            $("#"+j+ " .arrow"+k).css({webkitTransform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                            $("#"+j+ " .arrow"+k).css({transform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                        }
                    }

                    var atk = combatRules.combats[i].attackStrength;
                    var atkDisp = atk;
                    if(combatRules.storm){
                        atkDisp = atk*2 + " halved for storm = "+atk;
                    }
                    var def = combatRules.combats[i].defenseStrength;
                    var ter = combatRules.combats[i].terrainCombatEffect;
                    var idx = combatRules.combats[i].index+ 1;
                    var useAltColor = combatRules.combats[i].useAlt ? " altColor":"";
                    if(combatRules.combats[i].useDetermined){
                        useAltColor = " determinedColor";
                    }
                    var odds = Math.floor(atk/def);
                    var oddsDisp = odds + " : 1";
                    if(odds < 1){
                        oddsDisp = "No effect";
                    }
                    var idxDisp = idx + " : 1";
                    var ratio;
                    if(idx < 1){
                        idxDisp = "No effect";
                        ratio = "No Effect!";
                    }else{
                        ratio = $(".col"+idx).html() || "No Effect";
                    }
                    $("#"+i).attr('title',ratio).prepend('<div class="unitOdds '+useAltColor+'">'+ratio+'</div>');
                    var details = renderCrtDetails(combatRules.combats[i]);

                    newLine =  "<h5>odds = "+ oddsDisp +"</h5>" + details;
                    if(cD !== false && cD == i){
                        activeCombat = combatIndex;
                        activeCombatLine = newLine;

                    }
                    combatIndex++;
                }

            }
            str += "There are "+combatIndex+" Combats";
            if(cD !== false){
                attackers = combatRules.combats[cD].attackers;
            }
            str += "";
            $("#crtOddsExp").html(activeCombatLine);
            $("#status").html(cdLine+str);
            if(DR.crtDetails){
                $("#crtDetails").toggle();
            }
            $("#status").show();

        }else{
            chattyCrt = false;
        }


        var lastCombat = "";
        if(combatRules.combatsToResolve){
            $('.unit').removeAttr('title');
            $('.unit .unitOdds').remove();
            if(combatRules.lastResolvedCombat){
                if(combatRules.lastResolvedCombat.useDetermined){
                    $('.tableWrapper.main').hide();
                    $('.tableWrapper.determined').show();
                    $('#detTable').hide();
                    $('#mainTable').show()
                }else{
                        $('.tableWrapper.main').show();
                        $('.tableWrapper.determined').hide();

                    $('#detTable').show();
                    $('#mainTable').hide();
                }
                title += "<strong style='margin-left:20px;font-size:150%'>"+combatRules.lastResolvedCombat.Die+" "+combatRules.lastResolvedCombat.combatResult+"</strong>";
                combatCol = combatRules.lastResolvedCombat.index + 1;
                combatRoll = combatRules.lastResolvedCombat.Die;
                $(".col"+combatCol).css('background-color',"rgba(255,255,1,.6)");
                $(".row"+combatRoll+" .col"+combatCol).css('background-color',"cyan");
            }
            str += "";
            var noCombats = false;
            if(Object.keys(combatRules.combatsToResolve) == 0){
                noCombats = true;
                str += "0 combats to resolve";
            }
            var combatsToResolve = 0;
            for(i in combatRules.combatsToResolve){
                combatsToResolve++;
                if(combatRules.combatsToResolve[i].index !== null){
                    attackers = combatRules.combatsToResolve[i].attackers;
                    defenders = combatRules.combatsToResolve[i].defenders;
                    thetas = combatRules.combatsToResolve[i].thetas;

                    var theta = 0;
                    for(var j in attackers){
                        var numDef = Object.keys(defenders).length;
                        for(k in defenders){
                            $("#"+j+ " .arrow").clone().addClass('arrowClone').addClass('arrow'+k).insertAfter("#"+j+ " .arrow").removeClass('arrow');
                            theta = thetas[j][k];
                            theta *= 15;
                            theta += 180;
                            $("#"+j+ " .arrow"+k).css({opacity: "1.0"});
                            $("#"+j+ " .arrow"+k).css({webkitTransform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                            $("#"+j+ " .arrow"+k).css({transform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                        }
                    }




                    var atk = combatRules.combatsToResolve[i].attackStrength;
                    var atkDisp = atk;;
                    if(combatRules.storm){
                        atkDisp = atk*2 + " halved for storm "+atk;
                    }
                    var def = combatRules.combatsToResolve[i].defenseStrength;
                    var ter = combatRules.combatsToResolve[i].terrainCombatEffect;
                    var idx = combatRules.combatsToResolve[i].index+ 1;
                    var odds = Math.floor(atk/def);
                    var oddsDisp = odds + " : 1";
                    var ratio = $(".col"+idx).html() || "No Effect";
                    var useAltColor = combatRules.combatsToResolve[i].useAlt ? " altColor":"";
                    if(combatRules.combatsToResolve[i].useDetermined){
                        useAltColor = " determinedColor";
                    }
                    $("#"+i).attr('title',oddsDisp).prepend('<div class="unitOdds  '+useAltColor+'">'+ratio+'</div>');
                    newLine =  "<h5>odds = "+ oddsDisp +"</h5><div>Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>Terrain Shift left "+ter+ " = "+idxDisp+"</div>";
                }

            }
            if(combatsToResolve){
//                str += "Combats To Resolve: "+combatsToResolve;
            }
            var resolvedCombats = 0;
            for(i in combatRules.resolvedCombats){
                resolvedCombats++;
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
                        var x = $("#"+cD).css('left').replace(/px/,"");
                        var mapWidth = $("body").css('width').replace(/px/,"");
                    }
                    newLine += " Attack = "+atkDisp +" / Defender "+def+ " odds = " + atk/def +"<br>= "+Math.floor(atk/def)+" : 1<br>Terrain Shift left "+ter+ " = "+idx+" : 1<br><br>";
                    if(cD === i){
                        newLine = "";
                    }
                }

            }
            if(!noCombats){
                str += "Combats: " + resolvedCombats + " of " + (resolvedCombats+combatsToResolve);
            }

            $("#status").html(lastCombat+str);

            $("#status").show();

        }
    }
    $("#crt h3").html(title);


});
var globInit = true;
x.fetch(0);
</script>