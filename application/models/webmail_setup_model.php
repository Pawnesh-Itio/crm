<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Webmail_setup_model extends App_Model
{
    //private $pdf_fields = ['estimate', 'invoice', 'credit_note', 'items'];

    //private $client_portal_fields = ['customers', 'estimate', 'invoice', 'proposal', 'contracts', 'tasks', 'projects', 'contacts', 'tickets', 'company', 'credit_note'];

   // private $client_editable_fields = ['customers', 'contacts', 'tasks'];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param  integer (optional)
     * @return object
     * Get single custom field
     */
    public function get($id = false)
    {
	    
	    $this->db->order_by('id', 'desc');
        if (is_numeric($id)) {
            $this->db->where('staffid', $id);
            return $this->db->get(db_prefix().'webmail_setup')->result_array();
			
        }else{
		$this->db->where('staffid', 0); // for hide staff Added data from admin list
		}

        return $this->db->get(db_prefix().'webmail_setup')->result_array();
    }
	
	// for update
	public function getdata($id = false)
    {
	     
	    $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix().'webmail_setup')->result_array();
		//return 
		//echo $this->db->last_query();exit;
    }

    

   //Delete Webmail Setup
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix().'webmail_setup');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }
	
	
	public function create($data)
    {
	
	//print_r($data);
       
        $data['date_created']      = date('Y-m-d H:i:s');
        $data['staffid']       = get_staff_user_id();
		if (is_admin()){
		$data['staffid']       = 0;
		}
        $data['share_in_projects'] = isset($data['share_in_projects']) ? 1 : 0;
		
		
        $this->db->insert(db_prefix().'webmail_setup', $data);
		
		if ($this->db->affected_rows() > 0) {
		log_activity('Data Submitted');
		return true;
		}else{
		log_activity('Data Not Submitted');
		return false;
		}
		 //echo $this->db->last_query();exit;

        
    }
	
	
    //Update Webmail Setup
    public function update($id, $data)
    {
	
        $webmail = $this->get($id);

        $last_updated_from = $data['last_updated_from'];
        unset($data['last_updated_from']);
        $data['share_in_projects'] = isset($data['share_in_projects']) ? 1 : 0;

        $this->db->where('id', $id);
        $this->db->update(db_prefix().'webmail_setup', $data);

        if ($this->db->affected_rows() > 0) {
            $this->db->where('id', $id);
            $this->db->update(db_prefix().'webmail_setup', ['last_updated' => date('Y-m-d H:i:s'), 'last_updated_from' => $last_updated_from]);
            log_activity('Webmail Setup Entry Updated [ ID: ' . $id . ']');

            return true;
        }

        return false;
    }
	
	
	
	//Update Webmail Setup
	/*public function webmail_setup_entry($id)
    {
	
        
		$sid="";
	    if (!is_admin()) { $sid=get_staff_user_id();}
			
        

        $this->db->where('id', $id);
		$this->db->where('staffid', $sid);
        $this->db->delete(db_prefix().'webmail_setup');
        if ($this->db->affected_rows() > 0) {
            return true;
        }

        return false;
    }*/
	
	//Update Status
	public function status($id, $status)
    {
	    $sid="";
	    if (!is_admin()) {
			$sid=get_staff_user_id();
        }

        $this->db->where('id', $id);
		$this->db->where('staffid', $sid);
		
        $this->db->update(db_prefix().'webmail_setup', [
            'mailer_status' => $status,
        ]);
		if ($this->db->affected_rows() > 0) {
		log_activity('Webmail Setup Status Changed [FieldID: ' . $id . ' - Active: ' . $status . ']');
            return true;
        }

        return false;
        
    }
	
	public function getlist($id = '', $where = [])
    {
        $this->db->select('name,departmentid,');
        //$this->db->where($where);
        $this->db->order_by('name', 'asc');
		//$this->db->limit(1);
        return $this->db->get(db_prefix() . 'departments')->result_array();
    }

    
	 
   

   

    
    
}
