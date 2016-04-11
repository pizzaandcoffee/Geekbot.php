<?php
include __DIR__.'/vendor/autoload.php';
include __DIR__.'/Victoria.php';

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\WebSocket;
use Victoria\VictoriaSettings;

$envjson = file_get_contents('env.json');
$settings = json_decode($envjson);
$discord = new Discord($settings->username, $settings->password);
$ws = new WebSocket($discord);


function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

$ws->on('ready', function ($discord) use ($ws){
    $discord->updatePresence($ws, "PhpStorm 2016.1 Debugger", 0);
    echo "bot is ready!".PHP_EOL;

    $ws->on('message', function ($message, $discord){
        echo "Message from {$message->author->username}: {$message->content}".PHP_EOL;
        $message->reply('There is no point in sending me messages yet');
    });

    $ws->on(Event::MESSAGE_CREATE, function ($message, $discord, $newdiscord) {

        #
        #   Strings
        #

        #   Get message Count
        $db = new VictoriaSettings();
        $db_messagesname = $message->author->id.'-'.$message->full_channel->guild->id.'-messages';
        $amountofmessages = $db->get($db_messagesname);
        $newamountofmessages = $amountofmessages + 1;
        $db->put($db_messagesname, $newamountofmessages);
        #   split message in array
        $a = explode(' ', strtolower($message->content));
        $ac = strtolower($message->content);
        #other shortcuts
        $author = $message->author->username;
        $authorid = $message->author->id;

        global $settings;

        #
        #   reaction strings
        #

        if ($ac == 'ping') {
            $message->reply('pong!');
        }

        if ($ac == 'marco') {
            $message->reply('polo!');
        }

        if ($ac == 'deder') {
            $message->reply('DEDEST');
        }

        if ($author !== $settings->botname &&
            $author !== $settings->ownername){
            if (strpos(strtolower($message->content), 'cookies') !== false){
                $message->reply("All cookies belong to his pumpkinness Rune!");
            }
        }
        #
        #   Debugging purposes
        #

        if($message->content == '%array' && $message->author->username == 'Rune' && $message->full_channel->guild->name == "Swiss Geeks"){
            print_r($message).PHP_EOL;
            print_r($a);
        }

        #
        #   Help Command
        #

        if ($a[0] == '!help'){
            $message->reply("here is a list of all commands:
            !level - level settings for each user
            !class - class settings for each user
            !stats - show stats for each user
            !atts - attribute setttings
            Geekbot also knows how to respond to several words\n
            for more info about each command use
            ![command] help");
        }

        #
        #   Level Command
        #

        if($a[0] == '!level') {

            if ($a[1] == 'add') {
                if (startsWith($a[2], '<@')) {
                    if (is_numeric($a[3])) {
                        $oldlevel = $db->get($a[2] . '-level');
                        $level = $oldlevel + $a[3];
                        $db->put($a[2] . '-level', $level);
                        $message->reply($a[2] . ' leveled up with ' . $a[3] . ' levels and is now level ' . $level);
                    }
                }
            }

            if ($a[1] == 'drop') {
                if (startsWith($a[2], '<@')) {
                    if (is_numeric($a[3])) {
                        $oldlevel = $db->get($a[2] . '-level');
                        $level = $oldlevel - $a[3];
                        $db->put($a[2] . '-level', $level);
                        $message->reply($a[2] . ' dropped down with ' . $a[3] . ' levels and is now level ' . $level);
                    }
                }
            }

            if ($a[1] == 'show') {
                $level = $db->get($a[2] . '-level');
                $message->reply($a[2] . "'s level is " . $level);
            }

            if ($a[1] == 'help'){
                $message->reply("here are the commands for !level
                add - add a level to someone
                drop - lower someones level
                show - show someones level\n
                usage:
                !level [command] [mention] [value]");
            }
        }

        #
        #   Class Command
        #

        if($a[0] == '!class'){

            if($a[1] == 'set'){
                if(startsWith($a[2], '<@')){
                    $rpgclasses = ['geek', 'nerd', 'gamer', 'neko', 'furry','laladin', 'yandere'];
                    if(in_array($a[3], $rpgclasses) || $author == $settings->ownername){
                        $db->put($a[2].'-class', $a[3]);
                        $message->reply($a[2].' is now a '. $a[3]);
                    }
                    else{
                        $classes2 = implode(", ",$rpgclasses);
                        $message->reply('that class does not exist, please use one of the following:'."\n".$classes2);
                    }
                }
            }

            if($a[1] == 'show'){
                $class = $db->get($a[2].'-class');
                $message->reply($a[2].' is a '.$class);
            }

            if ($a[1] == 'help'){
                $message->reply("here are the commands for !class
                set - set someones class
                show - show someones class\n
                usage:
                !class [command] [mention] [class]");
            }
        }

        #
        #   Attributes Stuff
        #

        if($a[0] == '!atts'){

            $message->reply('this function is still in development');

        }
        
        #
        #   Items
        #
        
        //item code here
        
        #
        #   Weapons
        #
        
        if($a[0] == 'weapon'){
            if($a[1] == 'use'){
                //some code here
            }
        }
        
        #
        #   Stats command
        #

        if ($a[0] == '!stats'){
            if (isset($a[1]) && startsWith($a[1], "<@")){
                $statsuserid =  trim($a[1], '<@>');
                $statsmessages222 = $statsuserid.'-'.$message->full_channel->guild->id.'-messages';
                $statsmessages = $db->get($statsmessages222);
                $message->reply($a[1].' has send '.$statsmessages.' messages');
            }
            else{
                $message->reply("this command uses the following syntax:
                !stats [mention]");
            }
        }

        #
        #   Say command
        #

        if (startsWith($message->content, '%say') && $message->author->username == $settings->ownername){
            $data = explode(" ", $message->content);
            if (startsWith($data[1], "<@")){
                $message->reply($message->author->avatar);
            }
        }

        #
        #   Mention Geek Bot replies with Cleverbot
        #

        if (startsWith($message->content, '<@120230450277908480>')){
            $message->reply("i'm not smart enough to do that yet");
        }


        #
        #   Messages to console
        #

        $reply = $message->timestamp->format('d/m/y H:i:s').' - ';
        $reply .= $message->full_channel->guild->name.' - ';
        $reply .= $message->author->username.' - ';
        $reply .= $message->content;
        echo $reply.PHP_EOL;
    });
});

$ws->on(Event::PRESENCE_UPDATE, function ($member, $discord){
    $message = $member->user->id.' is now '.$member->status;
    print($message).PHP_EOL;
    //print_r($member);
});

$ws->on(Event::GUILD_MEMBER_ADD, function ($member, $discord) {
    //some code here
});

$ws->run();
