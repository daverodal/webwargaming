<?php
/**
 *
 * Copyright 2012-2015 David Rodal
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
    DR.players = ["observer", "<?=$playerOne?>","<?=$playerTwo?>"];
</script>
<link rel="stylesheet" href="<?= base_url("js/font-awesome-4.2.0/css/font-awesome.min.css"); ?>">
<style type="text/css">

</style>

<body xmlns="http://www.w3.org/1999/html">
<div id="theDiv">
    <header id="header">
        <div id="headerContent">
            <div id="rightHeader">
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

                        <div class="tableWrapper main">
                            <div id="odds">
                                <span class="col0">&nbsp;</span>
                                <?php
                                $crt = new \Troops\CombatResultsTable();

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
                        <span class="defaultZoom">1.0</span>
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
                        <div class="dropDown" id="showDeploy">Deploy/Staging Box</div>
                        <div class="dropDown" id="showExited">Exited Units</div>
                    </div>
                </div>
                <div class="dropDown" id="rangeWrapper">
                    <h4 class="WrapperLabel" title="toggle range display">Range</h4>
                    <div id="range-toggle" class="subMenu">
                        <div class="dropDown" id="all-on">All On</div>
                        <div class="dropDown" id="all-off">All Off</div>
                    </div>
                </div>
                <?php include_once "commonRules.php"; ?>
                <?php include_once "exclusiveRules.php"; ?>
                <?php include_once "obc.php"; ?>

                <div id="nextPhaseWrapper">
                    <button id="nextPhaseButton">Next Phase</button>
                    <button id="fullScreenButton"><i class="fa fa-arrows-alt"></i></button>
                    <button class="dynamicButton combatButton" id="clearCombatEvent">c</button>
                    <button class="dynamicButton combatButton" id="shiftKey">+</button>
                </div>

            </div>
            <div style="clear:both;"></div>

        </div>
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
                <div id="gameImages">
                    <div id="svgWrapper">
                        <svg id="arrow-svg" style="position:absolute;" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <marker id='heead' orient="auto"
                                        markerWidth='2' markerHeight='4'
                                        refX='0.1' refY='2'>
                                    <!-- triangle pointing right (+x) -->
                                    <path d='M0,0 V4 L2,2 Z'/>
                                </marker>

                                <marker
                                    inkscape:stockid="Scissors"
                                    orient="auto"
                                    refY="0.0"
                                    refX="0.0"
                                    id="Scissors"
                                    style="overflow:visible">
                                    <path
                                        id="schere"
                                        d="M 9.0898857,-3.6061018 C 8.1198849,-4.7769976 6.3697607,-4.7358294 5.0623558,-4.2327734 L -3.1500488,-1.1548705 C -5.5383421,-2.4615840 -7.8983361,-2.0874077 -7.8983361,-2.7236578 C -7.8983361,-3.2209742 -7.4416699,-3.1119800 -7.5100293,-4.4068519 C -7.5756648,-5.6501286 -8.8736064,-6.5699315 -10.100428,-6.4884954 C -11.327699,-6.4958500 -12.599867,-5.5553341 -12.610769,-4.2584343 C -12.702194,-2.9520479 -11.603560,-1.7387447 -10.304005,-1.6532027 C -8.7816644,-1.4265411 -6.0857470,-2.3487593 -4.8210600,-0.082342643 C -5.7633447,1.6559151 -7.4350844,1.6607341 -8.9465707,1.5737277 C -10.201445,1.5014928 -11.708664,1.8611256 -12.307219,3.0945882 C -12.885586,4.2766744 -12.318421,5.9591904 -10.990470,6.3210002 C -9.6502788,6.8128279 -7.8098011,6.1912892 -7.4910978,4.6502760 C -7.2454393,3.4624530 -8.0864637,2.9043186 -7.7636052,2.4731223 C -7.5199917,2.1477623 -5.9728246,2.3362771 -3.2164999,1.0982979 L 5.6763468,4.2330688 C 6.8000164,4.5467672 8.1730685,4.5362646 9.1684433,3.4313614 L -0.051640930,-0.053722219 L 9.0898857,-3.6061018 z M -9.2179159,-5.5066058 C -7.9233569,-4.7838060 -8.0290767,-2.8230356 -9.3743431,-2.4433169 C -10.590861,-2.0196559 -12.145370,-3.2022863 -11.757521,-4.5207817 C -11.530373,-5.6026336 -10.104134,-6.0014137 -9.2179159,-5.5066058 z M -9.1616516,2.5107591 C -7.8108215,3.0096239 -8.0402087,5.2951947 -9.4138723,5.6023681 C -10.324932,5.9187072 -11.627422,5.4635705 -11.719569,4.3902287 C -11.897178,3.0851737 -10.363484,1.9060805 -9.1616516,2.5107591 z "
                                        style="fill:#000000;"/>
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
                                        transform="scale(0.15) rotate(180) translate(12.5,0)"/>
                                </marker>
                            </defs>
</svg>
                    </div>
                    <img id="map" alt="map" src="<?php preg_match("/http/", $mapUrl) ? $pre = '' : $pre = base_url();
                    echo "$pre$mapUrl"; ?>"
                         style="position: relative;visibility: visible;z-index: 0;">
                    <?php $id = 0;?>
                    {units}
                    <div class="unit {class} {type} topDiv smallUnit" id="{id}">
                        <div class="shadow-mask"></div>
                        <img class="arrow" src="<?php echo base_url(); ?>js/short-red-arrow-md.png" class="counter">


                        <div class="counterWrapper">
                            <div class="unitNumbers attack">
                                6
                            </div>
                            <div class="unitNumbers rangewe">
                                {range}
                            </div>
                            <div class="type-wrapper artillery-svg">
                                <svg width="15" height="21" viewBox="0 0 10 20">
                                    <line x1="5" x2="5" y1="0" y2="20" stroke-width="2"></line>
                                    <line x1="0" x2="10" y1="10" y2="10"></line>
                                    <line x1="1" x2="1" y1="4" y2="16"></line>
                                    <line x1="9" x2="9" y1="4" y2="16"></line>
                                </svg>
                            </div>
                            <div class="type-wrapper howitzer-svg">
                                <svg width="15" height="21" viewBox="0 0 10 20">
                                    <line x1="5" x2="5" y1="0" y2="14" stroke-width="2"></line>
                                    <line x1="0" x2="10" y1="9" y2="9"></line>
                                    <line x1="1" x2="1" y1="4" y2="14"></line>
                                    <line x1="9" x2="9" y1="4" y2="14"></line>
                                    <circle r="2" cx="5" cy="17" fill="transparent"></circle>
                                </svg>
                            </div>
                            <div class="type-wrapper armor-svg">
                                <!-- <img src="<?php //echo base_url(); ?>js/A7vu_tank.svg" class="armor"> -->
                            </div>
                            <div class="type-wrapper aarmor-svg">
                                <!-- By Marseille77 (the-blueprints.com) [Public domain], <a href="https://commons.wikimedia.org/wiki/File%3APanzer_IV_G.svg">via Wikimedia Commons</a> -->
                                <!-- By Marseille77 (the-blueprints.com; Own work by Marseille77) [Public domain], <a href="https://commons.wikimedia.org/wiki/File%3APanzer_V_panther.svg">via Wikimedia Commons</a> -->
                                <!-- By Marseille77 (Own work, Nach Vorlage [1]erstellt) [Public domain], <a href="https://commons.wikimedia.org/wiki/File%3AA7v_tank_german.svg">via Wikimedia Commons</a> -->
                                <!-- By Marseille77 (Own work, drawn with inkscape) [Public domain], <a href="https://commons.wikimedia.org/wiki/File%3AA7vu_tank.svg">via Wikimedia Commons</a> -->

                            </div>
<!--                            <div class="type-wrapper mg-svg">-->
<!--                                <img src="--><?php //echo base_url(); ?><!--js/mg.svg" class="col-hex">-->
<!--                            </div>-->
                            <div class="type-wrapper mg-svg">
                                <svg width="10" height="20" viewBox="0 0 10 20">
                                    <line x1="5" x2="5" y1="0" y2="20" stroke-width="2"></line>
                                    <line x1="5" x2="0" y1="1" y2="5"></line>
                                    <line x1="5" x2="10" y1="1" y2="5"></line>
                                    <line x1="0" x2="10" y1="10" y2="10"></line>
                                    <line x1="0" x2="10" y1="13" y2="13"></line>
                                </svg>
                            </div>
                            <div class="type-wrapper infantry-svg">
                                <svg width="18" height="18" viewBox="0 0 20 20">
                                    <line x1="1" x2="1" y1="0" y2="20" stroke-width="2"></line>
                                    <line x1="0" x2="20" y1="19" y2="19" stroke-width="2"></line>
                                    <line x1="19" x2="19" y1="0" y2="20" stroke-width="2"></line>
                                    <line x1="0" x2="20" y1="1" y2="1" stroke-width="2"></line>
                                    <line x1="1" x2="19" y1="1" y2="19" stroke-width="2"></line>
                                    <line x1="1" x2="19" y1="19" y2="1" stroke-width="2"></line>
                                </svg>
                            </div>
                            <div class="type-wrapper cavalry-svg">
                                <svg width="18" height="18" viewBox="0 0 20 20">
                                    <line x1="1" x2="1" y1="0" y2="20" stroke-width="2"></line>
                                    <line x1="0" x2="20" y1="19" y2="19" stroke-width="2"></line>
                                    <line x1="19" x2="19" y1="0" y2="20" stroke-width="2"></line>
                                    <line x1="0" x2="20" y1="1" y2="1" stroke-width="2"></line>
                                    <line x1="1" x2="19" y1="19" y2="1" stroke-width="2"></line>
                                </svg>
                            </div>
                            <div class="unitNumbers movement">
                                3
                            </div>
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

        <div style="clear:both;height:20px;"></div>
    </div>
</div>
<script type="text/javascript">
</script>
<script>
    //    var $panzoom = $('#gameImages').panzoom({cursor: "normal"});
    //    $panzoom.parent().on('mousewheel DOMMouseScroll MozMousePixelScroll', function (e) {
    //        e.preventDefault();
    //        var delta = e.delta || e.originalEvent.wheelDelta;
    //
    //        var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;
    //        var zoomLevel = $("#zoom .defaultZoom").html() - 0;
    //        if (zoomLevel >= 1.0) {
    //            precision = 2;
    //        }
    //
    //        if(zoomOut){
    //            zoomLevel  -= .1
    //            $("#zoom .defaultZoom").html(zoomLevel.toPrecision(precision));
    //        }else{
    //            zoomLevel += .1
    //            $("#zoom .defaultZoom").html(zoomLevel.toPrecision(precision));
    //        }
    //        $panzoom.panzoom('zoom', zoomOut, {
    //            increment: 0.1,
    //            animate: false,
    //            focal: e
    //        });
    //        DR.$panzoom = $panzoom;
    //    });
</script>
<div id="display"></div>
</div>
</body></html>