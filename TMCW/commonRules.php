<style type="text/css">
    #header {
        /*display:none;*/
    }

    .exclusive {
        color: green;
    }

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

    #GR OL.topNumbers {
        counter-reset: item -1;
    }

    #GR .topNumbers > LI:before {
        content: "[" counters(item, ".") ".0] ";
        font-size: 19px;
    }

</style>
<div class="dropDown" id="GRWrapper" style="font-weight:normal">
    <h4 class="WrapperLabel" title="Game Rules">Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div id="gameRules">
            <?php $playerOne = $force_name[1];
            $playerTwo = $force_name[2]; ?>
            <h1>    <?= $name ?>    </h1>

            <h2>Rules of Play</h2>

            <h2>Design Context</h2>

            <p><?= $name ?> is a continuation of the gaming framework first pioneered by the game The Martian Civil War.
                We hope you enjoy playing our game.</p>


            <ol class="topNumbers">
                <li>
                    <?php include "commonUnitsRules.php" ?>
                </li>
                <li>
                    <?php include "commonSequenceOfPlay.php" ?>
                </li>
                <li>
                    <?php include "commonMoveRules.php" ?>
                </li>
                <li>
                    <span class="big">Zones of Control</span>

                    <p>
                        The six hexes surrounding a unit constitute it's Zone of Control or <abbr
                            title="Zone Of Control">ZOC</abbr>.
                        <abbr title="Zone Of Control">ZOC</abbr>'s affect the movement of enemy units. The affect is
                        dependant upon
                        many factors.
                    </p>
                    <ol>
                        <li><span class="big">Effects on Movement</span>

                            <p>When a unit enters a hostile <abbr title="Zone Of Control">ZOC</abbr> it must either stop
                                and
                                move no further, OR, expend a
                                certain amounts of <abbr title="Zone Of Control">MP's</abbr>to enter the hex, depending
                                upon
                                the
                                unit. If a units starts the
                                turn in a <abbr title="Zone Of Control">ZOC</abbr>, it may require movement points to
                                leave
                                the
                                hex, depending upon the unit type.</p>

                        <li><span class="big">Mechanized units</span>

                            <p>A mechanized unit (units with a second movement phase) require 2
                                additional movement points to enter a zoc. They also
                                require 1 additional MP to leave a zoc.</p>

                            <p>Mechanized units may move directly from one <abbr title="Zone Of Control">ZOC</abbr>
                                to
                                another
                                at the price of 3 additional <abbr title="Zone Of Control">MP's</abbr>'s</p></li>
                        <li><span class="big">Infantry units</span>

                            <p>Infantry units must stop upon entering a <abbr title="Zone Of Control">ZOC</abbr>.
                                Infantry units that start
                                their movement phase in a <abbr title="Zone Of Control">ZOC</abbr> may exit without
                                penalty, and re-enter a <abbr title="Zone Of Control">ZOC</abbr>
                                provided they do not move directly from one <abbr title="Zone Of Control">ZOC</abbr>
                                to
                                another.</p>

                            <?php if ($name == "Manchuria1976") { ?>
                                <p class="exclusive"><?= $playerTwo ?> Infantry that start their turn in a mountain
                                    hex
                                    in an enemy <abbr title="Zone Of Control">ZOC</abbr>, may move directly to
                                    another
                                    hexagon with an enemy <abbr title="Zone Of Control">ZOC</abbr>,at which point
                                    they
                                    must stop and move no further.</p>
                            <?php } ?>
                        </li>

                        <li>Regardless of movement points required, a unit may always move at least one hex per turn,
                            provided they are not moving directly from one zoc to another.
                        </li>
                        </ol>
                </li>
                <li><a name="combat"></a><span class="lessBig">Combat (Attacks)</span>


                    <ol>
                        <li><span class="lessBig">Combat Rules</span>

                            <p class="ruleComment">The first number on a unit is it's combat factor.</p>
                            <ol>
                                <li>A single unit may only participate in single attack in the friendly attack phase.</li>
                                <li>All combat is between adjacent units.</li>
                                <li>Units may attack more than one unit. See multi unit combat below.</li>
                                <li>Combat odds are determined by adding the attack strength of all attacking units and
                                    dividing it by the
                                    defense strength of all defending units.
                                </li>
                                <li>Attacks may always be made at lower than odds than those gained by calculation.</li>
                                <li>All attacks are voluntary. You may attack as many or as few units that are adjacent to
                                    your units.
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
                                <li>If you wish to lower the odd of an attack, select the combat, ensure the defender has
                                    yellow borders and click on the odds
                                    in the crt you wish to lower the odds to. A purple column on the lowered odds should
                                    appear. Clicking again on the odds will remove the
                                    lowered odds. Example if the odds were 2:1, you can click on 1:1 a purple column will
                                    appear over 1:1. During combat resolution the lowered odds will be used
                                    to resolve combat.
                                    <p class="ruleComment">People often lower the odds to avoid exchanges, often when a high
                                        valued unit is attack a lower valued unit.</p></li>
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

                            <p>A single unit may attack any or all hostile units that it is adjacent to so long as the odds
                                are not worse than
                                1-4.
                                All attacks against a group of contiguous defenders may be grouped together and resolved as
                                a single attack so
                                long
                                as all attackers are within range of all defenders. The terrain applied to the combat
                                situation is the most
                                favorable to the defender.</p>

                            <p>In order to attack more than one unit, click on a defender (it should get a yellow border),
                                then click on the second defender you with to attack while holding down the shift key (you
                                should see two units
                                with
                                a yellow border).
                                you may click on eligible attackers and arrows should appear point at both defenders.
                            </p>

                            <p>
                        </li>
                        <li>
                            <span class="lessBig">Retreats</span> Whenever obligated by combat result, the attacking player
                            retreats the units (attacking or defending) obeying the following requirements. Units may not
                            retreat off board, or
                            into
                            enemy zones of control,
                            If a unit cannot find an empty it may retreat over friendly hexes until it finds an empty hex.
                            Units that cannot
                            retreat are eliminated.
                            </p>
                        </li>

                        <li>
                            <span class="lessBig">Advance after combat</span> If a defending hex is left vacant any adjacent
                            attacker that participated in the
                            attack my
                            be moved into that hex. This must be done before the next attack is resolved. Artillery units
                            may NOT advance after
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

                                <li>
                                    When a combat takes place in a non clear terrain hex, or all attackers are attacking
                                    across a river or stream, there
                                    may be a shift of one or more columns. Please see the CRT for more info.
                                </li>

                                <li>See the Terrain Effects Chart, the TEC button, for more info.</li>
                            </ol>
                        </li>

                        <li><span class="lessBig">Combat Result Explanation</span>
                            <ol>
                                <li>A-E all attacking units eliminated</li>

                                <li>A-R All attacking units must retreat 1 hex (See Retreats page 2)</li>

                                <li>D-R All defending units must retreat 1 hex (See Retreats page 2)</li>

                                <li>EX all defending units are eliminated. Attacking units of the attackers choice = to or
                                    greater than
                                    eliminated defenders by unmodified combat strength are also eliminated.
                                </li>

                                <li>DE all defending units are eliminated,</li>
                            </ol>
                        </li>
                    </ol>
                </li>

            </ol>
        </div>
        <div class="exclusive">
            <?php include "victoryConditions.php" ?>
        </div>
    </div>
</div>

