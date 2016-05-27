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
use Geekbot\Utils;
use Geekbot\Stats;

//ToDo: make this extendable

class badJokeCounter implements messageCommand{
    public static function getName() {
        return "!bad";
    }

    public function getDescription() {
        return "a bad joke counter";
    }

    public function getHelp() {
        return $this->getDescription() . "
            usage:
            !bad [@mention]";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        $guildID = $message->channel->guild_id;
        if(isset($messageArray[1]) && Utils::startsWith($messageArray[1], "<@")){
            $userID = $message->mentions[0]->id;
            $dblocation = $guildID."-".$userID;
            $userdata = Database::get($dblocation);
            if(!isset($userdata->badJokes)){
                $userdata->badJokes = 0;
            }
            $userdata->badJokes = $userdata->badJokes + 1;
            Database::set($dblocation, $userdata);
            $message->channel->sendMessage("<@{$userID}> made a bad joke!");
        } else {
            $message->channel->sendMessage($this->getHelp());
        }
        return $message;
    }

}