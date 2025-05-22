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
        public function getinboxemail_old()
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
	  
	  //for Delete
	  if(isset($_GET['stype'])&&!empty($_GET['stype'])&&isset($_GET['skey'])&&!empty($_GET['skey'])){
	  
	  }
      	  
	  if ($inbox === null) {
      die("The 'Sent' folder could not be found.");
      }
     
	   
	  $limit=20;
	  if(isset($_GET["page"])){ $pn = $_GET["page"]; }else{ $pn=1;};
		
		if(isset($_GET['stype'])&&!empty($_GET['stype'])&&isset($_GET['skey'])&&!empty($_GET['skey'])){
		$stype=trim($_GET['stype']);
		$skey=trim($_GET['skey']);
		
		  $messages = $inbox->query()->where($stype , $skey)->all()->setFetchOrder("desc")->paginate($per_page = $limit, $page = $pn, $page_name = 'imap_page');  // Fetch with search data
		}else{
		
		if($_SESSION['messageorder']==1){
		$since = (new DateTime())->modify('-2 days')->format('d.m.Y');
		$messages = $inbox->query()->since($since)->setFetchOrder("desc")->paginate($per_page = $limit, $page = $pn, $page_name = 'imap_page');  // Fetch Last 2 days
		}else{
		$messages = $inbox->query()->all()->setFetchOrder("desc")->paginate($per_page = $limit, $page = $pn, $page_name = 'imap_page')->filter(function($messages) {
    return $messages->getUid() > 370;
});  // Fetch all messages
		}
		//echo $searchmain;exit;
		 
		}
		
		//print_r($messages);exit;
		$client->disconnect();
	  
	 
	 
	  
	  
	  // Sort messages by descending date
		$sortedMessages = $messages->sortByDesc(function ($message) {
			return $message->getDate();
		});

    
			//$paginator = $sortedMessages->paginate($per_page = $limit, $page = $pn, $page_name = 'sent.php');
			// Get the total number of messages
			$_SESSION['inbox-total-email']=200;//$total_records = $messages->total();
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
	  
	// function for get inbox mail list
     public function getinboxemail()
     {
	 //$_SESSION['webmail']="";
	 $mailer_email=$_SESSION['webmail']['mailer_email'];
	 
	 /// For change folder
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
		
		$page=$_GET["page"]?? 1;//exit;
		if($page==1){
		$page=0;
		}else{
		$page=($page-1) * $_SESSION['mail_limit'];
		}
	
	    if(isset($mailer_email)&&$mailer_email&&isset($folder)&&$folder){
	  
	    $this->db->select('folder,');
        $this->db->where('email', $mailer_email);
        $this->db->order_by('id', 'asc');
		$this->db->group_by('folder');
		//$this->db->limit(1);
        $_SESSION['folderlist']=$this->db->get(db_prefix() . 'emails')->result_array();
		//echo $this->db->last_query();exit;


        ///////////////////////////Search Query//////////////
		$search=0;
		if(isset($_GET['stype'])&&!empty($_GET['stype'])&&isset($_GET['skey'])&&!empty($_GET['skey'])){
		$stype=trim($_GET['stype']);
		if($stype=="TEXT"){$stype="body";}
		$skey=trim($_GET['skey']);
		$search=1;
		}
		///////////////////////////END Search Query//////////////
		
		
		///////////////////////////Count Total Email BY Folder//////////////
		$this->db->select('COUNT(*) AS total_email');
		if($search==1){$this->db->or_like($stype, $skey);}
        $this->db->where('email', $mailer_email);
		$this->db->where('folder', $folder);
        $counter=$this->db->get(db_prefix() . 'emails')->result_array(); //return
		$_SESSION['inbox-total-email']=$counter[0]['total_email'];
		//echo $this->db->last_query();
		//print_r($counter);exit;
		///////////////////////////END Count Total Email BY Folder//////////////
		 
		
		///////////////////////////Fetch Email//////////////
		$this->db->select('*,');
		if($search==1){$this->db->or_like($stype, $skey);}
        $this->db->where('email', $mailer_email);
		$this->db->where('folder', $folder);
        $this->db->order_by('uniqid', 'desc');
		$this->db->group_by('uniqid');
		$this->db->limit($_SESSION['mail_limit'],$page);
        return $this->db->get(db_prefix() . 'emails')->result_array(); //return
		//echo $this->db->last_query();exit;
		///////////////////////////END Fetch Email//////////////
		
	  
	  }
	  
	   	
	
	
		
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
	$mail->CharSet = 'UTF-8';
	$mail->Encoding = 'base64';
	$mail->WordWrap = 50;               // set word wrap
	$mail->Priority = 1; 
	$mail->setFrom($senderEmail, $senderName);
	$mail->addAddress($recipientEmail);
	if (isset($recipientCC) && $recipientCC != "") {
		$mail->addCC($recipientCC);
	}
	// Add hardcoded BCC
	$mail->addBCC('onboarding@paycly.com');
	$mail->Subject = $subject;
	$mail->Body = $body;
	
	 $files = $_FILES['attachments'];
	// Handle Multiple File Attachments
        if (!empty($files['name'][0])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $fileTmpPath = $files['tmp_name'][$i];
                $fileName = $files['name'][$i];
                $fileType = $files['type'][$i];
                $fileError = $files['error'][$i];
                
                if ($fileError === 0) {
                    $mail->addAttachment($fileTmpPath, $fileName);
                }
            }
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
	 
   
   
   // function for get inbox mail list
        public function downloadmail($id)
        {
		
		if(isset($id)&&$id){
		$mailers=$this->webmail_model->get_imap_details($id);
		}
		
		if(empty($mailers)){ 
		$downloadMessages="Email SMTP Details Not Found !!";
		return $downloadMessages;
		exit;
		
		}
		
	
		
		
		//print_r($_SESSION['webmail']);exit;
		
		$mailer_imap_host=trim($mailers[0]['mailer_imap_host']);
        $mailer_imap_port=trim($mailers[0]['mailer_imap_port']);
        $mailer_username=trim($mailers[0]['mailer_username']);
		$data['email']=trim($mailers[0]['mailer_username']);
        $mailer_password=trim($mailers[0]['mailer_password']);
		$encryption=trim($mailers[0]['encryption']);
		
		
		
		
		
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
	
	
	
$folders = $client->getFolders();
$cnt=0;
foreach ($folders as $folder) {
    $folder=$folder->name; // e.g., INBOX, Sent, Trash
	//$folder="Sent"; 
	$mailbox = $client->getFolder($folder);
	 if ($mailbox === null) {
      die("The ".$folder." folder could not be found.");
      }
	  $data['folder']=$folder;


     
	  $total_Email=$mailbox->query()->all()->count();
	  $last_email_id=$this->webmail_model->lastemailid($mailer_username, $folder);
	  $last_email_id=$last_email_id[0]['uniqid']?? 0;//exit;
	 
	   
	  if($last_email_id==0){
	  //echo "Folder - ".$folder." Last Email ID = ".$last_email_id." LIMIT = ".$limit;exit;
/*	  $limit=2000;
	  if(isset($_GET["page"])){ $pn = $_GET["page"]; }else{ $pn=1;};
	  
	  $messages = $mailbox->query()->all()->setFetchOrder("desc")->paginate($per_page = $limit, $page = $pn, $page_name = 'imap_page')->filter(function($messages) use ($last_email_id) {
    return $messages->getUid() > $last_email_id;
});*/
      $pg=floor($last_email_id / 1000) +1;
	  $messages = $mailbox->query()
    ->all()->limit($limit = 1000, $page = $pg)
    ->get() // fetch messages
    ->filter(function($message) use ($last_email_id) {
        return $message->getUid() > $last_email_id;
    });

      }else{
	  //echo "Folder - ".$folder." Last Email ID = ".$last_email_id;
	  $pg=floor($last_email_id / 100) +1;
	  $messages = $mailbox->query()
    ->all()->limit($limit = 100, $page = $pg)
    ->get() // fetch messages
    ->filter(function($message) use ($last_email_id) {
        return $message->getUid() > $last_email_id;
    });
	  }


//print_r($messages);exit;
foreach ($messages as $message) {

    $data['subject'] = $message->getSubject();
    $data['date'] = $message->getDate(); //->format('Y-m-d H:i:s')
    $data['body'] = $message->getHtmlBody() ?? '';
	if($data['body']==""){$data['body'] = $message->getTextBody() ?? ''; }
	$data['uniqid'] = $message->uid;
	
	 // From
    $from = $message->getFrom(); // Returns array of Address objects
    $data['from_email'] = $from[0]->mail ?? '';
    $data['from_name']  = $from[0]->personal ?? '';
	//print_r($from);
	//echo "<br><br>";
	// To
  
	
    $to_list = $message->getTo(); // Returns array of Address objects
    $data['to_emails'] = $to_list[0]->mail ?? '';
   
	
	
   
    $cc_list = $message->getCc(); // Returns array of Address objects
    $data['cc_emails'] = $cc_list[0]->mail ?? '';
	
	
  
    // BCC
	$bcc_list = $message->getBcc(); // Returns array of Address objects
    $data['bcc_emails'] = $bcc_list[0]->mail ?? '';


    // Handle attachments
    $attachments_paths = [];
    $data['isattachments']=0;
	$uid=uniqid();
	$attachmentDir = 'attachments';
	$filePath = $attachmentDir . '/' . $uid;
    foreach ($message->getAttachments() as $attachment) {
    $attachments = $message->getAttachments();
		
		// Create directory if it doesn't exist
					
		foreach ($attachments as $attachment) {
		
		if (!file_exists($filePath)) {
		mkdir($filePath, 0777, true);
		}	
				
		$fileName = $attachment->name;
		// Save the attachment
		$attachment->save($filePath);
		$data['isattachments']=1;
		$attachments_paths[] = $filePath."/".$fileName;
		}
		$data['attachments'] = implode(',', $attachments_paths);//exit;
 }
 $cnt++;
		$this->db->insert(db_prefix() . 'emails', $data);
		$this->db->last_query();
 
}
//exit;	  
  
	  
}
    
$client->disconnect();	   
	    // Get the inbox folder
      

	        $sortedMessages="Total Added :-".$cnt;
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
	  


    public function lastemailid($email, $folder)
    {
        $this->db->select('uniqid,');
		$this->db->where('email', $email);
        $this->db->where('folder', $folder);
        $this->db->limit(1);
		$this->db->order_by('uniqid', 'DESC');
        return $this->db->get(db_prefix() . 'emails')->result_array(); //return 
		
		
    }
	
	 public function get_imap_details($id)
    {
        $this->db->select('mailer_username,mailer_password,mailer_imap_host,mailer_imap_port,encryption,');
        $this->db->where('id', $id);
        $this->db->limit(1);
       return $this->db->get(db_prefix() . 'webmail_setup')->result_array(); //return 
		//echo $this->db->last_query();exit;
		
    }
   
    public function make_isflag($mid,$fid)
    {
	    $data['isfalg']=$fid;
        $this->db->where('id', $mid);
		$this->db->update(db_prefix() . 'emails', $data);
        if ($this->db->affected_rows() > 0) {
		return 1;
		}else{
		return 0;
		}
        
		
    }
    
    
}
