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
<style type="text/css">
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
    .sympth{
        background-color:gold;
    }
    .armyGreen, .sympth{
    background-color:  rgb(148,189,74);
    }
    .rebel{
        background-color:rgb(239,115,74);
    }
    .lightYellow, .rebel{
        background-color: rgb(255,239,156);
    }
    .flesh{
        background-color: rgb(239,198,156);
    }
    .brightRed, .rebel{
        background-color: rgb(223,88,66);
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
    .rebel div{
        color:black;
    }
    .sympth div{
        color:black;
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
    padding:5px 20px 5px 0px;
}
#TECWrapper img, #TECWrapper div{
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
</style>
<script type="text/javascript">
    $(document).ready(function(){
        $("video").bind("ended",function(){
            $("#gameViewer").show(1000);
        });
    });

    function playme(){
        var vid = $('video').get(0);
        vid.currentTime = 200;

    }
</script>