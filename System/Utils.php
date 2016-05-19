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

class Utils{
    
    public function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }

    public function xml_attribute($object, $attribute) {
        if (isset($object[$attribute])) {
            return (string)$object[$attribute];
        }
    }
    
    public function calculateLevel($messages) {
        $total = 0;
        $levels = [];
        for ($i = 1; $i < 100; $i++) {
            $total += floor($i + 300 * pow(2, $i / 7.0));
            $levels[] = floor($total / 16);
        }
        $level = 1;
        foreach ($levels as $l) {
            if ($l < $messages) {
                $level++;
            } else {
                break;
            }
        }
        return $level;
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
}