<?php
require_once "application/config/db.php";
// Fetch Token from the database
$bot_name = $_GET['bot'] ?? '';
// write the select query to fetch the token from the database connection is already established
if (empty($bot_name)) {
	die("Bot name is required.");
}	
$sqlStmt = "SELECT id, telegram_token FROM `it_crm_telegram_bot` WHERE telegram_name = '$bot_name' ";
$res = mysqli_query($conn, $sqlStmt);
if (mysqli_num_rows($res) > 0) {
	// Fetch the token from the result set
	$row = mysqli_fetch_assoc($res);
	$token = $row['telegram_token'];
	$botId = $row['id'];
}else{
	$token = "7750960478:AAHs_kjrNFODTpGA-J3xSzK6vDHxZOXKHSY";
}

// Read the incoming JSON data from the request body
$input = file_get_contents('php://input');
// Log the raw input for debugging
file_put_contents(__DIR__ . '/telegram_webhook.log', date('Y-m-d H:i:s') . "\n" . $input . "\n\n", FILE_APPEND);
// Decode the JSON data into a PHP object
$web_data = json_decode($input);

// Check if the 'chat' ID exists and is valid
if (isset($web_data->message->chat->id) && ($web_data->message->chat->id)) {
	// Retrieve relevant information from the incoming message
	echo json_encode($web_data);
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
			$photoArrayDecoded = json_decode($input, true);
			// Check if 'message' and 'photo' exist
			if (isset($photoArrayDecoded['message']['photo']) && is_array($photoArrayDecoded['message']['photo']) && count($photoArrayDecoded['message']['photo']) > 0) {
				$photos = $photoArrayDecoded['message']['photo'];
				// Get the photo with the largest file_size
				usort($photos, function($a, $b) {
					return $b['file_size'] <=> $a['file_size'];
				});
				$largestPhoto = $photos[0];
				$largestPhotofileId = $largestPhoto['file_id'];
				// find Image URL
				$getFileUrl = "https://api.telegram.org/bot$token/getFile?file_id=$largestPhotofileId";
				$fileResponse = file_get_contents($getFileUrl);
				$fileResult = json_decode($fileResponse, true);
				if ($fileResult['ok']) {
					$filePath = $fileResult['result']['file_path'];
					$imageUrl = "https://api.telegram.org/file/bot$token/$filePath";
				}else{
					$imageUrl = '';
				}
			}else{
				$imageUrl = '';
			}
			// Append the new text into tbltelegram (conversion) in the database
			$sqlStmt = "INSERT INTO `tbltelegram` (`lead_id`, `chat_id`, `message`, `msg_type`, `timestamp`, `staff_id`,`json_detail`, `file_path`) 
				VALUES ('$lead_id', '$chat_id', '$text', '2', NOW(), 0,'$input', '$imageUrl')";
			$res = mysqli_query($conn, $sqlStmt);
		} else {
			// If no lead exists for this client_id, create a new lead record
			// 'source' is hardcoded as 4 to indicate this lead came from Telegram
			$sqlStmt = "INSERT INTO `it_crm_leads` (`name`, `dateadded`, `description`, `client_id`, `SkypeInfo`, `source`, `status`,`telegram_bot_id`) 
				VALUES ('$name', NOW(), '$text', '$chat_id', '$username', 4, 2, '$botId')";

			if(mysqli_query($conn, $sqlStmt))	//if query executed successfully then execute following section 
			{
				//insert message into notification table
				$sqlStmt = "INSERT INTO `it_crm_notifications` (`isread`, `isread_inline`, `date`, `description`, `fromuserid`, `fromclientid`, `from_fullname`, `touserid`) VALUES(0, 0, NOW(), 'New Lead via Telegram', 0, '{$chat_id}', '{$name}', 1)";
				mysqli_query($conn, $sqlStmt);	//execute query
			}
		}
	}
}
mysqli_close($conn);
?>