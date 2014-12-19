<span class="big">Sequence of Play</span>

<p>The game is made up of <span class="gameLength">7</span> Game turns, each Game turn consists of two
    player turns, Each player turn has
    several phases. These are described below in the sequence of play.</p>
<ol>
    <li>
        <?= $playerOne ?> Player Turn
        <ol>
            <li>
                Replacement Phase
                <p>The phasing player may allocate as many replacements as they
                received. <?= $playerOne ?>
                forces receive one replacement per turn. (There is no replacement phase
                for the <?= $playerOne ?> player on turn one).</p>
            </li>
            <li>
                Movement Phase
                <p>The phasing player may move any or all of their units. Movement is voluntary.</p>
            </li>
            <li>
                Combat Phase
                <p>The phasing player may any and all units that adjacent to their units. Combat is
                voluntary.</p>
            </li>
            <li>
                Second Movement Phase
                <p>The phasing player may move any or all of their <strong>Armored</strong> or
                <strong>mechinized
                    infantry</strong> units. Infantry units may <strong>not</strong> move in the
                second
                movement phase.</p>
            </li>
        </ol>
    </li>
    <li>
        <?= $playerTwo ?> Player Turn
        <ol>
            <li>
                Replacement Phase
                The phasing player may receive as many replacements as they are
                allocated. <?= $playerTwo ?>
                s receive 10 replacements per turn.
            </li>
            <li>
                Movement Phase
                The phasing player may move any or all of their units. Movement is voluntary.
            </li>
            <li>
                Combat Phase
                The phasing player may any and all units that adjacent to their units. Combat is
                voluntary.
            </li>
            <li>
                Second Movement Phase
                The phasing player may move any or all of their <strong>Armored</strong> or
                <strong>mechinized
                    infantry</strong> units. Infantry units may <strong>not</strong> move in the
                second
                movement phase.
            </li>
        </ol>
    </li>

</ol>
<p>At the end of <span class="gameLength">7</span> game turns the game is over and victory is
    determined.
</p>
