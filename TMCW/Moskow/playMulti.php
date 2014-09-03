<head>
    <style type="text/css">
        <?php @include "playMulti.css";?>
        body{
            background:#ccc;
            color:#333;
            background: url("<?=base_url("js/Battle_of_Moscow.jpg")?>") #333 no-repeat;
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
    By United States Information Agency [Public domain], <a target="blank" href="http://commons.wikimedia.org/wiki/File%3ABattle_of_Moscow.jpg">via Wikimedia Commons</a>
</footer>