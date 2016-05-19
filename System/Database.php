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

use Geekbot\VictoriaDB;
 
class Database{
    private $type;
    private $connection;
    
    function __construct($type, $host='localhost', $port='6379', $password='geekbot', $location=__DIR__ . '/db'){
        $types = ['redis', 'leveldb', 'victoriadb', 'memcached'];
        
        if(in_array($type, $types)){
            $this->type = $type;
        }
        
        switch($type){
            case "redis":
                print_r("using redis as database\n");
                try{
                    $redis = new \Redis();
                    if($redis->connect($host, $port)){
                        $this->connection = $redis;
                        print_r("successfully connected to redis!\n");
                    } else {
                        die("failed to connect to Redis\n");
                    }
                } catch (Exception $e) {
                    die("Redis PHP Extension is not Installed\n {$e}");
                }
                break;

            case 'memcached':
                print_r("using memcached as database\n");
                $mem = new \Memcached();
                if($mem->addServer($host, $port)){
                    $this->connection = $mem;
                    print_r("successfully connected to memcached\n");
                } else {
                    die("failed to connect to memcached \n");
                }
                break;

            case "leveldb":
                $this->type = 'leveldb';
                print_r("using leveldb as database\n");
                try {
                    $this->connection = new LevelDB($location);
                } catch (Exception $e) {
                    die("LevelDB PHP Extension is not Installed\n {$e}");
                }
                break;
            
            case "victoriadb":
                $this->type = 'victoriadb';
                print_r("using victoriadb as database\n");
                $this->connection = new VictoriaDB($location);
                break;
        }
    }
        
        public function put($key, $value){
            switch ($this->type){
                case "redis":
                    $this->connection->set($key, $value);
                    break;
                case "memcached":
                    $this->connection->set($key, $value);
                    break;
                case "leveldb":
                    $this->connection->put($key, $value);
                    break;
                case "victoriadb":
                    $this->connection->put($key, $value);
                    break;
            }
        }
        
        public function get($key){
            switch ($this->type){
                case "redis":
                    return $this->connection->get($key);
                    break;
                case "memcached":
                    return $this->connection->get($key);
                    break;
                case "leveldb":
                    return $this->connection->get($key);
                    break;
                case "victoriadb":
                    return $this->connection->get($key);
                    break;
            }
        }
        
        public function delete($key){
            switch ($this->type){
                case "redis":
                    $this->connection->del($key);
                    break;
                case "memcached":
                    $this->connection->del($key);
                    break;
                case "leveldb":
                    $this->connection->del($key);
                    break;
                case "victoriadb":
                    $this->connection->del($key);
                    break;
            }
        }
    
    }
