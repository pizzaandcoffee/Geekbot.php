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

$dbtype = Settings::envGet('sys.database');
if ($dbtype == 'redis'){
    $geekbot_db = new \Redis();
    $geekbot_db->connect(Settings::envGet('redis.host'), Settings::envGet('redis.port'));
} elseif ($dbtype == 'json'){
    $geekbot_db = new JsonDB(__DIR__ . Settings::envGet('json.path'));
} else {
    die("please set database value in your .env to either 'json' or 'redis'");
}
 
class Database{

    /**
     * @param string|int $key The database key or storage location
     * @param array $value the array that should be in there
     * @return bool
     */
    public static function set($key, $value, $other=null){
        global $geekbot_db;
        $options = ['user', 'member', 'guild', 'global'];
        $loc = "failed";
        if (in_array($key, $options)){
            switch ($key){
                case "user":
                    $loc = '0-'.$GLOBALS['userid'];
                    break;
                case "member":
                    if($other == null){
                        $loc = $GLOBALS['dblocation'];
                    } else {
                        $loc = $GLOBALS['guildid'].'-'.$other;
                    }
                    break;
                case "guild":
                    $loc = $GLOBALS['guildid'];
                    break;
                case "global":
                    $loc = "global";
            }

        } else {
            echo "!!! Failed to store data !!!\n";
        }
        $data = json_encode($value);
        $geekbot_db->set($loc, $data);
        $geekbot_db->save();
        return true;
    }

    /**
     * @param string|int $key The database key or storage location
     * @return array
     */
    public static function get($key, $other=null){
        global $geekbot_db;
        $options = ['user', 'member', 'guild', 'global'];
        $loc = "failed";
        if (in_array($key, $options)){
            switch ($key){
                case "user":
                    $loc = '0-'.$GLOBALS['userid'];
                    break;
                case "member":
                    if($other == null){
                        $loc = $GLOBALS['dblocation'];
                    } else {
                        $loc = $GLOBALS['guildid'].'-'.$other;
                    }
                    break;
                case "guild":
                    $loc = $GLOBALS['guildid'];
                    break;
                case "global":
                    $loc = "global";
            }

        } else {
            echo "!!! Failed to get data !!!\n";
        }
        $data = json_decode($geekbot_db->get($loc));
        return $data;
    }

    /**
     * @param string|int $key The database key or storage location
     * @return bool
     */
    public static function delete($key){
        global $geekbot_db;
        $geekbot_db->del($key);
        $geekbot_db->save();
        return true;
    }
    
}
