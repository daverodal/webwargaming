<head>
    <style type="text/css">
        body{
            background:#ccc;
            color:#333;
<!--            background: url("--><?//=base_url("js/marsrover.jpg")?><!--") #333 no-repeat;-->
            background-position: 25% 0;
            background-size:1700px;
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
        .Prussian{
            color:rgb(255,253,127);
            color:rgb(12,0,162);
            border-color:rgb(255,253,127) !important;
        }
        .Russian{
            color:rgb(76,184,0);
            border-color:rgb(76,184,0) !important;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="left Prussian big">Prussians</div>
    <div class="right Russian big">Russians</div>
    <div class="clear"></div>
    <div class="left big Prussian">
        YOU
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right">
        <ul>
            {users}
            <li><a class="Russian" href="{path}/{wargame}/{me}/{key}">{key}</a></li>
            {/users}
        </ul>
    </div>
    <div class="clear"></div>
    <div class="big">OR</div>
    <div class="left">
        <ul>
            {others}
            <li><a class="Prussian" href="{path}/{wargame}/{key}">{key}</a></li>
            {/others}
        </ul>
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right big Russian">YOU</div>
    <div class="clear"></div>
    <div>
        <a href="<?=site_url("wargame/play");?>">Back to lobby</a>
    </div>
</div>
