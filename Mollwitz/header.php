<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
<style type="text/css">
    body {
    }

    #map{
        width:1000px !important;
    }

    .blueUnit, .Russian {
        background-color: white;
    }
    .unit{
        border:solid black;
        border-width: 3px;
    }

    .unit .counterWrapper img.counter {
        width: 14px;
        height:14px;
        display:block;
        border-right:1px solid black;

    }
    .unit .counterWrapper{
        border:solid black 1px;
        height:14px;
    }
    .unit.Austrian .counterWrapper{
        background:white;
    }
    .unit.Russian .counterWrapper{
        background:rgb(0, 156, 29);
    }
    .unit.Prussian .counterWrapper{
        background:rgb(1,23,222);
    }
    .unit .unit-numbers .infoLen7{
        font-size:13px;
    }

    .unit img {

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

    .unit .unit-numbers{
        border: 1px solid black;
        border-top:1px;
        height:15px;
        line-height:16px;
        font-size:16px !important;
        letter-spacing:-1px;
        background: white;
        font-weight: bold;
        font-family: serif;
    }
    p.forceMarch {
        position: absolute;
        top: -19px;
        right: 2px;
        display: none;
        color: white;
        text-transform: lowercase;
    }
    .Austrian p.forceMarch,.French p.forceMarch {
        color:black;
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
        background-color: rgb(223, 88, 66);
        border-color: rgb(223, 88, 66);
    }

    #clock {
        margin-left: 0;
    }

    .dropDownSelected {
        background: white;
        color: black;
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

    #VCWrapper .closer {
        height: 0px;
        padding: 0px;
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
    }
    #crtDetailsButton{
        font-size:10px;
        cursor: pointer;
    }
    #crtDetails{
        display:none;
    }
    .specialHexes.russian{
        color:white;
    }
    #CombatLog{
        width:300px;
    }
    .specialHexesVP .austrian,.specialHexesVP .anglo{
        background:transparent;
        text-shadow:
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),

            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 1px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 2px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 3px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 4px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 5px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 6px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 7px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 8px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 9px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66),
            0px 0px 10px rgb(223,88,66);
    }

    .specialHexesVP .prussian{
        color:black;
        background:transparent;
        text-shadow:
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,

            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 1px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 2px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 3px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 4px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 5px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 6px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 7px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 8px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 9px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow,
            0px 0px 10px yellow;
    }

    .specialHexesVP .russian{
        color:white;
        background:transparent;
        text-shadow:
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),

            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 1px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 2px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 3px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 4px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 5px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 6px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 7px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 8px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 9px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74),
            0px 0px 10px rgb(148, 189, 74);
    }
    .specialHexesVP .french{
        color:white;
        background:transparent;
        text-shadow:
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),

            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 1px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 2px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 3px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 4px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 5px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 6px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 7px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 8px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 9px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255),
            0px 0px 10px rgb(61,110,255);
    }

</style>
<script type="text/javascript">
$(document).ready(function(){
    DR = {};
    DR.crtDetails = false;

    $("#crtDetailsButton").on('click',function(){
        $('#crtDetails').toggle(function(){
            DR.crtDetails = $(this).css('display') == 'block';
        });
        return false;
    });
    $("#altTable").on('click', function(){
        $(this).hide();
        $("#mainTable").show();
        $('.tableWrapper.main').hide();
        $('.tableWrapper.alt').show();
    });
    $("#mainTable").on('click', function(){
        $(this).hide();
        $("#altTable").show();
        $('.tableWrapper.alt').hide();
        $('.tableWrapper.main').show();
    });
    $("#altTable").show();
    $("#mainTable").hide();
    $(".tableWrapper.alt").hide();
    $(".tableWrapper.main").show();

});
x.register("combatRules", function(combatRules, data){

    for(var combatCol = 1; combatCol <= 10; combatCol++){
        $(".col" + combatCol).css({background: "transparent"});
//            $(".odd .col"+combatCol).css({color:"white"});
//            $(".even .col"+combatCol).css({color:"black"});

    }
    var title = "Combat Results ";
    var cdLine = "";
    var activeCombat = false;
    var activeCombatLine = "";
    str = "";
    var toResolveLog = "";

    if(combatRules){
        cD = combatRules.currentDefender;
        if(combatRules.combats && Object.keys(combatRules.combats).length > 0){
            if(cD !== false){
                var defenders = combatRules.combats[cD].defenders;
                if(combatRules.combats[cD].useAlt){
                    $('.tableWrapper.main').hide();
                    $('.tableWrapper.alt').show();
                    $('#mainTable').show();
                    $('#altTable').hide();
                }else{
                    $('.tableWrapper.main').show();
                    $('.tableWrapper.alt').hide();
                    $('#mainTable').hide();
                    $('#altTable').show();
                }
                for(var loop in defenders){
                    $("#" + loop).css({borderColor: "yellow"});
                }
                if(!chattyCrt){
                    $("#crt").show({effect: "blind", direction: "up"});
                    chattyCrt = true;
                }
                fixCrt();
                if(Object.keys(combatRules.combats[cD].attackers).length != 0){
                    combatCol = combatRules.combats[cD].index + 1;
                    if(combatCol >= 1){
                        $(".col" + combatCol).css('background-color', "rgba(255,255,1,.6)");
                        if(combatRules.combats[cD].Die !== false){
                            $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('font-size', "110%");
                            $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('background', "#eee");
                        }
                    }
                }
            }
            var str = "";
            cdLine = "";
            var combatIndex = 0;
            for(i in combatRules.combats){
                if(combatRules.combats[i].index !== null){


                    attackers = combatRules.combats[i].attackers;
                    defenders = combatRules.combats[i].defenders;
                    thetas = combatRules.combats[i].thetas;

                    var theta = 0;
                    for(var j in attackers){
                        var numDef = Object.keys(defenders).length;
                        for(k in defenders){
                            $("#" + j + " .arrow").clone().addClass('arrowClone').addClass('arrow' + k).insertAfter("#" + j + " .arrow").removeClass('arrow');
                            theta = thetas[j][k];
                            theta *= 15;
                            theta += 180;
                            $("#" + j + " .arrow" + k).css({opacity: "1.0"});
                            $("#" + j + " .arrow" + k).css({webkitTransform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(45px)"});
                            $("#" + j + " .arrow" + k).css({transform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(45px)"});
                        }
                    }

                    var atk = combatRules.combats[i].attackStrength;
                    var atkDisp = atk;
                    if(combatRules.storm){
                        atkDisp = atk * 2 + " halved for storm = " + atk;
                    }
                    var def = combatRules.combats[i].defenseStrength;
                    var ter = combatRules.combats[i].terrainCombatEffect;
                    var idx = combatRules.combats[i].index + 1;
                    var odds = Math.floor(atk / def);
                    var oddsDisp = $(".col" + combatCol).html()

                    newLine = "<h5>odds = " + oddsDisp + " </h5><div id='crtDetails'>"+combatRules.combats[i].combatLog+"</div><div>Attack = " + atkDisp + " / Defender " + def + " = " + atk / def + "<br>Combined Arms Shift " + ter + " = " + $(".col" + combatCol).html() + "</div>";
                    if(cD !== false && cD == i){
                        activeCombat = combatIndex;
                        activeCombatLine = newLine;
                        /*cdLine = "<fieldset><legend>Current Combat</legend><strong>"+newLine+"</strong></fieldset>";
                         newLine = "";*/
                    }
                    combatIndex++;
//                            str += newLine;
                }

            }
            str += "There are " + combatIndex + " Combats";
            if(cD !== false){
                attackers = combatRules.combats[cD].attackers;
//                    var theta = 0;
//                    for(var i in attackers){
//                                      theta = attackers[i];
//                        theta *= 15;
//                        theta += 180;
//                        $("#"+i+ " .arrow").css({display: "block"});
//                        $("#"+i+ " .arrow").css({opacity: "1.0"});
//                        $("#"+i+ " .arrow").css({webkitTransform: 'scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
//
//
//                    }
            }
            str += "";
            $("#crtOddsExp").html(activeCombatLine);
            $("#status").html(cdLine + str);
            if(DR.crtDetails){
                $("#crtDetails").toggle();
            }
            $("#status").show();

        }else{
            chattyCrt = false;
        }


        var lastCombat = "";
        if(combatRules.combatsToResolve){
            if(combatRules.lastResolvedCombat){
                toResolveLog = "Current Combat or Last Combat<br>";
                title += "<strong style='margin-left:20px;font-size:150%'>" + combatRules.lastResolvedCombat.Die + " " + combatRules.lastResolvedCombat.combatResult + "</strong>";
                combatCol = combatRules.lastResolvedCombat.index + 1;
                combatRoll = combatRules.lastResolvedCombat.Die;
                $(".col" + combatCol).css('background-color', "rgba(255,255,1,.6)");
                $(".row" + combatRoll + " .col" + combatCol).css('background-color', "cyan");
                if(combatRules.lastResolvedCombat.useAlt){
                    $('.tableWrapper.main').hide();
                    $('.tableWrapper.alt').show();
                    $('#mainTable').show();
                    $('#altTable').hide();

                }else{
                    $('.tableWrapper.main').show();
                    $('.tableWrapper.alt').hide();
                    $('#mainTable').hide();
                    $('#altTable').show();
                }
                var atk = combatRules.lastResolvedCombat.attackStrength;
                var atkDisp = atk;
                ;

                var def = combatRules.lastResolvedCombat.defenseStrength;
                var ter = combatRules.lastResolvedCombat.terrainCombatEffect;
                var idx = combatRules.lastResolvedCombat.index + 1;
                var odds = Math.floor(atk / def);
                var oddsDisp = $(".col" + combatCol).html()
                newLine = "<h5>odds = " + oddsDisp + "</h5><div id='crtDetails'>"+combatRules.lastResolvedCombat.combatLog+"</div><div>Attack = " + atkDisp + " / Defender " + def + " = " + atk / def + "<br>Combined Arms Shift " + ter + " = " + oddsDisp + "</div>";

                toResolveLog += newLine;
                toResolveLog += "Roll: "+combatRules.lastResolvedCombat.Die + " result: " + combatRules.lastResolvedCombat.combatResult+"<br><br>";

                $("#crtOddsExp").html(newLine);
//                    $(".row"+combatRoll+" .col"+combatCol).css('color',"white");
            }
            str += "";
            var noCombats = false;
            if(Object.keys(combatRules.combatsToResolve) == 0){
                noCombats = true;
                str += "there are no combats to resolve";
            }
            var combatsToResolve = 0;
            toResolveLog += "Combats to Resolve<br>";
            for(i in combatRules.combatsToResolve){
                combatsToResolve++;
                if(combatRules.combatsToResolve[i].index !== null){
                    var atk = combatRules.combatsToResolve[i].attackStrength;
                    var atkDisp = atk;
                    ;

                    var def = combatRules.combatsToResolve[i].defenseStrength;
                    var ter = combatRules.combatsToResolve[i].terrainCombatEffect;
                    var combatCol = combatRules.combatsToResolve[i].index + 1;
                    var odds = Math.floor(atk / def);
                    var oddsDisp = $(".col" + combatCol).html()
                    newLine = "<h5>odds = " + oddsDisp + "</h5><div>Attack = " + atkDisp + " / Defender " + def + " = " + atk / def + "<br>Combined Arms Shift " + ter + " = " + oddsDisp + "</div>";
                    toResolveLog += newLine;
                }

            }
            if(combatsToResolve){
                str += "Combats To Resolve: " + combatsToResolve;
            }
            var resolvedCombats = 0;
            toResolveLog += "<br>Resolved Combats <br>";
            for(i in combatRules.resolvedCombats){
                debugger;
                resolvedCombats++;
                if(combatRules.resolvedCombats[i].index !== null){
                    atk = combatRules.resolvedCombats[i].attackStrength;
                    atkDisp = atk;
                    ;
                    if(combatRules.storm){
                        atkDisp = atk * 2 + " halved for storm " + atk;
                    }
                    def = combatRules.resolvedCombats[i].defenseStrength;
                    ter = combatRules.resolvedCombats[i].terrainCombatEffect;
                    idx = combatRules.resolvedCombats[i].index + 1;
                    newLine = "";
                    if(combatRules.resolvedCombats[i].Die){
                        var x = $("#" + cD).css('left').replace(/px/, "");
                        var mapWidth = $("body").css('width').replace(/px/, "");
                        /* STATUS_ELIMINATED */
                        if(data.force.units[cD].status != 22){
//                            if(x < mapWidth/2){
//                                var wrapWid = $("#crtWrapper").css('width').replace(/px/,"");
//                                var crtWid = $("#crt").css('width').replace(/px/,"");
//                                var moveLeft = $("body").css('width').replace(/px/,"");
//                                crtWid = crtWid - wrapWid + 40;
//                                $("#crt").animate({left:0 - crtWid},300);
//                                $("#crtWrapper").animate({left:moveLeft - wrapWid},300);
//                            }else{
//                                $("#crt").animate({left:crtWid},300);
//                                $("#crtWrapper").animate({left:0},300);
//
//                            }
                        }


                    }
                    var oddsDisp = $(".col" + combatCol).html()

                    newLine += " Attack = " + atkDisp + " / Defender " + def + atk / def + "<br>odds = " + Math.floor(atk / def) + " : 1<br>Combined Arms Shift " + ter + " = " + oddsDisp + "<br>";
                    newLine += "Roll: "+combatRules.resolvedCombats[i].Die + " result: " + combatRules.resolvedCombats[i].combatResult+"<br><br>";
                    if(cD === i){
                        newLine = "";
                    }
                    toResolveLog += newLine;
                }

            }
            if(!noCombats){
                str += " Resolved Combats: " + resolvedCombats + "";
            }
            $("#status").html(lastCombat + str);
            $("#status").show();

        }
    }
    $("#CombatLog").html(toResolveLog);
    $("#crt h3").html(title);


});
</script>