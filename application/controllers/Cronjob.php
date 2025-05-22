<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cronjob extends ClientsController
{
    public function index()
    {
        show_404();
    }

    public function download_email_from_cron($id)
    {
	    $data['title'] = _l('Download Email From Cron');
		$this->load->model('webmail_model');
		$data['message']=$this->webmail_model->downloadmail($id);
		//print_r($data['message']);exit;
		$this->load->view('cronjob/download_email_from_cron', $data);
	}

}