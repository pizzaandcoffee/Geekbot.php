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

class coin implements basicCommand{
    public static function getName() {
        return "!coin";
    }

    public function getDescription() {
        return "flip a coin";
    }

    public function getHelp() {
        return "!coin";
    }
    
    public function runCommand() {
        $number =['heads', 'tails'];
        $thereply = "you flipped ".$number[array_rand($number)];
        return $thereply;
    }

}

class dice implements basicCommand{
    public static function getName() {
        return "!dice";
    }

    public function getDescription() {
        return "roll a dice";
    }

    public function getHelp() {
        return "!dice";
    }

    public function runCommand() {
        $number = random_int(1, 6);
        $thereply = "you threw {$number}";
        return $thereply;
    }

}

class choose implements messageCommand{
    public static function getName() {
        return "!choose";
    }

    public function getDescription() {
        return "let geekbot make a decide for you!";
    }

    public function getHelp() {
        return "!choose [option1] [option2] ([more options])";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        if (isset($message[1]) && $messageArray[1] == 'help') {
            $this->getHelp();
        } elseif (isset($messageArray[1]) && isset($messageArray[2])) {
            unset($messageArray[0]);
            $thechoice = "my choice is '{$messageArray[array_rand($messageArray)]}'";
            $message->reply($thechoice);
        } else {
            $message->reply('please provide atleast 2 options');
        }
        return $message;
    }

}

class idiot implements messageCommand{
    public static function getName() {
        return "!idiot";
    }

    public function getDescription() {
        return "lets you see what the illusion thinks";
    }

    public function getHelp() {
        return "!idiot";
    }

    public function runCommand($message) {
        if ($message->author->id == '93421536890859520') {
            $message->channel->sendMessage("die Halluzination findet euch alle BEKLOPPT!");
        } else {
            $message->reply("du bist KEINE HALLUZINATION\n*triggered*");
        }
        return $message;
    }

}