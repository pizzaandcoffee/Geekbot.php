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

use Discord\Discord;
use Discord\Parts\User\Game;
use Discord\WebSockets\WebSocket;
use Geekbot\CommandsContainer;
use Geekbot\BlackList;

class Bot {
    public $discord;
    private $commands;
    private $reactions;

    function __construct() {

        $this->discord = new Discord(['token' => \Geekbot\Settings::envGet('sys.token')]);
        $this->commands = new CommandsContainer();
        $this->reactions = new Geekbot\Reactions();
        
        if(\Geekbot\Settings::envGet('sys.timezone') != "null") {
            date_default_timezone_set(\Geekbot\Settings::envGet('sys.timezone'));
        }
        
        if(Geekbot\Settings::envGet('sys.prefix') != "null"){
            $GLOBALS['prefix'] = Geekbot\Settings::envGet('sys.prefix');
        }
        
        $this->initSocket();
    }


    function initSocket() {
        $this->discord->on('ready', function ($discord){
            $discord->updatePresence($discord->factory(Game::class, ["name" => \Geekbot\Settings::envGet('sys.playing')]));
            echo "\ngeekbot is ready!\n" . PHP_EOL;

            $this->discord->on('message', function ($message) use ($discord) {

                $GLOBALS['userid'] = $message->author->id;
                if(!$message->getChannelAttribute()->is_private) {
                    $GLOBALS['guildid'] = $message->channel->guild_id;
                } else {
                    $GLOBALS['guildid'] = 0;
                }
                $GLOBALS['dblocation'] = $GLOBALS['guildid'].'-'.$GLOBALS['userid'];

                $stats = new \Geekbot\Stats($message);


                if(BlackList::check($message)){
                    $command = \Geekbot\Utils::getCommand($message);
                    try {
                        if ($this->commands->commandExists($command)) {

                            $commandslist = $this->commands->getCommands();
                            $cmd = $this->commands->getCommand($command);

                            // class_implements() expects an object or a string
                            if ($cmd != null) {
                                //BasicComand
                                if (in_array('Geekbot\Commands\basicCommand', class_implements($this->commands->getCommand($command)))) {
                                    $message->reply($commandslist[$command]->runCommand());
                                //MessageComand
                                } else if (in_array('Geekbot\Commands\messageCommand', class_implements($this->commands->getCommand($command)))) {
                                    $cmd->runCommand($message);
                                }
                            }
                        } else {
                            $reaction = $this->reactions->getReaction(\Geekbot\Utils::getCommand($message), $message);
                            if ($reaction != NULL) {
                                $message->channel->sendMessage($reaction);
                            }
                        }
                    } catch (Exception $e){
                        echo("An error occurd:\n");
                        echo($e."\n");
                        echo("Message that caused it: \"".$message->content."\"\n");
                        echo("Continuing Geekbot...");
                    }
                }
                
                //logging shit to the terminal
                echo $this->log($message) . PHP_EOL;
               
            });
        }
        );

        $this->discord->on('error', function ($error) {
            print($error);
        });
    }

    function run(){
        $this->discord->run();
    }
    
    function log($message) {
        $reply = $message->timestamp->format('d/m/y H:i:s') . ' - ';
        if(!$message->getChannelAttribute()->is_private) {
            $reply .= $message->channel->guild->name . ' - ';
            $reply .= $message->channel->name . ' - ';
        } else {
            $reply .= "Private - ";
        }
        $reply .= $message->author->username . ' - ';
        $reply .= $message->content;
        return $reply;
    }
}