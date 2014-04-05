<?php global $force_name;?>
<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<?php include_once("commonStyle.php");?>
<script>
var zoomed = false;
var globalZoom = 1;

$(document).ready(function(){
    DR = {};
    DR.crtDetails = false;

    $("#crtDetailsButton").on('click',function(){
        $('#crtDetails').toggle(function(){
            DR.crtDetails = $(this).css('display') == 'block';
        });
        return false;
    });

    $("#phaseClicks").on("click",".phaseClick",function(){
        x.timeTravel = true;
        if(x.current){
            x.current.abort();
        }
        var click = $(this).attr('data-click');
        x.fetch(click);
    });
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
    $("#timeBranch").click(function(){
        x.timeTravel = true;
        x.timeBranch = true;
        if(x.current){
            x.current.abort();
        }
        var click = $("#clickCnt").html();
        x.fetch(click);
        $("#TimeWrapper .WrapperLabel").click();
    });
    $("#timeLive").click(function(){
        $("#TimeWrapper .WrapperLabel").click();
        x.timeTravel = false;
        x.fetch(0);
    });
    $("#showHexNums").on('click', function(){
        var src = $("#map").attr('src');
        if(src.match(/HexNumbers/)){
            src = src.replace(/HexNumbers/,"");
        }else{
            src = src.replace(/Small/,"HexNumbersSmall");
        }
        $("#map").attr('src',src);
    });
    fixHeader();
    $(window).resize(fixItAll);
});
function fixItAll(){
    fixHeader();
//    alert("WHY");
//    fixCrt();
}
function fixHeader(){
    height = $("#crtWrapper h4").height();
        $("#bottomHeader").css("height",height);
//    $("#crtWrapper").animate({left:0},300);
//    $("#crt").animate({left:0},300);
    var headerHeight = $("#header").height();
    $("#content").css("margin-top",$("#header").height() + 10);
    var bodyHeight = $(window).height();
    var bodyWidth = $(window).width();
    var deployHeight = $("#deployWrapper:visible").height();
    var deadHeight = $("#deadpile:visible").height();
    if(deadHeight){
        deadHeight += 10 + 10 + 4 + 4;
    }
    if(deployHeight){
        deployHeight += 10 + 10 + 4 + 4;
    }else{
        deployHeight = 0;
    }
    var height = bodyHeight - deployHeight - deadHeight - headerHeight - 40;
    var width = bodyWidth - 35;

//    var mapHeight = $("#map").height();
//    var mapWidth = $("#map").width();
//
//    var containerWidth;
//    var containerMargin;
//    if(mapWidth > width){
//        diff = mapWidth - width;
//        containerWidth = mapWidth + diff;
//        containerMargin = 0 - diff;
//    }else{
//        containerWidth = "auto";
//        containerMargin = 0;
//    }
//
//    var containerHeight;
//    var containerTopMargin;
//    if(mapWidth > width){
//        diff = mapHeight - height;
//        containerHeight = mapHeight + diff;
//        containerTopMargin = 0 - diff;
//    }else{
//        containerHeight = "auto";
//        containerTopMargin = 0;
//    }
//
//    $("#gameContainer").css({marginLeft:containerMargin,width:containerWidth,height:containerHeight,marginTop:containerTopMargin});
//    $("#gameImages").width(mapWidth).height(mapHeight);

    $("#gameViewer").height(height);
    $("#gameViewer").width(width);
}
</script>
<?php include_once("commonSync.php");?>
<script>
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
        data:{chat:mychat},
    success:function(data, textstatus) {
        alert(data);
    }
});
$("#mychat").attr("value", "");
}
function doitKeypress(key) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor:"wait"});
    $(this).css({cursor:"wait"});
    $("#"+id+"").addClass("pushed");

    $("#comlink").html('waiting');
    $.ajax({url: "<?=site_url("wargame/poke");?>/",
        type: "POST",
        data:{id:key,event : <?=KEYPRESS_EVENT?>},
        error:function(data,text,third){
            try{
                obj = jQuery.parseJSON(data.responseText);
            }catch(e){
//                alert(data);
            }
            if(obj.emsg){
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor:"auto"});
            $(this).css({cursor:"auto"});
            $("#"+id+"").removeClass("pushed");
            $("#comlink").html('Working');
        },
        success:function(data, textstatus) {
            try{
                var success = +$.parseJSON(data).success;
            }catch(e){
//                alert(data);
            }
            if(success){
                playAudioLow();

            }else{
                playAudioBuzz();
            }
            $('body').css({cursor:"auto"});
            $(this).css({cursor:"auto"});
            $("#"+id+"").removeClass("pushed");


        }
    });
    $("#mychat").attr("value", "");
}
function doitCRT(id,event) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor:"wait"});
    $(this).css({cursor:"wait"});

    $("#comlink").html('waiting');
    $.ajax({url: "<?=site_url("wargame/poke");?>/",
        type: "POST",
        data:{id:id,event : event.shiftKey ? <?=COMBAT_PIN_EVENT;?> : <?=COMBAT_PIN_EVENT?>},
        error:function(data,text,third){
            try{
                obj = jQuery.parseJSON(data.responseText);
            }catch(e){
//                alert(data);
            }
            if(obj.emsg){
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor:"auto"});
            $(this).css({cursor:"auto"});
            $("#comlink").html('Working');
        },
    success:function(data, textstatus) {
        try{
            var success = +$.parseJSON(data).success;
        }catch(e){
//            alert(data);
        }
        if(success){
            playAudioLow();

        }else{
            playAudioBuzz();
        }
        $('body').css({cursor:"auto"});
        $(this).css({cursor:"auto"});
    }
});
$("#mychat").attr("value", "");
}
function doitUnit(id,event) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor:"wait"});
    $(this).css({cursor:"wait"});
    $("#"+id+"").addClass("pushed");

    $("#comlink").html('waiting');
    $.ajax({url: "<?=site_url("wargame/poke");?>/",
        type: "POST",
        data:{id:id,event : event.shiftKey ? <?=SELECT_SHIFT_COUNTER_EVENT;?> : <?=SELECT_COUNTER_EVENT?>},
        error:function(data,text,third){
            try{
                obj = jQuery.parseJSON(data.responseText);
            }catch(e){
//                alert(data);
            }
            if(obj.emsg){
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor:"auto"});
            $(this).css({cursor:"auto"});
            $("#"+id+"").removeClass("pushed");
            $("#comlink").html('Working');
        },
        success:function(data, textstatus) {
            try{
                var success = +$.parseJSON(data).success;
            }catch(e){
//            alert(data);
            }
            if(success){
                playAudioLow();

            }else{
                playAudioBuzz();
            }
            $('body').css({cursor:"auto"});
            $(this).css({cursor:"auto"});
            $("#"+id+"").removeClass("pushed");


        }
    });
    $("#mychat").attr("value", "");
}
function doitMap(x,y) {
    playAudio();
//    $('body').css({cursor:"wait"});
//    $(this).css({cursor:"wait"});
//    $("#comlink").html('waiting');

    $.ajax({url: "<?=site_url("wargame/poke/");?>/",
        type: "POST",
        data:{x:x,
        y:y,
            event : <?=SELECT_MAP_EVENT?>
    },
    success:function(data, textstatus) {
        try{
            var success = +$.parseJSON(data).success;
        }catch(e){
//            alert(data);
        }
        if(success){
            playAudioLow();

        }else{
            playAudioBuzz();
        }
        $('body').css({cursor:"auto"});
        $(this).css({cursor:"auto"});
    },
        error:function(data,text){
            try{
                var success = +$.parseJSON(data).success;
            }catch(e){
//                alert(data);
            }
            playAudioBuzz();
            $('body').css({cursor:"auto"});
            $(this).css({cursor:"auto"});
        }
});
return true;
}
function doitNext() {
    playAudio();

    $.ajax({url: "<?=site_url("wargame/poke/");?>/",
        type: "POST",
        data:{event: <?=SELECT_BUTTON_EVENT?>},
        success:function(data, textstatus) {
            try{
                var success = +$.parseJSON(data).success;
            }catch(e){
//                alert(data);
            }
            playAudioLow();

    },     error:function(data,text){
            try{
                var success = +$.parseJSON(data).success;
            }catch(e){
//                alert(data);
            }
            playAudioBuzz();
            $('body').css({cursor:"auto"});
            $(this).css({cursor:"auto"});
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

    var x = event.pageX - event.target.x;
    var y = event.pageY - event.target.y;
    $("#mouseMove").html("X "+x+" Y "+y);
}
function mapStop(event){

    $("#map").data('did-drag',true);
    event.stopPropagation();
//    fixCrt();
}
function mapClick(event) {
    var didDrag = $("#map").data('did-drag');
    $("#map").data('did-drag',false);
    if(didDrag){
        return;
    }

    var pixelX, pixelY;
    pixelX = event.pageX;
    pixelY = event.pageY;
    var p;
    p = $("#gameImages").offset();
    pixelX -= p.left;
    pixelY -= p.top;

    if(zoomed){
        doZoom(event);
        zoomed = false;
        return;
    }
    /*
     * Account for maps zooming in and out
     */
    if(!globalZoom){
        globalZoom = 1;
    }
    pixelX /= globalZoom;
    pixelY /= globalZoom;
    doitMap(pixelX,pixelY);
}

function changePosition(player){
    $("#flash").html(player);
}
function doZoom(event){

    var pixelX, pixelY;
    pixelX = event.pageX;
    pixelY = event.pageY;
    var p;
    p = $("#gameViewer").offset();
    pixelX -= p.left;
    pixelY -= p.top;

    zoomed = false;
    width = $("body").width();
    var left = (pixelX /-.3)+(width/2);
    var viewerHeight = $("#gameViewer").height()/2;
    var top = (pixelY /-.3)+(viewerHeight);

    if(left > 0){
        left = 0;
    }
    if(top > 0){
        top = 0;
    }
    // TODO: make this more modern
    $("#gameImages").css({MozTransform:"translate(0,0) scale(1.0)"});
$("#gameImages").animate({zoom:1.0,left:left,top:top},1500);
}
function counterClick(event) {
    if(zoomed){
        doZoom(event);
        return;
    }

    var didDrag = $("#map").data('did-drag');
    $("#map").data('did-drag',false);
    if(didDrag){
        return;
    }

    var id;
    id = $(event.target).attr('id');
    if(!id){
        id = $(event.target).parent().attr("id");
    }
    doitUnit(id,event);
}

function nextPhaseMouseDown(event) {
    doitNext();
}

var mute = false;

function playAudio(){
    var aud = $('.pop').get(0);
    <!--    aud.src = "--><?//=base_url().'js/pop.m4a'?><!--";-->
    if(aud && !mute){
        aud.play();
    }

}
function playAudioLow(){
    var aud = $('.poop').get(0);
    <!--    aud.src = "--><?//=base_url().'js/pop.m4a'?><!--";-->
    if(aud && !mute){
        aud.play();
    }

}
function unMuteMe(){
    mute = false;
    return true;
}
function muteMe(){
    mute = true;
    return true;
}
function playAudioBuzz(){
    var aud = $('.buzz').get(0);
    <!--    aud.src = "--><?//=base_url().'js/pop.m4a'?><!--";-->
    if(aud){
        aud.play();
    }

}


function initialize() {

    // setup events --------------------------------------------

    $(".unit").on('click',counterClick);
    $("#crt #odds span").on('click',function(event){
        var col = $(event.target).attr('class');
        col = col.replace(/col/,'');
        doitCRT(col,event);
    })
    $("#gameImages").on("click",".specialHexes",mapClick);

    $("#nextPhaseButton").on('click',nextPhaseMouseDown);
    $("#gameImages" ).draggable({stop:mapStop, distance:15});
    $("#gameImages #map").on("click",mapClick);
    $("#floatMessage").draggable({stop:function(){
        $(this).attr('hasDragged','true');
    }});
    $("#crt").draggable().css({cursor:"move"});
    $("#muteButton").click(function(){
       if(!mute){
           $("#muteButton").html("un-mute");
           muteMe();

       }else{
           $("#muteButton").html("mute");
           unMuteMe();
           playAudio();
       }
    });
    // end setup events ----------------------------------------


    var Player = 'Markarian';
//    $("#OBCWrapper .WrapperLabel").click(function(){
//        alert("HELY");
//       $(".dropDown > div").show({effect:"blind",direction:"up",done:function(){
//       alert(this);});
//       }
//    });
    $( ".dropDown .WrapperLabel" ).click(function() {
        $(this).parent().siblings(".dropDown").children('div').hide({effect:"blind",direction:"up",complete:function(){
                $(this).parent().children('h4').removeClass('dropDownSelected');
        }});

        $(this).next().toggle({effect:"blind",direction:"up",complete:function(){
            if($(this).is(":visible")){
                $(this).parent().children('h4').addClass('dropDownSelected');
            }else{
                $(this).parent().children('h4').removeClass('dropDownSelected');
            }
        }
        });
//        $( "#info" ).hide({effect:"blind",direction:"up"});
//        $( "#menu" ).hide({effect:"blind",direction:"up"});
//        $( "#OBC" ).toggle({effect:"blind",direction:"up"});
//        $( "#TEC" ).hide({effect:"blind",direction:"up"});
//        $( "#VC" ).hide({effect:"blind",direction:"up"});
    });
//    $( "#TECWrapper .WrapperLabel" ).click(function() {
//        $( "#info" ).hide({effect:"blind",direction:"up"});
//        $( "#menu" ).hide({effect:"blind",direction:"up"});
//        $( "#OBC" ).hide({effect:"blind",direction:"up"});
//        $( "#VC" ).hide({effect:"blind",direction:"up"});
//        $( "#TEC" ).toggle({effect:"blind",direction:"up"});
//    });
//    $( "#VCWrapper .WrapperLabel" ).click(function() {
//        $( "#info" ).hide({effect:"blind",direction:"up"});
//        $( "#menu" ).hide({effect:"blind",direction:"up"});
//        $( "#OBC" ).hide({effect:"blind",direction:"up"});
//        $( "#TEC" ).hide({effect:"blind",direction:"up"});
//        $( "#VC" ).toggle({effect:"blind",direction:"up"});
//    });
    $( "#menuWrapper .WrapperLabel" ).click(function() {
        $( ".dropDown > div" ).hide({effect:"blind",direction:"up"});
        $( "#info" ).hide({effect:"blind",direction:"up"});
        $( "#menu" ).toggle({effect:"blind",direction:"up"});
    });
    $( "#infoWrapper .WrapperLabel" ).click(function() {
        $( ".dropDown > div" ).hide({effect:"blind",direction:"up"});
        $( "#menu" ).hide({effect:"blind",direction:"up"});
        $( "#info" ).toggle({effect:"blind",direction:"up"});
    });
//    $("#GRWrapper .WrapperLabel").click(function(){
//        $( "#OBC" ).hide({effect:"blind",direction:"up"});
//        $( "#TEC" ).hide({effect:"blind",direction:"up"});
//        $( "#VC" ).hide({effect:"blind",direction:"up"});
//        $( "#info" ).hide({effect:"blind",direction:"up"});
//        $( "#menu" ).hide({effect:"blind",direction:"up"});
//        $( "#crt" ).hide({effect:"blind",direction:"up"});
//        $("#GR").toggle({effect:"blind",direction:"up",complete:function(){
//            fixHeader();
//        }});
//    });
    $("#jumpWrapper .WrapperLabel").click(function(){
//        $('.dropDown > div').hide({effect:"blind",direction:"up"});
//        $('.dropDownSelected').removeClass('dropDownSelected');
//        $(this).parent().siblings('.dropDown').children('.dropDownSelected').removeClass('dropDownSelected');

        $( "#crt" ).hide({effect:"blind",direction:"up"});
        $("#gameContainer").css("margin",0);
        $("#gameImages").css({zoom:.3,overflow:"visible"});
        // TODO: make this more modern (transform)
        $("#gameImages").css({MozTransform:"translate(-33%, -33%) scale(.3)"});
        $("html, body").animate({scrollTop:"0px"});


        $("#gameImages").css('left',0);
        $("#gameImages").css('top',0);
        zoomed = true;
    });
    $("#crtWrapper .WrapperLabel .goLeft").click(function(){
//    $("#crtWrapper").css("float","left");
        $("#crtWrapper").animate({left:0},300);
        $("#crt").animate({left:"0px",top:26},300);

        return false;
    });
    $("#crtWrapper .WrapperLabel .goRight").click(function(){
        var wrapWid = $("#crtWrapper").css('width').replace(/px/,"");
        var crtWid = $("#crt").css('width').replace(/px/,"");
        crtWid = crtWid - wrapWid + 40;
        var moveLeft = $("body").css('width').replace(/px/,"");
        $("#crtWrapper").animate({left:moveLeft - wrapWid},300);
        $("#crt").animate({left:0-crtWid, top:26},300);
        return false;
    });
    $(".close").click(function(){
        $(this).parent().hide({effect:"blind",direction:"up"});
    })
    $( "#crtWrapper .WrapperLabel" ).click(function() {
        $( "#crt" ).toggle({effect:"blind",direction:"up"});
    });

    var up = 0;
    $( "#hideShow" ).click(function() {
        up ^= 1;
        $( "#deadpile" ).toggle({effect:"blind",direction:"up",complete:fixHeader});
        fixHeader();
        return;
        var howFar;
        if(up){
            howFar = 30;
            $("#content").animate({marginTop:howFar+"px"},"slow");
        }else{
            howFar =50;
            $("#content").animate({marginTop:howFar+"px"},"slow");

        }
    });
    $( "#showExited" ).click(function() {
        up ^= 1;
        $( "#exitWrapper" ).toggle({effect:"blind",direction:"up",complete:fixHeader});
        fixHeader();
        return;
    });
    $( "#showDeploy" ).click(function() {
        up ^= 1;
        $( "#deployWrapper" ).toggle({effect:"blind",direction:"up",complete:fixHeader});
        fixHeader();
        return;
    });
    fixHeader();
    $("body").keypress(function(event){
        doitKeypress(event.which);
//        if(event.which == 109){
//            alert("you hi m");
//        }
    });

    /*
     * attach click events to zoom buttons. use Transform not zoom to perform zoom
     * Set globalZoom so clicks on map are still correct.
     */
    $("#zoom span").each(function(){
        $(this).click(function(){
            $("#zoom span").css({textDecoration:"none"});
            $(this).css({textDecoration:"underline"});
            var zoom = $(this).data('zoom');
            if(!zoom){
                zoom = $(this).html();
            }
            zoom = zoom - 0;
            globalZoom = zoom;
            var vHeight = $('#gameViewer').height();
            var vWidth = $('#gameViewer').width();
            vHeight /= 2;
            vWidth /= 2;
            var top = $('#gameImages').css('top').replace(/auto/,"0").replace(/px/,'');
            vHeight -= top;
            var left = $('#gameImages').css('left').replace(/auto/,"0").replace(/px/,'');
            vWidth -= left;
            $("#gameImages").css('-webkit-transform-origin',vWidth+"px "+vHeight+"px").css('transform-origin',vWidth+"px "+vHeight+"px");
            $("#gameImages").css('transform','scale('+zoom+','+zoom+')').css('-webkig-transform','scale('+zoom+','+zoom+')');
        });
    });
}
$(function() {
});
$(document).ready(initialize);


</script>