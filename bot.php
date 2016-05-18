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

include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/System/Database.php';
include __DIR__ . '/System/Commands.php';
include __DIR__ . '/System/Reactions.php';
include __DIR__ . '/System/Utils.php';
include __DIR__ . '/Commands/commandInterface.php';

use Discord\Discord;
use Discord\WebSockets\WebSocket;
use Geekbot\KeyStorage;
use Geekbot\CommandsContainer;
use Geekbot\Database;

$envjson = file_get_contents('env.json');
$settings = json_decode($envjson);

$discord = new Discord($settings->token);
$ws = new WebSocket($discord);
$cc = new CommandsContainer('nya');
//print_r($cc->getCommands());


date_default_timezone_set('Europe/Amsterdam');

$gbdb = new Database('victoriadb');

// if ($settings->leveldb == 'true') {
//     echo("using leveldb...\n");
//     $db = new LevelDB(__DIR__ . '/db');
// } else {
//     echo("using victoriadb...\n");
//     $db = new KeyStorage();
// }


$ws->on('ready', function ($discord) use ($ws, $settings, $db, $discord, $cc) {
    $discord->updatePresence($ws, "Ping Pong", 0);
    echo "bot is ready!" . PHP_EOL;

    $ws->on('message', function ($message) use ($settings, $db, $ws, $discord, $cc) {
        
        $cm = CommandsContainer::checkCommand($message);
        if($cc->commandExists($cm)){
            $nya = $cc->getCommands();
            if(in_array('Geekbot\Commands\basicCommand', class_implements($cc->getCommand($cm)))) {
                $message->reply($nya[$cm]::runCommand());
            } else if(in_array('Geekbot\Commands\messageCommand', class_implements($cc->getCommand($cm)))) {
                $result = $nya[$cm]::runCommand($message);
                if(is_string($command)){
                    $message->reply($result);
                } else {
                    $message = $result;
                }
            }         
        }
        else {
            $reactions = new Geekbot\Reactions($message);   
            if(method_exists($reactions, $cm)) {
                $reactions->{$cm}();
                $message = $reactions->getMessage();
            }
        }
        
        $reply = $message->timestamp->format('d/m/y H:i:s') . ' - ';
        $reply .= $message->author->username . ' - ';
        $reply .= $message->content;
        echo $reply . PHP_EOL;
    });
}
);

$ws->on('error', function ($error, $ws) {
    print($error);
    exit(1);
}
);

$ws->run();