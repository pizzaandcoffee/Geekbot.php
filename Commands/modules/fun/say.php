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

use Geekbot\Permission;

class say implements messageCommand{
    public static function getName() {
        return "!say";
    }

    public function getDescription() {
        return "geekbot will repeat what you said";
    }

    public function getHelp() {
        return "!say [your text]";
    }

    public function runCommand($message) {
        $perms = new Permission($message);
        if($perms->administrator) {
            $message->channel->sendMessage(substr($message->content, 5));
        }
        return $message;
    }
}