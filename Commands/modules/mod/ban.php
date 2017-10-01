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

use Geekbot\Permission;
use Discord\Exceptions\PartRequestFailedException;

class Ban implements messageCommand {

    public static function getName() {
        return ".!ban";
    }

    public function getDescription() {
        return "bans a User";
    }

    public function getHelp() {
        $this->getDescription();
    }

    public function runCommand($message) {
        $perms = new Permission($message);
        if($perms->ban_members){
            if(isset($message->mentions[0])) {
                $mentionID = $message->mentions[0]->id;
                $member = $message->getFullChannelAttribute()->getGuildAttribute()->getMembersAttribute()->get('id', $mentionID);
                try{
                    if($member->ban()){
                        return "banned " . $message->mentions[0]->username;
                    } else {
                        return "could not ban " . $message->mentions[0]->username;
                    }
                } catch (PartRequestFailedException $e) {
                    return "could not ban " . $message->mentions[0]->username;
                }
            } else {
                return "please mention a user";
            }
        } else {
            return "nope";
        }
    }
}
