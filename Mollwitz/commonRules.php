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
</style>
<div class="dropDown" id="GRWrapper">
<h4 class="WrapperLabel" title="Game Rules">Common Rules</h4>

<div id="GR" style="display:none">
<div class="close">X</div>
<div id="gameRules">
<H1>
    <?php $playerOne = $force_name[1];
    $playerTwo = $force_name[2];?>
    <?= $name ?>
</H1>

<h2>Common Rules</h2>

<p> These are the common rules for the Fredrick the Great Game System. These rules also will include the exclusive
    rules,
    for this particular game, &ldquo;<?= $name ?>&rdquo;. Exclusive rule also available for "at a glance" difference
    between this and other games.</p>

<p>Exclusive rules contained in here will be in green</p>

<h3> Quick Start Guide #3</h3>
<header>
    The assumption is made that the reader is extensively familiar with Hex and counter games. Boiler Plate is omitted.
    E.G. I tell you what a ZOC does but not what it is. All number rounding is down for all calculations. No stacking.
    No Supply.
</header>
<h2>Turn Order</h2>

<p>The game is divided in to phases as you complete each phase click 'Next Phase' on the top bar to proceed to the next
    phase. </p>
<ol>
    <li><h3>On the first turn of the game you will be instructed to deploy units.</h3>
    <h4><?=$playerTwo?> deploys first, then <?=$playerOne?></h4>
        <p>The units you need to deploy will appear in the top bar. When you click on a unit a ghost of it will appear
            on the map. Click on one of the ghosts and the unit will be placed on the map. (you are not required to
            deploy all of your units you can decline to place some of the units to balance the game.</p></li>
    <li><h3><?= $playerOne ?> Movement Phase</h3>

        <p>
            Click on one of your units. Ghosts will appear where it can legally move. Click on a ghost to move the unit
            to that location. If it has remaining movement that will be indicated by Ghosts. If you are satisfied with
            your move click on it again and the ghosts will disappear.
            Road Movement: Your units may move faster along roads but you must put them into road Move mode to get the
            benefit. Click on a unit before you move it. Now press
            <M> you will now see that your unit can move further along roads. This is also the Only way to use bridges.
                Units that road move may not attack in the following combat phase.
        </p>
    </li>

    <li><h3><?= $playerOne ?> Attack Phase</h3>

        <p>
            A] Attack planning Phase: When you start your attack phase all of your units that are eligible to attack
            will be highlighted. Click on a hostile unit you want to attack then click on one of your adjacent
            highlighted units. A red arrow will appear indicating that your unit is now committed to an attack. At the
            same time the combat results table will appear showing you the possible results. You can add more units to
            the attack by clicking on them. You can also have your unit attack more than one hostile unit by pressing
            <Shift> and clicking on your unit again then clicking on the new target. You may not make attacks at less
                than 1-4 odds.
                Artillery: Designate artillery attacks the same way but you may attack a hostile unit up to 3 hexes away
                (or 2 if reduced visibility) if your artillery is not adjacent to a hostile unit. And you have a clear
                line of sight.

                B] Combat Resolution Phase: Click on a hostile unit you targeted in attack planning. The combat result
                will appear. If the hostile unit needs to be retreated click on it and move it one space. See Retreats
                bellow. If your unit is allowed to advance click on it and move it in to the hex vacated by the hostile
                unit, or if you do not want to advance click on your unit again. Move on to the next combat. Once
                complete click on next phase to move to your opponent’s turn
        </p>
    </li>

    <li>
        <h3><?= $playerTwo ?> Movement Phase </h3>
        <ul>
            <?php if($name == "Jagersdorf"){?>
                <li class="exclusive">
                    No <?= $playerTwo ?> unit may expend more than 2 MP on turn 1 only
                </li>
            <?php } ?>
        </ul>
        <p>see <?= $playerOne ?> Movement above.</p></li>

    <li><h3><?= $playerTwo ?> Attack phase</h3>

        <p>see <?= $playerOne ?> Attack above.</p></li>

    <li><h3>Turn End</h3></li>
</ol>
<h2>Stacking</h2>

<p>No more than one unit may occupy the same hex at any given, however friendly units may move through each other.</p>

<h2>Movement</h2>

<p>The Second Number on the counter is Movement Points <abbr title="Movement Points">(MP)</abbr>.</p>

<p>Units expend different amounts of <abbr title="Movement Points">MP</abbr> for different terrains</p>
<ul>
    <li>Units pay different amounts of Movement Points or <abbr title="Movement Points">MP</abbr> to enter different
        hexes.
        different units will pay different amounts of MPs to enter the same hex. Please see the Terrain Effects Chart or
        <abbr title="Terrain Effects Chart">TEC</abbr> for the effects of terrain on movement.
    </li>

    <li>Creek, All units expend additional movement points to cross a Creek hex side, except when using road movement
        and
        crossing on a bridge.
    </li>

    <li>Road Movement. Units pay only &frac12; movement point when moving along contiguous road hexes regardless of
        other terrain in the hex. In order to use road movement, a unit must be in force march mode.
        <p><em>Forced March Mode: before a units moves, but while selected, hit the 'm' key, an "m" will appear
                on the unit. This means the units is in "forced march" mode and can take advantage of the roads.
                Pressing the "m" key again before moving the unit
                will take them out of "force march" mode. Once a unit has moved one hex they may not change their
                "forced march" status.</em></p>

        <p>Units are not obliged to use the road movement bonus however if they do they may not attack in that turn.</p>

    </li>

    <li>Zones of Control. When a unit enters a Hostile <abbr title="Zone Of Control">ZOC</abbr> it must stop and move no
        further that turn. When a unit exits a hostile zone of control it must stop and move no further that turn. A
        unit may never move directly from one hostile ZOC to another hostile ZOC.
    </li>
    <li>Regardless of movement points required, a unit may always move at least one hex per turn,
        provided they are not moving directly from one zoc to another.
    </li>
</ul>
<h2>Combat (Attacks)</h2>

<p>The first number on a unit is it's combat factor.</p>
<ul>
    <li>A single unit may only participate in single attack in the friendly attack phase.</li>
    <li>Attacks may always be made at lower than odds than those gained by calculation.</li>
    <li>All attacks are voluntary. Except that all hostile units adjacent to an attacking unit must them selves be
        attacked even if only by artillery bombardment.
    </li>
    <li>All combat is between adjacent units except that artillery may attack units up to two hexes away (Bombardment).
        Including into but not over town, hill or woods. Artillery units may not participate in bombardment attacks if
        they adjacent to an enemy unit,
        they must attack an adjacent unit if they attack at all.
    </li>
</ul>
<h4>Multi Hex Multi Unit Combat</h4>

<p>A single unit may attack any or all hostile units that it is adjacent to so long as the odds are not worse than 1-4.
    All attacks against a group of contiguous defenders may be grouped together and resolved as a single attack so long
    as all attackers are within range of all defenders. The terrain applied to the combat situation is the most
    favorable to the defender.</p>

<p>In order to attack more than one unit, click on a defender (it should get a yellow border),
    then click on the second defender you with to attack while holding down the shift key (you should see two units with
    a yellow border).
    you may click on eligible attackers and arrows should appear point at both defenders.
</p>

<p>
<h4>Retreats</h4> Whenever obligated by combat result, the attacking player
retreats the units (attacking or defending) obeying the following requirements. Units may not retreat off board, or into
enemy zones of control,
If a unit cannot find an empty it may retreat over friendly hexes until it finds an empty hex. Units that cannot
retreat are eliminated.
</p>
<h4>Retreat Before Combat</h4> To simmulate retreat before combat, when only non cavalry units are attacking only
cavalry units
the cavalry can do a "retreat before combat" this is reflected in the 'cavalry combat results table', which has all DR's
where EX's or DE's would be.
You may click on the crt where it says "see cavalry table" or "see normal table" to toggle between them.

<h4>Advance after combat</h4> If a defending hex is left vacant any adjacent attacker that participated in the attack my
be moved into that hex. This must be done before the next attack is resolved. Artillery units may NOT advance after
combat.

<h2>Combat Results</h2>

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
<ul>

    <li>
        <h4>Terrain Effects on Combat</h4>
        <ul>
            <?php if ($scenario->jagersdorfCombat) { ?>
                <?php if ($name == "Jagersdorf") { ?>
                    <li class="exclusive"><?= $playerTwo ?> Infantry units are +1 to their combat factor when Attacking
                        into or Defending in woods or
                        towns, unless they are attacking across a creek or bridge.
                    </li>
                <?php } ?>
                <li class="exclusive"><?= $playerOne ?> Infantry units are +1 to their combat factor when Attacking into
                    or Defending
                    in clear, unless they are attacking across a creek or bridge.
                </li>
            <?php } ?>

            <li>All Cavalry units combat factors are divided by 2 when attacking into hexes or across hex sides other
                than clear.
            </li>

            <li>All Infantry and artillery have their combat factors doubled when defending in a town</li>

            <li>All Units have their combat factors doubled when defending on a hill</li>

            <li>All Units except artillery have their combat factors divided by 2 when attacking across creek or bridge
                hex sides.
            </li>
        </ul>
    </li>
    <li>
        <h4>Combined Arms Bonus</h4>
        <ul>
            <li>Any attack starting at 1-1 odds or better against clear terrain hex, that includes attacking units from
                two different branches of service is receives 1 favorable column shift.
            </li>
            <li>Any attack against a clear terrain hex that includes attacking units from all three different branches
                of service receives 2 favorable column shifts.
            </li>
            <li>Any attack against a non clear terrain hex that includes both Infantry and artillery enjoys a 1 column
                favorable odds shift.
            </li>
        </ul>
    </li>

    <li>
        <h4>Combat Result Explanation</h4>
        <ul>
            <li>A-E all attacking units eliminated</li>

            <li>A-R All attacking units must retreat 1 hex (See Retreats page 2)</li>

            <li>D-R All defending units must retreat 1 hex (See Retreats page 2)</li>

            <li>EX all defending units are eliminated. Attacking units of the attackers choice = to or greater than
                eliminated defenders by unmodified combat strength are also eliminated.
            </li>

            <li>DE all defending units are eliminated,</li>
    </li>
</ul>

</ul>
<div class="exclusive">
    <?php include "victoryConditions.php" ?>
</div>

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