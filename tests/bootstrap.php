<?php
spl_autoload_register('spl_autoload');
class Battle{
    public static $class;
    public static function getBattle(){
    return self::$class;
    }
}
require_once "Battle.php";
set_include_path("Mollwitz". PATH_SEPARATOR .  get_include_path());
set_include_path("Mollwitz/Klissow1702". PATH_SEPARATOR .  get_include_path());
require_once("crtTraits.php");
set_include_path("TMCW". PATH_SEPARATOR .  get_include_path());
set_include_path("TMCW/Kiev". PATH_SEPARATOR .  get_include_path());

//require_once "Mollwitz/Klissow1702/Klissow1702.php";
