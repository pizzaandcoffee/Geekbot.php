<?php
/**
 * Contains the all core commands
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
 * The Help Command Displays all Command Descriptions 
 *
 */
class Help implements messageCommand{
    
    private $commands;
   
    public static function getName() {
        return "!help";
    }

    public function runCommand($message) {
        $parameters = explode(' ', $message->content);
        if(count($parameters) > 1) {
            if($parameters[1] == "help") {
                return $this->getHelp();
            }
            else {
                print_r($this->commands[$parameters[1]]);
                if(isset($this->commands[$parameters[1]])) {
                    return $this->commands[$parameters[1]]->getHelp();
                } else {
                    return "sorry, I do not know that command.";
                }
            }
        } else {
            return $this->helpText();          
        }
    }
    
    public function getDescription() {
        return "shows you information about commands";
    }

    public function getHelp() {
        return $this->getDescription() . "
            usage:
            !help - returns a list of all commands
            !help [command] - returns how to use that command";           
    }
    
    private function helpText() {
        $returnStirng = "here is a list of all commands: \n";
        foreach ($this->commands as $command){
            $returnStirng .= $command::getName() . ' - ' . $command->getDescription() . "\n";
        }
        return $returnStirng;
    }
    
    public function setCommands($commands) {
        $this->commands = $commands;
    }
}
