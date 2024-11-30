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
    public function configuration(){
        // if (!is_admin()) {
        //     access_denied('Customer Groups');
        // }
        // if ($this->input->is_ajax_request()) {
        //     $this->app->get_table_data('customers_groups');
        // }
        $data['title'] = 'Whatsapp-Configuration';
        $this->load->view('admin/whatsapp/configuration', $data);
    }
}

?>
