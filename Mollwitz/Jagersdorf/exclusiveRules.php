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
            <div>

                <h2>Setting Up</h2>

                <h4>Historical set up (The Prussians Probably Lose)</h4>
                <ul>
                    <li>The Russian player sets up first.</li>
                    <li>He must deploy an artillery unit on the hill north of Norkitten Forrest</li>

                    <li>He must deploy a cavalry unit on each hex marked RC north of Norkitten Forrest and south of the
                        Alm creek.
                    </li>

                    <li>The balance of his units may be deployed on any hex marked "R" and or east of the line of Hexes
                        marked "R"
                        running from the Alm Creek north to the board edge.
                    </li>

                    <li>The Prussian Player sets up after the Russian player is finished setting up</li>

                    <li>He must place a Prussian Cavalry unit on each hex marked "PC" North of Gross Jagersdorf and
                        South of the Alm
                        Creek.
                    </li>

                    <li>The Balance of his units must be deployed on Hexes marked "P" and or within the line of hexes
                        marked "P" around
                        Gross Jagersdorf.
                    </li>
                </ul>
                <h4>Attempt at balance Set up Variant</h4>

                <p>The Russian player removes from his OOB 1 artillery unit and 4 other units of his choice. If any are
                    cavalry he is
                    not obligated to cover all "RC" hexes though he must cover as many as he can. He must always place
                    an artillery unit
                    on the hill North of the Norkitten woods.</p>

                <h2>Victory Conditions</h2>
                <h4>The Game ends instantly at the end of any Turn when one or more of the following conditions applies.
                    Mutual
                    victories are a Draw</h4>
                <ol>
                    <li> At the end of any turn that the Prussian player has lost an accumulated total of units with
                        unmodified combat
                        strengths of 20 Russian victory.
                    </li>
                    <li>At the end of any turn that the Russian player has lost an accumulated total of units with
                        unmodified combat
                        strengths of 25 Prussian victory.
                    </li>

                    <li>There are no Russian units between the Alm and Litten creeks and there is a Prussian Unit on
                        either hex of the
                        East most Bridge of Alm creek. Prussian Victory
                    </li>

                    <li>There are no Russian units in the Norkitten forest or it's clearing. Prussian Victory.</li>


                    <li>Any Prussian turn concludes with no Prussian units within 5 hexes of Gross Jagersdorf
                        (inclusive) and north of
                        the Alt Creek. Russian Victory
                    </li>

                    <li>Turn 12 concludes with none of the above conditions met. Draw</li>
                </ol>

                <h2>Alternate Victory Conditions</h2>
                <h4>Use with or without the reduced Russian <abbr title="Order Of Battle">OOB</abbr></h4>
                <ol>
                    <li>
                        The Prussian player has lost an accumulated total of units with unmodified combat strengths of
                        25 Russian
                        Victory.
                    </li>
                    <li>
                        The Russian player has lost an accumulated total of units with unmodified combat strengths of 25
                        Prussian
                        Victory.
                    </li>
                    <li>
                        There are more Prussian units than Russian units between the Alm and Litten creeks and there is
                        a Prussian Unit
                        on either hex of the East most Bridge of Alm creek. Prussian Victory
                    </li>
                    <li>
                        That player with the most units on Norkitten woods hexes (including the clearing and hill hex)
                        at the end of
                        turn 10 wins.
                    </li>
                    <li>
                        Any Prussian turn concludes with no Prussian units within 5 hexes of Gross Jagersdorf
                        (inclusive) and north of
                        the Alt Creek. Russian Victory
                    </li>
                    <li>
                        Turn 10 concludes with none of the above conditions met. Draw
                    </li>
                </ol>
            </div>
            <div id="credits">
                <h2><cite>Gross Jagersdorf</cite></h2>
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