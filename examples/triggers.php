<?php

// Include Emma class
require __DIR__ . '/../src/Emma.php';

// Create new Emma class object
$E = new markroland\Emma('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$E->debug = true;

// Make API request

// List Triggers
//$response = $E->list_triggers();

// Create Trigger
//$response = $E->create_trigger('test trigger','s',13579);

// Get Trigger
//$response = $E->get_trigger(13579);

// Update Trigger
//$response = $E->update_trigger(13579);

// Delete Trigger
//$response = $E->delete_trigger(13579);

// Get mailings sent by a trigger.
//$response = $E->get_trigger_mailings(13579);

// Display response
var_dump($response);

?>
