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
use Geekbot\Settings;
use Geekbot\Utils;

class overwatchapi implements messageCommand{
    public static function getName() {
        return "!overwatch";
    }

    public function getDescription() {
        return "show someones stats in overwatch";
    }

    public function getHelp() {
        return $this->getDescription() ."
        usage: 
            !overwatch [full-battletag|@mention] ([@mention])
        options:
            The second parameter is used to associate your discord account with a battletag
            That means that no matter what server you are on, geekbot will know what your
            battle tag is.
            !overwatch Someone#2131 @someone";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        $messageArrayCaseSensitive = Utils::messageSplit($message, true);

        // https://api.lootbox.eu/pc/eu/Rune-22303/profile

        if (Utils::isHelp($message)) {
            $message->channel->sendMessage($this->getHelp());
        } elseif (isset($messageArray[1])) {
            $battletag = null;

            if(Utils::startsWith($messageArray[1], '<@')){
                $battletag = Settings::getUserSetting('battletag');
            } elseif(str_contains($messageArray[1], '#')) {
                $battletag = str_replace('#', '-', $messageArrayCaseSensitive[1]);
            } else {
                $message->reply("something is wrong with your battletag...");
            }

            if($battletag != null){
                if(isset($messageArray[2]) && Utils::startsWith($messageArray[2], "<@")){
                    Settings::setUserSetting("battletag", $battletag);
                    $message->reply("{$messageArray[2]}'s battleag is now set to {$battletag}");
                } else {
                    $curloptions = array(
                        'http' => array(
                            'method' => "GET",
                            'header' => "Accept-language: en\r\n" .
                                "Cookie: foo=bar\r\n" .
                                "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
                        )
                    );
                    $curlcontext = stream_context_create($curloptions);
                    $rawdata = file_get_contents("https://api.lootbox.eu/pc/eu/{$battletag}/profile", false, $curlcontext);
                    $data = json_decode($rawdata);
                    if(isset($data->statusCode) && $data->statusCode == 404){
                        $message->reply("sorry but there is no user with that battletag");
                    } else {
                        $message->channel->sendMessage("```\n".
                            "-- General --\n".
                            "Player:  {$data->data->username}\n".
                            "Level:   {$data->data->level}\n".
                            "-- Quick Play --\n".
                            "Wins:    {$data->data->games->quick->wins}\n".
                            "Lost:    {$data->data->games->quick->lost}\n".
                            "Played:  {$data->data->games->quick->played}\n".
                            "-- Competitive --\n".
                            "Rank:    {$data->data->competitive->rank}\n".
                            "Wins:    {$data->data->games->competitive->wins}\n".
                            "Lost:    {$data->data->games->competitive->lost}\n".
                            "Played:  {$data->data->games->competitive->played}\n".
                            "-- Avatar --\n".
                            "{$data->data->avatar}\n".
                            "```");

                    }
                }
            }

        }
        return $message;
    }
}