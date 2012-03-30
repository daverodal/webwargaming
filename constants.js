// Battle for Allen Creek wargame
// constants.js 

// copyright (c) 2009 Mark Butler
// This program is free software; you can redistribute it 
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version. 

// force data
var NO_FORCE = 0;
var BLUE_FORCE = 1;
var RED_FORCE = 2;

var force_name = new Array;
force_name[0] = "none";
force_name[1] = "BLUE";
force_name[2] = "RED";

// game phases
var BLUE_MOVE_PHASE = 1;
var BLUE_COMBAT_PHASE = 2;
var BLUE_FIRE_COMBAT_PHASE = 3;
var RED_MOVE_PHASE = 4;
var RED_COMBAT_PHASE = 5;
var RED_FIRE_COMBAT_PHASE = 6;
var GAME_OVER_PHASE = 7;

var phase_name = new Array;
phase_name[1] = "Blue Move";
phase_name[2] = "Blue Combat";
phase_name[3] = "Blue Fire Combat";
phase_name[4] = "Red Move";
phase_name[5] = "Red Combat";
phase_name[6] = "Red Fire Combat";
phase_name[7] = "Victory";

// game modes
var SELECT_TO_MOVE_MODE = 1;
var MOVING_MODE = 2;
var COMBAT_SETUP_MODE = 3;
var COMBAT_RESOLUTION_MODE = 4;
var FIRE_COMBAT_SETUP_MODE = 5;
var FIRE_COMBAT_RESOLUTION_MODE = 6;
var SELECT_TO_RETREAT_MODE = 7;
var CHECK_RETREAT_MODE = 8;
var RETREATING_MODE = 9;
var STOP_RETREAT_MODE = 10;
var SELECT_TO_ADVANCE_MODE = 11;
var ADVANCING_MODE = 12;
var SELECT_TO_DELETE_MODE = 13;
var DELETING_MODE = 14;
var CHECK_FOR_COMBAT_MODE = 15;
var GAME_OVER_MODE = 16;

// mode names
var mode_name = new Array;
mode_name[ 1] = "moving mode";
mode_name[ 2] = "moving mode";
mode_name[ 3] = "combat setup mode";
mode_name[ 4] = "combat resolution";
mode_name[ 5] = "fire combat setup mode";
mode_name[ 6] = "fire combat resolution";
mode_name[ 7] = "retreating mode";
mode_name[ 8] = "retreating mode";
mode_name[ 9] = "retreating mode";
mode_name[10] = "retreating mode";
mode_name[11] = "advancing mode";
mode_name[12] = "advancing mode";
mode_name[13] = "select units to delete";
mode_name[14] = "deleting unit";
mode_name[15] = "checking combat";
mode_name[16] = "game over";

// form event constants
var OVER_MAP_EVENT = 1;
var SELECT_MAP_EVENT = 2;
var OVER_COUNTER_EVENT = 3;
var SELECT_COUNTER_EVENT = 4;
var OVER_BUTTON_EVENT = 5;
var SELECT_BUTTON_EVENT = 6;

// event names
var event_name = new Array;
event_name[1] = "over map";
event_name[2] = "select map";
event_name[3] = "over counter";
event_name[4] = "select counter";
event_name[5] = "over button";
event_name[6] = "select button";


// form actions
var NO_ACTION = 0;
var UPDATE_GAME_STATUS_DISPLAY = 1;
var MOVE_COUNTER = 3;
var DELETE_COUNTERS = 4;
var REMOVE_COUNTERS = 5;
var GAME_OVER = 6;

// unit status 
var STATUS_NONE = 0;
var STATUS_READY = 1;
var STATUS_CAN_REINFORCE = 2;
var STATUS_REINFORCING = 3;
var STATUS_MOVING = 4;
var STATUS_NOT_MOVED = 5;
var STATUS_STOPPED = 6;
var STATUS_REMOVED = 7;
var STATUS_DEFENDING = 8;
var STATUS_ATTACKING = 9;
var STATUS_NO_RESULT = 10;
var STATUS_DEFENDED = 11;
var STATUS_ATTACKED = 12;
var STATUS_CAN_RETREAT = 13;
var STATUS_RETREATING = 14;
var STATUS_RETREATED = 15;
var STATUS_CAN_ADVANCE = 16;
var STATUS_ADVANCING = 17;
var STATUS_ADVANCED = 18;
var STATUS_DELETING = 19;
var STATUS_DELETED = 20;
var STATUS_ELIMINATING = 21;
var STATUS_ELIMINATED = 22;
var STATUS_EXITING = 23;
var STATUS_EXITED = 24;
var STATUS_NO_MORE_CRT = 25;
var STATUS_MORE_CRT = 26;

// unit status names
var status_name = new Array;
status_name[ 0] = " none";
status_name[ 1] = " is ready";
status_name[ 2] = " is ready to reinforce";
status_name[ 3] = " is reinforcing";
status_name[ 4] = " is moving";
status_name[ 5] = " not moved";
status_name[ 6] = " has stopped";
status_name[ 7] = " has been removed";
status_name[ 8] = " is defending";
status_name[ 9] = " is attacking";
status_name[10] = " no result";
status_name[11] = " has defended";
status_name[12] = " has attacked";
status_name[13] = " can retreat";
status_name[14] = " is retreating";
status_name[15] = " has retreated";
status_name[16] = " can advance";
status_name[17] = " is advancing";
status_name[18] = " has advanced";
status_name[19] = " id being deleted";
status_name[20] = " is deleted";
status_name[21] = " is eliminating";
status_name[22] = " has been eliminated";
status_name[23] = " is exiting";
status_name[24] = " has exited ";
status_name[25] = " no more CRT to resolve";
status_name[26] = " more CRT to resolve";

// Combat Results Table values
var DE = 0;
var DR = 1;
var NR = 2;
var AR = 3;
var AE = 4;

var results_name = new Array;
//results_name[DE] = "Defender eliminated";
//results_name[DR] = "Defender retreat";
//results_name[NR] = "No result";
//results_name[AR] = "Attacker retreat";
//results_name[AE] = "Attacker eliminated";
results_name[DE] = "DE";
results_name[DR] = "DR";
results_name[NR] = "NR";
results_name[AR] = "AR";
results_name[AE] = "AE";

// combat ratio
var combatRatio_name = new Array;
combatRatio_name[0] = "";
combatRatio_name[1] = " 1 to 2 ";
combatRatio_name[2] = " 1 to 1 ";
combatRatio_name[3] = " 2 to 1 ";
combatRatio_name[4] = " 3 to 1 ";
combatRatio_name[5] = " 4 to 1 ";
combatRatio_name[6] = " greater than 5 to 1 ";

var MAP = -1;
var NONE = -1;

// hexpart types
var HEXAGON_CENTER = 1;
var BOTTOM_HEXSIDE = 2;
var LOWER_LEFT_HEXSIDE = 3;
var UPPER_LEFT_HEXSIDE = 4;
