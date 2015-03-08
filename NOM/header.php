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
    .blueUnit, .french, .playerTwo{
        background-color:rgb(132,181,255);
        border-color:rgb(132,181,255);
    }
    .frenchVP{
        color:rgb(132,181,255);
        background: transparent;
        opacity:1.0;
    }
    .austrianlVP{
        color:rgb(239,115,74);
        background: transparent;
    }
    .russian{
        background-color:gold;
    }
    .armyGreen, .russian{
    background-color:  rgb(148,189,74);
    }
    .austrian{
        background-color:rgb(239,115,74);
    }
    .lightYellow, .austrian, .playerOne{
        background-color: rgb(255,239,156);
        border-color:rgb(255,239,156);
    }
    .flesh{
        background-color: rgb(239,198,156);
    }
    .brightRed{
        background-color: rgb(223,88,66);
    }
#OBC{
    background:white;
    width:514px;
}
#TEC{
    background:white;
    width:579px;
}
.dropDownSelected{
        background:white;
        color:black;
    }
#crt{
    width:476px;
}
    .austrian div{
        color:black;
    }
    .russian div{
        color:black;
    }
    .unit .counter{
        width:32px;
        margin-top:-2px;
    }
    .unit .unit-numbers{
        margin-top:-23px;
        font-size:12px;
        font-weight:bold;

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

    #GR{
        width:600px;
    }
    .game-name{
        word-break:break-all;
        font-family:'Nosifer';
    }
</style>
