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
 * Description of Cat
 *
 * @author fence
 */
class Cat implements messageCommand{
    public function getDescription() {
        return "returns a random image of a cat";
    }

    public function getHelp() {
        return $this->getDescription() . "
            usage:
            !cat";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        if (Utils::isHelp($messageArray)) {
            return $this->getHelp();
        } elseif(isset($messageArray[1]) && $messageArray[1] == "image"){
            $imagesource = file_get_contents('http://random.cat/meow');
            $imagecontent = json_decode($imagesource);
            $message->reply($imagecontent->file);
        } elseif(isset($messageArray[1]) && $messageArray[1] == "fact"){
            $factsource = file_get_contents("http://catfacts-api.appspot.com/api/facts");
            $factcontent = json_decode($factsource);
            $message->reply($factcontent->facts[0]);
            return $message;
        } else {
            $factsource = file_get_contents("http://catfacts-api.appspot.com/api/facts");
            $factcontent = json_decode($factsource);
            $imagesource = file_get_contents('http://random.cat/meow');
            $imagecontent = json_decode($imagesource);
            $message->reply($factcontent->facts[0].PHP_EOL.$imagecontent->file);
            return $message;
        }
    }

    public static function getName() {
        return "!cat";
    }
}
