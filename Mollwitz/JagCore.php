<?php
require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;

require_once "combatRules.php";
require_once "crt.php";
require_once "force.php";
require_once "gameRules.php";
require_once "hexagon.php";
require_once "hexpart.php";
require_once "los.php";
require_once "mapgrid.php";
require_once "moveRules.php";
require_once "prompt.php";
require_once "display.php";
require_once "terrain.php";
require_once "victory.php";



class JagCore extends Battle{

    static function playAs($name, $wargame, $arg = false)
    {
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        @include_once "playMulti.php";
    }

    function poke($event, $id, $x, $y, $user, $click)
    {
        $playerId = $this->gameRules->attackingForceId;
        if ($this->players[$this->gameRules->attackingForceId] != $user) {
            return false;
        }

        switch ($event) {
            case SELECT_MAP_EVENT:
                $mapGrid = new MapGrid($this->mapViewer[$playerId]);
                $mapGrid = new MapGrid($this->mapViewer[$playerId]);
                $mapGrid->setPixels($x, $y);

                $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(), $click);
                break;

            case SELECT_COUNTER_EVENT:
                /* fall through */
            case SELECT_SHIFT_COUNTER_EVENT:
                /* fall through */
            case COMBAT_PIN_EVENT:

                $ret = $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id), $click);
                return $ret;
                break;


            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0, $click);
                break;

            case KEYPRESS_EVENT:
                $this->gameRules->processEvent(KEYPRESS_EVENT, $id, null, $click);
                break;


        }
        return true;
    }

}