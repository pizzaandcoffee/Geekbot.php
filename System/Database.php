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
 
include __DIR__ . '/JsonDB.php';

use Geekbot\Settings;

$dbtype = Settings::envGet('database');
if ($dbtype == 'redis'){
    $redis = new \Redis();
    $db = $redis->connect('localhost', '6379');
} elseif ($dbtype == 'json'){
    $db = new JsonDB(__DIR__ . '/db');
} else {
    die("please set database value in your env.json to either 'json' or 'redis'");
}
 
class Database{

    public static function set($key, $value){
        global $db;
        $data = json_encode($value);
        $db->set($key, $data);
        $db->save();
        return true;
    }

    public static function get($key){
        global $db;
        $data = json_decode($db->get($key));
        return $data;
    }

    public static function delete($key){
        global $db;
        $db->del($key);
        $db->save();
        return true;
    }
    
}
