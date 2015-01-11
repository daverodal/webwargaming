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

        $class = __DIR__."/".$name;
        if(!strstr($class,".php")){
            $className = "victoryCore";
            $class .= "/victoryCore.php";
        }else{
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