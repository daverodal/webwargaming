<head>
<meta charset="UTF-8">
    <style type="text/css">
        <?php include "playAs.css";?>
        body{
            background:#ccc;
            color:#333;
            background: url("<?=base_url("js/KhalkhinGolSoldiers.jpg")?>") #333 no-repeat;
            background-position: 0 0;
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
    </style>
</head>
<body>
<div class="wrapper">
    <div class="left rebel big">Japanese</div>
    <div class="right loyalist big">Soviet</div>
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
    By NA (Contemporary Military Historian) [Public domain], <a target='blank' href="http://commons.wikimedia.org/wiki/File%3ABattle_of_Khalkhin_Gol-Japanese_soldiers.jpg">via Wikimedia Commons</a>
</footer>