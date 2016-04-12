<?php
require __DIR__.'/../Victoria.php';
use Victoria\VictoriaDB;
$db = new \Victoria\VictoriaDB();

$files = preg_grep('/^([^.])/', scandir(__DIR__));

foreach($files as $file){
    print("processing file ".$file);
    $inputfile = fopen($file, 'r');
    $db->create($file, ['name'=>'string', 'dis'=>'string']);
    if($inputfile){
        while(($line = fgets($inputfile)) !== false){
            $currentline = explode(' - ', $line);
            $db->put($file, ['name'=>$currentline[0], 'dis'=>$currentline[1]]);
        }
        fclose($inputfile);
    }
}