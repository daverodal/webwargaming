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

<a name="combat"></a><span class="big">Combat.</span>


<ol>
    <li>
        <span class="lessBig">Combat Rules</span>

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
            <li><span class="lessBig">Multi Unit Combat</span>

                <ol>
                    <li>
                        A single unit may attack any or all hostile units that it is adjacent to so long as the odds
                        are not worse than
                        1-4.
                    </li>
                    <li>
                        All attacks against a group of contiguous defenders may be grouped together and resolved as
                        a single attack so
                        long
                        as all attackers are adjacent to all defenders.
                    </li>
                    <li>
                        The terrain applied to the combat
                        situation is the most
                        favorable to the defender.
                    </li>
                    <li>
                        See multi unit combat setup below to see how to initiate multi unit combat.
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
                Multi unit combat setup.
                <ol>
                    <li>
                        In order to attack more than one unit, click on a defender (it should get a yellow border),
                        then click on the plus key in the menu bar, finally click on the second defender you with to attack (you
                        should see two units with a yellow border).
                    </li>
                    <li>
                        You may click on eligible attackers and arrows should appear pointing at both defenders.
                    </li>
                    <li>
                        Remember, all attackers must be adjacent to all defenders.
                    </li>
                </ol>

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

    <li>Combat Results Table<br><br>
        <?php                 $crt = new CombatResultsTable();
        foreach($crt->crts as $crtName => $crtValue){
            echo "<br><br>$crtName";
            ?>
        <div class="clear">&nbsp;</div>

        <div class="left">
            <div id="odds">
                <span class="col0">&nbsp;</span>
                <?php

                $i = 1;
                foreach ($crt->combatResultsHeader as $odds) {
                    ?>
                    <span class="col<?= $i++ ?>"><?= $odds ?></span>
                <?php } ?>
            </div>
            <?php
            $rowNum = 1;
            $odd = ($rowNum & 1) ? "odd" : "even";
            foreach ($crt->crts->normal as $row) {
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




        <div class="clear"></div>
        <?php }?>
    </li>


    <li><span class="lessBig">Combat Result Explanation</span>
        <ol>
            <li>AE all attacking units eliminated</li>

            <li>AL One attacking unit must take a step loss.</li>

            <li>AR All attacking units must retreat 1 hex (See Retreats below)</li>

            <li>DR All defending units must retreat 1 hex (See Retreats below)</li>

            <li>DRL All defending units must retreat 1 hex (See Retreats below).
                In addition, one defending unit must be reduced one step</li>

            <li>EX all defending units are eliminated. Attacking units of the attackers choice = to or
                greater than
                eliminated defenders by unmodified combat strength are also eliminated. Remaining Attackers may advance
                (See Advance after combat below).
            </li>

            <li>DE all defending units are eliminated,</li>
        </ol>
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
</ol>