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

class Settings{

    /**
     * @param string $key the settings you need
     * @return string|int|array
     */
    public static function getGuildSetting($key){
        $guildSettings = Database::get($GLOBALS['guildid']);
        if(!isset($guildSettings->$key)){
            return "null";
        } else {
            return $guildSettings->$key;
        }
    }

    /**
     * @param string $key the settings you want to change
     * @param array $value the value of the setting (can be an array too)
     * @return bool
     */
    public static function setGuildSetting($key, $value){
        $guildSettings = Database::get($GLOBALS['guildid'] );
        if(!isset($guildSettings->$key)){
            $guildSettings->$key = "null";
        }
        $guildSettings->$key = $value;
        Database::set($GLOBALS['guildid'] , $guildSettings);
        return true;
    }

    /**
     * @param string $key the settings you need
     * @return string|int|array
     */
    public static function getUserSetting($key){
        $userSettings = Database::get($GLOBALS['dblocation']);
        if(!isset($userSettings->$key)){
            return 0;
        } else {
            return $userSettings->$key;
        }
    }

    /**
     * @param string $key the settings you want to change
     * @param string|int $value the value of the setting (can be an array too)
     * @return bool
     */
    public static function setUserSetting($key, $value){
        $userSettings = Database::get($GLOBALS['dblocation']);
        if(!isset($userSettings->$key)){
            $userSettings->$key = 0;
        }
        $userSettings->$key = $value;
        Database::set($GLOBALS['dblocation'], $userSettings);
        return true;
    }

    /**
     * @param string $key the name of the value you need
     * @return string
     */
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