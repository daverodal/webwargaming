<body>
<style>
    body{
        background:#000;

    }
    h1{
        color:#f66;
        text-shadow: 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white, 0 0 5px white,0 0 5px white,0 0 5px white,0 0 5px white,
        0 0 5px white,0 0 5px white,0 0 5px white
    }
    .rebel{
        margin-right:100px;
        font-size:40px;
        color:#080;
    }
    .loyalist{
      font-size:40px;
        color:#18F;

    }
    legend   {
    color:white;
    }
   fieldset{

   }
</style>
<link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
<h2 style="text-align:center;font-size:30px;font-family:'Great Vibes'"> Welcome to</h2>
    <h1 style="text-align:center;font-size:90px;font-family:'Great Vibes'">The Martian Civil War</h1>
<div style="clear:both"></div>
<fieldset style="text-align:center;width:30%;margin:0 auto;"><Legend>Play As </Legend>
    <a class="rebel"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Enter Hotseat</a>
    <a class="loyalist" href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>">Enter Multi</a>
</fieldset>
