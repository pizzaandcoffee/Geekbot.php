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

class Permission {
    public $create_instant_invite;
    public $kick_members;
    public $ban_members;
    public $administrator;
    public $manage_channels;
    public $manage_server;
    public $change_nickname;
    public $manage_nicknames;
    public $manage_roles;
    public $read_messages;
    public $send_messages;
    public $send_tts_messages;
    public $manage_messages;
    public $embed_links;
    public $attach_files;
    public $read_message_history;
    public $mention_everyone;
    public $voice_connect;
    public $voice_speak;
    public $voice_mute_members;
    public $voice_deafen_members;
    public $voice_move_members;
    public $voice_use_vad;

    function __construct($message) {
        $roles = $message->getAuthorAttribute()->getRolesAttribute();

        $this->create_instant_invite = false;
        $this->kick_members = false;
        $this->ban_members = false;
        $this->administrator = false;
        $this->manage_channels = false;
        $this->manage_server = false;
        $this->change_nickname = false;
        $this->manage_nicknames = false;
        $this->manage_roles = false;
        $this->read_messages = false;
        $this->send_messages = false;
        $this->send_tts_messages = false;
        $this->manage_messages = false;
        $this->embed_links = false;
        $this->attach_files = false;
        $this->read_message_history = false;
        $this->mention_everyone = false;
        $this->voice_connect = false;
        $this->voice_speak = false;
        $this->voice_mute_members = false;
        $this->voice_deafen_members = false;
        $this->voice_move_members = false;
        $this->voice_use_vad = false;

        foreach ($roles as $role) {
            $permissions = $role->permissions;

            if ($permissions->create_instant_invite) {
                $this->create_instant_invite = true;
            }

            if ($permissions->kick_members) {
                $this->kick_members = true;
            }

            if ($permissions->ban_members) {
                $this->ban_members = true;
            }

            if ($permissions->administrator) {
                $this->administrator = true;
            }

            if ($permissions->manage_channels) {
                $this->manage_channels = true;
            }

            if ($permissions->manage_server) {
                $this->manage_server = true;
            }

            if ($permissions->change_nickname) {
                $this->change_nickname = true;
            }

            if ($permissions->manage_nicknames) {
                $this->manage_nicknames = true;
            }

            if ($permissions->manage_roles) {
                $this->manage_roles = true;
            }

            if ($permissions->read_messages ) {
                $this->read_messages = true;
            }

            if ($permissions->send_messages) {
                $this->send_messages = true;
            }

            if ($permissions->send_tts_messages) {
                $this->send_tts_messages = true;
            }

            if ($permissions->manage_messages) {
                $this->manage_messages = true;
            }

            if ($permissions->embed_links) {
                $this->embed_links = true;
            }

            if ($permissions->attach_files) {
                $this->attach_files = true;
            }

            if ($permissions->read_message_history) {
                $this->read_message_history = true;
            }

            if ($permissions->voice_connect) {
                $this->voice_connect = true;
            }

            if ($permissions->voice_speak) {
                $this->voice_speak = true;
            }

            if ($permissions->voice_mute_members) {
                $this->voice_mute_members = true;
            }

            if ($permissions->voice_deafen_members) {
                $this->voice_deafen_members = true;
            }

            if ($permissions->voice_move_members) {
                $this->voice_move_members = true;
            }

            if ($permissions->voice_use_vad) {
                $this->voice_use_vad = true;
            }
        }
    }
}
