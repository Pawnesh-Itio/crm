<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model');
    }
    public function chatlist(){
        $data['title'] = 'Chat List';
        $data['chatData'] = $this->leads_model->get_all_whatsapp_data();
        $this->load->view('admin/whatsapp/chatList', $data);
    }
    public function dmlist(){
        $data['title'] = "Whatsapp DM's List";
        $data['chatData'] = $this->leads_model->get_all_whatsapp_data();
        $this->load->view('admin/whatsapp/dmList', $data);
    }
    public function configuration(){
        $data['departmentData'] = $this->leads_model->getAllDepartments(); 
        $data['title'] = 'Whatsapp-Configuration';
        $this->load->view('admin/whatsapp/configuration', $data);
    }
}

?>
