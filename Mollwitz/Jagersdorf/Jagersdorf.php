<?php
set_include_path(__DIR__ . "/Jagersdorf". PATH_SEPARATOR .  get_include_path());

define("PRUSSIAN_FORCE",1);
define("RUSSIAN_FORCE",2);

global $force_name;
$force_name[1] = "Prussian";
$force_name[2] = "Russian";

require_once "JagCore.php";

class Jagersdorf extends JagCore {

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;


    public $players;
    static function getHeader($name, $playerData, $arg = false){
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "jagerHeader.php";
    }

    static function enterMulti(){
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl,$player = 0, $arg = false, $scenario = false, $game = false){
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "Prussian";
        $deployOne = $playerTwo = "Russian";
        @include_once "view.php";
    }

    function terrainGen($mapDoc, $terrainDoc){
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addAltEntranceCost('forest','artillery',3);
        $this->terrain->addAltEntranceCost('forest','cavalry',3);
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


    public function init(){

        $artRange = 3;
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "R", 1, 1, "Russian",false, 'infantry');

        $this->force->addUnit("infantry-1", RED_FORCE, 807, "RusArtBadge.png",4, 4, 3, true, STATUS_READY, "R", 1, $artRange, "Russian",false,'artillery');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "R", 1, $artRange, "Russian",false,'artillery');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "R", 1, $artRange, "Russian",false,'artillery');



        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",4, 4, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",4, 4, 5, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",2, 2, 5, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');

        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');
        $this->force->addUnit("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "RC", 1, 1, "Russian",false,'cavalry');

        if($this->scenario && $this->scenario->prussianDeploy){
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');

            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, $artRange, "Prussian",false,'artillery');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, $artRange, "Prussian",false,'artillery');
            if($this->scenario->extraArt){
                $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, $artRange, "Prussian",false,'artillery');
            }

            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "P", 1, 1, "Prussian",false, 'infantry');

            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "PC", 1, 1, "Prussian",false,'cavalry');
        }else{
            $this->force->addUnit("infantry-1", BLUE_FORCE, 306, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 307, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 405, "PruCavBadge.png", 3, 3, 6, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 406, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 407, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');

            $this->force->addUnit("infantry-1", BLUE_FORCE, 412, "PruArtBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, $artRange, "Prussian",false,'artillery');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 312, "PruArtBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, $artRange, "Prussian",false,'artillery');

            $this->force->addUnit("infantry-1", BLUE_FORCE, 512, "PruInfBadge.png", 5, 5, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 513, "PruInfBadge.png", 5, 5, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 311, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 411, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 413, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 314, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 214, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 114, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 211, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 110, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 210, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');

            $this->force->addUnit("infantry-1", BLUE_FORCE, 115, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 215, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 316, "PruCavBadge.png", 3, 3, 6, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            $this->force->addUnit("infantry-1", BLUE_FORCE, 416, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');

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
            $this->victory = new Victory("Mollwitz/Jagersdorf/jagerVictoryCore.php",$data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]),new MapViewer($data->mapViewer[1]),new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->players = $data->players;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;
            $this->victory = new Victory("Mollwitz/Jagersdorf/jagerVictoryCore.php");

            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(),new MapViewer(),new MapViewer());
            $this->force = new Force();
//            $this->force->combatRequired = true;
            $this->terrain = new Terrain();
//            $this->terrain->setMaxHex("2223");
            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->stickyZOC = false;
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);





            // game data
            $this->gameRules->setMaxTurn(12);

            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE,DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE;/* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE;/* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            /**
             * not not prussian deploy phase for now
             */
            if($scenario->prussianDeploy){
                $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
                $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            }else{
                $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            }

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE,BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // end terrain data ----------------------------------------

        }
    }
}