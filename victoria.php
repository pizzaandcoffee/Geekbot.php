<?php

namespace Victoria;

class VictoriaDB
{

    private function check($name){
        $where = __DIR__.'/Victoria/db/'.$name.'.json';
        if(file_exists($where)){
            if ($this->check_column_list($name)){
                return true;
            } else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    private function check_column_list($name){
        $indexfile = __DIR__.'/Victoria/db/columnlist.json';
        $get_conf = file_get_contents($indexfile);
        $decode_conf = json_decode($get_conf);
        if(isset($decode_conf->{$name}) || $name == "columnlist"){
            return true;
        } else {
            return false;
        }
    }

    private function add_table($name){
        $where = __DIR__.'/Victoria/db/'.$name.'.json';
        if(file_exists($where)){
            return true;
        }
        else{
            if(fopen($where, "w")){
                return true;
            }
            else{
                return false;
            }
        }
    }

    private function add_to_collumnlist($name, $collumns){
        $indexfile = __DIR__.'/Victoria/db/columnlist.json';
        if(!file_exists($indexfile)){
            if(!file_exists(__DIR__.'/Victoria/')){
                mkdir(__DIR__.'/Victoria/');
            }
            if(!file_exists(__DIR__.'/Victoria/db/')){
                mkdir(__DIR__.'/Victoria/db/');
            }
            fopen($indexfile, 'w');
        }
        $get_conf = file_get_contents($indexfile);
        $decode_conf = json_decode($get_conf);
        $decode_conf->{$name} = $collumns;
        $new = json_encode($decode_conf);
        file_put_contents($indexfile, $new);
        return true;
    }

    public function create($name, $columns){
        if($name != 'columnlist') {
            if (is_array($columns)) {
                $this->add_to_collumnlist($name, $columns);
                $this->add_table($name);
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * check if array has correct indexes
     */
    public function put($table, $data){
        $where = __DIR__.'/Victoria/db/'.$table.'.json';
        if($this->check($table)){
            $get_collumns = $this->get_all('columnlist');
            $collumns_req = $get_collumns->{$table};
            $yes = 0;
            foreach ($collumns_req as $i){
                $yes++;
            }
            $reqcount = 0;
            foreach($collumns_req as $req => $value) {
                $name = $req;
                if (array_key_exists($name, $data)) {
                    if (!is_numeric($data[$name]) && $value == 'string'){
                        $reqcount++;
                    }
                    elseif (is_numeric($data[$name]) && $value == 'int') {
                        $reqcount++;
                    }
                }
            }

            if($reqcount == $yes) {
                $idcount = $this->get_all($table);
                $id = 0;
                foreach ($idcount as $r){
                    $id = $id+1;
                }
                $get_conf = file_get_contents($where);
                $decode_conf = json_decode($get_conf);
                $decode_conf->{$id} = $data;
                $new = json_encode($decode_conf);
                file_put_contents($where, $new);
                print_r($data);
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

    public function get_all($table){
        $where = __DIR__.'/Victoria/db/'.$table.'.json';
        if($this->check($table)) {
            $get_conf = file_get_contents($where);
            $decode_conf = json_decode($get_conf);
            return $decode_conf;
        }
        else {
            print $table.' | ';
        }
    }

    /**
     * TODO: Create Search Query
     */
    public function search($table, $string, $content){

    }

    public function update($table, $id){
        $where = __DIR__.'/Victoria/db/'.$table.'.json';
        if($this->check($table)) {
            $get_conf = file_get_contents($where);
            $decode_conf = json_decode($get_conf);
            $value = $decode_conf->{$id};
            return $value;
        }
        else {
            return "";
        }
    }

    public function exists($table){
        if (file_exists(__DIR__.'/Victoria/db/'.$table.'.json')){
            return true;
        }
        else{
            return false;
        }
    }

    public function drop($table){
        $where = __DIR__.'/Victoria/db/'.$table.'.json';
        unlink($where);
        return true;
    }

}

class VictoriaSettings
{
    private function check(){
        if(!file_exists(__DIR__.'/Victoria')){
            mkdir(__DIR__.'/Victoria');
        }
        if(!file_exists(__DIR__.'/Victoria/options.json')){
            fopen(__DIR__.'/Victoria/options.json', 'w');
        }
        return true;
    }

    public function put($name, $data){
        $where = __DIR__.'/Victoria/options.json';
        if($this->check()) {
            $get_settings = file_get_contents($where);
            $decode_settings = json_decode($get_settings);
            $decode_settings->{$name} = $data;
            $new = json_encode($decode_settings);
            file_put_contents($where, $new);
        }
    }

    public function get($name){
        $where = __DIR__.'/Victoria/options.json';
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