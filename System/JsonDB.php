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

class JsonDB
{
    private $folder;
    
    function __construct($folder){
        $this->folder = $folder;
    }
    
    private function check($key){
        if(!file_exists($this->folder)){
            mkdir($this->folder);
        }
        if(!file_exists($this->folder.'/'.$key.'.json')){
            fopen($this->folder.'/'.$key.'.json', 'w');
        }
        return true;
    }

    /**
     * @param string|int $name The name of the database key
     * @param array $data The data to store
     * @return bool
     */
    public function set($name, $data){
        $where = $this->folder.'/'.$name.'.json';
        if($this->check($name)) {
            file_put_contents($where, $data);
        }
        return true;
    }

    /**
     * @param string|int $name The name of the database key
     * @return string
     */
    public function get($name){
        if(file_exists($this->folder.'/'.$name.'.json')){
            $where = $this->folder.'/'.$name.'.json';
            $get_settings = file_get_contents($where);
            return $get_settings;
        } else {
            return "{}";
        }
    }

    /**
     * @param string|int $name The name of the database key
     * @return bool
     */
    public function del($name){
        $where = $this->folder.'/'.$name.'.json';
        $this->del($where);
        return true;
    }

    /**
     * does nothing, only here for redis compatibility...
     */
    public function save(){
        return true;
    }
}