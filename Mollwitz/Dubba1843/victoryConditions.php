<?php global $force_name;
$playerOne = $force_name[1];
$playerTwo = $force_name[2];?>
<div>

    <h2>Setting Up</h2>

    <ul>
        <li>The <?= $playerTwo ?> player sets up first. The <?= $playerOne ?> play sets up second.</li>
        <li> The <?= $playerOne ?> player moves.</li>
    </ul>
    <h2>Combat Variations </h2>
    <ul>
        <li> All artillery is double defense in clear</li>
        <li> Beluchi artillery is only ranged 2</li>
        <li> Beluchi Infantry +1 combat point in Jungle/Scrub</li>
        <li> British Infantry and Cavalry MAY retreat in to Beluchi Infantry ZOC.</li>
        <li> Beluchi do not get combined arms bonus.</li>
    </ul>

    <h2>Victory Conditions</h2>
    <ol>
        <li> British win at 45 points</li>
        <li> All Beluchi losses are scored a face value</li>
        <li> Beluchi's win if they score 40 points or British don't win by turn 12.</li>
        <li> Beluchi's get 15 points as long as they occupy the road hex exiting the west edge of the map</li>
        <li> All Royal units are scored at double value including Inf.</li>
        <li>All Native units are scored at face value.</li>
        <li> Beluchi victory point hexes in Black</li>
    </ol>
</div>