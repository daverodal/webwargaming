<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
<style type="text/css">
    body {
    }

    #map {
        width: 1023px !important;
        left: 0px;
        top: 00px;
    }

    .blueUnit, .Russian {
        background-color: white;
    }

    img.counter {
        width: 32px;
        height: 16px;
    }

    .unit .unit-numbers {
        background: white;
        font-weight: bold;
        font-family: serif;
    }

    .RussianaVP {
        color: rgb(132, 181, 255);
        background: transparent;
        opacity: 1.0;
    }

    .PrussianlVP {
        color: rgb(239, 115, 74);
        background: transparent;
    }

    .russian {
        background-color: gold;
    }

    .armyGreen, .russian {
        background-color: rgb(148, 189, 74);
    }

    .Prussian {
        background-color: white;
    }

    .lightYellow, .Prussian {
        background-color: white;
    }

    .flesh {
        background-color: rgb(239, 198, 156);
    }

    .brightRed {
        background-color: rgb(223, 88, 66);
    }

    p.forceMarch {
        position: absolute;
        top: -18px;
        right: 0px;
        display: none;
        color: white;
    }

    #OBC {
        background: white;
        width: 514px;
    }

    #TEC {
        background: white;
        width: 779px;
    }

    #crt {
        width: 476px;
    }

    .tableWrapper.alt {
        display: none;
    }

    .alt {
        display: none;
        width: 476px;
    }

    .Prussian div {
        color: black;
    }

    .russian div {
        color: black;
    }

    .playerOne {
        background: rgb(255, 253, 127);
        border-color: rgb(255, 253, 127) !important;
    }

    .playerTwo {
        background: rgb(76, 184, 0);
        border-color: rgb(76, 184, 0) !important;
    }

    #clock {
        margin-left: 0;
    }

    .dropDownSelected {
        background: white;
        color: black;
    }




    #TECWrapper .colOne {
        background-size: 400px;
        width: 220px;
        height: 60px;
        margin-left: 20px;
    }

    #TECWrapper .colTwo {
        width: 170px;
        padding-right: 10px;
    }

    #TECWrapper .colThree {
        width: 315px;
    }

    #TECWrapper .colOne span {
        margin-top: 12px;
        display: inline-block;
    }

    #TECWrapper .colTwo, #TECWrapper .colThree {
        margin-top: 12px;
    }

    #TECWrapper .colOne,
    #TECWrapper .colTwo,
    #TECWrapper .colThree {
        float: left;
    }

    .hexWrapper{
        height:100px;
        width:100px;
        background-size:650px;
        float:left;
    }
    #TECWrapper div {
        /*height:112px;*/
    }

    #TECWrapper li {
        list-style-type: none;
        padding: 0px 20px 5px 0px;
    }

    #TECWrapper img, #TECWrapper div {
    }


    #TECWrapper .colOne.riverHex .hexWrapper{
        background-image: url('<?=base_url()?>js/JagersdorfTerrainKey.jpg');
        background-position: -179px -308px;
    }

    #TECWrapper .colOne.blankHex .hexWrapper {
        background-image: url('<?=base_url()?>js/JagersdorfTerrainKey.jpg');
        background-position: -192px -181px;
        margin-bottom:15px;
    }

    #TECWrapper .colOne.forestHex .hexWrapper {
        background-image: url('<?=base_url()?>js/JagersdorfTerrainKey.jpg');
        background-position: -53px -187px;
    }

    #TECWrapper .colOne.townHex .hexWrapper{
        background-image: url('<?=base_url()?>js/JagersdorfTerrainKey.jpg');
        background-position: -61px -62px;
    }

    #TECWrapper .colOne.mountainHex .hexWrapper{
        background-image: url('<?=base_url()?>js/JagersdorfTerrainKey.jpg');
        background-position: -47px -281px;
    }

    #TECWrapper .colOne.roadHex .hexWrapper{
        background-image: url('<?=base_url()?>js/JagersdorfTerrainKey.jpg');
        background-position: -200px -65px;
    }

    #TECWrapper .colOne.bridgeHex .hexWrapper {
        background-image: url('<?=base_url()?>js/JagersdorfTerrainKey.jpg');
        background-position: -343px -64px;
    }

    #TECWrapper .colOne {
        background-size: 400px;
        width: 220px;
        height: 65px;
        margin-left: 20px;
    }

    #GR {
        width: 600px;
    }

    .game-name {
        font-family: 'Berkshire Swash';
    }

    #altTable, #mainTable {
        position: absolute;
        right: 18px;
        cursor:pointer;
    }
</style>
