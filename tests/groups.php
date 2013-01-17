<?php

// Include Emma class
require('emma.class.php');

// Create new Emma class object
$E = new Emma('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$E->debug = true;

// Make API request

// List Groups
$response = $E->list_groups();

// Create Groups
//$groups = array( 'groups' => array( array('group_name' => 'Test 1'), array('group_name' => 'Test 2') ) );
//$groups = array( 'groups' => array( array('group_name' => 'Webhook Test') ) );
//$response = $E->create_groups($groups);

// List Groups
//$response = $E->list_groups('g,t');

// Get Group Detail
//$response = $E->get_group_detail(123456);

// Update Group
//$response = $E->update_group(123456, 'New Name');

// Delete Group
//$response = $E->delete_group(123456);

// List Group members
//$response = $E->list_group_members(123456);

// Add Members to a Group
//$members = array(123456789,234567890);
//$response = $E->add_members_to_group(123456,$members);

// Remove Members from a Group
//$members = array(123456789);
//$response = $E->remove_members_from_group(123456,$members);

// Remove ALL Members from a Group
//$response = $E->remove_all_members_from_group(123456);

// Remove ALL Members from ALL Groups
//$response = $E->remove_all_members_from_all_groups(123456,'a');

// Copy from Group to Group
//$member_status_id = array('a');
//$response = $E->copy_group_to_group(123456,654321,$member_status_id);

// Display response
var_dump($response);

?>
