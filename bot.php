<?php
include __DIR__.'/vendor/autoload.php';
include __DIR__.'/victoria.php';

use Discord\Discord;
use Discord\Voice\VoiceClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Victoria\VictoriaDB;
use Victoria\VictoriaSettings;

$envjson = file_get_contents('env.json');
$settings = json_decode($envjson);

$discord = new Discord(['token' => $settings->token]);

$ws      = $discord->getWebsocket();

date_default_timezone_set('Europe/Amsterdam');

function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function xml_attribute($object, $attribute)
{
    if(isset($object[$attribute]))
        return (string) $object[$attribute];
}

function dump($nope){
    print($nope);
}

if($settings->leveldb == 'true') {
    echo("using leveldb...\n");
    $db = new LevelDB(__DIR__ . '/db');
}else{
    echo("using victoriadb...\n");
    $db = new VictoriaSettings();
}
$idb = new VictoriaDB();

$ws->on('ready', function ($discord) use ($ws, $settings, $db, $idb, $discord) {
    $discord->updatePresence($ws, "Ping Pong", 0);
    echo "bot is ready!".PHP_EOL;

    $ws->on(Event::MESSAGE_CREATE, function ($message) use ($settings, $db, $idb, $ws, $discord) {

        #
        #   Strings
        #
        $author = $message->author->username;
        $authorid = $message->author->id;
        #   Get message Count
        $db_messagesname = $authorid.'-'.$message->channel->guild_id.'-messages';
        //$db_messagesname = $message->author->id.'-'.$message->full_channel->guild->id.'-messages';
        $amountofmessages = $db->get($db_messagesname);
        $newamountofmessages = $amountofmessages + 1;
        $db->put($db_messagesname, $newamountofmessages);
        $now = date(DATE_RFC2822);
        $db->put($authorid.'-last', $now);
        #   split message in array
        $oa = preg_replace('/\s+/', ' ',strtolower($message->content));
        $a = explode(' ', $oa);
        $ac = strtolower($message->content);


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

        if ($a[0] == '!idiot'){
            if($authorid == '93421536890859520'){
                $message->reply('die Halluzination findet euch alle BEKLOPPT!');
            } else {
                $message->reply('du bist KEINE HALLUZINATION *triggered*');
            }
        }

//        if ($author !== $settings->botname &&
//            $author !== $settings->ownername){
//            if (strpos(strtolower($message->content), 'cookies') !== false){
//                $message->reply("All cookies belong to his pumpkinness Rune!");
//            }
//        }

        #
        #   Debugging purposes
        #

        if($a[0] == '%array' && $message->author->username == $settings->ownername){
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
            !bad - a bad joke counter
            !last - see when the mentioned user last sent something
            !stats - show stats for each user
            !atts - attribute setttings
            !cat - shows a random cat picture
            !8ball - let the allknowingly 8ball answer your question
            !pokedex - does what a pokedex does
            !porn - :smirk:
            !fortune - get a fortune or quote
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

            elseif ($a[1] == 'drop') {
                if (startsWith($a[2], '<@')) {
                    if (is_numeric($a[3])) {
                        $oldlevel = $db->get($a[2] . '-level');
                        $level = $oldlevel - $a[3];
                        $db->put($a[2] . '-level', $level);
                        $message->reply($a[2] . ' dropped down with ' . $a[3] . ' levels and is now level ' . $level);
                    }
                }
            }

            elseif ($a[1] == 'set') {
                if(startsWith($a[2], '<@')){
                    if(is_numeric($a[3])){
                        $oldlevel = $db->get($a[2] . '-level');
                        $level = $a[3];
                        $db->put($a[2] . '-level', $level);
                        $message->reply($a[2] . ' went from level ' . $oldlevel . ' to level ' . $level);
                    }
                }
            }

            elseif ($a[1] == 'show') {
                $level = $db->get($a[2] . '-level');
                $message->reply($a[2] . "'s level is " . $level);
            }

            elseif ($a[1] == 'help'){
                $message->reply("here are the commands for !level
                add - add a level to someone
                drop - lower someones level
                show - show someones level\n
                usage:
                !level [command] [mention] [value]");
            }
            else{
                $message->reply("Wrong syntax for the command !level, please see !level help to see how the command works");
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
                        $message->reply($a[2].' ist jetzt ein '. ucfirst($a[3]));
                    }
                    else{
                        $message->reply('that class does not exist, please use one of the following:'."\n".$classes2);
                    }
                }
            }

            elseif($a[1] == 'show'){
                $class = $db->get($a[2].'-class');
                $message->reply($a[2].' is a '.ucfirst($class));
            }

            elseif ($a[1] == 'help'){
                $message->reply("here are the commands for !class
                set - set someones class
                show - show someones class\n
                usage:
                !class [command] [mention] [class]\n
                useable classes:
                {$classes2}");
            }
            else{
                $message->reply("Wrong syntax for the command !class, please see !class help to see how the command works");
            }
        }


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
        #   Last online
        #

        if($a[0] == '!last'){
            if(startsWith($a[1], '<@')){
                $user = trim($a[1], '<@>');
                $last = $db->get($user.'-last');
                $message->reply($a[1].' sent his last message on '.$last);
            }
            else {
                $message->reply('please mention someone');
            }
        }

        #
        #   Stats command
        #

        if ($a[0] == '!stats'){
            if (isset($a[1]) && startsWith($a[1], "<@")){
                $statsuserid =  trim($a[1], '<@>');
                $statsmessages222 = $statsuserid.'-'.$message->channel->guild_id.'-messages';
                $statsmessages = $db->get($statsmessages222);
                $badjokes = $db->get($a[1].'-badjokes');
                $level = $db->get($a[1] . '-level');
                $class = $db->get($a[1].'-class');
                $message->reply("stats for ".$a[1]." 
                Messages sent: ".$statsmessages." 
                Bad jokes made: ".$badjokes." 
                Level: ".$level." 
                Class: ".$class." 
                Letzte Nachricht: 
                ".$db->get($statsuserid.'-last')." ");
            }
            else{
                $message->reply("this command uses the following syntax:
                !stats [mention]");
            }
        }

        #
        #   bad joke counter
        #

        if($a[0] == '!bad' || $a[0] == '!shit'){
            if(isset($a[1]) && $a[1] == 'show'){
                if (isset($a[2]) && startsWith($a[2], '<@')){
                    $bads = $db->get($a[2].'-badjokes');
                    $message->reply($a[2].' made '.$bads.' bad jokes');
                }
                else{
                    $message->reply('please specify a user');
                }
            }
            elseif(isset($a[1]) && startsWith($a[1], '<@')) {
                $old = $db->get($a[1].'-badjokes');
                $new = $old + 1;
                $db->put($a[1].'-badjokes', $new);
                $message->reply($a[1].' made a bad joke');
            }
            else{
                $message->reply("the bad joke counter
                show - shows the amount of bad jokes
                @mention - adds 1 to the bad joke counter\n
                usage:
                !bad [show|@mention] ([@mention])");
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

        if($a[0] == '!porn'){
            if(isset($a[1]) && $a[1] == 'help'){
                $message->reply("shows some porn :smirk:\n
                usage:
                !porn [tags]");
            }
            elseif(isset($a[1])) {
                $getgelbooru = file_get_contents('http://gelbooru.com/index.php?page=dapi&s=post&q=index&tags=' . $a[1]);
                $xmlgelbooru = new SimpleXMLElement($getgelbooru);
                $randomnumberporn = rand(1, 100);
                $message->reply(xml_attribute($xmlgelbooru->post[$randomnumberporn], 'file_url'));
            }
            else{
                $message->reply('Please use atleast one tag');
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
        #   Fortunes command
        #

        if($a[0] == '!fortune'){
            $fortunes = file_get_contents('fortunes');
            $array = explode('%', $fortunes);
            $fortune = $array[array_rand($array)];
            $message->reply($fortune);
        }

        #
        #   Voice command
        #

        if($a[0] == '!s'){
            if(in_array($a[1], ['airhorn', 'cena', 'ethan'])){
                $guild = $discord->guilds->first();
                $channel = $guild->channels->get('type', 'voice');
                echo "Connecting to {$guild->name} {$channel->name}...\r\n";
                $ssound = __DIR__.'/audio/airhorn_default.wav';
                switch($a[1]){
                    case 'airhorn':
                        $ssound = __DIR__.'/audio/airhorn_default.wav';
                        break;
                    case 'cena':
                        $ssound = __DIR__.'/audio/jc_full.wav';
                        break;
                    case 'ethan':
                        $ssound = __DIR__.'/audio/ethan_areyou_classic.wav';
                        break;
                    default:
                        $ssound = __DIR__.'/audio/airhorn_default.wav';
                        break;
                }
                $ws->joinVoiceChannel($channel)->then(function (VoiceClient $vc) use ($ws, $a, $ssound) {
                    $vc->setFrameSize(40)->then(function () use ($vc, $ws, $a, $ssound) {
                        $vc->playFile($ssound);
                    });
                });
            }
            else{
                $message->reply("play a sound from a wide variety of sound files:
                airhorn, cena, ethan\n
                usage:
                !s [sound]");
            }
        }


        #
        #   Mention Geek Bot replies with Cleverbot
        #

        if (startsWith($message->content, '<@120230450277908480>')){
            $message->reply("i'm not smart enough to do that yet");
        }

        $reply = $message->timestamp->format('d/m/y H:i:s').' - '; // Format the message timestamp.
        //$reply .= ($message->channel->is_private ? 'PM' : $message->channel->guild->name).' - ';
        $reply .= $message->author->username.' - '; // Add the message author's username onto the string.
        $reply .= $message->content; // Add the message content.
        echo $reply.PHP_EOL; // Finally, echo the message with a PHP end of line.
        });
    }
);

$ws->on('error', function ($error, $ws) {
        dump($error);
        exit(1);
    }
);

// Now we will run the ReactPHP Event Loop!
$ws->run();
