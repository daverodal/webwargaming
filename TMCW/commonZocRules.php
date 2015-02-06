<?php
/**
 * Copyright davidrodal.com
 * User: david
 * Date: 2/1/15
 * Time: 4:02 PM
 */
?>
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