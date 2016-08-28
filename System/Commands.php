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

namespace Geekbot;

class CommandsContainer {
    private $commands;

    function __construct() {
        $this->commands = [];
        $this->includeCommands();
        $this->loadCommands();
        $this->loadCoreCommands();
        $this->commands[Commands\Help::getName()]->setCommands($this->commands);
    }

    private function includeCommands() {
        Utils::includeFolder(__DIR__ . '/../Commands/core');
        Utils::includeFolderRecursively(__DIR__ . '/../Commands/modules/');
    }

    private function loadCommands() {
        echo "Loading Commands:" . PHP_EOL;
        foreach (get_declared_classes() as $className) {
            if (in_array('Geekbot\Commands\command', class_implements($className))) {
                echo "   - ". substr($className, 17) . "\n";
                if($className::getName()){
                    if(\Geekbot\Settings::envGet('sys.invite')) {
                        $this->commands[$className::getName()] = new $className();
                    }
                } else {
                    $this->commands[$className::getName()] = new $className();
                }


            }
        }
    }

    private function loadCoreCommands() {
        $this->commands[Commands\Help::getName()] = new Commands\Help();
    }

    public function commandExists($name) {
        return isset($this->commands[$this->handlePrefix($name)]);
    }

    /**
     * @param $name
     * @return mixed|null returns a list of commands
     */
    public function getCommand($name) {
        $commandName = $this->handlePrefix($name);
        if(isset($this->commands[$commandName])) {
            return $this->commands[$commandName];
        } else {
            return NULL;
        }
    }

    /**
     * @return array
     */
    public function getCommands() {
        return $this->commands;
    }

    private function handlePrefix($command){
        if(isset($GLOBALS['prefix'])){
            if(Settings::getGuildSetting("private") == "null") {
                $prefix = $GLOBALS['prefix'];
                if(!(strpos($command, $prefix) === False)){
                    return str_replace($prefix, "", $command);
                } else {
                    //return something that is not a command
                    return $prefix;
                }
            } else {
                // the guild is private
                return $command;
            }
        } else {
            // no prefix is set
            return $command;
        }
    }
}
