<?php

//	require_once "../../../../application/config/db.php";

	$lead_id = e($lead->id);
/*
    $response = '<div class="message-container">';

	$sqlStmt = "SELECT * FROM tblleads WHERE `id` = '$lead_id'";
	$res = mysqli_query($conn, $sqlStmt);
	
	$row = mysqli_fetch_assoc($res);
	$chat_id		= $row['client_id'];
	$description	= $row['description'];
	$dateadded		= $row['dateadded'];

	$response .= '<div class="message outgoing-msg"><p class="message-text">' . ($description) . '</p><span class="send-time">'.date('h:m',strtotime($dateadded)).'</span></div>';

	$sqlStmt = "SELECT * FROM tbltelegram WHERE `lead_id` = '$lead_id' ORDER BY timestamp";
	$chat_res = mysqli_query($conn, $sqlStmt);
	
	while($chat_row = mysqli_fetch_assoc($chat_res))
	{
		$msg_type	= $chat_row['msg_type'];
		$message	= $chat_row['message'];
		$timestamp	= $chat_row['timestamp'];

		if($msg_type==1)
		{
			$response .= '<div class="message incoming-msg"><p class="message-text">' . ($message) . '</p><span class="send-time">'.date('h:m', strtotime($timestamp)).'</span></div>';
		}
		else
		{
			$response .= '<div class="message outgoing-msg"><p class="message-text">' . ($message) . '</p><span class="send-time">'.date('h:m', strtotime($timestamp)).'</span></div>';
		}
	}

    $response .= '</div>';
    // Return the response to the AJAX request
    echo $response;
*/
?>