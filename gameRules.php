<?php
// gameRules.js
 
// copyright (c) 2009-2011 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 
 
class PhaseChange{

    public $currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn;
    
    function __construct($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn ) {


	$this->currentPhase = $currentPhase;
	$this->nextPhase = $nextPhase;
	$this->nextMode = $nextMode;
	$this->nextAttackerId = $nextAttackerId;
	$this->nextDefenderId = $nextDefenderId;
	$this->phaseWillIncrementTurn = $phaseWillIncrementTurn;
    }
}

class GameRules{
    // class references
public $moveRules;
public $combatRules;
public $force;
public $phaseChanges;

function __construct( $MoveRules, $CombatRules, $Force ) {

	// local variables
//    	var turn;
//    	var maxTurn;
//	var phase;
//	var mode;
//	var combatModeType;
//	var gameHasCombatResolutionMode;
//	var attackingForceId;
//	var defendingForceId;
//	var deleteCount;

	$this->moveRules = MoveRules;
	$this->combatRules = CombatRules;
	$this->force = Force;
	$this->phaseChanges = array();

    	$this->turn = 1;
	$this->phase = BLUE_MOVE_PHASE;
	$this->mode = MOVING_MODE;
	$this->combatModeType = COMBAT_SETUP_MODE;
	$this->gameHasCombatResolutionMode = true;
	$this->trayX = 0;
	$this->trayY = 0;
	$this->deleteCount = 0;
	$this->attackingForceId = BLUE_FORCE;
	$this->defendingForceId = RED_FORCE;

    $this->force->setAttackingForceId($this->attackingForceId);
}
 
function setMaxTurn( $max_Turn ) {

	$this->maxTurn = $max_Turn;
}

function addPhaseChange( $currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn ) {

	$phaseChange = new PhaseChange($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn);
	$this->phaseChanges->push($phaseChange);
}

function processEvent( $event, $id, $hexagon ){
    global $phase_name, $event_name,$mode_name;

    $eventname = $event_name[$event];
    $modename = $mode_name[$this->mode];
    $phasename = $phase_name[$this->phase];

    switch( $this->mode ) {
		
    	case MOVING_MODE:

		switch( $event ) {

        		case SELECT_MAP_EVENT:
           		case SELECT_COUNTER_EVENT:
                    $this->moveRules->moveUnit($event, $id, $hexagon, $this->turn);
          			break;

 	       	case SELECT_BUTTON_EVENT:

      			$this->selectNextPhase();
				break;
     		}
      	break;


      case COMBAT_SETUP_MODE:

	      	switch( $event ) {

        	  	case SELECT_COUNTER_EVENT:
	           		$this->combatRules->setupCombat($id);

	     			break;

   	   		case SELECT_BUTTON_EVENT:
      			$this->force->undoDefendersWithoutAttackers();
				if ( $this->gameHasCombatResolutionMode == true ) {
					$this->mode = COMBAT_RESOLUTION_MODE;
				} else {
					$this->mode = COMBAT_SETUP_MODE;
				}
	     			break;
      		}
      		break;

    case COMBAT_RESOLUTION_MODE:

        switch ($event) {

            case SELECT_COUNTER_EVENT:

                $this->combatRules->resolveCombat($id);
                if ($this->force->unitsAreBeingEliminated() == true) {
                    $this->force->removeEliminatingUnits();
                }

                if ($this->force->unitsAreRetreating() == true) {
                    $this->force->clearRetreatHexagonList();
                    $this->mode = RETREATING_MODE;
                }
                else {   // check if advancing after eliminated unit
                    if ($this->force->unitsAreAdvancing() == true) {
                        $this->mode = ADVANCING_MODE;
                    }
                }          
               break;

            case SELECT_BUTTON_EVENT:

                $this->selectNextPhase();
                break;
        }
        break;

      case FIRE_COMBAT_SETUP_MODE:

	      	switch( $event ) {

        	  	case SELECT_COUNTER_EVENT:
	           		$this->combatRules->setupFireCombat($id);

	     			break;

   	   		case SELECT_BUTTON_EVENT:
      			$this->force->undoDefendersWithoutAttackers();
				if ( $this->gameHasCombatResolutionMode == true ) {
					$this->mode = COMBAT_RESOLUTION_MODE;
				} else {
					$this->mode = COMBAT_SETUP_MODE;
				}
	     			break;
      		}
      		break;

    case FIRE_COMBAT_RESOLUTION_MODE:

        switch ($event) {

            case SELECT_COUNTER_EVENT:

                $this->combatRules->resolveFireCombat($id);
                if ($this->force->unitsAreBeingEliminated() == true) {
                    $this->force->removeEliminatingUnits();
                }

                if ($this->force->unitsAreRetreating() == true) {
                    $this->force->clearRetreatHexagonList();
                    $this->mode = RETREATING_MODE;
                }
                else {   // check if advancing after eliminated unit
                    if ($this->force->unitsAreAdvancing() == true) {
                        $this->mode = ADVANCING_MODE;
                    }
                } 
               break;

            case SELECT_BUTTON_EVENT:

                $this->selectNextPhase();
                break;
        }
        break;

      case RETREATING_MODE:

 		switch( $event ) {

          		case SELECT_MAP_EVENT:
	          	case SELECT_COUNTER_EVENT:
                    $this->moveRules->retreatUnit($event, $id, $hexagon);
                    if ($this->force->unitsAreRetreating() == false)
                    {
                        if ($this->force->unitsAreAdvancing() == true) {
                            $this->mode = ADVANCING_MODE;
                        } else {	// melee
					        if ( $this->combatModeType == COMBAT_SETUP_MODE ) {
						        if ( $this->gameHasCombatResolutionMode == true ) {
							        $this->mode = COMBAT_RESOLUTION_MODE;
          						} else {
							        $this->mode = COMBAT_SETUP_MODE;
						        }
					        } else { // fire 
						        if ( $this->gameHasCombatResolutionMode == true ) {
							        $this->mode = FIRE_COMBAT_RESOLUTION_MODE;
						        } else {
							        $this->mode = FIRE_COMBAT_SETUP_MODE;
						        }
					        }
                        }
                	}
         		break;
    		}
    		break;

	case ADVANCING_MODE:

        	switch( $event ) {

        	  	case SELECT_MAP_EVENT:
	          	case SELECT_COUNTER_EVENT:
                    $this->moveRules->advanceUnit($event, $id, $hexagon);
                    if ($this->force->unitsAreAdvancing() == false)
			        {	// melee
				        if ( $this->combatModeType == COMBAT_SETUP_MODE ) {
					        if ( $this->gameHasCombatResolutionMode == true ) {
							    $this->mode = COMBAT_RESOLUTION_MODE;
						    } else {
							    $this->mode = COMBAT_SETUP_MODE;
						    }
					    } else {
						    if ( $this->gameHasCombatResolutionMode == true ) {
							    $this->mode = FIRE_COMBAT_RESOLUTION_MODE;
						    } else {
							    $this->mode = FIRE_COMBAT_SETUP_MODE;
						    }
					    }
                    }
                    break;
            }
            break;
    }

    // see who occupies city
    $this->force->checkVictoryConditions();
    if ( $this->force->isForceEliminated() == true )
    {
        $this->mode = GAME_OVER_MODE;
        $this->phase = GAME_OVER_PHASE;
    }
}
 
function selectNextPhase() {

	if ( $this->force->moreCombatToResolve() == false && $this->moveRules->anyUnitIsMoving == false)
	{

		for( $i = 0; $i < count($this->phaseChanges); $i++ ) {

			if ( $this->phaseChanges[$i]->currentPhase == $this->phase )
			{
				$this->phase = $this->phaseChanges[$i]->nextPhase;
				$this->mode = $this->phaseChanges[$i]->nextMode;
				$this->attackingForceId = $this->phaseChanges[$i]->nextAttackerId;
				$this->defendingForceId = $this->phaseChanges[$i]->nextDefenderId;

				if ( $this->phaseChanges[$i]->phaseWillIncrementTurn == true ) {
					$this->incrementTurn();
				}

				$this->force->recoverUnits();
				$this->force->setAttackingForceId($this->attackingForceId);

		        	if ( $this->turn >= $this->maxTurn ) {
      	    				$this->mode = GAME_OVER_MODE;
      	    				$this->phase = GAME_OVER_PHASE;
  	    			}
				break;
			}
		}
    }
}

function incrementTurn() {
    $this->turn++;
}
 
function getInfo( ) {

//	var info;
    global $phase_name, $force_name,$mode_name;
	$info = "turn: " + $this->turn;
	$info += " " + $phase_name[$this->phase];
	$info += " ( " + $force_name[$this->force->getVictorId()];
	if ( $this->turn < $this->maxTurn ) {
		$info +=  " is winning )";
	} else {
		$info +=  " wins! )";
	}
	$info += "<br />&nbsp; " + $mode_name[$this->mode];
	$info += "<br />last force to occupy Marysville wins";

	return $info;
}

}