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

namespace Geekbot\Commands;

use DateTime;

/**
 * Description of Info
 *
 * @author fence
 */
class Info implements basicCommand {
    private $startDate;
    
    function __construct() {
        $this->startDate = new DateTime('now');
        
    }
    
    public function getDescription() {
        return "returns some info about the bot";
    }

    public function getHelp() {
        $this->getDescription();
    }

    public function runCommand() {
        return "Runnign " . $this->getVersion() . " since: " . $this->timeToString($this->getEnlapsedTime());  
    }

    public static function getName() {
        return "!info";
    }
    
    private function getEnlapsedTime() {
        $startdate = $this->startDate;
        $now = new DateTime('now');
        $interval = $now->diff($startdate);
        return $interval;
    }
    
    private function timeToString($interval) {
        return $interval->format('%m months %d days %h hours %i minutes %S seconds'); 
    }
    
    private function getVersion() {
        return "Geekbot 2.0 alpha Test Build";
    }
}
