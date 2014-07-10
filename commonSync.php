<script>
x = new Sync("<?=site_url("wargame/fetch/");?>");
x.register("sentBreadcrumbs", function(breadcrumbs,data) {
    debugger;
//    return;

    $('svg > path').remove();
    $('svg circle').remove();
    var lastUnit = '';
    var lastMoves = '';
    for(var unitId in breadcrumbs){
        for(var moves in breadcrumbs[unitId]){
            var path = "";
            if(breadcrumbs[unitId][moves-0+1]){
//                path += "<path stroke-width='15' fill='none' stroke='#84b5ff'";
                path += "<path stroke-width='15' fill='none' stroke='orange'";
            }else{
                path += "<path marker-end='url(#head)' stroke-width='15' fill='none' stroke='orange'";
            }
            var d = 'M'+breadcrumbs[unitId][moves].fromX+','+breadcrumbs[unitId][moves].fromY;
            d += ' L'+breadcrumbs[unitId][moves].toX+','+breadcrumbs[unitId][moves].toY;
            path += ' d="'+d + '"/>';
            var circle = '<circle fill="orange" cx="'+breadcrumbs[unitId][moves].toX+'" cy="'+breadcrumbs[unitId][moves].toY+'" r="7"/>';
            $('svg').append(path);
            $('svg').append(circle);
            lastMoves = moves;
        }
//        if(breadcrumbs[unitId][lastMoves]){
//            var path = "<path marker-end='url(#head)'  stroke-width='15' fill='none' stroke='#df5842'";
//            var d = 'M'+breadcrumbs[unitId][lastMoves].fromX+','+breadcrumbs[unitId][lastMoves].fromY;
//            d += ' L'+breadcrumbs[unitId][lastMoves].toX+','+breadcrumbs[unitId][lastMoves].toY;
//            path += ' d="'+d + '"/>';
//            $('svg').append(path);
//        }

    }
    var svgHtml = $('#svgWrapper').html();
    debugger;
    $('#svgWrapper').html(svgHtml);
//    $("svg").html($('svg').html());
});
x.register("force", function(force,data) {
//        if(this.animate !== false){
//            self.clearInterval(this.animate);
//            this.animate = false;
//            $("#"+this.animateId).stop(true);
//        }
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
//        $("#"+i).css({zIndex: 100});
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

//                    color = "turquoise";
                    shadow = false;
                }else{
//                    shadow = false;

//                    color = "purple";
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


//                color = "orange";
                break;
            case <?=STATUS_MOVING?>:
                if(units[i].forceMarch){
                    $("#"+i+" .forceMarch").show();
                    $("#"+i+" .range").hide();

                    color = "f00 #666 #666 #f00";
                }else{
                    $("#"+i+" .forceMarch").hide();
                    $("#"+i+" .range").show();

                    color = "#ccc #666 #666 #ccc";

                }
                shadow = false;
//                $("#"+i).css({zIndex: 101});
                boxShadow = '5px 5px 5px #333';
//               var top =  $("#"+i).css("top");
//                var left =  $("#"+i).css("left");
//                 $("#"+i).css({top:top-5});
//                $("#"+i).css({left:left-5});



//                status += " "+units[i].moveAmountUsed+" MF's Used";
                break;
            case <?=STATUS_STOPPED?>:
                color = "#ccc #666 #666 #ccc";
                break;
            case <?=STATUS_DEFENDING?>:
                color = "orange";

                break;
            case <?=STATUS_BOMBARDING?>:

            case <?=STATUS_ATTACKING?>:

                shadow = false;
//                color = "red";
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

                    status = "Now click on a hex adjacent to the yellow unit. ";
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
                if($("#flashMessage header").html() != <?=ADVANCING_MODE?>){
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
//            var x = $("#"+i).css('left').replace(/px/,"");
//            var y = $("#"+i).css('top').replace(/px/,"");
            var x = $("#"+i).position().left;
            var y = $("#"+i).position().top;
            y /= globalZoom;
            x /= globalZoom;

            var mapWidth = $("body").width();
            var mapHeight = $("#gameViewer").height();
//                    $("#map").css('width').replace(/px/,"");

//            if(x < mapWidth/2){
//                var wrapWid = $("#crtWrapper").css('width').replace(/px/,"");
//                var crtWid = $("#crt").css('width').replace(/px/,"");
//                crtWid = 0 - (crtWid - wrapWid + 40);
//
//                var moveLeft = $("body").css('width').replace(/px/,"");
//                $("#crt").animate({left:crtWid},300);
//                $("#crtWrapper").animate({left:moveLeft - wrapWid},300);
//            }else{
//                $("#crtWrapper").animate({left:0},300);
//                $("#crt").animate({left:0},300);
//
//
//            }

//            alert($("#"+i).offset().top);
//            y = $("#"+i).offset().top;
//            var mapOffset  = $("#gameImages").css('top').replace(/px/,"");
            var mapOffset  = $("#gameImages").position().top;

            if(mapOffset === "auto"){
                mapOffset = 0;
            }
            var moveAmt;
//            alert($("#crt").offset().top);
//            alert($("#gameViewer").offset().top);
            if(mapOffset + y > 2*mapHeight/3){
                moveAmt = (100 + (mapOffset + y)/3);
                if(moveAmt > 250){
                    moveAmt = 250;
                }
                y -= moveAmt;

//                if(y + mapOffset < 180){
//                    y = 180 - mapOffset;
//                }
//                alert("B");
            }else{
//                alert("a");
                moveAmt = (mapHeight - (mapOffset + y ))/2;
                if(moveAmt > 200){
                    moveAmt = 200;
                }
                y += moveAmt;
            }
//            y = y + off;
//            alert(off);
//            x = parseInt(x);
//            y = parseInt(y) + off.top;
//            if(y > mapHeight/2){
//                y -= 200 + off.top;
//            }else{
//                y += 200 - off.top;
//            }
//            mapWidth = parseInt(mapWidth);
//            alert($("#crt").offset().top);
//            alert($("#crt").height());
//            if(x > mapWidth/2){
//                var floatWidth = $("#floatMessage").width();
//                    x -= (300 + floatWidth);
////                x = (mapWidth - x);
////                x += 100;
//                $("#floatMessage").css('left',x+"px");
//                $("#floatMessage").css('right',"auto");
////
//            }else{
//                x += 100;
//                $("#floatMessage").css('left',x+"px");
//                $("#floatMessage").css('right',"auto");
//            }
//            alert(y);
            var dragged = $("#floatMessage").attr('hasDragged');

            if(dragged != 'true'){
                $("#floatMessage").css('top',y+"px");
                $("#floatMessage").css('left',x+"px");
            }
            $("#floatMessage").show();
            $("#floatMessage p").html(status);
            status = "";
        }
        /*$("#status").html(status);*/
//        $("#"+i).css({borderColor: color});
        $("#"+i).css({borderColor: color,borderStyle:style});
        if(shadow){
            $("#"+i+" section").addClass("shadowy");
        }else{
            $("#"+i+" section").removeClass("shadowy");
        }
        $("#"+i).css({boxShadow: boxShadow});



    }
    if(totalAttackers || totalDefenders){
        $("#requiredCombats").html(totalAttackers+" attackers "+totalDefenders+" defenders required");
    }else{
        $("#requiredCombats").html('');
    }

    if(!showStatus){
        $("#floatMessage").removeAttr("hasDragged");
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
x.register("phaseClicks",function(clicks){
    var str = "";
    for(var i in clicks){
        str += "<a class='phaseClick' data-click='"+clicks[i]+"'>"+clicks[i]+"</a> " ;
    }
    $("#phaseClicks").html(str);
});
x.register("click",function(click){
    $("#clickCnt").html(click);
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
    if(gameRules.display.currentMessage){
        $("#display").html(gameRules.display.currentMessage+"<button onclick='doitNext()'>Next</button>").show();
    }else{
        $("#display").html("").hide();
    }
    var status = "";
    var turn = gameRules.turn;
    var maxTurn = gameRules.maxTurn
    if("gameTurn"+turn != $("#turnCounter").parent().attr("id")){
        $("#gameTurn"+turn).prepend($("#turnCounter"));
    }

    var pix = turn  + (turn - 1) * 36 + 1;
    if(gameRules.attackingForceId == 1){
//        $("#header").css("background","rgb(223,88,66)");

        $("#header").removeClass("playerTwo").addClass("playerOne");
        $("#turnCounter").css("background","rgb(0,128,0)");
        $("#turnCounter").css("color","white");
//        $("#crt").css("border-color","rgb(223,88,66)");
        $("#crt").addClass("playerOne").removeClass("playerTwo");
//        $(".row1,.row3,.row5").css("background-color","rgb(223,88,66)");
        $(".row1,.row3,.row5").addClass("playerOne").removeClass("playerTwo");
    }else{
        $("#header").removeClass("playerOne").addClass("playerTwo");
        $(".row1,.row3,.row5").addClass("playerTwo").removeClass("playerOne");
        $("#crt").addClass("playerTwo").removeClass("playerOne");
//        $("#header").css("background","rgb(132,181,255)");
        $("#turnCounter").css("background","rgb(0,128,255)");
        $("#turnCounter").css("color","white");
//        $("#crt").css("border-color","rgb(132,181,255)");
//        $(".row1,.row3,.row5").css("background-color","rgb(132,181,255)");
    }

    var html = "<span id='turn'>Turn "+turn+" of "+maxTurn+"</span> ";
    html += "<span id='phase'>"+gameRules.phase_name[gameRules.phase];
    if(gameRules.mode_name[gameRules.mode]){
        html += " "+gameRules.mode_name[gameRules.mode];
    }
    html += "</span>";
    if(gameRules.storm){
        html += "<br><strong>Sand Storm rules in effect</strong>";
    }
    switch(gameRules.phase){
        case <?=BLUE_REPLACEMENT_PHASE?>:
        case <?=RED_REPLACEMENT_PHASE?>:
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

            $("#floatMessage header").html(result+": Attacker Loss Mode");
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
    $("#clock").html(html);
    if(status){
        $("#status").html(status);
        $("#status").show();

    }else{
        $("#status").html(status);
        $("#status").hide();

    }
});
x.register("vp", function(vp){
    $("#victory").html(" Victory: <span class='playerOneFace'><?=$force_name[1]?> </span>"+vp[1]+ " <span class='playerTwoFace'><?=$force_name[2];?> </span>"+vp[2]+"");

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

//                game = mess.match(/^@show ([^,]*)/);
//                id = game[1];
//                $("#"+id).show({effect:"blind",direction:"up",complete:flashMessage});
                return;
            }
        }
        $("body").append('<div id="FlashMessage" style="top:'+y+'px;left:'+x+'px;" class="flashMessage">'+mess+'</div>');
        $("#FlashMessage").animate({opacity:0},fadeOut,flashMessage);
        return;
    }
}
x.register("specialHexes", function(specialHexes, data) {
//    $(".specialHexes").remove();
    var lab = ['unowned','<?=strtolower($force_name[1])?>','<?=strtolower($force_name[2])?>'];
    for(var i in specialHexes){
//        alert(i);
        var newHtml = lab[specialHexes[i]];
        var curHtml = $("#special"+i).html();
//        alert(curHtml);
//        alert("I "+i+" "+newHtml);
        <!--        -->
        if(true || newHtml != curHtml){
            var hexPos = i.replace(/\.\d*/g,'');
            var x = hexPos.match(/x(\d*)y/)[1];
            var y = hexPos.match(/y(\d*)\D*/)[1];
            $("#special"+hexPos).remove();
            if(data.specialHexesChanges[i]){
                $("#gameImages").append('<div id="special'+hexPos+'" style="border-radius:30px;border:10px solid black;top:'+y+'px;left:'+x+'px;font-size:205px;z-index:1000;" class="'+lab[specialHexes[i]]+' specialHexes">'+lab[specialHexes[i]]+'</div>');
//                alert("Hi");
                $('#special'+hexPos).animate({fontSize:"16px",zIndex:0,borderWidth:"0px",borderRadius:"0px"},1900,function(){
                    var id = $(this).attr('id');
                    id = id.replace(/special/,'');
//                    alert(id);

//                    alert("spec "+data.specialHexesVictory);

                    if(data.specialHexesVictory[id]){
                        var hexPos = id.replace(/\.\d*/g,'');

                        var x = hexPos.match(/x(\d*)y/)[1];
                        var y = hexPos.match(/y(\d*)\D*/)[1];
                        $('<div id="VP'+hexPos+'" style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').insertAfter('#special'+i);
                        $("#VP"+hexPos).animate({top:y-30,opacity:0.0},1900,function(){
                            var id = $(this).attr('id');

                            $("#"+id).remove();
                        });
                    }
                });

            }else{
                $("#gameImages").append('<div id="special'+i+'" style="border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:16px;z-index:0;" class="'+lab[specialHexes[i]]+' specialHexes">'+lab[specialHexes[i]]+'</div>');

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
        $('<div id="VP'+hexPos+'" style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').appendTo('#gameImages');
        $("#VP"+hexPos).animate({top:y-30,opacity:0.0},1900,function(){
            var id = $(this).attr('id');

            $("#"+id).remove();
        });
    }


});
x.register("mapUnits", function(mapUnits) {
    var str;
    var isStacked = new Array();
    var fudge;
    var x,y;
    var beforeDeploy = $("#deployBox").children().size();

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

            $("#"+i).css({left:mapUnits[i].x-width/2-fudge+"px",top:mapUnits[i].y-height/2-fudge+"px"});
        }
        var img = $("#"+i+" img").attr("src");
        if(mapUnits[i].isReduced){
            img = img.replace(/(.*[0-9])(\.png)/,"$1reduced.png");
        }else{
            img = img.replace(/([0-9])reduced\.png/,"$1.png");
        }
        var  move = mapUnits[i].maxMove - mapUnits[i].moveAmountUsed;
        var str = mapUnits[i].strength;
        var reduced = mapUnits[i].isReduced;
        var reduceDisp = "<span>";
        if(reduced){
            reduceDisp = "<span class='reduced'>";
        }
        var symb = mapUnits[i].supplied !== false ? " - " : " <span class='reduced'>u</span> ";
        var html = reduceDisp + str + symb + move + "</span>"
        $("#"+i+" .unit-numbers").html(html);
        var len  = $("#"+i+" div").text().length;
        $("#"+i+" div span ").addClass("infoLen"+len);
        $("#"+i).attr("src",img);
    }
    var dpBox = $("#deployBox").children().size();
    if(dpBox != beforeDeploy){
        fixHeader();
        beforeDeploy = dpBox;

    }
    if(dpBox == 0 && $("#deployBox").is(":visible")){
        $("#deployWrapper").hide({effect:"blind",direction:"up",complete:fixHeader});
    }

});
x.register("moveRules", function(moveRules,data) {
    var str;
    /*$("#status").html("");*/
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
                $("#"+newId+" div span").html("24 - "+moveRules.hexPath[i].pointsLeft );
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
            MYCLONE.find(".arrow").hide();
            MYCLONE.addClass("clone");
            MYCLONE.find('section').css({backgroundColor:'transparent'});
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
                        $(this).css("border-color","transparent");
                    }
                    $(this).css("opacity",opacity).css('box-shadow','none');
                    var path = $(this).attr("path");
                    var pathes = path.split(",");
                    for(i in pathes){
                        $("#"+id+"Hex"+pathes[i]).css("opacity",.4).css("border-color","transparent").css('box-shadow','none');
                        $("#"+id+"Hex"+pathes[i]+".occupied").css("display","none");

                    }

                });

            var label = MYCLONE.find("div span").html();
            if(data.gameRules.phase == <?=RED_COMBAT_PHASE;?> || data.gameRules.phase == <?=BLUE_COMBAT_PHASE;?>){
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
            console.log(diff = new Date().getTime());
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

                var newLabel = label.replace(/((?:<span[^>]*>)?[-+ru](?:<\/span>)?).*/,"$1 "+moveRules.moves[i].pointsLeft);
                secondGenClone.find('div span').html(newLabel).addClass('infoLen'+newLabel.length);
                if(moveRules.moves[i].isOccupied){
                    secondGenClone.addClass("occupied");


                }
                /* left and top need to be set after appendTo() */

                secondGenClone.appendTo('#gameImages').css({left:moveRules.moves[i].pixX - width/2 +"px",top:moveRules.moves[i].pixY - height/2 +"px"});
//                $('#gameImages').append(secondGenClone).css({left:moveRules.moves[i].pixX - width/2 +"px",top:moveRules.moves[i].pixY - height/2 +"px"});
//                    $("<div style='position:absolute' id='"+newId+"'>hi</div>").appendTo('#gameImages').css({left:moveRules.moves[i].pixX - width/2 +"px",top:moveRules.moves[i].pixY - height/2 +"px"});
                /* apparently cloning attaches the mouse events */
            }
            console.log(counter);
            console.log(new Date().getTime() - diff);

            $("#firstclone").remove();
        }

    }
});

function fixCrt(){
    if(!cD){
        return;
    }
    var off = parseInt($("#gameImages").offset().left);
    var x = parseInt($("#"+cD).css('left').replace(/px/,"")) + off;
    var mapWidth = $("body").css('width').replace(/px/,"");
    $("#map").css('width').replace(/px/,"");
    if(x < mapWidth/2){
        var wrapWid = $("#crtWrapper").css('width').replace(/px/,"");
        var crtWid = $("#crt").css('width').replace(/px/,"");
        crtWid = 0 - (crtWid - wrapWid + 40);

        var moveLeft = $("body").css('width').replace(/px/,"");
        $("#crt").animate({left:crtWid},300);
        $("#crtWrapper").animate({left:moveLeft - wrapWid},300);
    }else{
        $("#crtWrapper").animate({left:0},300);
        $("#crt").animate({left:0},300);
    }
}
var chattyCrt = false;
var cD; /* object oriented! */
x.register("combatRules", function(combatRules,data) {

    for(var combatCol = 1;combatCol <= 10;combatCol++){
        $(".col"+combatCol).css({background:"transparent"});
//            $(".odd .col"+combatCol).css({color:"white"});
//            $(".even .col"+combatCol).css({color:"black"});

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
                for(var loop in defenders){
                    $("#"+loop).css({borderColor: "yellow"});
                }
                if(!chattyCrt){
                    $("#crt").show({effect:"blind",direction:"up"});
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
                    var odds = Math.floor(atk/def);
                    var oddsDisp = odds + " : 1";
                    if(odds < 1){
                        oddsDisp = "No effect";
                    }
                    var idxDisp = idx + " : 1";
                    if(idx < 1){
                        idxDisp = "No effect";
                    }
                    var ratio = $(".col"+combatCol).html() || "No Effect";
                    newLine =  "<h5>odds = "+ oddsDisp +"</h5><div id='crtDetails'>"+combatRules.combats[i].combatLog+"</div><div>Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>Terrain Shift left "+ter+ " = "+ratio +"</div>";
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
            str += "There are "+combatIndex+" Combats";
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
            if(combatRules.lastResolvedCombat){
                title += "<strong style='margin-left:20px;font-size:150%'>"+combatRules.lastResolvedCombat.Die+" "+combatRules.lastResolvedCombat.combatResult+"</strong>";
                combatCol = combatRules.lastResolvedCombat.index + 1;
                combatRoll = combatRules.lastResolvedCombat.Die;
                $(".col"+combatCol).css('background-color',"rgba(255,255,1,.6)");
                $(".row"+combatRoll+" .col"+combatCol).css('background-color',"cyan");
//                    $(".row"+combatRoll+" .col"+combatCol).css('color',"white");
            }
            str += "";
            var noCombats = false;
            if(Object.keys(combatRules.combatsToResolve) == 0){
                noCombats = true;
                str += "there are no combats to resolve";
            }
            var combatsToResolve = 0;
            for(i in combatRules.combatsToResolve){
                combatsToResolve++;
                if(combatRules.combatsToResolve[i].index !== null){
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
                    newLine =  "<h5>odds = "+ oddsDisp +"</h5><div>Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>Terrain Shift left "+ter+ " = "+idxDisp+"</div>";
                }

            }
            if(combatsToResolve){
                str += "Combats To Resolve: "+combatsToResolve;
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
                        /* STATUS_ELIMINATED */
                        if(data.force.units[cD].status != 22){
//                            if(x < mapWidth/2){
//                                var wrapWid = $("#crtWrapper").css('width').replace(/px/,"");
//                                var crtWid = $("#crt").css('width').replace(/px/,"");
//                                var moveLeft = $("body").css('width').replace(/px/,"");
//                                crtWid = crtWid - wrapWid + 40;
//                                $("#crt").animate({left:0 - crtWid},300);
//                                $("#crtWrapper").animate({left:moveLeft - wrapWid},300);
//                            }else{
//                                $("#crt").animate({left:crtWid},300);
//                                $("#crtWrapper").animate({left:0},300);
//
//                            }
                        }


                    }
                    newLine += " Attack = "+atkDisp +" / Defender "+def+ " odds = " + atk/def +"<br>= "+Math.floor(atk/def)+" : 1<br>Terrain Shift left "+ter+ " = "+idx+" : 1<br><br>";
                    if(cD === i){
                        newLine = "";
                    }
                }

            }
            if(!noCombats){
                str += " Resolved Combats: "+resolvedCombats+"";
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