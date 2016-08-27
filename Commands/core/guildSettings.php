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

use Geekbot\BlackList;
use Geekbot\Permission;
use Geekbot\Utils;
use Geekbot\Settings;

class guildSettings implements messageCommand{
    public static function getName() {
        return "~settings";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        if(Permission::isAdmin($message)) {
            switch ($messageArray[1]) {

                case "blacklist":
                    if ($messageArray[2] == "add") {
                        BlackList::add($message->mentions[0]->id);
                        $message->reply("<@{$message->mentions[0]->id}> was added to the blacklist");
                    } elseif ($messageArray[2] == "remove") {
                        BlackList::remove($message->mentions[0]->id);
                        $message->reply("<@{$message->mentions[0]->id}> was removed from the blacklist");
                    }
                    break;

                case "botrole":
                    Permission::setGuildPermission('botRole', $messageArray[2]);
                    $message->reply("The bot Role has been set to {$messageArray[2]}, people without this role can use geekbot!");
                    break;
                case "privatemode":
                    if ($messageArray[2] == "enable") {
                        Settings::setGuildSetting("private", "true");
                        $message->reply(Settings::getGuildSetting("private"));
                    } elseif ($messageArray[2] == "disable") {
                        Settings::setGuildSetting("private", "null");
                    }
                    break;
            }
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
