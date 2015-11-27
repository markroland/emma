<?php

// Include Emma class
require __DIR__ . '/../src/Emma.php';

// Create new Emma class object
$E = new markroland\Emma('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$E->debug = true;

// Make API request

// Get mailing List
$response = $E->get_mailing_list('true','m,t','p,a,s,x,c,f','');

// Get mailing detail
//$response = $E->get_mailing_detail(123456);

// Get mailing members
// ??? didn't work
//$response = $E->get_mailing_members(123456);

// Get message as sent to member
//$response = $E->get_mailing_message(123456,123456789);

// Get mailing members
//$response = $E->get_mailing_groups(123456);

// Update Mailing Status
//$response = $E->update_mailing_status(123456);

// Cancel mailing
// $response = $E->cancel_mailing(123456);

// Forward Message
//$response = $E->forward_message(123456,123456789,array('recipient@gmail.com'), 'Test Note');

// Append Mailing
//$response = $E->append_to_mailing(123456,'', array('recipient@gmail.com'), array('my-alert@email.com'));

// Get Heads up emails
//$response = $E->get_heads_up_emails(123456);

// Display response
var_dump($response);

?>
