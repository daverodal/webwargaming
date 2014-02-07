<head>
    <style type="text/css">
        body{
            background:#ccc;
            color:#333;
            background: url("<?=base_url("js/The_Battle_of_Malplaquet_1709.png")?>") #333 no-repeat;
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
        .rebel{
            color:red;
        }
        .loyalist{
            color:blue;
        }
        .big{
            font-size: 50px;
            text-align: center;
        }
        .Anglo{
            color:#f00;
        }
        .Prussian{
            color:rgb(255,253,127);
            color:rgb(12,0,162);
            border-color:rgb(255,253,127) !important;
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
    <div class="left Anglo big"><?= $playerOne;?></div>
    <div class="right French big"><?= $playerTwo;?></div>
    <div class="clear"></div>
    <div class="left big Anglo">
        YOU
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right">
        <ul>
            {users}
            <li><a class="French" href="{path}/{wargame}/{me}/{key}">{key}</a></li>
            {/users}
        </ul>
    </div>
    <div class="clear"></div>
    <div class="big">OR</div>
    <div class="left">
        <ul>
            {others}
            <li><a class="Anglo" href="{path}/{wargame}/{key}">{key}</a></li>
            {/others}
        </ul>
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right big French">YOU</div>
    <div class="clear"></div>
    <div>
        <a href="<?=site_url("wargame/play");?>">Back to lobby</a>
    </div>
</div>
