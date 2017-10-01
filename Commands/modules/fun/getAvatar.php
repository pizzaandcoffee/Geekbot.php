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

class getAvatar implements messageCommand{
    public static function getName() {
        return "!avatar";
    }

    public function getDescription() {
        return "return someones avatar";
    }

    public function getHelp() {
        return $this->getDescription() . "
        usage:
            !avatar [@user]";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);

        if (isset($messageArray[1]) && Utils::startsWith($messageArray[1], "<@")){
            $author = $message->author;
            $mentions = $message->getMentionRolesAttribute();
            print("whatever");
            $message->channel->sendMessage($author->avatar);
        }

        return $message;
    }

}