<?php
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
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 6:43 PM
 * To change this template use File | Settings | File Templates.
 */

class Victory{
    private $core;
    private $coreName;
    function __construct($name = null, $data = null){

        if($name == null){
            return;
        }
        if(is_object($name)){
            $data = $name;
            $name = $data->victory->coreName;
            $this->coreName = $name;
            unset($data->victory->coreName);
        }else{
            $this->coreName = $name;
        }

        if(preg_match("/\\\\/", $name))
        {
            $this->core = new $name($data);
            return;
        }
        $class = __DIR__."/".$name;
        if(!strstr($class,".php")){
            $className = "victoryCore";
            $class .= "/victoryCore.php";
        }
        else{
            $matches = [];
            $className = basename($name);
            $className = preg_replace("/.php/","",$className);
        }
        if(file_exists($class)){
            require_once($class);
            $this->core = new $className($data);
        }
    }
    public function save(){
        $ret = $this->core->save();
        $ret->coreName = $this->coreName;
        return $ret;
    }
    public function __call($name, $arguments){
        if($this->core && method_exists($this->core,$name)){
            return $this->core->$name($arguments);
        }
        /*
         * Pattern matching, if method called starts with is, return false on no matching method
         * other return passed arguments array.
         */
        if(preg_match("/^is/",$name)){
            return false;
        }
        return $arguments;

    }
    public function &__get($name){
        if($this->core && $this->core->$name){
            return $this->core->$name;
        }
        return false;
    }

    public function __set($name, $val){
        if($this->core && $this->core->$name){
            return $this->core->$name = $val;
        }
        return false;
    }
}