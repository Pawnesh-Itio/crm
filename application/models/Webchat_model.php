<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webchat_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		// Load the database library
		$this->load->database();
	}
	
	// Method to get data from 'tblleads' with source = 4 or staffid = 1
	public function get_filtered_data()
	{
		// Query with conditions 'source = 5' (5 for webchat) and 'assigned'
		$this->db->where('source', 5);

		if (!is_admin()) {
			$this->db->where('assigned', $_SESSION['staff_logged_in']);	// Use condition
		}
		$query = $this->db->get(db_prefix() . 'leads'); // Get data from leads
		return $query->result_array(); // Return the result as an associative array
	}
	
	// Method to get all records from the 'it_crm_messages' table
	public function get_all_data($chat_id=NULL)
	{
		$this->db->where('outgoing_msg_id', $chat_id);

		$query = $this->db->get(db_prefix() . 'messages'); // Get data from leads

		return $query->result_array();
	}
}