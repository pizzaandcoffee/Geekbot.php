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
    echo "The geekbot dependencies are not installed yet...\n";
    echo "Installing dependencies, please wait...\n\n";
    exec("composer install");
    //exit;
}
if(!file_exists(".env")){
    echo "please configure your .env before running the bot";
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
include __DIR__ . '/System/BlackList.php';
include __DIR__ . '/Commands/commandInterface.php';

use Discord\Discord;
use Discord\Parts\User\Game;
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
                $GLOBALS['guildid'] = $message->channel->guild_id;
                $GLOBALS['dblocation'] = $GLOBALS['guildid'].'-'.$GLOBALS['userid'];

                $stats = new \Geekbot\Stats($message);

                if(\Geekbot\BlackList::check($message)){
                    $command = \Geekbot\Utils::getCommand($message);
                    try {
                        if ($this->commands->commandExists($command)) {

                            $commandslist = $this->commands->getCommands();
                            $cmd = $this->commands->getCommand($command);

                            // class_implements() expects an object or a string
                            if ($cmd != null) {
                                if (in_array('Geekbot\Commands\basicCommand', class_implements($this->commands->getCommand($command)))) {
                                    $message->reply($commandslist[$command]->runCommand());
                                } else if (in_array('Geekbot\Commands\messageCommand', class_implements($this->commands->getCommand($command)))) {
                                    $result = $cmd->runCommand($message);
                                    if (is_string($result)) {
                                        $message->reply($result);
                                    } else {
                                        $message = $result;
                                    }
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

                $reply = $message->timestamp->format('d/m/y H:i:s') . ' - ';
                $reply .= $message->channel->guild->name . ' - ';
                $reply .= $message->channel->name . ' - ';
                $reply .= $message->author->username . ' - ';
                $reply .= $message->content;
                echo $reply . PHP_EOL;
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
}

$bot = new Bot();
$bot->run();
