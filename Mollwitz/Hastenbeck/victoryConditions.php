<?php global $force_name;
$playerOne = $force_name[1];
$playerTwo = $force_name[2];?>
<div>

    <h2>Setting Up</h2>

    <ul>
        <li>The <?= $playerTwo ?> player sets up first. The <?= $playerOne ?> Setup second.</li>
        <li>When the <?= $playerOne ?> player starts deploying their units. There is a %50 chance they can deploy in the F2 hexes, and %50 they
        have to deploy in the F1 Hexes.
        </li>
    </ul>
    <h2>Movement </h2>
    <ul>
        <li> <?= $playerOne ?> moves first.</li>
    </ul>

    <h2>Victory Conditions</h2>
    <ol>
        <li> Victory goes to the side that first reaches 45 points with a lead of 10.
            Historically both Generals thought they had been out maneuvered and withdrew early. Alternate Play to 60
            points with a lead of 10.
        </li>
        <li>One point is awarded per infantry combat point eliminated.</li>
        <li> Two points are awarded per cavelry or artillery combat point eliminated.</li>
        <li> Yellow Victory points count for both sides</li>
        <li> Red only for allies</li>
        <li> Black only French</li>
        <li> Points are held by the last to move through</li>
        <li>probalby those above rules should be implemented.</li>
    </ol>
</div>