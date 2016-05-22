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

namespace Geekbot\Commands;

use Geekbot\Utils;

class pokedex implements messageCommand{
    public static function getName() {
        return "!pokedex";
    }

    public function runCommand($message) {
        $messageArray = Utils::messageSplit($message);
        $pokedexoptions = ['all', 'image', 'type'];
        if (isset($messageArray[1]) && $messageArray[1] == 'help') {
            $this->getHelp();
        } elseif (isset($messageArray[2]) && isset($messageArray[1]) && in_array($messageArray[1], $pokedexoptions)) {
            $message->reply('please wait a moment while i fetch all the information');
            $getrawpkdata = file_get_contents("http://pokeapi.co/api/v2/pokemon/".$messageArray[2]);
            if (Utils::startsWith($getrawpkdata, '{')) {
                $pkd = \GuzzleHttp\json_decode($getrawpkdata);
                if ($messageArray[1] == 'image') {
                    $message->reply($pkd->sprites->front_default);
                } elseif ($messageArray[1] == 'type') {
                    $message->reply("this is still in development");
                } else {
                    $message->reply("here is all the information about #{$pkd->id} {$pkd->name}
                    type: {$pkd->types[0]->type->name} {$pkd->types[1]->type->name}
                    weight: {$pkd->weight}lbs
                    height: {$pkd->height}inch
                    {$pkd->sprites->front_default}");
                }
            } else {
                $message->reply("that is not a pokemon...");
            }
        } else {
            $message->reply('That command in not valid, please see !pokedex help');
        }
        return $message;
    }

    public function getDescription() {
        return "A pokedex!";
    }

    public function getHelp() {
        return "a full featured pokedex
        usage: `!pokedex [option] [name or nr]
        
        options:
        - image
        - type
        - full";
    }
}