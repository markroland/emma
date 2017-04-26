<?php

// Include Emma class
require dirname(__DIR__).'/vendor/autoload.php';

// Create new Emma class object
$client = new MarkRoland\Emma\Client('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$client->debug = true;

// Make API request

// List Fields
$response = $client->get_field_list(1);

// Get specific Field
//$response = $client->get_field(12345);

// Create new Field
//$response = $client->create_field('test_field','Test Field','text',3);

// Delete Field
//$response = $client->delete_field(12345);

// Clear Field Data
//$response = $client->clear_member_field_data(12345);

// Update Field
//$field_data = array('test_field' => 'Test Field Update');
//$response = $client->update_field(12345, $field_data);

// Display response
var_dump($response);

