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

use PHPHtmlParser\Dom;
use SimpleXMLElement;

class Commands {
    //construct parameters
    private $message;
    private $db;
    private $settings;
    private $utils;
    //too much stuff
    private $author;
    private $authorid;
    private $db_messagesname;
    private $amountofmessages;
    private $newamountofmessages;
    private $db_messagesname_guild;
    private $amountofmessages_guild;
    private $newamountofmessages_guild;
    private $now;
    private $oa;
    private $a;
    private $ac;
           
    function __construct($message, $db, $settings, $utils) {
        $this->db = $db;
        $this->message = $message;
        $this->settings = $settings;
        $this->utils = $utils;
        
        $this->author = $message->author->username;
        $this->authorid = $message->author->id;
        #   Get message Count
        $this->db_messagesname = $this->authorid . '-' . $message->channel->guild_id . '-messages';
        $this->amountofmessages = $this->db->get($this->db_messagesname);
        $this->newamountofmessages = $this->amountofmessages + 1;
        $this->db->put($this->db_messagesname, $this->newamountofmessages);
        #   Get message Count for Server Stats
        $this->db_messagesname_guild = $message->channel->guild_id . '-messages';
        $this->amountofmessages_guild = $this->db->get($this->db_messagesname_guild);
        $this->newamountofmessages_guild = $this->amountofmessages_guild + 1;
        $this->db->put($this->db_messagesname_guild, $this->newamountofmessages_guild);
        #   update last message
        $this->now = date(DATE_RFC2822);
        $this->db->put($this->authorid . '-last', $this->now);
        #   split message in array
        $this->oa = preg_replace('/\s+/', ' ', strtolower($message->content));
        $this->a = explode(' ', $this->oa);
        $this->ac = strtolower($message->content);
    }
    
  
    
    public function getA() {
        return $this->a;
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    //-------------------------------------------------------------------------
    // Help Command
    //-------------------------------------------------------------------------
  
    //-------------------------------------------------------------------------
    // Debugging purposes
    //-------------------------------------------------------------------------
    public function debugArray() {
        if ($this->message->author->username == $this->settings->ownername) {
            print_r($this->message) . PHP_EOL;
            print_r($this->a);
            print_r($this->a[1]) . PHP_EOL;
        }
    }
    
    public function debugLevel() {
        $this->message->reply(calculateLevel($this->newamountofmessages));
    }
    
    //-------------------------------------------------------------------------
    // Commands
    //-------------------------------------------------------------------------
    public function idiot(){
            if ($this->authorid == '93421536890859520') {
                $this->message->reply('die Halluzination findet euch alle BEKLOPPT!');
            } else {
                $this->message->reply('du bist KEINE HALLUZINATION *triggered*');
            }
    }
    
    //-------------------------------------------------------------------------
    // Level Command
    //-------------------------------------------------------------------------
    public function level(){
        if ($this->utils->startsWith($this->a[1], '<@')){
            $userid = trim($this->a[1], '<@>');
            $messagesdbstring = $userid . '-' . $this->message->channel->guild_id . '-messages';
            $messages = $this->db->get($messagesdbstring);
            $thelevel = $this->utils->calculateLevel($messages);
            $this->message->reply($this->a[1]."s level is {$thelevel}");
        } elseif ($this->a[1] == 'server'){
            $thelevel = $this->utils->calculateLevel($this->newamountofmessages_guild);
            $this->message->reply("the server level is {$thelevel}");
        } else {
            $this->message->reply("Wrong syntax for the command !level, please see !level help to see how the command works");
        }
    }
    

    //-------------------------------------------------------------------------
    // Classes Command
    //-------------------------------------------------------------------------   
    public function classes() {
        $rpgclasses = ['geek', 'neckbeard', 'console-peasant', 'neko', 'furry', 'laladin', 'yandere', 'script-kiddie',
            'zweihorn', 'affe-mit-waffe', 'glitzertier'];
        $classes2 = implode(", ", $rpgclasses);

        if ($this->a[1] == 'set') {
            if ($this->utils->startsWith($this->a[2], '<@')) {

                if (in_array($this->a[3], $rpgclasses) || $this->author == $this->settings->ownername) {
                    $this->db->put($this->a[2] . '-class', $this->a[3]);
                    $this->message->reply("{$this->a[2]} ist jetzt ein " . ucfirst($this->a[3]));
                } else {
                    $this->message->reply("that class does not exist, please use one of the following: \n {$classes2}");
                }
            }
        } elseif ($this->a[1] == 'show') {
            $class = $this->db->get($this->a[2] . '-class');
            $this->message->reply("{$this->a[2]} is a " . ucfirst($class));
        } elseif ($this->a[1] == 'help') {
            $this->message->reply("here are the commands for !class
            set - set someones class
            show - show someones class\n
            usage:
            !class [command] [mention] [class]\n
            useable classes:
            {$classes2}");
        } else {
            $this->message->reply("Wrong syntax for the command !class, please see !class help to see how the command works");
        }
    }


    //-------------------------------------------------------------------------
    // Fortune Command
    //-------------------------------------------------------------------------
    public function fortune(){
        if ($this->a[1] == 'help') {
            $this->message->reply("!fortune return a random random forune");
        } else {
            $fortunes = file_get_contents('fortunes');
            $array = explode('%', $fortunes);
            $fortune = $array[array_rand($array)];
            $this->message->reply($fortune);
        }
    }
    
    //-------------------------------------------------------------------------
    // Last Online Command
    //-------------------------------------------------------------------------
    public function last(){
        if ($this->utils->startsWith($this->a[1], '<@')) {
            $user = trim($this->a[1], '<@>');
            $last = $this->db->get($user . '-last');
            $this->message->reply($this->a[1]." sent his last message on {$last}");
        } else {
            $this->message->reply('please mention someone');
        }
    }
    
    //-------------------------------------------------------------------------
    // Stats Command
    //-------------------------------------------------------------------------
    public function stats(){
        if (isset($this->a[1]) && $this->utils->startsWith($this->a[1], "<@")) {
            $statsuserid = trim($this->a[1], '<@>');
            $statsmessagesdbstring = $statsuserid . '-' . $this->message->channel->guild_id . '-messages';
            $statsmessages = $this->db->get($statsmessagesdbstring);
            $badjokes = $this->db->get($this->a[1] . '-badjokes');
            $class = $this->db->get($this->a[1] . '-class');
            $this->message->reply("stats for " . $this->a[1] . " 
            Messages sent: " . $statsmessages . " 
            Bad jokes made: " . $badjokes . " 
            Level: " . $this->utils->calculateLevel($statsmessages) . "
            Class: " . $class . " 
            Last Message: 
            " . $this->db->get($statsuserid . '-last') . " ");
        } elseif (isset($this->a[1]) && $this->utils->startsWith($this->a[1], 'server')){
            $this->message->reply("Stats for this server:
            Messages sent: {$this->amountofmessages_guild}
            Actual Level: ". $this->utils->calculateLevel($this->amountofmessages_guild)."
            (counting start 15 May 2016)");
        } else {
            $this->message->reply("this command uses the following syntax:
            !stats [mention]
            use @here for server stats");
        }
    }
    
    //-------------------------------------------------------------------------
    // Bad Joke Counter
    //-------------------------------------------------------------------------    
    public function bad(){
        if (isset($this->a[1]) && $this->a[1] == 'show') {
            if (isset($this->a[2]) && $this->utils->startsWith($this->a[2], '<@')) {
                $bads = $this->db->get($this->a[2] . '-badjokes');
                $this->message->reply($this->a[2]." made {$bads} bad jokes");
            } else {
                $this->message->reply('please specify a user');
            }
        } elseif (isset($this->a[1]) && $this->utils->startsWith($this->a[1], '<@')) {
            $old = $this->db->get($this->a[1] . '-badjokes');
            $new = $old + 1;
            $this->db->put($this->a[1] . '-badjokes', $new);
            $this->message->reply($this->a[1]." made a bad joke");
        } else {
            $this->message->reply("the bad joke counter
            show - shows the amount of bad jokes
            @mention - adds 1 to the bad joke counter\n
            usage:
            !bad [show|@mention] ([@mention])");
        }
    }

    public function shit(){
        $this->bad();
    }
    
    //-------------------------------------------------------------------------
    // Useless Commands 
    // Cat, 8ball, choose, johncena, coin, dice
    //-------------------------------------------------------------------------

    public function say(){
        if ($this->message->author->username == $this->settings->ownername) {
            $tosay = substr($this->ac, 5);
            $this->message->reply($tosay);
        }
    }
    
    //-------------------------------------------------------------------------
    // Porn Command
    //-------------------------------------------------------------------------     
    public function porn(){
        if (isset($this->a[1]) && $this->a[1] == 'help') {
            $this->message->reply("shows some porn :smirk:\n
                usage:
                !porn [tags]");
        } elseif (isset($this->a[1])) {
            $getgelbooru = file_get_contents('http://gelbooru.com/index.php?page=dapi&s=post&q=index&tags=rating:explicit ' . substr($this->ac, 5));
            $xmlgelbooru = new SimpleXMLElement($getgelbooru);
            $randomnumberporn = rand(1, 100);
            $this->message->reply($this->utils->xml_attribute($xmlgelbooru->post[$randomnumberporn], 'file_url'));
        } else {
            $this->message->reply('Please use atleast one tag');
        }
    }  
    
    //-------------------------------------------------------------------------
    // Pokedex Command
    //-------------------------------------------------------------------------      
    public function pokedex(){
        $pokedexoptions = ['all', 'image', 'type'];
        if (isset($this->a[1]) && $this->a[1] == 'help') {
            $this->message->reply("this return all information about a pokemon\n
            usage:
            !pokedex [name|number] [all|image|type]");
        } elseif (isset($this->a[1]) && isset($this->a[2]) && in_array($this->a[2], $pokedexoptions)) {
            $this->message->reply('please wait a moment while i fetch all the information');
            $getrawpkdata = file_get_contents("http://pokeapi.co/api/v2/pokemon/".$this->a[1]);
            if ($this->utils->startsWith($getrawpkdata, '{')) {
                $pkd = \GuzzleHttp\json_decode($getrawpkdata);
                if ($this->a[2] == 'image') {
                    $this->message->reply($pkd->sprites->front_default);
                } elseif ($this->a[2] == 'type') {
                    $this->message->reply("this is still in development");
                } else { 
                    $this->message->reply("here is all the information about #{$pkd->id} {$pkd->name}
                    type: {$pkd->types[0]->type->name} {$pkd->types[1]->type->name}
                    weight: {$pkd->weight}lbs
                    height: {$pkd->height}inch
                    {$pkd->sprites->front_default}");
                }
            } else {
                $this->message->reply("that is not a pokemon...");
            }
        } else {
            $this->message->reply('That command in not valid, please see !pokedex help');
        }
    }
    
    //-------------------------------------------------------------------------
    // 4chan Command
    //-------------------------------------------------------------------------



    
}