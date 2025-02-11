<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPPATH.'/vendor/vendor/autoload.php';

class Webmail_model extends App_Model
{
    

    public function __construct()
    {
        parent::__construct();
    }

 
	   // function for get inbox mail list
        public function getinboxemail()
        {
		
		//print_r($_SESSION['webmail']);exit;
		
		$mailer_imap_host=$_SESSION['webmail']['mailer_imap_host'];
        $mailer_imap_port=$_SESSION['webmail']['mailer_imap_port'];
        $mailer_username=$_SESSION['webmail']['mailer_username'];
        $mailer_password=$_SESSION['webmail']['mailer_password'];
		$encryption=$_SESSION['webmail']['encryption'];
		
		
		if((isset($_GET['fd'])&&$_GET['fd'])){
		$_SESSION['webmail']['folder']=$_GET['fd'];
		$_SESSION['inbox-total-email']="";
		$_SESSION['outbox-total-email']="";
		redirect(admin_url('webmail/inbox'));
		}elseif($_SESSION['webmail']['folder']==""){
		$_SESSION['webmail']['folder']="INBOX";
		redirect(admin_url('webmail/inbox'));
		}
		$folder=$_SESSION['webmail']['folder'];
		
		//exit;
		
		
		try {
		 
		 $cm = new ClientManager();

    // Define the IMAP connection settings
    $client = $cm->make([
        'host'          => $mailer_imap_host,
        'port'          => $mailer_imap_port,
        'encryption'    => $encryption,
        'validate_cert' => true,
        'username'      => $mailer_username,
        'password'      => $mailer_password,
        'protocol'      => 'imap', 
		//'authentication' => "oauth"            // Protocol (imap/pop3)
    ]);
	
	
	if ($client->connect()) {
       //echo "Connected to IMAP server successfully!";
	 $folderList = []; // Initialize an empty array  
	 $subfolderList = []; // Initialize an empty array 
	   // Get a list of mail folders
    $folderslist = $client->getFolders();
	//print_r($folderslist);exit;
	//$_SESSION['folderlist'] = !empty($_SESSION['folderlist']) ? $_SESSION['folderlist'] : "";
	//if(empty($_SESSION['folderlist'])){
	
	$subfolders="";
		foreach ($folderslist as $flist) {
		//print_r($folderslist);exit;
		 $folderList[]=$flist->name;
		 $subfolders = $flist->getChildren();
		 if($subfolders<>"[]"){
		 // Decode JSON string into an associative array
			$data = json_decode($subfolders, true);
			//print_r($data);
			
			if ($data) {
				foreach ($data as $item) {
					//echo $item['name'] . "\n";
					$subfolderList[$flist->name][]=$item['name'];
				}
			} else {
				echo "Invalid JSON data.";
			}
		 //echo $subfolders;
		 }
		}
		$_SESSION['folderlist']=$folderList;
		$_SESSION['subfolderlist']=$subfolderList;
	//}
      //print_r($_SESSION['folderlist']);exit;
    
	   
	    // Get the inbox folder
      $inbox = $client->getFolder($folder);
      	  
	  if ($inbox === null) {
      die("The 'Sent' folder could not be found.");
      }
      // Query to fetch emails
      //$messages = $inbox->query()->all()->get();  // Fetch all messages
	 
	  
	  $limit=30;
	  if(isset($_GET["page"])){ $pn = $_GET["page"]; }else{ $pn=1;};
	  $messages = $inbox->query()->all()->setFetchOrder("desc")->paginate($per_page = $limit, $page = $pn, $page_name = 'imap_page');  // Fetch all messages
	  
	  
	  // Sort messages by descending date
		$sortedMessages = $messages->sortByDesc(function ($message) {
			return $message->getDate();
		});

    
			//$paginator = $sortedMessages->paginate($per_page = $limit, $page = $pn, $page_name = 'sent.php');
			// Get the total number of messages
			$_SESSION['inbox-total-email']=$total_records = $messages->total();
			// Display the fetched emails
			$cnt=101;
			return $sortedMessages;
	  
	  }
	
   
	
		} catch (Exception $e) {
		//echo "ERROR 102";exit;
			echo "Error: " . $e->getMessage();exit;
			}
		exit;
	
       //echo "ERROR 103";exit;
        //return $this->db->get(db_prefix().'webmail_setup')->result_array();
      }
	  
	 
	
	   
	
	public function getemaillist($id = '', $where = [])
    {
        $this->db->select('id,mailer_email,');
        $this->db->where($where);
        $this->db->order_by('id', 'asc');
		$this->db->group_by('mailer_email');
		//$this->db->limit(1);
         return $this->db->get(db_prefix() . 'webmail_setup')->result_array();
		// echo $this->db->last_query();
    }
	
	 public function webmailsetup($id = '', $where = [])
    {
        $this->db->select('*,');
        $this->db->where($where);
        $this->db->order_by('id', 'asc');
		$this->db->limit(1);
        return $this->db->get(db_prefix() . 'webmail_setup')->result_array();
		//return 
		//echo $this->db->last_query();
    }
	
	 public function departmentid($id = '', $where = [])
    {
	
	
        $this->db->select('departmentid,');
        $this->db->where($where);
        //$this->db->order_by('id', 'asc');
		$this->db->limit(1);
        return $this->db->get(db_prefix() . 'staff_departments')->result_array();
		//return 
		//echo $this->db->last_query();
    }

     public function reply($data, $id = '' )
    {
	
	//print_r($data);
	
		$recipientEmail=$_POST['recipientEmail'];
		if(preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $recipientEmail, $matches)){
		$recipientEmail = $matches[0] ?? 'Email not found';
		}
		if(isset($_POST['recipientCC']) && $_POST['recipientCC'])
		{
			$recipientCC=$_POST['recipientCC'];
			if(preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $recipientCC, $matches)){
			$recipientCC = $matches[0] ?? 'Email not found';
			}
		}
		//echo $recipientEmail;exit;
		// Form Post Data
		$recipientEmail;
		$subject=$_POST['emailSubject'];
		$body=$_POST['emailBody'];
		$redirect=$_POST['redirect'];
		// SMTP Details from session
		$mailer_smtp_host=$_SESSION['webmail']['mailer_smtp_host'];
        $mailer_smtp_port=$_SESSION['webmail']['mailer_smtp_port'];
        $mailer_username=$_SESSION['webmail']['mailer_username'];
        $mailer_password=$_SESSION['webmail']['mailer_password'];
		$senderEmail=$_SESSION['webmail']["mailer_email"];
		$senderName=$_SESSION['webmail']["mailer_name"];
		$mail = new PHPMailer(true);
		
		
	try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = $mailer_smtp_host; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = $mailer_username; // Replace with your email
    $mail->Password = $mailer_password; // Replace with your email password or app-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $mailer_smtp_port;

    // Email settings
	$mail->isHTML(true); // Set email format to plain text
	$mail->WordWrap = 50;               // set word wrap
    $mail->Priority = 1; 
    $mail->setFrom($senderEmail, $senderName);
    $mail->addAddress($recipientEmail);
	if(isset($recipientCC)&&$recipientCC<>""){$mail->AddCC($recipientCC);}
    $mail->Subject = $subject;
    $mail->Body = $body;
	
	// Attached 1 file
    if (isset($_FILES['attachment1']) && $_FILES['attachment1']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath1 = $_FILES['attachment1']['tmp_name'];
        $fileName1 = $_FILES['attachment1']['name'];
        $mail->addAttachment($fileTmpPath1, $fileName1); // Attach the uploaded file
	}

	// Attached 2 file
    if (isset($_FILES['attachment2']) && $_FILES['attachment2']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath2 = $_FILES['attachment2']['tmp_name'];
        $fileName2 = $_FILES['attachment2']['name'];
        $mail->addAttachment($fileTmpPath2, $fileName2); // Attach the uploaded file
	}
	// Attached 3 file
    if (isset($_FILES['attachment3']) && $_FILES['attachment3']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath3 = $_FILES['attachment3']['tmp_name'];
        $fileName3 = $_FILES['attachment3']['name'];
        $mail->addAttachment($fileTmpPath3, $fileName3); // Attach the uploaded file
	}


    $mail->send();
    //echo "Email sent successfully!";
	log_activity('Email Reply With Subject Line -  [ Subject: ' . $subject . ']');
    return true;
	} catch (Exception $e) {
		//echo "Email could not be sent. Error: {$mail->ErrorInfo}";
		return false;
	}
	
	
	
	
	
	}
	 
   

   

    
    
}
