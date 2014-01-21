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

</style>
<div class="dropDown" id="GRWrapper">
    <h4 class="WrapperLabel" title="Game Rules">Exclusive Rules</h4>

    <div id="GR" style="display:none">
        <div class="close">X</div>
        <div id="gameRules">
            <H1>
                Gross Jagersdorf
            </H1>
            <h2 class="exclusive"> EXCLUSIVE RULES
            </h2>
            <ul>
                <li>
                    <h4><?= $playerTwo ?> Movement Phase </h4>
                    <ul>
                        <li>
                            No <?= $playerTwo ?> unit may expend more than 2 MP on turn 1 only
                        </li>
                    </ul>
                </li>
                <li>

                    <h4>Terrain Effects on Combat</h4>
                    <ul>
                        <li >Russian Infantry units are +1 to their combat factor when Attacking into
                            or Defending in woods or
                            towns, unless they are attacking across a creek or bridge.
                        </li>

                        <li >Prussian Infantry units are +1 to their combat factor when Attacking into
                            or Defending in clear, unless they are attacking across a creek or bridge.
                        </li>
                    </ul>
                </li>


            </ul>
            <?php include "victoryConditions.php"?>
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