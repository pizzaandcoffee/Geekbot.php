<?php

namespace Geekbot;

class KeyStorage
{
    private function check(){
        if(!file_exists(__DIR__.'/db')){
            mkdir(__DIR__.'/db');
        }
        if(!file_exists(__DIR__.'/db/db.json')){
            fopen(__DIR__.'/db/db.json', 'w');
        }
        return true;
    }

    public function put($name, $data){
        $where = __DIR__.'/db/db.json';
        if($this->check()) {
            $get_settings = file_get_contents($where);
            $decode_settings = json_decode($get_settings);
            $decode_settings->{$name} = $data;
            $new = json_encode($decode_settings);
            file_put_contents($where, $new);
        }
    }

    public function get($name){
        $where = __DIR__.'/db/db.json';
        $get_settings = file_get_contents($where);
        $decode_settings = json_decode($get_settings);
        try {
            $value = $decode_settings->{$name};
            return $value;
        }
        catch(Exception $e){
            return 0;
        }
    }
}