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
 
include __DIR__.'/VictoriaDB.php';
 
class Database{

    public static function set($key, $value){
        $type = Utils::settingsGet('database');
        if ($type == 'redis'){
            $redis = new \Redis();
            $db = $redis->connect('localhost', '6379');
        } else {
            $db = new VictoriaDB(__DIR__ . '/db');
        }
        $db->set($key, $value);
        $db->save();
        return true;
    }

    public static function get($key){
        $type = Utils::settingsGet('database');
        if ($type == 'redis'){
            $redis = new \Redis();
            $connection = $redis->connect('localhost', '6379');
        } else {
            $connection = new VictoriaDB(__DIR__ . '/db');
        }
        return $connection->get($key);
    }

    public static function delete($key){
        $type = Utils::settingsGet('database');
        if ($type == 'redis'){
            $redis = new \Redis();
            $db = $redis->connect('localhost', '6379');
        } else {
            $db = new VictoriaDB(__DIR__ . '/db');
        }
        $db->del($key);
        $db->save();
        return true;
    }
    
}
