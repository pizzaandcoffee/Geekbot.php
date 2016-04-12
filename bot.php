<?php
include __DIR__.'/vendor/autoload.php';
include __DIR__.'/Victoria.php';

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\WebSocket;
use Victoria\VictoriaDB;
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

        #   db Stuff
        $db = new VictoriaSettings();
        $idb = new VictoriaDB();
        #   Get message Count
        $db_messagesname = $message->author->id.'-'.$message->full_channel->guild->id.'-messages';
        $amountofmessages = $db->get($db_messagesname);
        $newamountofmessages = $amountofmessages + 1;
        $db->put($db_messagesname, $newamountofmessages);
        #   split message in array
        $oa = preg_replace('/\s+/', ' ',strtolower($message->content));
        $a = explode(' ', $oa);
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

        if($a[0] == '%array' && $message->author->username == $settings->ownername && $message->full_channel->guild->name == "Swiss Geeks"){
            print_r($newdiscord).PHP_EOL;
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
            !cat - shows a random cat picture
            !8ball - let the allknowingly 8ball answer your question
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

            $rpgclasses = ['geek', 'neckbeard', 'console-peasant', 'neko', 'furry','laladin', 'yandere', 'script-kiddie', 
                            'zweihorn', 'affe-mit-waffe', 'glitzertier'];
            $classes2 = implode(", ",$rpgclasses);

            if($a[1] == 'set'){
                if(startsWith($a[2], '<@')){
                    
                    if(in_array($a[3], $rpgclasses) || $author == $settings->ownername){
                        $db->put($a[2].'-class', $a[3]);
                        $message->reply($a[2].' is now a '. ucfirst($a[3]));
                    }
                    else{
                        $message->reply('that class does not exist, please use one of the following:'."\n".$classes2);
                    }
                }
            }

            if($a[1] == 'show'){
                $class = $db->get($a[2].'-class');
                $message->reply($a[2].' is a '.ucfirst($class));
            }

            if ($a[1] == 'help'){
                $message->reply("here are the commands for !class
                set - set someones class
                show - show someones class\n
                usage:
                !class [command] [mention] [class]\n
                useable classes:
                {$classes2}");
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
        
        if($a[0] == '!weapon'){
            $allweapons = $idb->get_all('aura');
            $allweaponsarray = [];
            foreach ($allweapons as $w){
                $allweaponsarray[] = $w->name;
            }
            if($a[1] == 'use'){
                $message->reply('This function is still in development');
            }
            elseif ($a[1] == 'show'){
                if($a[2] == 'all'){
                    $allweaponsstring = implode("\n",$allweaponsarray);
                    $message->reply("here is a list of all weapons\n".$allweaponsstring);
                }
                else{

                }
            }
        }

        #
        #   Locations command
        #

        if($a[0] == '!location'){
            $alllocations = $idb->get_all('locations');
            $alllocationsarray = [];
            foreach ($alllocations as $w){
                $alllocationsarray[] = $w->name;
            }
            if($a[1] == 'show'){
                if($a[2] == 'all'){
                    $alllocationsstring = implode("\n",$alllocationsarray);
                    $message->reply("here is a list of all locations\n".$alllocationsstring);
                }
                else{
                    if(in_array($a[2], $alllocationsarray)){

                    }
                }
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
                $message->reply($a[1].' has sent '.$statsmessages.' messages');
            }
            else{
                $message->reply("this command uses the following syntax:
                !stats [mention]");
            }
        }

        #
        #   Say command
        #

        if (startsWith($message->content, '!say') && $message->author->username == $settings->ownername){
            unset($a[0]);
            $newsayarray = implode(' ',$a);
            print_r($newsayarray);
            $message->reply($newsayarray);

        }

        #
        #   useless commands
        #

        if($a[0] == '!cat'){
            if(isset($a[1]) && $a[1] == 'help'){
                $message->reply("Return a random image of a cat\n
                usage:
                !cat");
            }
            else {
                $catsource = file_get_contents('http://random.cat/meow');
                $catcontent = json_decode($catsource);
                $message->reply($catcontent->file);
            }
        }

        if($a[0] == '!8ball'){
            if(isset($a[1]) && $a[1] == 'help'){
                $message->reply("Ask the allknowingly 8ball a question\n
                usage:
                !8ball [question]");
            }
            elseif(isset($a[1])) {
                $ballanswers = ['It is certain', 'It is decidedly so', 'Without a doubt', 'Yes, definitely', 'You may rely on it',
                    'As I see it, yes', 'Most likely', 'Outlook good', 'Yes', 'Signs point to yes', 'Reply hazy try again',
                    'Ask again later', 'Better not tell you now', 'Cannot predict now', 'Concentrate and ask again', "Don't count on it",
                    'My reply is no', 'My sources say no', 'Outlook not so good', 'Very doubtful'];
                $message->reply($ballanswers[array_rand($ballanswers)]);
            }
            else{
                $message->reply('you must ask a question!');
            }
        }

        if($a[0] == '!choose'){
            if(isset($a[1]) && $a[1] == 'help'){
                $message->reply("Let Geek Bot make the choice for you!\n
                usage:
                !choose [option1] [option2] ([option3] ...)");
            }
            elseif(isset($a[1]) && isset($a[2])) {
                unset($a[0]);
                $thechoice = "my choice is '" . $a[array_rand($a)] . "'";
                $message->reply($thechoice);
            }
            else{
                $message->reply('please provide atleast 2 options');
            }
        }

        #
        #   Pokedex
        #

        if($a[0] == '!pokedex'){
            $pokedexoptions = ['all', 'image', 'type'];
            if(isset($a[1]) && $a[1] == 'help'){
                $message->reply("this return all information about a pokemon\n
                usage:
                !pokedex [name|number] [all|image|type]");
            }
            elseif (isset($a[1]) && isset($a[2]) && in_array($a[2], $pokedexoptions)){
                $message->reply('please wait a moment while i fetch all the information');
                $getrawpkdata = file_get_contents("http://pokeapi.co/api/v2/pokemon/".$a[1]);
                if (startsWith($getrawpkdata, '{')){
                    $pkd = \GuzzleHttp\json_decode($getrawpkdata);
                    if($a[2] == 'image'){
                        $message->reply($pkd->sprites->front_default);
                    }
                    elseif($a[2] == 'type'){
                        $message->reply("this is still in development");
                    }
                    else{ // all
                        $message->reply("here is all the information about #".$pkd->id." ".$pkd->name."
                        type: ".$pkd->types[0]->type->name." ".$pkd->types[1]->type->name."
                        weight: ".$pkd->weight."lbs
                        height: ".$pkd->height."inch
                        ".$pkd->sprites->front_default);
                    }
                }
                else{
                    $message->reply("that is not a pokemon...");
                }
            }
            else{
                $message->reply('That command in not valid, please see !pokedex help');
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
