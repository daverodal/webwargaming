<body xmlns="http://www.w3.org/1999/html">
<div id="theDiv">
<header id="header">
    <div id="headerContent">
        <div id="rightHeader">
            <div id="mouseMove">mouse</div>
            <div id="comlinkWrapper" style="float:right;">
                <div id="comlink"></div>
            </div>
            <div id="menuWrapper">
                <h4 class="WrapperLabel" title="Game Menu">Menu</h4>

                <div id="menu">
                    <div class="close">X</div>
                    <ul>
                        <li><a id="muteButton">mute</a></li>
                        <li><a href="<?= site_url("wargame/leaveGame"); ?>">Go To Lobby</a></li>
                        <li><a href="<?= site_url("users/logout"); ?>">logout</a></li>
                        <!--                        <li><a href="-->
                        <? //=site_url("wargame/unitInit/MartianCivilWar");?><!--">Restart Game</a></li>-->
                        <li><a id="arrowButton">show arrows</a></li>
                        <li><a href="#" onclick="seeUnits();return false;">See Units</a></li>
                        <li><a href="#" onclick="seeBoth();return false;">See Both</a></li>
                        <li><a href="#" onclick="seeMap();return false;">See Map</a></li>
                        <li class="closer"></li>
                    </ul>
                </div>
            </div>
            <div id="infoWrapper">
                <h4 class="WrapperLabel" title="Game Information">Info</h4>

                <div id="info">
                    <div class="close">X</div>
                    <ul>
                        <li> Welcome {user}</li>
                        <li>you are playing as  <?= $youAre; ?></li>
                        <li>in <span class="game-name">{gameName}-{arg}</span></li>
                        <li> The file is called {name}</li>
                        <li>Game Designer: Lance Runolfsson</li>

                        <li class="closer"></li>
                    </ul>
                </div>
            </div>
                <span id="zoom">
                    <span>2.0</span>
                    <span>1.5</span>
                    <span>1.3</span>
                    <span>1.2</span>
                    <span>1.1</span>
                    <span class="defaultZoom" data-zoom="1">Zoom 1</span>
                    <span>.9</span>
                    <span>.8</span>
                    <span>.7</span>
                    <span>.6</span>
                    <span>.5</span>
                    <span>.4</span>
                    <span>.3</span>
                </span>

            <span id="clock"></span>
            <span id="status"></span>
            <span id="victory"></span>
        </div>
        <div style="clear:both;"></div>

    </div>
    <?php global $results_name; ?>
    <div id="bottomHeader" style="clear:both;">
        <div id="crtWrapper">
            <h4 class="WrapperLabel" title='Combat Results Table'>
                <span class="goLeft">&laquo;</span>CRT<span class="goRight">&raquo;</span></h4>

            <div id="crt">
                <div class="close">X</div>
                <div id="altTable">show cavalry table</div>
                <div id="mainTable">show normal table</div>
                <h3>Combat Odds</h3>

                <div class="tableWrapper main">
                    <div id="odds">
                        <span class="col0">&nbsp;</span>
                        <?php
                        $crt = new CombatResultsTable();

                        $i = 1;
                        foreach ($crt->combatResultsHeader as $odds) {
                            ?>
                            <span class="col<?= $i++ ?>"><?= $odds ?></span>
                        <?php } ?>
                    </div>
                    <?php
                    $rowNum = 1;
                    $odd = ($rowNum & 1) ? "odd" : "even";
                    foreach ($crt->combatResultsTable as $row) {
                        ?>
                        <div class="roll <?= "row$rowNum $odd" ?>">
                            <span class="col0"><?= $rowNum++ ?></span>
                            <?php $col = 1;
                            foreach ($row as $cell) {
                                ?>
                                <span class="col<?= $col++ ?>"><?= $results_name[$cell] ?></span>

                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="tableWrapper alt">
                    <div id="odds">
                        <span class="col0">&nbsp;</span>
                        <?php
                        $crt = new CombatResultsTable();

                        $i = 1;
                        foreach ($crt->combatResultsHeader as $odds) {
                            ?>
                            <span class="col<?= $i++ ?>"><?= $odds ?></span>
                        <?php } ?>
                    </div>
                    <?php
                    $rowNum = 1;
                    $odd = ($rowNum & 1) ? "odd" : "even";
                    foreach ($crt->combatResultsTableCav as $row) {
                        ?>
                        <div class="roll <?= "row$rowNum $odd" ?>">
                            <span class="col0"><?= $rowNum++ ?></span>
                            <?php $col = 1;
                            foreach ($row as $cell) {
                                ?>
                                <span class="col<?= $col++ ?>"><?= $results_name[$cell] ?></span>

                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
                <div id="crtDetailsButton">details</div>
                <div id="crtOddsExp"></div>
            </div>
        </div>
        <button id="nextPhaseButton">Next Phase</button>
        <div class="dropDown" id="jumpWrapper">
            <h4 class="WrapperLabel" title="Jump Map">Jump</h4>
        </div>

        <?php //include_once "obc.php"; ?>

        <?php include_once "tec.php"; ?>
        <?php include "timeTravel.php"; ?>

        <?php include_once "commonRules.php"; ?>
        <?php include_once "exclusiveRules.php"; ?>
        <span id="hideShow">Dead Pile</span>
        <span id="showDeploy">Deploy/Staging Box</span>

        <div class="dropDown" id="CombatLogWrapper">
            <h4 class="WrapperLabel" title='Combat Log'>Log</h4>

            <div id="CombatLog" class="dropDownContent" style="display:none;">
                <div class="close">X</div>

            </div>
        </div>
        <?php if ($scenario->showHexNums) { ?>
            <span class="dropDown" id="showHexNums"> show/hide hex numbers</span>
        <?php } ?>
</header>
<div id="content">
    <div id="rightCol">
        <div id="deployWrapper">
            <div style="margin-right:3px;" class="left">deploy on turn one</div>
            <div id="deployBox"></div>
            <div style="clear:both;"></div>
        </div>
        <div style="display:none;" id="deadpile">
            <div style="right:10px;font-size:50px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">
                Retired Units
            </div>
        </div>
        <div id="gameViewer">
            <div id="gameContainer">
                <div id="gameImages" class="ui-widget-content">
                    <div id="svgWrapper">
                        <svg style="opacity:.6;position:absolute;" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <marker id='head' orient="auto"
                                        markerWidth='2' markerHeight='4'
                                        refX='0.1' refY='2'>
                                    <!-- triangle pointing right (+x) -->
                                    <path d='M0,0 V4 L2,2 Z' fill="#df5842"/>
                                </marker>
                            </defs>
                        </svg>
                    </div>
                    <img id="map" alt="map" src="<?php preg_match("/http/",$mapUrl) ?   $pre = '': $pre = base_url();echo "$pre$mapUrl";?>"
                         style="position: relative;visibility: visible;z-index: 0;">
                    <?php $id = 0; ?>
                    {units}
                    <div class="unit {class} {type}" id="{id}" alt="0">
                        <div class="shadow_mask"></div>
                        <div class="counterWrapper">
                            <div class="counter"></div>
                        </div>
                        <p class="range">{range}</p>
                        <p class="forceMarch">M</p>
                        <img class="arrow" src="<?php echo base_url(); ?>js/short-red-arrow-md.png" class="counter">
                        <div class="unit-numbers">5 - 4</div>

                    </div>
                    {/units}
                    <div id="floatMessage">
                        <header></header>
                        <p></p></div>
                </div>
            </div>
        </div>

        <audio class="pop" src="<?= base_url() . 'js/pop.m4a' ?>"></audio>
        <audio class="poop" src="<?= base_url() . 'js/lowpop.m4a' ?>"></audio>
        <audio class="buzz" src="<?= base_url() . 'js/buzz.m4a' ?>"></audio>

        <div style="clear:both;height:20px;"></div>
    </div>
</div>
<script type="text/javascript">
</script>
<div id="display"></div>
</div>
</body></html>