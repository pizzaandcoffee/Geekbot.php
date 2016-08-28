# Modules
Geekbot is a modular Discord bot, this document will guide you with writing a module for it.

## What is a module
First let's have a look at what a module is:
* A module contains at least one command
* A module can contain more than one command if:
 * The commands all use the same service
 * The commands are almost the same and use similar services

## How-To write a module
Basically a module is a php file with some classes in it, this part will teach you how to write theses classes. If want to have a look at some example modules, you can do that [here](https://github.com/runebaas/Geekbot-Modules). Our standard modules mostly consist just of one class.

### Interfaces
Your command classes have to implement at least one of these interface, in order to be recognized  by the bot:
* BasicCommand
* MessageCommand

All command interfaces extend the interface called command.
```php
interface command {

    public static function getName();

    public function getDescription();

    public function getHelp();

}
```
All of these methods must return a string.

* getName() determines the keyword that has to be used for calling the command
* getDescription() is used by the !help command for listing all available commands, it should return a short one line description
* getHelp() has to return how the command is used, can be multi line

#### Command interfaces in detail

##### BasicCommand
The BasicCommand Interface is meant for Commands that don't need any parameters. Also It must return a string.
```php
interface basicCommand extends command {

    public function runCommand();

}
```

##### MessageCommand
If your Command needs parameters use this interface. A class that implements this interface receives the message object from the Discord API. The Command should return a message object, but it can also just return a string.
```php
interface messageCommand extends command {

    public function runCommand($message);

}
```

### Example Comands

#### BasicCommand
The Info Command is a core command and one of the best examples for a BasicComand. It returns the version of running bot and how long the bot is running. There aren't any parameters needed for that, but we still have to write a getHelp Method because it can be triggered with the !help command which is also a core command.

```php
class Info implements basicCommand {
    private $startDate;

    function __construct() {
        $this->startDate = new DateTime('now');

    }

    public static function getName() {
        return "!info";
    }

    public function getDescription() {
        return "returns some info about the bot";
    }

    public function getHelp() {
        $this->getDescription();
    }

    public function runCommand() {
        return "Running " . $this->getVersion() . " since: " .
        $this->timeToString($this->getEnlapsedTime());
    }

    private function getEnlapsedTime() {
        $startdate = $this->startDate;
        $now = new DateTime('now');
        $interval = $now->diff($startdate);
        return $interval;
    }

    private function timeToString($interval) {
        return $interval->format('%m months %d days %h hours %i minutes %S seconds');
    }

    private function getVersion() {
        return GEEKBOT_VERSION;
    }
}
```

#### MessageCommand
The cat Command is a pretty useless module, but one of our most favorite. The Code below is the original version of it, because it's shorter and still great example.

``` php
class Cat implements messageCommand{
    public static function getName() {
        return "!cat";
    }

    public function getDescription() {
        return "returns a random image of a cat";
    }

    public function getHelp() {
        return $this->getDescription() . "
            usage:
            !cat";
    }

    public function runCommand($message) {
        if (explode(' ', $message->content)[1] == "help") {
            return $this->getHelp();
        } else {
            $catsource = file_get_contents('http://random.cat/meow');
            $catcontent = json_decode($catsource);
            $message->reply($catcontent->file);
            return $message;
        }
    }
}
```
