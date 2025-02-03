<?php

use app\services\imap\Imap;
use app\services\imap\ConnectionErrorException;
use Ddeboer\Imap\Exception\MailboxDoesNotExistException;
defined('BASEPATH') or exit('No direct script access allowed');

class Webmail_setup extends AdminController
{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->model('webmail_setup_model');
        if (!is_admin()) {
            //access_denied('Access Webmail Setup');
			//$sid=get_staff_user_id();
        }
        
    }

    /* List all custom fields */
    public function index()
    {
	    $sid="";
	    if (!is_admin()) {
            //access_denied('Access Webmail Setup');
			$sid=get_staff_user_id();
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('webmail_setup');
        }
        $data['title'] = _l('Webmail Setup');
        $where="";
        $data['webmaillist']=$this->webmail_setup_model->get($sid);
		$data['departmentlist']   = $this->webmail_setup_model->getlist('', $where);
		//print_r($data['departmentlist']);
		
        $this->load->view('admin/webmail_setup', $data);
    }
	
	
	//Add Webmail Setup 
    public function webmail_setup_create()
    {
	
        $data = $this->input->post();
		
		if (isset($data['fakeusernameremembered'])) {
            unset($data['fakeusernameremembered']);
        }

        if (isset($data['fakepasswordremembered'])) {
            unset($data['fakepasswordremembered']);
        }



        unset($data['id']);
        $data['creator']      = get_staff_user_id();
        $data['creator_name'] = get_staff_full_name($data['creator']);

        if (empty($data['port'])) {
            unset($data['port']);
        }
         //print_r($data);
        $this->webmail_setup_model->create($data);
        set_alert('success', _l('added_successfully', _l('Webmail Setup')));
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    }
	
	
	//Update Webmail Setup 
    public function webmail_setup_update($entry_id)
    {
	
        $entry = $this->webmail_setup_model->get($entry_id);
		
        if (($entry->creator == get_staff_user_id() || is_admin()) || ( !is_admin()&& get_staff_user_id())) {
            $data = $this->input->post();

            if (isset($data['fakeusernameremembered'])) {
                unset($data['fakeusernameremembered']);
            }
            if (isset($data['fakepasswordremembered'])) {
                unset($data['fakepasswordremembered']);
            }
			unset($_SESSION['webmail']);

            $data['last_updated_from'] = get_staff_full_name(get_staff_user_id());
            //$data['description']       = nl2br($data['description']);

           

            $this->webmail_setup_model->update($entry_id, $data);
            set_alert('success', _l('updated_successfully', _l('Webmail Setup')));
        }
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
    }
	
	//Delete Webmail Setup from database 
    public function delete($id)
    {
	
	
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }
        $response = $this->webmail_setup_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('webmail setup')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('webmail setup')));
        }
        redirect(admin_url('webmail_setup'));
    }
	
	//Fetch for for update by id
    public function webmail_setup_entry($id)
    {
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }
		$sid="";
	    if (!is_admin()) { $sid=get_staff_user_id();}else{$sid=0;}
		
        $this->db->where('id', $id);
		$this->db->where('staffid', $sid);
        $entry=$this->webmail_setup_model->getdata();
		echo json_encode($entry[0]);
		}

    //Delete Webmail Setup from database 
    public function statusoff($id)
    {
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }
        $response = $this->webmail_setup_model->status($id ,0);
        if ($response == true) {
            set_alert('success', _l('webmail setup status updated', _l('webmail setup status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('webmail setup')));
        }
        redirect(admin_url('webmail_setup'));
    }
	//Delete Webmail Setup from database 
    public function statuson($id)
    {
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }
        $response = $this->webmail_setup_model->status($id ,1);
        if ($response == true) {
            set_alert('success', _l('webmail setup status updated', _l('webmail setup status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('webmail setup')));
        }
        redirect(admin_url('webmail_setup'));
    }
	
	
	public function folders()
    {
        app_check_imap_open_function();

        $imap = new Imap(
           $this->input->post('username') ? $this->input->post('username') : $this->input->post('email'),
           $this->input->post('password', false),
           $this->input->post('host'),
           $this->input->post('encryption')
        );

        try {
            echo json_encode($imap->getSelectableFolders());
        } catch (ConnectionErrorException $e) {
            echo json_encode([
                'alert_type' => 'warning',
                'message'    => $e->getMessage(),
            ]);
        }
    }

    public function test_imap_connection()
    {
        app_check_imap_open_function();

        $imap = new Imap(
           $this->input->post('username') ? $this->input->post('username') : $this->input->post('email'),
           $this->input->post('password', false),
           $this->input->post('host'),
           $this->input->post('encryption')
        );

        try {
            $connection = $imap->testConnection();

            try {
                $folder = $this->input->post('folder');

                $connection->getMailbox(empty($folder) ? 'INBOX' : $folder);
            } catch (MailboxDoesNotExistException $e) {
                echo json_encode([
                    'alert_type' => 'warning',
                    'message'    => $e->getMessage(),
                ]);
                die;
            }
            echo json_encode([
                'alert_type' => 'success',
                'message'    => _l('lead_email_connection_ok'),
            ]);
        } catch (ConnectionErrorException $e) {
            echo json_encode([
                'alert_type' => 'warning',
                'message'    => $e->getMessage(),
            ]);
        }
    }



}
 