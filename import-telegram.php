<?php
/*
webhook reponse parameters
message->chat->id;
message->chat->first_name;
message->chat->last_name;
message->chat->username;
message->chat->type;
message->date;
message->text;
*/

// Include the database configuration file
require_once "application/config/db.php";

// Read the incoming JSON data from the request body
$input = file_get_contents('php://input');
// Decode the JSON data into a PHP object
$web_data = json_decode($input);

// Check if the 'chat' ID exists and is valid
if (isset($web_data->message->chat->id) && ($web_data->message->chat->id)) {
	// Retrieve relevant information from the incoming message
	$chat_id	= $web_data->message->chat->id;
	$username	= $web_data->message->chat->username;
	$name		= $web_data->message->chat->first_name;
	
	// Retrieve the text message sent by the user
	$text = $web_data->message->text;
	
	// If the user's last name is available, append it to the full name
	if (isset($web_data->message->chat->last_name)) {
		$name .= " " . $web_data->message->chat->last_name;
	}
	
	// Check if the message is '/start', which is a common start command for bots
	if ($text == '/start') {
		// Prepare a welcome message
		$msg = "Hi $name,\nHow can I help you?";
	
		// URL encode the message to ensure it's safe for a URL
		$text = urlencode($msg); // The message to send
	
		// Set your Telegram Bot Token (replace with your actual token)
		$token = "7588093840:AAHllbIf3S_qqgCBOYW-B1ZB6ZNIJEY-sPM";
	
		// Build the Telegram API URL for sending the message
		$url = "https://api.telegram.org/bot$token/sendMessage?text=$text&chat_id=$chat_id";
	
		// Initialize cURL to make a request to the Telegram API
		$ch = curl_init();
	
		// Set the necessary cURL options
		curl_setopt($ch, CURLOPT_URL, $url); // The URL to make the request to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Set to true to return the response as a string
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects if necessary
	
		// Execute the cURL request and store the response (although we don't use it here)
		$response = curl_exec($ch);
	} else {
	
		$chat_id = trim($chat_id);
		// If the message is not '/start', we check for an existing lead in the database
		$sqlStmt = "SELECT * FROM `it_crm_leads` WHERE `client_id` = '$chat_id'";
		$res = mysqli_query($conn, $sqlStmt);
	
		// If the lead already exists, update its description
		if (mysqli_num_rows($res) > 0) {
			$row = mysqli_fetch_assoc($res);
			$lead_id = $row['id'];
	
			// Append the new text into tbltelegram (conversion) in the database
			$sqlStmt = "INSERT INTO `tbltelegram` (`lead_id`, `chat_id`, `message`, `msg_type`, `timestamp`, `staff_id`,`json_detail`) 
				VALUES ('$lead_id', '$chat_id', '$text', '2', NOW(), 0,'$input')";
			$res = mysqli_query($conn, $sqlStmt);
		} else {
			// If no lead exists for this client_id, create a new lead record
			// 'source' is hardcoded as 4 to indicate this lead came from Telegram
			$sqlStmt = "INSERT INTO `it_crm_leads` (`name`, `dateadded`, `description`, `client_id`, `email`, `source`, `status`) 
				VALUES ('$name', NOW(), '$text', '$chat_id', '$username', 4, 2)";
			mysqli_query($conn, $sqlStmt);
		}
	}
}
mysqli_close($conn);
?>