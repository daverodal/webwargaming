<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?><link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
<script type="text/javascript">
$(document).ready(function(){
    if(!DR){
        DR = {};
    }
    if(!DR.playerOne){
        DR.playerOne = "playerOne";
    }
    if(!DR.playerTwo) {
        DR.playerTwo = "playerTwo";
    }
    $("#altTable").on('click', function(){
        $(this).hide();
        $("#mainTable").show();
        $('.tableWrapper.main').hide();
        $('.tableWrapper.determined').hide();
        $('.tableWrapper.alt').show();
    });
    $("#mainTable").on('click', function(){
        $(this).hide();
        $("#detTable").show();
        $('.tableWrapper.alt').hide();
        $('.tableWrapper.determined').hide();
        $('.tableWrapper.main').show();
    });
    $("#detTable").on('click', function(){
        $(this).hide();
        $("#mainTable").hide();
        $("#altTable").show();
        $('.tableWrapper.alt').hide();
        $('.tableWrapper.main').hide();
        $('.tableWrapper.determined').show();
    });
    $("#altTable").show();
    $("#mainTable").hide();
    $("#detTable").hide();
    $('.tableWrapper.determined').hide();
    $(".tableWrapper.alt").hide();
    $(".tableWrapper.main").show();


});
x.register("mapUnits", function(mapUnits) {
    var str;
    var fudge;
    var x,y;
    var beforeDeploy = $("#deployBox").children().size();
    DR.stackModel = {};
    DR.stackModel.ids = {};
    clearHexes();

    for (i in mapUnits) {
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
            var hexSideLen = 35.2;
            var b = hexSideLen * .866;
            var unit = mapUnits[i];
            unit.id = i;
            drawHex(hexSideLen, unit, 'short');
            var range = mapUnits[i].range;
            drawHex(b * (range * 2 + 1), unit);
            $("#"+i).hover(function(){
                var id = $(this).attr('id');
                $('#arrow-svg #rangeHex'+id).attr('fill-opacity',.1);
                $('#arrow-svg #rangeHex'+id).attr('style', 'stroke-opacity:1');
                $('#arrow-svg #rangeHex'+id+'short').attr('style','stroke:red;stroke-opacity:1;');
            }, function(){
                var id = $(this).attr('id');
                $('#arrow-svg #rangeHex'+id).attr('fill-opacity',0.0);
                $('#arrow-svg #rangeHex'+id).attr('style','');
                $('#arrow-svg #rangeHex'+id+'short').attr('style','');
            });
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
        var html = reduceDisp + str + symb + move + "</span>"
        $("#"+i+" .unitNumbers.attack").html(str);
        var len  = $("#"+i+" .unitNumbers.attack").text().length;
        $("#"+i+" unitNumbers.attack").addClass("infoLen"+len);
        $("#"+i+" .unitNumbers.movement").html(move);
        var len  = $("#"+i+" .unitNumbers.movement").text().length;
        $("#"+i+" unitNumbers.movement").addClass("infoLen"+len);
        if(mapUnits[i].isImproved){
            $("#"+i).css('border-style','dotted');
        }
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
                $("#"+newId+".unitNumbers.movement").html(moveRules.hexPath[i].pointsLeft);
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

            var label = MYCLONE.find("unitNumbers.movement").html();
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

                var newLabel = moveRules.moves[i].pointsLeft;
                secondGenClone.find('.unitNumbers.movement').html(newLabel).addClass('infoLen'+newLabel.length);
                if(moveRules.moves[i].isOccupied){
                    secondGenClone.addClass("occupied");
                }
                /* left and top need to be set after appendTo() */

                secondGenClone.appendTo('#gameImages').css({left:moveRules.moves[i].pixX - width/2 +"px",top:moveRules.moves[i].pixY - height/2 +"px"});
                /* apparently cloning attaches the mouse events */
            }

            $("#firstclone").remove();
            $("#firstclone").remove();
        }

    }
});
x.register("specialHexes", function(specialHexes, data) {
    $(".specialHexesVP").remove();
    var lab = ['unowned','<?=strtolower($force_name[1])?>','<?=strtolower($force_name[2])?>'];
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
        var newVp = $('<div style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:30px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').appendTo('#gameImages');
//        $(newVp).animate({top:y-30,opacity:0.0},1900,function(){
//            $(this).remove();
//        });
    }


});
/*
 * for now, remove this cuz it's very very slow...... (troops only!)
 */
x.register("sentBreadcrumbs", function(breadcrumbs,data) {
});



    function drawHex(hexside, unit, isShort){

        var decoration = isShort || "";
        var c = hexside - 0;
        var a = (c / 2);
        var b = .866 * c;
        var ac = a+c;
        var x = unit.x;
        var y = unit.y;
        var id = unit.id+decoration;
        var nat = DR.players[unit.forceId];
        var type= nat+'-'+unit.class;
        var cls = unit.class;

        x = x - b;
        y = y - c;

        var path = '<path class="range-hex '+nat+' '+decoration+' '+cls+'" stroke="transparent" id="rangeHex'+id+'" fill="#000" fill-opacity="0" stroke-width="2" d="M '+x+' ' + (ac + y) + ' L ' + x + ' '+ (a + y) + ' L ' + (b + x) + ' ' + y;
        path += ' L ' + (2 * b + x) + ' ' + (a + y) + ' L ' + (2 * b + x) + ' ' + (ac + y) + ' L ' + (b + x) + ' '+ (2 * c + y)+' Z"></path>';

        $('#arrow-svg').append(path);
        $('#arrow-svg').html($('#arrow-svg').html());
    }

    function clearHexes(){
        $('svg path').remove();
    }

    $("#all-on").click(function () {
        $('path.range-hex').attr('style','stroke-opacity:1');
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });

    $("#all-off").click(function () {
        $('path.range-hex').attr('style','stroke-opacity:0');
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });

</script>