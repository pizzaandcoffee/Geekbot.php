<?php
/**
 * The Interfaces for writting a plugin command for Geekbot
 * message refers to Discord\Parts\Channel\Message
 */

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


namespace Geekbot\Commands;
/**
 * The most basic command Interface, YOU MUST NOT IMPLEMENT IT!
 */
interface command {
    /**
     * @return string name of the command
     */
    public static function getName();
    /**
     * @return string short description of what your command does
     */
    public function getDescription();
    /**
     * @return string how your command is used
     */
    public function getHelp();
}

/**
 * The Interace for a basic command that doesn't need any parameters
 */
interface basicCommand extends command {
    /**
     * @return string|message has to return a string or message object 
     */
    public function runCommand();
}

/**
 * The Interace for a command that needs one parameter
 */
interface messageCommand extends command {
    /**
     * @param message|string $message
     * @return string|message has to return a string or message object
     */
    public function runCommand($message);
}

interface subCommand extends messageCommand {
    public function getParent();
}

abstract class ParentCommand implements messageCommand{
    
    public $subCommands;
    
    public function addCommad($cmd){
        $this->subCommands[$cmd->getName] = $cmd;
    }
    
    public function runCommand($message) {
        $subcmd = \Geekbot\Utils::messageSplit($message->content)[1];
        if ($subcmd == "help") {
            $this->subCommands;
        } else if(isset ($this->subCommands[$subcmd])) {
            $this->subCommands[$subcmd]->runCommand($message);
        } else {
            $message-reply("please state a subcommand");
        }
    }
    
}

