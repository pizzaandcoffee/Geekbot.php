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

if(!file_exists("vendor/autoload.php")) {
    echo "please run \"composer install\" before running the bot";
    exit;
}
if(!file_exists("env.json")){
    echo "please configure your env.json before running the bot";
    exit;
}
include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/System/Utils.php';
include __DIR__ . '/System/Settings.php';
include __DIR__ . '/System/Permission.php';
include __DIR__ . '/System/Commands.php';
include __DIR__ . '/System/Database.php';
include __DIR__ . '/System/Reactions.php';
include __DIR__ . '/System/Stats.php';
include __DIR__ . '/Commands/commandInterface.php';

use Discord\Discord;
use Discord\WebSockets\WebSocket;
use Geekbot\CommandsContainer;

if(file_exists(".git/ORIG_HEAD")) {
    $githead = file_get_contents(".git/ORIG_HEAD");
    $version = "2.0 Beta - Build {$githead}";
} else {
    $version = "2.0 Beta";
}
define('GEEKBOT_VERSION', $version);

echo("  ____ _____ _____ _  ______   ___ _____\n");
echo(" / ___| ____| ____| |/ / __ ) / _ \\_   _|\n");
echo("| |  _|  _| |  _| | ' /|  _ \\| | | || |\n");
echo("| |_| | |___| |___| . \\| |_) | |_| || |\n");
echo(" \\____|_____|_____|_|\\_\\____/ \\___/ |_|\n");

echo "{$version}\n\n";

class Bot {
    public $discord;
    public $ws;
    private $commands;
    private $reactions;
    
    function __construct() {

        $this->discord = new Discord(\Geekbot\Settings::envGet('token'));
        $this->ws = new WebSocket($this->discord);
        $this->commands = new CommandsContainer();
        $this->reactions = new Geekbot\Reactions(); 
        if(\Geekbot\Settings::envGet('timezone') != "null") {
            date_default_timezone_set(\Geekbot\Settings::envGet('timezone'));
        }        
        $this->initSocket();
    }
    
    
    function initSocket() {
        $this->ws->on('ready', function ($discord){
            $discord->updatePresence($this->ws, \Geekbot\Settings::envGet('playing'), 0);
            echo "geekbot is ready!\n" . PHP_EOL;

            $this->ws->on('message', function ($message) use ($discord) {
                $GLOBALS['userid'] = $message->author->id;
                $GLOBALS['guildid'] = $message->channel->guild_id;
                $GLOBALS['dblocation'] = $GLOBALS['guildid'].'-'.$GLOBALS['userid'];
                
                $stats = new \Geekbot\Stats($message);

                if(\Geekbot\Permission::blacklistCheck($message)){
                    $command = \Geekbot\Utils::getCommand($message);
                    if($this->commands->commandExists($command)){
                        $commandslist = $this->commands->getCommands();
                        if(in_array('Geekbot\Commands\basicCommand', class_implements($this->commands->getCommand($command)))) {
                            $message->reply($commandslist[$command]->runCommand());
                        } else if(in_array('Geekbot\Commands\messageCommand', class_implements($this->commands->getCommand($command)))) {
                            $result = $commandslist[$command]->runCommand($message);
                            if(is_string($result)){
                                $message->reply($result);
                            } else {
                                $message = $result;
                            }
                        }
                    }
                    else {
                        $reaction = $this->reactions->getReaction(\Geekbot\Utils::getCommand($message), $message);
                        if( $reaction != NULL) {
                            $message->channel->sendMessage($reaction);
                        }
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
        });
    }
   
    function run(){
        $this->ws->run();
    }
}

$bot = new Bot();
$bot->run();
