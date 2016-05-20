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
use Geekbot\VictoriaDB;
use Geekbot\CommandsContainer;
use Geekbot\Database;

echo 'Geekbot' .PHP_EOL;
echo '' .PHP_EOL;

class Bot {
    private $discord;
    private $ws;
    private $commands;
    
    function __construct() {

        $this->discord = new Discord(\Geekbot\Utils::settingsGet('token'));
        $this->ws = new WebSocket($this->discord);
        $this->commands = new CommandsContainer();


        date_default_timezone_set('Europe/Amsterdam');
        
        $this->initSocket();
    }
    
    
    function initSocket() {
        $this->ws->on('ready', function ($discord){
            $discord->updatePresence($this->ws, "Ping Pong", 0);
            echo "bot is ready!" . PHP_EOL;

            $this->ws->on('message', function ($message) {

                $cm = CommandsContainer::checkCommand($message);
                if($this->commands->commandExists($cm)){
                    $nya = $this->commands->getCommands();
                    if(in_array('Geekbot\Commands\basicCommand', class_implements($this->commands->getCommand($cm)))) {
                        $message->reply($nya[$cm]->runCommand());
                    } else if(in_array('Geekbot\Commands\messageCommand', class_implements($this->commands->getCommand($cm)))) {
                        $result = $nya[$cm]->runCommand($message);
                        if(is_string($result)){
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

        $this->ws->on('error', function ($error, $ws) {
            print($error);
            exit(1);
        }
        );
    }
   
    function run(){
        $this->ws->run();
    }
}

$bot = new Bot();
$bot->run();
