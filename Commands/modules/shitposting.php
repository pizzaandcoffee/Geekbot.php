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

class coin implements basicCommand{
    public static function getName() {
        return "!coin";
    }

    public function runCommand() {
        $number =['heads', 'tails'];
        $thereply = "you flipped ".$number[array_rand($number)];
        return $thereply;
    }

    public function getDescription() {
        return "flip a coin";
    }

    public function getHelp() {
        return "!coin";
    }

}

class dice implements basicCommand{
    public static function getName() {
        return "!dice";
    }

    public function runCommand() {
        $number = random_int(1, 6);
        $thereply = "you threw {$number}";
        return $thereply;
    }

    public function getDescription() {
        return "roll a dice";
    }

    public function getHelp() {
        return "!dice";
    }

}