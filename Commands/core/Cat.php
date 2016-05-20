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
        if (explode(' ', $message->content)[1] == "help") {
            return $this->getHelp();
        } else {
            $catsource = file_get_contents('http://random.cat/meow');
            $catcontent = json_decode($catsource);
            $message->reply($catcontent->file);
            return $message;
        }
    }

    public static function getName() {
        return "!cat";
    }
}
