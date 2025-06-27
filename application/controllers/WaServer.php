<?php

class WaServer extends CI_Controller
{
    public function index()
    {
        $this->load->model('leads_model');
        $log = [
            'controller' => 'WaServer',
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => $this->input->method(),
            'entry_point' => 'index()'
        ];
        $this->write_log($log);

        try {
            $log['raw_post'] = file_get_contents('php://input');
            $data = $this->input->post();
            $log['parsed_post'] = $data;
            $this->write_log($log);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $log['request_method'] = 'POST';

                // Type 1 - Insert Lead
                if (isset($data['type']) && $data['type'] == 1) {
                    $log['condition'] = 'Type 1 - Insert Lead';

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

                    $insert_data = [
                        'name' => $data['name'],
                        'source' => 3,
                        'phonenumber' => $data['from'],
                        'status' => 2,
                        'lastcontact' => $leadDate,
                        'dateadded' => $leadDate
                    ];
                    $log['prepared_data'] = $insert_data;

                    $insert_id = $this->leads_model->tech_wizard_insert_lead($insert_data);
                    $log['insert_id'] = $insert_id;

                    $response = $insert_id
                        ? ['status' => 'success', 'message' => 'Lead data inserted successfully!']
                        : ['status' => 'error', 'message' => 'Failed to insert lead data.'];

                    $log['final_response'] = $response;
                    $this->write_log($log);

                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($response));
                }

                // Type 2 - Log Lead Activity
                elseif (isset($data['type']) && $data['type'] == 2) {
                    $log['condition'] = 'Type 2 - Log Lead Activity';
                    $log['from'] = $data['from'] ?? null;
                    $this->write_log($log);

                    if (!empty($data['from'])) {
                        try {
                            $lead_record = $this->leads_model->get_lead_by_number($data['from']);
                            if ($lead_record) {
                                $log['lead_found'] = true;
                                $log['lead_record'] = $lead_record;
                                $log['LeadID'] = $lead_record['id'];
                                 $this->write_log($log);
                                $SaveLog = $this->leads_model->log_lead_activity($lead_record['id'], "New_Message", false);
                                $log['activity_log'] = $SaveLog;
                                $this->write_log($log);
                            } else {
                                $log['lead_found'] = false;
                                $this->write_log($log);
                            }
                        } catch (Exception $e) {
                            $log['exception'] = $e->getMessage();
                            $this->write_log($log);
                        }
                    } else {
                        $log['error'] = 'Missing `from` value in Type 2 request.';
                        $this->write_log($log);
                    }

                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode(['status' => 'received']));
                }

                // Unknown type
                else {
                    $log['condition'] = 'Unknown type';
                    $log['error'] = 'Invalid or missing `type` value.';
                    $this->write_log($log);

                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(400)
                        ->set_output(json_encode(['status' => 'error', 'message' => 'Invalid request type']));
                }
            }

            // Invalid method
            else {
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
