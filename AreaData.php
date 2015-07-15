<?php
//  MapGrid
//
// Copyright (c) 1998-2011 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */



class AreaData implements JsonSerializable
{

    public $areas;

    private static $instance;
    public $mapUrl;

    private function __construct($data = false)
    {
        if($data){

        }else{
            $this->areas = new stdClass();
        }

    }

    function jsonSerialize()
    {
        return $this;
    }

    public function breadcrumbMove($id,$attackingForceId, $turn, $phase, $mode, $fromHex, $toHex){

        $index = $turn.'t'.$attackingForceId.'a'.$phase.'p'.$mode.'m'.$id;
        if(!isset($this->breadcrumbs)){
            $this->breadcrumbs = new stdClass();
        }
        if(!isset($this->breadcrumbs->$index)){
            $this->breadcrumbs->$index = [];
        }
        $crumbs = $this->breadcrumbs->$index;
        $crumb = new stdClass();
        $crumb->type = "move";
        $crumb->fromHex = $fromHex;
        $crumb->toHex = $toHex;
        $crumbs[] = $crumb;
        $this->breadcrumbs->$index = $crumbs;
}

    public function breadcrumbCombat($id, $attackingForceId, $turn, $phase, $mode, $result, $dieRoll, $hex){
        global $results_name;

        $index = $turn.'t'.$attackingForceId.'a'.$phase.'p'.$mode.'m'.$id;
        if(!isset($this->breadcrumbs)){
            $this->breadcrumbs = new stdClass();
        }
        if(!isset($this->breadcrumbs->$index)){
            $this->breadcrumbs->$index = [];
        }
        $crumbs = $this->breadcrumbs->$index;
        $crumb = new stdClass();
        $crumb->type = "combatResult";
        $crumb->result = $results_name[$result];
        $crumb->dieRoll = $dieRoll + 1;
        $crumb->hex = $hex;
        $crumbs[] = $crumb;
        $this->breadcrumbs->$index = $crumbs;
    }

    public static function getInstance()
    {
        if (!AreaData::$instance) {
            AreaData::$instance = new AreaData();
        }
        return AreaData::$instance;
    }

    public function init($data)
    {

    }

    function addArea($name){
        $this->areas->$name = new stdClass();
    }


    function getArea($name)
    {
        return $this->areas->$name;
    }
}

class MapViewer
{

    public $originX;
    public $originY;
    public $topHeight;
    public $bottomHeight;
    public $hexsideWidth;
    public $centerWidth;

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }

        }
    }

    function setData($originX, $originY
        , $topHeight, $bottomHeight
        , $hexsideWidth, $centerWidth)
    {

        $this->originX = $originX;
        $this->originY = $originY;
        $this->topHeight = $topHeight;
        $this->bottomHeight = $bottomHeight;
        $this->hexsideWidth = $hexsideWidth;
        $this->centerWidth = $centerWidth;
    }
}
