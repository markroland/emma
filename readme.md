Emma
====

Emma is a PHP class for interaction with the [Emma API](http://api.myemma.com).

    Copyright (c) 2012-2015 Mark Roland.
    Written by Mark Roland
    Released under the MIT license.

This PHP class may be distributed and used for free. The author makes
no guarantee for this software and offers no support.

Build status: [![Build Status](https://travis-ci.org/markroland/emma.svg)](https://travis-ci.org/markroland/emma)

Installation
------------

```sh
    composer require markroland/emma ^3.0
```

Usage
-----

To get started, initialize the Emma class as follows:

```php
    use MarkRoland\Emma\Client;

    $emma = new Client(<account_id>, <public_key>, <private_key>);
```

For example,

```php
    use MarkRoland\Emma\Client;

    $emma = new Client('1234','Drivorj7QueckLeuk','WoghtepheecijnibV');
```

The [tests](https://github.com/markroland/emma/blob/master/tests) folder in this package contains some test scripts that can
be run to see how Emma Client class may be used.

Also look in the [examples](https://github.com/markroland/emma/blob/master/examples/) folder for code examples for:
- [fields](https://github.com/markroland/emma/blob/master/examples/fields.php)
- [groups](https://github.com/markroland/emma/blob/master/examples/groups.php)
- [mailing](https://github.com/markroland/emma/blob/master/examples/mailing.php)
- [members](https://github.com/markroland/emma/blob/master/examples/members.php)
- [response](https://github.com/markroland/emma/blob/master/examples/response.php)
- [searches](https://github.com/markroland/emma/blob/master/examples/searches.php)
- [triggers](https://github.com/markroland/emma/blob/master/examples/triggers.php)
- [webhooks](https://github.com/markroland/emma/blob/master/examples/webhooks.php)

In order to understand how to use this script, please make sure you
have a good understanding of the Emma API:

http://api.myemma.com/

Build
=====

## Build using Phing

```sh
    phing
```

```sh
    phing phpdoc
```

```sh
    phing phpcs
```

## PHPUnit 

```sh
    phpunit --bootstrap tests/bootstrap.php tests
```

## Code Coverage

```sh
    phpunit --coverage-html ./report ./tests
```

## PHP Documentation

PHP Documentation is compiled using [phpDocumentor](http://www.phpdoc.org), which is assumed
to be installed globally on the server. It uses phpdoc.dist.xml for runtime configuration.

```sh
    phpdoc
```

## Code Sniff

```sh
    phpcs -n --report-width=100 ./src/Emma.php
```
