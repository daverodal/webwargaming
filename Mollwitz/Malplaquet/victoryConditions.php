<?php global $force_name;
$playerOne = $force_name[1];
$playerTwo = $force_name[2];?>
<li class="exclusive">
    <span class="lessBig">Victory Conditions</span>
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
</li>