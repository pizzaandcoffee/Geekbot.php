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
 * Description of GuildHelper
 *
 * @author fence
 */
class GuildAdapter {
    private $guild;
    private $id;
    private $name;
    private $icon;
    private $icon_hash;
    private $region;
    private $owner;
    private $owner_id;
    private $joined_at;
    private $afk_channel_id;
    private $embed_enabled;
    private $embed_channel_id;
    private $features;
    private $splash;
    private $splash_hash;
    private $emojis;
    private $large;
    private $verification_level;
    private $member_count;
    private $roles;
    private $channels;
    private $members;
    private $invites;
    private $bans;

    function __construct($guild) {
        if(!$guild->created){
            die("You can't create an Helper for a Part that is not crated");
        } else {
            $this->guild = $guild;
            $this->id = $guild->id;
            $this->name = $guild->name;
            $this->icon = $guild->icon;
            $this->icon_hash = $guild->icon_hash;
            $this->region = $guild->region;
            $this->owner = $guild->owner;
            $this->owner_id = $guild->owner_id;
            $this->joined_at = $guild->joined_at;
            $this->afk_channel_id = $guild->afk_channel_id;
            $this->embed_enabled = $guild->embed_enabled;
            $this->embed_channel_id = $guild->embed_channel_id;
            $this->features = $guild->features;
            $this->splash = $guild->splash;
            $this->splash_hash = $guild->splash_hash;
            $this->emojis = $guild->emojis;
            $this->large = $guild->large;
            $this->verification_level = $guild->verification_level;
            $this->member_count = $guild->member_count;
            $this->roles = $guild->roles;
            $this->channels = $guild->channels;
            $this->members = $guild->members;
            $this->invites = $guild->invites;
            $this->bans = $guild->bans;
        }
    }
    
    function getGuild() {
        return $this->guild;
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getIcon() {
        return $this->icon;
    }

    function getIcon_hash() {
        return $this->icon_hash;
    }

    function getRegion() {
        return $this->region;
    }

    function getOwner() {
        return $this->owner;
    }

    function getOwner_id() {
        return $this->owner_id;
    }

    function getJoined_at() {
        return $this->joined_at;
    }

    function getAfk_channel_id() {
        return $this->afk_channel_id;
    }

    function isEmbed_enabled() {
        return $this->embed_enabled;
    }

    function getEmbed_channel_id() {
        return $this->embed_channel_id;
    }

    function getFeatures() {
        return $this->features;
    }

    function getSplash() {
        return $this->splash;
    }

    function getSplash_hash() {
        return $this->splash_hash;
    }

    function getEmojis() {
        return $this->emojis;
    }

    function isLarge() {
        return $this->large;
    }

    function getVerification_level() {
        return $this->verification_level;
    }

    function getMember_count() {
        return $this->member_count;
    }

    function getRoles() {
        return $this->roles;
    }

    function getChannels() {
        return $this->channels;
    }

    function getMembers() {
        return $this->members;
    }

    function getInvites() {
        return $this->invites;
    }

    function getBans() {
        return $this->bans;
    }

}
