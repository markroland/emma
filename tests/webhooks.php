<?php

// Include Emma class
require('emma.class.php');

// Create new Emma class object
$E = new Emma('1234567');

// Control Debugging output
$E->debug = true;

// Make API request

// List Webhooks
$response = $E->list_webhooks();

// Get Webhook
//$response = $E->get_webhook(1234);

// List Webhooks
//$response = $E->list_webhook_event_types();

// Create Webhook
//$response = $E->create_webhook('message_open','https://yourcallbackurl...','POST');

// Update Webhook
//$response = $E->update_webhook(1234,'message_forward','https://yourcallbackurl...','POST','your-emma-user-id');

// Delete Webhook
//$response = $E->delete_webhook(1234);

// Display response
var_dump($response);

?>