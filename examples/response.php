<?php

// Include Emma class
require dirname(__DIR__).'/vendor/autoload.php';

// Create new Emma class object
$client = new MarkRoland\Emma\Client('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$client->debug = true;

// Make API request

// Get Response
$response = $client->get_response();

// Get Response Overview
//$response = $client->get_response_overview(567890);

// Get Sends
//$response = $client->get_sends(567890);

// Get In Progress
//$response = $client->get_in_progress(567890);

// Get Deliviers
//$response = $client->get_deliveries(567890);

// Get Opens
//$response = $client->get_opens(567890);

// Get Links
//$response = $client->get_links(567890);

// Get Clicks
//$response = $client->get_clicks(567890);
//$response = $client->get_clicks(567890,123456789);
//$response = $client->get_clicks(567890,123456789,4567890);

// Get Forwards
//$response = $client->get_forwards(567890);

// Get Optouts
//$response = $client->get_optouts(567890);

// Get Signups
//$response = $client->get_signups(567890);

// Get Shares
//$response = $client->get_shares(567890);

// Get Shares
// Didn't work. Must be via Social Publishing (?)
//$response = $client->save_customer_share(567890);

// Get Customer Shares
//$response = $client->get_customer_shares(567890);

// Get Customer Share Clicks
//$response = $client->get_customer_share_clicks(567890);

// Get Share Info
// Untested
//$response = $client->get_customer_share();

// Get Share Overview
//$response = $client->get_share_overview(567890);

// Display response
var_dump($response);

