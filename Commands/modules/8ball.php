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

class eightball implements messageCommand{
    public static function getName() {
        return "!8ball";
    }

    public function getDescription() {
        return "ask 8ball something";
    }

    public function getHelp() {
        return $this->getDescription() . "
        usage:
        !8ball [question]";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        if (Utils::isHelp($messageArray)) {
            $message->channel->sendMessage($this->getHelp());
        } elseif (isset($messageArray[1])) {
            $ballanswers = ['It is certain', 'It is decidedly so', 'Without a doubt', 'Yes, definitely', 'You may rely on it',
                'As I see it, yes', 'Most likely', 'Outlook good', 'Yes', 'Signs point to yes', 'Reply hazy try again',
                'Ask again later', 'Better not tell you now', 'Cannot predict now', 'Concentrate and ask again', "Don't count on it",
                'My reply is no', 'My sources say no', 'Outlook not so good', 'Very doubtful'];
            $message->reply($ballanswers[array_rand($ballanswers)]);
        } else {
            $message->reply('you must ask a question!');
        }
        return $message;
    }

}
