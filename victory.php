<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 6:43 PM
 * To change this template use File | Settings | File Templates.
 */
class Victory{
    private $core;
    function __construct($name = null, $data = null){
        if($name == null){
            return;
        }
        $class = __DIR__."/".$name."/victoryCore.php";
        if(file_exists($class)){
            require_once($class);
            $this->core = new victoryCore($data);
        }
    }
    public function __call($name, $arguments){
        if($this->core && method_exists($this->core,$name)){
            return $this->core->$name($arguments);
        }
        return $arguments[0];

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