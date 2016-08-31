# The API

-- **OUTDATED DUE TO CODE RESTRUCTURING, WILL BE UPDATED AS SOON AS IT'S FINISHED** -- 

We created Geekbot to make bot development for discord easier. For this we made an API with useful tools for you to use.

When an argument is called `$message` the message object is meant.

All functions with geekbot are static.

## Utils

This class maily exists to make certain things easier in development and doesn't have anything to do with discord

#### startWith

Check if a string starts with a given string

```php
Utils::startsWith($haystack, $needle);
```

#### xml_attribute

```php
Utils::xml_attribute($object, $attribute);
```

#### messageSplit - important

This is a very important function for argument handling in commands. It parts the message into a lowercase array.


```php
Utils::messageSplit($mesage);
```

#### playSound

Plays a sound in a given channel.

```php
Utils::playSound($sound, $channel);
```

#### isHelp

checks if the given argument is help. It looks at the second word. We used this to make some lines a bit shorter.

```php
Utils::isHelp($messageArray);
```

## Files

we created a small file storage api so the filesystem doesn't become one big mess.

These functions are inside the Utils class

#### getFile

Get a file from the Storage

```php
Utils::getFile($fileName);
```

#### storeFile

Store a file or raw text inside a file

```php
Utils::storeFile($fileName, $contents);
```

## Statistics

When we developed geekbot we thought it might be a fun idea to track how many messages a user has sent. These values are stored inside the database and can be called by using this api.

#### getLastMessage

Check when a user sent their last message. This value is stored with the `DATE_RFC2822` format. 

```php
Stats::getLastMessage($userID);
```

#### getAmountOfMessages

Get the amount of messages a user has sent on the current server.

```php
Stats::getAmountOfMessages($userID);
```

#### getClass

We thought i might be a fun idea to give users a class, so we created a function for it :D

This only returns `null` when the classes module isn't installed.

```php
Stats::getClass($userID);
```

#### getBadJokes

Another #justforfun function, a bad joke counter! This function gets the amount of bad jokes an user has made.

This only returns `null` when the badjokes module isn't installed.

```php
Stats::getBadJokes($userID);
```

#### getLevel

This calculates the users level according to the amount of messages sent.

The following calculation is used for this

`floor(floor($i + 300 * pow(2, $i / 7.0)) / 16)`

```php
Stats::getLevel($userID);
```

#### getGuildMessages

Geekbot also records how many messages are sent within a guild

```php
Stats::getGuildMessages();
```

## Permissions

We built a simple permission system with permission levels to give custom permissions per role. Same goes for the default permission system of discord.

#### isAdmin

This checks if the given user is an Admin, it does this in 3 different ways:

* The user has a role with the name Admin or Administrator
* The user has a role with the administration permission
* The user has a permission level within geekbot of 1

This returns a bool

```php
Permission::isAdmin($message)
```

#### getUserLevel

Returns the userlevel of a certain user within geekbot, this ranges from 1 to 9 where 1 is the highest possible


```php
Permission::getUserLevel()
```

#### setUserLevel

Set someone’s userlevel in geekbot. The levels range from 1 to 9 where 1 is the highest possible

```php
Permission::setUserLevel($level)
```

#### hasRole

Check if someone has a certain role

```php
Permission::hasRole($message, $roleName);
```

## Database

See the separate database documentation

## Settings

A class to manage Settings

#### Guild Settings

Get or set guild settings

```php
Settings::getGuildSetting($key);
Settings::setGuildSetting($key, $value);
```

#### User Settings

Get or set user settings

```php
Settings::getUserSetting($key);
Settings::setUserSetting($key, $value);
```

#### getEnv

Read settings from the env file. These settings can only be modified by hand to prevent certain issues.

```php
Settings::getEnv($key);
```

## Worth-to-know things

#### globals

We've stored the userid and guildid of the current message inside the `$GLOBALS` array to make the framework a bit more efficient. You could do this with the `$message` object ofcourse, but that goes through A LOT more steps.

* `$GLOBALS['userid']` - returns the userid
* `$GLOBALS['guildid']` - returns the guildid
* `$GLOBALS['dblocation']` - returns the database key which is used to store user data
