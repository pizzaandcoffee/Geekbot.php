<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Victoria;

/**
 * Description of Commands
 *
 * @author fence
 */
class Commands {
    private $message;
    private $db;
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
            
    function __construct($message, $db) {
        $this->db = $db;
        $this->message = $message;
        
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
    
    function getA() {
        return $this->a;
    }
    
    function getMessage() {
        return $this->message;
    }

    public function ping(){
        $this->message->reply('pong!');
    }

    public function marco(){
        $this->message->reply('polo!');
    }

    public function deder() {
        $this->message->reply('DEDEST');
    }

    public function idiot(){
        if ($this->authorid == '93421536890859520') {
            $this->message->reply('die Halluzination findet euch alle BEKLOPPT!');
        } else {
            $this->message->reply('du bist KEINE HALLUZINATION *triggered*');
        }
    }
    public function hui(){
        $this->message->reply("hui");
    }
}
