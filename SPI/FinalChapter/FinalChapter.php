<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

define("EASTERN_FORCE", 1);
define("WESTERN_EMPIRE_FORCE", 3);
define("WESTERN_FORCE", 2);
define("EASTERN_EMPIRE_FORCE", 4);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "Eastern";
$force_name[2] = "Western";
$force_name[3] = "German";
$force_name[4] = "German";

require_once "constants.php";
require_once "ModernLandBattle.php";

class FinalChapter extends ModernLandBattle
{

    public $specialHexesMap = ['SpecialHexA'=>3, 'SpecialHexB'=>3, 'SpecialHexC'=>3];

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "finalChapterHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = $force_name[1];
        $deployOne = $playerTwo = $force_name[2];
        $playerThree = $force_name[3];
        $playerFour = $force_name[4];
        @include_once "view.php";
    }

    function terrainGen($mapDoc, $terrainDoc)
    {

        /*
         * Usurp crest hexside for non crossable border between
         */
        $this->terrain->addTerrainFeature("crest", "crest", "o", 0, 0, 0, false);
        $this->terrain->addNatAltTraverseCost('crest', 'yugoslavian', 'inf', 'blocked');
        $this->terrain->addNatAltTraverseCost('crest', 'eastern', 'inf', 'blocked');

        parent::terrainGen($mapDoc, $terrainDoc);
    }

    function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
        $this->mapData->alterSpecialHex(2302, WESTERN_FORCE);
        $this->mapData->alterSpecialHex(1804, WESTERN_FORCE);
        $this->mapData->alterSpecialHex(1706, WESTERN_FORCE);
        $symbol = new stdClass();
        $symbol->type = 'WestWall';
        $symbol->image = 'colHex.svg';
        $symbol->class = 'col-hex';
        $symbols = new stdClass();
        foreach([609,610,611,712, 2404, 2304, 2105, 2005, 1905, 1805, 1806, 1707, 1606, 1506] as $id){
            $symbols->$id = $symbol;
        }
        $this->mapData->setMapSymbols($symbols, "westwall");
    }

    function save()
    {
        $data = parent::save();
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;
        $data->specialHexD = $this->specialHexD;
        return $data;
    }

    public function init()
    {

        $scenario = $this->scenario;
        $baseValue = 2;
        $reducedBaseValue = 2;

        /* Eastern German units */
        /* @var $unit SimpleUnit */
        $unitNum = 100;
        UnitFactory::$injector = $this->force;
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 3423, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 3423, "multiArmor.png", 5,4, 6, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 3224, "multiInf.png", 4,6, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 3224, "multiArmor.png", 7,5, 8, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 3024, "multiInf.png", 3,5, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 3024, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2823, "multiInf.png", 3,4, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2823, "multiArmor.png", 5,5, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2722, "multiArmor.png", 4,3, 8, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2623, "multiInf.png", 3,4, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2623, "multiInf.png", 2,3, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2525, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2424, "multiArmor.png", 4,3, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2424, "multiArmor.png", 4,3, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2223, "multiInf.png", 3,4, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2223, "multiArmor.png", 4,3, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 2024, "multiMountain.png", 3,2, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1824, "multiInf.png", 2,4, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1823, "multiInf.png", 3,4, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1823, "multiArmor.png", 7,5, 8, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1622, "multiInf.png", 1,2, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1622, "multiInf.png", 3,2, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1421, "multiArmor.png", 5,3, 7, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1320, "multiArmor.png", 3,2, 7, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1019, "multiArmor.png", 4,3, 6, STATUS_READY, "A", 1, 1, "easternGerman",  'mech', $unitNum++);


        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 1021, "multiInf.png", 3,2, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);


        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 823, "multiInf.png", 4,4, 5, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 524, "multiInf.png", 2,4, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", EASTERN_EMPIRE_FORCE, 324, "multiInf.png", 3,4, 4, STATUS_READY, "A", 1, 1, "easternGerman",  'inf', $unitNum++);





        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 2502, "multiPara.png", 5,11, 4, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 2304, "multiArmor.png", 6,5, 6, STATUS_READY, "A", 1, 1, "westernGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 2205, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 2205, "multiInf.png", 5,6, 5, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 2105, "multiInf.png", 3,5, 4, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);


        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 2005, "multiArmor.png", 6,5, 8, STATUS_READY, "A", 1, 1, "westernGerman",  'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 2005, "multiArmor.png", 6,5, 8, STATUS_READY, "A", 1, 1, "westernGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1905, "multiArmor.png", 5,3, 6, STATUS_READY, "A", 1, 1, "westernGerman",  'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1905, "multiArmor.png", 6,5, 6, STATUS_READY, "A", 1, 1, "westernGerman",  'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1906, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);


        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1806, "multiInf.png", 6,7, 6, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1806, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1805, "multiInf.png", 2,4, 4, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1805, "multiInf.png", 4,5, 5, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);


        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1606, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1606, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 1506, "multiInf.png", 2,3, 4, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 609, "multiMountain.png", 2,4, 4, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 610, "multiPara.png", 3,5, 5, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 611, "multiInf.png", 4,6, 5, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 712, "multiInf.png", 1,3, 4, STATUS_READY, "A", 1, 1, "westernGerman",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_EMPIRE_FORCE, 712, "multiArmor.png", 3,5, 5, STATUS_READY, "A", 1, 1, "westernGerman",  'mech', $unitNum++);




        UnitFactory::create("xxx", WESTERN_FORCE, 2401, "multiArmor.png", 7, 4, 8, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 2401, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 2303, "multiArmor.png", 7, 4, 8, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 2303, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 2203, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 2204, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 2204, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 2104, "multiArmor.png", 7, 4, 8, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 2104, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 1904, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 1705, "multiArmor.png", 7, 4, 8, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 1705, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 1605, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 1706, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 1505, "multiMech.png", 6, 6, 7, STATUS_READY, "C", 1, 1, "western", 'mech', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 1405, "multiInf.png", 4,8, 6, STATUS_READY, "C", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 510, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 511, "multiMech.png", 6, 6, 7, STATUS_READY, "D", 1, 1, "western", 'mech', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 512, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 512, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);

        UnitFactory::create("xxx", WESTERN_FORCE, 612, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);
        UnitFactory::create("xxx", WESTERN_FORCE, 612, "multiInf.png", 4,8, 6, STATUS_READY, "D", 1, 1, "western",  'inf', $unitNum++);


        $unitNum = 0;
        UnitFactory::create("xxxxx", BLUE_FORCE, 3524, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);
        UnitFactory::create("xxxx", BLUE_FORCE, 3325, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);
        UnitFactory::create("xxxx", BLUE_FORCE, 3325, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);
        UnitFactory::create("xxxxx", BLUE_FORCE, 3225, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);

        UnitFactory::create("xxxx", BLUE_FORCE, 3025, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);
        UnitFactory::create("xxxxx", BLUE_FORCE, 2925, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);
        UnitFactory::create("xxxx", BLUE_FORCE, 2824, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",$unitNum++);
        UnitFactory::create("xxxx", BLUE_FORCE, 2724, "multiInf.png", 3, 3, 4,  STATUS_READY, "B", 1, 1, "polish",  "inf","pol");
        UnitFactory::create("xxxx", BLUE_FORCE, 2624, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",$unitNum++);
        UnitFactory::create("xxxxx", BLUE_FORCE, 2624, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);

        UnitFactory::create("xxxx", BLUE_FORCE, 2526, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",$unitNum++);
        UnitFactory::create("xxxx", BLUE_FORCE, 2526, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);

        UnitFactory::create("xxxx", BLUE_FORCE, 2425, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);
        UnitFactory::create("xxxx", BLUE_FORCE, 2425, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);

        UnitFactory::create("xxxx", BLUE_FORCE, 2325, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",$unitNum++);
        UnitFactory::create("xxxx", BLUE_FORCE, 2325, "multiArmor.png", 6, 4, 6,  STATUS_READY, "B", 1, 1, "eastern",  "mech",$unitNum++);

        UnitFactory::create("xxxxx", BLUE_FORCE, 2224, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);
        UnitFactory::create("xxxxx", BLUE_FORCE, 1724, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);

        UnitFactory::create("xxxxx", BLUE_FORCE, 1422, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);

        UnitFactory::create("xxxx", BLUE_FORCE, 1926, "multiInf.png", 7, 7, 4,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);

        UnitFactory::create("xxxxx", BLUE_FORCE, 1220, "multiInf.png", 8, 20, 3,  STATUS_READY, "B", 1, 1, "eastern",  "inf",$unitNum++);


        UnitFactory::create("xxxx", BLUE_FORCE, 1123, "multiInf.png", 2, 5, 4,  STATUS_READY, "B", 1, 1, "bulgarian",  "inf","bul");

        UnitFactory::create("xxxx", BLUE_FORCE, 824, "multiInf.png", 5, 6, 4,  STATUS_READY, "B", 1, 1, "yugoslavian",  "inf","yug");
        UnitFactory::create("xxxx", BLUE_FORCE, 525, "multiInf.png", 5, 6, 4,  STATUS_READY, "B", 1, 1, "yugoslavian",  "inf","yug");
        UnitFactory::create("xxxx", BLUE_FORCE, 325, "multiInf.png", 5, 6, 4,  STATUS_READY, "B", 1, 1, "yugoslavian",  "inf","yug");
        UnitFactory::create("xxxx", BLUE_FORCE, 224, "multiInf.png", 5, 6, 4,  STATUS_READY, "B", 1, 1, "yugoslavian",  "inf","yug");

    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
        } else {
            $this->victory = new Victory("SPI/FinalChapter/finalChapterVictoryCore.php");

            foreach($this->mapViewer as $mapView){
                $mapView->trueRows = true;
            }
            $this->moveRules->noZocZocOneHex = true;
            $this->moveRules->blockedRetreatDamages = false;
            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = 0;
            $this->moveRules->noZocZoc = true;
            $this->moveRules->friendlyAllowsRetreat = false;

//            $this->moveRules->stacking = 2;
            // game data
            $this->gameRules->setMaxTurn(10);

            $this->gameRules->setInitialPhaseMode(BLUE_MOVE_PHASE, MOVING_MODE);

            $this->gameRules->attackingForceId = EASTERN_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = EASTERN_EMPIRE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId, $this->gameRules->defendingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, EASTERN_FORCE, EASTERN_EMPIRE_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, EASTERN_FORCE, EASTERN_EMPIRE_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, TEAL_REPLACEMENT_PHASE, REPLACING_MODE,  WESTERN_EMPIRE_FORCE, WESTERN_FORCE, false);


            $this->gameRules->addPhaseChange(TEAL_REPLACEMENT_PHASE, TEAL_MOVE_PHASE, MOVING_MODE, WESTERN_EMPIRE_FORCE, WESTERN_FORCE, false);
            $this->gameRules->addPhaseChange(TEAL_MOVE_PHASE, TEAL_COMBAT_PHASE, COMBAT_SETUP_MODE, WESTERN_EMPIRE_FORCE, WESTERN_FORCE, false);
            $this->gameRules->addPhaseChange(TEAL_COMBAT_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, WESTERN_FORCE, WESTERN_EMPIRE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, WESTERN_FORCE, WESTERN_EMPIRE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, WESTERN_FORCE, WESTERN_EMPIRE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, PURPLE_REPLACEMENT_PHASE, REPLACING_MODE, EASTERN_EMPIRE_FORCE, EASTERN_FORCE, false);

            $this->gameRules->addPhaseChange(PURPLE_REPLACEMENT_PHASE, PURPLE_MOVE_PHASE, MOVING_MODE, EASTERN_EMPIRE_FORCE, EASTERN_FORCE, false);
            $this->gameRules->addPhaseChange(PURPLE_MOVE_PHASE, PURPLE_COMBAT_PHASE, COMBAT_SETUP_MODE, EASTERN_EMPIRE_FORCE, EASTERN_FORCE, false);
            $this->gameRules->addPhaseChange(PURPLE_COMBAT_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, EASTERN_FORCE, EASTERN_EMPIRE_FORCE, true);


            $this->victory->victoryPoints[WESTERN_FORCE] = 3;
        }

        if($this->players){
            /*
             * if player 3 set, they become player 3 and four
             * if 3 and 4 not set 3 becomes 1 and 4 becomes 2
             */
            if(!isset($this->players[4]) && $this->players[3]) {
                $this->players[4] = $this->players[3];
            }
            if(!isset($this->players[3]) && $this->players[1]){
                $this->players[3] = $this->players[1];
            }
            if(!isset($this->players[4]) && $this->players[2]) {
                $this->players[4] = $this->players[2];
            }
        }

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){
            $armyGroup = false;
            if($unit->name == "xxxxx"){
                if(count((array)$mapHex->forces[$forceId]) >= 1){
                    $armyGroup = true;
                }
            }

            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if($this->force->units[$mKey]->name == "xxxxx"){
                    if($armyGroup){
                        return true;
                    }
                }
            }
            return count((array)$mapHex->forces[$forceId]) >= 2;
        };

    }
}