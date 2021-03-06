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


    function renderUnitNumbers(unit, moveAmount, move, clone){

        var moveLeft = unit.maxMove - unit.moveAmountUsed;

        if(move){
            theta = move.facing;
            theta *= 60;
            $(clone).find('.heading').css({opacity: "1.0"});
            $(clone).find('.heading').css({webkitTransform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(-45px)"});
            $(clone).find('.mp').html(moveAmount);
        }


//        var hexSideLen = 32;
//        var b = hexSideLen * .866;
//        unit.id = i;
//        drawHex(hexSideLen, unit, 'short');
//        var range = unit.gunRange;
//        drawHex(b * (range * 2 + 1), unit);
//        $("#"+i).hover(function(){
//            var id = $(this).attr('id');
//            $('#arrow-svg #rangeHex'+id).attr('fill-opacity',.1);
//            $('#arrow-svg #rangeHex'+id+'short').attr('style','stroke:red;');
//        }, function(){
//            var id = $(this).attr('id');
//            $('#arrow-svg #rangeHex'+id).attr('fill-opacity',0.0);
//            $('#arrow-svg #rangeHex'+id+'short').attr('style','');
//        });
//


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
            $("#" + unit.id + " .heading").css({backgroundColor: "red"});
        }
        if(unit.pDamage > 1){
            pDamage = "red";
            $("#" + unit.id + " .heading").css({display: "none"});

        }
        if( unit.parent !== 'gameImages'){
            $("#" + unit.id + " .heading").css({display: "none"});
        }
        var speed = Math.floor(unit.maxMove);
        if(speed <= 0){
            speed = "";
        }
        var hits = "";
        if(unit.hits == 1){
            hits = "white";
        }
        if(unit.hits == 2){
            hits = "red";
        }
        var torpStrength = unit.torpedoStrength;
        if(unit.torpLoad === 0){
            torpStrength = 'x';
        }
        if(unit.torpReload !== false){
            torpStrength = 'r '+unit.torpReload;
        }
        if(unit.spotted){
            $("#" + unit.id + " .top-numbers .spotted").addClass('fa-bullseye');

        }else{
            $("#" + unit.id + " .top-numbers .spotted").removeClass('fa-bullseye');
        }

        if(unit.fire){
            $("#" + unit.id + " .top-numbers .spotted").addClass('fa-fire');
            $("#" + unit.id + " .top-numbers .spotted").removeClass('fa-bullseye');
        }
        $("#" + unit.id + " .heading").css({opacity: "1.0"});
        $("#" + unit.id + " .heading").attr("src", "<?php echo base_url(); ?>js/blackArrow"+speed+".svg");
        $("#" + unit.id + " .heading").css({webkitTransform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(-45px)"});

        $("#" + unit.id + " .gunnery").html(unit.strength).addClass(damage);
        $("#" + unit.id + " .torpedo").html(torpStrength);
        $("#" + unit.id + " .torpedo").addClass(damage);
        $("#" + unit.id + " .ship-desig").addClass(hits);
        $("#" + unit.id + " .defense").html(unit.defenseStrength);
        debugger;
        $("#" + unit.id + " .mp").html(unit.newSpeed);

        return "";
    }


    function renderCrtDetails(combat){
        var atk = combat.attackStrength;
        var def = combat.defenseStrength;
        var div = atk / def;
        var ter = combat.terrainCombatEffect;
        var combatCol = combat.index + 1;

        var html = "<div id='crtDetails'>"+combat.combatLog+"</div><div>Attack = " + atk + " / Defender " + def + " = " + div + "<br>Final Column  "  + $(".col" + combatCol).html() + "</div>"
        return html;
    }
    $(document).ready( function() {
        $('.unit').hover(
            function(e) {
                if (e.altKey) {


                    $(this).animate({
                        width: '90px',
                        height: '90px',
                        marginTop: '-30px',
                        marginLeft: '-30px',
                        zIndex: 1000
                    }, 0);
                    $(this).find('.heading').animate({
                        opacity: 0.0
                    }, 0);
                    $(this).find('.top-numbers, .ship-desig, .bottom-numbers').animate({
                        fontSize: '25px',
                        height: '30px'
                    }, 0);
                    $(this).find('.top-numbers .gunnery').width(30);
                }
            },
            function() {
                $(this).animate({
                    width:'32px',
                    height: '32px',
                    marginTop: '',
                    marginLeft: '',
                    zIndex: ''
                }, 0);
                $(this).find('.heading').animate({
                    opacity:1.0
                }, 0);
                $(this).find('.top-numbers, .ship-desig, .bottom-numbers').css({
                    fontSize:'',
                    height:'10px'
                });
                $(this).find('.top-numbers .gunnery').width('');
            });
    });


//
//    function drawHex(hexside, unit, isShort){
//
//        var decoration = isShort || "";
//        var c = hexside - 0;
//        var a = (c / 2);
//        var b = .866 * c;
//        var ac = a+c;
//        var x = unit.x;
//        var y = unit.y;
//        var id = unit.id+decoration;
//        var nat = DR.players[unit.forceId];
//
//        x = x - b;
//        y = y - c;
//
//        var path = '<path class="'+nat+' '+decoration+'" stroke="red" id="rangeHex'+id+'" fill="#000" fill-opacity="0" stroke-width="2" d="M '+x+' ' + (ac + y) + ' L ' + x + ' '+ (a + y) + ' L ' + (b + x) + ' ' + y;
//        path += ' L ' + (2 * b + x) + ' ' + (a + y) + ' L ' + (2 * b + x) + ' ' + (ac + y) + ' L ' + (b + x) + ' '+ (2 * c + y)+' Z"></path>';
//
//        $('#arrow-svg').append(path);
//        $('#arrow-svg').html($('#arrow-svg').html());
//    }
//
//    function clearHexes(){
//        $('svg path').remove();
//    }

</script>