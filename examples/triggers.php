<?php

// Include Emma class
require dirname(__DIR__).'/vendor/autoload.php';

// Create new Emma class object
$client = new MarkRoland\Emma\Client('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$client->debug = true;

// Make API request

// List Triggers
//$response = $client->list_triggers();

// Create Trigger
//$response = $client->create_trigger('test trigger','s',13579);

// Get Trigger
//$response = $client->get_trigger(13579);

// Update Trigger
//$response = $client->update_trigger(13579);

// Delete Trigger
//$response = $client->delete_trigger(13579);

// Get mailings sent by a trigger.
//$response = $client->get_trigger_mailings(13579);

// Display response
var_dump($response);

