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
    
    function __construct($type, $host='localhost', $username='geekbot', $password='geekbot', $post='3306', $location=null){
        $types = ['redis', 'leveldb', 'victoriadb'];
        
        if(in_array($type, $types)){
            $this->type = $type;
        }
        
        switch($type){
            case "redis":
                try{
                    //init redis here
                } catch (Exception $e) {
                    die("Redis PHP Extension is not Installed\n {$e}");
                }
                break;
            
            case "leveldb":
                try {
                    $this->connection = new LevelDB(__DIR__ . '/db');
                } catch (Exception $e) {
                    die("LevelDB PHP Extension is not Installed\n {$e}");
                }
                break;
            
            case "victoriadb":
                $this->connection = new VictoriaDB(__DIR__.'/db');
                break;
        }
    }
        
        public function put($key, $value){
            switch ($this->type){
                case "redis":
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
                    $this->connection->get($key);
                    break;
                case "leveldb":
                    $this->connection->get($key);
                    break;
                case "victoriadb":
                    $this->connection->get($key);
                    break;
            }
        }
        
        public function delete($key){
            switch ($this->type){
                case "redis":
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
