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
?>
<script type="text/javascript">
    DR.playerOne = "<?=$playerOne?>";
    DR.playerTwo = "<?=$playerTwo?>";
    DR.playerThree = "<?=$playerThree?>";
    DR.playerFour = "<?=$playerFour?>";
    DR.players = ["observer", "<?=$playerOne?>","<?=$playerTwo?>","<?=$playerThree?>","<?=$playerFour?>"];
</script>
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
                    <h3>Combat Odds</h3>

                    <?php
                    $topCrt = new CombatResultsTable();
                    $crts = $topCrt->crts;
                    ?>
                    <div id="crt-buttons">
                        <?php
                        foreach($crts as $crtName => $crt){?>
                            <div class="switch-crt" id="<?=$crtName?>Table">show <?=$crtName?> table</div>
                        <?php
                        }?>
                    </div>
                    <?php
                    foreach($crts as $crtName => $crt){?>
                        <div class="tableWrapper <?=$crtName?>Table">
                            <h4 class="crt-table-name"><?=$crtName?> combat table.</h4>

                            <div id="odds">
                                <span class="col0">&nbsp;</span>
                                <?php
                                $i = 1;
                                foreach ($topCrt->combatResultsHeader as $odds) {
                                    ?>
                                    <span class="col<?= $i++ ?>"><?= $odds ?></span>
                                <?php } ?>
                            </div>
                            <?php
                            $rowNum = 1;
                            $odd = ($rowNum & 1) ? "odd" : "even";
                            foreach ($crt as $row) {
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
                        <div class="dropDown" id="closeAllUnits">Close All</div>
                        <div class="dropDown" id="hideShow">Retired Units</div>
                        <div class="dropDown" id="showExited">Exited Units</div>
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
                <div class="close">X</div>
                <div style="margin-right:3px;" class="left">Deploy/Staging area</div>
                <div id="deployBox">
                </div>
                <div style="clear:both;"></div>
            </div>
            <div style="display:none;" id="deadpile">
                <div class="close">X</div>
                <div style="right:10px;font-size:50px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">
                    Retired Units
                </div>
            </div>
            <div style="display:none;" id="exitWrapper">
                <div class="close">X</div>
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
                            <svg id="arrow-svg" style="opacity:.6;position:absolute;" xmlns="http://www.w3.org/2000/svg">
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
                        <div class="unit {nationality} {type}" id="{id}" alt="0">
                            <div class="shadow-mask"></div>
                            <img class="heading" src="<?php echo base_url(); ?>js/blackArrow.svg" class="counter">
                            <img class="arrow" src="<?php echo base_url(); ?>js/short-red-arrow-md.png" class="counter">
                            <div class="counterWrapper">
                                <div class="top-numbers"><span class="gunnery">{origStrength}</span><i class="spotted fa"></i> <span class="gun-range">{gunRange}</span></div>
                                <div class="ship-desig">{unitSize}</div>
                                <div class="bottom-numbers unit-numbers"><span class="defense">{defenseStrength}</span> <span class="mp"></span> <span class="torpedo">{torpedoStrength}</span></div>
                            </div>
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