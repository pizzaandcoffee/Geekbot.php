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
        Utils::includeFolder(__DIR__ . '/../Commands/modules');
    }
    
    private function loadCommands() {
        echo "Loading Commands:" . PHP_EOL;
        foreach (get_declared_classes() as $className) {
            if (in_array('Geekbot\Commands\command', class_implements($className))) {
                echo "   - $className" . PHP_EOL;
                $this->commands[$className::getName()] = new $className();
            }
        }
        echo " " . PHP_EOL;
    }
    
    private function loadCoreCommands() {
        $this->commands[Commands\Help::getName()] = new Commands\Help();
    }

        public function commandExists($name) {
        return isset($this->commands[$name]);
    }
    
    public function getCommand($name) {
        if($this->commandExists($name)) {
            return $this->commands[$name];
        } else {
            return NULL;
        }
    }
    
    public function getCommands() {
        return $this->commands;
    }
}
