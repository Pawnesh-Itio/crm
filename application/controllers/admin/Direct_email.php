<?php
//use app\services\imap\Imap;
defined('BASEPATH') or exit('No direct script access allowed');

class Direct_email extends AdminController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $data['title']          = 'Direct E-mail';
        $this->load->view('admin/directemail/email', $data);
    }
    public function sendMail(){
        $email_to=$_POST['email'];
		$email_subject=$_POST['subject'];
		$email_message="<html>".$_POST['message']."</html>";
		$emailArray = explode(";",$email_to);
		$bulkEmails = array_map(function($email) {
			return ['Email' => $email];
		}, $emailArray);
		$response=$this->send_attchment_message1($bulkEmails,$email_subject,$email_message); 
	   $pst['msg'] = "Your message has been successfully sent to " . $email_to;
	   $pst['response'] = $response;
	   echo json_encode($pst);
    }
    function send_attchment_message1($bulkEmails,$email_subject,$email_message){
        $email_from='PAYCLY <info@paycly.com>';
        $email_reply='PAYCLY <info@paycly.com>';
        // TechWizard Logic
        $apiKey = '66D2CD590806CC7B4D826B621729CCDE154FB77DB40D9C50B3ABD1A00E56B1DE5FCB29FD311552A0FCE88D9D1378764F';
        $url = 'https://api.elasticemail.com/v4/emails';
        $postData = [
            'Recipients' => $bulkEmails,
            'Content' => [
                'Body' => [
                    [
                        'ContentType' => 'HTML',
                        'Content' => $email_message,
                        'Charset' => 'utf-8'
                    ]
                ],
                'From' => $email_from,
                'ReplyTo' => $email_reply,
                'Subject' => $email_subject
            ],
            'Options' => [
                'TrackOpens' => true,
                'TrackClicks' => true
            ]
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-ElasticEmail-ApiKey: ' . $apiKey
            ]
        ]);
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response === false) {
            return 'Curl error: ' . $curl_error;
        }
        return 'HTTP Code: ' . $http_code . ' Response: ' . $response;
        // TechWizard logic End
    }
}