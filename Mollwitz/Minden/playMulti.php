<head>
    <style type="text/css">
        <?php include "playAs.css";?>
        body{
            background:#ccc;
            color:#333;
            background: url("<?=base_url("js/Battle_of_Minden_1759.jpeg")?>") #333 no-repeat;
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
        a{
            color:#000;
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
        .Anglo{
            color:#f00;
        }

        .French{
            color:rgb(61,110,255);;
            border-color:rgb(61,110,255); !important;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <?php global $force_name;$playerOne = $force_name[1];
    $playerTwo = $force_name[2];?>
    <div class="left French big"><?= $playerOne;?></div>
    <div class="right Anglo big"><?= $playerTwo;?></div>
    <div class="clear"></div>
    <div class="left big French">
        YOU
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right">
        <ul>
            {users}
            <li><a class="Anglo" href="{path}/{wargame}/{me}/{key}">{key}</a></li>
            {/users}
        </ul>
    </div>
    <div class="clear"></div>
    <div class="big">OR</div>
    <div class="left">
        <ul>
            {others}
            <li><a class="French" href="{path}/{wargame}/{key}">{key}</a></li>
            {/others}
        </ul>
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right big Anglo">YOU</div>
    <div class="clear"></div>
    <div>
        <a href="<?=site_url("wargame/play");?>">Back to lobby</a>
    </div>
</div>
<footer class="attribution">
    Richard Caton Woodville [Public domain], <a target='blank' href="http://commons.wikimedia.org/wiki/File%3ACaton-Woodville_Battle_of_Minden_1759.jpeg">via Wikimedia Commons</a>
</footer>