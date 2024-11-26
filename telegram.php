<?php
// Include the database configuration file
require_once "application/config/db.php";

// Check if we received the 'telegram_message' parameter
if (isset($_POST['telegram_message'])) {

	// Get the message sent from the AJAX request
	$message	= $_POST['telegram_message'];
	$lead_id	= $_POST['lead_id'];
	$staff_user_id= $_POST['staff_user_id'];

	// Process the message here (e.g., send it via Telegram API)
	// For this example, we will just echo back the message as a confirmation
	// You can replace this part with actual Telegram API interaction if needed

	$response = '<div class="message-container">';

	$sqlStmt= "SELECT * FROM `it_crm_leads` WHERE `id` = '$lead_id'";
	$res	= mysqli_query($conn, $sqlStmt);
	$row	= mysqli_fetch_assoc($res);

	$chat_id		= $row['client_id'];
	$description	= $row['description'];
	$dateadded		= $row['dateadded'];

	$response .= '<div class="message outgoing-msg"><p class="message-text">' . ($description) . '</p><span class="send-time">'.date('d-M H:i',strtotime($dateadded)).'</span></div>';

	// Append the new text into tbltelegram (conversion) in the database
	$sqlStmt = "INSERT INTO `tbltelegram` (`lead_id`, `chat_id`, `message`, `msg_type`, `timestamp`, `staff_id`) 
		VALUES ('$lead_id', '$chat_id', '$message', '1', NOW(), $staff_user_id)";
	$res = mysqli_query($conn, $sqlStmt);


	$sqlStmt = "SELECT * FROM `tbltelegram` WHERE `lead_id` = '$lead_id' ORDER BY `timestamp` ASC";
	$chat_res = mysqli_query($conn, $sqlStmt);
	
	$disp_date='';
	while($chat_row = mysqli_fetch_assoc($chat_res))
	{
		$msg_type	= $chat_row['msg_type'];
		$message	= $chat_row['message'];
		$timestamp	= $chat_row['timestamp'];

		$date=date('Y-m-d', strtotime($timestamp));

		if($disp_date!=$date)
		{
			$response .= '<div class="disp_date">' . ($disp_date) . '</div>';
			$disp_date=$date;
		}

		if($msg_type==1)
		{
			$response .= '<div class="message incoming-msg"><p class="message-text">' . ($message) . '</p><span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
		else
		{
			$response .= '<div class="message outgoing-msg"><p class="message-text">' . ($message) . '</p><span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
	}

	//$response .= $start_response.'</div>';
	$response .= '</div>';

	// Return the response to the AJAX request
	echo $response;
} else {


	$lead_id = e($lead->id);

	$response = '<div class="message-container">';

	$sqlStmt = "SELECT * FROM `it_crm_leads` WHERE `id` = '$lead_id'";
	$res = mysqli_query($conn, $sqlStmt);
	
	$row = mysqli_fetch_assoc($res);
	$chat_id		= $row['client_id'];
	$description	= $row['description'];
	$dateadded		= $row['dateadded'];

	$response .= '<div class="message outgoing-msg"><p class="message-text">' . ($description) . '</p><span class="send-time">'.date('d-M H:i',strtotime($dateadded)).'</span></div>';


	$sqlStmt = "SELECT * FROM `tbltelegram` WHERE `lead_id` = '$lead_id' ORDER BY timestamp ASC";
	$chat_res = mysqli_query($conn, $sqlStmt);
	
	$disp_date='';
	while($chat_row = mysqli_fetch_assoc($chat_res))
	{
		$msg_type	= $chat_row['msg_type'];
		$message	= $chat_row['message'];
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
			$response .= '<div class="message incoming-msg"><p class="message-text">' . ($message) . '</p><span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
		else
		{
			$response .= '<div class="message outgoing-msg"><p class="message-text">' . ($message) . '</p><span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
	}

	//$response .= $start_response.'</div>';
	$response .= '</div>';

	// Return the response to the AJAX request
	echo $response;
}
mysqli_close($conn);
?>
</body>

</html>
