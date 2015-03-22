<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?><li><span class="lessBig">Victory Conditions</span>

        <p>The Game ends instantly at the end of any Player Turn when one or more of the following conditions applies.
            Mutual
            victories are a Draw.</p>

        <h1 class="victory">50 points and a 10 point lead</h1>

        <p class="ruleComment">At the end of any turn that one player has accumulated 50 Victory Points with a 10 point
            lead.</p>
        <ol>
            <li> Victory Points are awarded when one player destroys another players unit and for occupying certain
                hexes.

                <ol>
                    <li>
                        1 <abbr title="Victory Point">VP</abbr> per infantry strength point killed.
                    </li>
                    <li>
                        2 <abbr title="Victory Point">VP</abbr> per artillery or cavalry strength point killed.
                    </li>


                    <li><span class="lessBig">Victory Cities.</span>

                        <p class="ruleComment">
                            There are some cities labeled on the map either <?= $playerOne ?> or <?= $playerTwo ?>.
                            These give no points to the original owners. But if the opponent takes these cities or hexes,
                            the opponent will be awarded some victory points. If the original owner retakes them,
                            the opponents loses the victory points they were awarded.
                        </p>
                        <ol>
                            <li>
                                If the French occupy or were the last to pass through any of the Austrian hexes they
                                will have 5 points per at the moment they take the hex.
                                They may make a grand total of 25 points for all 5 hexes.
                            </li>
                            <li>
                                If the Anglo Allied occupy or were the last to pass through any of the three hexes of
                                Minden, (marked with a 10 on them),
                                they will have 10 points per city at the moment they take the hex.
                                They make make a grand total of 30 points for all 3 hexes.
                            </li>
                        </ol>

                    </li>
                    <li>Victory Points are display in the Victory: box at the top of the page.</li>

                </ol>

            </li>
            <li>If turn 14 concludes with none of the above conditions met. The game is a draw.</li>
        </ol>
    </li>