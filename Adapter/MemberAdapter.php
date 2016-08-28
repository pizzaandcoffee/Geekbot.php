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

/**
 * Description of MemberHelper
 *
 * @author fence
 */
class MemberAdapter {
    
    private $member;
    private $id;
    private $username;
    private $discriminator;
    private $user;
    private $roles;
    private $deaf;
    private $mute;
    private $joined_at;
    private $guild;
    private $guild_id;
    private $status;
    private $game;
    private $nick;
            
    function __construct($member) {
        if(!$member->created){
            die("You can't create an Helper for a Part that is not crated");
        } else {
            $this->member = $member;
            $this->id = $member->id;
            $this->username = $member->username;
            $this->discriminator = $member->discriminator;
            $this->user = $member->user;
            $this->roles = $member->roles;
            $this->deaf = $member->deaf;
            $this->mute = $member->mute;
            $this->joined_at = $member->joined_at;
            $this->guild = $member->guild;
            $this->guild_id = $member->guild_id;
            $this->staus = $member->status;
            $this->game = $member->game;
            $this->nick = $member->nick;
        }
    }
    
    function getMember() {
        return $this->member;
    }

    function getId() {
        return $this->id;
    }

    function getUsername() {
        return $this->username;
    }

    function getDiscriminator() {
        return $this->discriminator;
    }

    function getUser() {
        return $this->user;
    }

    function getRoles() {
        return $this->roles;
    }

    function isDeaf() {
        return $this->deaf;
    }

    function isMute() {
        return $this->mute;
    }

    function getJoined_at() {
        return $this->joined_at;
    }

    function getGuild() {
        return $this->guild;
    }

    function getGuild_id() {
        return $this->guild_id;
    }

    function getStatus() {
        return $this->status;
    }

    function getGame() {
        return $this->game;
    }

    function getNick() {
        return $this->nick;
    }
    
    function ban(){
        return $this->member->ban();
    }
    
    function getPermissions() {
        new \Geekbot\Permission($this->member);
    }
   
}
