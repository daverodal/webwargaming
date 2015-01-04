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
        font-size:16px;
        font-weight: normal;
    }

    #gameRules table,  #gameRules th,  #gameRules td {
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
        margin: 3px 0;
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
</style>
<div class="dropDown" id="GRWrapper">
<h4 class="WrapperLabel" title="Game Rules">Rules</h4>
<div id="GR" style="display:none">
<div class="close">X</div>
<div id="gameRules">
<H1>
   Napoleons Training Academy 
</H1>

<h3> Quick Start Guide #3</h3>
<header>
    
</header>
<h2></h2>
<ol>


    <li>Red Movement Phase (Alpha)</li>

    <li>Red Combat Setup Phase (Alpha)</li>

    <li>Red Combat Resolution Phase (Alpha)</li>

    <li>Blue Movement Phase (Bravo)</li>

    <li>Blue Combat Setup Phase (Bravo)</li>

    <li>Blue Combat Resolution Phase (Bravo)</li>
    
    <li>Turn End</li>
</ol>
<h2>Movement</h2>

<p>The Second Number on the counter is Movement Points <abbr title="Movement Points">(MP)</abbr>.</p>

<p>All hexes in the game are considered clear, event the lone tree in the center of the game. Movement points required for terrain are below.</p>
<ul>
    <li>Clear and Town, 1 <abbr title="Movement Points">MP</abbr> to enter.</li>

    <li>Zones of Control. When a unit enters a Hostile <abbr title="Zone Of Control">ZOC</abbr> it must stop and move no
        further that turn. Units that start a turn in an enemy ZOC may not move that phase, and may only escape a zoc via combat.
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
        Including into but not over town, hill or woods. Artillery units may not participate in bombardment attacks if they adjacent to an enemy unit,
        they must attack an adjacent unit if they attack at all.
    </li>
</ul>
<h4>Multi Hex Multi Unit Combat</h4>

<p>A single unit may attack any or all hostile units that it is adjacent to so long as the odds are not worse than 1-4.
    All attacks against a group of contiguous defenders may be grouped together and resolved as a single attack so long
    as those defenders occupy a single terrain type.</p>

<p>
<h4>Retreats</h4> Whenever obligated by combat result, or choosing to retreat a unit before combat, the owning player
retreats his units obeying the following requirements. Units may not retreat off board, or into enemy zones of control,
the minimum number friendly of friendly units may be displaced 1 hex each to allow a unit to retreat. Units that cannot
retreat are eliminated.
</p>
<h4>Retreat Before Combat</h4> Any cavalry unit that is attacked solely by infantry may instead of suffering the attack
be retreated by the owning player 1 hex after the attack is announced and before the die is rolled.

<h4>Advance after combat</h4> If a defending hex is left vacant any adjacent attacker that participated in the attack my
be moved into that hex. This must be done before the next attack is resolved. Artillery units may NOT advance after combat.

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
            <li>Russian Infantry units are +1 to their combat factor when Attacking into or Defending in woods or
                towns.
            </li>

            <li>Prussian Infantry units are +1 to their combat factor when Attacking into or Defending in clear,</li>

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

<h2>Victory Conditions</h2>
<h4>The game ends at the end of turn 7. The player last occupied or passed through the "tree of victory" (Arbre de la Victoire) wins the game.
    If nobody passed through it, the game is a tie</h4>


<div id="credits">
    <h2><cite>Napoleons Training Academy</cite></h2>
    <h3>Design Credits</h3>

    <h4>Game Design:</h4>
    David M. Rodal
    <h4>Graphics:</h4>
    David M. Rodal
    <h4>Rules:</h4>
    <site>David M. Rodal, based off of a game created by Lance Runolfsson</site>
    <h4>HTML 5 Version:</h4>
    David M. Rodal
</div>
</div>
</div></div>