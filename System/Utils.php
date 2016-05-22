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

class Utils{
    
    public static function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public static function xml_attribute($object, $attribute) {
        if (isset($object[$attribute])) {
            return (string)$object[$attribute];
        }
        return null;
    }
    
    public static function includeFolder($folder) {
        $dir = $folder;
        $commands = scandir($dir);
        array_shift($commands);
        array_shift($commands);
        foreach($commands as $command){
            include $dir.'/'.$command;
        }
    }

    public static function messageSplit($message){
        $oa = preg_replace('/\s+/', ' ', strtolower($message->content));
        $a = explode(' ', $oa);
        return $a;
    }

    public static function isHelp($messageArray){
        if (isset($messageArray[1]) && $messageArray[1] == "help") {
            return true;
        } else {
            return false;
        }
    }

    public static function settingsGet($key){
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
    
    public static function getSettingsArray(){
        $envjson = file_get_contents(__DIR__ . "/../env.json");
        $settings = json_decode($envjson);
        return $settings;
    }
    
    public static function playSound($sound, $channel){
        global $bot;
        $ws = $bot->ws;
        $ws->joinVoiceChannel($channel)->then(function (VoiceClient $vc) use ($ws, $sound) {
            $vc->setFrameSize(40)->then(function () use ($vc, $ws, $sound) {
                $vc->playFile($sound);
            });
        });
    }

    public static function getFile($fileName){
        $file = __DIR__.'/../Storage/'.$fileName;
        if (file_exists($file)){
            return file_get_contents($file);
        } else {
            echo("the file '{$fileName}' does not exist");
            return null;
        }
    }

    public static function storeFile($fileName, $contents){
        $file = __DIR__.'/../Storage/'.$fileName;
        file_put_contents($file, $contents);
        return true;
    }
    
    public static function getCommand($message) {
        $command = explode(' ', $message->content);
        
        return $command[0];
    }

    public static function getGuildOption($message, $what){
        $guildID = $message->channel->guild_id;
        $settingsRaw = Database::get($guildID."-settings");
        $settings = \GuzzleHttp\json_decode($settingsRaw);
        if(isset($settings->{$what})){
            return $settings->{$what};
        } else {
            return "null";
        }
    }

    public static function setGuildOption($message, $what, $value){
        $guildID = $message->channel->guild_id;
        $settingsRaw = Database::get($guildID."-settings");
        $settings = \GuzzleHttp\json_decode($settingsRaw);
        $settings->{$what} = $value;
        $settingsNew = \GuzzleHttp\json_encode($settings);
        Database::set($guildID."-settings", $settingsNew);
        return true;
    }
}