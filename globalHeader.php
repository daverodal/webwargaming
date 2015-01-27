<?php global $force_name; ?>
<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<style type="text/css">
</style>
<script>
    var DR = {};
    var zoomed = false;
    DR.globalZoom = 1;
    function toggleFullScreen() {
        var doc = window.document;
        var docEl = doc.documentElement;

        var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
        var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

        if(!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
            requestFullScreen.call(docEl);
            $("#fullScreenButton i").removeClass("fa-arrows-alt").addClass("fa-compress");
        }
        else {
            cancelFullScreen.call(doc);
            $("#fullScreenButton i").addClass("fa-arrows-alt").removeClass("fa-compress");
        }
    }

    $(document).ready(function () {

        DR.crtDetails = false;
        DR.showArrows = false;

        $("#header, #deadpile, #deployWrapper, #exitWrapper").on('touchmove', function(event){
            if($(event.target).parents('#crt').length > 0){
                return;
            }
            event.stopPropagation();
        });

        $("#fullScreenButton").on('click touchstart', function () {
            toggleFullScreen();
            return false;
        });


            var $panzoom = $('#gameImages').panzoom({cursor: "normal", animate: true, maxScale: 2.0, minScale:.3, onPan: function(e, panzoom){

                var xDrag;
                var yDrag;
                if(event.type === 'touchmove'){
                    xDrag = Math.abs(event.touches[0].clientX - DR.clickX);
                    yDrag = Math.abs(event.touches[0].clientY - DR.clickY);
                    if(xDrag > 40 || yDrag > 40){
                        DR.dragged = true;
                    }
                }else {
                    xDrag = Math.abs(event.clientX - DR.clickX);
                    yDrag = Math.abs(event.clientY - DR.clickY);
                    if(xDrag > 4 || yDrag > 4){
                        DR.dragged = true;
                    }
                }
            },
            onZoom:function(e,p,q){
                DR.globalZoom = q;
                var out = DR.globalZoom.toFixed(1);

                $("#zoom .defaultZoom").html(out);
            }});

            $panzoom.parent().on('mousewheel DOMMouseScroll MozMousePixelScroll', function (e) {
                e.preventDefault();
                var delta = e.delta || e.originalEvent.wheelDelta;

                var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;

                $panzoom.panzoom('zoom', zoomOut, {
                    increment: 0.1,
                    animate: false,
                    focal: e
                });
            });
            DR.$panzoom = $panzoom;
        $('#map').load(function(){
                fixItAll();
        });

        $("#crtDetailsButton").on('click touchstart', function () {
            $('#crtDetails').toggle(function () {
                DR.crtDetails = $(this).css('display') == 'block';
            });
            return false;
        });

        $("#phaseClicks").on("click", ".phaseClick", function () {
            x.timeTravel = true;
            if (x.current) {
                x.current.abort();
            }
            var click = $(this).attr('data-click');
            DR.currentClick = click;
            x.fetch(click);
        });
        $("#timeMachine").click(function () {
            x.timeTravel = true;
            if (x.current) {
                x.current.abort();
            }
            var click = DR.currentClick;
            click--;
            x.fetch(click);
        });
        $("#timeSurge").click(function () {
            var click = DR.currentClick;
            click++;
            x.fetch(click);
        });
        $("#timeBranch").click(function () {
            x.timeTravel = true;
            x.timeBranch = true;
            if (x.current) {
                x.current.abort();
            }
            var click = DR.currentClick;
            x.fetch(click);
            $("#TimeWrapper .WrapperLabel").click();
        });
        $("#phaseClicks").on("click", ".realtime", function () {
            x.timeTravel = false;
            x.fetch(0);
        })
        $("#timeLive").click(function () {
            $("#TimeWrapper .WrapperLabel").click();
            x.timeTravel = false;
            x.fetch(0);
        });
        $("#showHexNums").on('click', function () {
            var src = $("#map").attr('src');
            if (src.match(/HexNumbers/)) {
                src = src.replace(/HexNumbers/, "");
            } else {
                src = src.replace(/Small/, "HexNumbersSmall");
            }
            $("#map").attr('src', src);
        });
        fixHeader();
        $(window).resize(fixItAll);

        $("#mainTable").on('click', function () {
            $(this).hide();
            $("#detTable").show();
            $('.tableWrapper.determined').hide();
            $('.tableWrapper.main').show();
        });
        $("#detTable").on('click', function () {
            $(this).hide();
            $("#mainTable").show();
            $('.tableWrapper.main').hide();
            $('.tableWrapper.determined').show();
        });
        $("#mainTable").hide();
        $("#detTable").show();
        $('.tableWrapper.determined').hide();
        $(".tableWrapper.main").show();

    });
    function fixItAll() {
        fixHeader();

    }
    function fixHeader() {

        var winHeight = $(window).height();
        var winWidth = $(window).width();
        var mapHeight = $("#map").height();
        var mapWidth = $("#map").width();
        if(winWidth > mapWidth){
            mapWidth = winWidth;
        }
        if(winHeight > mapHeight){
            mapHeight = winHeight;
        }
        $("#gameImages, #gameContainer").height(mapHeight).width(mapWidth);
        DR.$panzoom.panzoom('resetDimensions');

        height = $("#crtWrapper h4").height();
        $("#bottomHeader").css("height", height);

        var headerHeight = $("#header").height();
        $("#content").css("margin-top",0);
        var bodyHeight = $(window).height();
        var bodyWidth = $(window).width();
        var deployHeight = $("#deployWrapper:visible").height();
        var deadHeight = $("#deadpile:visible").height();
//        $("#gameViewer, #gameContainer").height(bodyHeight - (deployHeight+deadHeight + 20));
        if (deadHeight) {
            deadHeight += 10 + 10 + 4 + 4;
        }
        if (deployHeight) {
            deployHeight += 10 + 10 + 4 + 4;
        } else {
            deployHeight = 0;
        }
        var height = bodyHeight - deployHeight - deadHeight - headerHeight - 40;
        var width = bodyWidth - 35;
        DR.$panzoom.panzoom('resetDimensions');

        window.scrollTo(0, 1);

//        $("#gameViewer").height(height);
//        $("#gameViewer").width(width);
    }
</script>
<?php include_once("commonSync.php"); ?>
<script>
function seeMap() {
    $(".unit").css("opacity", .0);
}
function seeUnits() {
    $(".unit").css("opacity", 1.);
}
function seeBoth() {
    $(".unit").css("opacity", .3);
}
function doit() {
    var mychat = $("#mychat").attr("value");
    $.ajax({
        url: "<?=site_url("wargame/add/");?>",
        type: "POST",
        data: {chat: mychat},
        success: function (data, textstatus) {
            alert(data);
        }
    });
    $("#mychat").attr("value", "");
}
function doitKeypress(key) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor: "wait"});
    $(this).css({cursor: "wait"});
//    $("#"+id+"").addClass("pushed");

    $("#comlink").html('Waiting');
    $.ajax({
        url: "<?=site_url("wargame/poke");?>/",
        type: "POST",
        data: {id: key, event: <?=KEYPRESS_EVENT?>},
        error: function (data, text, third) {
            try {
                obj = jQuery.parseJSON(data.responseText);
            } catch (e) {
//                alert(data);
            }
            if (obj.emsg) {
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
            $("#" + id + "").removeClass("pushed");
            $("#comlink").html('Working');
        },
        success: function (data, textstatus) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//                alert(data);
            }
            if (success) {
                playAudioLow();

            } else {
                playAudioBuzz();
            }
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
//            $("#"+id+"").removeClass("pushed");


        }
    });
    $("#mychat").attr("value", "");
}
function doitCRT(id, event) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor: "wait"});
    $(this).css({cursor: "wait"});

    $("#comlink").html('waiting');
    $.ajax({
        url: "<?=site_url("wargame/poke");?>/",
        type: "POST",
        data: {id: id, event: (event.shiftKey || DR.shiftKey) ? <?=COMBAT_PIN_EVENT;?> : <?=COMBAT_PIN_EVENT?>},
        error: function (data, text, third) {
            try {
                obj = jQuery.parseJSON(data.responseText);
            } catch (e) {
//                alert(data);
            }
            if (obj.emsg) {
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
            $("#comlink").html('Working');
        },
        success: function (data, textstatus) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//            alert(data);
            }
            if (success) {
                playAudioLow();

            } else {
                playAudioBuzz();
            }
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
        }
    });
    $("#mychat").attr("value", "");
}
function doitUnit(id, event) {
    var mychat = $("#mychat").attr("value");
    playAudio();
    $('body').css({cursor: "wait"});
    $(this).css({cursor: "wait"});
    $("#" + id + "").addClass("pushed");

    $("#comlink").html('waiting');
    if(DR.shiftKey){
        event.shiftKey = true;
        $("#shiftKey").click();
    }
    $.ajax({
        url: "<?=site_url("wargame/poke");?>/",
        type: "POST",
        data: {id: id, event: (event.shiftKey || DR.shiftKey) ? <?=SELECT_SHIFT_COUNTER_EVENT;?> : <?=SELECT_COUNTER_EVENT?>},
        error: function (data, text, third) {
            try {
                obj = jQuery.parseJSON(data.responseText);
            } catch (e) {
//                alert(data);
            }
            if (obj.emsg) {
                alert(obj.emsg);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
            $("#" + id + "").removeClass("pushed");
            $("#comlink").html('Working');
        },
        success: function (data, textstatus) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//            alert(data);
            }
            if (success) {
                playAudioLow();

            } else {
                playAudioBuzz();
            }
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
            $("#" + id + "").removeClass("pushed");


        }
    });
    $("#mychat").attr("value", "");
}
function doitMap(x, y) {
    playAudio();

    $.ajax({
        url: "<?=site_url("wargame/poke/");?>/",
        type: "POST",
        data: {
            x: x,
            y: y,
            event: <?=SELECT_MAP_EVENT?>
        },
        success: function (data, textstatus) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//            alert(data);
            }
            if (success) {
                playAudioLow();

            } else {
                playAudioBuzz();
            }
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
        },
        error: function (data, text) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//                alert(data);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
        }
    });
    return true;
}
function doitNext() {
    playAudio();

    $.ajax({
        url: "<?=site_url("wargame/poke/");?>/",
        type: "POST",
        data: {event: <?=SELECT_BUTTON_EVENT?>},
        success: function (data, textstatus) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//                alert(data);
            }
            playAudioLow();

        }, error: function (data, text) {
            try {
                var success = +$.parseJSON(data).success;
            } catch (e) {
//                alert(data);
            }
            playAudioBuzz();
            $('body').css({cursor: "auto"});
            $(this).css({cursor: "auto"});
        }
    });

}


// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

// main classes for wargame

function mapMouseMove(event) {
    var tar = event.target;

    var x = event.pageX - event.target.x;
    var y = event.pageY - event.target.y;
    $("#mouseMove").html("X " + x + " Y " + y);
}
function mapStop(event) {

    $("#map").data('did-drag', true);
    event.stopPropagation();
}
function mapClick(event) {
    var didDrag = $("#map").data('did-drag');
    $("#map").data('did-drag', false);
    if (didDrag) {
        return;
    }

    var pixelX, pixelY;
    pixelX = event.pageX;
    pixelY = event.pageY;
    var p;
    p = $("#gameImages").offset();
    pixelX -= p.left;
    pixelY -= p.top;

    if (zoomed) {
        doZoom(event);
        zoomed = false;
        return;
    }
}

function changePosition(player) {
    $("#flash").html(player);
}

function doZoom(event) {

    var pixelX, pixelY;
    pixelX = event.pageX;
    pixelY = event.pageY;
    var p;
    p = $("#gameViewer").offset();
    pixelX -= p.left;
    pixelY -= p.top;

    zoomed = false;
    width = $("body").width();
    var left = (pixelX / -.3) + (width / 2);
    var viewerHeight = $("#gameViewer").height() / 2;
    var top = (pixelY / -.3) + (viewerHeight);

    if (left > 0) {
        left = 0;
    }
    if (top > 0) {
        top = 0;
    }
    // TODO: make this more modern
    $("#gameImages").css({MozTransform: "translate(0,0) scale(1.0)"});
    $("#gameImages").animate({zoom: 1.0, left: left, top: top}, 1500);
}

function counterClick(event) {
    if(event.type == 'touchend'){
        event.stopPropagation();
        event.preventDefault();
    }

    DR.clickX = DR.clickY = undefined;
    if(DR.dragged){
        return;
    }
    if(event.which === 3){
        return;
    }
    if (zoomed) {
        doZoom(event);
        return;
    }

    var didDrag = $("#map").data('did-drag');
    $("#map").data('did-drag', false);
    if (didDrag) {
        return;
    }

    var id;
    id = $(event.target).attr('id');
    if (!id) {
        id = $(event.target).parent().attr("id");
    }
    if (!id) {
        id = $(event.target).parent().parent().attr("id");
    }
    doitUnit(id, event);
}

function nextPhaseMouseDown(event) {
    doitNext();
}

var mute = false;

function playAudio() {
    var aud = $('.pop').get(0);
    <!--    aud.src = "-->
    if (aud && !mute) {
        aud.play();
    }

}
function playAudioLow() {
    var aud = $('.poop').get(0);
    <!--    aud.src = "-->
    if (aud && !mute) {
        aud.play();
    }

}
function unMuteMe() {
    mute = false;
    return true;
}
function muteMe() {
    mute = true;
    return true;
}
function playAudioBuzz() {
    var aud = $('.buzz').get(0);
    <!--    aud.src = "-->
    if (aud) {
        aud.play();
    }

}


function initialize() {

    /* yuck */
    if(navigator.userAgent.match(/Android/)){
        $('body').height($(window).height());
    }
    // setup events --------------------------------------------
    $("#map").load(function () {
        var width = $("#gameImages #map").width();
        var height = $("#gameImages #map").height();
        $('svg').width(width);
        $('svg').height(height);
        $('svg').attr('viewBox', "0 0 " + width + " " + height);
    });


    $(".unit").on('mousedown touchstart', function(e){
        DR.clickX = e.clientX;
        DR.clickY = e.clientY;
        DR.dragged = false;
    });

    $(".unit").on('mouseup touchend', counterClick);


    $("#crt #odds span").on('click touchstart', function (event) {
        var col = $(event.target).attr('class');
        col = col.replace(/col/, '');
        doitCRT(col, event);
    })
    $("#gameImages").on("click", ".specialHexes", mapClick);
    $("#gameImages").on("click", "svg", mapClick);

    $("#nextPhaseButton").on('click', nextPhaseMouseDown);
//    $("#gameImages" ).draggable({stop:mapStop, distance:15});
    $("#gameImages #map").on("click", mapClick);

    DR.$floatMessagePanZoom = $('#floatMessage').panzoom({cursor: "normal", disableZoom: true, onPan: function(e, panzoom){
        DR.floatMessageDragged = true;
    }});

    $("#Time").draggable();
    DR.$crtPanZoom = $('#crt').panzoom({cursor: "move", disableZoom: true, onPan: function(e, panzoom){
        var xDrag;
        var yDrag;
        if(event.type === 'touchmove'){
            xDrag = Math.abs(event.touches[0].clientX - DR.clickX);
            yDrag = Math.abs(event.touches[0].clientY - DR.clickY);
            if(xDrag > 40 || yDrag > 40){
                DR.dragged = true;
            }
        }else {
            xDrag = Math.abs(event.clientX - DR.clickX);
            yDrag = Math.abs(event.clientY - DR.clickY);
            if(xDrag > 4 || yDrag > 4){
                DR.dragged = true;
            }
        }

        DR.dragged = true;
    }});
//
//    DR.$crtPanZoom = $('#Time').panzoom({cursor: "move", disableZoom: true, onPan: function(e, panzoom){
//        var xDrag;
//        var yDrag;
//        if(event.type === 'touchmove'){
//            xDrag = Math.abs(event.touches[0].clientX - DR.clickX);
//            yDrag = Math.abs(event.touches[0].clientY - DR.clickY);
//            if(xDrag > 40 || yDrag > 40){
//                DR.dragged = true;
//            }
//        }else {
//            xDrag = Math.abs(event.clientX - DR.clickX);
//            yDrag = Math.abs(event.clientY - DR.clickY);
//            if(xDrag > 4 || yDrag > 4){
//                DR.dragged = true;
//            }
//        }
//
//        DR.dragged = true;
//    }});

    $("#muteButton").click(function () {
        if (!mute) {
            $("#muteButton").html("un-mute");
            muteMe();

        } else {
            $("#muteButton").html("mute");
            unMuteMe();
            playAudio();
        }
    });
    $("#arrowButton").click(function () {
        if (!DR.showArrows) {
            $("#arrowButton").html("hide arrows");
            DR.showArrows = true;
            $('svg').show();
        } else {
            $("#arrowButton").html("show arrows");
            DR.showArrows = false;
            $('svg').hide();
        }
    });
    $('.unit:not(.clone)').hover(function (event) {

        $(".unitPath" + this.id).css({opacity: 1.0});

    }, function (event) {
        $(".unitPath" + this.id).css({opacity: ''});

    });


    $('.unit').bind('contextmenu', function (e) {
        if (e.ctrlKey) {
            return true;
        }
        var id = this.id;
        var x = DR.stackModel.ids[id].x;
        var y = DR.stackModel.ids[id].y;
        var units = DR.stackModel[x][y].ids;
        for (i in units) {
            var zindex = $("#" + i).css('z-index') - 0;
            $("#" + i).css({zIndex: zindex + 1});
        }
        $(this).css({zIndex: 1});
        return false;
    });
    // end setup events ----------------------------------------


    var Player = 'Markarian';

    $("#TimeWrapper .WrapperLabel").click(function(){
        $("#TimeWrapper").css('overflow', 'visible');
    });
    $(".dropDown .WrapperLabel").click(function () {
        $(this).parent().siblings(".dropDown, #crtWrapper").children('div').hide({
            effect: "blind", direction: "up", complete: function () {
                $(this).parent().children('h4').removeClass('dropDownSelected');
            }
        });

        $(this).next().toggle({
            effect: "blind", direction: "up", complete: function () {
                if ($(this).is(":visible")) {
                    $(this).parent().children('h4').addClass('dropDownSelected');
                } else {
                    $(this).parent().children('h4').removeClass('dropDownSelected');
                    $(this).parent().parent().parent('.dropDown').children('div').hide({
                        effect: "blind", direction: "up", complete: function () {
                            $(this).parent().children('h4').removeClass('dropDownSelected');
                        }
                    });
                }
            }
        });

    });

    $("#jumpWrapper .WrapperLabel").click(function () {

        $("#crt").hide({effect: "blind", direction: "up"});
        $("#gameContainer").css("margin", 0);
        $("#gameImages").css({zoom: .3, overflow: "visible"});
        // TODO: make this more modern (transform)
        $("#gameImages").css({MozTransform: "translate(-33%, -33%) scale(.3)"});
        $("html, body").animate({scrollTop: "0px"});


        $("#gameImages").css('left', 0);
        $("#gameImages").css('top', 0);
        zoomed = true;
    });
    $("#crtWrapper .WrapperLabel .goLeft").click(function () {
        $("#crtWrapper").animate({left: 0}, 300);
        $("#crt").animate({left: "0px", top: 26}, 300);
        DR.$crtPanZoom.panzoom('reset', {animate: false});


        return false;
    });
    $("#crtWrapper .WrapperLabel .goRight").click(function () {
        var wrapWid = $("#crtWrapper").css('width').replace(/px/, "");
        var crtWid = $("#crt").css('width').replace(/px/, "");
        crtWid = crtWid - wrapWid + 40;
        var moveLeft = $("body").css('width').replace(/px/, "");
        $("#crtWrapper").animate({left: moveLeft - wrapWid}, 300);
        $("#crt").animate({left: 0 - crtWid, top: 26}, 300);
        DR.$crtPanZoom.panzoom('reset', {animate: false});

        return false;
    });
    $(".close").on('click touchstart', function () {
        $(this).parent().hide({effect: "blind", direction: "up"});
    })
    $("#crtWrapper .WrapperLabel").click(function () {
        $("#crtWrapper").css('overflow', 'visible');
        DR.$crtPanZoom.panzoom('reset');

        $("#crt").toggle({effect: "blind", direction: "up"});
    });

    var up = 0;
    $("#hideShow").click(function () {
        up ^= 1;
        $("#deadpile").toggle({effect: "blind", direction: "up", complete: fixHeader});
        fixHeader();
        return;
        var howFar;
        if (up) {
            howFar = 30;
            $("#content").animate({marginTop: howFar + "px"}, "slow");
        } else {
            howFar = 50;
            $("#content").animate({marginTop: howFar + "px"}, "slow");

        }
    });
    $("#showExited").click(function () {
        up ^= 1;
        $("#exitWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        fixHeader();
        return;
    });
    $("#showDeploy").click(function () {
        up ^= 1;
        $("#deployWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        fixHeader();
        return;
    });
    $("#closeAllUnits").click(function(){
        $("#deployWrapper").hide({effect: "blind", direction: "up", complete: fixHeader});
        $("#exitWrapper").hide({effect: "blind", direction: "up", complete: fixHeader});
        $("#deadpile").hide({effect: "blind", direction: "up", complete: fixHeader});
        $("#units").hide({effect: "blind", direction: "up", complete: fixHeader});
        $("#unitsWrapper .WrapperLabel").removeClass('dropDownSelected');
        fixHeader();
    })
    fixHeader();
    $("body").keypress(function (event) {
        doitKeypress(event.which);
    });

    $("#forceMarchEvent").on('click',function(){
        doitKeypress(109);
    });

    $("#determinedAttackEvent").on('click',function(){
        doitKeypress(100);
    });

    $("#clearCombatEvent").on('click',function(){
        doitKeypress(99);
    });

    $("#shiftKey").on('click',function(){
        DR.shiftKey = !DR.shiftKey;
        $("#shiftKey").toggleClass('swooshy', DR.shiftKey);
    });

    $("#zoom .defaultZoom").on('click', function () {
        DR.globalZoom = 1.0;
        $("#zoom .defaultZoom").html(DR.globalZoom.toFixed(1));
        DR.$panzoom.panzoom('reset');
    });


}

var state = {
    x: 0,
    y: 0,
    scale: 1
};
var oX, oY;
var changeScale = function (scale) {
    // Limit the scale here if you want
    // Zoom and pan transform-origin equivalent
    var scaleD = scale / state.scale;
    var currentX = state.x;
    var currentY = state.y;
    // The magic
    var x = scaleD * (currentX - oX) + oX;
    var y = scaleD * (currentY - oY) + oY;

    state.scale = scale;
    state.x = x;
    state.y = y;

    var transform = "matrix(" + scale + ",0,0," + scale + "," + x + "," + y + ")";
    //var transform = "translate("+x+","+y+") scale("+scale+")"; //same
//    view.setAttributeNS(null, "transform", transform);
    $("#gameImages").css('transform', transform);
};

function doUserZoom(event) {

    var vHeight;
    var vWidth;
    var prevWidth;
    var precision = 1;
    if (DR.globalZoom >= 1.0) {
        precision = 2;
    }
    $("#zoom .defaultZoom").html(DR.globalZoom.toPrecision(precision));

    if (event) {
        prevWidth = $("#gameImages").css('-webkit-transform-origin');
        vWidth = event.pageX - event.target.x;
        vHeight = event.pageY - event.target.y;
//        oX = vWidth;
//        oY = vHeight;
//        oX = window.innerWidth/2;
//        oY = window.innerHeight/2;
//        changeScale(DR.globalZoom);
    } else {
        var origHeight = vHeight = $('#gameViewer').height();
        var origWidth = vWidth = $('#gameViewer').width();
        vHeight /= 2;
        vWidth /= 2;
        var pos = $('#gameImages').position();
        var top = pos.top;
        vHeight -= top;
        var left = pos.left;
        vWidth -= left;
        if (vWidth > origWidth) {
            vWidth = origWidth;
        }
        if (vHeight > origHeight) {
            vHeight = origHeight;
        }
        if (vHeight < 0) {
            vheight = 0;
        }
        if (vWidth < 0) {
            vWidth = 0;
        }
    }
    $("#gameImages").css('-webkit-transform-origin', vWidth + "px " + vHeight + "px").css('transform-origin', vWidth + "px " + vHeight + "px");
    $("#gameImages").css('transform', 'scale(' + DR.globalZoom + ',' + DR.globalZoom + ')').css('-webkit-transform', 'scale(' + DR.globalZoom + ',' + DR.globalZoom + ')');
}
$(document).ready(initialize);
</script>