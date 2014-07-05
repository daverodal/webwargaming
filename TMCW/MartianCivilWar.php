<?php

require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";

$phase_name = array();
$phase_name[1] = "<span class='rebelFace'>Rebel</span> Movement Phase";
$phase_name[2] = "<span class='rebelFace'>Rebel</span>";
$phase_name[3] = "Blue Fire Combat";
$phase_name[4] = "<span class='loyalistFace'>Loyalist</span> Movement Phase";
$phase_name[5] = "<span class='loyalistFace'>Loyalist</span>";
$phase_name[6] = "Red Fire Combat";
$phase_name[7] = "Victory";
$phase_name[8] = "<span class='rebelFace'>Rebel</span> Deploy Phase";
$phase_name[9] = "<span class='rebelFace'>Rebel</span> Mech Movement Phase";
$phase_name[10] = "<span class='rebelFace'>Rebel</span> Replacement Phase";
$phase_name[11] = "<span class='loyalistFace'>Loyalist</span> Mech Movement Phase";
$phase_name[12] = "<span class='loyalistFace'>Loyalist</span> Replacement Phase";
$phase_name[13] = "";
$phase_name[14] = "";

$mode_name[3] = "Combat Setup Phase";
$mode_name[4] = "Combat Resolution Phase";
$mode_name[19] = "";

$mode_name[1] = "";
$mode_name[2] = "";
require_once "ModernLandBattle.php";
define("REBEL_FORCE", BLUE_FORCE);
define("LOYALIST_FORCE", RED_FORCE);

// battlefforallencreek.js

// counter image values
$oneHalfImageWidth = 16;
$oneHalfImageHeight = 16;


class MartianCivilWar extends ModernLandBattle
{
    /* a comment */

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    public $playerData;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;
    public $display;
    public $victory;
    public $genTerrain = false;
    public $arg;
    public $scenario;

    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        $playerData = array_shift($playerData);
        foreach ($playerData as $k => $v) {
            $$k = $v;
        }
        @include_once "globalHeader.php";
        @include_once "tmcwHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "Prussian";
        $deployOne = $playerTwo = "Austrian";
        @include_once "view.php";
    }

    static function playAs($name, $wargame, $arg = false)
    {

        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        @include_once "playMulti.php";
    }

    function terrainInit($terrainName){
        parent::terrainInit($this->terrainName);
    }

    function terrainGen($hexDocId){
        $this->terrainName = "terrain-".get_class($this);
        if($this->scenario->hardCuneiform){
            $this->terrainName = "terrain-siegeofcuneiform";
        }
        parent::terrainGen($hexDocId);
    }

    function save()
    {
        $data = new stdClass();
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->playerData = $this->playerData;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        $data->genTerrain = $this->genTerrain;
        if ($this->genTerrain) {
            $data->terrain = $this->terrain;
        }
        return $data;
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
                $mapGrid->setPixels($x, $y);
                return $this->gameRules->processEvent(SELECT_MAP_EVENT, MAP, $mapGrid->getHexagon(), $click);
                break;

            case SELECT_COUNTER_EVENT:
                /* fall through */
            case SELECT_SHIFT_COUNTER_EVENT:
                return $this->gameRules->processEvent($event, $id, $this->force->getUnitHexagon($id), $click);

                break;

            case SELECT_BUTTON_EVENT:
                $this->gameRules->processEvent(SELECT_BUTTON_EVENT, "next_phase", 0, $click);

        }
        return true;
    }

    public function oldInit()
    {

        $scenario = $this->scenario;

        $this->force->addUnit("xx", RED_FORCE, 407, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 516, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 1515, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 1612, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 1316, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 2207, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 2210, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 208, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 508, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 512, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 1909, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 914, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');

//
//            for($i = 1;$i<= 10;$i+=2){
//                $this->force->addUnit("xx", RED_FORCE, 500+$i, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist");
//
//            }

        for ($i = 7; $i <= 10; $i += 2) {
            $this->force->addUnit("xx", RED_FORCE, 1000 + $i, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist");

        }

        $loyalMechMove = 6;
        if (isset($scenario->loyalMechMove)) {
            $loyalMechMove = $scenario->loyalMechMove;
        }
        $this->force->addUnit("xx", RED_FORCE, 2415, "multiRecon.png", 5, 2, 9, false, STATUS_READY, "B", 1, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, 2416, "multiRecon.png", 5, 2, 9, false, STATUS_READY, "B", 1, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, 2417, "multiRecon.png", 5, 2, 9, false, STATUS_READY, "B", 1, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, 2515, "multiMech.png", 9, 4, $loyalMechMove, true, STATUS_READY, "B", 1, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, 2516, "multiArmor.png", 7, 3, $loyalMechMove, true, STATUS_READY, "B", 1, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, 2517, "multiArmor.png", 7, 3, $loyalMechMove, true, STATUS_READY, "B", 1, 1, "loyalist", true, "mech");

        $bigLoyalist = false;
        if ($scenario && $scenario->bigLoyal) {
            $bigLoyalist = true;
        }
        if (isset($scenario->loyalExtraInf)) {
            $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
            $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
            $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
            $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
        }
        $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiArmor.png", 7, 3, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiArmor.png", 7, 3, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, "gameTurn3", "multiMech.png", 9, 4, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, "gameTurn3", "multiMech.png", 9, 4, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, "gameTurn3", "multiMech.png", 9, 4, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, "gameTurn4", "multiMech.png", 9, 4, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, "gameTurn4", "multiMech.png", 9, 4, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, "gameTurn5", "multiArmor.png", 7, 3, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, "mech");
        $this->force->addUnit("xx", RED_FORCE, "gameTurn5", "multiArmor.png", 7, 3, $loyalMechMove, !$bigLoyalist, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, "mech");

        if ($bigLoyalist) {
            $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiRecon.png", 5, 2, 9, $bigLoyalist, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, "mech");
            $this->force->addUnit("xx", RED_FORCE, "gameTurn4", "multiRecon.png", 5, 2, 9, $bigLoyalist, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist", true, "mech");
            $this->force->addUnit("xx", RED_FORCE, "gameTurn4", "multiRecon.png", 5, 2, 9, $bigLoyalist, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist", true, "mech");
            $this->force->addUnit("xx", RED_FORCE, "gameTurn3", "multiRecon.png", 5, 2, 9, $bigLoyalist, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist", true, "mech");
            $this->force->addUnit("xx", RED_FORCE, "gameTurn5", "multiRecon.png", 5, 2, 9, $bigLoyalist, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, "mech");
        }

//            $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist");
//            $this->force->addUnit("xx", RED_FORCE, "gameTurn2", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist");
//            $this->force->addUnit("xx", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist");
//            $this->force->addUnit("xx", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist");
//            $this->force->addUnit("xx", RED_FORCE, "gameTurn3", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist");
//            $this->force->addUnit("xx", RED_FORCE, "gameTurn4", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist");
//            $this->force->addUnit("xx", RED_FORCE, "gameTurn4", "multiMech.png",9, 4, 6, true, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist");
//            $this->force->addUnit("xx", RED_FORCE, "gameTurn5", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist");
//            $this->force->addUnit("xx", RED_FORCE, "gameTurn5", "multiArmor.png",7, 3, 6, true, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist");


        $i = 1;
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $j = $i;
        $i = 0;
        $j = 11;
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");


    }

    public function init()
    {

        $scenario = $this->scenario;
        if(!$scenario->hardCuneiform){
            $this->oldInit();
            return;
        }

        $this->force->addUnit("xx", LOYALIST_FORCE, 407, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 516, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1515, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1612, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1316, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1805, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1706, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 208, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 608, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 512, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1909, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 914, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1505, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 2411, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1704, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", LOYALIST_FORCE, 1803, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xx", RED_FORCE, 909, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist");
        $this->force->addUnit("xx", RED_FORCE, 1007, "multiInf.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist");

        $this->force->addUnit("xxx", LOYALIST_FORCE, 2415, "multiHeavy.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", LOYALIST_FORCE, 2416, "multiHeavy.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", LOYALIST_FORCE, 2417, "multiHeavy.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", LOYALIST_FORCE, 2515, "multiHeavy.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", LOYALIST_FORCE, 2516, "multiHeavy.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", LOYALIST_FORCE, 2517, "multiHeavy.png", 2, 1, 4, true, STATUS_READY, "B", 1, 1, "loyalist", true, 'heavy');


        $this->force->addUnit("xxx", RED_FORCE, "gameTurn2", "multiHeavy.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn2", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 2, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn3", "multiHeavy.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn3", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 3, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn4", "multiHeavy.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn4", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 4, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn5", "multiHeavy.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn5", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 5, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn6", "multiHeavy.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn6", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 6, 1, "loyalist", true, 'inf');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn7", "multiHeavy.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 7, 1, "loyalist", true, 'heavy');
        $this->force->addUnit("xxx", RED_FORCE, "gameTurn7", "multiInf.png", 2, 1, 4, false, STATUS_CAN_REINFORCE, "B", 7, 1, "loyalist", true, 'inf');



        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiArmor.png", 6, 3, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMech.png", 4, 2, 8, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mech");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMountain.png", 3, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mountain");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiMountain.png", 3, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "mountain");

        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");
        $this->force->addUnit("xx", BLUE_FORCE, "deployBox", "multiInf.png", 2, 1, 5, false, STATUS_CAN_REINFORCE, "A", 1, 1, "rebel", true, "inf");


    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->genTerrain = false;
            $this->victory = new Victory("TMCW", $data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = $data->players;
            $this->playerData = $data->playerData;
            $this->terrainName = $data->terrainName;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->genTerrain = true;
            $this->terrainName = "terrain-MartianCivilWar";
            $this->victory = new Victory("TMCW");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->display = new Display();
//            if($scenario->hardCuneiform){
//                $this->mapData->setData(30, 20, "js/HardCuneiform.png");
//            }else{
//                $this->mapData->setData(30, 20, "js/MartianIV.png");
//            }

//            if ($scenario && $scenario->supply === true) {
//                $this->mapData->setSpecialHexes(array(407 => RED_FORCE, 1909 => RED_FORCE, 1515 => RED_FORCE, 516 => RED_FORCE,
//                    2414 => RED_FORCE, 2415 => RED_FORCE, 2515 => RED_FORCE, 1508 => RED_FORCE,
//                    2615 => RED_FORCE, 2615 => RED_FORCE, 2716 => RED_FORCE, 2816 => RED_FORCE,
//                    2917 => RED_FORCE, 3017 => RED_FORCE));
//
//            } else {
//                $this->mapData->setSpecialHexes(array(407 => RED_FORCE, 1909 => RED_FORCE, 1515 => RED_FORCE, 516 => RED_FORCE,
//                    2414 => RED_FORCE, 2415 => RED_FORCE, 2515 => RED_FORCE, 1508 => RED_FORCE, 2514 => RED_FORCE, 2614 => RED_FORCE,
//                    2615 => RED_FORCE, 2416 => RED_FORCE, 2516 => RED_FORCE, 2615 => RED_FORCE, 2716 => RED_FORCE, 2816 => RED_FORCE,
//                    2917 => RED_FORCE, 3017 => RED_FORCE));
//
//            }
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
//            $this->terrain->setMaxHex("3020");
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            if ($scenario && $scenario->supply === true) {
                $this->moveRules->enterZoc = 2;
                $this->moveRules->exitZoc = 1;
                $this->moveRules->noZocZocOneHex = true;
            } else {
                $this->moveRules->enterZoc = "stop";
                $this->moveRules->exitZoc = 0;
                $this->moveRules->noZocZocOneHex = false;
            }
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
//            $this->players = array("", "", "");
//            $this->playerData = new stdClass();
//            for ($player = 0; $player <= 2; $player++) {
//                $this->playerData->${player} = new stdClass();
//                $this->playerData->${player}->mapWidth = "auto";
//                $this->playerData->${player}->mapHeight = "auto";
//                $this->playerData->${player}->unitSize = "32px";
//                $this->playerData->${player}->unitFontSize = "12px";
//                $this->playerData->${player}->unitMargin = "-21px";
//            }


            // mapData
            /*            $this->mapData->setData(88,117, // originX, originY
                            40, 40, // top hexagon height, bottom hexagon height
                            24, 48, // hexagon edge width, hexagon center width
                            1410, 1410 // max right hexagon, max bottom hexagon
                        );*/
//            $this->mapData->setData(66,87, // originX, originY
//                30, 30, // top hexagon height, bottom hexagon height
//                18, 36, // hexagon edge width, hexagon center width
//                1410, 1410 // max right hexagon, max bottom hexagon
//            );
//            $this->mapViewer[0]->setData(60, 76, // originX, originY
//                25, 25, // top hexagon height, bottom hexagon height
//                15, 30, // hexagon edge width, hexagon center width
//                3020, 3020 // max right hexagon, max bottom hexagon
//            );
//            $this->mapViewer[1]->setData(60, 76, // originX, originY
//                25, 25, // top hexagon height, bottom hexagon height
//                15, 30, // hexagon edge width, hexagon center width
//                3020, 3020 // max right hexagon, max bottom hexagon
//            );
//            $this->mapViewer[2]->setData(60, 76, // originX, originY
//                25, 25, // top hexagon height, bottom hexagon height
//                15, 30, // hexagon edge width, hexagon center width
//                3020, 3020 // max right hexagon, max bottom hexagon
//            );

            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);
//            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, BLUE_FORCE, RED_FORCE, true);

            // force data
            //$this->force->setEliminationTrayXY(900);

            // unit data -----------------------------------------------
            //  ( name, force, hexagon, image, strength, maxMove, status, reinforceZone, reinforceTurn )
            // end unit data -------------------------------------------

            // unit terrain data----------------------------------------

            // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
//            $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("road", "road", "A", .5, 0, 0, false);
//            $this->terrain->addTerrainFeature("trail", "trail", "A", 1, 0, 0, false);
//            $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
//            $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
//            $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
//            $this->terrain->addTerrainFeature("mountain", "mountain", "g", 3, 0, 2, true);
//            $this->terrain->addTerrainFeature("river", "Martian River", "v", 0, 1, 1, true);
//            $this->terrain->addTerrainFeature("newrichmond", "New Richmond", "m", 0, 0, 1, false);
//            $this->terrain->addTerrainFeature("eastedge", "East Edge", "m", 0, 0, 0, false);
//            $this->terrain->addTerrainFeature("westedge", "West Edge", "m", 0, 0, 0, false);
//            /* handle fort's in crtTraits */
//            $this->terrain->addTerrainFeature("forta", "forta", "f", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("fortb", "fortb", "f", 1, 0, 0, true);
//            $this->terrain->addTerrainFeature("mine", "mine", "m", 0, 0, 0, false);
//            $this->terrain->addNatAltEntranceCost("mine","rebel",'mech',2);
//            $this->terrain->addNatAltEntranceCost("mine","rebel",'inf',1);
//            $this->terrain->addAltEntranceCost("mountain",'mountain',2);
//
//
//            $this->terrain->addAltEntranceCost("rough", "mech", "blocked");

//
//            $deployZones = array(103, 104, 106, 107, 201, 202, 203, 204, 205, 206, 209, 210, 305, 306, 307, 309, 310, 406, 407, 408, 409, 410);
//            for ($i = 1; $i <= 1; $i++) {
//                for ($j = 1; $j <= 10; $j++) {
//                    $this->terrain->addReinforceZone($j * 100 + $i, "A");
//
//                }
//            }
//            for($i = 6;$i <= 10;$i++){
//                    $this->terrain->addReinforceZone(300 + $i,"A");
//
//            }
//            $loyalReinf = array(3016, 3017, 3018, 3019, 3020, 2320, 2420, 2520, 2620, 2720, 2820, 2920);
//            foreach ($loyalReinf as $zone) {
//                $this->terrain->addReinforceZone($zone, "B");
//
//            }
            /*
             * First put clear everywhere, hexes and hex sides
             */
//            for ($col = 100; $col <= 3000; $col += 100) {
//                for ($row = 1; $row <= 20; $row++) {
//                    $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
//                    $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");
//
//                }
//            }

//            $this->terrain->addTerrain(501,UPPER_LEFT_HEXSIDE, "blocked");
//            $this->terrain->addTerrain(501,LOWER_LEFT_HEXSIDE, "blocked");
//            $this->terrain->addTerrain(502,UPPER_LEFT_HEXSIDE, "blocked");
//            $this->terrain->addTerrain(502,LOWER_LEFT_HEXSIDE, "blocked");
//            $this->terrain->addTerrain(503,UPPER_LEFT_HEXSIDE, "blocked");
//            $this->terrain->addTerrain(503,LOWER_LEFT_HEXSIDE, "blocked");
            /*
             * Next put terrain like rough and forest because they are exclusive and will cancel what else is there.
             */
//            if ($scenario->hardCuneiform) {
//                $this->terrain->addTerrain(407 ,1 , "town");
//                $this->terrain->addTerrain(407 ,1 , "road");
//                $this->terrain->addTerrain(1909 ,1 , "town");
//                $this->terrain->addTerrain(1909 ,1 , "road");
//                $this->terrain->addTerrain(1515 ,1 , "town");
//                $this->terrain->addTerrain(1515 ,1 , "road");
//                $this->terrain->addTerrain(516 ,1 , "town");
//                $this->terrain->addTerrain(516 ,1 , "road");
//                $this->terrain->addTerrain(2414 ,1 , "town");
//                $this->terrain->addTerrain(2414 ,1 , "road");
//                $this->terrain->addTerrain(2414 ,1 , "fortb");
//                $this->terrain->addTerrain(2415 ,1 , "town");
//                $this->terrain->addTerrain(2415 ,1 , "fortb");
//                $this->terrain->addTerrain(2515 ,1 , "town");
//                $this->terrain->addTerrain(2515 ,1 , "road");
//                $this->terrain->addTerrain(2515 ,1 , "fortb");
//                $this->terrain->addTerrain(1008 ,1 , "forest");
//                $this->terrain->addTerrain(1009 ,1 , "forest");
//                $this->terrain->addTerrain(1109 ,1 , "forest");
//                $this->terrain->addTerrain(1110 ,1 , "forest");
//                $this->terrain->addTerrain(1208 ,1 , "forest");
//                $this->terrain->addTerrain(1208 ,1 , "trail");
//                $this->terrain->addTerrain(1209 ,1 , "forest");
//                $this->terrain->addTerrain(1308 ,1 , "forest");
//                $this->terrain->addTerrain(1309 ,1 , "forest");
//                $this->terrain->addTerrain(1309 ,1 , "trail");
//                $this->terrain->addTerrain(1310 ,1 , "forest");
//                $this->terrain->addTerrain(1409 ,1 , "forest");
//                $this->terrain->addTerrain(1409 ,1 , "trail");
//                $this->terrain->addTerrain(1408 ,1 , "forest");
//                $this->terrain->addTerrain(1407 ,1 , "forest");
//                $this->terrain->addTerrain(1508 ,1 , "forest");
//                $this->terrain->addTerrain(1509 ,1 , "forest");
//                $this->terrain->addTerrain(1509 ,1 , "trail");
//                $this->terrain->addTerrain(1608 ,1 , "forest");
//                $this->terrain->addTerrain(1608 ,1 , "trail");
//                $this->terrain->addTerrain(1804 ,1 , "forest");
//                $this->terrain->addTerrain(1904 ,1 , "forest");
//                $this->terrain->addTerrain(1903 ,1 , "forest");
//                $this->terrain->addTerrain(2003 ,1 , "forest");
//                $this->terrain->addTerrain(2002 ,1 , "forest");
//                $this->terrain->addTerrain(2102 ,1 , "forest");
//                $this->terrain->addTerrain(2202 ,1 , "forest");
//                $this->terrain->addTerrain(2201 ,1 , "forest");
//                $this->terrain->addTerrain(2301 ,1 , "forest");
//                $this->terrain->addTerrain(715 ,1 , "forest");
//                $this->terrain->addTerrain(615 ,1 , "forest");
//                $this->terrain->addTerrain(616 ,1 , "forest");
//                $this->terrain->addTerrain(517 ,1 , "forest");
//                $this->terrain->addTerrain(518 ,1 , "forest");
//                $this->terrain->addTerrain(418 ,1 , "forest");
//                $this->terrain->addTerrain(319 ,1 , "forest");
//                $this->terrain->addTerrain(219 ,1 , "forest");
//                $this->terrain->addTerrain(220 ,1 , "forest");
//                $this->terrain->addTerrain(414 ,4 , "river");
//                $this->terrain->addTerrain(414 ,3 , "river");
//                $this->terrain->addTerrain(414 ,2 , "river");
//                $this->terrain->addTerrain(515 ,3 , "river");
//                $this->terrain->addTerrain(516 ,4 , "river");
//                $this->terrain->addTerrain(516 ,3 , "river");
//                $this->terrain->addTerrain(516 ,3 , "road");
//                $this->terrain->addTerrain(517 ,4 , "river");
//                $this->terrain->addTerrain(416 ,2 , "river");
//                $this->terrain->addTerrain(417 ,4 , "river");
//                $this->terrain->addTerrain(317 ,2 , "river");
//                $this->terrain->addTerrain(318 ,4 , "river");
//                $this->terrain->addTerrain(217 ,2 , "river");
//                $this->terrain->addTerrain(218 ,4 , "river");
//                $this->terrain->addTerrain(118 ,2 , "river");
//                $this->terrain->addTerrain(118 ,2 , "road");
//                $this->terrain->addTerrain(516 ,2 , "river");
//                $this->terrain->addTerrain(616 ,4 , "river");
//                $this->terrain->addTerrain(615 ,3 , "river");
//                $this->terrain->addTerrain(615 ,4 , "river");
//                $this->terrain->addTerrain(614 ,2 , "river");
//                $this->terrain->addTerrain(715 ,4 , "river");
//                $this->terrain->addTerrain(714 ,2 , "river");
//                $this->terrain->addTerrain(814 ,4 , "river");
//                $this->terrain->addTerrain(813 ,2 , "river");
//                $this->terrain->addTerrain(914 ,4 , "river");
//                $this->terrain->addTerrain(914 ,4 , "road");
//                $this->terrain->addTerrain(913 ,3 , "river");
//                $this->terrain->addTerrain(913 ,4 , "river");
//                $this->terrain->addTerrain(912 ,3 , "river");
//                $this->terrain->addTerrain(912 ,4 , "river");
//                $this->terrain->addTerrain(911 ,3 , "river");
//                $this->terrain->addTerrain(911 ,4 , "river");
//                $this->terrain->addTerrain(1011 ,4 , "river");
//                $this->terrain->addTerrain(1011 ,3 , "river");
//                $this->terrain->addTerrain(1012 ,4 , "river");
//                $this->terrain->addTerrain(1012 ,3 , "river");
//                $this->terrain->addTerrain(1013 ,4 , "river");
//                $this->terrain->addTerrain(1013 ,3 , "river");
//                $this->terrain->addTerrain(1014 ,4 , "river");
//                $this->terrain->addTerrain(1014 ,4 , "road");
//                $this->terrain->addTerrain(914 ,2 , "river");
//                $this->terrain->addTerrain(915 ,4 , "river");
//                $this->terrain->addTerrain(814 ,2 , "river");
//                $this->terrain->addTerrain(814 ,3 , "river");
//                $this->terrain->addTerrain(815 ,4 , "river");
//                $this->terrain->addTerrain(815 ,3 , "river");
//                $this->terrain->addTerrain(815 ,2 , "river");
//                $this->terrain->addTerrain(916 ,4 , "river");
//                $this->terrain->addTerrain(915 ,2 , "river");
//                $this->terrain->addTerrain(1015 ,4 , "river");
//                $this->terrain->addTerrain(1014 ,2 , "river");
//                $this->terrain->addTerrain(1115 ,4 , "river");
//                $this->terrain->addTerrain(1115 ,4 , "road");
//                $this->terrain->addTerrain(1114 ,3 , "river");
//                $this->terrain->addTerrain(1114 ,4 , "river");
//                $this->terrain->addTerrain(1113 ,3 , "river");
//                $this->terrain->addTerrain(1113 ,4 , "river");
//                $this->terrain->addTerrain(1112 ,3 , "river");
//                $this->terrain->addTerrain(1112 ,4 , "river");
//                $this->terrain->addTerrain(1404 ,2 , "river");
//                $this->terrain->addTerrain(1505 ,4 , "river");
//                $this->terrain->addTerrain(1504 ,2 , "river");
//                $this->terrain->addTerrain(1604 ,4 , "river");
//                $this->terrain->addTerrain(1603 ,2 , "river");
//                $this->terrain->addTerrain(1704 ,4 , "river");
//                $this->terrain->addTerrain(1703 ,2 , "river");
//                $this->terrain->addTerrain(1803 ,4 , "river");
//                $this->terrain->addTerrain(1802 ,2 , "river");
//                $this->terrain->addTerrain(1903 ,4 , "river");
//                $this->terrain->addTerrain(1902 ,2 , "river");
//                $this->terrain->addTerrain(2002 ,4 , "river");
//                $this->terrain->addTerrain(2001 ,2 , "river");
//                $this->terrain->addTerrain(2102 ,4 , "river");
//                $this->terrain->addTerrain(2101 ,2 , "river");
//                $this->terrain->addTerrain(2201 ,4 , "river");
//                $this->terrain->addTerrain(1903 ,3 , "river");
//                $this->terrain->addTerrain(1904 ,4 , "river");
//                $this->terrain->addTerrain(1803 ,2 , "river");
//                $this->terrain->addTerrain(1804 ,4 , "river");
//                $this->terrain->addTerrain(1704 ,2 , "river");
//                $this->terrain->addTerrain(1705 ,4 , "river");
//                $this->terrain->addTerrain(1705 ,4 , "trail");
//                $this->terrain->addTerrain(1604 ,2 , "river");
//                $this->terrain->addTerrain(1605 ,4 , "river");
//                $this->terrain->addTerrain(1305 ,1 , "mountain");
//                $this->terrain->addTerrain(1205 ,1 , "mountain");
//                $this->terrain->addTerrain(1106 ,1 , "mountain");
//                $this->terrain->addTerrain(1006 ,1 , "mountain");
//                $this->terrain->addTerrain(907 ,1 , "mountain");
//                $this->terrain->addTerrain(907 ,1 , "trail");
//                $this->terrain->addTerrain(908 ,1 , "mountain");
//                $this->terrain->addTerrain(909 ,1 , "mountain");
//                $this->terrain->addTerrain(910 ,1 , "mountain");
//                $this->terrain->addTerrain(1010 ,1 , "mountain");
//                $this->terrain->addTerrain(1111 ,1 , "mountain");
//                $this->terrain->addTerrain(1210 ,1 , "mountain");
//                $this->terrain->addTerrain(1311 ,1 , "mountain");
//                $this->terrain->addTerrain(1410 ,1 , "mountain");
//                $this->terrain->addTerrain(1511 ,1 , "mountain");
//                $this->terrain->addTerrain(1510 ,1 , "mountain");
//                $this->terrain->addTerrain(1609 ,1 , "mountain");
//                $this->terrain->addTerrain(1709 ,1 , "mountain");
//                $this->terrain->addTerrain(1708 ,1 , "mountain");
//                $this->terrain->addTerrain(1708 ,1 , "trail");
//                $this->terrain->addTerrain(1707 ,1 , "mountain");
//                $this->terrain->addTerrain(1607 ,1 , "mountain");
//                $this->terrain->addTerrain(1507 ,1 , "mountain");
//                $this->terrain->addTerrain(1506 ,1 , "mountain");
//                $this->terrain->addTerrain(1406 ,1 , "mountain");
//                $this->terrain->addTerrain(1307 ,1 , "mountain");
//                $this->terrain->addTerrain(1207 ,1 , "mountain");
//                $this->terrain->addTerrain(1108 ,1 , "mountain");
//                $this->terrain->addTerrain(1108 ,1 , "trail");
//                $this->terrain->addTerrain(1107 ,1 , "mountain");
//                $this->terrain->addTerrain(1107 ,1 , "trail");
//                $this->terrain->addTerrain(1007 ,1 , "mountain");
//                $this->terrain->addTerrain(1007 ,1 , "trail");
//                $this->terrain->addTerrain(401 ,1 , "road");
//                $this->terrain->addTerrain(401 ,2 , "road");
//                $this->terrain->addTerrain(402 ,1 , "road");
//                $this->terrain->addTerrain(402 ,2 , "road");
//                $this->terrain->addTerrain(403 ,1 , "road");
//                $this->terrain->addTerrain(403 ,2 , "road");
//                $this->terrain->addTerrain(404 ,1 , "road");
//                $this->terrain->addTerrain(404 ,2 , "road");
//                $this->terrain->addTerrain(405 ,1 , "road");
//                $this->terrain->addTerrain(405 ,2 , "road");
//                $this->terrain->addTerrain(406 ,1 , "road");
//                $this->terrain->addTerrain(406 ,2 , "road");
//                $this->terrain->addTerrain(407 ,2 , "road");
//                $this->terrain->addTerrain(408 ,1 , "road");
//                $this->terrain->addTerrain(408 ,2 , "road");
//                $this->terrain->addTerrain(409 ,1 , "road");
//                $this->terrain->addTerrain(409 ,2 , "road");
//                $this->terrain->addTerrain(410 ,1 , "road");
//                $this->terrain->addTerrain(410 ,2 , "road");
//                $this->terrain->addTerrain(411 ,1 , "road");
//                $this->terrain->addTerrain(512 ,4 , "road");
//                $this->terrain->addTerrain(512 ,1 , "road");
//                $this->terrain->addTerrain(512 ,2 , "road");
//                $this->terrain->addTerrain(513 ,1 , "road");
//                $this->terrain->addTerrain(513 ,2 , "road");
//                $this->terrain->addTerrain(514 ,1 , "road");
//                $this->terrain->addTerrain(514 ,2 , "road");
//                $this->terrain->addTerrain(515 ,1 , "road");
//                $this->terrain->addTerrain(515 ,2 , "road");
//                $this->terrain->addTerrain(416 ,1 , "road");
//                $this->terrain->addTerrain(416 ,3 , "road");
//                $this->terrain->addTerrain(317 ,1 , "road");
//                $this->terrain->addTerrain(317 ,3 , "road");
//                $this->terrain->addTerrain(217 ,1 , "road");
//                $this->terrain->addTerrain(217 ,3 , "road");
//                $this->terrain->addTerrain(118 ,1 , "road");
//                $this->terrain->addTerrain(119 ,1 , "road");
//                $this->terrain->addTerrain(119 ,2 , "road");
//                $this->terrain->addTerrain(120 ,1 , "road");
//                $this->terrain->addTerrain(612 ,4 , "road");
//                $this->terrain->addTerrain(612 ,1 , "road");
//                $this->terrain->addTerrain(713 ,4 , "road");
//                $this->terrain->addTerrain(713 ,1 , "road");
//                $this->terrain->addTerrain(813 ,4 , "road");
//                $this->terrain->addTerrain(813 ,1 , "road");
//                $this->terrain->addTerrain(914 ,1 , "road");
//                $this->terrain->addTerrain(1014 ,1 , "road");
//                $this->terrain->addTerrain(1115 ,1 , "road");
//                $this->terrain->addTerrain(1215 ,4 , "road");
//                $this->terrain->addTerrain(1215 ,1 , "road");
//                $this->terrain->addTerrain(1316 ,4 , "road");
//                $this->terrain->addTerrain(1316 ,1 , "road");
//                $this->terrain->addTerrain(1316 ,1 , "forta");
//                $this->terrain->addTerrain(1415 ,3 , "road");
//                $this->terrain->addTerrain(1415 ,1 , "road");
//                $this->terrain->addTerrain(1515 ,3 , "road");
//                $this->terrain->addTerrain(1614 ,3 , "road");
//                $this->terrain->addTerrain(1614 ,1 , "road");
//                $this->terrain->addTerrain(1613 ,2 , "road");
//                $this->terrain->addTerrain(1613 ,1 , "road");
//                $this->terrain->addTerrain(1612 ,2 , "road");
//                $this->terrain->addTerrain(1611 ,2 , "road");
//                $this->terrain->addTerrain(1612 ,1 , "road");
//                $this->terrain->addTerrain(1611 ,1 , "road");
//                $this->terrain->addTerrain(1610 ,2 , "road");
//                $this->terrain->addTerrain(1610 ,1 , "road");
//                $this->terrain->addTerrain(1710 ,3 , "road");
//                $this->terrain->addTerrain(1710 ,1 , "road");
//                $this->terrain->addTerrain(1809 ,3 , "road");
//                $this->terrain->addTerrain(1809 ,1 , "road");
//                $this->terrain->addTerrain(1909 ,3 , "road");
//                $this->terrain->addTerrain(2009 ,4 , "road");
//                $this->terrain->addTerrain(2009 ,1 , "road");
//                $this->terrain->addTerrain(2110 ,4 , "road");
//                $this->terrain->addTerrain(2110 ,1 , "road");
//                $this->terrain->addTerrain(2210 ,4 , "road");
//                $this->terrain->addTerrain(2210 ,1 , "road");
//                $this->terrain->addTerrain(2311 ,4 , "road");
//                $this->terrain->addTerrain(2311 ,1 , "road");
//                $this->terrain->addTerrain(2411 ,4 , "mine");
//                $this->terrain->addTerrain(2411 ,4 , "minedroad");
//                $this->terrain->addTerrain(2411 ,1 , "road");
//                $this->terrain->addTerrain(2411 ,1 , "forta");
//                $this->terrain->addTerrain(2411 ,2 , "road");
//                $this->terrain->addTerrain(2412 ,1 , "road");
//                $this->terrain->addTerrain(2412 ,2 , "road");
//                $this->terrain->addTerrain(2413 ,1 , "road");
//                $this->terrain->addTerrain(2413 ,1 , "fortb");
//                $this->terrain->addTerrain(2413 ,2 , "road");
//                $this->terrain->addTerrain(2413 ,3 , "road");
//                $this->terrain->addTerrain(2314 ,1 , "road");
//                $this->terrain->addTerrain(2314 ,1 , "fortb");
//                $this->terrain->addTerrain(2314 ,2 , "road");
//                $this->terrain->addTerrain(2315 ,1 , "road");
//                $this->terrain->addTerrain(2315 ,1 , "fortb");
//                $this->terrain->addTerrain(2315 ,2 , "road");
//                $this->terrain->addTerrain(2316 ,1 , "road");
//                $this->terrain->addTerrain(2316 ,1 , "fortb");
//                $this->terrain->addTerrain(2416 ,4 , "road");
//                $this->terrain->addTerrain(2416 ,1 , "road");
//                $this->terrain->addTerrain(2416 ,1 , "fortb");
//                $this->terrain->addTerrain(2516 ,3 , "road");
//                $this->terrain->addTerrain(2516 ,1 , "road");
//                $this->terrain->addTerrain(2615 ,3 , "road");
//                $this->terrain->addTerrain(2615 ,1 , "road");
//                $this->terrain->addTerrain(2614 ,2 , "road");
//                $this->terrain->addTerrain(2614 ,1 , "road");
//                $this->terrain->addTerrain(2614 ,4 , "road");
//                $this->terrain->addTerrain(2514 ,1 , "road");
//                $this->terrain->addTerrain(2514 ,1 , "fortb");
//                $this->terrain->addTerrain(2514 ,4 , "road");
//                $this->terrain->addTerrain(2716 ,4 , "road");
//                $this->terrain->addTerrain(2615 ,4 , "road");
//                $this->terrain->addTerrain(2515 ,4 , "road");
//                $this->terrain->addTerrain(2414 ,4 , "road");
//                $this->terrain->addTerrain(2716 ,1 , "road");
//                $this->terrain->addTerrain(2816 ,4 , "road");
//                $this->terrain->addTerrain(2816 ,1 , "road");
//                $this->terrain->addTerrain(2917 ,4 , "road");
//                $this->terrain->addTerrain(2917 ,1 , "road");
//                $this->terrain->addTerrain(3017 ,4 , "road");
//                $this->terrain->addTerrain(3017 ,1 , "road");
//                $this->terrain->addTerrain(2314 ,4 , "road");
//                $this->terrain->addTerrain(2213 ,1 , "road");
//                $this->terrain->addTerrain(2213 ,4 , "road");
//                $this->terrain->addTerrain(2113 ,1 , "road");
//                $this->terrain->addTerrain(2113 ,1 , "forta");
//                $this->terrain->addTerrain(2113 ,4 , "mine");
//                $this->terrain->addTerrain(2113 ,4 , "minedroad");
//                $this->terrain->addTerrain(2012 ,1 , "road");
//                $this->terrain->addTerrain(2012 ,3 , "road");
//                $this->terrain->addTerrain(1913 ,1 , "road");
//                $this->terrain->addTerrain(1913 ,3 , "road");
//                $this->terrain->addTerrain(1813 ,1 , "road");
//                $this->terrain->addTerrain(1813 ,3 , "road");
//                $this->terrain->addTerrain(1714 ,1 , "road");
//                $this->terrain->addTerrain(1714 ,3 , "road");
//                $this->terrain->addTerrain(2120 ,3 , "mine");
//                $this->terrain->addTerrain(2120 ,4 , "mine");
//                $this->terrain->addTerrain(2119 ,3 , "mine");
//                $this->terrain->addTerrain(2119 ,4 , "mine");
//                $this->terrain->addTerrain(2118 ,3 , "mine");
//                $this->terrain->addTerrain(2118 ,4 , "mine");
//                $this->terrain->addTerrain(2117 ,3 , "mine");
//                $this->terrain->addTerrain(2117 ,4 , "mine");
//                $this->terrain->addTerrain(2116 ,3 , "mine");
//                $this->terrain->addTerrain(2116 ,4 , "mine");
//                $this->terrain->addTerrain(2115 ,3 , "mine");
//                $this->terrain->addTerrain(2115 ,4 , "mine");
//                $this->terrain->addTerrain(2114 ,3 , "mine");
//                $this->terrain->addTerrain(2114 ,4 , "mine");
//                $this->terrain->addTerrain(2113 ,3 , "mine");
//                $this->terrain->addTerrain(2112 ,2 , "mine");
//                $this->terrain->addTerrain(2212 ,4 , "mine");
//                $this->terrain->addTerrain(2211 ,2 , "mine");
//                $this->terrain->addTerrain(2312 ,4 , "mine");
//                $this->terrain->addTerrain(2311 ,2 , "mine");
//                $this->terrain->addTerrain(2410 ,2 , "mine");
//                $this->terrain->addTerrain(2511 ,4 , "mine");
//                $this->terrain->addTerrain(2510 ,2 , "mine");
//                $this->terrain->addTerrain(2610 ,4 , "mine");
//                $this->terrain->addTerrain(2609 ,2 , "mine");
//                $this->terrain->addTerrain(2710 ,4 , "mine");
//                $this->terrain->addTerrain(2709 ,2 , "mine");
//                $this->terrain->addTerrain(2809 ,4 , "mine");
//                $this->terrain->addTerrain(2808 ,2 , "mine");
//                $this->terrain->addTerrain(2909 ,4 , "mine");
//                $this->terrain->addTerrain(2908 ,2 , "mine");
//                $this->terrain->addTerrain(3008 ,4 , "mine");
//                $this->terrain->addTerrain(3007 ,2 , "mine");
//                $this->terrain->addTerrain(1320 ,1 , "forta");
//                $this->terrain->addTerrain(1319 ,1 , "forta");
//                $this->terrain->addTerrain(1318 ,1 , "forta");
//                $this->terrain->addTerrain(1317 ,1 , "forta");
//                $this->terrain->addTerrain(1315 ,1 , "forta");
//                $this->terrain->addTerrain(1314 ,1 , "forta");
//                $this->terrain->addTerrain(1313 ,1 , "forta");
//                $this->terrain->addTerrain(1312 ,1 , "forta");
//                $this->terrain->addTerrain(1706 ,1 , "forta");
//                $this->terrain->addTerrain(1805 ,1 , "forta");
//                $this->terrain->addTerrain(1805 ,1 , "trail");
//                $this->terrain->addTerrain(2318 ,1 , "fortb");
//                $this->terrain->addTerrain(2317 ,1 , "fortb");
//                $this->terrain->addTerrain(2513 ,1 , "fortb");
//                $this->terrain->addTerrain(2612 ,1 , "fortb");
//                $this->terrain->addTerrain(3008 ,1 , "forta");
//                $this->terrain->addTerrain(2909 ,1 , "forta");
//                $this->terrain->addTerrain(2809 ,1 , "forta");
//                $this->terrain->addTerrain(2710 ,1 , "forta");
//                $this->terrain->addTerrain(2610 ,1 , "forta");
//                $this->terrain->addTerrain(2511 ,1 , "forta");
//                $this->terrain->addTerrain(2312 ,1 , "forta");
//                $this->terrain->addTerrain(2212 ,1 , "forta");
//                $this->terrain->addTerrain(2114 ,1 , "forta");
//                $this->terrain->addTerrain(2115 ,1 , "forta");
//                $this->terrain->addTerrain(2116 ,1 , "forta");
//                $this->terrain->addTerrain(2117 ,1 , "forta");
//                $this->terrain->addTerrain(2118 ,1 , "forta");
//                $this->terrain->addTerrain(2119 ,1 , "forta");
//                $this->terrain->addTerrain(2120 ,1 , "forta");
//                $this->terrain->addTerrain(505 ,4 , "trail");
//                $this->terrain->addTerrain(505 ,1 , "trail");
//                $this->terrain->addTerrain(605 ,4 , "trail");
//                $this->terrain->addTerrain(605 ,1 , "trail");
//                $this->terrain->addTerrain(706 ,4 , "trail");
//                $this->terrain->addTerrain(706 ,1 , "trail");
//                $this->terrain->addTerrain(806 ,4 , "trail");
//                $this->terrain->addTerrain(806 ,1 , "trail");
//                $this->terrain->addTerrain(907 ,4 , "trail");
//                $this->terrain->addTerrain(1007 ,4 , "trail");
//                $this->terrain->addTerrain(1108 ,4 , "trail");
//                $this->terrain->addTerrain(1208 ,4 , "trail");
//                $this->terrain->addTerrain(1309 ,4 , "trail");
//                $this->terrain->addTerrain(1409 ,4 , "trail");
//                $this->terrain->addTerrain(1509 ,3 , "trail");
//                $this->terrain->addTerrain(1608 ,3 , "trail");
//                $this->terrain->addTerrain(1708 ,3 , "trail");
//                $this->terrain->addTerrain(1808 ,1 , "trail");
//                $this->terrain->addTerrain(1909 ,4 , "trail");
//                $this->terrain->addTerrain(1808 ,4 , "trail");
//                $this->terrain->addTerrain(1908 ,2 , "trail");
//                $this->terrain->addTerrain(1908 ,1 , "trail");
//                $this->terrain->addTerrain(1907 ,2 , "trail");
//                $this->terrain->addTerrain(1907 ,1 , "trail");
//                $this->terrain->addTerrain(1906 ,2 , "trail");
//                $this->terrain->addTerrain(1906 ,4 , "trail");
//                $this->terrain->addTerrain(1906 ,1 , "trail");
//                $this->terrain->addTerrain(1805 ,4 , "trail");
//                $this->terrain->addTerrain(1705 ,1 , "trail");
//                $this->terrain->addTerrain(1604 ,1 , "trail");
//                $this->terrain->addTerrain(1604 ,3 , "trail");
//                $this->terrain->addTerrain(1505 ,1 , "trail");
//                $this->terrain->addTerrain(1505 ,3 , "trail");
//                $this->terrain->addTerrain(1405 ,1 , "trail");
//                $this->terrain->addTerrain(1405 ,3 , "trail");
//                $this->terrain->addTerrain(1306 ,1 , "trail");
//                $this->terrain->addTerrain(1306 ,3 , "trail");
//                $this->terrain->addTerrain(1206 ,1 , "trail");
//                $this->terrain->addTerrain(1206 ,3 , "trail");
//                $this->terrain->addTerrain(1107 ,3 , "trail");
//                $this->terrain->addTerrain(2401 ,1 , "road");
//                $this->terrain->addTerrain(2401 ,2 , "road");
//                $this->terrain->addTerrain(2402 ,1 , "road");
//                $this->terrain->addTerrain(2402 ,2 , "road");
//                $this->terrain->addTerrain(2403 ,1 , "road");
//                $this->terrain->addTerrain(2403 ,2 , "road");
//                $this->terrain->addTerrain(2404 ,1 , "road");
//                $this->terrain->addTerrain(2404 ,2 , "road");
//                $this->terrain->addTerrain(2405 ,1 , "road");
//                $this->terrain->addTerrain(2405 ,2 , "road");
//                $this->terrain->addTerrain(2406 ,1 , "road");
//                $this->terrain->addTerrain(2406 ,3 , "road");
//                $this->terrain->addTerrain(2307 ,1 , "road");
//                $this->terrain->addTerrain(2307 ,3 , "road");
//                $this->terrain->addTerrain(2207 ,1 , "road");
//                $this->terrain->addTerrain(2207 ,3 , "road");
//                $this->terrain->addTerrain(2108 ,1 , "road");
//                $this->terrain->addTerrain(2108 ,3 , "road");
//                $this->terrain->addTerrain(2008 ,1 , "road");
//                $this->terrain->addTerrain(2008 ,3 , "road");
//                $this->terrain->addTerrain(2302 ,1 , "forest");
//            } else {
//                $hexes = array(907, 908, 909, 910, 1006, 1007, 1010, 1106, 1107, 1108, 1111, 1205, 1207, 1210,
//                    1305, 1307, 1311, 1406, 1410, 1506, 1507, 1510, 1511, 1607, 1609, 1707, 1708, 1709);
//
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "rough");
//                }
//
//                $hexes = array(219, 220, 319, 418, 517, 518, 615, 616, 715,
//                    1008, 1009, 1109, 1110, 1208, 1209, 1308, 1309, 1310, 1407, 1408, 1409, 1508, 1509, 1608,
//                    1804, 1903, 1904, 2002, 2003, 2102, 2201, 2202, 2301, 2302);
//
//
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "forest");
//                }
//
//                $hexes = array("0407", "0516", 1515, 1909);
//
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "town");
//                }
//                $hexes = array(2414, 2415, 2515);
//
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "newrichmond");
//                }
//
//                for ($i = 3001; $i <= 3020; $i++) {
//                    $this->terrain->addTerrain($i, HEXAGON_CENTER, "eastedge");
//
//                }
//
//                for ($i = 101; $i <= 120; $i++) {
//                    $this->terrain->addTerrain($i, HEXAGON_CENTER, "westedge");
//
//                }
//
//                $this->terrain->addTerrain(813, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(814, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(814, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(814, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(815, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(815, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(815, BOTTOM_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(714, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(715, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(614, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(615, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(615, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(616, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(516, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(516, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(516, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(515, LOWER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(414, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(414, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(414, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(416, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(417, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(317, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(318, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(217, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(218, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(118, BOTTOM_HEXSIDE, "river");
//
//
//                $this->terrain->addTerrain(911, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(911, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(912, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(912, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(913, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(913, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(914, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(914, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(915, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(915, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(916, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1011, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1012, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1012, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1013, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1013, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1014, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1014, BOTTOM_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1112, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1112, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1113, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1113, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1114, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1114, LOWER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1112, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1011, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1404, BOTTOM_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1504, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(1505, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1603, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(1604, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1604, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(1605, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1703, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(1704, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1704, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(1705, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1802, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(1803, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1803, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(1804, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(1902, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(1903, UPPER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1903, LOWER_LEFT_HEXSIDE, "river");
//                $this->terrain->addTerrain(1904, UPPER_LEFT_HEXSIDE, "river");
//
//
//                $this->terrain->addTerrain(2001, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(2002, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(2101, BOTTOM_HEXSIDE, "river");
//                $this->terrain->addTerrain(2102, UPPER_LEFT_HEXSIDE, "river");
//
//                $this->terrain->addTerrain(2201, UPPER_LEFT_HEXSIDE, "river");
//
//                /*
//                 * Now put the roads and trails on top of verything else
//                 */
//                for ($i = 401; $i <= 410; $i++) {
//                    $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");
//
//                }
//                $this->terrain->addTerrain(411, HEXAGON_CENTER, "road");
//
//                $this->terrain->addTerrain(512, UPPER_LEFT_HEXSIDE, "road");
//
//                for ($i = 512; $i <= 515; $i++) {
//                    $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");
//
//                }
//
//                $this->terrain->addTerrain(516, HEXAGON_CENTER, "road");
//                $this->terrain->addTerrain(516, LOWER_LEFT_HEXSIDE, "road");
//                $this->terrain->addTerrain(416, HEXAGON_CENTER, "road");
//                $this->terrain->addTerrain(416, LOWER_LEFT_HEXSIDE, "road");
//                $this->terrain->addTerrain(317, HEXAGON_CENTER, "road");
//                $this->terrain->addTerrain(317, LOWER_LEFT_HEXSIDE, "road");
//                $this->terrain->addTerrain(217, HEXAGON_CENTER, "road");
//                $this->terrain->addTerrain(217, LOWER_LEFT_HEXSIDE, "road");
//                $this->terrain->addTerrain(118, HEXAGON_CENTER, "road");
//                $this->terrain->addTerrain(118, BOTTOM_HEXSIDE, "road");
//                $this->terrain->addTerrain(119, HEXAGON_CENTER, "road");
//                $this->terrain->addTerrain(119, BOTTOM_HEXSIDE, "road");
//                $this->terrain->addTerrain(120, HEXAGON_CENTER, "road");
//                $this->terrain->addTerrain(120, BOTTOM_HEXSIDE, "road");
//
//
//                $hexes = array(612, 713, 813, 914, 1014, 1115, 1215, 1316);
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($hex, UPPER_LEFT_HEXSIDE, "road");
//
//                }
//                $hexes = array(1415, 1515, 1614, 1714, 1813, 1913, 2012, 2615);
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($hex, LOWER_LEFT_HEXSIDE, "road");
//
//                }
//
//                for ($i = 2401; $i <= 2405; $i++) {
//                    $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");
//
//                }
//                $hexes = array(2406, 2307, 2207, 2108, 2008, 1909, 1809, 1710,
//                    2516, 2413);
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($hex, LOWER_LEFT_HEXSIDE, "road");
//                }
//                for ($i = 1610; $i <= 1614; $i++) {
//                    $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");
//
//                }
//
//                for ($i = 2411; $i <= 2413; $i++) {
//                    $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");
//
//                }
//
//                $hexes = array(2009, 2110, 2210, 2311, 2411, 2113, 2213, 2214, 2414, 2515, 2616, 2716, 2816, 2917, 3017
//                , 2314, 2514, 2614, 2416, 2615);
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($hex, UPPER_LEFT_HEXSIDE, "road");
//                }
//                $this->terrain->addTerrain(2614, BOTTOM_HEXSIDE, "road");
//
//                for ($i = 2314; $i <= 2315; $i++) {
//                    $this->terrain->addTerrain($i, HEXAGON_CENTER, "road");
//                    $this->terrain->addTerrain($i, BOTTOM_HEXSIDE, "road");
//
//                }
//                $this->terrain->addTerrain(2316, HEXAGON_CENTER, "road");
//
//                $hexes = array(505, 605, 706, 806, 907, 1007, 1108, 1208, 1309, 1409, 1808, 1909);
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "trail");
//                    $this->terrain->addTerrain($hex, UPPER_LEFT_HEXSIDE, "trail");
//
//                }
//                $hexes = array(1509, 1608, 1708);
//                foreach ($hexes as $hex) {
//                    $this->terrain->addTerrain($hex, HEXAGON_CENTER, "trail");
//                    $this->terrain->addTerrain($hex, LOWER_LEFT_HEXSIDE, "trail");
//
//                }
//            }

            // end terrain data ----------------------------------------
        }
    }
}