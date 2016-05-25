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

use Discord\Voice\VoiceClient;
use Geekbot\Database;
use Geekbot\Utils;

class Settings{

    public static function getGuildSetting($message, $key){
        $guildID = $message->channel->guild_id;
        $guildSettings = Database::get($guildID);
        if(!isset($guildSettings->$key)){
            return "null";
        } else {
            return $guildSettings->$key;
        }
    }

    public static function setGuildSetting($message, $key, $value){
        $guildID = $message->channel->guild_id;
        $guildSettings = Database::get($guildID);
        if(!isset($guildSettings->$key)){
            $guildSettings->$key = "null";
        }
        $guildSettings->$key = $value;
        Database::set($guildID, $guildSettings);
        return true;
    }

    public static function getUserSetting($message, $key){
        $authorID = $message->author->id;
        $guildID = $message->channel->guild_id;
        $dbLocation = $guildID.'-'.$authorID;
        $userSettings = Database::get($dbLocation);
        if(!isset($userSettings->$key)){
            return 0;
        } else {
            return $userSettings->$key;
        }
    }

    public static function setUserSetting($message, $key, $value){
        $authorID = $message->author->id;
        $guildID = $message->channel->guild_id;
        $dbLocation = $guildID.'-'.$authorID;
        $userSettings = Database::get($dbLocation);
        if(!isset($userSettings->$key)){
            $userSettings->$key = 0;
        }
        $userSettings->$key = $value;
        Database::set($dbLocation, $userSettings);
        return true;
    }

    public static function envGet($key){
        $envjson = file_get_contents(__DIR__ . "/../env.json");
        $settings = json_decode($envjson);
        if(isset($settings->{$key})){
            $value = $settings->{$key};
        } else {
            echo("setting '{$key}' is not found, returning 'null' instead\n");
            $value = "null";
        }
        return $value;
    }

}