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
use PHPHtmlParser\Dom;

class Dog implements messageCommand{
    public static function getName() {
        return "!dog";
    }

    public function getDescription() {
        return "returns a random image of a dog";
    }

    public function getHelp() {
        return $this->getDescription() . "
        usage:
            !dog";
    }

    public function runCommand($message) {
        $dom = new Dom;
        $dom->loadFromUrl("http://random.dog/");
        $imgtag = $dom->getElementsByTag('img');
        $val = $imgtag[0]->getAttribute("src");
        if(isset($val)) {
            $response = "http://random.dog/" . $val;
        } else {
            $response = "something went wrong... wuff... :D";
        }
        $message->channel->sendMessage($response);
        return $message;
    }

}
