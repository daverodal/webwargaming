<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
<style type="text/css">
    body{
    }
    #map{
        width: 1023px;
        left: 0px;
        top: 00px;
    }
    .blueUnit, .Russian{
        background-color:rgb(132,181,255);
    }
    img.counter{
        width:32px;
        height:16px;
    }
    .unit div{
        margin-top:-5px !important;
        background:white;
        font-size:12px;
        font-weight: bold;
        font-family: serif;
    }
    .RussianaVP{
        color:rgb(132,181,255);
        background: transparent;
        opacity:1.0;
    }
    .PrussianlVP{
        color:rgb(239,115,74);
        background: transparent;
    }
    .russian{
        background-color:gold;
    }
    .armyGreen, .russian{
    background-color:  rgb(148,189,74);
    }
    .Prussian{
        background-color:white;
    }
    .lightYellow, .Prussian{
        background-color: white;
    }
    .flesh{
        background-color: rgb(239,198,156);
    }
    .brightRed{
        background-color: rgb(223,88,66);
    }
    p.forceMarch{
        position:absolute;
        top:-18px;
        right:0px;
        display:none;
        color:white;
    }
#OBC{
    left:-240px;
}
#OBC{
    background:white;
    width:514px;
}
#TEC{
    background:white;
    width:579px;
    left:-230px;
}

#crt{
    width:476px;
}
.tableWrapper.alt{
    display:none;
}
    .alt{
        display:none;
        width:476px;
    }
    .Prussian div{
        color:black;
    }
    .russian div{
        color:black;
    }

    .playerOne{
        background:rgb(255,253,127);
        border-color:rgb(255,253,127) !important;
    }
    .playerTwo{
        background:rgb(76,184,0);
        border-color:rgb(76,184,0) !important;
    }
#TECWrapper .closer{
    height:0px;
    padding:0px;
}
    #VCWrapper .closer{
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
#TECWrapper .colOne.trailHex{
    background-image: url('<?=base_url()?>js/trail.png');
    background-position:0px -26px;
    background-size:516px;
    /* TODO why this different from others */
}
#TECWrapper .colOne{
    background-size:400px;
    width:220px;
    height:56px;
    margin-left:20px;
}
#TECWrapper .colTwo{
    width:170px;
    padding-right:10px;
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




    #VCWrapper .colOne{
        background-size:400px;
        width:220px;
        height:56px;
        margin-left:20px;
    }
    #VCWrapper .colTwo{
        width:170px;
        padding-right:10px;
    }
    #VCWrapper .colThree{
        width:136px;
    }
    #VCWrapper .colOne span{
        margin-top:12px;
        margin-left: 80px;
        display:inline-block;
    }
    #VCWrapper .colTwo, #VCWrapper .colThree{
        margin-top:12px;
    }
    #VCWrapper .colOne,
    #VCWrapper .colTwo,
    #VCWrapper .colThree{
        float:left;
    }
    #VCWrapper div{
        /*height:112px;*/
    }
    #VCWrapper li{
        list-style-type: none;
        /*height:79px;*/
        border:0 solid black;
        border-width:1px 1px 0 1px;
        padding:5px 20px 5px 0px;
    }
    #VCWrapper img, #VCWrapper div{
    }
    #GRWrapper h4{
        width:auto !important;
    }
#GR{
    width:600px;
    left:-260px
}
    #GRWrapper{
        width:10%;
        left:48%;
        bottom:0px;
    }
</style>
<script type="text/javascript">
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
                if(combatRules.combats[cD].useAlt){
                    $('.tableWrapper.main').hide();
                    $('.tableWrapper.alt').show();
                }else{
                    $('.tableWrapper.main').show();
                    $('.tableWrapper.alt').hide();
                }
                for(var loop in defenders){
                    $("#"+loop).css({borderColor: "yellow"});
                }
                if(!chattyCrt){
                    $("#crt").show({effect:"blind",direction:"up"});
                    chattyCrt = true;
                }
                fixCrt();
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

                    newLine =  "<h5>odds = "+ oddsDisp +"</h5><div>Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>Combined Arms Shift "+ter+ " = "+$(".col"+combatCol).html()+"</div>";
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
                    newLine =  "<h5>odds = "+ oddsDisp +"</h5><div>Attack = "+atkDisp+" / Defender "+def+ " = " + atk/def +"<br>Combined Arms Shift "+ter+ " = "+idxDisp+"</div>";
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
                    newLine += " Attack = "+atkDisp +" / Defender "+def+ " odds = " + atk/def +"<br>= "+Math.floor(atk/def)+" : 1<br>Combined Arms Shift "+ter+ " = "+idx+" : 1<br><br>";
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
</script>