<?php

// Include Emma class
require dirname(__DIR__).'/vendor/autoload.php';

// Create new Emma class object
$client = new MarkRoland\Emma\Client('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$client->debug = true;

// Make API request

// List Webhooks
$response = $client->list_webhooks();

// Get Webhook
//$response = $client->get_webhook(1234);

// List Webhooks
//$response = $client->list_webhook_event_types();

// Create Webhook
//$response = $client->create_webhook('message_open','https://yourcallbackurl...','POST');

// Update Webhook
//$response = $client->update_webhook(1234,'message_forward','https://yourcallbackurl...','POST','your-emma-user-id');

// Delete Webhook
//$response = $client->delete_webhook(1234);

// Display response
var_dump($response);

