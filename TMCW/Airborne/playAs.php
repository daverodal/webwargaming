<body>
<style>
    <?php @include "playAs.css";?>
    body{
        background:url("<?=base_url("js/C-47_Dakotas_and_paratroops.jpg")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;
    }
</style>
<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>

<h2 style="text-align:center;font-size:30px;font-family:'Great Vibes'"> Welcome to</h2>
<h1 style="text-align:center;font-size:90px;font-family:'Nosifer'">Uit de Hemel</h1>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a>
    <div class="attribution">
        By Sgt. D.M. Smith, British Army Film and Photographic Unit [Public domain], <a target="blank" href="http://commons.wikimedia.org/wiki/File%3AC-47_Dakotas_and_paratroops.jpg">via Wikimedia Commons</a>    </div>
</fieldset>
