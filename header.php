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

    var html;
    html = gameRules.phase_name[gameRules.phase] + " - " + gameRules.mode_name[gameRules.mode];
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
    for (i in mapUnits) {
        width = $("#"+i).width();
        height = $("#"+i).height();
        $("#"+i).css({left: -1+mapUnits[i].x-width/2+"px",top:-1+mapUnits[i].y-height/2+"px"});
        var img = $("#"+i).attr("src");
        if(mapUnits[i].isReduced){
            img = img.replace(/(.*[0-9])(\.png)/,"$1reduced.png");
        }else{
            img = img.replace(/([0-9])reduced\.png/,"$1.png");
        }
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
        switch(units[i].status){
            case <?=STATUS_CAN_REINFORCE?>:
                if(units[i].forceId === force.attackingForceId && units[i].forceId == <?=BLUE_FORCE?>){

                    color = "turquoise";
                }
                break;
           case 1:
                if(units[i].forceId === force.attackingForceId){

                    color = "#66f";
                }
                break;
            case <?=STATUS_REINFORCING?>:
            case <?=STATUS_MOVING?>:
                color = "orange";
                status += " "+units[i].moveAmountUsed+" MF's Used";
                break;
            case 6:
                color = "transparent";
                break;
//            case 8:
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
            case 27:
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
    str = ""
    if(combatRules ){
        cD = combatRules.currentDefender;
        if(cD !== false){
            if(combatRules.combats){

                $("#"+cD).css({borderColor: "white"});
               if(Object.keys(combatRules.combats[cD].attackers).length != 0){
                    combatCol = combatRules.combats[cD].index + 1;
                   if(combatCol >= 1){
                    $(".col"+combatCol).css('background-color',"rgba(255,255,1,.6)");
                    if(combatRules.combats[cD].Die !== false){
                        $(".row"+combatRules.combats[cD].Die+" .col"+combatCol).css('font-size',"110%");
                        $(".row"+combatRules.combats[cD].Die+" .col"+combatCol).css('background',"#eee");
                    }
                   }
                    var newline = "";
                    for(i in combatRules.combats){
                        if(combatRules.combats[i].Die){
                            str += " Die "+combatRules.combats[i].Die + " result "+combatRules.combats[i].combatResult;
                        }
                        if(combatRules.combats[i].index !== null){
                            var atk = combatRules.combats[i].attackStrength;
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
                            var newline;
                            newLine = "Defender "+i+" Attack = "+atk+" / Defender "+def+ " = " + atk/def +"<br>= "+ oddsDisp +"<br>Terrain Shift left "+ter+ " = "+idxDisp+"<br>";
                            if(cD == i){
                                newLine = "<strong>"+newLine+"</strong>";
                            }
                            str += newLine+"<br>";
                        }

                    }
                    attackers = combatRules.combats[cD].attackers;
                    for(var i in attackers){
                        $("#"+i).css({borderColor: "crimson"});

                    }
                }
                $("#status").html(str);

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
                if(combatRules.combatsToResolve[i].index !== null){
                     var atk = combatRules.combatsToResolve[i].attackStrength;
                    var def = combatRules.combatsToResolve[i].defenseStrength;
                    var ter = combatRules.combatsToResolve[i].terrainCombatEffect;
                    var idx = combatRules.combatsToResolve[i].index+ 1;
                    newLine = "Defender "+i+" Attack = "+atk+" / Defender "+def+ " = " + atk/def +"<br>= "+Math.floor(atk/def)+" : 1<br>Terrain Shift left "+ter+ " = "+idx+" : 1<br>";
                    if(combatRules.lastResolveCombat === i){
                        newLine = "<strong>"+newLine+"</strong>";
                    }
                    str += newLine+"<br>";
                }

            }
            str += "Resolved Combats<br>";
            for(i in combatRules.resolvedCombats){
                if(combatRules.resolvedCombats[i].index !== null){
                     atk = combatRules.resolvedCombats[i].attackStrength;
                     def = combatRules.resolvedCombats[i].defenseStrength;
                     ter = combatRules.resolvedCombats[i].terrainCombatEffect;
                     idx = combatRules.resolvedCombats[i].index+ 1;
                    newLine = "";
                    if(combatRules.resolvedCombats[i].Die){
                        newLine += " Die "+combatRules.resolvedCombats[i].Die + " result "+combatRules.resolvedCombats[i].combatResult+"<br>";
                    }
                    newLine += "Defender "+i+" Attack = "+atk+" / Defender "+def+ " = " + atk/def +"<br>= "+Math.floor(atk/def)+" : 1<br>Terrain Shift left "+ter+ " = "+idx+" : 1<br>";
                    if(cD == i){
                        newLine = "<strong>"+newLine+"</strong>";
                    }
                    str += newLine+"<br>";
               }

            }
            $("#status").html(str);

        }

//        $("#status").html(str);
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
    $.ajax({url: "<?=site_url("wargame/unit");?>/"+id,
        type: "POST",
        data:{unit:id,
    },
    success:function(data, textstatus) {
    }
});
$("#mychat").attr("value", "");
}
function doitMap(x,y) {
    $.ajax({url: "<?=site_url("wargame/map/");?>/",
        type: "POST",
        data:{x:x,
        y:y
    },
    success:function(data, textstatus) {
    }
});

}
function doitNext() {
    $.ajax({url: "<?=site_url("wargame/phase/");?>/",
        type: "POST",

        success:function(data, textstatus) {
    }
});

}

</script>