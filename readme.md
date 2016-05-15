# Geekbot

### Description 

A php bot for Discord.

Originally created to mess around but now open for everyone to use and learn from!

Don't ask why it can do certain things, i don't know either...

### Commands 

* !level - level settings for each user
* !classes - class settings for each user
* !bad - a bad joke counter
* !last - see when the mentioned user last sent something
* !stats - show stats for each user
* !cat - shows a random cat picture
* !ball - let the allknowingly 8ball answer your question
* !pokedex - does what a pokedex does
* !porn - :smirk:
* !fortune - get a fortune or quote
* !chan - get a totally random image from 4chan (be aware of shitposts)
* !anime - looks up an anime from myanimelist
* !manga - looks up a a manga from myanimelist

### How to use

1. git clone https://github.com/runebaas/geekbot 
2. cd geekbot
3. composer install
4. cp env.json.env env.json
5. fill out env.json
6. php bot.php

### Q&A

**LevelDB? False or True?**

If you are on linux and using php 5.6 or older, try using LevelDB, it is A LOT faster than the integrated database.

**I want to use LevelDB, but i don't know how...**

1. compile the [LevelDB Binary](https://github.com/google/leveldb)
2. compile the [php zend extension](https://github.com/reeze/php-leveldb)
3. Enable the zend extension in your php.ini (the cli one, not the apache one)

**The Integrated Database spits out a bunch of warnings for some reason**

It does that when it can't find a certain Value because there is no error handling

Originally it was just meant to replace LevelDB on PHP7

**The bot dies after some time for no reason**

This occurs due to a library bug which should be fixed soon (according to the developer)

### Contributing

Everyone is welcome to contribute!

Make a pull request whenever you want to and i'll review it

##### but where is everything?

| File          | Purpose                                       |
| ------------- | --------------------------------------------- |
| bot.php       | in this file the bot is initialized           |
| Commands.php  | the commands are in here                      |
| DB.php        | the integrated databased, based on json       |
| Reactions.php | reactions on certain words                    |
| Utils.php     | a bunch of small utilities                    |
| fortunes      | huge list of fortunes for the fortunes command |

### Creators

* [Daan Boerlage](https://github.com/runebaas)
* [Alex Fence](https://github.com/AlexFence)

### License

Geekbot is open for everyone under the [GNU Public License v3](http://www.gnu.org/licenses/gpl-3.0.html)