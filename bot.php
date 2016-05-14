<?php

include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/victoria.php';

use Discord\Discord;
use Discord\WebSockets\WebSocket;
use Victoria\VictoriaSettings;

$envjson = file_get_contents('env.json');
$settings = json_decode($envjson);

$discord = new Discord($settings->token);
$ws = new WebSocket($discord);

date_default_timezone_set('Europe/Amsterdam');

function startsWith($haystack, $needle) {
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function xml_attribute($object, $attribute) {
    if (isset($object[$attribute]))
        return (string) $object[$attribute];
}

function dump($nope) {
    print($nope);
}

function calculateLevel($messages){
    $total = 0;
    $levels = [];
    for ($i = 1; $i < 100; $i++)
    {
        $total += floor($i + 300 * pow(2, $i / 7.0));
        $levels[] = floor($total / 4);
    }
    $level = 1;
    foreach($levels as $l){
        if ($l < $messages){
            $level++;
        } else {
            break;
        }
    }
    return $level;
}

if ($settings->leveldb == 'true') {
    echo("using leveldb...\n");
    $db = new LevelDB(__DIR__ . '/db');
} else {
    echo("using victoriadb...\n");
    $db = new VictoriaSettings();
}

$ws->on('ready', function ($discord) use ($ws, $settings, $db, $discord) {
    $discord->updatePresence($ws, "Ping Pong", 0);
    echo "bot is ready!" . PHP_EOL;

    $ws->on('message', function ($message) use ($settings, $db, $ws, $discord) {

        #
        #   Strings
        #
        $author = $message->author->username;
        $authorid = $message->author->id;
        #   Get message Count
        $db_messagesname = $authorid . '-' . $message->channel->guild_id . '-messages';
        $amountofmessages = $db->get($db_messagesname);
        $newamountofmessages = $amountofmessages + 1;
        $db->put($db_messagesname, $newamountofmessages);
        $now = date(DATE_RFC2822);
        $db->put($authorid . '-last', $now);
        #   split message in array
        $oa = preg_replace('/\s+/', ' ', strtolower($message->content));
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

        if ($a[0] == '!idiot') {
            if ($authorid == '93421536890859520') {
                $message->reply('die Halluzination findet euch alle BEKLOPPT!');
            } else {
                $message->reply('du bist KEINE HALLUZINATION *triggered*');
            }
        }
        
        #
        #   Debugging purposes
        #

        if ($a[0] == '%array' && $message->author->username == $settings->ownername) {
            print_r($message) . PHP_EOL;
            $guild = $discord->guilds->first();
            $member = $guild->members->first();
            $role = $member->roles->first();
            print_r($guild).PHP_EOL;
            print_r($member).PHP_EOL;
            print_r($role).PHP_EOL;
            print_r($a);
        }

        if ($a[0] == '%level'){
            $message->reply(calculateLevel($newamountofmessages));
        }

        #
        #   Help Command
        #

        if ($a[0] == '!help') {
            $message->reply("here is a list of all commands:
            !level - level settings for each user
            !class - class settings for each user
            !bad - a bad joke counter
            !last - see when the mentioned user last sent something
            !stats - show stats for each user
            !cat - shows a random cat picture
            !8ball - let the allknowingly 8ball answer your question
            !pokedex - does what a pokedex does
            !porn - :smirk:
            !fortune - get a fortune or quote
            !4chan - get a totally random image from 4chan (be aware of shitposts)
            Geekbot also knows how to respond to several words\n
            for more info about each command use
            ![command] help");
        }

        #
        #   Level Command
        #

        if ($a[0] == '!level') {

            if ($a[1] == 'add') {
                if (startsWith($a[2], '<@')) {
                    if (is_numeric($a[3])) {
                        $oldlevel = $db->get($a[2] . '-level');
                        $level = $oldlevel + $a[3];
                        $db->put($a[2] . '-level', $level);
                        $message->reply("{$a[2]} leveled up with {$a[3]} levels and is now level {$level}");
                    }
                }
            } elseif ($a[1] == 'drop') {
                if (startsWith($a[2], '<@')) {
                    if (is_numeric($a[3])) {
                        $oldlevel = $db->get($a[2] . '-level');
                        $level = $oldlevel - $a[3];
                        $db->put($a[2] . '-level', $level);
                        $message->reply("{$a[2]} dropped down with {$a[3]} levels and is now level {$level}");
                    }
                }
            } elseif ($a[1] == 'set') {
                if (startsWith($a[2], '<@')) {
                    if (is_numeric($a[3])) {
                        $oldlevel = $db->get($a[2] . '-level');
                        $level = $a[3];
                        $db->put($a[2] . '-level', $level);
                        $message->reply("{$a[2]} went from level {$oldlevel} to level {$level}");
                    }
                }
            } elseif ($a[1] == 'show') {
                $level = $db->get($a[2] . '-level');
                $message->reply("{$a[2]}'s level is {$level}");
            } elseif ($a[1] == 'help') {
                $message->reply("here are the commands for !level
                add - add a level to someone
                drop - lower someones level
                show - show someones level\n
                usage:
                !level [command] [mention] [value]");
            } else {
                $message->reply("Wrong syntax for the command !level, please see !level help to see how the command works");
            }
        }

        #
        #   Class Command
        #

        if ($a[0] == '!class') {

            $rpgclasses = ['geek', 'neckbeard', 'console-peasant', 'neko', 'furry', 'laladin', 'yandere', 'script-kiddie',
                'zweihorn', 'affe-mit-waffe', 'glitzertier'];
            $classes2 = implode(", ", $rpgclasses);

            if ($a[1] == 'set') {
                if (startsWith($a[2], '<@')) {

                    if (in_array($a[3], $rpgclasses) || $author == $settings->ownername) {
                        $db->put($a[2] . '-class', $a[3]);
                        $message->reply("{$a[2]} ist jetzt ein " . ucfirst($a[3]));
                    } else {
                        $message->reply("that class does not exist, please use one of the following: \n {$classes2}");
                    }
                }
            } elseif ($a[1] == 'show') {
                $class = $db->get($a[2] . '-class');
                $message->reply("{$a[2]} is a ".ucfirst($class));
            } elseif ($a[1] == 'help') {
                $message->reply("here are the commands for !class
                set - set someones class
                show - show someones class\n
                usage:
                !class [command] [mention] [class]\n
                useable classes:
                {$classes2}");
            } else {
                $message->reply("Wrong syntax for the command !class, please see !class help to see how the command works");
            }
        }

        #
        #   Last online
        #

        if ($a[0] == '!last') {
            if (startsWith($a[1], '<@')) {
                $user = trim($a[1], '<@>');
                $last = $db->get($user . '-last');
                $message->reply("{$a[1]} sent his last message on {$last}");
            } else {
                $message->reply('please mention someone');
            }
        }

        #
        #   Stats command
        #

        if ($a[0] == '!stats') {
            if (isset($a[1]) && startsWith($a[1], "<@")) {
                $statsuserid = trim($a[1], '<@>');
                $statsmessages222 = $statsuserid . '-' . $message->channel->guild_id . '-messages';
                $statsmessages = $db->get($statsmessages222);
                $badjokes = $db->get($a[1] . '-badjokes');
                $level = $db->get($a[1] . '-level');
                $class = $db->get($a[1] . '-class');
                $message->reply("stats for " . $a[1] . " 
                Messages sent: " . $statsmessages . " 
                Bad jokes made: " . $badjokes . " 
                Level: " . $level . " 
                Actual Level: " . calculateLevel($statsmessages) . "
                Class: " . $class . " 
                Last Message: 
                " . $db->get($statsuserid . '-last') . " ");
            } else {
                $message->reply("this command uses the following syntax:
                !stats [mention]");
            }
        }

        #
        #   bad joke counter
        #

        if ($a[0] == '!bad' || $a[0] == '!shit') {
            if (isset($a[1]) && $a[1] == 'show') {
                if (isset($a[2]) && startsWith($a[2], '<@')) {
                    $bads = $db->get($a[2] . '-badjokes');
                    $message->reply("{$a[2]} made {$bads} bad jokes");
                } else {
                    $message->reply('please specify a user');
                }
            } elseif (isset($a[1]) && startsWith($a[1], '<@')) {
                $old = $db->get($a[1] . '-badjokes');
                $new = $old + 1;
                $db->put($a[1] . '-badjokes', $new);
                $message->reply("{$a[1]} made a bad joke");
            } else {
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

        if (startsWith($message->content, '!say') && $message->author->username == $settings->ownername) {
            unset($a[0]);
            $newsayarray = implode(' ', $a);
            print_r($newsayarray);
            $message->reply($newsayarray);
        }

        #
        #   useless commands
        #

        if ($a[0] == '!cat') {
            if (isset($a[1]) && $a[1] == 'help') {
                $message->reply("Return a random image of a cat\n
                usage:
                !cat");
            } else {
                $catsource = file_get_contents('http://random.cat/meow');
                $catcontent = json_decode($catsource);
                $message->reply($catcontent->file);
            }
        }

        if ($a[0] == '!8ball') {
            if (isset($a[1]) && $a[1] == 'help') {
                $message->reply("Ask the allknowingly 8ball a question\n
                usage:
                !8ball [question]");
            } elseif (isset($a[1])) {
                $ballanswers = ['It is certain', 'It is decidedly so', 'Without a doubt', 'Yes, definitely', 'You may rely on it',
                    'As I see it, yes', 'Most likely', 'Outlook good', 'Yes', 'Signs point to yes', 'Reply hazy try again',
                    'Ask again later', 'Better not tell you now', 'Cannot predict now', 'Concentrate and ask again', "Don't count on it",
                    'My reply is no', 'My sources say no', 'Outlook not so good', 'Very doubtful'];
                $message->reply($ballanswers[array_rand($ballanswers)]);
            } else {
                $message->reply('you must ask a question!');
            }
        }

        if ($a[0] == '!choose') {
            if (isset($a[1]) && $a[1] == 'help') {
                $message->reply("Let Geek Bot make the choice for you!\n
                usage:
                !choose [option1] [option2] ([option3] ...)");
            } elseif (isset($a[1]) && isset($a[2])) {
                unset($a[0]);
                $thechoice = "my choice is '{$a[array_rand($a)]}'";
                $message->reply($thechoice);
            } else {
                $message->reply('please provide atleast 2 options');
            }
        }

        if ($a[0] == '!porn') {
            if (isset($a[1]) && $a[1] == 'help') {
                $message->reply("shows some porn :smirk:\n
                usage:
                !porn [tags]");
            } elseif (isset($a[1])) {
                $getgelbooru = file_get_contents('http://gelbooru.com/index.php?page=dapi&s=post&q=index&tags=rating:explicit ' . substr($ac, 5));
                $xmlgelbooru = new SimpleXMLElement($getgelbooru);
                $randomnumberporn = rand(1, 100);
                $message->reply(xml_attribute($xmlgelbooru->post[$randomnumberporn], 'file_url'));
            } else {
                $message->reply('Please use atleast one tag');
            }
        }

        #
        #   Pokedex
        #

        if ($a[0] == '!pokedex') {
            $pokedexoptions = ['all', 'image', 'type'];
            if (isset($a[1]) && $a[1] == 'help') {
                $message->reply("this return all information about a pokemon\n
                usage:
                !pokedex [name|number] [all|image|type]");
            } elseif (isset($a[1]) && isset($a[2]) && in_array($a[2], $pokedexoptions)) {
                $message->reply('please wait a moment while i fetch all the information');
                $getrawpkdata = file_get_contents("http://pokeapi.co/api/v2/pokemon/{$a[1]}");
                if (startsWith($getrawpkdata, '{')) {
                    $pkd = \GuzzleHttp\json_decode($getrawpkdata);
                    if ($a[2] == 'image') {
                        $message->reply($pkd->sprites->front_default);
                    } elseif ($a[2] == 'type') {
                        $message->reply("this is still in development");
                    } else { // all
                        $message->reply("here is all the information about #{$pkd->id} {$pkd->name}
                        type: {$pkd->types[0]->type->name} {$pkd->types[1]->type->name}
                        weight: {$pkd->weight}lbs
                        height: {$pkd->height}inch
                        {$pkd->sprites->front_default}");
                    }
                } else {
                    $message->reply("that is not a pokemon...");
                }
            } else {
                $message->reply('That command in not valid, please see !pokedex help');
            }
        }

        #
        #   Fortunes command
        #

        if ($a[0] == '!fortune') {
            if($a[1] == 'help'){
                $message->reply("!fortune return a random random forune");
            } else {
                $fortunes = file_get_contents('fortunes');
                $array = explode('%', $fortunes);
                $fortune = $array[array_rand($array)];
                $message->reply($fortune);
            }
        }

        #
        #   4chan command
        #

        if ($a[0] == "!4chan") {
            $curloptions = array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=>"Accept-language: en\r\n" .
                        "Cookie: foo=bar\r\n" .
                        "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
                )
            );
            $curlcontext = stream_context_create($curloptions);
            $boards = '[a / b / c / d / e / f / g / gif / h / hr / k / m / o / p / r / s / t / u / v / vg / vr / w / wg] [i / ic] [r9k] [s4s] [cm / hm / lgbt / y] [3 / aco / adv / an / asp / biz / cgl / ck / co / diy / fa / fit / gd / hc / his / int / jp / lit / mlp / mu / n / news / out / po / pol / qst / sci / soc / sp / tg / toy / trv / tv / vp / wsg / wsr / x]';
            $boardstrim = str_replace(array('[',']', '/'), '',$boards);
            $boardspreg = preg_replace('/\s+/', ' ',$boardstrim);
            $boardsarray = explode(' ', $boardspreg);
            if (!isset($a[1])){
                $theboard = $boardsarray[array_rand($boardsarray)];
            } else {
                $theboard = $a[1];
            }
            if(in_array($theboard, $boardsarray)) {
                $board = $theboard;

                $catalogjson = file_get_contents("https://a.4cdn.org/{$board}/catalog.json", false, $curlcontext);
                $catalog = json_decode($catalogjson);
                $randompage = $catalog[array_rand($catalog)];
                $whatever = $randompage->threads;
                $randomthread= $whatever[array_rand($whatever)];
                $stuff = $randomthread->no;
                $getthread = file_get_contents("https://a.4cdn.org/{$board}/thread/{$stuff}.json", false, $curlcontext);
                $thread = json_decode($getthread);
                $hasimage = 0;
                $postnumbers = count($thread->posts);
                $image = null;
                $triednr = [];
                $i = 0;
                $originalthread = "https://boards.4chan.org/{$board}/thread/{$stuff}";
                while ($hasimage == 0) {
                    $postnr = random_int(0, $postnumbers);
                    if(in_array($postnr, $triednr)){
                        if(count($triednr) == $postnumbers){
                            $hasimage = 2;
                        }
                    } else {
                        if (isset($thread->posts[$postnr]->tim)) {
                            $image = $thread->posts[$postnr]->tim . $thread->posts[$postnr]->ext;
                            $hasimage = 1;
                        } else {
                            $triednr[] = $postnr;
                        }
                    }
                    $i++;
                }
                print("{$i}\n");
                if($hasimage == 1) {
                    $file = "http://i.4cdn.org/{$board}/{$image}";
                    $message->reply($file.' from '.$originalthread);
                } else {
                    $message->reply('there are no images in this thread!');
                }
            } else if ($a[1] == 'help') {
                $message->reply("get a totally random image or from a specified board
                Usage:
                !4chan ([board])");
            } else {
                $message->reply("that is not a valid board");
            }
        }

        #
        #   Output message to console
        #

        $reply = $message->timestamp->format('d/m/y H:i:s') . ' - ';
        $reply .= $message->author->username . ' - ';
        $reply .= $message->content;
        echo $reply . PHP_EOL;
    });
}
);

$ws->on('error', function ($error, $ws) {
    dump($error);
    exit(1);
}
);

$ws->run();