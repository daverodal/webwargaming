<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<style type="text/css">
    /* Unit Styles */
    .prc {
        background-color:  rgb(247, 206, 0);
    }

    .playerTwoFace, .prcFace {
        color:  rgb(255, 215, 0);
    }

    .prcVictoryPoints{
        color:black;
    }
    .playerOne {
        background: rgb(223, 88, 66);
        border-color: rgb(223, 88, 66) !important;
    }

    .playerTwo {
        background:   rgb(247, 206, 0);
        border-color:   rgb(247, 206, 0) !important;


    }

    .playerOneFace, .sovietFace {
        color: rgb(239, 115, 74);
    }

    .soviet {
        background-color: rgb(223, 88, 66);
    }

    .prc {
        background-color:   rgb(247, 206, 0);
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

    .reduced {
        color: white;
    }

    /* dropdown styles */
    #OBC {
        background: white;
        width: 514px;
    }

    #crt {
        width: 308px;
    }
    #tecImage{
        width:500px;
    }

    #TEC{
        width:520px;
    }

    #TECWrapper .closer {
        height: 0px;
        padding: 0px;
    }

    #TECWrapper .colOne.riverHex {
        background-image: url('<?=base_url()?>js/river.png');
        background-position: 0px -26px;
    }

    #TECWrapper .colOne.bridgeHex {
        background-image: url('<?=base_url()?>js/riverRoad.png');
        background-position: 0px -26px;
    }

    #TECWrapper .colOne.blankHex {
        background-image: url('<?=base_url()?>js/blank.png');
    }

    #TECWrapper .colOne.forestHex {
        background-image: url('<?=base_url()?>js/forest 2.png');
    }

    #TECWrapper .colOne.mountainHex {
        background-image: url('<?=base_url()?>js/mountain.png');
    }

    #TECWrapper .colOne.roadHex {
        background-image: url('<?=base_url()?>js/road.png');
        background-position: 0px -26px;
    }

    #TECWrapper .colOne.trailHex {
        background-image: url('<?=base_url()?>js/trail.png');
        background-position: 0px -26px;
        background-size: 516px;
        /* TODO why this different from others */
    }

    #TECWrapper .colOne {
        background-size: 400px;
        width: 220px;
        height: 56px;
        margin-left: 20px;
    }

    #TECWrapper .colTwo {
        width: 170px;
        padding-right: 10px;
    }

    #TECWrapper .colThree {
        width: 136px;
    }

    #TECWrapper .colOne span {
        margin-top: 12px;
        margin-left: 80px;
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

    #TECWrapper li {
        list-style-type: none;
        padding: 5px 20px 5px 0px;
    }

    #TECWrapper img, #TECWrapper div {
    }

    #GR {
        width: 600px;
    }

    .game-name {
        word-break: break-all;
        font-family: 'Nosifer';
    }
</style>
