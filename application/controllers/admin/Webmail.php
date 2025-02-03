<?php

//use app\services\imap\Imap;

defined('BASEPATH') or exit('No direct script access allowed');

class Webmail extends AdminController
{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->model('webmail_model');
        if (!is_admin()) {
            //access_denied('Access Webmail Setup');
        }
		//echo "=====================>>>";
		$wheredata="";
		$staffid=get_staff_user_id();
		if (!is_client_logged_in() && !is_admin() && is_staff_logged_in()) {
		$wheredata=' staffid=' . $staffid;
		$data['staffid']=get_staff_user_id();
		$data['departmentid']  = $this->webmail_model->departmentid(get_staff_user_id(), $wheredata);
		//print_r($data['departmentid']);
		//
		
		if(isset($data['departmentid'][0]['departmentid'])&&$data['departmentid'][0]['departmentid']){
		$wheredata='('.$wheredata.' OR departmentid='.$data['departmentid'][0]['departmentid'].' OR FIND_IN_SET('.$staffid.', assignto))';
		}else{
		$wheredata=$wheredata.' OR FIND_IN_SET('.$staffid.', assignto)';
		}
		//echo $wheredata;exit;
		
		}else{
		$wheredata='staffid=0';
		}
		
		$wheredata=$wheredata.' AND mailer_status=1';
		//echo $wheredata;exit;
		$_SESSION['mailersdropdowns']   = $this->webmail_model->getemaillist('', $wheredata);
		
		//print_r($_SESSION['mailersdropdowns']);exit;
		//if(!empty($_SESSION['mailersdropdowns'])){
		//echo "================>";
		//print_r($_SESSION['webmail']);
		
		
		
		if(isset($_GET['mt'])&&$_GET['mt']){
		$wheredata.=' AND id='.$_GET['mt'];
		}
		
		if((isset($_GET['mt'])&&$_GET['mt'] <> $_SESSION['webmail']['id']) || empty($_SESSION['webmail']) ){  
		//echo "New Session";
		//echo $wheredata;exit;
		$_SESSION['inbox-total-email']="";
		$_SESSION['outbox-total-email']="";
		$data['webmailsetup']= $this->webmail_model->webmailsetup('', $wheredata);
		//print_r($data['webmailsetup']);
		$_SESSION['webmail']=$data['webmailsetup'][0];
		$_SESSION['webmail']['login-staffid']=$data['staffid'];
		$_SESSION['webmail']['login-departmentid']=$data['departmentid'][0]['departmentid'];
		$_SESSION['webmail']['login-staffid']=$data['staffid'];
		}else{
		//echo "Old Session";
		}
		//}
		
		//print_r($_SESSION['webmail']);
		//echo "=====================>>>";//exit;
        
    }
    /* Redirect Index to Inbox page */
    public function index()
    {
		redirect(admin_url('webmail/inbox'));
    }
	
	
	 //Display Inbox Listing 
	public function inbox()
	{
	    $data['title'] = _l('Webmail Setup');
		
		
		
		
		//print_r($_SESSION['mailersdropdowns']);
		if(empty($_SESSION['mailersdropdowns'])){
		$data['errormessage']="Account not Assigned, Please add your webmail setup or contact web admin";
		$this->load->view('admin/webmail/inbox22', $data);
		}else{
		
		$data['inboxemail']=$this->webmail_model->getinboxemail();
		////////////////////////////////////////////
		$this->load->view('admin/webmail/inbox', $data);
		}
	}	
	
	//Display Inbox Listing 
	public function sent()
	{
	    $data['title'] = _l('Send Box');
		
		//print_r($_SESSION['mailersdropdowns']);
		if(empty($_SESSION['mailersdropdowns'])){
		$data['errormessage']="Account not Assigned, Please add your webmail setup or contact web admin";
		$this->load->view('admin/webmail/inbox44', $data);
		}else{
		
		$data['outboxemail']=$this->webmail_model->getoutboxemail();
		////////////////////////////////////////////
		$this->load->view('admin/webmail/sent', $data);
		}
	}	
	
	
	 
	 public function reply()
	{
	    
		$data = $this->input->post();
		//print_r($data);exit;
		$entry_id=get_staff_user_id();
        $this->webmail_model->reply($data, $entry_id);
        set_alert('success', _l('Emai Sent Successfully', _l('Emai Sent')));
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
	} 
	

	
	 //Display Inbox Listing 
	public function compose()
	{
	    $data['title'] = _l('New Email');
		
		//print_r($_SESSION['mailersdropdowns']);
		if(empty($_SESSION['mailersdropdowns'])){
		$data['errormessage']="Account not Assigned, Please add your webmail setup or contact web admin";
		$this->load->view('admin/webmail/compose', $data);
		}else{
		////////////////////////////////////////////
		$this->load->view('admin/webmail/compose', $data);
		}
	}

	


}
 