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

    /**
     * @param array $message the message object
     * @return bool
     */
    public static function isAdmin($message){
        $isAdmin = false;
        try {
            if($message->author->roles->has('administrator')){
                $isAdmin = true;
            } else {
                $isAdmin = false;
            }
        } catch (\Exception $e){
            echo "Geekbot has not enough permission to check if the user is an admin or not!";
            echo "Give Geekbot 'manage roles' rights to do this!";
        }
        return $isAdmin;
    }

    /**
     * @param array $message the message object
     * @param string $roleName name of the role
     * @return bool
     */
    public static function hasRole($message, $roleName){
        $hasRole = false;
        try {
            $memberRoles = $message->full_channel->guild->members->get('id', $message->author->id)->roles;

            foreach ($memberRoles as $role) {
                if (strtolower($role->name) == $roleName) {
                    $hasRole = true;
                }
            }
        } catch (\Exception $e){
            echo "Geekbot has not enough permission to check if the user is an admin or not!";
            echo "Give Geekbot 'manage roles' rights to do this!";
        }
        return $hasRole;
    }

    /**
     * @param array $message the message object
     * @return bool
     */
    public static function blacklistCheck($message){
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
    public static function blacklistAdd($userID){
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
    public static function blacklistRemove($userID){
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