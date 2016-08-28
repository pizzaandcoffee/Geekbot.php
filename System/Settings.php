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
        $guildSettings = Database::get('guild');
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
        $guildSettings = Database::get('guild');
        if(!isset($guildSettings->$key)){
            $guildSettings->$key = "null";
        }
        $guildSettings->$key = $value;
        Database::set('guild', $guildSettings);
        return true;
    }

    /**
     * @param string $key the settings you need
     * @return string|int|array
     */
    public static function getUserSetting($key){
        $userSettings = Database::get('user');
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
        $userSettings = Database::get('user');
        if(!isset($userSettings->$key)){
            $userSettings->$key = 0;
        }
        $userSettings->$key = $value;
        Database::set('user', $userSettings);
        return true;
    }

    /**
     * @param string $key the settings you need
     * @return string|int|array
     */
    public static function getMemberSetting($key){
        $userSettings = Database::get('member');
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
    public static function setMemberSetting($key, $value){
        $userSettings = Database::get('member');
        if(!isset($userSettings->$key)){
            $userSettings->$key = 0;
        }
        $userSettings->$key = $value;
        Database::set('member', $userSettings);
        return true;
    }

    /**
     * @param string $key the name of the value you need
     * @return string
     */
    public static function envGet($key){
        $envfile = fopen(__DIR__."/../.env", 'r');
        $value = null;
        if($envfile){
            while(!feof($envfile)){
                $line = fgets($envfile);
                $arr = explode('=', $line);
                if(isset($arr[0]) && isset($arr[1])){
                    if($arr[0] == $key){
                        $value = $output = str_replace(array("\r\n", "\r", "\n"), "", $arr[1]);
                        break;
                    }
                }
            }
        }
        if($value == null){
            echo "{$key} not found in env file, returning empty string\n";
        }
        return $value;
    }

}