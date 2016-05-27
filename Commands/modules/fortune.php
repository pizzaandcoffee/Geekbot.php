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

class fortune implements messageCommand{
    public static function getName() {
        return "!fortune";
    }

    public function getDescription() {
        return "returns a fortune from the unix fortune database";
    }

    public function getHelp() {
        return $this->getDescription() . "
        usage:
        !fortune";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        if (Utils::isHelp($message)) {
            $message->channel->sendMessage($this->getHelp());
        } else {
            $fortunes = Utils::getFile('fortunes');
            $array = explode('%', $fortunes);
            $randomfortune = $array[array_rand($array)];
            $fortune = trim(preg_replace('/\s\s+/', ' ', $randomfortune));
            $message->channel->sendMessage($fortune);
        }
        return $message;
    }
}