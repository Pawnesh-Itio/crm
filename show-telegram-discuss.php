<?php
// Include the database configuration file
require_once "application/config/db.php";

if (isset($_POST['telegram_token'])) {
	$token = $_POST['telegram_token'];
}
else
{
	// Set your Telegram Bot Token (replace with your actual token)
	$token = "7588093840:AAHllbIf3S_qqgCBOYW-B1ZB6ZNIJEY-sPM";
}
// Check if we received the 'telegram_message' parameter
if (isset($_POST['telegram_message'])) {

	// Get the message sent from the AJAX request
	$message	= $_POST['telegram_message'];
	$chat_id	= $_POST['chat_id'];
	$staff_user_id= $_POST['staff_id'];

	// Process the message here (e.g., send it via Telegram API)
	// For this example, we will just echo back the message as a confirmation
	// You can replace this part with actual Telegram API interaction if needed

//	$response = '<div class="message-container">';
	$response = '';
	$sqlStmt= "SELECT * FROM `it_crm_leads` WHERE `client_id` = '$chat_id'";
	$res	= mysqli_query($conn, $sqlStmt);
	$row	= mysqli_fetch_assoc($res);

	$lead_id		= $row['id'];
	$description	= $row['description'];
	$dateadded		= $row['dateadded'];

	$response .= '<div class="message incoming-message">' . ($description) . '<span class="send-time">'.date('d-M H:i',strtotime($dateadded)).'</span></div>';

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

		if($msg_type==1)
		{
			$response .= '<div class="message  sent-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
		}
		else
		{
			$response .= '<div class="message incoming-message">' . ($message) . '<span class="send-time">'.date('H:i', strtotime($timestamp)).'</span></div>';
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
	
			if(empty($message))
			{
				if($file_path)
				{
					$message='<img src="https://api.telegram.org/file/bot'.$token.'/'.$file_path.'" alt="Image from Telegram" width="300">';
				}
				elseif($json_detail)
				{
					// Decode the JSON string to an associative array
					$data = json_decode($json_detail, true);
					
					// Initialize the variable to store the file_id of the desired image

					$file_id = null;
					$largestImage = null;
					$maxSize = 0;
					// Loop through the photo array
					foreach ($data['message']['photo'] as $image) {
			            if ($photo['file_size'] > $maxSize) {
                            $maxSize = $photo['file_size'];
                            $largestImage = $photo;
                        }
                        if ($largestImage) {
                            $file_id = $largestImage['file_id'];
                        }else{
                            echo "No images found.";
                        }
					}

					if(isset($file_id)&&$file_id)
					{
							
						// Build the Telegram API URL for sending the message
						$url = "https://api.telegram.org/bot$token/getFile?file_id=$file_id";
					
						// Initialize cURL to make a request to the Telegram API
						$ch = curl_init();
					
						// Set the necessary cURL options
						curl_setopt($ch, CURLOPT_URL, $url); // The URL to make the request to
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set to true to return the response as a string
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if necessary
					
						// Execute the cURL request and store the response (although we don't use it here)
						$res=curl_exec($ch);	
						$arr=json_decode($res,true);
						$file_path=$arr['result']['file_path'];

						if($file_path)
						{
							$message='<img src="https://api.telegram.org/file/bot'.$token.'/'.$file_path.'" alt="Image from Telegram" width="300">';

							$sqlStmt = "UPDATE `tbltelegram` SET `file_path`='$file_path' WHERE `id` = '$msg_id'";
							mysqli_query($conn, $sqlStmt);
						}
					}
				}
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