<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 3/21/13
 * Time: 12:39 PM
 * To change this template use File | Settings | File Templates.
 */

class Display {
    public $messages = array('');
    public $currentMessageID = 0;
    public $currentMessage = "";
    public function __construct($display = null){
        if($display){
            $this->currentMessage = $display->currentMessage;
            $this->currentMessageID = $display->currentMessageID;
        }else{
            $this->currentMessage = $this->messages[$this->currentMessageID];
        }
    }
    public function next(){
        $this->currentMessageID++;
        $this->currentMessage = $this->messages[$this->currentMessageID];
    }
    public function set($val){
        if(is_array($val)){
            $this->mesages = $val;
        }else{
            $this->messages = array($val);
        }
        $this->currentMessageID = 0;
        $this->currentMessage = $this->messages[$this->currentMessageID];
    }
}