<head>
    <style type="text/css">
        body{
            background:#ccc;
            color:#333;
            background: url("<?=base_url("js/Battle_of_Chillianwalla.jpg")?>") #333 no-repeat;
            background-position: 25% 0;
            background-size:100%;
        }
        .wrapper{
            background:rgba(255,255,255,.8);
            border-radius:15px;
            padding:20px;
            margin:20px;
            border:3px solid gray;
        }
        a.British{
            color:#F00;
        }
        li{
            list-style-type: none;
        }
        div{
            text-align:center;
        }
        .center{
            float:left;
            width:8%;
            font-size:45px;
        }
        .left{
            width:45%;

            float:left;
        }
        .right{
            width:45%;

            float:right;
        }
        .clear{
            clear:both;
        }
        .big{
            font-size: 50px;
            text-align: center;
        }
        .British{
            color:#f00;
        }
        .Prussian{
            color:rgb(255,253,127);
            color:rgb(12,0,162);
            border-color:rgb(255,253,127) !important;
        }
        .Sikh {
            color: #865900; }
    </style>
</head>
<body>
<div class="wrapper">
    <?php global $force_name;$playerOne = $force_name[1];
    $playerTwo = $force_name[2];?>
    <div class="left <?= $playerOne;?> big"><?= $playerOne;?></div>
    <div class="right <?= $playerTwo;?> big"><?= $playerTwo;?></div>
    <div class="clear"></div>
    <div class="left big <?= $playerOne;?>">
        YOU
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right">
        <ul>
            {users}
            <li><a class="<?= $playerTwo;?>" href="{path}/{wargame}/{me}/{key}">{key}</a></li>
            {/users}
        </ul>
    </div>
    <div class="clear"></div>
    <div class="big">OR</div>
    <div class="left">
        <ul>
            {others}
            <li><a class="<?= $playerOne;?>" href="{path}/{wargame}/{key}">{key}</a></li>
            {/others}
        </ul>
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right big <?= $playerTwo;?>">YOU</div>
    <div class="clear"></div>
    <div>
        <a href="<?=site_url("wargame/play");?>">Back to lobby</a>
    </div>
</div>
<footer class="attribution">
    By Not given [Public domain], <a target='blank' href="http://commons.wikimedia.org/wiki/File%3ABattle_of_Chillianwalla.jpg">via Wikimedia Commons</a>
</footer>