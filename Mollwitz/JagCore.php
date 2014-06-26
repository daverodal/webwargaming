<?php
require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;

require_once "Battle.php";
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



class JagCore extends LandBattle{
//
//    function terrainInit($terrainName){
//
//        $CI =& get_instance();
//        $terrainDoc = $CI->couchsag->get($terrainName);
//        $terrainInfo = $terrainDoc->terrain;
//
//        $oldMapUrl = $this->mapData->mapUrl;
//        if(!$oldMapUrl){
//            $maxCol = $terrainInfo->maxCol;
//            $maxRow = $terrainInfo->maxRow;
//            $mapUrl = $terrainInfo->mapUrl;
//            $this->mapData->setData($maxCol, $maxRow, $mapUrl);
//
//            Hexagon::setMinMax();
//            $this->terrain->setMaxHex();
//        }
//        $this->genTerrain = false;
//        return;
//    }
}