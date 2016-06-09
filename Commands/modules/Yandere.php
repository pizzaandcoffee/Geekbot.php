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

use Geekbot\Utils;

/**
 * Description of Yandere
 *
 * @author Alex Fence
 */
class Yandere implements messageCommand {
    public static function getName() {
        return "!yandere";
    }
    
    public function getDescription() {
        return "looks up stuff from yande.re";
    }

    public function getHelp() {
        return $this->getDescription() . "           
        usage:
            !yandere safe|questionable|explicit [tags] - returns a random image in the specified rating
            !yandere [tags] - returns a random image";
    }

    public function runCommand($message) {
        $parameters = Utils::messageSplit($message);
        switch ($parameters[1]) {
            case "help":
                return $this->getHelp();

            case "safe":
                return $this->imageWithRating("safe", $parameters);
            case "questionable":
                return $this->imageWithRating("questionable", $parameters);
            case "explicit":
                return $this->imageWithRating("explicit", $parameters);
            default :
                array_shift($parameters);
                return $this->images(implode("%20", $parameters));
        }
    }
    
    private function images($tags) {
        $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'GET',
                        'header' => 'Content-Type: application/json',
                        'verify_peer'      => false,
                        'verify_peer_name' => false,
                        ), 
                    )
                );
        $getyanderejson = file_get_contents('http://yande.re/post.json?tags=' . $tags, false, $context);
        $yandere = json_decode($getyanderejson, true);
        $randomnumberporn = rand(1, count($yandere) -1);
        $result = $yandere[$randomnumberporn]['file_url'];
        if ($result == NULL) {
            return "no results";
        } else {
            return $result;
        }
    }

    private function imageWithRating($rating, $parameters) {
        $tags = "rating:$rating%20";
        array_shift($parameters);
        array_shift($parameters);
        $tags .= implode("%20", $parameters);
        return $this->images($tags);
    }
}
