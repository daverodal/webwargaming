<?php
set_include_path(__DIR__ . "/Zorndorf". PATH_SEPARATOR .  get_include_path());

define("PRUSSIAN_FORCE", BLUE_FORCE);
define("RUSSIAN_FORCE", RED_FORCE);

global $force_name;
$force_name = array();
$force_name[1] = "Prussian";
$force_name[2] = "Russian";

require_once "JagCore.php";

class Zorndorf extends JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>2, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;
    public $display;
    public $victory;


    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "ZorndorfHeader.php";
    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "Prussian";
        $deployOne = $playerTwo = "Russian";
        @include_once "view.php";
    }

    function terrainGen($mapDoc, $terrainDoc){
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 2, 0, false);
    }

    function save()
    {
        $data = new stdClass();
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
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;

        return $data;
    }
    function init(){

        $artRange = 3;

        $coinFlip = floor(2 * (rand() / getrandmax()));

        $pruDeploy = $coinFlip == 1 ? "B": "C";

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'infantry');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, $artRange, "Prussian", false, 'artillery');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, $artRange, "Prussian", false, 'artillery');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');

        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');
        $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, $pruDeploy, 1, 1, "Prussian", false, 'cavalry');






        if($this->scenario->bigRussian){
            for($i = 0;$i < 15;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            }
            for($i = 0;$i < 10;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');

            }
            for($i = 0;$i < 7;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            }
            for($i = 0;$i < 5;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Russian", false, 'artillery');
            }
            for($i = 0;$i < 6;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            }
            for($i = 0;$i < 5;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            }
            for($i = 0;$i < 4;$i++){
                $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            }
        }else{
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png", 2, 2, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'infantry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Russian", false, 'artillery');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Russian", false, 'artillery');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Russian", false, 'artillery');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Russian", false, 'artillery');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Russian", false, 'artillery');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 4, 4, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');

            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
            $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian", false, 'cavalry');
        }

    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->terrainName = $data->terrainName;
            $this->game = $data->game;
            $this->victory = new Victory("Mollwitz/Zorndorf/zorndorfVictoryCore.php", $data);
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
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;
            $this->victory = new Victory("Mollwitz/Zorndorf/zorndorfVictoryCore.php");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->mapData->terrain = $this->terrain;
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->stickyZOC = false;
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = array("", "", "");
            // game data


            $this->gameRules->setMaxTurn(15);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}