<span class="big">Sequence of Play</span>

<p>The game is made up of 7 Game turns, each Game turn consists of two
    player turns, Each player turn has
    several phases. These are described below in the sequence of play.</p>
<ol>
    <li>
        <?= $playerOne ?> Player Turn
        <ol>
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
                The phasing player may move any or all of their <strong>Armored</strong> or
                <strong>mechinized
                    infantry</strong> units. Infantry units may <strong>not</strong> move in the
                second
                movement phase.
            </li>
        </ol>
    </li>
    <li>
        <?= $playerTwo ?> Player Turn
        <ol>
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
<p>At the end of 7 game turns the game is over and victory is
    determined.
</p>
