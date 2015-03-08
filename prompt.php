<?php
// prompt.php

// Copyright (c) 2009-2011 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */


class Prompt {

    public $promptMessage;
    public $gameRules;
    public $moveRules;
    public $combatRules;
    /* @var Force */
    public $force;
    public $terrain;

    function __construct($GameRules, $MoveRules, $CombatRules, $Force, $Terrain){
        $this->gameRules = $GameRules;
        $this->moveRules = $MoveRules;
        $this->combatRules = $CombatRules;
        $this->force = $Force;
        $this->terrain = $Terrain;
    }

    function getPrompt( $eventType, $id, $hexagon ){

        $this->promptMessage = "";
        //  var unitName;
        //  var status;

        switch( $this->gameRules->mode ) {

            case MOVING_MODE:

                switch ( $eventType ) {

                    case OVER_MAP_EVENT:

                        if ( $this->moveRules->anyUnitIsMoving ) {
                            $this->promptMessage += "click on hexagon to move into";
                            if ( $this->force->units[$this->moveRules->movingUnitId]->status == STATUS_MOVING ) {

                                $los = new Los();
                                $los->setOrigin($this->force->getUnitHexagon($this->moveRules->movingUnitId));
                                $los->setEndPoint($hexagon);
                                $range = $los->getRange();

                                $this->promptMessage += "<br />" + $hexagon->getName();
                                $this->promptMessage += "<br />range is: " + $range;

                                $moveCost = $this->terrain->getTerrainMoveCost(
                                    $this->force->getUnitHexagon($this->moveRules->movingUnitId)
                                    , $hexagon
                                    , $this->force->units[$this->moveRules->movingUnitId]->maxMoveAmount
                                );
                                //if ( (terrain->isExit(hexpartX, hexpartY) == false ) && range == 1) {
                                if ( $range == 1) {
                                    $this->promptMessage += "<br />move cost is: " + $moveCost;
                                }
                                else
                                {
                                    $this->promptMessage += "<br />cannot move here";
                                }
                            }
                            if ( $this->force->units[$this->moveRules->movingUnitId]->status == STATUS_REINFORCING ) {

                                if ( $this->force->units[$this->moveRules->movingUnitId]->reinforceZone
                                    == $this->terrain->getReinforceZone($hexagon))
                                {
                                    $this->promptMessage += "<br />can reinforce here";
                                }
                                else
                                {
                                    $this->promptMessage += "<br />cannot reinforce here";
                                    $this->promptMessage += "<br />blue can reinforce 0103, 0104, 0105";
                                    $this->promptMessage += "<br />red can reinforce 0501";

                                }
                            }
                        }
                        else {
                            $this->promptMessage += "click on a unit to start moving";
                        }
                        break;

                    case OVER_COUNTER_EVENT:

                        if ( $this->force->units[$id]->forceId == $this->force->attackingForceId )
                        {
                            if ( $this->moveRules->anyUnitIsMoving == true ) {
                                if ( $this->moveRules->movingUnitId == $id ) {
                                    $this->promptMessage += "click here to stop";
                                } else {
                                    $this->promptMessage += "click on hexagon to move into";
                                }
                            }
                            else
                            {
                                if ( $this->force->units[$id]->status == STATUS_READY || $this->force->units[$id]->status == STATUS_CAN_REINFORCE ) {
                                    $this->promptMessage += "click on " + $this->force->units[id]->name + " to move";
                                }
                            }
                        }
                        else
                        {
                            $this->promptMessage += "enemy cannot move this phase";
                        }
                        break;
                }
                break;

            case COMBAT_SETUP_MODE:

                switch ( $eventType ) {


                    case OVER_MAP_EVENT:

                        $this->promptMessage .= "click on attacking units to setup combat";
                        $this->promptMessage .= "<br />click on another defender to start new attack";
                        break;

                    case OVER_COUNTER_EVENT:

                        $this->promptMessage .= "click on defender to start new attack";
                        $this->promptMessage .= "<br />click on attacking units to setup combat";


                        if ( $this->force->units[$id]->status == STATUS_DEFENDING ) {
                            //$this->promptMessage += "<br /> " + $this->combatRules->getDefenderTerrainCombatEffect($this->force->units[id]->combatNumber);
                            if ( $this->force->unitHasAttackers($id) == true ) {
                                $this->promptMessage += "<br /> " + $this->force->unitGetAttackerList($id) + " attacking";
                                $this->promptMessage += "<br />combat index: atk str - def str - tec";
                                $this->promptMessage += "<br />" + $this->force->getAttackerStrength($this->force->units[$id]->combatNumber) ;
                                $this->promptMessage += " - " + $this->force->getDefenderStrength($this->force->units[$id]->combatNumber) ;
                                $this->promptMessage += " - " + $this->combatRules->getDefenderTerrainCombatEffect($this->force->units[$id]->combatNumber);
                                $index = ($this->force->getAttackerStrength($this->force->units[$id]->combatNumber) - $this->force->getDefenderStrength($this->force->units[$id]->combatNumber) - $this->combatRules->getDefenderTerrainCombatEffect($this->force->units[$id]->combatNumber));
                                if( $index < 0 ) $index = 0;
                                $this->promptMessage += " = " + $index;
                                if( $index > 5 ) $this->promptMessage += ", use max of 5";
                                $this->promptMessage += "<br />" + $this->combatRules->getCombatOddsList($this->force->units[$id]->combatIndex);
                            }
                        }
                        break;
                }
                break;

            case COMBAT_RESOLUTION_MODE:

                switch ( $eventType ) {

                    case OVER_MAP_EVENT:
                        if ( $this->force->unitsAreDefending() == true )
                        {
                            $this->promptMessage = "click on a defender to resolve combat";
                        } else {
                            $this->promptMessage = "No more attacks to resolve<br />click on Next Phase button to continue";
                        }
                        break;

                    case OVER_COUNTER_EVENT:

                        if ( $this->force->unitsAreDefending() == true )
                        {
                            $this->promptMessage = "click on a defender to resolve combat";
                            if ( $this->force->units[$id]->status == STATUS_DEFENDING ) {
                                $this->promptMessage += "<br /> " + $this->combatRules->getCombatOddsList($this->force->units[$id]->combatIndex);
                                //$this->promptMessage = $this->force->units[id]->name + " " + status_name[$this->force->units[id]->status];
                                //$this->promptMessage = "<BR>attacking odds are: " + combatRatio_name[$this->force->units[id]->combatIndex];
                                //$this->promptMessage += "<BR>" + $this->combatRules->getCombatOddsList( $this->force->units[id]->combatIndex );
                            }
                        } else {
                            $this->promptMessage = "No more attacks to resolve<br />click on Next Phase button to continue";
                        }
                        break;
                }
                break;

            case RETREATING_MODE:
                //$this->promptMessage = "combat results: " + results_name[ $this->force->units[$this->moveRules->movingUnitId]->combatResults ];
                $this->promptMessage += "<br />click on a unit to start retreat<br />then click on hexagon to move";
                break;

            case ADVANCING_MODE:
                //$this->promptMessage = "combat results: " + results_name[ $this->force->units[$this->moveRules->movingUnitId]->combatResults  ];
                $this->promptMessage += "<br />click on unit to start advance <br />then click on hexagon to move <br />or click attacker again to stop in place";
                break;

        }

        return ( $this->promptMessage );
    }
}