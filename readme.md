Emma
====

Emma is a PHP class for interaction with the Emma API.

    Copyright (c) 2012 Mark Roland.
    Written by Mark Roland, mark [at] mark roland dot com
    Released under the MIT license.

This PHP class may be distributed and used for free. The author makes
no guarantee for this software and offers no support.

Usage
-----

To get started, initialize the Emma class as follows:

    $emma = new Emma(<account_id>, <public_key>, <private_key>);

For example,

    $emma = new Emma('1234','Drivorj7QueckLeuk','WoghtepheecijnibV');

The "tests" folder in this package contains some test scripts that can
be run to see how emma.class.php may be used.

In order to understand how to use this script, please make sure you
have a good understanding of the Emma API:

http://api.myemma.com/
