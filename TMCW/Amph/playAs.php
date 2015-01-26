<body>
<link href='http://fonts.googleapis.com/css?family=Poiret+One|Lobster' rel='stylesheet' type='text/css'>
<style>
    <?php @include "playAs.css";?>
    body{
        background:url("<?=base_url("js/1280px-1944_NormandyLST_clean.jpg")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;
    }
</style>

<h2 style="text-align:center;font-size:30px;font-family:'Poiret One'"> Welcome to</h2>
<h1 style="text-align:center;font-size:90px;font-family:'Lobster'">Sur La Mer</h1>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a>
    <div class="attribution">
        By Official U.S. Navy Page from United States of America MC2 Tamara Vaughn/U.S. Navy (Sailors conduct amphibious assault training.) [Public domain], <a target='blank' href="http://commons.wikimedia.org/wiki/File%3ASailors_conduct_amphibious_assault_training._(9137290248).jpg">via Wikimedia Commons</a>
    </div>
</fieldset>
