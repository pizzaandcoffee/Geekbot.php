<?php
namespace Geekbot;

use PHPHtmlParser\Dom;

class Commands {
    //construct parameters
    private $message;
    private $db;
    private $settings;
    private $utils;
    //too much stuff
    private $author;
    private $authorid;
    private $db_messagesname;
    private $amountofmessages;
    private $newamountofmessages;
    private $db_messagesname_guild;
    private $amountofmessages_guild;
    private $newamountofmessages_guild;
    private $now;
    private $oa;
    private $a;
    private $ac;
           
    function __construct($message, $db, $settings, $utils) {
        $this->db = $db;
        $this->message = $message;
        $this->settings = $settings;
        $this->utils = $utils;
        
        $this->author = $message->author->username;
        $this->authorid = $message->author->id;
        #   Get message Count
        $this->db_messagesname = $this->authorid . '-' . $message->channel->guild_id . '-messages';
        $this->amountofmessages = $this->db->get($this->db_messagesname);
        $this->newamountofmessages = $this->amountofmessages + 1;
        $this->db->put($this->db_messagesname, $this->newamountofmessages);
        #   Get message Count for Server Stats
        $this->db_messagesname_guild = $message->channel->guild_id . '-messages';
        $this->amountofmessages_guild = $this->db->get($this->db_messagesname_guild);
        $this->newamountofmessages_guild = $this->amountofmessages_guild + 1;
        $this->db->put($this->db_messagesname_guild, $this->newamountofmessages_guild);
        #   update last message
        $this->now = date(DATE_RFC2822);
        $this->db->put($this->authorid . '-last', $this->now);
        #   split message in array
        $this->oa = preg_replace('/\s+/', ' ', strtolower($message->content));
        $this->a = explode(' ', $this->oa);
        $this->ac = strtolower($message->content);
    }
    
  
    
    public function getA() {
        return $this->a;
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    //-------------------------------------------------------------------------
    // Help Command
    //-------------------------------------------------------------------------
    public function help() {
            $this->message->reply("here is a list of all commands:
            !level - level settings for each user
            !classes - class settings for each user
            !bad - a bad joke counter
            !last - see when the mentioned user last sent something
            !stats - show stats for each user
            !cat - shows a random cat picture
            !ball - let the allknowingly 8ball answer your question
            !pokedex - does what a pokedex does
            !porn - :smirk:
            !fortune - get a fortune or quote
            !chan - get a totally random image from 4chan (be aware of shitposts)
            Geekbot also knows how to respond to several words\n
            for more info about each command use
            !anime - looks up an anime from myanimelist
            !manga - looks up a a manga from myanimelist
            ![command] help");
    }
    //-------------------------------------------------------------------------
    // Debugging purposes
    //-------------------------------------------------------------------------
    public function debugArray() {
        if ($this->message->author->username == $this->settings->ownername) {
            print_r($this->message) . PHP_EOL;
            print_r($this->a);
            print_r($this->a[1]) . PHP_EOL;
        }
    }
    
    public function debugLevel() {
        $this->message->reply(calculateLevel($this->newamountofmessages));
    }
    
    //-------------------------------------------------------------------------
    // Commands
    //-------------------------------------------------------------------------
    public function idiot(){
            if ($this->authorid == '93421536890859520') {
                $this->message->reply('die Halluzination findet euch alle BEKLOPPT!');
            } else {
                $this->message->reply('du bist KEINE HALLUZINATION *triggered*');
            }
    }
    
    //-------------------------------------------------------------------------
    // Level Command
    //-------------------------------------------------------------------------
    public function level(){
        if ($this->utils->startsWith($this->a[1], '<@')){
            $userid = trim($this->a[1], '<@>');
            $messagesdbstring = $userid . '-' . $this->message->channel->guild_id . '-messages';
            $messages = $this->db->get($messagesdbstring);
            $thelevel = $this->utils->calculateLevel($messages);
            $this->message->reply($this->a[1]."s level is {$thelevel}");
        } elseif ($this->a[1] == 'server'){
            $thelevel = $this->utils->calculateLevel($this->newamountofmessages_guild);
            $this->message->reply("the server level is {$thelevel}");
        } else {
            $this->message->reply("Wrong syntax for the command !level, please see !level help to see how the command works");
        }
    }
    

    //-------------------------------------------------------------------------
    // Classes Command
    //-------------------------------------------------------------------------   
    public function classes() {
        $rpgclasses = ['geek', 'neckbeard', 'console-peasant', 'neko', 'furry', 'laladin', 'yandere', 'script-kiddie',
            'zweihorn', 'affe-mit-waffe', 'glitzertier'];
        $classes2 = implode(", ", $rpgclasses);

        if ($this->a[1] == 'set') {
            if ($this->utils->startsWith($this->a[2], '<@')) {

                if (in_array($this->a[3], $rpgclasses) || $this->author == $this->settings->ownername) {
                    $this->db->put($this->a[2] . '-class', $this->a[3]);
                    $this->message->reply("{$this->a[2]} ist jetzt ein " . ucfirst($this->a[3]));
                } else {
                    $this->message->reply("that class does not exist, please use one of the following: \n {$classes2}");
                }
            }
        } elseif ($this->a[1] == 'show') {
            $class = $this->db->get($this->a[2] . '-class');
            $this->message->reply("{$this->a[2]} is a " . ucfirst($class));
        } elseif ($this->a[1] == 'help') {
            $this->message->reply("here are the commands for !class
            set - set someones class
            show - show someones class\n
            usage:
            !class [command] [mention] [class]\n
            useable classes:
            {$classes2}");
        } else {
            $this->message->reply("Wrong syntax for the command !class, please see !class help to see how the command works");
        }
    }


    //-------------------------------------------------------------------------
    // Fortune Command
    //-------------------------------------------------------------------------
    public function fortune(){
        if ($this->a[1] == 'help') {
            $this->message->reply("!fortune return a random random forune");
        } else {
            $fortunes = file_get_contents('fortunes');
            $array = explode('%', $fortunes);
            $fortune = $array[array_rand($array)];
            $this->message->reply($fortune);
        }
    }
    
    //-------------------------------------------------------------------------
    // Last Online Command
    //-------------------------------------------------------------------------
    public function last(){
        if ($this->utils->startsWith($this->a[1], '<@')) {
            $user = trim($this->a[1], '<@>');
            $last = $this->db->get($user . '-last');
            $this->message->reply($this->a[1]." sent his last message on {$last}");
        } else {
            $this->message->reply('please mention someone');
        }
    }
    
    //-------------------------------------------------------------------------
    // Stats Command
    //-------------------------------------------------------------------------
    public function stats(){
        if (isset($this->a[1]) && $this->utils->startsWith($this->a[1], "<@")) {
            $statsuserid = trim($this->a[1], '<@>');
            $statsmessagesdbstring = $statsuserid . '-' . $this->message->channel->guild_id . '-messages';
            $statsmessages = $this->db->get($statsmessagesdbstring);
            $badjokes = $this->db->get($this->a[1] . '-badjokes');
            $class = $this->db->get($this->a[1] . '-class');
            $this->message->reply("stats for " . $this->a[1] . " 
            Messages sent: " . $statsmessages . " 
            Bad jokes made: " . $badjokes . " 
            Level: " . $this->utils->calculateLevel($statsmessages) . "
            Class: " . $class . " 
            Last Message: 
            " . $this->db->get($statsuserid . '-last') . " ");
        } elseif (isset($this->a[1]) && $this->utils->startsWith($this->a[1], 'server')){
            $this->message->reply("Stats for this server:
            Messages sent: {$this->amountofmessages_guild}
            Actual Level: ". $this->utils->calculateLevel($this->amountofmessages_guild)."
            (counting start 15 May 2016)");
        } else {
            $this->message->reply("this command uses the following syntax:
            !stats [mention]
            use @here for server stats");
        }
    }
    
    //-------------------------------------------------------------------------
    // Bad Joke Counter
    //-------------------------------------------------------------------------    
    public function bad(){
        if (isset($this->a[1]) && $this->a[1] == 'show') {
            if (isset($this->a[2]) && $this->utils->startsWith($this->a[2], '<@')) {
                $bads = $this->db->get($this->a[2] . '-badjokes');
                $this->message->reply($this->a[2]." made {$bads} bad jokes");
            } else {
                $this->message->reply('please specify a user');
            }
        } elseif (isset($this->a[1]) && $this->utils->startsWith($this->a[1], '<@')) {
            $old = $this->db->get($this->a[1] . '-badjokes');
            $new = $old + 1;
            $this->db->put($this->a[1] . '-badjokes', $new);
            $this->message->reply($this->a[1]." made a bad joke");
        } else {
            $this->message->reply("the bad joke counter
            show - shows the amount of bad jokes
            @mention - adds 1 to the bad joke counter\n
            usage:
            !bad [show|@mention] ([@mention])");
        }
    }
    
    //-------------------------------------------------------------------------
    // Useless Commands 
    // Cat, 8ball, choose
    //-------------------------------------------------------------------------   
    public function cat(){
        if (isset($this->a[1]) && $this->a[1] == 'help') {
            $this->message->reply("Return a random image of a cat
            usage:
            !cat");
        } else {
            $catsource = file_get_contents('http://random.cat/meow');
            $catcontent = json_decode($catsource);
            $this->message->reply($catcontent->file);
        }
    }
    
    public function ball(){
        if (isset($this->a[1]) && $this->a[1] == 'help') {
            $this->message->reply("Ask the allknowingly 8ball a question\n
            usage:
            !8ball [question]");
        } elseif (isset($this->a[1])) {
            $ballanswers = ['It is certain', 'It is decidedly so', 'Without a doubt', 'Yes, definitely', 'You may rely on it',
                'As I see it, yes', 'Most likely', 'Outlook good', 'Yes', 'Signs point to yes', 'Reply hazy try again',
                'Ask again later', 'Better not tell you now', 'Cannot predict now', 'Concentrate and ask again', "Don't count on it",
                'My reply is no', 'My sources say no', 'Outlook not so good', 'Very doubtful'];
            $this->message->reply($ballanswers[array_rand($ballanswers)]);
        } else {
            $this->message->reply('you must ask a question!');
        }
    }
    
    public function choose(){
        if (isset($this->a[1]) && $this->a[1] == 'help') {
            $this->message->reply("Let Geek Bot make the choice for you!\n
                usage:
                !choose [option1] [option2] ([option3] ...)");
        } elseif (isset($this->a[1]) && isset($this->a[2])) {
            unset($this->a[0]);
            $thechoice = "my choice is '{$this->a[array_rand($this->a)]}'";
            $this->message->reply($thechoice);
        } else {
            $this->message->reply('please provide atleast 2 options');
        }
    }
    
    public function say(){
        if ($this->message->author->username == $this->settings->ownername) {
            $tosay = substr($this->ac, 5);
            $this->message->reply($tosay);
        }
    }
    
    //-------------------------------------------------------------------------
    // Porn Command
    //-------------------------------------------------------------------------     
    public function porn(){
        if (isset($this->a[1]) && $this->a[1] == 'help') {
            $this->message->reply("shows some porn :smirk:\n
                usage:
                !porn [tags]");
        } elseif (isset($this->a[1])) {
            $getgelbooru = file_get_contents('http://gelbooru.com/index.php?page=dapi&s=post&q=index&tags=rating:explicit ' . substr($this->ac, 5));
            $xmlgelbooru = new SimpleXMLElement($getgelbooru);
            $randomnumberporn = rand(1, 100);
            $this->message->reply(xml_attribute($xmlgelbooru->post[$randomnumberporn], 'file_url'));
        } else {
            $this->message->reply('Please use atleast one tag');
        }
    }  
    
    //-------------------------------------------------------------------------
    // Pokedex Command
    //-------------------------------------------------------------------------      
    public function pokedex(){
        $pokedexoptions = ['all', 'image', 'type'];
        if (isset($this->a[1]) && $this->a[1] == 'help') {
            $this->message->reply("this return all information about a pokemon\n
            usage:
            !pokedex [name|number] [all|image|type]");
        } elseif (isset($this->a[1]) && isset($this->a[2]) && in_array($this->a[2], $pokedexoptions)) {
            $this->message->reply('please wait a moment while i fetch all the information');
            $getrawpkdata = file_get_contents("http://pokeapi.co/api/v2/pokemon/".$this->a[1]);
            if (startsWith($getrawpkdata, '{')) {
                $pkd = \GuzzleHttp\json_decode($getrawpkdata);
                if ($this->a[2] == 'image') {
                    $this->message->reply($pkd->sprites->front_default);
                } elseif ($this->a[2] == 'type') {
                    $this->message->reply("this is still in development");
                } else { 
                    $this->message->reply("here is all the information about #{$pkd->id} {$pkd->name}
                    type: {$pkd->types[0]->type->name} {$pkd->types[1]->type->name}
                    weight: {$pkd->weight}lbs
                    height: {$pkd->height}inch
                    {$pkd->sprites->front_default}");
                }
            } else {
                $this->message->reply("that is not a pokemon...");
            }
        } else {
            $this->message->reply('That command in not valid, please see !pokedex help');
        }
    }
    
    //-------------------------------------------------------------------------
    // 4chan Command
    //-------------------------------------------------------------------------      
    
    public function chan(){
        $curloptions = array(
            'http' => array(
                'method' => "GET",
                'header' => "Accept-language: en\r\n" .
                    "Cookie: foo=bar\r\n" .
                    "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
            )
        );
        $curlcontext = stream_context_create($curloptions);
        $boards = '[a / b / c / d / e / f / g / gif / h / hr / k / m / o / p / r / s / t / u / v / vg / vr / w / wg] [i / ic] [r9k] [s4s] [cm / hm / lgbt / y] [3 / aco / adv / an / asp / biz / cgl / ck / co / diy / fa / fit / gd / hc / his / int / jp / lit / mlp / mu / n / news / out / po / pol / qst / sci / soc / sp / tg / toy / trv / tv / vp / wsg / wsr / x]';
        $boardstrim = str_replace(array('[', ']', '/'), '', $boards);
        $boardspreg = preg_replace('/\s+/', ' ', $boardstrim);
        $boardsarray = explode(' ', $boardspreg);
        if (!isset($this->a[1])) {
            $theboard = $boardsarray[array_rand($boardsarray)];
        } else {
            $theboard = $this->a[1];
        }
        if (in_array($theboard, $boardsarray)) {
            $board = $theboard;

            $catalogjson = file_get_contents("https://a.4cdn.org/{$board}/catalog.json", false, $curlcontext);
            $catalog = json_decode($catalogjson);
            $randompage = $catalog[array_rand($catalog)];
            $whatever = $randompage->threads;
            $randomthread = $whatever[array_rand($whatever)];
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
                if (in_array($postnr, $triednr)) {
                    if (count($triednr) == $postnumbers) {
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
            if ($hasimage == 1) {
                $file = "http://i.4cdn.org/{$board}/{$image}";
                $this->message->reply($file . ' from ' . $originalthread);
            } else {
                $this->message->reply('there are no images in this thread!');
            }
        } else if ($this->a[1] == 'help') {
            $this->message->reply("get a totally random image or from a specified board
            Usage:
            !4chan ([board])");
        } else {
            $this->message->reply("that is not a valid board");
        }
    }
    
    function anime(){
        $a = $this->a;
        
        if($a[1] == "help") {
            $this->message->reply("command to look up animes from my animelist
            usage:
            !anime [name]");
        }
        else {
            unset($a[0]);
            $searchString = implode("+", $a);

            $dom = new Dom;  
            $dom->loadFromUrl("http://myanimelist.net/anime.php?q=". $searchString);
            $table = $dom->find("table")[2];
            $result = $table->find("tr")[1]->find("a")[0]->getAttribute("href");
            $this->message->reply($result . "\n" . "For more Results : ". "http://myanimelist.net/anime.php?q=". $searchString);
        }
    }
    
    function manga(){
        $a = $this->a;
        
        if($a[1] == "help") {
            $this->message->reply("command to look up manga from my animelist
            usage:
            !manga [name]");
        }
        else {
            unset($a[0]);
            $searchString = implode("+", $a);

            $dom = new Dom;  
            $dom->loadFromUrl("http://myanimelist.net/manga.php?q=". $searchString);
            $table = $dom->find("table")[2];
            $result = $table->find("tr")[1]->find("a")[0]->getAttribute("href");
            $this->message->reply($result . "\n" . "For more Results : ". "http://myanimelist.net/manga.php?q=". $searchString);
        }
    }
    
}