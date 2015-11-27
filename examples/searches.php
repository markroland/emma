<?php

// Include Emma class
require __DIR__ . '/../src/Emma.php';

// Create new Emma class object
$E = new markroland\Emma('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$E->debug = true;

// Make API request

// List Members
$response = $E->list_searches();

// Get Search Details
//$response = $E->get_search_detail(1234);

// Create Search
//$criteria = array( 'and', array('email','contains','gmail.com') );
//$name = 'Gmail users';
//$response = $E->create_search($criteria, $name);

// Update Search
//$criteria = array( 'or', array('email','contains','gmail.com'), array('email','eq','test1@yahoo.com') );
//$name = 'Gmail users and test';
//$response = $E->update_search(1234,$criteria, $name);

// Get Search Members
//$response = $E->get_search_members(1234);

// Delete Search
//$response = $E->delete_search(1234);

// Display response
var_dump($response);

?>
