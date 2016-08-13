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
use Geekbot\Stats;

//ToDo: make this extendable

class statistics implements messageCommand{
    public static function getName() {
        return "!stats";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        if(isset($messageArray[1]) && Utils::startsWith($messageArray[1], "<@")){
            $userID = $message->mentions->first()->id;
            $message->channel->sendMessage("Here are the Stats for <@{$userID}>:".PHP_EOL.
            "```".PHP_EOL.
            "Level:         ".Stats::getLevel($userID).PHP_EOL.
            "Sent Messages: ".Stats::getAmountOfMessages($userID).PHP_EOL.
            "Class:         ".Stats::getClass($userID).PHP_EOL.
            "Bad Jokes:     ".Stats::getBadJokes($userID).PHP_EOL.
            "Last Message:  ".Stats::getLastMessage($userID).PHP_EOL.
            "```");
        }
        return $message;
    }

    public function getDescription() {
        return "shows stats for the mentioned user";
    }

    public function getHelp() {
        return "!stats";
    }

}