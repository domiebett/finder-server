[![CircleCI](https://circleci.com/gh/DomieBett/finder-server.svg?style=svg)](https://circleci.com/gh/DomieBett/finder-server)


# Finder Server
Repository for finder server, a Lumen RestFUL API designed to help communities help each
other in finding lost items and retrieving them back to the user.
We believe in the strength in unity and thus we believe communities could
benefit a great deal from helping each other.

### Prerequisites.

You should have `php` installed, prefferable php 7.0 and above. You also need
`composer package manager`.

You also need the `postgres database` installed.

### Initial Set up

You need to set up the database to be used. You can use your postgres terminal
command psql, PgAdmin, or Postico for the following procedure. I am however going
to show how to set up a database on the terminal.

First, enter Postgres interactive mode:

> Run $ `psql -u postgres postgres`

This will start you up on the default postgres database and user. You should create
the database the api will use via the command:

> postgres~# `CREATE DATABASE finder_server`

### Setting up the api server.

First, you need to install Lumen via the composer package manager.

> Run $ `composer global require "laravel/lumen-installer"`

Then, you need to clone the api repository to your local machine.

> Run $ `git clone https://github.com/DomieBett/finder-server.git`

Cd into the repository directory:

> Run $ `cd finder-server`

Run the migrations:

> Run $ `php artisan migrate`

You can optionally choose to seed the database with sample data for testing:

> Run $ `php artisan db:seed`

Once you have the repository locally, you need to install packages:

> Run $ `composer install`

That will install packages contained in the composer.json file. You are now fully
set up. You just need to start the server.

> Run $ `php -S localhost:8888 -t public/`

You can access your server and test it with post man at the url 
[http://127.0.0.1:8888/](http://127.0.0.1:8888/)

To test the api:

> Run $ `composer test`

### Api Endpoints

This api implements the following endpoints:

> `api/v1/auth/login`

This is accessed using post method to login a user

> `api/v1/auth/register`

This is accessed using post method to register a user

> `api/v1/items`

This can be accessed using http post and get methods.

> `api/v1/category`

This can be accessed using http get method.

> NB > This is a restful API thus it follows the RestFUL conventions that methods
determine functionality to be called.

### Author

Dominic Bett