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
	public function get_filtered_leads_data($bot_id)
	{
		// Query with conditions 'source = 4' (4 for telegram) and 'assigned'
		$this->db->where('source', 4);
		if (!is_admin()) {
		$this->db->where('assigned', $_SESSION['staff_logged_in']);	// Use condition
		}
		// Add condition to filter by bot_id
		$this->db->where('telegram_bot_id', $bot_id); // Filter by bot_id
		$query = $this->db->get('leads'); // Get data from leads
		return $query->result_array(); // Return the result as an associative array
	}
	
	// Method to get all records from the 'telegram' table
	public function get_all_telegram_data($chat_id=NULL)
	{
		$query = $this->db->query("SELECT * FROM tbltelegram WHERE chat_id = ?", [$chat_id]);
		return $query->result_array();
	}
	// Method to get all Telegram configurations
	public function getAllTelegramConfigurations()
	{
		// Get all records from the 'telegram_bot' table
		$query = $this->db->get(db_prefix() .'telegram_bot');
		return $query->result_array(); // Return the result as an associative array
	}
	public function getTelegramConfigurationByNameOrUsername($name, $username)
	{
		// Prepare the data array
		$data = [
			'name' => $name,
			'username' => $username
		];
		$this->db->where('telegram_name', $data['name']);
		$this->db->or_where('telegram_username', $data['username']);
		$query = $this->db->get(db_prefix() .'telegram_bot');
		return $query->row_array(); // Return a single row if exists
	}
	public function getTelegramConfigurationById($id)
	{
		// Get the record with the given ID from the 'telegram_bot' table
		$this->db->where('id', $id);
		$query = $this->db->get(db_prefix() .'telegram_bot');
		return $query->row_array(); // Return a single row if exists
	}	
	public function addTelegramConfiguration($data)
	{
		// Insert the data into the 'tbltelegram' table
		return $this->db->insert(db_prefix() .'telegram_bot', $data);
	}
	public function updateTelegramConfiguration($id, $data)
	{
		// Update the record with the given ID in the 'tbltelegram' table
		$this->db->where('id', $id);
		return $this->db->update(db_prefix() .'telegram_bot', $data);
	}
	public function deleteTelegramConfiguration($id)
	{
		// Delete the record with the given ID from the 'tbltelegram' table
		$this->db->where('id', $id);
		return $this->db->delete(db_prefix() .'telegram_bot');
	}	
	public function get_all_bots()
	{
		// Get all records from the 'telegram_bot' table
		$query = $this->db->get(db_prefix() .'telegram_bot');
		return $query->result_array(); // Return the result as an associative array
	}
}