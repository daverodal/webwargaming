<head>
    <style type="text/css">
        <?php @include "playMulti.css";?>
        body{
            background:#ccc;
            color:#333;
            background: url("<?=base_url("js/Bundesarchiv_Bild_146-1981-149-34A,_Russland,_Herausziehen_eines_Autos.jpg")?>") #333 no-repeat;
            background-position: 10% 0;
            background-size:100%;
        }

    </style>
</head>
<body>
<div class="wrapper">
    <div class="left german big">German (invaders)</div>
    <div class="right soviet big">Soviet (defenders)</div>
    <div class="clear"></div>
    <div class="left big german">
        YOU
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right">
        <ul>
            {users}
            <li><a class="soviet" href="{path}/{wargame}/{me}/{key}">{key}</a></li>
            {/users}
        </ul>
    </div>
    <div class="clear"></div>
    <div class="big">OR</div>
    <div class="left">
        <ul>
            {others}
            <li><a class="german" href="{path}/{wargame}/{key}">{key}</a></li>
            {/others}
        </ul>
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right big soviet">YOU</div>
    <div class="clear"></div>
    <div>
        <a href="<?=site_url("wargame/play");?>">Back to lobby</a>
    </div>
</div>
<footer class="attribution">
    Bundesarchiv, Bild 146-1981-149-34A / CC-BY-SA [<a href="http://creativecommons.org/licenses/by-sa/3.0/de/deed.en">CC-BY-SA-3.0-de</a>], <a target="blank" href="http://commons.wikimedia.org/wiki/File%3ABundesarchiv_Bild_146-1981-149-34A%2C_Russland%2C_Herausziehen_eines_Autos.jpg">via Wikimedia Commons</a>
<!--    By United States Information Agency [Public domain], <a target="blank" href="http://commons.wikimedia.org/wiki/File%3ABattle_of_Moscow.jpg">via Wikimedia Commons</a>-->
</footer>