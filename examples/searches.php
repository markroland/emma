<?php

// Include Emma class
require dirname(__DIR__).'/vendor/autoload.php';

// Create new Emma class object
$client = new MarkRoland\Emma\Client('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$client->debug = true;

// Make API request

// List Members
$response = $client->list_searches();

// Get Search Details
//$response = $client->get_search_detail(1234);

// Create Search
//$criteria = array( 'and', array('email','contains','gmail.com') );
//$name = 'Gmail users';
//$response = $client->create_search($criteria, $name);

// Update Search
//$criteria = array( 'or', array('email','contains','gmail.com'), array('email','eq','test1@yahoo.com') );
//$name = 'Gmail users and test';
//$response = $client->update_search(1234,$criteria, $name);

// Get Search Members
//$response = $client->get_search_members(1234);

// Delete Search
//$response = $client->delete_search(1234);

// Display response
var_dump($response);

