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

    /**
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    /**
     * @param $object
     * @param $attribute
     * @return null|string
     */
    public static function xml_attribute($object, $attribute) {
        if (isset($object[$attribute])) {
            return (string)$object[$attribute];
        }
        return null;
    }

    /**
     * @param string $folder
     */
    public static function includeFolder($folder) {
        $dir = $folder;
        $commands = scandir($dir);
        array_shift($commands);
        array_shift($commands);
        foreach($commands as $command){
            include $dir.'/'.$command;
        }
    }

    /**
     * @param array $message the message object
     * @return array
     */
    public static function messageSplit($message){
        $oa = preg_replace('/\s+/', ' ', strtolower($message->content));
        $a = explode(' ', $oa);
        return $a;
    }

    /**
     * @param array $messageArray
     * @return bool
     */
    public static function isHelp($messageArray){
        if (isset($messageArray[1]) && $messageArray[1] == "help") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $sound
     * @param $channel
     */
    public static function playSound($sound, $channel){
        global $bot;
        $ws = $bot->ws;
        $ws->joinVoiceChannel($channel)->then(function (VoiceClient $vc) use ($ws, $sound) {
            $vc->setFrameSize(40)->then(function () use ($vc, $ws, $sound) {
                $vc->playFile($sound);
            });
        });
    }

    /**
     * get a file from the Storage
     * @param string $fileName
     * @return null|string
     */
    public static function getFile($fileName){
        $file = __DIR__.'/../Storage/'.$fileName;
        if (file_exists($file)){
            return file_get_contents($file);
        } else {
            echo("the file '{$fileName}' does not exist");
            return null;
        }
    }

    /**
     * @param string $fileName
     * @param $contents
     * @return bool
     */
    public static function storeFile($fileName, $contents){
        $file = __DIR__.'/../Storage/'.$fileName;
        file_put_contents($file, $contents);
        return true;
    }

    /**
     * @param $message
     * @return mixed
     */
    public static function getCommand($message) {
        $command = explode(' ', strtolower($message->content));
        
        return $command[0];
    }
}