# Database

With geekbot we needed something that was fast and easy to use, so we decided to use [Redis](http://redis.io/), an extremely fast key-storage database.

But due to us being too lazy to actually setup redis on every machine and make it work together with php we created a Redis compatible wrapper based in JSON.

This means you have the freedom to choose which database you want to use. For Development we recommend using the json database and for production redis.

## How to use

We never actually call directly upon Redis or the jsondb, we created a class with static methods that handles everything.

#### Write to the database

```php
Database::set($location, "value", $value);
//example
Database::set($GLOBALS['dblocation'], "messagecount", "4");
```

#### Get data from the database

```php
Database::get($location, "value");
//example
Database::get($GLOBALS['dblocation'], "value");
```

#### Delete a key

```php
Database::delete("key");
//example
Database::delete($GLOBALS['dblocation']);
```
