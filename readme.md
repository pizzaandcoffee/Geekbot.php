# Geekbot [UNMAINTAINED]

### Description

----------------

A bot framework for discord built on [DiscordPHP](https://github.com/teamreflex/DiscordPHP) for easier bot development

We are Restructuring parts of the code currently to create new features, some code or all of it may not work on certain commits.

### Features and ToDo

##### Features for Developers

- [x] Commands as Modules
- [x] Built-in Database 
- [x] Documentation 
- [x] Message recording and level system
- [x] Api Wrapper for easier and more intuitive development
- [x] Permission Wrapper for easy command permission handling
- [x] easy to read and well documented and easy extensible config file
- [x] automatic help command
- [ ] async based on the promises system
- [ ] parent and child commands
- [ ] RESTful API
- [ ] Installation Script

##### Features for Users (includes base commands)

- [x] Prefix System to avoid collision with other bots
- [x] kick and ban users
- [x] all commands work in direct messages
- [x] level system, look how many images you or someone else has written
- [x] automatic reactions to certain words
- [x] Look up someones overwatch stats
- [x] a pokedex
- [x] search for youtube videos 
- [x] get images from several image boards like konachan and gelbooru
- [x] get a random image from 4chan or from a specified board
- [x] look up anime information from myanime list
- [x] count how many bad jokes someone made
- [x] get random cat images together with an unrelated cat fact
- [x] get a fortune from a database with over 9000 fortunes
- [x] 8ball
- [x] throw a coin or a dice
- [ ] lots of other commands
- [ ] web interface for easy bot management



### How to use

----------------

For a full guide please refer to Docs/Setup.md

##### Starting the bot

1. `git clone https://github.com/runebaas/geekbot` 
2. `cd geekbot`
3. `composer install`
4. `cp .env.example .env`
5. fill out the .env file
6. `php run.php`

##### adding modules

Just drop the modules in the Commands/modules folder and geekbot will automatically load them, no configuration required

The default modules are in another repository, you can get them [here](https://github.com/runebaas/Geekbot-Modules)

### Documentation

----------------

The Documentation can be found in the docs folder | **Outdated \***

The Code itself is also pretty well documentated and there are a lot
of different [example modules here](https://github.com/runebaas/Geekbot-Modules)

\* Even though the docs are outdated because of the current code restructuring, most of it should still give you a general idea of how everything works

### Contributing

----------------

Everyone is welcome to contribute!

Make a pull request whenever you want to and we'll review it

### Credits

----------------

* Daan Boerlage | [Github](https://github.com/runebaas) | [LinkedIn](https://ch.linkedin.com/in/dboerlage) | [Website](https://boerlage.me) 
* Alex Fence | [Github](https://github.com/AlexFence)

### License

----------------

Geekbot is open for everyone under the [GNU Public License v3](http://www.gnu.org/licenses/gpl-3.0.html)
