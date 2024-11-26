<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telegram_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		// Load the database library
		$this->load->database();
	}
	
	// Method to get data from 'tblleads' with source = 4 or staffid = 1
	public function get_filtered_leads_data()
	{
		// Query with conditions 'source = 4' (4 for telegram) and 'assigned'
		$this->db->where('source', 4);
		if (!is_admin()) {
		$this->db->where('assigned', $_SESSION['staff_logged_in']);	// Use condition
		}
		$query = $this->db->get('leads'); // Get data from leads
		return $query->result_array(); // Return the result as an associative array
	}
	
	// Method to get all records from the 'telegram' table
	public function get_all_telegram_data($chat_id=NULL)
	{
		$query = $this->db->query("SELECT * FROM tbltelegram WHERE chat_id = ?", [$chat_id]);
		return $query->result_array();
	}
}