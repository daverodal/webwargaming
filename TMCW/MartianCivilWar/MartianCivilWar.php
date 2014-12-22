<?php
define("REBEL_FORCE", 1);
define("LOYALIST_FORCE", 2);

global $force_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Rebel";
$force_name[2] = "Loyalist";
require_once "constants.php";

global $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;

require_once "ModernLandBattle.php";


class MartianCivilWar extends ModernLandBattle
{
    /* a comment */

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
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
            $this->terrainName = "terrain-SiegeOfCuneiform";
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
            $this->victory = new Victory("TMCW/MartianCivilWar", $data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->combatRules->crt->aggressorId = REBEL_FORCE;
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = $data->players;
            $this->terrainName = $data->terrainName;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->genTerrain = true;
            $this->terrainName = "terrain-MartianCivilWar";
            if($this->scenario->hardCuneiform){
                $this->terrainName = "terrain-SiegeOfCuneiform";
            }
            $this->victory = new Victory("TMCW/MartianCivilWar");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->display = new Display();

            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
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
            $this->combatRules->crt->aggressorId = REBEL_FORCE;
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);


            // game data
            $this->gameRules->setMaxTurn(7);
            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);
            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, BLUE_FORCE, RED_FORCE, true);
        }
    }
}