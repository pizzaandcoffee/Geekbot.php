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

use \Geekbot\Utils;
use PHPHtmlParser\Dom;

/**
 * Looks up stuff from myanimelist
 *
 * @author fence
 */
class MAL implements messageCommand{    

    public function getDescription() {
        return "looks up stuff from myanimelist";
    }

    public function getHelp() {
        return $this->getDescription() . " 
            usage:
            !mal [anime|manga] [search]";
    }

    public function runCommand($message) {
        $parameters = Utils::messageSplit($message);       
        if($parameters[1] == "help") {
            return $this->getHelp();
        } else {
            if($parameters[1] == "anime" | $parameters[1] == "manga") {
                //getting rid of the command
                array_shift($parameters);              
                return $this->getResult(array_shift($parameters), implode("+", $parameters));
            } else {
                return "can't search for that";
            }
        }
    }

    public static function getName() {
        return "!mal";
    }
    
    private function getResult($type, $searchString) {
        $dom = new Dom;
        $dom->loadFromUrl("http://myanimelist.net/$type.php?q=". $searchString);
        $table = $dom->find("table")[2];
        $result = $table->find("tr")[1]->find("a")[0]->getAttribute("href");
        return $result . "\n" . "For more Results : ". "http://myanimelist.net/$type.php?q=". $searchString;
    }
}
