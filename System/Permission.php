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
            $memberRoles = $message->full_channel->guild->members->get('id', $message->author->id)->roles;

            foreach ($memberRoles as $role) {
                if (strtolower($role->name) == "admin") {
                    $isAdmin = true;
                }
            }
        } catch (\Exception $e){
            echo "Geekbot has not enough permission to check if the user is an admin or not!";
            echo "Give Geekbot 'manage roles' rights to do this!";
        }
        return $isAdmin;
    }

    /**
     * @param array $message the message object
     * @return int
     */
    public static function getUserLevel($message){
        $userlevel = Settings::getUserSetting($message, 'userLevel');
        return $userlevel;
    }

    /**
     * @param array $message the message object
     * @param int $level the user level (should be between 1 and 9)
     * @return bool
     */
    public static function setUserLevel($message, $level){
        if ($level <= 0 && $level >= 10){
            Settings::setUserSetting($message, 'userLevel', $level);
            return true;
        } else {
            return false;
        }
    }

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

    public static function blacklistCheck($message, $userID){
        $permissions = Settings::getGuildSetting($message, 'permissions');
        if(isset($permissions->botrole)){
            if(Permission::hasRole($message, $permissions->botrole)){
                return true;
            } else {
                return false;
            }
        } else {
            if (isset($permissions->blacklist)) {
                if (in_array($userID, $permissions->blacklist)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }

    }

    public static function blacklistAdd($message, $userID){
        $permissions = Settings::getGuildSetting($message, 'permissions');
        if(!is_array($permissions->blacklist)){
            $permissions->blacklist = [];
        }
        $permissions->blacklist[] = $userID;
        Settings::setGuildSetting($message, 'permissions', $permissions);
    }

    public static function blacklistRemove($message, $userID){
        $permissions = Settings::getGuildSetting($message, 'permissions');
        $newList = [];
        foreach($permissions->blacklist as $users){
            if($users != $userID){
                $newList[] = $users;
            }
        }
        $permissions->blacklist = $newList;
        Settings::setGuildSetting($message, 'permissions', $permissions);
    }
}