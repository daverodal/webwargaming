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
<div class="dropDown" id="GRWrapper">
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
                    <?php include "commonMoveRules.php"?>
                </li>
            </ol>

            <h3>Zones of Control</h3>
            <ul>
                <li><p>
                        The six hexes surrounding a unit constitute it's Zone of Control or <abbr
                            title="Zone Of Control">ZOC</abbr>.
                        <abbr title="Zone Of Control">ZOC</abbr>'s affect the movement of enemy units. The affect is
                        dependant upon
                        many factors.
                    </p>

                    <p>When a unit enters a hostile <abbr title="Zone Of Control">ZOC</abbr> it must either stop and
                        move no further, OR, expend a
                        certain amounts of <abbr title="Zone Of Control">MP's</abbr>to enter the hex, depending upon
                        the
                        unit. If a units starts the
                        turn in a <abbr title="Zone Of Control">ZOC</abbr>, it may require movement points to leave
                        the
                        hex, depending upon the unit type.</p>
                    <ul>
                        <li>Mechanized units<p>A mechanized unit (units with a second movement phase) require 2
                                additional movement points to enter a zoc. They also
                                require 1 additional MP to leave a zoc.</p>

                            <p>Mechanized units may move directly from one <abbr title="Zone Of Control">ZOC</abbr>
                                to
                                another
                                at the price of 3 additional <abbr title="Zone Of Control">MP's</abbr>'s</p></li>
                        <li>Infantry units
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
                    </ul>
                </li>
                <li>Regardless of movement points required, a unit may always move at least one hex per turn,
                    provided they are not moving directly from one zoc to another.
                </li>
            </ul>
        </div>
        <div class="exclusive">
            <?php include "victoryConditions.php" ?>
        </div>
    </div>
</div>

