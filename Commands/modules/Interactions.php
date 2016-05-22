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

/**
 * Description of hug
 *
 * @author fence
 */
class Hug implements messageCommand {
    public function getDescription() {
        return "hug someone";
    }

    public function getHelp() {
        return $this->getDescription() . "
            usage: hug [@user]";

    }

    public function runCommand($message) {
        $parameters = Utils::messageSplit($message);
        if(Utils::startsWith($parameters[1], "<@")){
            return "hugs " . $parameters[1];
        } else if ($parameters[1] == "help"){
            return $this->getHelp();
        } else {
            return "you cant hug air";
        }
    }

    public static function getName() {
        return "hug";
    }
}


class Poke implements messageCommand {
    public function getDescription() {
        return "poke someone";
    }

    public function getHelp() {
        return $this->getDescription() . "
            usage: poke [@user]";

    }

    public function runCommand($message) {
        $parameters = Utils::messageSplit($message);
        if(Utils::startsWith($parameters[1], "<@")){
            return "pokes " . $parameters[1];
        } else if ($parameters[1] == "help"){
            return $this->getHelp();
        } else {
            return "you cant poke air";
        }
    }

    public static function getName() {
        return "poke";
    }
}