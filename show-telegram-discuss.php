<?php
// Include the database configuration file
require_once "application/config/db.php";

	$token = $_POST['telegram_token'] ?? "YOUR_DEFAULT_TOKEN";
	$chat_id = $_POST['chat_id'];
	// Preprare the response.
	$response = '';
	$sqlStmt= "SELECT * FROM `it_crm_leads` WHERE `client_id` = '$chat_id'";
	$res	= mysqli_query($conn, $sqlStmt);
	$row	= mysqli_fetch_assoc($res);

	$lead_id		= $row['id'];
	$description	= $row['description'];
	$dateadded		= $row['dateadded'];
	$response .= '<div class="message incoming-message">' . ($description) . '<span class="send-time">'.date('d-M H:i',strtotime($dateadded)).'</span></div>';

// Check if we received the 'telegram_message' parameter
if (isset($_POST['telegram_message'])) {
	$staff_user_id = $_POST['staff_id'];
	// Get the message sent from the AJAX request
	$message	= $_POST['telegram_message'];
	// Append the new text into tbltelegram (conversion) in the database
	$sqlStmt = "INSERT INTO `tbltelegram` (`lead_id`, `chat_id`, `message`, `msg_type`, `timestamp`, `staff_id`) 
		VALUES ('$lead_id', '$chat_id', '$message', '1', NOW(), $staff_user_id)";
	$res = mysqli_query($conn, $sqlStmt);
	if(mysqli_affected_rows($conn)>0)
	{
		// URL encode the message to ensure it's safe for a URL
		$text = urlencode($message); // The message to send
		// Build the Telegram API URL for sending the message
		$url = "https://api.telegram.org/bot$token/sendMessage?text=$text&chat_id=$chat_id";
		// Initialize cURL to make a request to the Telegram API
		$ch = curl_init();
		// Set the necessary cURL options
		curl_setopt($ch, CURLOPT_URL, $url); // The URL to make the request to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set to true to return the response as a string
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if necessary
		// Execute the cURL request and store the response (although we don't use it here)
		curl_exec($ch);	
	}
	// Fetch the chat history from the database and display it
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
			if($date==date('Y-m-d'))
				$dt_display= 'Today';
			else
				$dt_display= date('d-M', strtotime($date));

			$response .= '<div class="disp_date">' . ($dt_display) . '</div>';
			$disp_date=$date;
		}
		//Media Message Handling 
		if(!empty($chat_row['file_path'])){
			$message = '
				<div style="text-align: center;">
					<img src="'.$chat_row['file_path'].'" alt="Image from Telegram" width="300">
					<p style="margin-top: 5px; font-weight: bold;">'.$chat_row['message'].'</p>
				</div>';
		}
		if($msg_type==1)
		{   
			$response .= '<div class="message  sent-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
		else
		{
			$response .= '<div class="message incoming-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
	}
	echo $response;
	exit;
} elseif(isset($_FILES['media'])){
	$staff_user_id = $_POST['staff_id'];
	$caption = $_POST['caption'] ?? '';
	$file = $_FILES['media'];
	$file_path = $file['tmp_name'];
	$file_name = $file['name'];
	$url = "https://api.telegram.org/bot$token/sendPhoto";
	$post_fields = [
		'chat_id' => $_POST['chat_id'],
		'caption' => $caption,
		'photo' => new CURLFile($file_path, mime_content_type($file_path), $file_name)
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $response_data = curl_exec($ch);
    curl_close($ch);
	// Check if the response is successful
	if ($response === false) {
		
	} else {
		// Decode the JSON response
		$response_data = json_decode($response_data, true);
		if ($response_data['ok']) {
			// Get the file ID from the response
			$largestFileId = '';
			$maxSize = 0;
			// Loop through photo sizes
				foreach ($response_data['result']['photo'] as $photo) {
					if ($photo['file_size'] > $maxSize) {
						$maxSize = $photo['file_size'];
						$largestFileId = $photo['file_id'];
					}
				}
				$getFileUrl = "https://api.telegram.org/bot$token/getFile?file_id=$largestFileId";
				$response_data = file_get_contents($getFileUrl);
				$result = json_decode($response_data, true);
				if ($result['ok']) {
				$filePath = $result['result']['file_path'];
				$imageUrl = "https://api.telegram.org/file/bot$token/$filePath";
				}
			$sqlStmt = "INSERT INTO `tbltelegram` (`lead_id`, `chat_id`, `message`, `msg_type`, `timestamp`, `staff_id`, `file_path`) 
				VALUES ('$lead_id', '$chat_id', '$caption', '1', NOW(), $staff_user_id, '$imageUrl')";
			$res = mysqli_query($conn, $sqlStmt);
			if ($res) {
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
						if($date==date('Y-m-d'))
							$dt_display= 'Today';
						else
							$dt_display= date('d-M', strtotime($date));

						$response .= '<div class="disp_date">' . ($dt_display) . '</div>';
						$disp_date=$date;
					}
					//Media Message Handling 
					if(!empty($chat_row['file_path'])){
									$message = '
				<div style="text-align: center;">
					<img src="'.$chat_row['file_path'].'" alt="Image from Telegram" width="300">
					<p style="margin-top: 5px; font-weight: bold;">'.$chat_row['message'].'</p>
				</div>';
					}
					if($msg_type==1)
					{   

						$response .= '<div class="message  sent-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
					}
					else
					{
						$response .= '<div class="message incoming-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
					}
				}
				echo $response;
				exit;
			}
		} else {
			
		}
	}

}elseif(isset($_FILES['document'])){
	$staff_user_id = $_POST['staff_id'];
	$caption = $_POST['caption'] ?? '';
	$file = $_FILES['document'];
	$file_path = $file['tmp_name'];
	$file_name = $file['name'];
	$url = "https://api.telegram.org/bot$token/sendDocument";
	$post_fields = [
		'chat_id' => $_POST['chat_id'],
		'caption' => $caption,
		'document' => new CURLFile($file_path, mime_content_type($file_path), $file_name)
	];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type:multipart/form-data"]);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	$response = curl_exec($ch);
	curl_close($ch);
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
	
		$response .= '<div class="message incoming-message">' . ($description) . '<span class="send-time">'.date('d-M H:i',strtotime($dateadded)).'</span></div>';
	
		$sqlStmt = "SELECT * FROM `tbltelegram` WHERE `lead_id` = '$lead_id' ORDER BY `timestamp` ASC";
		$chat_res = mysqli_query($conn, $sqlStmt);
		
		$disp_date='';
		while($chat_row = mysqli_fetch_assoc($chat_res))
		{
			$msg_id		= $chat_row['id'];
			$msg_type	= $chat_row['msg_type'];
			$message	= $chat_row['message'];
			$timestamp	= $chat_row['timestamp'];
			$json_detail= $chat_row['json_detail'];
			$file_path	= $chat_row['file_path'];
	
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
			//Media Message Handling 
		if(!empty($chat_row['file_path'])){
			$message = '
				<div style="text-align: center;">
					<img src="'.$chat_row['file_path'].'" alt="Image from Telegram" width="300">
					<p style="margin-top: 5px; font-weight: bold;">'.$chat_row['message'].'</p>
				</div>';
			}
			if($msg_type==1)
			{
				$response .= '<div class="message sent-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
			}
			else
			{
				$response .= '<div class="message incoming-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
			}
		}
	
		// Return the response to the AJAX request
		echo $response;
	}
} 
mysqli_close($conn);
?>