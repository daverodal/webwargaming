<?php
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 6/14/15
 * Time: 5:37 PM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class NavalUnit extends MovableUnit implements JsonSerializable
{

    public $origStrength;
    public $torpedoStrength;
    public $defStrength;
    public $hits = 0;
    public $pDamage = 0;
    public $wDamage = 0;

    private $range;
    public $gunRange;
    public $spotted = false;
    public $fire = false;


    public function jsonSerialize()
    {
        if (is_object($this->hexagon)) {
            if ($this->hexagon->name) {
                $this->hexagon = $this->hexagon->getName();

            } else {
                $this->hexagon = $this->hexagon->parent;
            }
        }
        return $this;
    }


    public function getUnmodifiedStrength(){
        return  $this->origStrength;
    }


    public function __get($name)
    {

        $b = Battle::getBattle();
        if ($name !== "range" && $name !== "strength" && $name !== "torpedoStrength" && $name !== "attStrength" && $name !== "defStrength") {
            return false;
        }
        if($name === "range") {
            if ($b->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $b->gameRules->phase == RED_TORP_COMBAT_PHASE) {
                if ($this->nationality === "ijn") {
                    return 7;
                } else {
                    return 3;
                }
            }else{
                return $this->gunRange;
            }
        }
        $strength = $this->origStrength;


        if($name === "strength" && ($b->gameRules->phase == BLUE_TORP_COMBAT_PHASE || $b->gameRules->phase == RED_TORP_COMBAT_PHASE)){
            $strength = $this->torpedoStrength;
        }


        foreach ($this->adjustments as $adjustment) {
            switch ($adjustment) {
                case 'floorHalf':
                    $strength = floor($strength / 2);
                    break;
                case 'half':
                    $strength = $strength / 2;
                    break;
                case 'double':
                    $strength = $strength * 2;
                    break;
            }
        }
        return $strength;
    }


    function set( $unitName, $unitForceId, $unitHexagon, $unitImage, $gunneryStrength, $range,$defenseStrength, $torpedoStrength,  $unitMaxMove, $facing, $unitStatus, $unitReinforceZone, $unitReinforceTurn,  $nationality = "neutral", $forceMarch, $class, $unitDesig)
    {

        $this->dirty = true;
        $this->name = $unitName;
        $this->forceId = $unitForceId;
        $this->class = $class;

        $this->hexagon = new Hexagon($unitHexagon);
        $this->unitStrength  = $gunneryStrength;


        $battle = Battle::getBattle();
        $mapData = $battle->mapData;

        $mapHex = $mapData->getHex($this->hexagon->getName());
        if ($mapHex) {
            $mapHex->setUnit($this->forceId, $this);
        }
        $this->image = $unitImage;


        $this->maxMove = $unitMaxMove;
        $this->moveAmountUnused = $unitMaxMove;
        $this->origStrength = $gunneryStrength;
        $this->status = $unitStatus;
        $this->torpedoStrength = $torpedoStrength;
        $this->facing = $facing;
        $this->defStrength = $defenseStrength;
        $this->moveAmountUsed = 0;
        $this->reinforceZone = $unitReinforceZone;
        $this->reinforceTurn = $unitReinforceTurn;
        $this->combatNumber = 0;
        $this->combatIndex = 0;
        $this->combatOdds = "";
        $this->moveCount = 0;
        $this->retreatCountRequired = 0;
        $this->combatResults = NR;
        $this->gunRange = $this->range = $range;
        $this->nationality = $nationality;
        $this->forceMarch = $forceMarch;
        $this->unitDesig = $unitDesig;
        $this->hits = 0;
        $this->wDamage = 0;
        $this->pDamage = 0;
        $this->newSpeed = false;
        $this->torpReload = false;
        $this->fire = false;
        if($torpedoStrength > 0){
            if($nationality === "ijn"){
                $this->torpLoad = 2;
            }else{
                $this->torpLoad = 1;
            }
        }else{
            $this->torpLoad = 0;
        }
        $this->vp = 0;
    }

    function firedGun(){
        $this->spotted = true;
    }

    function removeSpotted(){
        if($this->fire){
            return;
        }
        $this->spotted = false;
    }

    function torpFired(){
        $this->torpLoad--;
        if($this->torpLoad > 0 && $this->nationality === "ijn"){
            $this->torpReload = 2;
        }
    }

    function reloadTorp(){
        $this->torpReload--;
        if($this->torpReload === 0){
            $this->torpReload = false;
        }
    }

    function fireOut(){
        $battle = Battle::getBattle();
        $hex = $this->hexagon;
        $this->fire = false;
        $this->spotted = true;
        $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='fire'>Fire out, SPOTTED!</span><br>";

    }
    function startFire(){
        $battle = Battle::getBattle();
        $this->fire = true;
        $hex = $this->hexagon;

        $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='fire'>FIRE</span><br>";
    }

    function postMove(){
        if($this->hexagon->parent === "gameImages" && $this->fire){
            $Die = rand(1,6);
            if($Die === 1){
                $this->fireOut();
            }
        }
    }

    function eliminate(){
        $this->fire =false;
        $this->spotted = false;
    }

    function damageUnit($result = false)
    {
        $battle = Battle::getBattle();

        if($battle->scenario->two && $this->class === 'ca' && $this->nationality === 'usn'){
            $this->startFire();
        }
        switch($result){
            case P:
                $this->pDamage++;
                if($this->pDamage == 1){
                    $this->maxMove = floor($this->maxMove/2);
                }
                if($this->pDamage > 1){
                    $this->maxMove = 0;
                }
                $this->hits++;
                break;
            case W:
                $Die = rand(1,6);
                if($Die <=2){
                    $this->startFire();
                }
                $this->wDamage++;
                if($this->wDamage == 1){
                    $this->origStrength /= 2;
                    $this->torpedoStrength /= 2;
                }
                if($this->wDamage > 1){
                    $this->origStrength = 0;
                    $this->torpedoStrength = 0;
                }
                $this->hits++;
                break;
            case PW:
                $Die = rand(1,6);
                if($Die <=2){
                    $this->startFire();
                }
                $this->wDamage++;
                $this->pDamage++;
                $this->hits += 2;
                if($this->pDamage == 1){
                    $this->maxMove = floor($this->maxMove/2);
                }
                if($this->pDamage > 1){
                    $this->maxMove = 0;
                }
                if($this->wDamage == 1){
                    $this->origStrength /= 2;
                    $this->torpedoStrength /= 2;
                }
                if($this->wDamage > 1){
                    $this->origStrength = 0;
                    $this->torpedoStrength = 0;
                }
                break;
            case P2:
                $this->pDamage += 2;
                $this->hits += 2;
                 $this->maxMove = 0;
                break;
            case S:
                $this->hits = 3;
                break;
        }


        if($this->hits >= 3){
            $this->status = STATUS_ELIMINATING;
            return true;
        }
        $battle->victory->scoreHit($this);
        return false;
    }

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                if ($k == "hexagon") {
                    $this->hexagon = new Hexagon($v);
                    continue;
                }
                $this->$k = $v;
            }
            $this->dirty = false;
        } else {
            $this->adjustments = new stdClass();
        }
    }


    public function fetchData(){
        $mapUnit = new StdClass();
        $mapUnit->parent = $this->hexagon->parent;
        $mapUnit->moveAmountUsed = $this->moveAmountUsed;
        $mapUnit->maxMove = $this->maxMove;
        $mapUnit->strength = $this->strength;
        $mapUnit->class = $this->class;
        $mapUnit->id = $this->id;
        $mapUnit->defenseStrength = $this->defStrength;
        $mapUnit->torpedoStrength = $this->torpedoStrength;
        $mapUnit->facing = $this->facing;
        $mapUnit->wDamage = $this->wDamage;
        $mapUnit->range = $this->range;
        $mapUnit->pDamage = $this->pDamage;
        $mapUnit->hits = $this->hits;
        $mapUnit->newSpeed = $this->newSpeed;
        $mapUnit->torpLoad = $this->torpLoad;
        $mapUnit->torpReload = $this->torpReload;
        $mapUnit->spotted = $this->spotted;
        $mapUnit->fire = $this->fire;
        $mapUnit->unitDefenseStrength = $this->unitDefenseStrength;
        return $mapUnit;
    }

    function setStatus($status)
    {
        $battle = Battle::getBattle();
        $success = false;
        $prevStatus = $this->status;
        switch ($status) {


            case STATUS_ELIMINATED:
                if ($this->status == STATUS_CAN_REPLACE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_REINFORCING:
                if ($this->status == STATUS_CAN_REINFORCE) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_DEPLOYING:
                if ($this->status == STATUS_CAN_DEPLOY) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_CAN_REINFORCE:
                if ($this->status == STATUS_REINFORCING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_CAN_DEPLOY:
                if ($this->status == STATUS_DEPLOYING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            case STATUS_READY:
            case STATUS_DEFENDING:
            case STATUS_ATTACKING:
                $this->status = $status;
                $id = $this->id;
                if ($status === STATUS_ATTACKING) {
                    if ($battle->force->combatRequired && isset($battle->force->requiredAttacks->$id)) {
                        $battle->force->requiredAttacks->$id = false;
                    }
                }
                if ($status === STATUS_DEFENDING) {
                    if ($battle->force->combatRequired && isset($battle->force->requiredDefenses->$id)) {
                        $battle->force->requiredDefenses->$id = false;
                    }
                }
                if ($status === STATUS_READY) {

                    if ($battle->force->combatRequired && isset($battle->force->requiredAttacks->$id)) {
                        $battle->force->requiredAttacks->$id = true;
                    }
                    if ($battle->force->combatRequired && isset($battle->force->requiredDefenses->$id)) {
                        $battle->force->requiredDefenses->$id = true;
                    }
                }
                break;

            case STATUS_MOVING:
                if (($this->status == STATUS_READY || $this->status == STATUS_REINFORCING)
                ) {
                    $this->status = $status;
                    $this->moveCount = 0;
                    $this->moveAmountUsed = 0;
                    $this->moveAmountUnused = $this->maxMove;
                    $success = true;
                }
                break;

            case STATUS_STOPPED:
                if ($this->status == STATUS_MOVING || $this->status == STATUS_DEPLOYING) {
                    $this->status = $status;
                    $this->moveAmountUnused = $this->maxMove - $this->moveAmountUsed;
                    $this->moveAmountUsed = $this->maxMove;

                    $success = true;
                }
                if ($this->status == STATUS_ADVANCING) {
                    $this->status = STATUS_ADVANCED;
//                    $this->moveAmountUsed = $$this->maxMove;
                    $success = true;
                }
                if ($this->status == STATUS_RETREATING) {
                    $this->status = STATUS_RETREATED;
//                    $this->moveAmountUsed = $$this->maxMove;
                    $success = true;
                }
                break;

            case STATUS_EXITED:
                if ($this->status == STATUS_MOVING) {
                    $this->status = $status;
                    $success = true;
                }
                break;

            default:
                break;
        }
        $this->dirty = true;
        return $success;
    }

}


class UnitFactory {
    public static $id = 0;
    public static $injector;
    public static function build($data = false){

        $sU =  new NavalUnit($data);
        if($data === false){
            $sU->id = self::$id++;
        }
        return $sU;
    }
    public static function create( $unitName, $unitForceId, $unitHexagon, $unitImage, $unitGunneryStrength, $range, $defense, $unitTorpedoStrength, $unitMaxMove, $facing, $unitStatus, $unitReinforceZone, $unitReinforceTurn, $nationality = "neutral", $class, $unitDesig = ""){
        $unit = self::build();
        $unit->set($unitName, $unitForceId, $unitHexagon, $unitImage, $unitGunneryStrength,$range, $defense,  $unitTorpedoStrength, $unitMaxMove, $facing, $unitStatus, $unitReinforceZone, $unitReinforceTurn,  $nationality, true, $class, $unitDesig);
        self::$injector->injectUnit($unit);
    }

}