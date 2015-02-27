<?php
/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 6/19/13
 * Time: 12:21 PM added this
 * To change this template use File | Settings | File Templates.
 */
?>
<style type="text/css">
    #gameRules {
        font-family: sans-serif;
    }

    #gameRules table, #gameRules th, #gameRules td {
        border: 1px solid black;
    }

    #gameRules h1 {
        color: #338833;
        font-size: 60px;

    }

    #GR #credits h2 {
        color: #338833;
    }

    #GR li {
        margin: 15px 0;
    }

    #GR h4 {
        margin-bottom: 5px;
    }

    #GR #credits h4 {
        margin-bottom: 0px;
    }

    #gameRules h4:hover {
        text-decoration: none;
    }

    .exclusive {
        color: green;
    }

    #GR {
        left: -200px;
    }

    #GR OL {
        counter-reset: item;
        padding-left: 10px;
    }

    #GR LI {
        display: block;
    }

    #GR LI:before {
        content: "[" counters(item, ".") "] ";
        counter-increment: item;
        font-size: 15px;
        font-weight: bold;
    }

    .big {
        font-size: 19px;
        font-weight: bold;

    }

    .lessBig {
        font-size: 18px;
        font-weight: bold;
    }

    #GR OL.topNumbers {
        counter-reset: item -1;
    }

</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Common Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div id="gameRules">
            <H1>
                <?= $name ?>
            </H1>

            <h2>Common Rules</h2>

            <p> These are the common rules for the Fredrick the Great Game System. These rules also will include the
                exclusive
                rules,
                for this particular game, &ldquo;<?= $name ?>&rdquo;. Exclusive rule also available for "at a glance"
                difference
                between this and other games.</p>

            <p>Exclusive rules contained in here will be in green</p>

            <h3> Quick Start Guide #3</h3>
            <header>
                The assumption is made that the reader is extensively familiar with Hex and counter games. Boiler Plate
                is omitted.
                E.G. I tell you what a ZOC does but not what it is. All number rounding is down for all calculations. No
                stacking.
                No Supply.
            </header>
            <ol class="topNumbers">
                <li><span class="big">Contents</span>
                    <ol>
                        <li><a href="#units">Units.</a></li>
                        <li><a href="#sop">Sequence of play.</a></li>
                        <li><a href="#deploy">Deploy.</a></li>
                        <li><a href="#stacking">Stacking.</a></li>
                        <li><a href="#movement">Moving.</a></li>
                        <li><a href="#combat">Combat.</a></li>
                        <li><a href="#victoryConditions">Victory.</a></li>
                    </ol>
                </li>

                <li><a name="units"></a><span class="big">Units</span>

                    <ol>
                        <li>
                            <?= "$playerOne" ?> infantry units look like this. You can tell by the symbol in the upper
                            left corner.
                            <div class="unit <?= $playerOne ?> infantry"
                                 style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                                 alt="0">
                                <nav class="counterWrapper">
                                    <div class="counter">
                                </nav>
                                <p class="range"></p>

                                <p class="forceMarch">M</p>
                                <section></section>


                                <div class="unit-numbers">5 - 4</div>

                            </div>
                            <p class='ruleComment'>The left number is the combat strength. Right right number is the
                                Movement
                                allowance.</p>
                        </li>
                        <li>
                            <?= $playerOne ?> cavalry units look like this.
                            <div class="unit <?= $playerOne ?> cavalry"
                                 style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                                 alt="0">
                                <nav class="counterWrapper">
                                    <div class="counter">
                                </nav>
                                <p class="range"></p>

                                <p class="forceMarch">M</p>
                                <section></section>


                                <div class="unit-numbers">4 - 5</div>

                            </div>
                            <p class='ruleComment'>Note they tend to move faster than infantry units, and are sometimes
                                weaker than
                                infantry
                                units.</p>
                        </li>
                        <li>
                            <?= $playerOne ?> artillery units look like this. Note the number in the upper right corner.
                            That's the
                            units
                            range.
                            <div class="unit <?= $playerOne ?> artillery"
                                 style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                                 alt="0">
                                <nav class="counterWrapper">
                                    <div class="counter">
                                </nav>
                                <p class="range">3</p>

                                <p class="forceMarch">M</p>
                                <section></section>


                                <div class="unit-numbers">3 - 3</div>

                            </div>
                            <p class='ruleComment'>Artillery units can fire at non adjacent units.</p>
                        </li>
                        <li>
                            <?= $playerTwo ?> infantry, cavalry and artillery look similar.
                            <div>
                                <div class="unit <?= $playerTwo ?> infantry"
                                     style="float:left;margin-left:10px; border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                                     alt="0">
                                    <nav class="counterWrapper">
                                        <div class="counter">
                                    </nav>
                                    <p class="range"></p>

                                    <p class="forceMarch">M</p>
                                    <section></section>


                                    <div class="unit-numbers">5 - 4</div>

                                </div>
                                <div class="unit <?= $playerTwo ?> cavalry"
                                     style="float:left;margin-left:10px; border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                                     alt="0">
                                    <nav class="counterWrapper">
                                        <div class="counter">
                                    </nav>
                                    <p class="range"></p>

                                    <p class="forceMarch">M</p>
                                    <section></section>


                                    <div class="unit-numbers">5 - 5</div>

                                </div>
                                <div class="unit <?= $playerTwo ?> artillery"
                                     style="float:left;margin-left:10px; border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                                     alt="0">
                                    <nav class="counterWrapper">
                                        <div class="counter">
                                    </nav>
                                    <p class="range">3</p>

                                    <p class="forceMarch">M</p>
                                    <section></section>


                                    <div class="unit-numbers">3 - 3</div>

                                </div>
                            </div>
                            <div style="clear:both"></div>
                        </li>
                    </ol>
                </li>
                <li><a name="sop"></a><span class="big">Sequence of play.</span>


                    <p>Each game turn is composed of two player turns. The <?= $playerOne ?> is the first player, and
                        the <?= $playerTwo ?> is the second.
                    </p>

                    <p>Each player turn is divided into a movement phase and a combat phase.</p>

                    <p> Before the first game turn, each player deploys their unis during the deploy phase.</p>
                    <ol>
                        <li><span class="lessBig">Deploy Phase</span>

                            <p>On the first turn of the game both sides must deploy their units.</p>

                            <p class="ruleComment">The <?= $deployOne ?> player deploys first, then
                                the <?= $deployTwo ?> player.</p>

                        </li>
                        <li><span class="lessBig"><?= $playerOne ?> Player Turn</span>
                            <ol>
                                <li><span class="lessBig">Movement Phase</span>

                                    <p class="ruleComment">The <?= $playerOne ?> may move as many or as few units as
                                        they desire.
                                        If they have reinforcements available
                                        they may enter the map now.</p>


                                </li>

                                <li><span class="lessBig"><?= $playerOne ?> Player Combat Phase</span>

                                    <p class="ruleComment">The <?= $playerOne ?> may initiate as many or as few attacks
                                        as they wish.
                                        Combat is broken up into two
                                        phases, Combat setup and Combat Resolution.</p>


                                </li>
                            </ol>
                        </li>
                        <li><span class="lessBig"><?= $playerTwo ?> Player Turn</span>
                            <ol>
                                <li><span class="lessBig">Movement Phase</span>

                                    <p class="ruleComment">The <?= $playerTwo ?> may move as many or as few units as
                                        they desire.
                                        If they have reinforcements available
                                        they may enter the map now.</p>


                                </li>

                                <li><span class="lessBig"><?= $playerTwo ?> Player Combat Phase</span>

                                    <p class="ruleComment">The <?= $playerTwo ?> may initiate as many or as few attacks
                                        as they wish.
                                        Combat is broken up into two
                                        phases, Combat setup and Combat Resolution.</p>


                                </li>
                            </ol>
                        </li>
                        <li><span class="lessBig">Turn End</span>

                            <p>
                                Game turn is incremented. If the game has ended. Victory should be announced.
                            </p></li>
                    </ol>

                </li>
                <li><a name="deploy"></a><span class="big">Deploy Units</span>

                    <p class="ruleComment">The <?= $deployOne ?> player deploys first, then the <?= $deployTwo ?>
                        player.</p>

                    <ol>
                        <li>The units you need to deploy will appear in the top bar.</li>
                        <li>When you click on a unit a ghost of it will appear on the map. Click on one of the ghosts
                            and the
                            unit
                            will be placed on the map.
                        </li>
                        <li> If you misplace a unit on the
                            map
                            you can click on it again, and click on the correct spot to place it.
                        </li>
                        <li>You are not required to
                            deploy all of your units you can decline to place some of the units to balance the game.
                        </li>
                        <li>
                            You may not deploy any units after the initial phase.
                        </li>
                        <li>
                            You may view/hide the deploy box by hitting the button 'deploy/staging'
                        </li>
                    </ol>
                </li>
                <li><a name="stacking"></a><span class="big">Stacking</span>


                    <p>No more than one unit may occupy the same hex at any given, however friendly units may move
                        through each
                        other.</p>
                </li>
                <li><a name="movement"></a><span class="big">Movement</span>


                    <p class="ruleComment">The <?= $playerOne ?> player moves first, the <?= $playerTwo ?> moves
                        second.</p>

                    <p>The Second Number on the counter is Movement Points <abbr title="Movement Points">(MP)</abbr>.
                    </p>

                    <p>Units expend different amounts of <abbr title="Movement Points">MP</abbr> for different terrains
                    </p>
                    <ol>
                        <li>Units pay different amounts of Movement Points or <abbr title="Movement Points">MP</abbr> to
                            enter different
                            hexes.
                            different units will pay different amounts of MPs to enter the same hex. Please see the
                            Terrain Effects
                            Chart or
                            <abbr title="Terrain Effects Chart">TEC</abbr> for the effects of terrain on movement.
                        </li>

                        <li>Creek, All units expend additional movement points to cross a Creek hex side, except when
                            using road
                            movement
                            and
                            crossing on a bridge.
                        </li>

                        <li>Road Movement. Units pay only &frac12; movement point when moving along contiguous road
                            hexes regardless of
                            other terrain in the hex. In order to use road movement, a unit must be in force march mode.
                            <p><em>Forced March Mode: before a units moves, but while selected, hit the 'm' key, an "m"
                                    will appear
                                    on the unit. This means the units is in "forced march" mode and can take advantage
                                    of the roads.
                                    Pressing the "m" key again before moving the unit
                                    will take them out of "force march" mode. Once a unit has moved one hex they may not
                                    change their
                                    "forced march" status.</em></p>

                            <p>Units are not obliged to use the road movement bonus however if they do they may not
                                attack in that
                                turn.</p>

                        </li>

                        <li>Zones of Control. When a unit enters a Hostile <abbr title="Zone Of Control">ZOC</abbr> it
                            must stop and
                            move no
                            further that turn. When a unit exits a hostile zone of control it must stop and move no
                            further that turn. A
                            unit may never move directly from one hostile ZOC to another hostile ZOC.
                        </li>
                        <li>Regardless of movement points required, a unit may always move at least one hex per turn,
                            provided they are not moving directly from one zoc to another.
                        </li>
                        <li>Click on one of your units. Ghosts will appear where it can legally move.</li>
                        <li>Click on a ghost to move the unit to that location.</li>
                        <li>If it has remaining movement that will be indicated by Ghosts.</li>
                        <li>If you are satisfied with your move click on it again and the ghosts will disappear.</li>
                        <li>Road Movement:

                            <p class="ruleComment"> Your units may move faster along roads, but you must put them into
                                road
                                Move mode to get the
                                benefit.
                            </p>
                            <ol>

                                <li class="indent">Click on a unit but do not move it. Now press
                                    'M'. You will now see that your unit can move further along roads.
                                </li>
                                <li class="indent">This is also the Only way to use bridges.
                                    Units that road move may not attack in the following combat phase.
                                </li>
                                <li>Once a unit has been moved even one hex, it cannot change into or out of road
                                    movement
                                    mode.
                                </li>
                            </ol>
                        </li>
                    </ol>
                </li>
                <li><a name="combat"></a><span class="lessBig">Combat (Attacks)</span>


                    <ol>
                        <li><span class="lessBig">Combat Rules</span>

                            <p class="ruleComment">The first number on a unit is it's combat factor.</p>
                            <ol>
                                <li>A single unit may only participate in single attack in the friendly attack phase.
                                </li>
                                <li>Units may attack more than one unit. See multi unit combat below.</li>
                                <li>Combat odds are determined by adding the attack strength of all attacking units and
                                    dividing it by the
                                    defense strength of all defending units.
                                </li>
                                <li>Attacks may always be made at lower than odds than those gained by calculation.</li>
                                <li>All attacks are voluntary. Except that all hostile units adjacent to an attacking
                                    unit must them selves be
                                    attacked even if only by artillery bombardment.
                                </li>
                                <li>All combat is between adjacent units except that artillery may attack units within
                                    their range, (Bombardment).
                                </li>
                                <li><span class="lessBig">Bombardment</span>

                                    <p>The number in the upper right corner of an artillery unit is it's range.</p>

                                    <p class="ruleComment">Bombardment occurs between artillery units and non adjacent
                                        enemy units. Artillery units that attack adjacent enemy units
                                        are not considered bombarding.</p>
                                    <ol>
                                        <li>Bombardment occurs when an artillery unit attacks an enemy unit that is non
                                            adjacent.
                                        </li>
                                        <li>Bombarding units do not suffer the ill effects of combat and ignore all
                                            exchanges, attacker retreats or attacker eliminated results.
                                        </li>
                                        <li>Bombarding units may add their strength to other adjacent attacking units.
                                            If there are ill effects of the attack (EX, AR or AE) only the adjacent
                                            units
                                            are affected.
                                        </li>
                                        <li>Bombarding units may NOT attack more than one unit unless they are attacking
                                            with other adjacent, non bombarding units. See multi unit combat below.
                                        </li>
                                        <li>Artillery units that are adjacent to an enemy unit may NOT bombard a distant
                                            enemy unit. If they attack at all, they must attack one of the adjacent
                                            units.
                                        </li>
                                        <li>Artillery units that are adjacent to enemy units DO suffer ill effects of
                                            combat, just as normal units do.
                                        </li>
                                        <li>Bombarding units may not shoot over woods, hills or towns. They may attack
                                            into and out of these hexes but not through them.
                                        </li>

                                    </ol>
                                </li>
                            </ol>
                        </li>
                        <li>
                            <span class="lessBig">Combat Setup Phase</span>
                            <ol>
                                <li>
                                    When you start your attack phase all of your units that are eligible to attack
                                    will be highlighted. Darker units are not eligible to attack.
                                </li>
                                <li>
                                    Click on a hostile unit you want to attack. The defending unit will have a yellow
                                    border.
                                </li>
                                <li>
                                    Then click on one of your adjacent units or an artillery unit within range.

                                    <p>If the attack was valid a red arrow will appear. At the same time the combat
                                        results
                                        table
                                        will appear showing you the possible results.</p>

                                    <p>You may click on the word 'details' to get more info about the attack. </p>
                                </li>
                                <li>
                                    Clicking on additional eligible attacker will cause the CRT to change and additional
                                    red
                                    arrows
                                    to appear.
                                </li>
                                <li>
                                    If you click on a unit that's already allocated to the current attack, clicking
                                    again will
                                    remove
                                    it from this attack.
                                </li>
                                <li>
                                    If you click on a different defender. That unit will get a yellow border, if there
                                    were any
                                    units allocated to attack the previous defender, it's border will be orange. If not,
                                    it will
                                    have
                                    a grey border.
                                </li>
                                <li>
                                    If you have multiple defenders being attacked. Clicking on an attacker that is
                                    allocated to
                                    another attack will re-allocate it the attach the current defender.
                                </li>
                                <li>If you wish to lower the odd of an attack, select the combat, ensure the defender
                                    has yellow borders and click on the odds
                                    in the crt you wish to lower the odds to. A purple column on the lowered odds should
                                    appear. Clicking again on the odds will remove the
                                    lowered odds. Example if the odds were 2:1, you can click on 1:1 a purple column
                                    will appear over 1:1. During combat resolution the lowered odds will be used
                                    to resolve combat.
                                    <p class="ruleComment">People often lower the odds to avoid exchanges, often when a
                                        high valued unit is attack a lower valued unit.</p></li>
                                <li>
                                    Once you have setup all your attacks. Click 'Next Phase' to move to combat
                                    resolution phase.
                                </li>
                                <li>
                                    See Combat below for more details.
                                </li>
                            </ol>
                        </li>
                        <li>
                            <span class="lessBig">Combat Resolution Phase</span>
                            <ol>
                                <li>
                                    Click on a hostile unit you targeted in attack planning. The combat result
                                    will appear, both in the CRT and in a popup box with info. Drag the box if you don't
                                    like
                                    the
                                    placement.
                                </li>
                                <li>
                                    If the defenders border is purple. The unit must be retreated.
                                    Follow the instructions in the popup box.
                                </li>
                                <li>
                                    If the attackers need to retreat, they will have purple borders.
                                    Follow the instructions in the popup box.
                                </li>
                                <li>
                                    If there was an exchange, the attackers will have red borders, you must click on the
                                    unit
                                    you intend to sacrifice.
                                    Follow the instructions in the popup box.
                                </li>
                                <li>
                                    Finally if any attackers have black borders, they are eligible for advance after
                                    combat.
                                    Click on any of the black bordered units and then click on their original position
                                    or the
                                    vacated defenders hex.
                                </li>
                                <li>
                                    When all combats have been resolved. Click on Next Phase.
                                </li>
                            </ol>
                        </li>

                        <li><span class="lessBig">Multi Hex Multi Unit Combat</span>

                            <p>A single unit may attack any or all hostile units that it is adjacent to so long as the
                                odds are not worse than
                                1-4.
                                All attacks against a group of contiguous defenders may be grouped together and resolved
                                as a single attack so
                                long
                                as all attackers are within range of all defenders. The terrain applied to the combat
                                situation is the most
                                favorable to the defender.</p>

                            <p>In order to attack more than one unit, click on a defender (it should get a yellow
                                border),
                                then click on the second defender you with to attack while holding down the shift key
                                (you should see two units
                                with
                                a yellow border).
                                you may click on eligible attackers and arrows should appear point at both defenders.
                            </p>

                            <p>
                        </li>
                        <li>
                            <span class="lessBig">Retreats</span> Whenever obligated by combat result, the attacking
                            player
                            retreats the units (attacking or defending) obeying the following requirements. Units may
                            not retreat off board, or
                            into
                            enemy zones of control,
                            If a unit cannot find an empty it may retreat over friendly hexes until it finds an empty
                            hex. Units that cannot
                            retreat are eliminated.
                            </p>
                        </li>
                        <li>
                            <span class="lessBig">Retreat Before Combat</span> To simmulate retreat before combat, when
                            only non cavalry units are attacking only
                            cavalry units
                            the cavalry can do a "retreat before combat" this is reflected in the 'cavalry combat
                            results table', which has all
                            DR's
                            where EX's or DE's would be.
                            You may click on the crt where it says "see cavalry table" or "see normal table" to toggle
                            between them.
                        </li>
                        <li>
                            <span class="lessBig">Advance after combat</span> If a defending hex is left vacant any
                            adjacent attacker that participated in the
                            attack my
                            be moved into that hex. This must be done before the next attack is resolved. Artillery
                            units may NOT advance after
                            combat.
                        </li>
                        <li>
                            <span class="lessBig">Combat Results</span>

                            <h4>Combat Results Table (CRT) (No Attack at less than 1-4 is allowed)</h4>

                            <table>
                                <tr>
                                    <td>Die
                                    <td>1-4<br>1-3</td>

                                    <td>1-2</td>

                                    <td>1-1</td>

                                    <td>1.5-1</td>

                                    <td>2-1</td>

                                    <td>3-1</td>

                                    <td>4-1</td>

                                    <td>5-1</td>

                                    <td>6-1 or more</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>A-E</td>
                                    <td>A-E</td>
                                    <td>A-E</td>
                                    <td>A-R</td>
                                    <td>A-R</td>
                                    <td>D-R</td>
                                    <td>D-R</td>
                                    <td>D-R</td>
                                    <td>D-R</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>A-E</td>
                                    <td>A-E</td>
                                    <td>A-R</td>
                                    <td>A-R</td>
                                    <td>A-R</td>
                                    <td>D-R</td>
                                    <td>D-R</td>
                                    <td>D-R</td>
                                    <td>D-R</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>A-E</td>
                                    <td>A-E</td>
                                    <td>A-R</td>
                                    <td>A-R</td>
                                    <td>D-R</td>
                                    <td>EX</td>
                                    <td>D-R</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>A-E</td>
                                    <td>A-R</td>
                                    <td>A-R</td>
                                    <td>D-R</td>
                                    <td>D-R</td>
                                    <td>EX</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>A-E</td>
                                    <td>A-R</td>
                                    <td>A-R</td>
                                    <td>D-R</td>
                                    <td>EX</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>A-R</td>
                                    <td>A-R</td>
                                    <td>D-R</td>
                                    <td>EX</td>
                                    <td>EX</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                    <td>D-E</td>
                                </tr>
                            </table>
                        </li>


                        <li>
                            <span class="lessBig">Terrain Effects on Combat</span>
                            <ol>
                                <?php if ($scenario->jagersdorfCombat) { ?>
                                    <?php if ($name == "Jagersdorf") { ?>
                                        <li class="exclusive"><?= $playerTwo ?> Infantry units are +1 to their combat
                                            factor when
                                            Attacking
                                            into or Defending in woods or
                                            towns, unless they are attacking across a creek or bridge.
                                        </li>
                                    <?php } ?>
                                    <li class="exclusive"><?= $playerOne ?> Infantry units are +1 to their combat factor
                                        when Attacking
                                        into
                                        or Defending
                                        in clear, unless they are attacking across a creek or bridge.
                                    </li>
                                <?php } ?>

                                <li>All Cavalry units combat factors are divided by 2 when attacking into hexes or
                                    across hex sides
                                    other
                                    than clear.
                                </li>

                                <li>All Infantry and artillery have their combat factors doubled when defending in a
                                    town
                                </li>

                                <li>All Units have their combat factors doubled when defending on a hill</li>

                                <li>All Units except artillery have their combat factors divided by 2 when attacking
                                    across creek or
                                    bridge
                                    hex sides.
                                </li>
                                <li>See the Terrain Effects Chart, the TEC button, for more info.</li>
                                <?php @include "combatTerrainEffects.php"; ?>
                            </ol>
                        </li>
                        <li><span class="lessBig">Combined Arms Bonus</span>
                            <ol>
                                <li>Any attack starting at 1-1 odds or better against clear terrain hex, that includes
                                    attacking units
                                    from
                                    two different branches of service is receives 1 favorable column shift.
                                </li>
                                <li>Any attack against a clear terrain hex that includes attacking units from all three
                                    different
                                    branches
                                    of service receives 2 favorable column shifts.
                                </li>
                                <li>Any attack against a non clear terrain hex that includes both Infantry and artillery
                                    enjoys a 1
                                    column
                                    favorable odds shift.
                                </li>
                            </ol>
                        </li>

                        <li><span class="lessBig">Combat Result Explanation</span>
                            <ol>
                                <li>A-E all attacking units eliminated</li>

                                <li>A-R All attacking units must retreat 1 hex (See Retreats page 2)</li>

                                <li>D-R All defending units must retreat 1 hex (See Retreats page 2)</li>

                                <li>EX all defending units are eliminated. Attacking units of the attackers choice = to
                                    or greater than
                                    eliminated defenders by unmodified combat strength are also eliminated.
                                </li>

                                <li>DE all defending units are eliminated,</li>
                            </ol>
                        </li>
                    </ol>
                </li>

                <a name="victoryConditions"></a>

                <div class="exclusive">
                    <?php include "victoryConditions.php" ?>
                </div>
            </ol>
            <div id="credits">
                <h2><cite><?= $name ?></cite></h2>
                <h4>Design Credits</h4>

                <h4>Game Design:</h4>
                Lance Runolfsson
                <h4>Graphics and Rules:</h4>
                <site>Lance Runolfsson</site>
                <h4>HTML 5 Version:</h4>
                David M. Rodal
            </div>
        </div>
    </div>
</div>