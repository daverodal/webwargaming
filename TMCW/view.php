<body xmlns="http://www.w3.org/1999/html">
<header id="header">
    <div id="headerContent">
        <div id="leftHeader">
            <span style="font-size:1.0em">Welcome {user} {player} to <span style="font-family:'Nosifer';">The Martian Civil War</span><span
                    style="font-style: italic;">&ldquo;{wargame}&rdquo;</span></span>
            <div style="margin-top:0px;">
                <a href="<?=site_url("wargame/changeWargame/{wargame}/1");?>">As Rebel</a>
                <a href="<?=site_url("wargame/changeWargame/{wargame}/2");?>">As Loyalist</a>
                <a href="<?=site_url("wargame/leaveGame");?>">Go To Lobby</a>
                <a href="<?=site_url("wargame/logout");?>">logout</a>
                <a href="<?=site_url("wargame/unitInit/MartianCivilWar");?>">Restart Game</a>
                <a href="#" onclick="seeUnits();return false;">See Units</a>
                <a href="#" onclick="seeBoth();return false;">See Both</a>
                <a href="#" onclick="seeMap();return false;">See Map</a>
            </div>
        </div>
        <div id="rightHeader">
            <div id="mouseMove">mouse</div>
            <div id="comlinkWrapper" style="float:right;">
                <h4>Comlink</h4>

                <div id="comlink"></div>
            </div>
            <div>

                <fieldset id="phaseDiv">
                    <legend>Phase Mode
                    </legend>
                    <div id="clock"></div>
                </fieldset>
                <fieldset id="statusDiv">
                    <legend>Status
                    </legend>
                    <div id="status"></div>
                </fieldset>
                <fieldset id="victoryDiv">
                    <legend>Victory
                    </legend>
                    <div id="victory"></div>
                </fieldset>

            </div>
        </div>
        <div style="clear:left;"></div>

            <div id="clickCnt"></div>
        <button id="timeMachine">Time Travel</button>
        <button id="timeSurge">Time Surge</button>
        <button id="timeLive">Live</button>
        </div>
    <?php global $results_name;?>

    <span id="hideShow">Hide/Show</span>
    <button id="nextPhaseButton">Next Phase</button>
    <div id="crtWrapper">
        <h4><span class="goLeft">&laquo;</span>View Crt<span class="goRight">&raquo;</span></h4>
        <div id="crt">
            <h3>Combat Odds</h3>

            <div id="odds">
                <span class="col0">&nbsp;</span>
                <?php
                $crt = new CombatResultsTable();

                $i = 1;
                foreach($crt->combatResultsHeader as $odds){
                    ?>
                    <span class="col<?=$i++?>"><?=$odds?></span>
                    <?php } ?>
            </div>
            <?php
            $rowNum = 1;$odd = ($rowNum & 1) ? "odd" : "even";
            foreach ($crt->combatResultsTable as $row) {
                ?>
                <div class="roll <?="row$rowNum $odd"?>">
                    <span class="col0"><?=$rowNum++?></span>
                    <?php $col = 1;foreach ($row as $cell) { ?>
                    <span class="col<?=$col++?>"><?=$results_name[$cell]?></span>

                    <?php }?>
                </div>
                <?php }?>
            <div id="crtOddsExp"></div>
        </div>
    </div>
    <div id="OBCWrapper">
        <h4>Order of Battle</h4>
        <div id="OBC" style="display:none;">
            <fieldset>
                <legend>turn 1</legend>
                <div id="gameTurn1">
                    <div id="turnCounter">Game Turn</div>
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 2</legend>
                <div id="gameTurn2">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 3</legend>
                <div id="gameTurn3">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 4</legend>
                <div id="gameTurn4">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 5</legend>
                <div id="gameTurn5">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 6</legend>
                <div id="gameTurn6">
                </div>
            </fieldset>
            <fieldset>
                <legend>turn 7</legend>
                <div id="gameTurn7">
                </div>
            </fieldset>
            <div style="clear:both"></div>
        </div>



    </div>

    <div id="TECWrapper">
        <h4>Terrain Effects</h4>
        <DIV id="TEC" style="display:none;">
        <ul>
            <li>
                <div class="colOne blankHex">
                    <span>Clear</span>
                </div>
                <div class="colTwo">1 Movement Point</div>
                <div class="colThree">No Effect</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne forestHex">
                    <span>Forest</span>
                </div>
                <div class="colTwo">2 Movement Point</div>
                <div class="colThree">Shift one</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne mountainHex">
                    <span>Mountain</span>
                </div>
                <div class="colTwo">3 Movement Point</div>
                <div class="colThree">Shift two</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne riverHex">
                    <span>River Hexside</span>
                </div>
                <div class="colTwo">+1 Movement Point</div>
                <div class="colThree">Shift one if all attacks across river</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne roadHex">
                    <span>Road Hexside</span>
                </div>
                <div class="colTwo">1/2 Movement Point if across road hex side</div>
                <div class="colThree">No Effect</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne bridgeHex">
                    <span>Bridge Hexside</span>
                </div>
                <div class="colTwo">Ignore terrain</div>
                <div class="colThree">Shift one if all attacks across river/bridge</div>
                <div class="clear"></div>
            </li>
            <li>
                <div class="colOne trailHex">
                    <span>Trail Hexside</span>
                </div>
                <div class="colTwo">1 Movement Point if across tail hex side</div>
                <div class="colThree">No Effect</div>
                <div class="clear"></div>
            </li>
            <!--    Empty one for the bottom border -->
            <li class="closer"></li>
        </ul>
        </div>
    </div>
</header>
<div id="content">

    <div id="leftcol">

        <!-- <div id="chatDiv">
            <form onsubmit="doit();return false;" id="chatform" method="post">

                <input id="mychat" name="chats" type="text">
                <input name="submit" type="submit">
                <fieldset>
                    <legend>Chats
                    </legend>
                    <div id="chats"></div>
                </fieldset>
            </form>
        </div>-->

        </div>
    <div style="clear:both;"></div>
    <div id="rightCol">
        <div id="deployWrapper">
            <div style="margin-right:3px;" class="left">deploy on turn one</div>
            <div id="deployBox"></div>
            <div style="clear:both;"></div>
        </div>

        <div id="gameViewer" style="position:relative;">
            <div id="gameImages" class="ui-widget-content">
                <img id="map" alt="map" src="<?php echo base_url();?>js/MartianIV.png"
                     style="position: relative;visibility: visible;z-index: 0;">
                <?php $id = 0;?>
                {units}
                <div class="unit {class}" id="{id}" alt="0"><section></section>
                    <img class="arrow" src="<?php echo base_url();?>js/short-red-arrow-md.png" class="counter">
                    <img src="<?php echo base_url();?>js/{image}" class="counter">

                    <div>5 - 4</div>

                </div>
                {/units}
            </div>

            <!-- end gameImages -->
        </div>
        <audio class="pop"  src="<?=base_url().'js/pop.m4a'?>"></audio>
        <audio class="poop"  src="<?=base_url().'js/lowpop.m4a'?>"></audio>
        <audio class="buzz"  src="<?=base_url().'js/buzz.m4a'?>"></audio>

        <div style="clear:both;height:20px;"></div>

        <div style="position:relative;" id="deadpile">
            <div style="right:10px;font-size:50px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">
                Retired Units
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
</script>
<div id="display"></div>
</body></html>