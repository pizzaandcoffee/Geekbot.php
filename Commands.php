<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Description of Commands
 *
 * @author fence
 */
class Commands {
    //construct parameters
    private $message;
    private $db;
    private $settings;
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
    
            
    function __construct($message, $db, $settings) {
        $this->db = $db;
        $this->message = $message;
        $this->settings = $settings;
        
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
    
    private function isCommand(){
        return substr($this->a[0], 0, 1) == "!";
    }
    
     private function isDebug(){
        return substr($this->a[0], 0, 1) == "%";
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
    public function help() {
        if($this->isCommand()) {
            $this->message->reply("here is a list of all commands:
            !level - level settings for each user
            !class - class settings for each user
            !bad - a bad joke counter
            !last - see when the mentioned user last sent something
            !stats - show stats for each user
            !cat - shows a random cat picture
            !8ball - let the allknowingly 8ball answer your question
            !pokedex - does what a pokedex does
            !porn - :smirk:
            !fortune - get a fortune or quote
            !4chan - get a totally random image from 4chan (be aware of shitposts)
            Geekbot also knows how to respond to several words\n
            for more info about each command use
            ![command] help");
        }
    }
    
    //-------------------------------------------------------------------------
    // reaction strings
    //-------------------------------------------------------------------------
    public function ping(){
        $this->message->reply('pong!');
    }

    public function marco(){
        $this->message->reply('polo!');
    }

    public function deder() {
        $this->message->reply('DEDEST');
    }
    
    public function hui(){
        $this->message->reply("hui!");
    }
    //-------------------------------------------------------------------------
    // Debugging purposes
    //-------------------------------------------------------------------------
    public function debugArray() {
   
            if ( $this->isDebug() && $this->message->author->username == $this->settings->ownername) {
                print_r($this->message) . PHP_EOL;
                print_r($this->a);
            }
    }
    
    public function level() {
        if($this->isDebug()) {
            $this->message->reply(calculateLevel($this->newamountofmessages));
        }
    }
    
    //-------------------------------------------------------------------------
    // Commands
    //-------------------------------------------------------------------------
    public function idiot(){
        if($this->isCommand()) {
            if ($this->authorid == '93421536890859520') {
                $this->message->reply('die Halluzination findet euch alle BEKLOPPT!');
            } else {
                $this->message->reply('du bist KEINE HALLUZINATION *triggered*');
            }
        }
    }
    
}
