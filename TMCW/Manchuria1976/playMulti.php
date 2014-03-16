<head>
    <style type="text/css">
        body{
            background:#ccc;
            color:#333;
            background: url("<?=base_url("js/ChineseType59Small.png")?>") #333 no-repeat;
            background-position: 10% 0;
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
        .soviet{
            color:red;
        }
        .chinese{
            color:rgb(255, 165, 0);
        }
        .big{
            font-size: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="left soviet big">Soviets</div>
    <div class="right chinese big">Chinese</div>
    <div class="clear"></div>
    <div class="left big soviet">
        YOU
    </div>
    <div class="center">&laquo;&laquo;vs&raquo;&raquo;</div>
    <div class="right">
        <ul>
            {users}
            <li><a class="chinese" href="{path}/{wargame}/{me}/{key}">{key}</a></li>
            {/users}
        </ul>
    </div>
    <div class="clear"></div>
    <div class="big">OR</div>
    <div class="left">
        <ul>
            {others}
            <li><a class="soviet" href="{path}/{wargame}/{key}">{key}</a></li>
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
