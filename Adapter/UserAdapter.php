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

namespace Geekbot\Adapter;

class UserAdapter {
    
    private $user;
    private $id;
    private $username;
    private $avatar;
    private $avatar_hash;
    private $discriminator;
    private $bot;

    function __construct($user) {
        if(!$user->created){
            die("You can't create an Helper for a Part that is not crated");
        } else {
            $this->user = $user;
            $this->id = $user->id;
            $this->username = $user->username;
            $this->avatar = $user->avatar;
            $this->avatar_hash = $user->avatar_hash;
            $this->discriminator = $user->discriminator;
            $this->bot = $user->bot;
        }
    }


    function getUser(){
        return $this->User;
    }
    
    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getAvatar() {
        return $this->avatar;
    }

    function getAvatar_hash() {
        return $this->avatar_hash;
    }

    function getDiscriminator() {
        return $this->discriminator;
    }

    function isBot() {
        return $this->bot;
    }
    
    function getPrivateChannel(){
        return $this->user->getPrivateChannel();
    }
    
    function sendMessage($message, $tts = false){
        return $this->user->getPrivateChannel($message, $tts);
    }
            
    function getMemeberAdapter($guild) {
        return new MemberAdapter($guild->members->fetch($this->id));
    }
    
    static function getMember($user, $guild) {
        return $guild->members->fetch($user->id);
    }
}
