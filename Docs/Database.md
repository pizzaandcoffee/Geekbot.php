# Database

With geekbot we needed something that was fast and easy to use, so we decided to use [Redis](http://redis.io/), an extremely fast key-storage database.

But due to us being too lazy to actually setup redis on every machine and make it work together with php we created a Redis compatible wrapper based on JSON.

This means you have the freedom to choose which database you want to use. For development we recommend using the json database and for production redis.

## How to use

We never actually call directly upon Redis or the jsondb, we created a class with static methods that handles everything.

The database differs between 4 storage locations

* user - the config file of an user, this is discord wide and used in all guilds
* member - the config file of an user, this is guild only
* guild - the config file for a guild
* global - the config file for geekb1ot itself (.env is preferred towards using this method)

These locations always look at the message author and therefor always allpies to them.

And Exception to this rule is the member location which can be specified with a userid

#### Write to the database

`$data` should be the modified array received from the database previously 

```php
Database::set("location", $data, [$userid]);
//example
Database::set("user", $data);
//adtionally, if you need to store something for a member with a certain user id 
Database::set("member", $data, $userid); 
```

#### Get data from the database

returns an array with all userdata

```php
Database::get("location");
//example
Database::get("guild");
//adtionally, if you need to store something for a member with a certain user id 
Database::set("member", $userid); 
```

#### Delete a key

```php
Database::get("location");
//example
Database::get("guild");
//adtionally, if you need to store something for a member with a certain user id 
Database::set("member", $userid); 
```
