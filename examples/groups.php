<?php

// Include Emma class
require dirname(__DIR__).'/vendor/autoload.php';

// Create new Emma class object
$client = new MarkRoland\Emma\Client('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$client->debug = true;

// Make API request

// List Groups
$response = $client->list_groups();

// Create Groups
//$groups = array( 'groups' => array( array('group_name' => 'Test 1'), array('group_name' => 'Test 2') ) );
//$groups = array( 'groups' => array( array('group_name' => 'Webhook Test') ) );
//$response = $client->create_groups($groups);

// List Groups
//$response = $client->list_groups('g,t');

// Get Group Detail
//$response = $client->get_group_detail(123456);

// Update Group
//$response = $client->update_group(123456, 'New Name');

// Delete Group
//$response = $client->delete_group(123456);

// List Group members
//$response = $client->list_group_members(123456);

// Add Members to a Group
//$members = array(123456789,234567890);
//$response = $client->add_members_to_group(123456,$members);

// Remove Members from a Group
//$members = array(123456789);
//$response = $client->remove_members_from_group(123456,$members);

// Remove ALL Members from a Group
//$response = $client->remove_all_members_from_group(123456);

// Remove ALL Members from ALL Groups
//$response = $client->remove_all_members_from_all_groups(123456,'a');

// Copy from Group to Group
//$member_status_id = array('a');
//$response = $client->copy_group_to_group(123456,654321,$member_status_id);

// Display response
var_dump($response);

