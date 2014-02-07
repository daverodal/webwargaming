<?php global $force_name;
$playerOne = $force_name[1];
$playerTwo = $force_name[2];?>
<div>

    <h2>Setting Up</h2>

    <ul>
        <li>The <?=$playerTwo?> player sets up first. The <?=$playerTwo?> units are blue and grey.</li>
        <li>They may deploy any or all their units on or south of the hexes with an F on them.
            <p>Clicking on a unit will
        display all possible placements of the unit. The <?=$playerTwo?> player may deploy all, some or none of their units.</p>
            <p></p>
            Units not deployed may be placed later during a subsequent
        movement phase. (it will cost their entire movement allowance though).
        </P></li>
        <li>When the <?=$playerTwo?> player is done deploying they should hit the "next phase" button</li>

        <li>The <?=$playerOne?> Player sets up next. The <?=$playerOne?> units are red and yellow. </li>
        <li> They may deploy any or all their units on or north of the hexes with an A on them.<p>

                Again, clicking on a unit will
                display all possible placements of the unit. The <?=$playerOne?> may deploy all, some or none of their units.</p>
            <p></p>
            Units not deployed may be placed later during a subsequent
            movement phase. (it will cost their entire movement allowance though).
            </P>
        </li>

    </ul>

    <h2>Victory Conditions</h2>
    <ol>
        <li> At the end of any <?=$playerTwo?> player turn, that the <?=$playerOne?>'s occupy or were the last to pass through
            Malplaquet and at least one other city. and have a 10 point lead in victory points, the <?=$playerOne?>'s win.
            <p>
                Victory points are awared as follows:
                For each infantry strength eliminated, one victory point.
                For each cavalry or artillery strength point eliminated, two victory points.
            </p>
            </li>
        <li>
            If the <?=$playerTwo?> player can avoid the above victory until the end of turn 12, and hold all the cities, the <?=$playerTwo?> wins.
            </li>
        <li>
            Any other outcome, a draw.
            </li>

    </ol>
</div>