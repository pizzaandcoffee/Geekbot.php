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

use Geekbot\Database;
use Geekbot\Settings;
use Geekbot\Utils;

class classes implements messageCommand{
    public static function getName() {
        return "!class";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        $rpgclasses = Settings::getGuildSetting($message, "classes");
        if(is_array($rpgclasses)) {
            $classesString = implode(", ", $rpgclasses);
            if (isset($messageArray[1]) && $messageArray[1] == 'set') {
                if (Utils::startsWith($messageArray[2], '<@')) {

                    if (isset($messageArray[3]) && in_array($messageArray[3], $rpgclasses)) {
                        Settings::setUserSetting($message, 'class', $messageArray[3]);
                        $message->reply("{$messageArray[2]} is now a " . $messageArray[3]);
                    } else {
                        $message->reply("that class does not exist, please use one of the following: \n {$classesString}");
                    }
                }
            }
        } else {
            $message->reply("please add atleast 1 class to the classes list...");
        }

        if(isset($messageArray[1]) && $messageArray[1] == "add"){
            if(isset($messageArray[2])){
                if(!is_array($rpgclasses)){
                    $rpgclasses = [];
                }
                $rpgclasses[] = $messageArray[2];
                Settings::setGuildSetting($message, "classes", $rpgclasses);
                $message->reply("Added class {$messageArray[2]}");
            }
        } elseif(isset($messageArray[1]) && $messageArray[1] == "remove"){
            if(isset($messageArray[2])){
                if(in_array($messageArray[2], $rpgclasses)){
                    $rpgclasses = array_diff($rpgclasses, array($messageArray[2]));
                    Settings::setGuildSetting($message, "classes", $rpgclasses);
                    $message->reply("the class {$messageArray[2]} has been removed");
                } else {
                    $message->reply("you cannot remove that class because it does not exist...");
                }
            }
        } elseif(isset($messageArray[1]) && $messageArray[1] == "show"){
            if (!isset($messageArray[2]) && is_array($rpgclasses)){
                $classesString = implode(", ", $rpgclasses);
                $message->reply("the avaible classes are:\n{$classesString}");
            } elseif(isset($messageArray[2]) && Utils::startsWith($messageArray[2], "<@")){
                $guildID = $message->channel->guild_id;
                $mentionID = $message->mentions[0]->id;
                $userData = Database::get($guildID.'-'.$mentionID);
                if(!isset($userData->class)){
                    $message->reply("{$messageArray[2]} does not have a class yet");
                } else {
                    $userClass = $userData->class;
                    $message->reply("{$messageArray[2]} is a {$userClass}");
                }
            }
        } elseif(isset($messageArray[1]) && $messageArray[1] == "help"){
            $message->channel->send($this->getHelp());
        } elseif(!isset($messageArray[1])) {
            $message->reply("Wrong syntax for the command !class, please see !class help to see how the command works");
        }

        return $message;
    }

    public function getDescription() {
        return "set a class";
    }

    public function getHelp() {
        return "!class";
    }

}