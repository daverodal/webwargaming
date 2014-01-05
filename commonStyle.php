<style type="text/css">
body{
    min-width:675px;
}
h2 #status{
    font-size:16px;
}
h5{
    margin:0px;
}
h4:hover{
    text-decoration:underline;
}
#rightHeader{
    /*width:50%;*/
    /*float:right;*/
}
#leftHeader{
    width:49%;
    float:left;
    display:none;
}
.shadowy{
    background-color: rgba(0,0,0,.3) !important;
}
#turn,#phase, #status,#victory{
    background:white;
    padding:0 3px;
    font-family:sans-serif;
}
#victory{
    padding:0;
}
#floatMessage{
    white-space: nowrap;
    position:absolute;
    display:none;
    background:rgba(255,255,255,.9);
    border-radius: 10px;
    border:2px solid black;
    box-shadow: 10px 10px 10px rgba(30,30,30,.85);
    z-index:4;
    padding:0 5px;
    cursor:move;
}
#floatMessage header{
    font-size:35px;
}
#altTable, #mainTable {
    position: absolute;
    right: 18px;
    cursor:pointer;
}
#comlinkWrapper{
    border:1px solid black;
    border-radius: 10px;
    background:white;
    padding:0 2px 0 2px;
    font-style: italic;
}
.unit.pushed section{
    /*color:#f3172d !important;*/
    /*color:mediumpurple !important;*/
    /*background-color: #858585 !important;*/
    background-color: rgba(0,0,0,.5) !important;

}
#muteButton{
    cursor:pointer;
    text-decoration: underline;
}
    /*.embossed{*/
    /*-webkit-box-shadow: inset 0 1px 0 rgba(255,255,255,.5), inset 0 -2px 0 rgba(0,0,0,.25), inset 0 -3px 0 rgba(255,255,255,.2), 0 1px 0 rgba(0,0,0,.1);*/
    /*-moz-box-shadow: inset 0 1px 0 rgba(255,255,255,.5), inset 0 -2px 0 rgba(0,0,0,.25), inset 0 -3px 0 rgba(255,255,255,.2), 0 1px 0 rgba(0,0,0,.1);*/
    /*box-shadow: inset 0 1px 0 rgba(255,255,255,.5), inset 0 -2px 0 rgba(0,0,0,.25), inset 0 -3px 0 rgba(255,255,255,.2), 0 1px 0 rgba(0,0,0,.1);*/
    /*}*/
.specialHexes,.specialHexesVP{
    position:absolute;
    text-transform: capitalize;
    background:yellow;
    opacity:.8;
}
.specialHexesVP{
    background:transparent !important;
    color:white;
}
.specialHexesVP .loyalistVictoryPoints{
    text-shadow:
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),

        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 1px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 2px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 3px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 4px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 5px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 6px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 7px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 8px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 9px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255),
        0px 0px 10px rgb(132,181,255);
}
.specialHexesVP .rebelVictoryPoints{
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
#display{
    display:none;
    position:fixed;
    top:25%;
    width:90%;
    left:5%;
    padding:30px 0;
    background:rgba(255,255,255,.9);
    font-size:100px;
    text-align: center;
    border:#000 solid 10px;
    border-radius: 30px;
    z-index:4;
}
.flashMessage{
    position:fixed;
    width:80%;
    background:rgba(255,255,255,.9);
    /*background:transparent;*/
    text-align: center;
    border-radius:30px;
    border:10px solid black;
    /*border:none;*/
    font-size:100px;
    z-index:1000;
    pointer-events:none;
}
#display button{
    font-size:30px;
}
#comlinkWrapper h4{
    font-weight: bold;
    margin:3px;
}
#header{
    position:fixed;
    width:99%;
    top:0px;
    /*background:#99cc99;*/
    /*background:rgb(132,181,255);*/
    padding-bottom:5px;
    z-index:2000;
}
#mouseMove{
    display:none;
}
#headerContent{
    /*min-height:110px;*/
}
#nextPhaseButton{
    font-size:15px;
    position: static;
    bottom: 0;
    left:17%;
    display:inline-block;
}
#header a, #header a:visited{
    color:white;
}
#phaseClicks a{
    color:black;
}
#header a:hover{
    color:#ddd !important;
}
#header h2{
    margin: 10px 0 5px;
}
#content{
    margin-top:140px;
}
.clear{
    clear:both !important;
    float:none !important;
}
.blueUnit{
    background-color:rgb(132,181,255);
}
.darkBlueUnit{
    background-color:rgb(99,136,192);
}
.redUnit{
    background-color:rgb(239,115,74);
}
.darkRedUnit{
    background-color:rgb(179,86,55);
}

.armyGreen{
    background-color:  rgb(148,189,74);
}
.gold{
    background-color:rgb(247,239,142);
}

.lightYellow{
    background-color: rgb(255,239,156);
}
.gray{
    background-color: rgb(181,181,156);
}
.oliveDrab{
    background-color: rgb(115,156,115);
}
.kaki{
    background-color: rgb(222,198,132);
}
.lightGreen{
    background-color: rgb(181,206,115);
}
.lightBlue{
    background-color: rgb(196,216,228);
}
.lightGray{
    background-color: rgb(216,218,214);
}
.brightGreen{
    background-color: rgb(123,222,82);
}
.flesh{
    background-color: rgb(239,198,156);
}
.brightRed{
    background-color: rgb(223,88,66);
}

#phaseDiv,#statusDiv,#chatsDiv,#crtWrapper,#victoryDiv{
    display:inline;
    vertical-align: top;
    /*float:left;*/
}
#statusDiv{

}
#infoWrapper, #menuWrapper{
    user-select:text;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    /*float:left;*/
    position: absolute;
}
#TimeWrapper, #crtWrapper , #OBCWrapper,#TECWrapper,#VCWrapper,#jumpWrapper,#GRWrapper{
    user-select:text;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    /*float:left;*/
    position: relative;
    display:inline-block;

}
.dropDown , #hideShow, #showDeploy{
    border:1px solid #333;
}
.dropDownSelected{
    background:white;
}
#TimeWrapper .WrapperLabel, #crtWrapper .WrapperLabel, #OBCWrapper .WrapperLabel, #TECWrapper .WrapperLabel,#VCWrapper .WrapperLabel,#jumpWrapper .WrapperLabel,#menuWrapper .WrapperLabel,#infoWrapper .WrapperLabel, #GRWrapper .WrapperLabel{
    margin:0;
    border:none;
    user-select:text;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    cursor: pointer;
    /*width:3em;*/
}
#menuWrapper .WrapperLabel,#infoWrapper .WrapperLabel{
width:3em;
}
#menuWrapper h4{
    width:2.5em;
}
#crtWrapper{
    left:0px;
    bottom:0px;
}
#zoom{
    margin-left:6em;
}
#zoom{
    cursor: pointer;
}
#zoom :first-child{
    text-decoration: underline;
}
#clock{
    margin-left:0;
}
#menuWrapper{
    width:6em;
}
#infoWrapper{
    left:45px;
}
#menuWrapper li{
    list-style-type: none;
    padding:5px 10px 5px 10px;
    border:0 solid black;
    border-width:1px 1px 0 1px;
}
#infoWrapper li{
    list-style-type: none;
    padding:5px 10px 5px 10px;
    border:0 solid black;
    border-width:1px 1px 0 1px;
}
#menuWrapper li a, #menuWrapper li a:visited{
    color:#333;
    text-decoration:none;
}
#infoWrapper li a, #infoWrapper li a:visited{
    color:#333;
    text-decoration:none;
}
#VCWrapper{
    /*width:10%;*/
    /*left:48%;*/
    /*bottom:0px;*/
}
#jumpWrapper{
    /*width:6%;*/
    /*left:41%;*/
    /*bottom:0px;*/
}
#OBC{
    /*left:0px;*/
}
#TEC ul{
    margin:0;
    padding:0
}
#menu ul{
    margin:0;
    padding:0;
}
#Time ul{
    margin:0;
    padding:0;
}
#menu{
    background:white;
    color:#333;
    width:160%;
}
#info ul{
    margin:0;
    padding:0;
}
#info{
    background:white;
    color:#333;
    width:10em;
}
.close{
    position: absolute;
    border: 1px solid #ccc;
    right:3px;
    top:3px;
    color:#ccc;
    font-size:11px;
    line-height: 12px;
    cursor:pointer;
}
#VC ul{
    margin:0;
    padding:0
}
#hideShow,#showDeploy{
    cursor:pointer;
    position: static;
    font-weight: bold;
}
#OBC{
    background:white;
    width:514px;
}
#OBC .unit{
    margin-top:10px;
}
#OBC .unit section{
    /*background-color:rgba(0,0,0,.3);*/
}
#Time{
    background:white;
    width:200px;
}
#TEC{
    background:white;
    width:579px;
}
#GR{
    background:white;
    max-height:500px;
    min-height:100px;
    overflow-y:auto;
    padding:0 20px 10px;
}
#VC{
    background:white;
    width:579px;
    left:-230px;
}
#crtWrapper .WrapperLabel{
    width: auto;

}
#crt{
    width:308px;
}
#clickCnt{
    float:left;
    padding:3px;
}
#crtWrapper h4 .goLeft,#crtWrapper h4 .goRight{
    font-size:22px;
    padding:0 10px;
}
#OBCWrapper h4 {
    /*width:2em;*/
}
#OBC, #crt, #TEC,#VC,#jump,#menu,#info,#GR, #Time{
    position:absolute;
    z-index:30;
    display:none;
}
.closer{
    height:0px;
    padding:0px !important;
}
#crt{
    z-index: 40;
}
#OBCWrapper h4:focus,#crtWrapper h4:focus{
    outline: -webkit-focus-ring-color none;
}
body{
    background:#eee;
    color:#333;
}

#clock{
    margin-left:5em;
    /*width:100px;*/
}
#status{
    /*height:70px;*/
    /*overflow-y:scroll;*/
    /*text-align:right;*/
}
#status legend{
    /*text-align:left;*/
}
fieldset{
    background:white;
    border-radius:9px;
}
#OBC fieldset{
    float:left;
    min-height: 100px;
}
.left{
    float:left;
}
.right{
    float:right;
}
#crt{
    border-radius:15px;
    border-width: 10px;
    border-style: solid;
    /*border:10px solid rgb(132,181,255);*/
    background:#fff;color:black;
    font-weight:bold;
    padding:1px 5px 10px 15px;
    box-shadow: 0px 0px 2px black;
}
#crt h3{
    height:40px;
    margin-bottom:5px;
    vertical-align:bottom;
}
#crt span{
    width:32px;
}
.col1{
    left:20px;
}
.col2{
    left:60px;
}
.col3{
    left:100px;
}
.col4{
    left:140px;
}
.col5{
    left:180px;
}
.col6{
    left:220px;
}
.roll, #odds{
    height:20px;
    /*background :#1af;*/
    background: rgb(132,181,255);
    margin-right:14px
}
#odds{
    background:white;
}
.even{
    color:black;
}
.odd{
    color:black;
}
.row1{
    top:80px;

}
.row2{
    top:100px;
    background:white;
}
.row3 {
    top:120px;
}
.row4{
    top:140px;
    background:white;
}
.row5{
    top:160px;
}
.row6{
    top:180px;
    background:white;
}
.roll span, #odds span{
    margin-right:10px;
    float:left;
    display:block;
    width:32px;
}
#gameImages{
    position: relative;
}
#gameViewer{
    position:fixed;
    border:10px solid #555;
    border-radius:10px;
    overflow:hidden;
    background:#ccc;
    margin-bottom:5px;
}
#leftcol {
    /*float:left;
    width:360px;*/
}
#rightCol{
    /*float:right;*/
    overflow:hidden;
}
#gameturnContainer{
    height:38px;
    position:relative;
    float:left;
}
#gameturnContainer div{
    float:left;
    height:36px;
    width:36px;
    border:solid black;
    border-width:1px 1px 1px 0;
    font-size:18px;
    text-indent:2px;
}
.storm {
    font-size:50%;
}
#gameturnContainer #turn1{
    border-width:1px;
}
#turnCounter{
    width:32px;
    height:32px;
    font-size:11px;
    text-indent:0px;
    top:2px;
    left:2px;
    text-align:center;
    border:2px solid;
    border-color:#ccc #666 #666 #ccc;

}

#content{
    -webkit-user-select:none;
    -moz-user-select:none;
    user-select:none;

}
#map {
    -webkit-user-select:none;
    width:<?=$mapWidth;?>;/*really*/
    height:<?=$mapHeight;?>;
    height:<?=$mapHeight;?>;
    position:relative;
    z-index: 0;
}
#gameImages {
    width:<?=$mapWidth;?>;/*really*/
    height:<?=$mapHeight;?>;
}
#deadpile{
    border-radius:10px;
    border:10px solid #555;
    height:70px;
    background:#333;
    overflow:hidden;
    position: relative;
    margin-bottom:5px;
    /*display:none;*/
}
#deployBox{
    position:relative;

}
#deployWrapper{
    /*display:none;*/
    padding:4px 7px 4px 7px;
    text-align:left;
    font-family:sans-serif;
    font-size:1.2em;
    color:white;
    border-radius:10px;
    border:10px solid #555;
    background:#aaa;
    margin-bottom:5px;
}
#deployWrapper .unit{
    margin-right:5px;
}
.unit{
    width:64px;
    height:64px;
    width:48px;
    height:49px;
    position:absolute;
    left:0;top:0;
    z-index:3;
    width:<?=$unitSize?>;
    height:<?=$unitSize?>;
    /*width:32px;*/
    /*height:32px;*/
}
.unit section{
    top:0px;
    left:0px;
    height:100%;
    width:100%;
    position:absolute;
    background:transparent;
}
.unit div {
    text-align:center;
    margin-top:<?=$unitMargin?>;
    color:black;
    /*text-indent:3px;*/
    font-size:<?=$unitFontSize?>;
    font-weight:bold;
    font-family: serif;
    -webkit-user-select:none;
}
.unit .unitSize{
    font-size:10px;
    line-height: 5px;
    text-align: center;
    color:black;
}
.unit .unit-numbers{
    height:12px;
    font-size:11px;
}
.unit img{
    width:100%;
    height:100%;
    max-height:100px;
    max-width:100px;
}
.unit .counter {
    width:100%;
    height:29px;

}
.arrowClone,.arrow{
    position:absolute;
    pointer-events:none;
    z-index:102;
    top:0px;
}
.clone{
    /*pointer-events:none;*/
}
.occupied{
    display:none;
}
#TEC{
    max-height:500px;
    overflow:auto;
}
</style>