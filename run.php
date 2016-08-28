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

if(!file_exists("vendor/autoload.php")) {
    echo "The geekbot dependencies are not installed yet...\n";
    echo "Installing dependencies, please wait...\n\n";
    exec("composer install");
    //exit;
}

if(!file_exists(".env")){
    echo "please configure your .env before running the bot";
    exit;
}

include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/System/Utils.php';
include __DIR__ . '/System/Settings.php';
include __DIR__ . '/System/Permission.php';
include __DIR__ . '/System/Commands.php';
include __DIR__ . '/System/Database.php';
include __DIR__ . '/System/Reactions.php';
include __DIR__ . '/System/Stats.php';
include __DIR__ . '/System/BlackList.php';
include __DIR__ . '/System/Bot.php';
include __DIR__ . '/Commands/commandInterface.php';


if(file_exists(".git/ORIG_HEAD")) {
    $githead = file_get_contents(".git/ORIG_HEAD");
    $version = "2.0 Beta - Build {$githead}";
} else {
    $version = "2.0 Beta";
}
define('GEEKBOT_VERSION', $version);

echo("  ____ _____ _____ _  ______   ___ _____\n");
echo(" / ___| ____| ____| |/ / __ ) / _ \\_   _|\n");
echo("| |  _|  _| |  _| | ' /|  _ \\| | | || |\n");
echo("| |_| | |___| |___| . \\| |_) | |_| || |\n");
echo(" \\____|_____|_____|_|\\_\\____/ \\___/ |_|\n");

echo "{$version}\n\n";

$bot = new Bot();
$bot->run();
