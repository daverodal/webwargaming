<body>
<link href='http://fonts.googleapis.com/css?family=Great+Vibes' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Petit+Formal+Script' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Monsieur+La+Doulaise' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Pinyon+Script' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Berkshire+Swash' rel='stylesheet' type='text/css'>
<style>
    <?php include "playAs.css";?>
    body{
        background:#000;
        background:url("<?=base_url("js/Battle_of_Minden_1759.jpeg")?>") #333 no-repeat;
        background-position:center 10%;
        background-size:100%;

    }
</style>
<div class="backBox">
<h2 style="text-align:center;font-size:30px;font-family:'Monsieur La Doulaise'"> Welcome to</h2>
    <h1 style=""><span>The Battle of Minden&nbsp;&nbsp;&nbsp;</span></h1>
</div>
<div style="clear:both"></div>
<fieldset ><Legend>Play As </Legend>
    <a  class="link" href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>/">Play Hotseat</a><br>
    <a  class="link" href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>/">Play Multi</a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a><br>
    <div class="attribution">
        Richard Caton Woodville [Public domain], <a target='blank' href="http://commons.wikimedia.org/wiki/File%3ACaton-Woodville_Battle_of_Minden_1759.jpeg">via Wikimedia Commons</a>
    </div>
</fieldset>
