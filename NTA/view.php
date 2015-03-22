<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?><link rel="stylesheet" href="<?= base_url("js/font-awesome-4.2.0/css/font-awesome.min.css"); ?>">
<body xmlns="http://www.w3.org/1999/html">
<div id="theDiv">
    <header id="header">
        <div id="headerContent">
            <div id="rightHeader">
                <div id="mouseMove">mouse</div>
                <div id="comlinkWrapper" style="float:right;">
                    <div id="comlink"></div>
                </div>
                <div class="dropDown alpha" id="menuWrapper">
                    <h4 class="WrapperLabel" title="Game Menu"><i class="fa fa-bars"></i></h4>

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
                    <h4 class="WrapperLabel" title="Game Information">i</h4>

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
                        <?php if($crt->combatResultsTableDetermined){?>

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
                        <?php }?>

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
                <?php include_once "help.php"; ?>


            </div>
            <div id="nextPhaseWrapper">
                <button id="nextPhaseButton">Next Phase</button>
                <button id="fullScreenButton"><i class="fa fa-arrows-alt"></i></button>
            </div>

            <div style="clear:both;"></div>

            <!--            <div id="clickCnt"></div>-->
            <!--        <button id="timeMachine">Time Travel</button>-->
            <!--        <button id="timeSurge">Time Surge</button>-->
            <!--        <button id="timeLive">Live</button>-->
            <!--        <span id="phaseClicks"></span>-->
        </div>
        <?php global $results_name; ?>
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
                    <div id="gameImages" >
                        <img id="map" alt="map" src="<?php echo base_url() . $mapUrl; ?>"
                             style="position: relative;visibility: visible;z-index: 0;">
                        <?php $id = 0; ?>
                        {units}
                        <div class="unit {class}" id="{id}" alt="0">
                            <div class="shadow-mask"></div>
                            <img class="arrow" src="<?php echo base_url(); ?>js/short-red-arrow-md.png" class="counter">
                            <div class="counterWrapper">
                                <img src="<?php echo base_url(); ?>js/{image}" class="counter">
                            </div>
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