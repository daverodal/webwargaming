<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
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
<style type="text/css">
<?php @include "all.css";?>
</style>
<script type="text/javascript">
    x.register("specialHexes", function(specialHexes, data) {
        $('.specialHexes').remove();
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
                            var newVP = $('<div style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').insertAfter('#special'+i);
                            $(newVP).animate({top:y-30,opacity:0.0},1900,function(){
                                $(this).remove();
                            });
                        }
                    });

                }else{
                    if(specialHexes[i] == 1 && i != 'x416y357'){
                        $("#gameImages").append('<div id="special'+i+'" class="specialHexes fa fa-adjust supply"></div>');
                        $("#special"+i).css({top:y+"px", left:x+"px"}).addClass(lab[specialHexes[i]]);
                    }else{
                        $("#gameImages").append('<div id="special'+i+'" class="specialHexes">'+lab[specialHexes[i]]+'</div>');
                        $("#special"+i).css({top:y+"px", left:x+"px"}).addClass(lab[specialHexes[i]]);                    }
                }

            }
        }

        for(var id in data.specialHexesVictory)
        {
            if(data.specialHexesChanges[id]){
                continue;
            }
            var hexPos = id.replace(/\.\d*/g,'');
            var x = hexPos.match(/x(\d*)y/)[1];
            var y = hexPos.match(/y(\d*)\D*/)[1];
            var newVP = $('<div  style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').appendTo('#gameImages');
            $(newVP).animate({top:y-30,opacity:0.0},1900,function(){
                var id = $(this).attr('id');

                $(this).remove();
            });
        }


    });


    x.register("moveRules", function(moveRules,data) {
        var str;
        $(".clone").remove();
        $('.selected').removeClass('selected');
        if(moveRules.movingUnitId >= 0){
            var opacity = .4;
            var borderColor = "#ccc #333 #333 #ccc";
            if(moveRules.moves){
                id = moveRules.movingUnitId;
                for(var i in moveRules.moves){
                    debugger;

                    var color = $("#"+i).css('background-color');
                    debugger;



                    $("#"+i).addClass('selected');


                }
                return;
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

                    var newLabel = label.replace(/((?:<span[^>]*>)?[-+ru](?:<\/span>)?).*/,"$1 "+moveRules.moves[i].pointsLeft);
                    newLabel = renderUnitNumbers(data.mapUnits[id], moveRules.moves[i].pointsLeft);
                    var txt = secondGenClone.find('div.unit-numbers span').html(newLabel).text();
                    secondGenClone.find('div.unit-numbers span').addClass('infoLen'+txt.length);
                    secondGenClone.find('.counterWrapper .guard-unit').addClass('infoLen'+newLabel.length);
                    if(moveRules.moves[i].isOccupied){
                        secondGenClone.addClass("occupied");


                    }
                    /* left and top need to be set after appendTo() */

                    secondGenClone.appendTo('#gameImages').css({left:moveRules.moves[i].pixX - width/2 +"px",top:moveRules.moves[i].pixY - height/2 +"px"});
                    /* apparently cloning attaches the mouse events */
                }

                $("#firstclone").remove();
            }

        }
    });


    $(document).ready(function(){

        $(".area").on("click", function(){
            console.log($(this).attr('id'));
        });

    });



    function LightenDarkenColor(col, amt) {

        var usePound = false;

        if (col[0] == "#") {
            col = col.slice(1);
            usePound = true;
        }

        var num = parseInt(col,16);

        var r = (num >> 16) + amt;

        if (r > 255) r = 255;
        else if  (r < 0) r = 0;

        var b = ((num >> 8) & 0x00FF) + amt;

        if (b > 255) b = 255;
        else if  (b < 0) b = 0;

        var g = (num & 0x0000FF) + amt;

        if (g > 255) g = 255;
        else if (g < 0) g = 0;

        return (usePound?"#":"") + (g | (b << 8) | (r << 16)).toString(16);

    }
</script>
