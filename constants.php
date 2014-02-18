<?php
// Battle for Allen Creek wargame
// constants.js

// copyright (c) 2009 Mark Butler
// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version.

// force data
global $force_name,$phase_name,$mode_name, $event_name, $status_name, $results_name,$combatRatio_name;
define("NO_FORCE",0);
define("BLUE_FORCE",1);
define("RED_FORCE",2);

$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

// game phases
define("BLUE_MOVE_PHASE",1);
define("BLUE_COMBAT_PHASE",2);
define("BLUE_FIRE_COMBAT_PHASE",3);
define("RED_MOVE_PHASE",4);
define("RED_COMBAT_PHASE",5);
define("RED_FIRE_COMBAT_PHASE",6);
define("GAME_OVER_PHASE",7);
define("BLUE_DEPLOY_PHASE",8);
define("BLUE_MECH_PHASE",9);
define("BLUE_REPLACEMENT_PHASE",10);
define("RED_MECH_PHASE",11);
define("RED_REPLACEMENT_PHASE",12);
define("BLUE_DISPLAY_PHASE",13);
define("RED_DISPLAY_PHASE",14);
define("RED_DEPLOY_PHASE",15);
define("BLUE_SURPRISE_MOVE_PHASE",16);


$phase_name = array();
$phase_name[1] = "Rebel Move";
$phase_name[2] = "Rebel Combat";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "Loyalist Move";
$phase_name[5] = "Loyalist Combat";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "Rebel Deploy";
$phase_name[9] = "Rebel Mech";
$phase_name[10] = "Rebel Replacement";
$phase_name[11] = "Loyalist Mech";
$phase_name[12] = "Loyalist Replacement";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "red deploy phase";

// game modes
define("SELECT_TO_MOVE_MODE",1);
define("MOVING_MODE",2);
define("COMBAT_SETUP_MODE",3);
define("COMBAT_RESOLUTION_MODE",4);
define("FIRE_COMBAT_SETUP_MODE",5);
define("FIRE_COMBAT_RESOLUTION_MODE",6);
define("SELECT_TO_RETREAT_MODE",7);
define("CHECK_RETREAT_MODE",8);
define("RETREATING_MODE",9);
define("STOP_RETREAT_MODE",10);
define("SELECT_TO_ADVANCE_MODE",11);
define("ADVANCING_MODE",12);
define("SELECT_TO_DELETE_MODE",13);
define("DELETING_MODE",14);
define("CHECK_FOR_COMBAT_MODE",15);
define("GAME_OVER_MODE",16);
define("DEPLOY_MODE",17);
define("EXCHANGING_MODE",18);
define("REPLACING_MODE",19);
define("ATTACKER_LOSING_MODE",20);
define("DISPLAY_MODE",21);


// mode names
$mode_name = array();
$mode_name[ 1] = "moving mode";
$mode_name[ 2] = "moving mode";
$mode_name[ 3] = "combat setup mode";
$mode_name[ 4] = "combat resolution";
$mode_name[ 5] = "fire combat setup mode";
$mode_name[ 6] = "fire combat resolution";
$mode_name[ 7] = "retreating mode";
$mode_name[ 8] = "retreating mode";
$mode_name[ 9] = "retreating mode";
$mode_name[10] = "retreating mode";
$mode_name[11] = "advancing mode";
$mode_name[12] = "advancing mode";
$mode_name[13] = "select units to delete";
$mode_name[14] = "deleting unit";
$mode_name[15] = "checking combat";
$mode_name[16] = "game over";
$mode_name[17] = "click on a unit in the deploy box";
$mode_name[18] = "exchanging mode";
$mode_name[19] = "replacing mode";
$mode_name[20] = "attacker loss mode";
$mode_name[21] = "";

// form event constants
define("OVER_MAP_EVENT",1);
define("SELECT_MAP_EVENT",2);
define("OVER_COUNTER_EVENT",3);
define("SELECT_COUNTER_EVENT",4);
define("OVER_BUTTON_EVENT",5);
define("SELECT_BUTTON_EVENT",6);
define("SELECT_SHIFT_COUNTER_EVENT",7);
define("KEYPRESS_EVENT",8);
define("COMBAT_PIN_EVENT",9);

// event names
$event_name = array();
$event_name[1] = "over map";
$event_name[2] = "select map";
$event_name[3] = "over counter";
$event_name[4] = "select counter";
$event_name[5] = "over button";
$event_name[6] = "select button";


// form actions
define("NO_ACTION",0);
define("UPDATE_GAME_STATUS_DISPLAY",1);
define("MOVE_COUNTER",3);
define("DELETE_COUNTERS",4);
define("REMOVE_COUNTERS",5);
define("GAME_OVER",6);

// unit status
define("STATUS_NONE",0);
define("STATUS_READY",1);
define("STATUS_CAN_REINFORCE",2);
define("STATUS_REINFORCING",3);
define("STATUS_MOVING",4);
define("STATUS_STOPPED",6);
define("STATUS_DEFENDING",8);
define("STATUS_ATTACKING",9);
define("STATUS_NO_RESULT",10);
define("STATUS_DEFENDED",11);
define("STATUS_ATTACKED",12);
define("STATUS_CAN_RETREAT",13);
define("STATUS_RETREATING",14);
define("STATUS_RETREATED",15);
define("STATUS_CAN_ADVANCE",16);
define("STATUS_ADVANCING",17);
define("STATUS_ADVANCED",18);
define("STATUS_DELETING",19);
define("STATUS_ELIMINATING",21);
define("STATUS_ELIMINATED",22);
define("STATUS_EXITING",23);
define("STATUS_EXITED",24);
define("STATUS_CAN_EXCHANGE",27);
define("STATUS_EXCHANGED",28);
define("STATUS_REPLACED",29);
define("STATUS_CAN_REPLACE",30);
define("STATUS_CAN_UPGRADE",31);
define("STATUS_CAN_ATTACK_LOSE",32);
define("STATUS_BOMBARDING",33);
define("STATUS_UNAVAIL_THIS_PHASE",34);
define("STATUS_CAN_DEPLOY",35);
define("STATUS_DEPLOYING",36);


// unit status names
$status_name = array();
$status_name[ 0] = " none";
$status_name[ 1] = " is ready";
$status_name[ 2] = " is ready to reinforce";
$status_name[ 3] = " is reinforcing";
$status_name[ 4] = " is moving";
$status_name[ 5] = " not moved";
$status_name[ 6] = " has stopped";
$status_name[ 7] = " has been removed";
$status_name[ 8] = " is defending";
$status_name[ 9] = " is attacking";
$status_name[10] = " no result";
$status_name[11] = " has defended";
$status_name[12] = " has attacked";
$status_name[13] = " can retreat";
$status_name[14] = " is retreating";
$status_name[15] = " has retreated";
$status_name[16] = " can advance";
$status_name[17] = " is advancing";
$status_name[18] = " has advanced";
$status_name[19] = " id being deleted";
$status_name[20] = " is deleted";
$status_name[21] = " is eliminating";
$status_name[22] = " has been eliminated";
$status_name[23] = " is exiting";
$status_name[24] = " has exited ";
$status_name[25] = " no more CRT to resolve";
$status_name[26] = " more CRT to resolve";

// Combat Results Table values
define("DE",0);
define("DRL",1);
define("DR",2);
define("EX",3);
define("NE",4);
define("AL",5);
define("AR",6);
define("AE",7);
define("DR2",8);
define("EX2",9);
define("DRL2",10);
define("DL",11);

$results_name = array();
//results_name[DE] = "Defender eliminated";
//results_name[DR] = "Defender retreat";
//results_name[NR] = "No result";
//results_name[AR] = "Attacker retreat";
//results_name[AE] = "Attacker eliminated";
$results_name[DE] = "DE";
$results_name[DRL] = "DRL";
$results_name[DR] = "DR";
$results_name[EX] = "EX";
$results_name[NE] = "NE";
$results_name[AL] = "AL";
$results_name[AR] = "AR";
$results_name[AE] = "AE";
$results_name[DR2] = "DR";
$results_name[EX2] = "EX";
$results_name[DRL2] = "DRL";
$results_name[DL] = "DL";

// combat ratio
$combatRatio_name = array();
$combatRatio_name[0] = "";
$combatRatio_name[1] = " 1 to 2 ";
$combatRatio_name[2] = " 1 to 1 ";
$combatRatio_name[3] = " 2 to 1 ";
$combatRatio_name[4] = " 3 to 1 ";
$combatRatio_name[5] = " 4 to 1 ";
$combatRatio_name[6] = " greater than 5 to 1 ";

define("MAP",false);
define("NONE",-1);

// hexpart types
define("HEXAGON_CENTER",1);
define("BOTTOM_HEXSIDE",2);
define("LOWER_LEFT_HEXSIDE",3);
define("UPPER_LEFT_HEXSIDE",4);

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    if($errno != E_NOTICE){
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    }
}
ini_set('display_errors',1);
set_error_handler('exception_error_handler');