<?php

class WaServer extends CI_Controller
{
 public function index()
    {
        $this->load->model('leads_model');
        $method = $this->input->method(); 
        if(isset($_POST)){
        // Get the POST data
        $data = $this->input->post();
        // Check if user or customer already exist
        // Create chat list for customer or new contact or create lead
        
        // Extract and prepare the data to insert into the database
        $timestamp = $data['timestamp'];
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        // Convert to desired format, e.g., "Y-m-d H:i:s"
        $leadDate =  $date->format("Y-m-d H:i:s");
        $insert_data = array(
            'name'        => $data['name'],
            'source'      => 3,
            'phonenumber' => $data['from'], //
            'status'      => 2,
            'lastcontact' => $leadDate,
            'dateadded'   => $leadDate
        );
        // Call the model method to insert the data
        $insert_id = $this->leads_model->tech_wizard_insert_lead($insert_data);
        // Check if data was inserted successfully
        if ($insert_id) {
                $response = [
                    'message' => 'Lead data inserted successfully!',
                    'status' => 'success', // Customer status
                ];
                $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200) // HTTP 200 OK
                    ->set_output(json_encode($response));
        } else {
            echo 'Error inserting lead data.';
        }
        }
    }
}
