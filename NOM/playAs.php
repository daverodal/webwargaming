<body>
<link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
<style>
    body{
        background:#000;
        background:url("<?=base_url("js/Napoleon.jpg")?>") #333 no-repeat;
        background-position:center top;

    }
    h2{
        color:#f66;
        text-shadow: 0 0 3px white,0 0 3px white,0 0 3px white,0 0 3px white,0 0 3px white,0 0 3px white,
        0 0 3px white,0 0 3px white;
    }
    h1{
        text-align:center;
        font-size:90px;
        font-family:'Great Vibes';
        color:#f66;
        margin-top:0px;
        text-shadow: 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white, 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white;
    }
    .link{
        font-size:40px;
        text-decoration: none;
        color:#f66;
        text-shadow: 3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black,3px 3px 3px black
    }
    legend   {
    color:white;
    }
   fieldset{
        text-align: center;
       width:30%;
       margin:0px;
       position:absolute;
       top:500px;
       left:50%;
       margin-left:-15%;
       background-color: rgba(255,255,255,.4);
   }
</style>

<h2 style="text-align:center;font-size:30px;font-family:'Great Vibes'"> Welcome to</h2>
    <h1 style="">Napoleons Training Academy</h1>
<div style="clear:both"></div>
<fieldset ><Legend>Play As </Legend>
    <a  class="link" href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>/">Play Hotseat</a><br>
    <a  class="link" href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>/">Play Multi</a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a><br>
</fieldset>
