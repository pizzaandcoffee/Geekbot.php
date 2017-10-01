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
use Geekbot\Permission;
use Geekbot\Settings;
use Geekbot\Utils;

class classes implements messageCommand{
    public static function getName() {
        return "!class";
    }

    public function getDescription() {
        return "give someone a class";
    }

    public function getHelp() {
        return $this->getDescription() . "
        usage:
            !class [option] [class]
        options:
            set - assign yourself a class
            add - add a class
            remove - remove a class
        admins can assign others a class by using the following syntax:
            !class [option] [mention] [class]
        admins can also assing any class outside of the classes list";
    }
    
    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        $rpgclasses = Settings::getGuildSetting("classes");
        $perms = new Permission($message);
        if(is_array($rpgclasses)) {
            $classesString = implode(", ", $rpgclasses);

            if (isset($messageArray[1]) && $messageArray[1] == 'set') {
                if (Utils::startsWith($messageArray[2], '<@') && $perms->administrator) {

                    if (isset($messageArray[3]) && in_array($messageArray[3], $rpgclasses) || $perms->administrator) {
                        $mentionID = $message->mentions->first()->id;
                        $userData = Database::get('member', $mentionID);
                        $userData->class = $messageArray[3];
                        Database::set('member', $userData, $mentionID);
                        $message->channel->sendMessage("{$messageArray[2]} is now a " . $messageArray[3]);
                    } else {
                        $message->channel->sendMessage("that class does not exist, please use one of the following: \n {$classesString}");
                    }
                } elseif(Utils::startsWith($messageArray[2], '<@')) {
                    $message->reply("you cannot set someone else's class");
                } else {
                    if (in_array($messageArray[2], $rpgclasses) || Settings::envGet('sys.ownerid') == $message->author->id) {
                        Settings::setMemberSetting('class', $messageArray[2]);
                        $message->channel->sendMessage("<@{$message->author->id}> is now a " . $messageArray[2]);
                    } else {
                        $message->channel->sendMessage("that class does not exist, please use one of the following: \n {$classesString}");
                    }
                }
            }
        } else {
            $message->reply("please add atleast 1 class to the classes list...");
        }

        if(isset($messageArray[1]) && $messageArray[1] == "add" && $perms->administrator){
            if(isset($messageArray[2])){
                if(!is_array($rpgclasses)){
                    $rpgclasses = [];
                }
                $rpgclasses[] = $messageArray[2];
                Settings::setGuildSetting("classes", $rpgclasses);
                $message->reply("Added class {$messageArray[2]}");
            }

        } elseif(isset($messageArray[1]) && $messageArray[1] == "remove" && $perms->administrator){
            if(isset($messageArray[2]) && is_array($rpgclasses)){
                if(in_array($messageArray[2], $rpgclasses)){
                    $newclasses = [];
                    foreach($rpgclasses as $class){
                        if($class != $messageArray[2]){
                            $newclasses[] = $class;
                        }
                    }
                    Settings::setGuildSetting("classes", $newclasses);
                    $message->reply("the class {$messageArray[2]} has been removed");
                } else {
                    $message->reply("you cannot remove that class because it does not exist...");
                }
            } else {
                $message->reply("you cannot do that because there are no classes set yet...");
            }

        } elseif(isset($messageArray[1]) && $messageArray[1] == "show"){
            if (!isset($messageArray[2]) && is_array($rpgclasses)){
                $classesString = implode(", ", $rpgclasses);
                $message->reply("the avaible classes are:\n{$classesString}");
            } elseif(isset($messageArray[2]) && Utils::startsWith($messageArray[2], "<@")){
                $mentionID = $message->mentions->first()->id;
                $userData = Database::get('member', $mentionID);
                if(!isset($userData->class)){
                    $message->reply("{$messageArray[2]} does not have a class yet");
                } else {
                    $userClass = $userData->class;
                    $message->reply("{$messageArray[2]} is a {$userClass}");
                }
            }
        } elseif(Utils::isHelp($message)){
            $message->channel->send($this->getHelp());
        } elseif(!isset($messageArray[1])) {
            $message->reply("Wrong syntax for the command !class, please see !class help to see how the command works");
        }

        return $message;
    }

}