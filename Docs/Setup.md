# Setup

## 1. Getting the Source

You can get the source code by either cloning this repository 

```bash
git clone https://github.com/runebaas/Geekbot
```

or downloading the latest stable release (see tags)

## 2. Filling out the env.json

First of all, copy the `env.json.example` to `env.json`

Now open `env.json` with your favorite text editor.

#### required options

* `token` - the discord access token, you can get one [here](https://discordapp.com/login?redirect_to=/developers/applications/me)
* `botid` - the user id of the bot, this is used for several internal things, e.g. double execution and infinite loop prevention
* `database` - here you can choose between the built in json based database (recommended) or redis
* `playing` - which game is your bot playing?
* `timezone` - what timezone are you using? please refer to the [php documentation](http://php.net/manual/en/timezones.php) to find your timezone
* `invite` - is the bot allowed to react to !invite? (returns an invite url)

#### optional options

* `clientid` - only if `invite` is true. This is the client id of your bot application
* `mallogin` - used for the myanimelist.net api, only needed if mal module is installed
* `ytkey` - your youtube api key, only needed if the youtube module is installed
* `prefix` - to prevent public server bot collision. if you set this to `gb` you would have to enter `gb!command` to execute a command instead of `!command`

## 3. Dependencies

Make sure that you have php 5.6 or php 7 (recommended) together with [composer](https://getcomposer.org/) installed 

Run `composer install` to install all required dependencies. There may be some problems with this on windows, please refer to the erros given in the console if this occurs.


## 4. Starting the bot

Congratulations, you now have a bare Geekbot install!

to run Geekbot type this into your terminal 

```bash
php bot.php
```

Geekbot has no daemon support yet, you can either run it in `tmux` or `screen` if you want to run it in the background.


## 5. Installing modules

To install a module you only have to drop them into the `./Commands/modules` folder, Geekbot will automatically load it (requires restart of the bot)

#### Example Modules

We've created several example modules for you, you can find them in [this repository](https://github.com/runebaas/Geekbot-Modules). For easy updating we recommend cloning the repository directly into the modules folder.

To do this go into `./Commands/modules` and delete the `modules_be_here` file, then open a terminal inside the folder and run `git clone https://github.com/runebaas/Geekbot-Modules .`
