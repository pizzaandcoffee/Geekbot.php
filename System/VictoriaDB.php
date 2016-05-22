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

class VictoriaDB
{
    private $folder;
    
    function __construct($folder){
        $this->folder = $folder;
    }
    
    private function check(){
        if(!file_exists($this->folder)){
            mkdir($this->folder);
        }
        if(!file_exists($this->folder.'/db.json')){
            fopen($this->folder.'/db.json', 'w');
        }
        return true;
    }

    public function set($name, $data){
        $where = $this->folder.'/db.json';
        if($this->check()) {
            $get_settings = file_get_contents($where);
            $decode_settings = json_decode($get_settings);
            $decode_settings->{$name} = $data;
            $new = json_encode($decode_settings);
            file_put_contents($where, $new);
        }
    }

    public function get($name){
        $where = $this->folder.'/db.json';
        $get_settings = file_get_contents($where);
        $decode_settings = json_decode($get_settings);
        $value = $decode_settings->{$name};
        if($value == ""){
            $value = null;
        }
        return $value;
    }

    public function del($name){
        $where = $this->folder.'/db.json';
        $get_settings = file_get_contents($where);
        $decode_settings = json_decode($get_settings);
        unset($decode_settings->{$name});
        $new = json_encode($decode_settings);
        file_put_contents($where, $new);
    }

    public function save(){
        //does nothing, only here for redis compatibility...
    }
}