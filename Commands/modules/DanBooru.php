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
 * Description of DanBooru
 *
 * @author fence
 */
class DanBooru implements messageCommand {
    public static function getName() {
        return "!danbooru";
    }
    
    public function getDescription() {
        return "looks up stuff from danbooru";
    }

    public function getHelp() {
        return $this->getDescription() . "           
        usage:
            !danbooru safe|questionable|explicit [tags] - returns a random image in the specified rating
            !danbooru [tags] - returns a random image";
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
        $getDanBooru = file_get_contents('http://danbooru.donmai.us/posts.json?tags=' . $tags);
        $DanBooru = json_decode($getDanBooru, true);
        $randomnumberporn = rand(1, count($DanBooru) -1);
        $result = $DanBooru[$randomnumberporn]['file_url'];
        if ($result == NULL) {
            return "no results";
        } else {
            return "http://danbooru.donmai.us" . $result;
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