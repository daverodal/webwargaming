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


    function renderUnitNumbers(unit, moveAmount){

        debugger;
        var theta = unit.facing;
        theta *= 60;
        var damage = "";
        var pDamage = "";
        if(unit.wDamage == 1){
            damage = "white";
        }
        if(unit.wDamage > 1){
            damage = "red";
        }
        if(unit.pDamage == 1){
            pDamage = "white";
        }
        if(unit.pDamage > 1){
            pDamage = "red";
        }
        $("#" + unit.id + " .heading").css({opacity: "1.0"});
        $("#" + unit.id + " .heading").css({webkitTransform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(-45px)"});

        $("#" + unit.id + " .gunnery").html(unit.strength).addClass(damage);
        $("#" + unit.id + " .torpedo").html(unit.torpedoStrength);
        $("#" + unit.id + " .torpedo").addClass(damage);
        $("#" + unit.id + " .ship-desig").addClass(pDamage);


        return;
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

        if(unit.class == 'air'){
            $("#"+unit.id+" .air-strength").html(unit.airStrength)
        }
        var html =  move + "</span>";
        return html;



    }


    function renderCrtDetails(combat){
        var atk = combat.attackStrength;
        var def = combat.defenseStrength;
        var div = atk - def;
        var ter = combat.terrainCombatEffect;
        var combatCol = combat.index + 1;

        var html = "<div id='crtDetails'>"+combat.combatLog+"</div><div>Attack = " + atk + " - Defender " + def + " = " + div + "<br>Final Column  "  + $(".col" + combatCol).html() + "</div>"
        return html;
    }

</script>