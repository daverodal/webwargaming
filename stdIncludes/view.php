<link rel="stylesheet" href="<?= base_url("js/font-awesome-4.2.0/css/font-awesome.min.css"); ?>">
<body xmlns="http://www.w3.org/1999/html">
<div id="theDiv">
    <header id="header">
        <div id="headerContent">
            <div id="mouseMove">mouse</div>

            <div class="dropDown alpha" id="menuWrapper">
                <h4 class="WrapperLabel" title="Game Menu"><i class="tablet fa fa-bars"></i><span class="desktop">Menu</span></h4>

                <div id="menu">
                    <div class="close">X</div>
                    <ul>
                        <li><a id="muteButton">mute</a></li>
                        <li><a href="<?= site_url("wargame/leaveGame"); ?>">Go To Lobby</a></li>
                        <li><a href="<?= site_url("users/logout"); ?>">logout</a></li>
                        <li><a id="arrowButton">show arrows</a></li>
                        <li><a href="#" onclick="seeUnits();return false;">See Units</a></li>
                        <li><a href="#" onclick="seeBoth();return false;">See Both</a></li>
                        <li><a href="#" onclick="seeMap();return false;">See Map</a></li>
                        <li class="closer"></li>
                    </ul>
                </div>
            </div>
            <div class="dropDown" id="infoWrapper">
                <h4 class="WrapperLabel" title="Game Information"><i class="tablet">i</i><span class="desktop">Info</span></h4>
                <div id="info">
                    <div class="close">X</div>
                    <ul>
                        <li> Welcome {user}</li>
                        <li>you are playing as  <?= $player; ?></li>
                        <li>
                            in <span class="game-name">{gameName}-{arg}</span></li>
                        <li> The file is called {name}</li>
                        <!-- TODO: make game credits from DB -->
                        <li>Game Designer: David Rodal</li>
                        <li class="closer"></li>
                    </ul>
                </div>
            </div>
            <?php global $results_name; ?>

            <div id="crtWrapper">
                <h4 class="WrapperLabel" title='Combat Results Table'>
                    <span>CRT</span></h4>

                <div id="crt">
                    <div class="close">X</div>
                    <div id="mainTable">show normal table</div>
                    <div id="detTable">show determined table</div>
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
                    <?php if ($crt->combatResultsTableDetermined) { ?>

                        <div class="tableWrapper determined">
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
                            foreach ($crt->combatResultsTableDetermined as $row) {
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
                    <?php } ?>

                    <div id="crtDetailsButton">details</div>
                    <div id="crtOddsExp"></div>
                </div>
            </div>
            <?php include "timeTravel.php"; ?>
            <div id="statusWrapper">
                <div id="comlinkWrapper">
                    <div id="comlink"></div>
                </div>
                <div id="topStatus"></div>
                <div class="clear">
                    <span id="status"></span>
                    <span id="victory"></span>
                </div>
            </div>
            <div id="zoomWrapper">
                    <span id="zoom">
<!--                        <span class="minusZoom">-</span>-->
                        <span class="defaultZoom">1.0</span>
<!--                        <span class="plusZoom">+</span>-->
                    </span>
            </div>
            <div class="dropDown">
                <h4 class="WrapperLabel"><span class="tablet">?</span><span class="desktop">Rules</span></h4>

                <div class="subMenu">
                    <?php include_once "commonRules.php"; ?>
                    <?php include_once "exclusiveRules.php"; ?>
                    <?php include_once "obc.php"; ?>

                </div>
            </div>
            <?php include_once "tec.php"; ?>

            <div class="dropDown" id="unitsWrapper">
                <h4 class="WrapperLabel" title="Offmap Units">Units</h4>

                <div id="units" class="subMenu">
                    <ul>
                        <li id="closeAllUnits">Close All</li>
                        <li id="hideShow">Retired Units</li>
                        <li id="showDeploy">Deploy/Staging Box</li>
                        <li id="showExited">Exited Units</li>
                    </ul>
                </div>
            </div>

            <div id="nextPhaseWrapper">
                <button id="nextPhaseButton">Next Phase</button>
                <button id="fullScreenButton"><i class="fa fa-arrows-alt"></i></button>
                <button class="dynamicButton combatButton" id="clearCombatEvent">c</button>
                <button class="dynamicButton combatButton" id="shiftKey">+</button>

            </div>

            <div style="clear:both;"></div>

        </div>
    </header>
    <div id="content">
        <div id="rightCol">
            <div id="deployWrapper">
                <div style="margin-right:3px;" class="left">Deploy/Staging area</div>
                <div id="deployBox"></div>
                <div style="clear:both;"></div>
            </div>
            <div style="display:none;" id="deadpile">
                <div style="right:10px;font-size:50px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">
                    Retired Units
                </div>
            </div>
            <div style="display:none;" id="exitWrapper">
                <div style="margin-right:3px;" class="left">Exited Units</div>
                <div id="exitBox">
                </div>
                <div style="clear:both;"></div>
            </div>
            <div style="display:none;" id="undeadpile"></div>
            <div id="gameViewer">
                <div id="gameContainer">
                    <div id="gameImages">
                        <div id="svgWrapper">
                            <svg style="opacity:.6;position:absolute;" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <marker id='heead' orient="auto"
                                            markerWidth='2' markerHeight='4'
                                            refX='0.1' refY='2'>
                                        <!-- triangle pointing right (+x) -->
                                        <path d='M0,0 V4 L2,2 Z' />
                                    </marker>
                                    <marker
                                        inkscape:stockid="Arrow1Lend"
                                        orient="auto"
                                        refY="0.0"
                                        refX="0.0"
                                        id="head"
                                        style="overflow:visible;">
                                        <path
                                            id="path3762"
                                            d="M 0.0,0.0 L 5.0,-5.0 L -12.5,0.0 L 5.0,5.0 L 0.0,0.0 z "
                                            style="fill-rule:evenodd;stroke:#000000;stroke-width:1.0pt;"
                                            transform="scale(0.15) rotate(180) translate(12.5,0)" />
                                    </marker>
                                </defs>
                            </svg>
                        </div>
                        <img id="map" alt="map" src="<?php preg_match("/http/",$mapUrl) ?   $pre = '': $pre = base_url();echo "$pre$mapUrl";?>">
                        <?php $id = 0; ?>
                        {units}
                        <div class="unit {nationality}" id="{id}" alt="0">
                            <div class="shadow_mask"></div>
                            <div class="unitSize">{unitSize}</div>
                            <img class="arrow" src="<?php echo base_url(); ?>js/short-red-arrow-md.png" class="counter">
                            <div class="counterWrapper">
                                <img src="<?php echo base_url(); ?>js/{image}" class="counter"><span class="unit-desig">{unitDesig}</span>
                            </div>
                            <div class="unit-numbers">5 - 4</div>
                        </div>
                        {/units}
                        <div id="floatMessage">
                            <header></header>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>

            <audio class="pop" src="<?= base_url() . 'js/pop.m4a' ?>"></audio>
            <audio class="poop" src="<?= base_url() . 'js/lowpop.m4a' ?>"></audio>
            <audio class="buzz" src="<?= base_url() . 'js/buzz.m4a' ?>"></audio>

        </div>
    </div>
    <div id="display"></div>
</div>
</body></html>