<?php
if(session_id() === "") session_start();

include_once "../application/config/db.php";

if(isset($_SESSION['unique_id']))
{
	$outgoing_id= $_SESSION['unique_id'];
	unset($_SESSION['unique_id']);
}

//print_r($_POST);
//exit;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$sqlStmt = "SELECT *FROM `it_crm_messages` LEFT JOIN it_crm_leads ON it_crm_leads.client_id=it_crm_messages.outgoing_msg_id WHERE (outgoing_msg_id='{$outgoing_id}' OR incoming_msg_id='{$outgoing_id}') ORDER BY msg_id";

	$query = mysqli_query($conn,$sqlStmt);
	
	if(mysqli_num_rows($query)>0)
	{
		$i=0;
		$chat_array=[];
		$chat_array['client_id']=$outgoing_id;
		while($row=mysqli_fetch_assoc($query))
		{
			//if(empty($row['img'])) $row['img']='no-image.png';

			$chat_array[$i]['msg_type']	=$row['msg_type'];
			$chat_array[$i]['timestamp']=$row['timestamp'];
			$chat_array[$i]['msg']		=$row['msg'];
			$chat_array[$i]['staff_id']	=$row['staff_id'];
			$i++;
		}
		
		$chat_json = json_encode($chat_array);
		
		$sqlStmt= "INSERT INTO `it_crm_chat_archive` (`client_id`, `chat_message`) VALUES ('{$outgoing_id}', '{$chat_json}')";
		
		$sql = mysqli_query($conn, $sqlStmt) or die();
		
		$sqlStmt= "DELETE FROM `it_crm_messages` WHERE outgoing_msg_id='{$outgoing_id}'";
		mysqli_query($conn, $sqlStmt) or die();
	}

    // Get the chat messages from the POST request
    $chatMessages = $_POST['chatMessages'];
	
	if(isset($_POST['issendEmail'])&&$_POST['issendEmail']==true)
	{
		// Your email logic here
		$to = $_SESSION['client_email']; // The email to send to

		$subject = 'Chat Transcript';
		$message = "Here is your chat transcript:\n\n" . $chatMessages;
		$headers = 'From: no-reply@yourdomain.com' . "\r\n" .
				   'Reply-To: no-reply@yourdomain.com' . "\r\n" .
				   'X-Mailer: PHP/' . phpversion();
	
		if (mail($to, $subject, $message, $headers)) {
			echo "Email sent successfully!";
		} else {
			echo "Failed to send email.";
		}
	}
}
mysqli_close($conn);
?>