<?php

// Include Emma class
require('emma.class.php');

// Create new Emma class object
$E = new Emma('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$E->debug = true;

// Make API request

// Get Response
$response = $E->get_response();

// Get Response Overview
//$response = $E->get_response_overview(567890);

// Get Sends
//$response = $E->get_sends(567890);

// Get In Progress
//$response = $E->get_in_progress(567890);

// Get Deliviers
//$response = $E->get_deliveries(567890);

// Get Opens
//$response = $E->get_opens(567890);

// Get Links
//$response = $E->get_links(567890);

// Get Clicks
//$response = $E->get_clicks(567890);
//$response = $E->get_clicks(567890,123456789);
//$response = $E->get_clicks(567890,123456789,4567890);

// Get Forwards
//$response = $E->get_forwards(567890);

// Get Optouts
//$response = $E->get_optouts(567890);

// Get Signups
//$response = $E->get_signups(567890);

// Get Shares
//$response = $E->get_shares(567890);

// Get Shares
// Didn't work. Must be via Social Publishing (?)
//$response = $E->save_customer_share(567890);

// Get Customer Shares
//$response = $E->get_customer_shares(567890);

// Get Customer Share Clicks
//$response = $E->get_customer_share_clicks(567890);

// Get Share Info
// Untested
//$response = $E->get_customer_share();

// Get Share Overview
//$response = $E->get_share_overview(567890);

// Display response
var_dump($response);

?>
