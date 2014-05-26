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
</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Exclusive Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div id="gameRules">
            <H1>
                <?=$name?>
            </H1>
            <h2 class="exclusive"> EXCLUSIVE RULES
            </h2>
            <ul>
                <?php if($scenario->jagersdorfCombat){?>
                <li>
                    <h4>Terrain Effects on Combat</h4>
                    <ul>
                        <li >Prussian Infantry units are +1 to their combat factor when Attacking into
                            or Defending in clear, unless they are attacking across a creek or bridge.
                        </li>
                    </ul>
                </li>
                <?php } ?>


            </ul>
            <ol class="ExclusiveRules">
            <?php include "victoryConditions.php"?>
            </ol>
            <div id="credits">
                <h2><cite><?=$name?></cite></h2>
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