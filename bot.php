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
include __DIR__ . '/DB.php';
include __DIR__ . '/Commands.php';
include __DIR__ . '/Reactions.php';
include __DIR__ . '/Utils.php';

use Discord\Discord;
use Discord\WebSockets\WebSocket;
use Geekbot\KeyStorage;
use Geekbot\Commands;

$envjson = file_get_contents('env.json');
$settings = json_decode($envjson);

$discord = new Discord($settings->token);
$ws = new WebSocket($discord);

date_default_timezone_set('Europe/Amsterdam');

if ($settings->leveldb == 'true') {
    echo("using leveldb...\n");
    $db = new LevelDB(__DIR__ . '/db');
} else {
    echo("using victoriadb...\n");
    $db = new KeyStorage();
}

$ws->on('ready', function ($discord) use ($ws, $settings, $db, $discord) {
    $discord->updatePresence($ws, "Ping Pong", 0);
    echo "bot is ready!" . PHP_EOL;

    $ws->on('message', function ($message) use ($settings, $db, $ws, $discord) {
        
        #
        #   Command Handler
        #
        
        if ($message->author->id != $settings->botid){
            $commands = new Geekbot\Commands($message, $db, $settings, new Geekbot\Utils);
            $reactions = new Geekbot\Reactions($message);
            
            //is Command or Debug Command
            if(substr($commands->getA()[0], 0, 1) == "!") {
                if(method_exists($commands, substr($commands->getA()[0], 1))) {
                    $commands->{substr($commands->getA()[0], 1)}();
                    $message = $commands->getMessage();
                }
            } else {
                if(method_exists($reactions, $commands->getA()[0])) {
                   $reactions->{$commands->getA()[0]}();
                   $message = $reactions->getMessage();
                }
            }
            
        }

        #
        #   Output message to console
        #

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