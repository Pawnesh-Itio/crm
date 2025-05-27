<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Telegram extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model');
        $this->load->model('telegram_model');
    }
    public function configuration()
    {
        // Fetch all Telegram configurations
        $data['configurationData'] = $this->telegram_model->getAllTelegramConfigurations();
        $data['departmentData'] = $this->leads_model->getAllDepartments();
        $data['title'] = 'Telegram-Configuration';
        $this->load->view('admin/telegram/configuration', $data);
    }
    public function add_configuration()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            if (isset($data['type']) && $data['type'] == 1) {
                $data['staff_ids'] = '';
            } elseif (isset($data['type']) && $data['type'] == 2) {
                $data['department_id'] = 0;
            } else {
                $data['department_id'] = 0;
                $data['staff_ids'] = '';
                redirect(admin_url('telegram/configuration'));
                set_alert('warning','Please select a type');
            }
            if (isset($data['department_id']) && $data['department_id'] == 0) {
                $data['department_id'] = '';
                set_alert('warning','Please select a department');
            }
            // Check if the name or username already exists
            $existingConfig = $this->telegram_model->getTelegramConfigurationByNameOrUsername($data['name'], $data['username']);
            if ($existingConfig) {
                set_alert('warning', 'Configuration with this name or username already exists.');
                redirect(admin_url('telegram/configuration'));
            }
            // Delete the webhook if it exists
            $url = "https://api.telegram.org/bot" . $data['telegram_token'] . "/deleteWebhook";
            // Make the API call to delete the webhook
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            // Check if the response indicates success
            if ($response === false) {
                set_alert('danger', 'Failed to delete existing webhook.');
                redirect(admin_url('telegram/configuration'));
            }
            // Add new webhook URL
            $webhookUrl = base_url('import-telegram.php?bot=' . urlencode($data['telegram_name']));
            $url = "https://api.telegram.org/bot" . $data['telegram_token'] . "/setWebhook?url=" . urlencode($webhookUrl);
            // Make the API call to set the webhook
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            if ($response === false) {
                set_alert('danger', 'Failed to set webhook.');
                redirect(admin_url('telegram/configuration'));
            }   
            $data['webhook'] = $webhookUrl;
           $addConfig = $this->telegram_model->addTelegramConfiguration($data);
           if ($addConfig) {
            set_alert('success', 'Configuration added successfully.');
            redirect(admin_url('telegram/configuration'));
            } else {
                redirect(admin_url('telegram/configuration'));
            }
        } 
    }
    public function delete_configuration($id)
    {
        // Check if the ID is valid
        if (is_numeric($id) && $id > 0) {
            // delete the webhook using the Telegram API
            $config = $this->telegram_model->getTelegramConfigurationById($id);
            if (!$config) {
                set_alert('danger', 'Configuration not found.');
                redirect(admin_url('telegram/configuration'));
            }
            $url = "https://api.telegram.org/bot" . $config['telegram_token'] . "/deleteWebhook";
            // Make the API call to delete the webhook
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            // Check if the response indicates success
            if ($response === false) {
                set_alert('danger', 'Failed to delete existing webhook.');
                redirect(admin_url('telegram/configuration'));
            }
            // Delete the configuration by ID
            $delete = $this->telegram_model->deleteTelegramConfiguration($id);
            if ($delete) {
                set_alert('success', 'Configuration deleted successfully.');
            } else {
                set_alert('danger', 'Failed to delete configuration.');
            }
        } else {
            set_alert('danger', 'Invalid configuration ID.');
        }
        redirect(admin_url('telegram/configuration'));
    }
}