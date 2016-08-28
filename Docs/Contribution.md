# Contribution Guidelines

Do you want to contribute to geekbots development? Awesome! 

Please read this guide to understand our project a little better and have a good chance that your changes are accepted into the master branch.

## Bugs and Ideas

First and most important of all, you don't have to be able to program to contribute! You can also test geekbot and report bugs. Please be as precise as possible about your bugs and if possible include a stacktrace.

Ideas are also welcome!

Please prefix all your issues on github with a tag like [idea] or [bug]

examples:

* [idea] add somethings to the core code
* [bug] crash when this or that happens

## Coding Style

We :heart: object orientated code

Therefor we ask you to try writing only in oop.

We try to keep the [PSR-1](http://www.php-fig.org/psr/psr-1/) and [PSR-2](http://www.php-fig.org/psr/psr-2/) guidelines

## Project Structure

#### File - bot.php

This is where it all start, the class which loads geekbot and all of its components.

We do not recommend changing anything here.

#### Folder - System

In this Folder are all the system classes, everything that geekbot needs to run and operate. Most of the development is done here.

Most of the classes are static. Please do follow this practice unless you really have to do otherwise.

#### Folder - Commands

This is where are all the magic happens, the commands folder.

This folder is devided into 2 different parts

* core
* modules

The core folder is for commands that that are really necessary or are super basic things, for example the help command.

The modules folder is everything for else, commands which are not required to run geekbot.

There is also a file inside the commands folder (` commandInterface.php`), this is where the interfaces for the commands are defined.

#### Folder - Storage

This is the folder for the storage API, files are stored  here. By default there are 2 files in there:

* reactions.json - used for predefined answers
* fortune - used for the fortune module

There is nothing to do in here.

