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
        
class Test implements basicCommand{
    public static function getName() {
        return "!test";
    }

    public static function runCommand() {
        return "test";
    }
}

class Test2 implements messageCommand{
    public static function getName() {
        return "!test2";
    }

    public static function runCommand($message) {
        return "test2";
    }
}

class MessageTest implements messageCommand{
    public static function getName() {
        return "!msg";
    }

    public static function runCommand($message) {
        $m = $message;
        $m->reply("message!");
        return $m;
    }
}