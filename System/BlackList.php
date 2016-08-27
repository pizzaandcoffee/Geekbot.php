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

class BlackList {
    /**
     * @param array $message the message object
     * @return bool
     */
    public static function check($message){
        $permissions = Settings::getGuildSetting('permissions');
        if(isset($permissions->botRole)){
            if(Permission::hasRole($message, $permissions->botRole)){
                return true;
            } else {
                return false;
            }
        } else {
            if (isset($permissions->blacklist)) {
                if (in_array($GLOBALS['userid'], $permissions->blacklist)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }

    }

    /**
     * @param int $userID the user you want to blacklist
     */
    public static function add($userID){
        $permissions = Settings::getGuildSetting('permissions');
        if(!isset($permissions->blacklist) || !is_array($permissions->blacklist)){
            $permissions->blacklist = [];
        }
        $permissions->blacklist[] = $userID;
        Settings::setGuildSetting('permissions', $permissions);
    }

    /**
     * @param int $userID the user you want to remove from the blacklist
     */
    public static function remove($userID){
        $permissions = Settings::getGuildSetting('permissions');
        $newList = [];
        foreach($permissions->blacklist as $users){
            if($users != $userID){
                $newList[] = $users;
            }
        }
        Permission::setGuildPermission('blacklist', $newList);
    }
}
