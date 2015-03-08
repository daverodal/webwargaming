<?php
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 3/21/13
 * Time: 12:39 PM
 * To change this template use File | Settings | File Templates.
 */
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version->

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
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