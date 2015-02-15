<head>
    <style type="text/css">
        <?php @include "playMulti.css";?>
        body{
            background:#ccc;
            color:#333;
            background: url("<?=base_url("js/1280px-Amphibious_Assault_Vehicle_(AAV).jpg")?>") #333 no-repeat;
            background-position: 10% 0;
            background-size:100%;
        }

    </style>
</head>
<body>
<div class="wrapper">
    <div class="left rebel big">Rebel (invaders)</div>
    <div class="right loyalist big">Loyalist (defenders)</div>
    <div class="clear"></div>
    <div class="left big rebel">
        YOU
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right">
        <ul>
            {users}
            <li><a class="loyalist" href="{path}/{wargame}/{me}/{key}">{key}</a></li>
            {/users}
        </ul>
    </div>
    <div class="clear"></div>
    <div class="big">OR</div>
    <div class="left">
        <ul>
            {others}
            <li><a class="rebel" href="{path}/{wargame}/{key}">{key}</a></li>
            {/others}
        </ul>
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right big loyalist">YOU</div>
    <div class="clear"></div>
    <div>
        <a href="<?=site_url("wargame/play");?>">Back to lobby</a>
    </div>
</div>
<footer class="attribution">
    By Mate 1st Class Brien Aho, U.S. Navy (http://www.navy.mil; exact Source) [Public domain], <a target="blank" href="http://commons.wikimedia.org/wiki/File%3AAmphibious_Assault_Vehicle_(AAV).jpg">via Wikimedia Commons</a>
</footer>