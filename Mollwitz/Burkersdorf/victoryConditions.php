<style>
    #exclusiveRules p {
        margin-left: 15px;
    }

    #exclusiveRules h1 {
        font-size: 24px;
    }

    #exclusiveRules h2 {
        font-size: 15px;
    }
</style>
<div id="exclusiveRules">

    <h1>Setting Up</h2>
        <h2><?= $playerOne ?> Deploy Phase</h1>
    <ul>
        <li>The <?= $playerOne ?> player sets up first. The <?= $playerOne ?> units are red and white.</li>
        <li>They may deploy any or all their units on or south of the hexes with an A on them.
            <p>Clicking on a unit will
                display all possible placements of the unit. The <?= $playerOne ?> player may deploy all, some or none
                of their units.</p>

            <p>

                Units not deployed may be placed later during a subsequent
                movement phase. (it will cost their entire movement allowance though).
            </p></li>
        <li>When the <?= $playeOne ?> player is done deploying they should hit the "next phase" button</li>
    </ul>
    <h2><?= $playerTwo ?> Deploy Phase</h2>
    <ul>
        <li>The <?= $playerTwo ?> Player sets up next. The <?= $playerTwo ?> units are red and yellow.</li>
        <li> They may deploy any or all their units on or north of the hexes with an P on them.<p>

                Again, clicking on a unit will
                display all possible placements of the unit. The <?= $playerTwo ?> may deploy all, some or none of
                their units.</p>

            <p>
                Units not deployed may be placed later during a subsequent
                movement phase. (it will cost their entire movement allowance though).
            </p>
        </li>
        <li>When the <?= $playeTwo ?> player is done deploying they should hit the "next phase" button, it will then
            be <?= $playerOne ?>'s movement phase
        </li>
    </ul>
    <h2><?= $playerOne ?> Movement Phase</h2>
    <ul>
        <li>On the first movement phase all of <?= $playeOne ?> have a movement allowance of two. This is for
            the <?= $playeOne ?>'s first movement phase only.
        </li>

    </ul>

    <h1>Victory Conditions</h1>
    <ol>
        <li> Some Hexes are marked in Red numbers for victory points only the Prussian receives points for these hexes.
            And only while they are actually occupied by a Prussian unit at the end of an Austrian turn
        </li>
        <li> The Prussian has won the game at the end of any Austrian turn that the Prussian has 70 victory points
            including at least two victory towns or the LOC hex.
        </li>
        <li> The Austrian Player wins at the end of any Austrian turn that he has 70 victory points.</li>
        <li> The Austrian Player wins if the Prussian has occupied no victory hexes by the end of the last turn of the
            game.
        </li>
        <li> All other cases are a draw.</li>

    </ol>
    <h2>Victory points are awarded on the following basis.</h2>
    <ol>
        <li>
            For each infantry combat strength point eliminated, 1 point.
        </li>
        <li>
            For each cavalry or artillery combat strength point eliminated, 2 point.
        </li>
        <li>
            For each city labeled "Austrian", the Prussian player is awarded 10 points if they take it. If the
            Austrian player retakes the city,
            the Prussian player loses the awarded 10 points.
        </li>
        <li>If the Prussian player takes the LOC hex at the south edge of the map,
            it's the same as taking a labeled city (see above), except there are 50 points awarded or lost.
        </li>
    </ol>
</div>