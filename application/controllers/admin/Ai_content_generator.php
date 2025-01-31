<?php

//use app\services\imap\Imap;

defined('BASEPATH') or exit('No direct script access allowed');

class Ai_content_generator extends AdminController
{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ai_content_generator_model');
        if (!is_admin()) {
            //access_denied('Access Webmail Setup');
        }
		$data['user_id']      = get_staff_user_id();
        $data['added_by'] = get_staff_full_name($data['user_id']);
		$where=" user_id = '".$data['user_id']."' AND added_by ='".$data['added_by']."'";
		$_SESSION['datalists']   = $this->ai_content_generator_model->getlist('', $where);
		
        
    }
    /* Redirect Index to Inbox page */
    public function index()
    {
	    $data['title'] = _l('Generate Content');
		$this->load->view('admin/ai-content-generator', $data);
    }
	
	public function generate()
    {
	
	    $data = $this->input->post();
		if (isset($data['submit'])) { unset($data['submit'] );}
		$data['user_id']      = get_staff_user_id();
        $data['added_by'] = get_staff_full_name($data['user_id']);
        $data['ai_content']=$this->ai_content_generator_model->generate($data);
		//print_r($data['ai_content']);
		$data['content_description']=$data['ai_content']['content'];
		//exit;
        set_alert('success', _l('added_successfully', _l('AI Content')));
		$this->load->view('admin/ai-content-generator', $data);
    }
	

	


}
 