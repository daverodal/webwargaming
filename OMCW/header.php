<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<style type="text/css">
    .blueUnit, .loyalist{
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
    .sympth{
        background-color:gold;
    }
    .armyGreen, .loyalist{
    background-color:  rgb(148,189,74);
    }
    .gold{
    background-color:rgb(247,239,142);
    }
    .rebel{
        background-color:rgb(239,115,74);
    }
    .lightYellow, .rebel{
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
    .flesh, .sympth{
        background-color: rgb(239,198,156);
    }
    .brightRed, .rebel, .sympth{
        background-color: rgb(223,88,66);
    }

    body{
        background:#eee;
        color:#333;
    }
    #status{
        text-align:right;
    }
    #status legend{
        text-align:left;
    }
    fieldset{
        background:white;
        border-radius:9px;
    }
    .left{
        float:left;
    }
    .right{
        float:right;
    }
    #crt{
        border-radius:15px;
        border:10px solid #1AF;
    //position:relative;
        width:308px;
        background:#fff;color:black;
        font-weight:bold;
        padding:1px 5px 10px 15px;
    }
    #crt h3{
        height:40px;
        margin-bottom:5px;
        vertical-align:bottom;
    }
    #crt span{
        width:32px;
    // position:absolute;
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
        background :#1af;
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
        border:10px solid #555;
        border-radius:10px;
        overflow:hidden;
        background:#620;
        margin-bottom:5px;
    }
    #leftcol {
        float:left;
        width:360px;
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

        width:1044px;
        height:850px;
        width:783px;
        height:638px;
    width:<?=$mapWidth;?>;/*really*/
    height:<?=$mapHeight;?>;
        /*width:787px;*/
        /*height:481px;*/
        }
    #gameImages {
        width:<?=$mapWidth;?>;/*really*/
        height:<?=$mapHeight;?>;
    }
    #deadpile{
        border-radius:10px;
        border:10px solid #555;
        height:100px;
        background:#333;
        overflow:hidden;
    }
    #deployBox{
        position:relative;
    }
    #deployWrapper{
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
    .unit{
        width:64px;
        height:64px;
        width:48px;
        height:49px;
        position:absolute;
        left:0;top:0;
    width:<?=$unitSize?>;
    height:<?=$unitSize?>;
        /*width:32px;*/
        /*height:32px;*/
        }
    .unit div {
        text-align:center;
    margin-top:<?=$unitMargin?>;
    color:black;
        /*text-indent:3px;*/
    font-size:<?=$unitFontSize?>;
    font-weight:bold;
    -webkit-user-select:none;
        }
    .rebel div{
        color:black;
    }
    .sympth div{
        color:black;
    }
    .unit img {

    }
    .arrow{
        position:absolute;
        pointer-events:none;
        z-index:102;
    }
    .clone{
        /*pointer-events:none;*/
    }

    .playerOne{
        background:rgb(223,88,66);
        border-color:rgb(223,88,66) !important;
    }
    .playerTwo{
        background:rgb(148,189,74);;
        border-color:rgb(132,181,255) !important;
    }
</style>
<script>



</script>