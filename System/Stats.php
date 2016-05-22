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

namespace Geekbot;

class Stats{

    function __construct($message){
        //set a few variables
        $authorid = $message->author->id;
        $guild = $message->channel->guild_id;
        $dbLocation = $guild.'-'.$authorid;

        //get user info from db
        $rawUserData = Database::get($dbLocation);
        $userData = json_decode($rawUserData);
        
        //update stuff
        $userData->messages = $userData->messages + 1;
        $userData->lastmessage = date(DATE_RFC2822);

        //put it back into the db
        $rawUserData = json_encode($userData);
        Database::set($dbLocation, $rawUserData);

        //lets do the same for the guild itself
        $rawGuildData = Database::get($guild);
        $guildData = json_decode($rawGuildData);
        $guildData->messages = $guildData->messages + 1;
        $rawGuildData = json_encode($guildData);
        Database::set($guild, $rawGuildData);
    }

    public static function calculateLevel($messages) {
        $total = 0;
        $levels = [];
        for ($i = 1; $i < 100; $i++) {
            $total += floor($i + 300 * pow(2, $i / 7.0));
            $levels[] = floor($total / 16);
        }
        $level = 1;
        foreach ($levels as $l) {
            if ($l < $messages) {
                $level++;
            } else {
                break;
            }
        }
        return $level;
    }

    public static function getUserData($message){
        $authorid = $message->author->id;
        $guild = $message->channel->guild_id;
        $dbLocation = $guild.'-'.$authorid;
        $rawUserData = Database::get($dbLocation);
        $userData = json_decode($rawUserData);
        return $userData;
    }

}