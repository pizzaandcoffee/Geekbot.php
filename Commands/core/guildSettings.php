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

class guildSettings implements messageCommand{
    public static function getName() {
        return "~settings";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        switch ($messageArray[1]){
            case "test":
                if($messageArray[2] == "set"){
                    Utils::setGuildOption($message, $messageArray[1], $messageArray[3]);
                    $message->reply("option {$messageArray[1]} was set to {$messageArray[3]}");
                } elseif ($messageArray[2] == "get"){
                    $option = Utils::getGuildOption($message, $messageArray[1]);
                    $message->channel->sendMessage($option);
                }

                break;
        }
        return $message;
    }

    public function getDescription() {
        return "edit how geekbot behaves on your server";
    }

    public function getHelp() {
        return "~settings [option]
        with `show` geekbot returns all settings for your server
        the options are:
        `test [test]`";
    }
}