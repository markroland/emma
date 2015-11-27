<?php

namespace markroland;

/**
 * Emma Class
 *
 * @package   Emma
 * @author    Mark Roland <mark [at] mark roland dot com>
 * @copyright Mark Roland, 2012
 * @version   2.0.0
 *
 * Documentation: http://api.myemma.com/
 **/
class Emma
{

    /**
     * Emma API Dommain
     * @var string
     */
    private $emma_api_domain = 'api.e2ma.net';

    /**
     * Emma Account Public Key
     * @var string
     */
    private $emma_public_key = '';

    /**
     * Emma Account Private Key
     * @var string
     */
    private $emma_private_key = '';

    /**
     * Emma Account ID
     * @var string
     */
    private $emma_account_id = '';

    /**
     * Contains the last response from Emma. It contains her response code followed
     * by a colon and a textual description
     * @var string
     */
    public $last_emma_response = '';

    /**
     * Contains the headers from the last response from Emma.
     * @var string
     */
    public $last_emma_response_headers = '';

    /**
     * Contains the last response information from Emma. It contains an array
     * of Contains the last response from Emma. It contains various information
     * about the request and response, including the HTTP code.
     * @var array
     */
    public $last_emma_response_info = array();

    /**
     * Set starting record. Used for paginated results
     * @var integer
     */
    public $start = -1;

    /**
     * Set ending record. Used for paginated results
     * @var integer
     */
    public $end = -1;

    /**
     * Set the number of records to return
     * @var integer
     */
    public $count = false;

    /**
     * A debugging variable. Set to true to see debugging output
     * @var boolean
     */
    public $debug = false;

    /**
     * Class constructor
     *
     * @param  string|array $account_id  The Emma Account ID on which to perform actions, or an array of params
     * @param  string       $public_key  The Emma public key for the account
     * @param  string       $private_key The Emma private key for the account
     * @return boolean false if $account_id is not provided
     **/
    function __construct($account_id, $public_key=null, $private_key=null)
    {

        if (is_array($account_id) ) {
            $params = $account_id;
            $account_id = isset($params['account_id']) ? $params['account_id'] : null;
            $public_key = isset($params['public_key']) ? $params['public_key'] : null;
            $private_key = isset($params['private_key']) ? $params['private_key'] : null;
        }

        if (empty($account_id) || empty($public_key) || empty($private_key) ) {
            throw new \Exception('Emma Error: no account id, public key, or private key sent to constructor.');
        }

        // Save account ID to class object variable
        $this->emma_account_id = $account_id;
        $this->emma_public_key = $public_key;
        $this->emma_private_key = $private_key;

    }

    /**
     * Make a request to the Emma API
     *
     * @param  string $api_method  The API method to be called
     * @param  string $http_method The HTTP method to be used (GET, POST, PUT, DELETE, etc.)
     * @param  array  $data        Any data to be sent to the API
     * @return string|array API request results
     **/
    function make_request($api_method, $http_method = null, $data = null)
    {

        // Set query string
        $get_query_string = '';
        if ($this->count ) {
            $get_query_string = '?count=true';
        }elseif ($this->start >= 0 && $this->end >= 0 ) {
            $get_query_string = sprintf('?start=%d&end=%d', $this->start, $this->end);
        }
        if (($http_method == 'GET' || $http_method == 'DELETE') && !empty($data) ) {
            $get_query_string = '?';
            $get_query_string .= http_build_query($data);
        }

        // Set request
        $request_url = 'https://'.$this->emma_api_domain.'/'.$this->emma_account_id.'/'.$api_method.$get_query_string;

        // Debugging output
        if ($this->debug) {
            echo 'HTTP Method: '.$http_method."\n";
            echo 'Request URL: '.$request_url."\n";
        }

        // Create a cURL handle
        $ch = curl_init();

        // Set the request
        curl_setopt($ch, CURLOPT_URL, $request_url);

        // Use HTTP Basic Authentication
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        // Set the public_key and private_key
        curl_setopt($ch, CURLOPT_USERPWD, $this->emma_public_key.':'.$this->emma_private_key);

        // Save the response to a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Send data as PUT request
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);

        // This may be necessary, depending on your server's configuration
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Send data
        if (!empty($data) ) {
            $postdata = json_encode($data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($postdata)));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);

            // Debugging output
            if ($this->debug) {
                echo 'JSON Post Data: '.$postdata."\n";
            }
        }

        // Execute cURL request
        curl_setopt($ch, CURLOPT_HEADER, true);
        $curl_response = curl_exec($ch);
        $curl_info = curl_getinfo($ch);

        // Debugging
        if ($this->debug) {
            //$status = curl_getinfo($ch);
            //var_dump($status);
            //var_dump($curl_response);
        }

        // Close cURL handle
        curl_close($ch);

        // Parse response
        list($curl_response_headers, $curl_response) = preg_split("/\R\R/", $curl_response, 2);
        if ($this->count ) {
            $parsed_result = $curl_response;
        }
        else {
            $parsed_result = $this->parse_response($curl_response);
        }

        // Save response to class variable for use in debugging
        $this->last_emma_response = $curl_response;
        $this->last_emma_response_headers = $curl_response_headers;
        $this->last_emma_response_info = $curl_info;

        // Return parsed response
        return $parsed_result;
    }

    /**
     * Parse Response
     *
     * @param  string|array $response A json-formatted API response
     * @return string|array API request results
     **/
    function parse_response($response)
    {
        $data = json_decode($response);
        return $data;
    }

    /* *** BEGIN `FIELDS` METHODS *** */

    /**
     * List Fields
     *
     * @param  boolean $deleted Set to TRUE or 1 to include deleted fields in results
     * @return string|array API request results
     **/
    function get_field_list($deleted = '')
    {

        if ($deleted) {
            $send_data['deleted'] = 1;
        }
        else {
            $send_data['deleted'] = 0;
        }

        $data = $this->make_request('fields', 'GET', $send_data);
        return $data;
    }

    /**
     * Gets the detailed information about a particular field
     *
     * @param  integer $field_id A unique Field ID
     * @param  boolean $deleted  Set to TRUE or 1 to include deleted fields in results
     * @return string|array API request results
     **/
    function get_field($field_id, $deleted = '')
    {

        if ($deleted) {
            $send_data['deleted'] = 1;
        }
        else {
            $send_data['deleted'] = 0;
        }

        $data = $this->make_request('fields/'.$field_id, 'GET', $send_data);
        return $data;
    }

    /**
     * Create a new field
     *
     * @param  string  $shortcut_name The internal name for this field
     * @param  string  $display_name  Display name, used for forms and reports
     * @param  string  $field_type    The type of value this field will contain. Accepts one of text, text[], numeric, boolean, date, timestamp. text[], numeric, boolean, date, timestamp.
     * text[], numeric, boolean, date, timestamp.
     * @param  integer $column_order  Order of this column in lists.
     * @return string|array API request results
     **/
    function create_field($shortcut_name, $display_name, $field_type, $column_order)
    {

        // Combine input data to an array
        $send_data['shortcut_name'] = $shortcut_name;
        $send_data['display_name'] = $display_name;
        $send_data['field_type'] = $field_type;
        $send_data['column_order'] = $column_order;

        // Make API request
        $data = $this->make_request('fields', 'POST', $send_data);

        // Return API request results
        return $data;
    }

    /**
     * Delete a field
     *
     * @param  integer $field_id A unique Field ID
     * @return string|array API request results
     **/
    function delete_field($field_id)
    {

        // Make API request
        $data = $this->make_request('fields/'.$field_id, 'DELETE');

        // Return API request results
        return $data;
    }

    /**
     * Clear the member data for the specified field
     *
     * @param  integer $field_id A unique Field ID
     * @return string|array API request results
     **/
    function clear_member_field_data($field_id)
    {

        // Make API request
        $data = $this->make_request('fields/'.$field_id.'/clear', 'POST');

        // Return API request results
        return $data;
    }

    /**
     * Updates an existing field
     *
     * @param  integer $field_id  A unique Field ID
     * @param  array   $send_data Field data to be updated
     * @return string|array API request results
     **/
    function update_field($field_id, $send_data)
    {

        // Make API request
        $data = $this->make_request('fields/'.$field_id, 'PUT', $send_data);

        // Return API request results
        return $data;
    }

    /* *** END `FIELDS` METHODS *** */

    /* *** BEGIN `GROUPS` METHODS *** */

    /**
     * Get a basic listing of all active member groups for a single account.
     *
     * @param  string $group_types Accepts a comma-separated string with one or more of the
     * following group_types: ‘g’ (group), ‘t’ (test), ‘h’ (hidden), ‘all’ (all). Defaults to ‘g’
     * @return string|array API request results
     **/
    function list_groups($group_types = 'g')
    {
        $send_data['group_types'] = $group_types;
        $data = $this->make_request('groups', 'GET', $send_data);
        return $data;
    }

    /**
     * Add Group
     *
     * @param  array $groups An array of group information
     * @return string|array API request results
     **/
    function create_groups($groups)
    {
        $send_data = $groups;
        $data = $this->make_request('groups', 'POST', $send_data);
        return $data;
    }

    /**
     * Get the detailed information for a single member group.
     *
     * @param  integer $group_id required, identifier that you would like to use to reference the group
     * @return string|array API request results
     **/
    function get_group_detail($group_id)
    {
        $data = $this->make_request('groups/'.$group_id, 'GET');
        return $data;
    }

    /**
     * Update information for a single member group
     *
     * @param  integer $group_id   required, identifier that you would like to use to reference the group
     * @param  string  $group_name required, the name of the new group, can not exceed 255 characcters
     * @return string|array API request results
     **/
    function update_group($group_id, $group_name)
    {
        $send_data['group_name'] = $group_name;
        $data = $this->make_request('groups/'.$group_id, 'PUT', $send_data);
        return $data;
    }

    /**
     * Delete a single member group.
     *
     * @param  integer $group_id required, identifier that you would like to use to reference the group
     * @return string|array API request results
     **/
    function delete_group($group_id)
    {
        $data = $this->make_request('groups/'.$group_id, 'DELETE');
        return $data;
    }

    /**
     * Get the members in a single active member group.
     *
     * @param  integer $group_id     required, identifier that you would like to use to reference the group
     * @param  boolean $show_deleted Set to 1 so see deleted group members, 0 to ignore deleted members
     * @return string|array API request results
     **/
    function list_group_members($group_id, $show_deleted = null)
    {

        if ($show_deleted) {
            $send_data['deleted'] = 1;
        } else {
            $send_data['deleted'] = 0;
        }

        $data = $this->make_request('groups/'.$group_id.'/members', 'GET', $send_data['deleted']);
        return $data;
    }

    /**
     * Add a list of members to a single active member group.
     *
     * @param  integer $group_id   required, identifier that you would like to use to reference the group
     * @param  array   $member_ids An array of Member IDs to add in the group
     * @return string|array API request results
     **/
    function add_members_to_group($group_id, $member_ids)
    {
        $send_data['member_ids'] = $member_ids;
        $data = $this->make_request('groups/'.$group_id.'/members', 'PUT', $send_data);
        return $data;
    }

    /**
     * Remove members from a single active member group.
     *
     * @param  integer $group_id   required, identifier that you would like to use to reference the group
     * @param  array   $member_ids An array of Member IDs
     * @return string|array API request results
     **/
    function remove_members_from_group($group_id, $member_ids)
    {
        $send_data['member_ids'] = $member_ids;
        $data = $this->make_request('groups/'.$group_id.'/members/remove', 'PUT', $send_data);
        return $data;
    }

    /**
     * Remove ALL members from a single active member group.
     *
     * @param  integer $group_id         required, identifier that you would like to use to reference the group
     * @param  string  $member_status_id Optional. This is ‘a’ctive, ‘o’ptout, or ‘e’error.
     * @return string|array API request results
     **/
    function remove_all_members_from_group($group_id, $member_status_id = null)
    {
        $send_data['member_status_id'] = $member_status_id;
        $data = $this->make_request('groups/'.$group_id.'/members', 'DELETE', $send_data);
        return $data;
    }

    /**
     * Remove all members from all active member groups as a background job. The member_status_id parameter must be set.
     *
     * @param  integer $group_id         required, identifier that you would like to use to reference the group
     * @param  string  $member_status_id Required. This is ‘a’ctive, ‘o’ptout, or ‘e’error.
     * @return string|array API request results
     **/
    function remove_all_members_from_all_groups($group_id, $member_status_id = 'a')
    {
        $send_data['member_status_id'] = $member_status_id;
        $data = $this->make_request('groups/'.$group_id.'/members/remove', 'DELETE', $send_data);
        return $data;
    }

    /**
     * Copy all the users of one group into another group.
     *
     * @param  integer $from_group_id    A unique Group ID
     * @param  integer $to_group_id      A unique Group ID
     * @param  string  $member_status_id Required. This is ‘a’ctive, ‘o’ptout, or ‘e’error.
     * @return string|array API request results
     **/
    function copy_group_to_group($from_group_id, $to_group_id, $member_status_id = 'a')
    {
        $send_data['member_status_id'] = $member_status_id;
        $data = $this->make_request('groups/'.$from_group_id.'/'.$to_group_id.'/members/copy', 'PUT', $send_data);
        return $data;
    }

    /* *** END `GROUPS` METHODS *** */

    /* *** BEGIN `MAILINGS` METHODS *** */

    /**
     * Get information about current mailings.
     *
     * @param  boolean $include_archived Accepts “true”. All other values are False. Optional flag
     * to include archived mailings in the list.
     * @param  string  $mailing_types    Accepts a comma-separated string with one or more of the following mailing types: ‘m’ (regular), ‘t’ (test), ‘r’ (trigger). Defaults to ‘m,t’, standard and test mailings, when none are specified. mailing types: ‘m’ (regular), ‘t’ (test), ‘r’ (trigger). Defaults to ‘m,t’, standard and test mailings, when none are specified.
     * mailing types: ‘m’ (regular), ‘t’ (test), ‘r’ (trigger). Defaults to ‘m,t’, standard and test
     * mailings, when none are specified.
     * @param  string  $mailing_statuses Accepts a comma-separated string with one or more of the following mailing statuses: ‘p’ (pending), ‘a’ (paused), ‘s’ (sending), ‘x’ (canceled), ‘c’ (complete), ‘f’ (failed). Defaults to ‘p,a,s,x,c,f’, all statuses, when none are specified.
     * following mailing statuses: ‘p’ (pending), ‘a’ (paused), ‘s’ (sending), ‘x’ (canceled),
     * ‘c’ (complete), ‘f’ (failed). Defaults to ‘p,a,s,x,c,f’, all statuses, when none are specified.
     * @param  boolean $is_scheduled     Mailings that have a scheduled timestamp.
     * @return string|array API request results
     **/
    function get_mailing_list($include_archived = false, $mailing_types = null, $mailing_statuses = null, $is_scheduled = false)
    {
        $send_data['include_archived'] = $include_archived;
        if (!empty($mailing_types) ) {
            $send_data['mailing_types'] = $mailing_types;
        }
        if (!empty($mailing_statuses) ) {
            $send_data['mailing_statuses'] = $mailing_statuses;
        }
        $send_data['is_scheduled'] = $is_scheduled;
        $data = $this->make_request('mailings', 'GET', $send_data);
        return $data;
    }

    /**
     * Get detailed information for one mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_mailing_detail($mailing_id)
    {
        $data = $this->make_request('mailings/'.$mailing_id, 'GET');
        return $data;
    }

    /**
     * Get the list of members to whom the given mailing was sent.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_mailing_members($mailing_id)
    {
        $data = $this->make_request('mailings/'.$mailing_id.'/members', 'GET');
        return $data;
    }

    /**
     * Gets the personalized message content as sent to a specific member as part of the specified mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @param  integer $member_id  A unique Member ID
     * @param  string  $type       Accepts: ‘all’, ‘html’, ‘plaintext’, ‘subject’. Defaults to ‘all’, if not provided.
     * @return string|array API request results
     **/
    function get_mailing_message($mailing_id, $member_id, $type = 'all')
    {
        $data = $this->make_request('mailings/'.$mailing_id.'/messages/'.$member_id, 'GET');
        return $data;
    }

    /**
     * Get the groups to which a particular mailing was sent.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_mailing_groups($mailing_id)
    {
        $data = $this->make_request('mailings/'.$mailing_id.'/groups'.$member_id, 'GET');
        return $data;
    }

    /**
     * Update status of a current mailing This method can be used to control the progress of a mailing
     * by pausing, canceling or resuming it. Once a mailing is canceled it can’t be resumed, and will
     * not show in the normal mailing_list output.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @param  string  $status     The status can be one of: canceled, paused or ready.
     * @return string|array API request results
     **/
    function update_mailing_status($mailing_id, $status)
    {
        $send_data['status'] = $status;
        $data = $this->make_request('mailings/'.$mailing_id, 'PUT', $send_data);
        return $data;
    }

    /**
     * Sets archived timestamp for a mailing so it is no longer included in mailing_list.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function archive_mailing($mailing_id)
    {
        $data = $this->make_request('mailings/'.$mailing_id, 'DELETE');
        return $data;
    }

    /**
     * Cancels a mailing that has a current status of pending or paused. All other statuses will result in a 404.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function cancel_mailing($mailing_id)
    {
        $data = $this->make_request('mailings/cancel/'.$mailing_id, 'DELETE');
        return $data;
    }

    /**
     * Forward a previous message to additional recipients. If these recipients are not already in
     * the audience, they will be added with a status of FORWARDED.
     *
     * @param  integer $mailing_id       A unique Mailing ID
     * @param  integer $member_id        A unique Member ID
     * @param  array   $recipient_emails An array of email addresses to which to forward the specified message.
     * @param  string  $note             A note to include in the forward. This note will be HTML encoded and is limited to 500 characters.
     * @return string|array API request results
     **/
    function forward_message($mailing_id, $member_id, $recipient_emails, $note)
    {
        $send_data['recipient_emails'] = $recipient_emails;
        $send_data['note'] = $note;
        $data = $this->make_request('forwards/'.$mailing_id.'/'.$member_id, 'POST', $send_data);
        return $data;
    }

    /**
     * Send a prior mailing to additional recipients. A new mailing will be created that inherits its content from the original.
     *
     * @param  integer $mailing_id         A unique Mailing ID
     * @param  string  $sender             The message sender. If this is not supplied, the sender of the original mailing will be used.
     * @param  array   $heads_up_emails    A list of email addresses that heads up notification emails will be sent to.
     * @param  array   $recipient_emails   An array of email addresses to which the new mailing should be sent.
     * @param  array   $recipient_groups   An array of member groups to which the new mailing should be sent.
     * @param  string  $recipient_searches A list of searches that this mailing should be sent to.
     * @return string|array API request results
     **/
    function append_to_mailing($mailing_id, $sender = null, $heads_up_emails = null,
        $recipient_emails = null, $recipient_groups = null, $recipient_searches = null
    ) {
        $send_data = array();
        if (!empty($sender)) {
            $send_data['sender'] = $sender;
        }
        if (!empty($heads_up_emails)) {
            $send_data['heads_up_emails'] = $heads_up_emails;
        }
        if (!empty($recipient_emails)) {
            $send_data['recipient_emails'] = $recipient_emails;
        }
        if (!empty($recipient_groups)) {
            $send_data['recipient_groups'] = $recipient_groups;
        }
        if (!empty($recipient_searches)) {
            $send_data['recipient_searches'] = $recipient_searches;
        }
        $data = $this->make_request('mailings/'.$mailing_id, 'POST', $send_data);
        return $data;
    }

    /**
     * Get heads up email address(es) related to a mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_heads_up_emails($mailing_id)
    {
        $data = $this->make_request('mailings/'.$mailing_id.'/headsup', 'GET');
        return $data;
    }

    /**
     * Validate that a mailing has valid personalization-tag syntax.
     *
     * @param  string $html_body The html contents of the mailing
     * @param  string $plaintext The plaintext contents of the mailing.
     * Unlike in create_mailing, this param is not required.
     * @param  string $subject   The subject of the mailing
     * @return boolean true
     **/
    function validate_mailing($html_body, $plaintext, $subject)
    {
        $send_data = array(
            'html_body' => $html_body,
            'plaintext' => $plaintext,
            'subject' => $subject
        );
        $data = $this->make_request('mailings/validate', 'POST', $send_data);
        return $data;
    }

    /**
     * Declare the winner of a split test manually
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @param  integer $winner_id  A mailing split-test ID
     * @return boolean true on success
     **/
    function declare_mailing_winner($mailing_id, $winner_id)
    {
        $data = $this->make_request('mailings/'.$mailing_id.'/winner/'.$winner_id, 'POST');
        return $data;
    }

    /* *** END `MAILINGS` METHODS *** */

    /* *** BEGIN `MEMBERS` METHODS *** */

    /**
     * List Members
     *
     * @param  boolean $show_deleted Set to true to include deleted members in results
     * @param  integer $start Starting Offset
     * @param  integer $end Ending Offset
     * @return string|array API request results
     **/
    function list_members($show_deleted = null, $start = null, $end = null)
    {

        if ($show_deleted) {
            $send_data['deleted'] = 1;
        } else {
            $send_data['deleted'] = 0;
        }

        if ($start) {
            $send_data['start'] = $start;
        }

        if ($end > $start) {
            $send_data['end'] = $end;
        }

        $data = $this->make_request('members', 'GET', $send_data);
        return $data;
    }

    /**
     * Get Member Detail
     *
     * @param  integer $member_id Emma-assigned Member ID
     * @return string|array API request results
     **/
    function get_member_detail($member_id)
    {
        $data = $this->make_request('members/'.$member_id, 'GET');
        return $data;
    }

    /**
     * Get Member Detail using email
     *
     * @param  string $email Emma-assigned Member ID
     * @return string|array API request results
     **/
    function get_member_detail_by_email($email)
    {
        $data = $this->make_request('members/email/'.$email, 'GET');
        return $data;
    }

    /**
     * If a member has been opted out, returns the details of their optout, specifically date and mailing_id.
     *
     * @param  integer $member_id Emma-assigned Member ID
     * @return string|array API request results
     **/
    function get_member_optout_detail($member_id)
    {
        $data = $this->make_request('members/'.$member_id.'/optout', 'GET');
        return $data;
    }

    /**
     * Update a member’s status to optout, keyed on email address instead of an ID
     *
     * @param  string $email Email address of member
     * @return boolean True if member status change was successful or member was already opted out.
     **/
    function set_member_optout($email)
    {
        $data = $this->make_request('members/email/optout/'.$email, 'PUT');
        return $data;
    }

    /**
     * Add new members or update existing members.
     *
     * @param  array   $members         An array of members to update
     * @param  string  $source_filename An arbitrary string to associate with this import.
     * @param  boolean $add_only        Optional. Only add new members, ignore existing members.
     * @param  array   $group_ids       Optional. Add imported members to this list of groups
     * @return string|array API request results
     **/
    function import_member_list($members, $source_filename, $add_only = null, $group_ids = null )
    {

        $send_data['members'] = $members;
        $send_data['source_filename'] = $source_filename;

        if (!empty($add_only)) {
            $send_data['add_only'] = $add_only;
        }

        if (!empty($group_ids)) {
            $send_data['group_ids'] = $group_ids;
        }

        $data = $this->make_request('members', 'POST', $send_data);

        return $data;
    }

    /**
     * Adds or updates an audience member
     *
     * @param  string  $email          Email address of member to add or update
     * @param  array   $fields         Names and values of user-defined fields to update
     * @param  array   $group_ids      Optional. Add imported members to this list of groups
     * @param  integer $signup_form_id Optional. Indicate that this member used a particular signup form.
     * This is important if you have custom confirmation messages for a particular signup form and so that
     * signup-based triggers will be fired.
     * @return string|array API request results
     **/
    function import_single_member($email, $fields, $group_ids = null, $signup_form_id = null )
    {

        $send_data['email'] = $email;

        if (!empty($fields)) {
            $send_data['fields'] = $fields;
        }

        if (!empty($group_ids)) {
            $send_data['group_ids'] = $group_ids;
        }

        if (!empty($signup_form_id)) {
            $send_data['signup_form_id'] = $signup_form_id;
        }

        $data = $this->make_request('members/add', 'POST', $send_data);

        return $data;
    }

    /**
     * Delete an array of members.
     *
     * @param  array $member_ids An array of Emma-assigned Member IDs
     * @return string|array API request results
     **/
    function delete_members($member_ids)
    {
        $send_data['member_ids'] = $member_ids;
        $data = $this->make_request('members/delete', 'PUT', $send_data);
        return $data;
    }

    /**
     * The members will have their member_status_id updated.
     *
     * @param  array  $member_ids An array of Emma-assigned Member IDs
     * @param  string $status_to  The new status for the given members Accepts one of ‘a’, ‘e’, ‘o’ (active, error, optout).
     * Accepts one of ‘a’, ‘e’, ‘o’ (active, error, optout).
     * @return string|array API request results
     **/
    function update_members_status($member_ids, $status_to)
    {
        $send_data['member_ids'] = $member_ids;
        $send_data['status_to'] = $status_to;
        $data = $this->make_request('members/status', 'PUT', $send_data);
        return $data;
    }

    /**
     * Update a single member’s information.
     *
     * @param  string $member_id A unique Emma-assigned Member ID
     * @param  string $email     Email address of member to add or update
     * @param  string $status_to The new status for the given members
     * Accepts one of ‘a’, ‘e’, ‘o’ (active, error, optout).
     * @param  array  $fields    Names and values of user-defined fields to update
     * @return string|array API request results
     **/
    function update_member($member_id, $email, $status_to, $fields)
    {
        $send_data['email'] = $email;
        $send_data['status_to'] = $status_to;
        $send_data['fields'] = $fields;
        $data = $this->make_request('members/'.$member_id, 'PUT', $send_data);
        return $data;
    }

    /**
     * Delete the specified member
     *
     * @param  string $member_id A unique Emma-assigned Member ID
     * @return string|array API request results
     **/
    function delete_member($member_id)
    {
        $data = $this->make_request('members/'.$member_id, 'DELETE');
        return $data;
    }

    /**
     * Get the groups to which a member belongs.
     *
     * @param  string $member_id A unique Emma-assigned Member ID
     * @return string|array API request results
     **/
    function list_member_groups($member_id)
    {
        $data = $this->make_request('members/'.$member_id.'/groups', 'GET');
        return $data;
    }

    /**
     * Add a single member to one or more groups.
     *
     * @param  string $member_id A unique Emma-assigned Member ID
     * @param  array  $group_ids An array of integer Group IDs
     * @return string|array API request results
     **/
    function add_member_to_groups($member_id, $group_ids)
    {
        $send_data['group_ids'] = $group_ids;
        $data = $this->make_request('members/'.$member_id.'/groups', 'PUT', $send_data);
        return $data;
    }

    /**
     * Remove a single member from one or more groups.
     *
     * @param  string $member_id A unique Emma-assigned Member ID
     * @param  array  $group_ids An array of integer Group IDs
     * @return string|array API request results
     **/
    function remove_member_from_groups($member_id, $group_ids)
    {
        $send_data['group_ids'] = $group_ids;
        $data = $this->make_request('members/'.$member_id.'/groups/remove', 'PUT', $send_data);
        return $data;
    }

    /**
     * Delete all members
     *
     * @param  string $member_status_id This is ‘a’ctive, ‘o’ptout, or ‘e’error
     * @return string|array API request results
     **/
    function remove_all_members($member_status_id)
    {
        $send_data['member_status_id'] = $member_status_id;
        $data = $this->make_request('members', 'DELETE', $send_data);
        return $data;
    }

    /**
     * Remove the specified member from all groups.
     *
     * @param  string $member_id A unique Emma-assigned Member ID
     * @return string|array API request results
     **/
    function remove_member_from_all_groups($member_id)
    {
        $data = $this->make_request('members/'.$member_id.'/groups', 'DELETE');
        return $data;
    }

    /**
     * Remove multiple members from groups.
     *
     * @param  array $member_ids An array of unique Member IDs
     * @param  array $group_ids  An array of unique Group IDs
     * @return string|array API request results
     **/
    function remove_members_from_groups($member_ids, $group_ids)
    {
        $send_data['member_ids'] = $member_ids;
        $send_data['group_ids'] = $group_ids;
        $data = $this->make_request('members/groups/remove', 'PUT', $send_data);
        return $data;
    }

    /**
     * Get the entire mailing history for a member.
     *
     * @param  string $member_id A unique Member ID
     * @return string|array API request results
     **/
    function get_member_mailing_history($member_id)
    {
        $data = $this->make_request('members/'.$member_id.'/mailings', 'GET');
        return $data;
    }

    /**
     * Get a list of members affected by this import.
     *
     * @param  string $import_id A unique Import ID
     * @return string|array API request results
     **/
    function get_import_stats_members($import_id)
    {
        $data = $this->make_request('members/imports/'.$import_id.'/members', 'GET');
        return $data;
    }

    /**
     * Get information and statistics about this import.
     *
     * @param  string $import_id A unique Import ID
     * @return string|array API request results
     **/
    function get_import_stats($import_id)
    {
        $data = $this->make_request('members/imports/'.$import_id, 'GET');
        return $data;
    }

    /**
     * Get information about all imports for this account.
     *
     * @return string|array API request results
     **/
    function get_all_import_stats()
    {
        $data = $this->make_request('members/imports', 'GET');
        return $data;
    }

    /**
     * Update an import record to be marked as ‘deleted’.
     *
     * @return string|array API request results
     **/
    function mark_import_as_deleted()
    {
        $data = $this->make_request('members/imports/delete', 'DELETE');
        return $data;
    }

    /**
     * Copy all account members of one or more statuses into a group.
     *
     * @param  integer $group_id         A unique Group ID
     * @param  array   $member_status_id This is an array containing ‘a’ctive, ‘o’ptout, and/or ‘e’error.
     * @return string|array API request results
     **/
    function copy_to_group($group_id, $member_status_id)
    {
        $send_data['member_status_id'] = $member_status_id;
        $data = $this->make_request('members/'.$group_id.'/copy', 'PUT', $send_data);
        return $data;
    }

    /**
     * Update the status for a group of members, based on their current status
     *
     * @param  string  $status_from This is ‘a’ctive, ‘o’ptout, and/or ‘e’error.
     * @param  string  $status_to   This is ‘a’ctive, ‘o’ptout, and/or ‘e’error.
     * @param  integer $group_id    A unique Group ID
     * @return string|array API request results
     **/
    function bulk_change_member_status($status_from, $status_to, $group_id = null)
    {
        $send_data['group_id'] = $group_id;
        $data = $this->make_request('members/status/'.$status_from.'/to/'.$status_to, 'PUT', $send_data);
        return $data;
    }

    /* *** END `MEMBERS` METHODS *** */

    /* *** BEGIN `RESPONSE` METHODS *** */

    /**
     * Get the response summary for an account.
     * @param boolean $include_archived Accepts 1. All other values are False. Optional flag to
     * include archived mailings in the list.
     * @param string  $range            Accepts 2 dates (YYYY-MM-DD) delimited by a tilde (~). Example: 2011-04-01~2011-09-01 Optional argument to limit results to a date range. If one of the dates is omitted, the default will be either min date or now. If a single date is provided with no tilde, then only mailings sent on that date will be included. Example: 2011-04-01~2011-09-01 Optional argument to limit results to a date range. If one of the dates is omitted, the default will be either min date or now. If a single date is provided with no tilde, then only mailings sent on that date will be included.
     * Example: 2011-04-01~2011-09-01 Optional argument to limit results to a date range. If one
     * of the dates is omitted, the default will be either min date or now. If a single date is
     * provided with no tilde, then only mailings sent on that date will be included.
     * @return string|array API request results
     **/
    function get_response($include_archived = false, $range = null)
    {

        $send_data = array();
        if (!empty($include_archived)) {
            $send_data['include_archived'] = $include_archived;
        }
        if (!empty($range)) {
            $send_data['range'] = $range;
        }

        $data = $this->make_request('response', 'GET', $send_data);
        return $data;
    }

    /**
     * Get the response summary for a particular mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_response_overview($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id, 'GET');
        return $data;
    }

    /**
     * Get the list of messages that have been sent to an MTA for delivery.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_sends($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/sends', 'GET');
        return $data;
    }

    /**
     * Get the list of messages that are in the queue, possibly sent, but not yet delivered.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_in_progress($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/in_progress', 'GET');
        return $data;
    }

    /**
     * Get the list of messages that have finished delivery.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_deliveries($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/deliveries', 'GET');
        return $data;
    }

    /**
     * Get the list of opened messages for this campaign.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_opens($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/opens', 'GET');
        return $data;
    }

    /**
     * Get the list of links for this mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_links($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/links', 'GET');
        return $data;
    }

    /**
     * Get the list of clicks for this mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @param  integer $member_id  Limits results to a single member.
     * @param  integer $link_id    Limits results to a single link
     * @return string|array API request results
     **/
    function get_clicks($mailing_id, $member_id = null, $link_id = null)
    {

        $send_data = array();
        if (!empty($member_id)) {
            $send_data['member_id'] = $member_id;
        }
        if (!empty($link_id)) {
            $send_data['link_id'] = $link_id;
        }

        $data = $this->make_request('response/'.$mailing_id.'/clicks', 'GET', $send_data);
        return $data;
    }

    /**
     * Get the list of forwards for this mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_forwards($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/forwards', 'GET');
        return $data;
    }

    /**
     * Get the list of optouts for this mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_optouts($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/optouts', 'GET');
        return $data;
    }

    /**
     * Get the list of signups for this mailing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_signups($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/signups', 'GET');
        return $data;
    }

    /**
     * Get the list of shares for this mailing
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_shares($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/shares', 'GET');
        return $data;
    }

    /**
     * Save an entry every time a customer shares a mailing via Social Publishing.
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function save_customer_share($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/customer_share', 'POST');
        return $data;
    }

    /**
     * Get the list of customer shares for this mailing
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_customer_shares($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/customer_shares', 'GET');
        return $data;
    }

    /**
     * Get the list of customer share clicks for this mailing
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_customer_share_clicks($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/customer_share_clicks', 'GET');
        return $data;
    }

    /**
     * Get the customer share associated with the share id.
     *
     * @param  integer $share_id A unique Share ID
     * @return string|array API request results
     **/
    function get_customer_share($share_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/customer_share', 'GET');
        return $data;
    }

    /**
     * Get the list of customer share clicks for this mailing
     *
     * @param  integer $mailing_id A unique Mailing ID
     * @return string|array API request results
     **/
    function get_share_overview($mailing_id)
    {
        $data = $this->make_request('response/'.$mailing_id.'/shares/overview', 'GET');
        return $data;
    }

    /* *** END `RESPONSE` METHODS *** */

    /* *** BEGIN `SEARCHES` METHODS *** */

    /**
     * Retrieve a list of saved searches
     *
     * @param  boolean $deleted Set to TRUE or 1 to include deleted fields in results
     * @return string|array API request results
     **/
    function list_searches($deleted = null)
    {

        if ($deleted) {
            $send_data['deleted'] = 1;
        } else {
            $send_data['deleted'] = 0;
        }

        $data = $this->make_request('searches', 'GET', $send_data);
        return $data;
    }

    /**
     * Get the details for a saved search
     *
     * @param  string $search_id A unique Search ID
     * @return string|array API request results
     **/
    function get_search_detail($search_id)
    {
        $data = $this->make_request('searches/'.$search_id, 'GET');
        return $data;
    }

    /**
     * Create a saved search
     *
     * @param  array  $criteria Search criteria
     * @param  string $name     A name for the search
     * @return string|array API request results
     **/
    function create_search($criteria, $name)
    {
        $send_data['criteria'] = $criteria;
        $send_data['name'] = $name;
        $data = $this->make_request('searches', 'POST', $send_data);
        return $data;
    }

    /**
     * Update a saved search
     *
     * @param  string $search_id A unique Search ID
     * @param  array  $criteria  Search criteria
     * @param  string $name      A name for the search
     * @return string|array API request results
     **/
    function update_search($search_id, $criteria, $name)
    {
        $send_data['criteria'] = $criteria;
        $send_data['name'] = $name;
        $data = $this->make_request('searches/'.$search_id, 'PUT', $send_data);
        return $data;
    }

    /**
     * Delete a saved search. The member records referred to by the search are not affected.
     *
     * @param  string $search_id A unique Search ID
     * @return string|array API request results
     **/
    function delete_search($search_id)
    {
        $data = $this->make_request('searches/'.$search_id, 'DELETE');
        return $data;
    }

    /**
     * Get the members matching the search.
     *
     * @param  string $search_id A unique Search ID
     * @return string|array API request results
     **/
    function get_search_members($search_id)
    {
        $data = $this->make_request('searches/'.$search_id.'/members', 'GET');
        return $data;
    }

    /* *** END `SEARCHES` METHODS *** */

    /* *** BEGIN `TRIGGERS` METHODS *** */

    /**
     * Get a basic listing of all triggers in an account.
     *
     * @param  boolean $deleted Set to TRUE or 1 to include deleted fields in results
     * @return string|array API request results
     **/
    function list_triggers($deleted = null)
    {
        $data = $this->make_request('triggers', 'GET');
        return $data;
    }

    /**
     * Create a new trigger.
     *
     * @param  string  $name              A descriptive name for the trigger.
     * @param  string  $event_type        The type of event that causes this trigger to fire. Accepts one of ‘s’, ‘c’, ‘u’, ‘d’, ‘r’ (signup, click, survey, date, recurring date). Accepts one of ‘s’, ‘c’, ‘u’, ‘d’, ‘r’ (signup, click, survey, date, recurring date).
     * Accepts one of ‘s’, ‘c’, ‘u’, ‘d’, ‘r’ (signup, click, survey, date, recurring date).
     * @param  integer $parent_mailing_id The id of the mailing this trigger will use as a template for message generation.
     * @param  array   $groups            An optional array of group ids to which this trigger will apply.
     * @param  array   $links             An array of link_ids for click triggers.
     * @param  array   $signups           An array of signup_form_ids for signup triggers.
     * @param  array   $surveys           An array of survey_ids for survey triggers.
     * @param  integer $field_id          A field id to which this trigger will apply if it is a date or recurring date trigger.
     * @param  integer $push_offset       An optional delay interval for messages created from this trigger (specified in seconds).
     * @param  boolean $is_disabled       An optional flag to disable the trigger.
     * @return string|array API request results
     **/
    function create_trigger($name, $event_type, $parent_mailing_id, $groups = null, $links = null,
        $signups = null, $surveys = null, $field_id = null, $push_offset = null, $is_disabled = null
    ) {
        $send_data['name'] = $name;
        $send_data['event_type'] = $event_type;
        $send_data['parent_mailing_id'] = $parent_mailing_id;
        if (!empty($groups)) {
            $send_data['groups'] = $groups;
        }
        if (!empty($links)) {
            $send_data['links'] = $links;
        }
        if (!empty($signups)) {
            $send_data['signups'] = $signups;
        }
        if (!empty($surveys)) {
            $send_data['surveys'] = $surveys;
        }
        if (!empty($field_id)) {
            $send_data['field_id'] = $field_id;
        }
        if (!empty($push_offset)) {
            $send_data['push_offset'] = $push_offset;
        }
        if (!empty($is_disabled)) {
            $send_data['is_disabled'] = $is_disabled;
        }
        $data = $this->make_request('triggers', 'POST', $send_data);
        return $data;
    }

    /**
     * Look up a trigger by trigger id.
     *
     * @param  string $trigger_id A unique Trigger ID
     * @return string|array API request results
     **/
    function get_trigger($trigger_id)
    {
        $data = $this->make_request('triggers/'.$trigger_id, 'GET');
        return $data;
    }

    /**
     * Update or edit a trigger.
     *
     * @param  string $trigger_id A unique Trigger ID
     * @return string|array API request results
     **/
    function update_trigger($trigger_id)
    {
        $data = $this->make_request('triggers/'.$trigger_id, 'PUT');
        return $data;
    }

    /**
     * Delete a trigger.
     *
     * @param  string $trigger_id A unique Trigger ID
     * @return string|array API request results
     **/
    function delete_trigger($trigger_id)
    {
        $data = $this->make_request('triggers/'.$trigger_id, 'DELETE');
        return $data;
    }

    /**
     * Get mailings sent by a trigger.
     *
     * @param  string $trigger_id A unique Trigger ID
     * @return string|array API request results
     **/
    function get_trigger_mailings($trigger_id)
    {
        $data = $this->make_request('triggers/'.$trigger_id.'/mailings', 'GET');
        return $data;
    }

    /* *** END `TRIGGERS` METHODS *** */

    /* *** BEGIN `WEBHOOKS` METHODS *** */

    /**
     * Get a basic listing of all webhooks associated with an account.
     *
     * @return string|array API request results
     **/
    function list_webhooks()
    {
        $data = $this->make_request('webhooks', 'GET');
        return $data;
    }

    /**
     * Get information for a specific webhook belonging to a specific account.
     *
     * @param  string $webhook_id A unique Webhook ID
     * @return string|array API request results
     **/
    function get_webhook($webhook_id)
    {
        $data = $this->make_request('webhooks/'.$webhook_id, 'GET');
        return $data;
    }

    /**
     * Get a listing of all event types that are available for webhooks.
     *
     * @return string|array API request results
     **/
    function list_webhook_event_types()
    {
        $data = $this->make_request('webhooks/events', 'GET');
        return $data;
    }

    /**
     * Create a new webhook.
     *
     * @param  string $event  The name of an event to register this webhook for
     * @param  string $url    The URL to call when the event happens
     * @param  string $method The method to use when calling the webhook. Can be GET or POST. Defaults to POST.
     * @param  string $user   The user_id to use for authentication
     * @return string|array API request results
     **/
    function create_webhook($event, $url, $method = 'POST', $user=null)
    {
        $send_data['event'] = $event;
        $send_data['url'] = $url;
        $send_data['method'] = $method;
        if (!empty($user)) {
            $send_data['user'] = $user;
        }
        $data = $this->make_request('webhooks', 'POST', $send_data);
        return $data;
    }

    /**
     * Update an existing webhook
     *
     * @param  string $webhook_id A unique Webhook ID
     * @param  string $event      The name of an event to register this webhook for
     * @param  string $url        The URL to call when the event happens
     * @param  string $method     The method to use when calling the webhook. Can be GET or POST. Defaults to POST.
     * @param  string $user       The user_id to use for authentication
     * @return string|array API request results
     **/
    function update_webhook($webhook_id, $event, $url, $method, $user=null)
    {
        $send_data['event'] = $event;
        $send_data['url'] = $url;
        $send_data['method'] = $method;
        if (!empty($user)) {
            $send_data['user'] = $user;
        }
        $data = $this->make_request('webhooks/'.$webhook_id, 'PUT', $send_data);
        return $data;
    }

    /**
     * Deletes an existing webhook.
     *
     * @param  string $webhook_id A unique Webhook ID
     * @return string|array API request results
     **/
    function delete_webhook($webhook_id)
    {
        $data = $this->make_request('webhooks/'.$webhook_id, 'DELETE');
        return $data;
    }

    /**
     * Delete all webhooks registered for an account
     *
     * @param  string $webhook_id A unique Webhook ID
     * @return string|array API request results
     **/
    function delete_webhooks($webhook_id)
    {
        $data = $this->make_request('webhooks', 'DELETE');
        return $data;
    }

    /* *** END `WEBHOOKS` METHODS *** */

} // END class
?>