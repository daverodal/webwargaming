<?php
/**
 * Created by JetBrains PhpStorm.
 * User: david
 * Date: 6/19/13
 * Time: 12:21 PM added this
 * To change this template use File | Settings | File Templates.
 */
?>
<style type="text/css">

    /*#GR ol.ExclusiveRules{*/
    /*counter-reset: item 6;*/
    /*}*/
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Exclusive Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div id="gameRules">
            <H1>
                <?= $name ?>
            </H1>

            <h2 class="exclusive"> EXCLUSIVE RULES </h2>

            <div class="indent">
                <h3>Units</h3>

                <div class="indent">
                    <p> British units have horse artillery.</p>

                    <div class="unit British horseartillery"
                         style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                         alt="0">
                        <nav class="counterWrapper">
                            <div class="counter">
                        </nav>
                        <p class="range">3</p>

                        <p class="forceMarch">M</p>
                        <section></section>


                        <div class="unit-numbers">3 - 5</div>

                    </div>
                    <p class="ruleComment">It moves faster than regular artillery but is the same
                        otherwise. Note the range may be shorter than regular artillery.</p>
                    <p> The British player has both British and Native units available.</p>

                    <div class="left">
                        British
                        <div class="unit British infantry"
                             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                             alt="0">
                            <nav class="counterWrapper">
                                <div class="counter">
                            </nav>
                            <p class="range"></p>

                            <p class="forceMarch">M</p>
                            <section></section>


                            <div class="unit-numbers">3 - 5</div>

                        </div>
                    </div>
                    <div class="left">
                        Native
                        <div class="unit Native infantry"
                             style="border-color: rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204);   position: relative;"
                             alt="0">
                            <nav class="counterWrapper">
                                <div class="counter">
                            </nav>
                            <p class="range"></p>

                            <p class="forceMarch">M</p>
                            <section></section>


                            <div class="unit-numbers">3 - 5</div>

                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <h3>Deploy Phase</h3>

                <p class="indent">The <?= $deployOne ?> player deploys first. The <?= $deployTwo ?> player deploys
                    Second</p>

                <h3>First Player</h3>

                <p class="indent">The <?= $playerOne ?> player moves first. The  <?= $playerTwo ?>  player moves second.
                    After the <?= $playerTwo ?> player completes their
                    turn, the game turn is incremented.</p>
            </div>
            <ol class="ExclusiveRules topNumbers">
                <?php include "victoryConditions.php" ?>
            </ol>
            <div id="credits">
                <h2><cite><?= $name ?></cite></h2>
                <h4>Design Credits</h4>

                <h4>Game Design:</h4>
                Lance Runolfsson
                <h4>Graphics and Rules:</h4>
                <site>Lance Runolfsson</site>
                <h4>HTML 5 Version:</h4>
                David M. Rodal
            </div>
        </div>
    </div>
</div>