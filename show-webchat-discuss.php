<?php
// Include the database configuration file
require_once "application/config/db.php";


// Check if we received the 'telegram_message' parameter
if (isset($_POST['message'])) {

	// Get the message sent from the AJAX request
	$message	= $_POST['message'];
	$chat_id	= $_POST['chat_id'];
	$staff_user_id= $_POST['staff_id'];

	// Process the message here (e.g., send it via webchat)
	// For this example, we will just echo back the message as a confirmation

//	$response = '<div class="message-container">';
	$response = '';
	$sqlStmt= "SELECT * FROM `it_crm_leads` WHERE `client_id` = '$chat_id'";
	$res	= mysqli_query($conn, $sqlStmt);
	$row	= mysqli_fetch_assoc($res);

	$lead_id		= $row['id'];
	$description	= $row['description'];
	$dateadded		= $row['dateadded'];
	$assigned		= $row['assigned'];
	$dateassigned	= $row['dateassigned'];

	if(isset($description)&&$description)
	{
		$response .= '<div class="incoming-message">' . ($description) . '<span class="send-time">'.date('d-M H:i',strtotime($dateadded)).'</span></div>';
	}

	// Append the new text into tbltelegram (conversion) in the database
	$sqlStmt = "INSERT INTO `it_crm_messages` (`incoming_msg_id`, `outgoing_msg_id`, `msg`, `msg_type`, `timestamp`, `staff_id`) 
		VALUES ('$lead_id', '$chat_id', '$message', 1, NOW(), $staff_user_id)";
	$res = mysqli_query($conn, $sqlStmt);

	if(empty($assigned))
	{
		$sqlStmt = "UPDATE `it_crm_leads` SET `assigned`='$staff_user_id', dateassigned=NOW() WHERE id='$lead_id'";
		$res = mysqli_query($conn, $sqlStmt);
	}

	$sqlStmt = "SELECT * FROM `it_crm_messages` WHERE `outgoing_msg_id` = '$chat_id' ORDER BY `timestamp` ASC";
	$chat_res = mysqli_query($conn, $sqlStmt);
	
	$disp_date='';
	while($chat_row = mysqli_fetch_assoc($chat_res))
	{
		$msg_type	= $chat_row['msg_type'];
		$message	= $chat_row['msg'];
		$timestamp	= $chat_row['timestamp'];

		$date=date('Y-m-d', strtotime($timestamp));

		if($disp_date!=$date)
		{
			if($date==date('Y-m-d'))
				$dt_display= 'Today';
			else
				$dt_display= date('d-M', strtotime($date));

			$response .= '<div class="disp_date">' . ($dt_display) . '</div>';
			$disp_date=$date;
		}

		if($msg_type==1)
		{
			$response .= '<div class="sent-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
		else
		{
			$response .= '<div class="incoming-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
	}

	//$response .= $start_response.'</div>';
//	$response .= '</div>';

	// Return the response to the AJAX request
	echo $response;
}
else
{
	if(isset($_POST['chat_id'])&&$_POST['chat_id'])
	{
		$chat_id	= $_POST['chat_id'];
	
		// Process the message here (e.g., send it via Telegram API)
		// For this example, we will just echo back the message as a confirmation
		// You can replace this part with actual Telegram API interaction if needed
	
		$response = '';
		$sqlStmt= "SELECT * FROM `it_crm_leads` WHERE `client_id` = '$chat_id'";
		$res	= mysqli_query($conn, $sqlStmt);
		$row	= mysqli_fetch_assoc($res);
	
		$lead_id		= $row['id'];
		$description	= $row['description'];
		$dateadded		= $row['dateadded'];
	
		if(isset($description)&&$description)
		{
			$response .= '<div class="incoming-message">' . ($description) . '<span class="send-time">'.date('d-M H:i',strtotime($dateadded)).'</span></div>';
		}

		$sqlStmt = "SELECT * FROM `it_crm_messages` WHERE `incoming_msg_id` = '$lead_id' ORDER BY `timestamp` ASC";
		$chat_res = mysqli_query($conn, $sqlStmt);
		
		$disp_date='';
		while($chat_row = mysqli_fetch_assoc($chat_res))
		{
			$msg_id		= $chat_row['msg_id'];
			$msg_type	= $chat_row['msg_type'];
			$message	= $chat_row['msg'];
			$timestamp	= $chat_row['timestamp'];
	
			$date=date('Y-m-d', strtotime($timestamp));
	
			if($disp_date!=$date)
			{
				if($date==date('Y-m-d'))
					$dt_display= 'Today';
				else
					$dt_display= date('d-M', strtotime($date));
	
				$response .= '<div class="disp_date">' . ($dt_display) . '</div>';
				$disp_date=$date;
			}

			if($msg_type==1)
			{
				$response .= '<div class="sent-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
			}
			else
			{
				$response .= '<div class="incoming-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
			}
		}
	
		// Return the response to the AJAX request
		echo $response;
	}
} 
mysqli_close($conn);
?>