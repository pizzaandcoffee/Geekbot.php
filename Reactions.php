<?php
namespace Geekbot;

class Reactions {

    //construct parameters
    private $message;
    
    function __construct($message) {
        $this->message = $message;    
    }
    
    public function getMessage() {
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
    
    public function hui(){
        $this->message->reply("hui!");
    }
    
    public function fuck(){
        $this->message->reply("Aso nei, da seit mer also nid :rage:");
    }
}