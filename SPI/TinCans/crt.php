<?php
// crt.js

// Copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

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

require_once "ModernCombatResultsTable.php";

class CombatResultsTable extends ModernCombatResultsTable
{
    use NavalCombatTrait;


    public $aggressorId = EASTERN_FORCE;

    function __construct(){
        $this->combatResultsHeader = array("1:1","2:1","3:1","4:1","5:1","6:1","7:1","8:1","9:1");
        $this->crts = new stdClass();
        $this->crts->normal = array(
            array(P,  P,  P,  P,  PW, PW, PW, PW, PW    ),
            array(MISS, W,  P,  P,  P,  PW, PW, PW,  PW),
            array(MISS, MISS, W,  W,  W,  P,  PW, PW, PW),
            array(MISS, MISS, MISS, W,  W,  W,  P, PW, PW),
            array(MISS, MISS, MISS, MISS, MISS, W,  P, P, PW),
            array(MISS, MISS, MISS, MISS, MISS, MISS, P, P, PW),
        );
        $this->crts->torpedo = array(
            array(H,  H,  H,  H,  HH, HH, HH, HH, HH    ),
            array(MISS, H,  H,  H,  H,  HH, HH, HH,  HH),
            array(MISS, MISS, H,  H,  H,  H,  HH, HH, HH),
            array(MISS, MISS, MISS, H,  H,  H,  H, HH, HH),
            array(MISS, MISS, MISS, MISS, MISS, H,  H, H, HH),
            array(MISS, MISS, MISS, MISS, MISS, MISS, H, H, HH),
        );

        $this->torpedoHitTwoResultsHeader = array("1-4","5-6","7-10","10-20","20+");

        $this->crts->torpedoHitTwo = array(
            array(S,  P2,  P2,  P,  MISS),
            array(S,  PW,  P2,  P,  P),
            array(S,  PW,  PW,  P2, P),
            array(S,  S,   PW,  P2, P),
            array(S,  S,   S,   P2, P2),
            array(S,  S,   S,   PW, P2),
        );

        $this->torpedoHitOneResultsHeader = array("1-2", "3", "4","5-6","7-10","10-20","20+");

        $this->crts->torpedoHitOne = array(
            array(PW, P,  P,  P,  P,  MISS, MISS),
            array(S,  P,  P,  P,  P,  P,  MISS),
            array(S,  S,  P,  P,  P,  P,  MISS),
            array(S,  PW, PW, P,  P,  P,  P),
            array(S,  P2, P2, PW, P,  P,  P),
            array(P2, S, S,  P2, PW, P2, P),
        );

        $this->combatOddsTable = array(
            array(),
            array(),
            array(),
            array(),
            array(),
            array()
        );

        $this->combatIndexCount = 9;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieSideCount = 6;
        $this->combatResultCount = 9;

        $this->setCombatOddsTable();
    }

    function getCombatIndex($attackStrength, $defenseStrength)
    {
        $ratio = $attackStrength / $defenseStrength;
        $combatIndex = floor($ratio) - 1;
        return $combatIndex;
    }


    function getCombatResults($Die, $index, $combat)
    {
        $battle = Battle::getBattle();

        if($battle->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $battle->gameRules->phase == RED_TORP_COMBAT_PHASE){
            $hits = $this->crts->torpedo[$Die][$index];
            $combat->hits = $hits;
            switch($hits){
                case MISS:
                    return MISS;
                case H:
                    $Die = floor($this->dieSideCount * (rand() / getrandmax()));
//                    $Die = 0;
                    $combat->hitDie = $Die + 1;
                    $defense = $combat->unitDefenseStrength;
                    $col = 6; /* catch all 20+ */
                    if($defense > 0 && $defense < 11){
                        switch($defense){
                            case 1:
                            case 2:
                                $col = 0;
                                break;
                            case 3:
                                $col = 1;
                                break;
                            case 4:
                                $col = 2;
                                break;
                            case 5:
                            case 6:
                                $col = 3;
                                break;
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                                $col = 4;
                                break;
                        }
                    }elseif($defense >= 11 && $defense <= 20){
                        $col = 5;
                    }
                    $combat->hitCol = $col + 1;

                    return $this->crts->torpedoHitOne[$Die][$col];
                break;
                case HH:
                    $Die = floor($this->dieSideCount * (rand() / getrandmax()));
//                    $Die = 2;

                    $combat->hitDie = $Die+1;
                    $defense = $combat->unitDefenseStrength;
                    $col = 4; /* catch all 20+ */
                    if($defense > 0 && $defense < 11){
                        switch($defense){
                            case 1:
                            case 2:
                            case 3:
                            case 4:
                                $col = 0;
                                break;
                            case 5:
                            case 6:
                                $col = 1;
                                break;
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                                $col = 2;
                                break;
                        }
                    }elseif($defense >= 11 && $defense <= 20){
                        $col = 3;
                    }

                    $combat->hitCol = $col + 1;
                    return $this->crts->torpedoHitTwo[$Die][$col];

            }

            return MISS;
        }
        return $this->crts->normal[$Die][$index];

    }
}
