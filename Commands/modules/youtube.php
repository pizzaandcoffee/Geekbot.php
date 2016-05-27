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

use Geekbot\Settings;
use Madcoda\Youtube;

class yt implements messageCommand{
    public static function getName() {
        return "!yt";
    }

    public function getDescription() {
        return "searches on youtube and return the first result";
    }

    public function getHelp() {
        return $this->getDescription() . "
        usage:
            !yt [some video]";
    }

    public function runCommand($message) {
        try {
            $y = new Youtube(array('key' => Settings::envGet('ytkey')));
            $s = $y->searchVideos(substr($message->content, 4));
            $message->channel->sendMessage("'" . $s[0]->snippet->title . "' from '" . $s[0]->snippet->channelTitle . "'\nhttps://www.youtube.com/watch?v=" . $s[0]->id->videoId);
        } catch(\Exception $e){
            $message->reply("you can not use the youtube command because the api key is not set");
        }
        return $message;
    }
}