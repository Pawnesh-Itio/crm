<?php

class WaServer extends CI_Controller
{
    public function index()
    {
        $this->load->model('leads_model');
        $log = []; // array to collect logs

        try {
            $log['timestamp'] = date('Y-m-d H:i:s');
            $log['method'] = $this->input->method();

            // Log raw request
            $log['raw_post'] = file_get_contents('php://input');

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $data = $this->input->post();
                $log['parsed_post'] = $data;

                // Validate required fields
                if (!isset($data['timestamp'], $data['name'], $data['from'])) {
                    $log['error'] = 'Missing required POST fields.';
                    $this->write_log($log);
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(400)
                        ->set_output(json_encode(['status' => 'error', 'message' => 'Missing required fields']));
                }

                $timestamp = $data['timestamp'];
                $date = new DateTime();
                $date->setTimestamp($timestamp);
                $leadDate = $date->format("Y-m-d H:i:s");

                $insert_data = array(
                    'name'        => $data['name'],
                    'source'      => 3,
                    'phonenumber' => $data['from'],
                    'status'      => 2,
                    'lastcontact' => $leadDate,
                    'dateadded'   => $leadDate
                );
                $log['prepared_data'] = $insert_data;

                $insert_id = $this->leads_model->tech_wizard_insert_lead($insert_data);
                $log['insert_id'] = $insert_id;

                if ($insert_id) {
                    $response = ['status' => 'success', 'message' => 'Lead data inserted successfully!'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Failed to insert lead data.'];
                }

                $log['final_response'] = $response;
                $this->write_log($log);

                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($response));
            } else {
                $log['error'] = 'Invalid request method.';
                $this->write_log($log);
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(405)
                    ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request method']));
            }

        } catch (Exception $e) {
            $log['exception'] = $e->getMessage();
            $this->write_log($log);

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['status' => 'error', 'message' => 'Server error occurred.']));
        }
    }

    private function write_log($logArray)
    {
        $logText = json_encode($logArray, JSON_PRETTY_PRINT);
        $logFile = APPPATH . 'logs/wa_server_log_' . date('Y-m-d') . '.log';
        file_put_contents($logFile, $logText . PHP_EOL . str_repeat('-', 100) . PHP_EOL, FILE_APPEND);
    }
}
