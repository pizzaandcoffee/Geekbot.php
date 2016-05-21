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

use PHPHtmlParser\Dom;
use Geekbot\Utils;
use SimpleXMLElement;

/**
 * Description of GelBooru
 *
 * @author fence
 */
class Gelbooru implements Commands\messageCommand{
    public function getDescription() {
        return "looks up stuff from gelbooru";
    }

    public function getHelp() {
        return $this->getDescription() . "           
            usage:
            !gelbooru tags [tag] - looks up tags
            !gelbooru safe|questionable|explicit [tags] - returns a random image in the specified rating
            !gelbooru [tags] - returns a random image";
    }

    public function runCommand($message) {
        $parameters = Utils::messageSplit($message);
        switch ($parameters[1]){
            case "help":
                return $this->getHelp();
                
            case "tags":
                if(isset($parameters[2])) {
                    return $this->tags($parameters[2]);
                } else {
                    return "please set a tag";
                }
            case "safe": 
                return $this->imageWithRating("safe", $parameters);
            case "questionable": 
                return $this->imageWithRating("questionable", $parameters);
            case "explicit":
                return $this->imageWithRating("questionable", $parameters);
            default :
                array_shift($parameters);
                return $this->images(implode(" ", $parameters));
        }
    }

    public static function getName() {
        return "!gelbooru";
    }
    
    private function tags($search) {        
        $dom = new Dom;
        $dom->loadFromUrl("http://gelbooru.com/index.php?page=tags&s=list&tags=$search&sort=asc&order_by=updated");
        $table = $dom->getElementsByClass("highlightable")[0]->find("tr")[0];
        $string  = "results for '*yui': \n";
        for ($index = 0; $index < 3; $index++) {
            $tr = $table->find("tr")[$index];
            $td = $tr->find("td");
            $string .= $td[0]->text() . " - " . $td[1]->find("a")[0]->text() . " \n";
        }
        
        return $string;
    }
    
    private function images($tags){
            $getgelbooru = file_get_contents('http://gelbooru.com/index.php?page=dapi&s=post&q=index&tags=' .$tags);
            $xmlgelbooru = new SimpleXMLElement($getgelbooru);
            $randomnumberporn = rand(1, 100);
            $result = Utils::xml_attribute($xmlgelbooru->post[$randomnumberporn], 'file_url');
            if($result == NULL) {
                return "no results";
            } else {
                return $result;
            }
    }
    
    private function imageWithRating($rating, $parameters) {
        $tags = "rating:$rating ";
        array_shift($parameters);
        array_shift($parameters);
        $tags .= implode(" ", $parameters);
        return $this->images($tags);
    }
}
