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

use Geekbot\Settings;
use \Geekbot\Utils;
use SimpleXMLElement;

/**
 * Looks up stuff from myanimelist
 *
 * @author fence
 */
class MAL implements messageCommand {

    private $login;

    function __construct() {
        $this->login = Settings::envGet("mallogin");
    }

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
        if ($parameters[1] == "help") {
            return $this->getHelp();
        } else {
            if ($parameters[1] == "anime" | $parameters[1] == "manga") {
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
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Authorization: Basic " . base64_encode($this->login)
            )
        );
        $context = stream_context_create($opts);
        $nya = file_get_contents("http://myanimelist.net/api/$type/search.xml?q=" . $searchString, false, $context);
        if($nya != NULL) {
            $mal = new SimpleXMLElement($nya);
            return $this->{$type . "Info"}($mal->entry[0]);
        } else {
            return "couldn't find that";
        }   
    }
    
    private function animeInfo($xml) {
        $string = "\n";
        $string .= "**Title:** $xml->title (http://myanimelist.net/anime/$xml->id) \n**Type:** $xml->type \n**Status:** $xml->status \n**Year:** " . substr($xml->start_date, 0, 4) . "\n";
        if($xml->type != "Movie") {
            $string.= "**Episodes:** $xml->episodes \n";
        }
        return $string;
    }
    
    private function mangaInfo($xml) {
        $string = "\n";
        $string .= "**Title:** $xml->title (http://myanimelist.net/manga/$xml->id) \n**Type:** $xml->type \n**Status:** $xml->status \n**Year:** " . substr($xml->start_date, 0, 4) . "\n";
        $string.= "**Chapters:** $xml->chapters \n**Volumes:** $xml->volumes";
        
        return $string;
    }
}
