<body>
<style>
    <?php @include "playAs.css";?>
    body{
        background:url("<?=base_url("js/lossy-page1-1280px-German_troops_in_Russia_-_NARA_-_540156.tif.jpg")?>") #333 no-repeat;
        background-position:center 0;
        background-size:100%;
    }
    @font-face{
        font-family: Ussr;
        src: url('<?=base_url("js/Back_In_the_USSR_DL_k.ttf");?>');
    }
    @font-face{
        font-family: Kremlin;
        src: url('<?=base_url("js/kremlin.ttf");?>');
    }
    #playastitle{
        font-family: Ussr;
    }
    #welcome{
        font-family: Kremlin;
    }
</style>
<link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>

<h2 id="welcome" style="text-align:center;font-size:30px;">Komrad,  Welcome to</h2>
<h1 id='playastitle' style="text-align:center;font-size:90px;">Moscow &#167;</h1>
<div class="clear">&nbsp;</div>
<fieldset ><Legend>Play As </Legend>
    <a class="link"  href="<?=site_url("wargame/enterHotseat");?>/<?=$wargame?>">Play Hotseat</a><br>
    <a class="link"  href="<?=site_url("wargame/enterMulti");?>/<?=$wargame?>">Play Multi Player </a><br>
    <a class="link" href="<?=site_url("wargame/leaveGame");?>">Go to Lobby</a>
    <div class="attribution">
        By Unknown or not provided [Public domain], <a target="blank" href="http://commons.wikimedia.org/wiki/File%3AGerman_troops_in_Russia_-_NARA_-_540156.tif">via Wikimedia Commons</a>
    </div>
</fieldset>
