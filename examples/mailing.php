<?php

// Include Emma class
require dirname(__DIR__).'/vendor/autoload.php';

// Create new Emma class object
$client = new MarkRoland\Emma\Client('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$client->debug = true;

// Make API request

// Get mailing List
$response = $client->get_mailing_list('true','m,t','p,a,s,x,c,f','');

// Get mailing detail
//$response = $client->get_mailing_detail(123456);

// Get mailing members
// ??? didn't work
//$response = $client->get_mailing_members(123456);

// Get message as sent to member
//$response = $client->get_mailing_message(123456,123456789);

// Get mailing members
//$response = $client->get_mailing_groups(123456);

// Update Mailing Status
//$response = $client->update_mailing_status(123456);

// Cancel mailing
// $response = $client->cancel_mailing(123456);

// Forward Message
//$response = $client->forward_message(123456,123456789,array('recipient@gmail.com'), 'Test Note');

// Append Mailing
//$response = $client->append_to_mailing(123456,'', array('recipient@gmail.com'), array('my-alert@email.com'));

// Get Heads up emails
//$response = $client->get_heads_up_emails(123456);

// Display response
var_dump($response);

