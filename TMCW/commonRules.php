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


</style>
<div class="dropDown" id="GRWrapper" style="font-weight:normal">
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
                <li id="contentsRules">
                    <?php include "commonContents.php";?>
                </li>
                <li id="unitsRules">
                    <?php include "commonUnitsRules.php" ?>
                </li>
                <li id="sopRules">
                    <?php include "commonSequenceOfPlay.php" ?>
                </li>
                <li id="stackingRules">
                    <?php include "commonStacking.php" ?>
                </li>
                <li id="moveRules">
                    <?php include "commonMoveRules.php" ?>
                </li>
                <li id="zocRules">
                    <?php include "commonZocRules.php"; ?>

                </li>
                <li id="combatRules">
                    <?php include "commonCombatRules.php"; ?>
                </li>

                <li class="exclusive" id="victoryConditions">
                    <?php include "victoryConditions.php"; ?>
                </li>

            </ol>
        </div>
    </div>
</div>

