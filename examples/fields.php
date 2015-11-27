<?php

// Include Emma class
require __DIR__ . '/../src/Emma.php';

// Create new Emma class object
$E = new markroland\Emma('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$E->debug = true;

// Make API request

// List Fields
$response = $E->get_field_list(1);

// Get specific Field
//$response = $E->get_field(12345);

// Create new Field
//$response = $E->create_field('test_field','Test Field','text',3);

// Delete Field
//$response = $E->delete_field(12345);

// Clear Field Data
//$response = $E->clear_member_field_data(12345);

// Update Field
//$field_data = array('test_field' => 'Test Field Update');
//$response = $E->update_field(12345, $field_data);

// Display response
var_dump($response);

?>
