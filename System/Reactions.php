<?php

/*
 *   This file is part of Geekbot.
 *
 *   Geekbot is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Geekbot is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Geekbot.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Geekbot;

class Reactions {

    //construct parameters
    private $message;
    
    function __construct($message) {
        $this->message = $message;    
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    public function ping(){
        $this->message->reply('pong!');
    }
    
    public function marco(){
        $this->message->reply('polo!');
    }
    
    public function deder() {
        $this->message->reply('DEDEST');
    }
    
    public function hui(){
        $this->message->reply("hui!");
    }
    
    public function fuck(){
        $this->message->reply("Aso nei, da seit mer also nid :rage:");
    }
}