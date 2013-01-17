<?php

// Include Emma class
require('emma.class.php');

// Create new Emma class object
$E = new Emma('1234567', 'Drivorj7QueckLeuk', 'WoghtepheecijnibV');

// Control Debugging output
$E->debug = true;

// Make API request

// List Members
$response = $E->list_members();

// Count Members
//$response = $E->list_members(NULL,TRUE);

// Get Member Info
//$response = $E->get_member_detail(123456789);

// Get Member Info using Email
//$response = $E->get_member_detail_by_email('recipient@gmail.com');

// Get optout detail
//$response = $E->get_member_optout_detail(123456789);

// Import Members
//$members = array( array('email'=>'test1@gmail.com'),
//                  array('email'=>'test2@yahoo.com') );
//$groups = array(123456,234567);
//$response = $E->import_member_list($members, 'test-import-2', 1, $groups);

// Add/Update Member
//$fields = array('first_name'=>'Foo', 'last_name'=>'Bar');
//$groups = '123456';
//$response = $E->import_single_member('test1@gmail.com', $fields, $groups, $signup_form);

// Delete Members
//$member_ids = array(123456789,234567890);
//$response = $E->delete_members($member_ids);

// Update Member Status
//$member_ids = array(123456789);
//$response = $E->update_members_status($member_ids, 'e');

// Update Member
//$fields = array('first_name'=>'Foo', 'last_name'=>'Bar Update');
//$response = $E->update_member(123456789, 'test1@gmail.com', 'a', $fields);

// Delete Member
//$response = $E->delete_member(123456789);

// List Member's Groups
//$response = $E->list_member_groups(123456789);

// Add Member to Group(s)
//$response = $E->add_member_to_groups(123456789, array(123456) );

// Remove Member from Group(s)
//$response = $E->remove_member_from_groups(123456789, array(123456) );

// Delete all members
//$response = $E->remove_all_members('e');

// Remove Member from all Groups
//$response = $E->remove_member_from_all_groups(123456789);

// Remove Members from all Groups
//$response = $E->remove_members_from_groups(array(123456789,234567890), array(123456,654321));

// Get mailing history
//$response = $E->get_member_mailing_history(123456789);

// Get Import stats regarding members
//$response = $E->get_import_stats_members(567890);

// Get Import stats
//$response = $E->get_import_stats(567890);

// Get Import stats for all imports
//$response = $E->get_all_import_stats();

// Get Import stats for all imports
//$response = $E->copy_to_group(123456, array('a','e') );

// Update the status for a group of members, based on their current status
//$response = $E->bulk_change_member_status('e','a');

// Display response
var_dump($response);

?>
