# Setup

## 1. Getting the Source

You can get the source code by cloning this repository 

```bash
git clone https://github.com/runebaas/Geekbot
```


## 2. Filling out the .env file

First of all, copy the `.env.example` to `.env`

Now open `.env` with your favorite text editor.

Here you'll see a lot of option, we'll go through them one by one

#### Required 

These are absolutely required to run geekbot

Option | Description | Default 
--- | --- | ---
`sys.token` | the discord access token, you can get one [here](https://discordapp.com/login?redirect_to=/developers/applications/me) | (empty) 
`sys.botid` | the user id of the bot, this is used for several internal things, e.g. double execution and infinite loop prevention | (empty) 
`sys.ownerid` | the user id of the bot owner (you). overrides several permission settings | (empty) 

#### System

These are also required but can be left as default

Option | Description | Default 
--- | --- | ---
`sys.playing` | Value is set to the game the bot is playing in discord | Ping Pong
`sys.timezone` | The Timezone used for various tasks, must be [unix compatible](http://php.net/manual/en/timezones.php) | Europe/London
`sys.invite` | Specify with true or false if the bot can return an invite link | false
`sys.clientid` | If the clientid of your bot, used to create an invite link | (empty)
`sys.prefix` | a prefix to execute bot commands to avoid collision with other bots, commented out by default | gb

#### Logging

Chatloging, defaults can be kept

Option | Description | Default 
--- | --- | ---
`log.toFile` | Specify with true or false if you want to log the chatlog to a file | false
`log.location` | Specify the location of the logfile. starts in /System/ | /../Storage/ChatLog.txt

#### Database

Database configuration, defaults can be kept

There are two databases supported by geekbot, one is redis (for production) and one is a json wrapper for redis (for development)

Option | Description | Default 
--- | --- | ---
`sys.database` | choose your database, options are `redis` and `json` | json
`json.path` | Where do you want the database to be? starts in /System/ | /../Storage/db
`redis.host` | host address of your redis server | localhost
`redis.port` | port on which your redis server is running | 6379

#### API

Configuration for the RESTful geekbot api. This feature is still in the works

Option | Description | Default 
--- | --- | ---
`api.enable` | do you want to enable the api? | false
`api.port` | on which port should the api server run? | 666
`api.auth` | Authentication key for the api | (empty)

#### Module Settings

Settings for individual modules

Option | Description | Default | Module
--- | --- | --- | ---
`mal.login` | login for myanimelist, username and password seperated by a `:` | user:pass | myanimelist
`youtube.key` | your youtube api key | (empty) | youtube 

## 3. Dependencies

Make sure that you have php 5.6 or php 7 (recommended) together with [composer](https://getcomposer.org/) installed 

Run `composer install` to install all required dependencies. There may be some problems with this on windows, please refer to the erros given in the console if this occurs.


## 4. Starting the bot

Congratulations, you now have a bare Geekbot install!

to run Geekbot type this into your terminal 

```bash
php run.php
```

Geekbot has no daemon support yet, you can either run it in `tmux` or `screen` if you want to run it in the background.


## 5. Installing modules

To install a module you only have to drop them into the `./Commands/modules` folder, Geekbot will automatically load it (requires restart of the bot)

#### Example Modules

We've created several example modules for you, you can find them in [this repository](https://github.com/runebaas/Geekbot-Modules). For easy updating we recommend cloning the repository directly into the modules folder.

To do this go into `./Commands/modules` and delete the `modules_be_here` file, then open a terminal inside the folder and run `git clone https://github.com/runebaas/Geekbot-Modules .`
