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

class Commands
{
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

    function __construct($message, $db, $settings, $utils)
    {
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


    public function getA()
    {
        return $this->a;
    }

    public function getMessage()
    {
        return $this->message;
    }


    //-------------------------------------------------------------------------
    // Classes Command
    //-------------------------------------------------------------------------   
    public function classes()
    {
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
    // Porn Command
    //-------------------------------------------------------------------------     
    public function porn()
    {
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
}