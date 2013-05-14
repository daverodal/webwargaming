<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<style type="text/css">
h2 #status{
    font-size:16px;
}
h5{
    margin:0px;
}
#rightHeader{
    width:50%;
    float:right;
}
#TECWrapper{
    /*float:left;*/
}
#TECWrapper .closer{
    height:0px;
    padding:0px;
}
#TECWrapper .colOne.riverHex{
    background-image: url('<?=base_url()?>js/river.png');
    background-position:0px -26px;
}
#TECWrapper .colOne.bridgeHex{
    background-image: url('<?=base_url()?>js/riverRoad.png');
    background-position:0px -26px;
}
#TECWrapper .colOne.blankHex{
    background-image: url('<?=base_url()?>js/blank.png');
}
#TECWrapper .colOne.forestHex{
    background-image: url('<?=base_url()?>js/forest 2.png');
}
#TECWrapper .colOne.mountainHex{
    background-image: url('<?=base_url()?>js/mountain.png');
}
#TECWrapper .colOne.roadHex{
    background-image: url('<?=base_url()?>js/road.png');
    background-position:0px -26px;
}
#TECWrapper .colOne{
    background-size:400px;
    width:220px;
    height:56px;
    margin-left:20px;
}
#TECWrapper .colOne.header{
    height:26px;
}
#TECWrapper .colOne.header span{
    margin-left:40px;
}
#TECWrapper .colTwo{
    width:180px;
}
#TECWrapper .colThree{
    width:136px;
}
#TECWrapper .colOne span{
     margin-top:12px;
     margin-left: 80px;
     display:inline-block;
 }
#TECWrapper .colTwo, #TECWrapper .colThree{
    margin-top:12px;
}
#TECWrapper .colOne,
#TECWrapper .colTwo,
#TECWrapper .colThree{
    float:left;
}
#TECWrapper div{
    /*height:112px;*/
}
#TECWrapper li{
    list-style-type: none;
    /*height:79px;*/
    border:0 solid black;
    border-width:1px 1px 0 1px;
    padding:5px 20px 5px 0px;
}
#TECWrapper img, #TECWrapper div{
}
video{
    border:10px #aaa solid;
    box-shadow:10px 10px 10px #999;
    margin-right:10px;
}
#leftHeader{
    width:49%;
    float:left;
}
#comlinkWrapper{
    border:1px solid black;
    border-radius: 10px;
    background:white;
    padding:0 5px 0 5px;
    font-style: italic;
}
.specialHexes,.specialHexesVP{
    position:absolute;
    text-transform: capitalize;
    background:yellow;
    opacity:.8;
}
.specialHexesVP{
    text-shadow:0px 0px 1px white,
    0px 0px 1px white,
    0px 0px 1px white,
    0px 0px 2px white,
    0px 0px 2px white,
    0px 0px 2px white,
    0px 0px 3px white,
    0px 0px 3px white,
    0px 0px 3px white,
    0px 0px 4px white,
    0px 0px 4px white,
    0px 0px 4px white,
    0px 0px 5px white,
    0px 0px 5px white,
    0px 0px 5px white,
    0px 0px 6px white,
    0px 0px 6px white,
    0px 0px 6px white,
    0px 0px 7px white,
    0px 0px 7px white,
    0px 0px 7px white,
    0px 0px 8px white,
    0px 0px 8px white,
    0px 0px 8px white,
    0px 0px 9px white,
    0px 0px 9px white,
    0px 0px 9px white,
    0px 0px 10px white,
    0px 0px 10px white,
    0px 0px 10px white;
}
#display{
    display:none;
    position:fixed;
    top:25%;
    width:90%;
    left:5%;
    padding:30px 0;
    background:rgba(255,255,255,.9);
    font-size:100px;
    text-align: center;
    border:#000 solid 10px;
    border-radius: 30px;
    z-index:4;
}
.flashMessage{
    position:absolute;
    width:80%;
    background:rgba(255,255,255,.9);
    /*background:transparent;*/
    text-align: center;
    border-radius:30px;
    border:10px solid black;
    /*border:none;*/
    font-size:45px;
    z-index:1000;
}
#display button{
    font-size:30px;
}
#comlinkWrapper h4{
    font-weight: bold;
    margin:3px;
}
#header{
    position:fixed;
    width:99%;
    top:0px;
    background:#99cc99;
    background:rgb(132,181,255);
    z-index:2000;
}
#mouseMove{
    display:none;
}
#headerContent{
    min-height:110px;
}
#nextPhaseButton{
    font-size:15px;
    position: absolute;
    bottom: 0px;
    left:20%;
}
    #header a, #header a:visited{
        color:white;
    }
    #header a:hover{
        color:#ddd;
    }
    #header h2{
        margin: 10px 0 5px;
    }
    #content{
        margin-top:140px;
    }
    .clear{
        clear:both !important;
        float:none !important;
    }
    .blueUnit, .loyalist{
        background-color:rgb(132,181,255);
    }
    .loyalistVP{
        color:rgb(132,181,255);
        background: transparent;
        opacity:1.0;
    }
    .rebelVP{
        color:rgb(239,115,74);
        background: transparent;
    }
    .darkBlueUnit{
        background-color:rgb(99,136,192);
    }
    .redUnit{
        background-color:rgb(239,115,74);
    }
    .darkRedUnit{
        background-color:rgb(179,86,55);
    }
    .sympth{
        background-color:gold;
    }
    .armyGreen, .lloyalist{
    background-color:  rgb(148,189,74);
    }
    .gold{
    background-color:rgb(247,239,142);
    }
    .rebel{
        background-color:rgb(239,115,74);
    }
    .lightYellow, .rebel{
        background-color: rgb(255,239,156);
    }
    .gray{
        background-color: rgb(181,181,156);
    }
    .oliveDrab{
        background-color: rgb(115,156,115);
    }
    .kaki{
        background-color: rgb(222,198,132);
    }
    .lightGreen{
        background-color: rgb(181,206,115);
    }
    .lightBlue{
        background-color: rgb(196,216,228);
    }
    .lightGray{
        background-color: rgb(216,218,214);
    }
    .brightGreen{
        background-color: rgb(123,222,82);
    }
    .flesh, .sympth{
        background-color: rgb(239,198,156);
    }
    .brightRed, .rebel, .sympth{
        background-color: rgb(223,88,66);
    }

#phaseDiv,#statusDiv,#chatsDiv,#crtWrapper,#victoryDiv{
    display:inline;
    vertical-align: top;
    /*float:left;*/
}
#statusDiv{

}
#crtWrapper , #OBCWrapper, #TECWrapper{
    user-select:none;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    /*float:left;*/
    position: absolute;

}
#crtWrapper h4, #OBCWrapper h4, #TECWrapper h4{
    margin:0;
    border:none;
    user-select:none;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    cursor: pointer;

}
#crtWrapper{
    left:0px;
    bottom:0px;
    display:none;
}
#TECWrapper{
    width:120px;
    left:0%;
    bottom:0px;
}
#TEC ul{
    margin:0;
    padding:0
}
#OBCWrapper{
    width:120px;
    left:25%;
    bottom:0px;
    display:none;
}
#hideShow{
    cursor:pointer;
    margin-left:55%;
    display:none;
}
#OBC{
    background:white;
    width:514px;
}
#TEC{
    background:white;
    width:579px;
}
#crtWrapper h4{
    width: 349px;

}
#clickCnt{
    float:left;
    padding:3px;
}
#crtWrapper h4 .goLeft,#crtWrapper h4 .goRight{
font-size:22px;
    padding:0 15px;
}
#OBCWrapper h4 {
}
#OBC, #crt, #TEC{
    position:absolute;
    z-index:30;
    display:none;
}
#OBCWrapper h4:focus,#crtWrapper h4:focus{
outline: -webkit-focus-ring-color none;
}
body{
        background:#eee;
        color:#333;
    }

    #clock{
        /*width:100px;*/
    }
    #status{
        /*height:70px;*/
        /*overflow-y:scroll;*/
        /*text-align:right;*/
    }
    #status legend{
        /*text-align:left;*/
    }
    fieldset{
        background:white;
        border-radius:9px;
    }
    #OBC fieldset{
        float:left;
        min-height: 100px;
    }
    .left{
        float:left;
    }
    .right{
        float:right;
    }
    #crt{
        border-radius:15px;
        border:10px solid rgb(132,181,255);
        background:#fff;color:black;
    ;
        font-weight:bold;
        padding:1px 5px 10px 15px;
        box-shadow: 0px 0px 2px black;
    }
    #crt h3{
        height:40px;
        margin-bottom:5px;
        vertical-align:bottom;
    }
    #crt span{
        width:32px;
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
        /*background :#1af;*/
        background: rgb(132,181,255);
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
        position: relative;
    }
    #gameViewer{
        border:10px solid #555;
        border-radius:10px;
        overflow:hidden;
        background:#620;
        margin-bottom:5px;
        /*width:332px;*/
        /*margin:auto;*/
        float:left;
        display:none;
    }
    #leftcol {
        /*float:left;
        width:360px;*/
    }
    #rightCol{
        /*float:right;*/
        overflow:hidden;
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
    #turnCounter{
        width:32px;
        height:32px;
        font-size:11px;
        text-indent:0px;
        top:2px;
        left:2px;
        text-align:center;
        border:2px solid;
        border-color:#ccc #666 #666 #ccc;

    }

    #content{
        -webkit-user-select:none;
        -moz-user-select:none;
        user-select:none;

    }
    #map {
        -webkit-user-select:none;
    width:<?=$mapWidth;?>;/*really*/
    height:<?=$mapHeight;?>;

        }
    #gameImages {
        width:<?=$mapWidth;?>;/*really*/
        height:<?=$mapHeight;?>;
    }
    #deadpile{
        border-radius:10px;
        border:10px solid #555;
        height:100px;
        background:#333;
        overflow:hidden;
    }
    #deployBox{
        position:relative;
    }
    #deployWrapper{
        padding:4px 7px 4px 7px;
        text-align:left;
        font-family:sans-serif;
        font-size:1.2em;
        color:white;
        border-radius:10px;
        border:10px solid #555;
        background:#333;
        margin-bottom:5px;
    }
    .unit{
        width:64px;
        height:64px;
        width:48px;
        height:49px;
        position:absolute;
        left:0;top:0;
        z-index:3;
    width:<?=$unitSize?>;
    height:<?=$unitSize?>;
        /*width:32px;*/
        /*height:32px;*/
        }
    .unit div {
        text-align:center;
    margin-top:<?=$unitMargin?>;
    color:black;
        /*text-indent:3px;*/
    font-size:<?=$unitFontSize?>;
    font-weight:bold;
    -webkit-user-select:none;
        }
    .rebel div{
        color:black;
    }
    .sympth div{
        color:black;
    }
    .unit img {
        width:100%;
        height:100%;
        max-height:100px;
        max-width:100px;
    }
    .arrow{
        position:absolute;
        pointer-events:none;
        z-index:102;
    }
    .clone{
        /*pointer-events:none;*/
    }
    .occupied{
        display:none;
    }
</style>
<script>
$(document).ready(function(){
    $("#timeMachine").click(function(){
        x.timeTravel = true;
        if(x.current){
            x.current.abort();
        }
        var click = $("#clickCnt").html();
        click--;
        x.fetch(click);
    });
    $("#timeSurge").click(function(){
        var click = $("#clickCnt").html();
        click++;
        x.fetch(click);
    });
    $("#timeLive").click(function(){
        x.timeTravel = false;
        x.fetch(0);
    });
});
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
x.register("gameRules", function(gameRules) {
    if(gameRules.display.currentMessage){
        $("#display").html(gameRules.display.currentMessage+"<button onclick='doitNext()'>Next</button>").show();
    }else{
        $("#display").html("").hide();
    }
    var status = "&nbsp;";
    turn = gameRules.turn;
    if("gameTurn"+turn != $("#turnCounter").parent().attr("id")){
        $("#gameTurn"+turn).prepend($("#turnCounter"));
    }

        var pix = turn  + (turn - 1) * 36 + 1;
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
                status = "There are "+gameRules.replacementsAvail+" available";
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
    $("#status").html(status);
//    $("#victory").html("Rebel "+gameRules.vp[1]+ " Loyalist "+gameRules.vp[2]);
});
x.register("vp", function(vp){
        $("#victory").html("Rebel "+vp[1]+ " Loyalist "+vp[2]);

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

//    alert("flash");
//    alert(messages[0]);
    flashMessages = messages;
    flashMessage(data.gameRules.playerStatus);
//    alert("WHY");
});
function flashMessage(playerStatus){
    var x = 30;
    var y = 100;
//    alert("HERE");

    var mess = flashMessages.shift();
//    alert(mess);
    $("#FlashMessage").remove();
    while(mess){

        if(mess.match(/^@/)){
            if(mess.match(/^@forward/)){
                if(playerStatus == "hot seat"){
                    game = mess.match(/^@forward ([^,]*)/);
                    newPlayer = game[1];
                    if(newPlayer != Player){
<!--                       window.location = "--><?//=site_url("wargame/changeWargame");?><!--";-->
                    }
                }
                /* we only loop of we failed to forward */
                var mess = flashMessages.shift();
                continue;
            }
            if(mess.match(/^@video/)){
//                game = mess.match(/^@show ([^,]*)/);
//                id = game[1];
//                $("#"+id).show({effect:"blind",direction:"up",complete:flashMessage});
//                playLadies();
                return;
            }
            if(mess.match(/^@gameover/)){
                $("#gameViewer").append('<div id="FlashMessage" style="top:'+y+'px;left:'+x+'px;" class="flashMessage">'+"Victory"+'</div>');
                $("#FlashMessage").animate({opacity:0},2400,flashMessage);

//                game = mess.match(/^@show ([^,]*)/);
//                id = game[1];
//                $("#"+id).show({effect:"blind",direction:"up",complete:flashMessage});
                playLadies();
                return;
            }
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
            }        }
        $("#gameViewer").append('<div id="FlashMessage" style="top:'+y+'px;left:'+x+'px;" class="flashMessage">'+mess+'</div>');
        $("#FlashMessage").animate({opacity:0},2400,flashMessage);
        return;
    }
}
x.register("specialHexes", function(specialHexes, data) {

//    $(".specialHexes").remove();
    var lab = ['unowned','rebel','loyalist'];
    for(var i in specialHexes){
        var newHtml = lab[specialHexes[i]];
        var curHtml = $("#special"+i).html();
        if(newHtml != curHtml){
            var x = i.match(/x(\d*)y/)[1];
            var y = i.match(/y(\d*)\D*/)[1];
            var hexI = i.replace(/\./,"");
            $("#special"+hexI).remove();
            if(data.specialHexesChanges[i]){
            $("#gameImages").append('<div id="special'+hexI+'" style="border-radius:30px;border:10px solid black;top:'+y+'px;left:'+x+'px;font-size:205px;z-index:1000;" class="'+lab[specialHexes[i]]+' specialHexes">'+lab[specialHexes[i]]+'</div>');
                $('#special'+hexI).animate({fontSize:"16px",zIndex:0,borderWidth:"0px",borderRadius:"0px"},1600,function(){
                    playLadies();
//                    var id = $(this).attr('id');
//                    var myClass = $(this).attr('class');
//                    if(myClass.match(/loyalist/)){
//                        myClass = "loyalist";
//                    }else{
//                        myClass = "rebel"
//                    }
//
//                    var x = id.match(/x(\d*)y/)[1];
//                    var y = id.match(/y(\d*)\D*/)[1];
//                    $("#gameImages").append('<div id="specialVP'+i+'" style="border-radius:5px;top:'+y+'px;left:'+x+'px;font-size:80px;z-index:1000;" class="'+myClass+'VP specialHexesVP">+1 VP</div>');
//                    $(".specialHexesVP").animate({opacity:0.0,top:y-80},2200,function(){
//                        $(this).remove();
//                    });

                });

            }else{
                $("#gameImages").append('<div id="special'+hexI+'" style="border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:16px;z-index:0;" class="'+lab[specialHexes[i]]+' specialHexes">'+lab[specialHexes[i]]+'</div>');

            }

        }
    }
//    $(".specialHexes").bind("mousedown",mapMouseDown);

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
        if(mapUnits[i].parent == "gameImages"){

            $("#"+i).css({left: -1+mapUnits[i].x-width/2-fudge+"px",top:-1+mapUnits[i].y-height/2-fudge+"px"});
        }
        var img = $("#"+i+" img").attr("src");
        if(mapUnits[i].isReduced){
            img = img.replace(/(.*[0-9])(\.png)/,"$1reduced.png");
        }else{
            img = img.replace(/([0-9])reduced\.png/,"$1.png");
        }
        var  move = mapUnits[i].maxMove - mapUnits[i].moveAmountUsed;
        var str = mapUnits[i].strength;
        var symb = mapUnits[i].isReduced ? " r " : " - ";
        $("#"+i+" div").html(str + symb + move);
        $("#"+i).attr("src",img);
    }
    var dpBox = $("#deployBox").children().size();
    if(dpBox == 0){
        $("#deployWrapper").hide();
    }

});
x.register("moveRules", function(moveRules) {
    var str;
    /*$("#status").html("");*/
    $(".clone").remove();
    if(moveRules.movingUnitId >= 0){
//        alert("MovingUnitid"+moveRules.movingUnitId);

//        $("#status").html("Unit #:"+moveRules.movingUnitId+" is currently moving");
        if(moveRules.hexPath){
            alert("WHAT IS A HEXPATH!");
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
                $("#"+newId+" div").html("24 - "+moveRules.hexPath[i].pointsLeft );
                $("#"+newId).css("opacity",.9);
                $("#"+newId).css("z-index",101);


            }
        }
        if(moveRules.moves){
            id = moveRules.movingUnitId;
            newId = "firstclone";
            $("#"+id).clone(true).attr('id',newId).appendTo('#gameImages');
            $("#"+newId+" .arrow").hide();
            $("#"+newId).addClass("clone");

            width = $("#"+newId).width();
            height = $("#"+newId).height();

            var label = $("#"+newId+" div").html();

            $("#"+newId).css({opacity:.4,
                zIndex:102,
                borderColor:"#ccc #333 #333 #ccc",
                boxShadow:"none"}
            );
            for( i in moveRules.moves){
                newId = id+"Hex"+i;
                if(!moveRules.moves[i].isValid){
                    continue;
                }
                if(moveRules.moves[i].isOccupied){
//                    continue;
                }

                $("#"+'firstclone').clone(true).attr('id',newId).appendTo('#gameImages');
                $("#"+newId).attr("path",moveRules.moves[i].pathToHere);
                $("#"+newId).css({left:moveRules.moves[i].pixX - width/2 +"px",top:moveRules.moves[i].pixY - height/2 +"px"});
                var newLabel = label.replace(/([-+r]).*/,"$1 "+moveRules.moves[i].pointsLeft);

                $("#"+newId+" div").html(newLabel);
                if(moveRules.moves[i].isOccupied){
                    $("#"+newId).addClass("occupied");


                }

                    attachMouseEventsToCounter(newId);


            }
            $("#firstclone").remove();
        }
        $(".clone").hover(function(){
                    $(this).css("opacity",1.0).css("border-color","#fff").css('box-shadow','#333 5px 5px 5px');
                    var path = $(this).attr("path");
                    var pathes = path.split(",");
                    for(i in pathes){
                        $("#"+id+"Hex"+pathes[i]).css("opacity",1.0).css("border-color","#fff").css('box-shadow','#333 5px 5px 5px');
                        $("#"+id+"Hex"+pathes[i]+".occupied").css("display","block");

                    }
            //alert("A "+ path);
        },
        function(){
            $(this).css("opacity",.4).css("border-color","transparent").css('box-shadow','none');
            var path = $(this).attr("path");
            var pathes = path.split(",");
            for(i in pathes){
                $("#"+id+"Hex"+pathes[i]).css("opacity",.4).css("border-color","transparent").css('box-shadow','none');
                $("#"+id+"Hex"+pathes[i]+".occupied").css("display","none");

            }

           // alert("B");
        });
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
    var boxShadow;
    var shadow;
    for (i in units) {
        color = "#ccc #666 #666 #ccc";
        $("#"+i + " .arrow").css({opacity: "0.0"});

        boxShadow = "none";
//        $("#"+i).css({zIndex: 100});
        shadow = false;
        if(units[i].forceId !== force.attackingForceId){
            shadow = true;
        }
        switch(units[i].status){
            case <?=STATUS_CAN_REINFORCE?>:
                if(units[i].forceId === force.attackingForceId && units[i].forceId == <?=BLUE_FORCE?>){

                    color = "turquoise";
                    shadow = true;
                }
                break;
           case <?=STATUS_READY?>:
                if(units[i].forceId === force.attackingForceId){
                    $("#"+i + " .arrow").css({opacity: "0.0"});

//                    color = "turquoise";
                    shadow = true;
                }else{
//                    shadow = true;

//                    color = "purple";
                }
                break;
            case <?=STATUS_REINFORCING?>:
            case <?=STATUS_MOVING?>:
                color = "#ccc #666 #666 #ccc";
                    shadow = true;
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

                shadow = true;
//                color = "red";
                break;
            case <?=STATUS_CAN_RETREAT?>:
                color = "purple";
                break;
            case <?=STATUS_RETREATING?>:
                color = "yellow";
                break;
            case <?=STATUS_CAN_ADVANCE?>:
                color = "black";
                    shadow = true;
                break;
            case <?=STATUS_ADVANCING?>:
                    shadow = true;
                color = "cyan";
                break;
            case <?=STATUS_CAN_EXCHANGE?>:
            case <?=STATUS_CAN_ATTACK_LOSE?>:

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
                shadow = true;
                color = "turquoise";
                    }
                break;


        }
        /*$("#status").html(status);*/
//        $("#"+i).css({borderColor: color});
        $("#"+i).css({borderColor: color});
        if(!shadow){
            $("#"+i+" section").css({backgroundColor: 'rgba(0,0,0,.3)'});
        }else{
            $("#"+i+" section").css({backgroundColor: 'transparent'});
        }
        $("#"+i).css({boxShadow: boxShadow});



    }
});
x.register("combatRules", function(combatRules,data) {

    for(var combatCol = 1;combatCol <= 6;combatCol++){
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
                $("#"+cD).css({borderColor: "yellow"});
                    $("#crt").show({effect:"blind",direction:"up"});
                    var x = $("#"+cD).css('left').replace(/px/,"");
                    var mapWidth = $("body").css('width').replace(/px/,"");
//                    $("#map").css('width').replace(/px/,"");

                    if(x < mapWidth/2){
                        var wrapWid = $("#crtWrapper").css('width').replace(/px/,"");
                        var moveLeft = $("body").css('width').replace(/px/,"");
//                        alert(moveLeft);
                        $("#crtWrapper").animate({left:moveLeft - wrapWid},300);
//                        alert('this');
                    }else{
                        $("#crtWrapper").animate({left:0},300);
//                        alert("that");

                    }
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
                   var str = "<div>";
                    cdLine = "";
                    var combatIndex = 0;
                    for(i in combatRules.combats){
  //                        if(combatRules.combats[i].Die){
//                            str += " Die "+combatRules.combats[i].Die + " result "+combatRules.combats[i].combatResult;
//                        }
                        if(combatRules.combats[i].index !== null){


                            attackers = combatRules.combats[i].attackers;
                            var theta = 0;
                            for(var j in attackers){
                                theta = attackers[j];
                                theta *= 15;
                                theta += 180;
                                $("#"+j+ " .arrow").css({opacity: "1.0"});
                                $("#"+j+ " .arrow").css({webkitTransform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                                $("#"+j+ " .arrow").css({transform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});


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

                            newLine =  "<h5>odds = "+ oddsDisp +"</h5><div>Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>Terrain Shift left "+ter+ " = "+idxDisp+"</div>";
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
                    var theta = 0;
                    for(var i in attackers){
                                      theta = attackers[i];
                        theta *= 15;
                        theta += 180;
//                        $("#"+i).css({borderColor: "crimson"});
                        $("#"+i+ " .arrow").css({display: "block"});
                        $("#"+i+ " .arrow").css({opacity: "1.0"});
                        $("#"+i+ " .arrow").css({webkitTransform: 'scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});


                    }
                }
                str += "</div>";
                $("#crtOddsExp").html(activeCombatLine);
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
            str += "<div>";
            if(Object.keys(combatRules.combatsToResolve) == 0){
                str += "there are no combats to resolve<br>";
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
                    //newLine = " Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>odds = "+Math.floor(atk/def)+" : 1<br>Terrain Shift left "+ter+ " = "+idx+" : 1<br><br>";
//                    if(combatRules.lastResolveCombat === i){
//                        lastCombat = "<strong>"+newLine+"</strong>";
//                        newLine = "";
//                    }
//                    str += newLine;
                }

            }
            if(combatsToResolve){
            str += "Combats To Resolve: "+combatsToResolve;
            }
            str += "</div>";
            str += "<div>";
//            str += "</div></fieldset><fieldset><legend>Resolved Combats</legend>";
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
                        $("#crt").show({effect:"blind",direction:"up"});
                        var x = $("#"+cD).css('left').replace(/px/,"");
                        var mapWidth = $("body").css('width').replace(/px/,"");
//                    $("#map").css('width').replace(/px/,"");
                        /* STATUS_ELIMINATED */
                        if(data.force.units[cD].status != 22){
                            if(x < mapWidth/2){
                                var wrapWid = $("#crtWrapper").css('width').replace(/px/,"");
                                var moveLeft = $("body").css('width').replace(/px/,"");
    //                        alert(moveLeft);
                                $("#crtWrapper").animate({left:moveLeft - wrapWid},300);
    //                        alert('this');
                            }else{
                                $("#crtWrapper").animate({left:0},300);
    //                        alert("that");

                            }
                        }

//                        newLine += " Die "+combatRules.resolvedCombats[i].Die + " result "+combatRules.resolvedCombats[i].combatResult+"<br>";
                    }
                    newLine += " Attack = "+atkDisp +" / Defender "+def+ " odds = " + atk/def +"<br>= "+Math.floor(atk/def)+" : 1<br>Terrain Shift left "+ter+ " = "+idx+" : 1<br><br>";
                    if(cD === i){
//                        lastCombat = "<fieldset><legend>Last Resolve Combat</legend><strong>"+newLine+"</strong></fieldset>";
                        newLine = "";
                    }
//                    str += newLine;
               }

            }
            str += "Resolved Combats: "+resolvedCombats+"</div>";
            $("#status").html(lastCombat+str);

        }
    }
    $("#crt h3").html(title);
//    $("#status div").accordion({collapsible: true, active:false});
//    $("#status div").accordion("option","active",activeCombat);

});
var globInit = true;
x.fetch(0);

function seeMap(){
    $(".unit").css("opacity",.0);
}
function seeUnits(){
    $(".unit").css("opacity",1.);
}
function seeBoth(){
    $(".unit").css("opacity",.3);
}
function doit() {
    var mychat = $("#mychat").attr("value");
    $.ajax({url: "<?=site_url("wargame/add/");?>",
        type: "POST",
        data:{chat:mychat,
    },
    success:function(data, textstatus) {
        alert(data);
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

function mapMouseMove(event){
    var tar = event.target;

//    alert(event.target.x);
    var x = event.pageX - event.target.x;
    var y = event.pageY - event.target.y;
    $("#mouseMove").html("X "+x+" Y "+y);
}

function mapMouseDown(event) {


    var pixelX, pixelY;
        pixelX = event.pageX;
        pixelY = event.pageY;
    var p;
    p = $("#map").offset();
    pixelX -= p.left;
    pixelY -= p.top;

    doitMap(pixelX,pixelY);

}

function changePosition(player){
    $("#flash").html(player);
}
function counterMouseDown(event) {
    var id;
    id = $(event.target).parent().attr("id");
    doitUnit(id);
}

function nextPhaseMouseDown(event) {
    doitNext();
}



function attachMouseEventsToCounter(objectName) {
    $("#"+objectName).on('mousedown',counterMouseDown);
    return;
}

function playme(){
    var vid = $('video').get(0);
    vid.currentTime = 200;

}
function playLadies(){
    var vid = $('video').get(0);
    vid.src = "<?=base_url().'js/OldLadies.m4v'?>";
    vid.play();

}

function initialize() {

    // setup events --------------------------------------------
    $("#map").bind("mousedown",mapMouseDown);
//    $("#map").bind("mousemove",mapMouseMove);
    $(".unit").on('mousedown',counterMouseDown);
    $("#gameImages").on("mousedown",".specialHexes",mapMouseDown);
//    $("#gameViewer").hide();

    $("#nextPhaseButton").on('mousedown',nextPhaseMouseDown);
    $( "#gameImages" ).draggable({distance:40,axis:"x"});
    $("video").bind("ended",function(){
        $("#gameViewer").show(1000);
    });
    // end setup events ----------------------------------------
}
$(function() {
});
$(document).ready(initialize);
</script>