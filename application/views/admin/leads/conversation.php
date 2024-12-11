<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- new model for telegra-->
<div class="modal fade" id="myModalTel" tabindex="-1" role="dialog" aria-labelledby="myModalTelLabel" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalTelLabel"></h4>
			</div>
			<div class="modal-body">
					
				<div class="chat-screen"
					style="background: url('<?php echo base_url('assets/images/chatbackground3.jpg')?>">
					<?php
					$telegram_token = get_option('telegram_token');
					?>
					<div id="telegram-message-container" class="chat-container">
					</div>
					
					
					<div class="button">
						<div class="message-input">
							<input type="hidden" id="chatTeleId" name="chatTeleId"/>
							<input type="hidden" id="telegram_token" name="telegram_token" value="<?php echo $telegram_token;?>"/>
							<input type="text" class="form-control input-box" id="telegram_message" placeholder="Type a message...">	
							<button type="submit" class="btn wa-btn" id="send-button" onclick="sendTelegramMessage()">
								<svg xmlns="http://www.w3.org/2000/svg" style="padding-top:3.5px" viewBox="0 0 50 25" width="50" height="24" fill="white"><path d="M2 21v-7l11-2-11-2V3l21 9-21 9z"/></svg>
							</button>	
						</div>
					</div>
					
				</div>
			</div>
			
			
		</div>
	</div>
</div>



<!--- new model for webchat-->
<div class="modal fade" id="myModal_web" tabindex="-1" role="dialog" aria-labelledby="myModal_webLabel" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModal_webLabel"></h4>
			</div>
			<div class="modal-body">
					
				<div class="chat-screen"
					style="background: url('<?php echo base_url('assets/images/chatbackground3.jpg')?>">
					<?php
					$response = '<div id="message-container" class="chat-container">';

					$response .= '</div>';
					echo $response;

					//if(isset($chat_id)&&$chat_id)
					{
					?>
					<div class="button">
						<div class="message-input">
							<input type="hidden" id="chatId" name="chatId"/>

							<input type="text" class="form-control input-box" id="message" placeholder="Type a message...">	
							<button type="submit" class="btn wa-btn" id="send-button" onclick="sendMessage()">
								<svg xmlns="http://www.w3.org/2000/svg" style="padding-top:3.5px" viewBox="0 0 50 25" width="50" height="24" fill="white"><path d="M2 21v-7l11-2-11-2V3l21 9-21 9z"/></svg>
							</button>
						</div>
					</div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>

function getWebChat(name,chatId){
	// console.log(name);
	$('#lead-modal').modal('hide');
	$('.modal-title').html(name+' ('+ chatId +')');
	$('#chatId').val(chatId);
	$('#message-container').html('');// Remove any exisiting listener before adding new one

	$.ajax({
		type: "POST",
		url: "/crm/show-webchat-discuss.php",
		data: { chat_id: chatId }
	}).done(function(data) {
//			alert(1111+data);

		$('#message-container').html(data); // Update the content of the message container
		scrollToBottom(); // Scroll to the bottom if needed
	}).fail(function(jqXHR, textStatus, errorThrown) {
		console.error("Failed to fetch data:", textStatus, errorThrown);
	});

}

// Function to send the message via AJAX
function sendMessage() {
	var message = document.getElementById("message").value;
	var chat_id = document.getElementById("chatId").value;
	var staff_id = '<?php echo $_SESSION['staff_user_id'];?>' //1; // You can change this to dynamically fetch the staff_id, based on your system

	// Check if the message is not empty
	if (message) {
		// Prepare data to be sent to PHP file
		var data = {
			chat_id: chat_id,
			message: message,
			staff_id: staff_id,
		};

		// Perform AJAX request to send the message
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "/crm/show-webchat-discuss.php", true); // The PHP file is called discuss page
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		// When the request is completed
		xhr.onload = function() {
			if (xhr.status === 200) {
				// If message was sent successfully, clear the input and scroll to bottom
				$('#message-container').html(xhr.responseText);
			//	console.log("Message sent successfully:", xhr.responseText);
				document.getElementById("message").value = "";	// Clear the input box
				scrollToBottom();	// Scroll to the bottom
			} else {
				// Handle any error
				alert("Error sending message.");
			}
		};

		// Sending data to the PHP file (using URL-encoded form data)
		xhr.send("chat_id=" + encodeURIComponent(data.chat_id) + "&message=" + encodeURIComponent(data.message) + "&staff_id=" + encodeURIComponent(data.staff_id));
	} else {
		alert("Please type a message before sending.");
	}
}

// Function to scroll to the bottom of the message container
function scrollToBottom() {
	var messageContainer = document.getElementById("message-container");
	messageContainer.scrollTop = messageContainer.scrollHeight;
}

// Event listener for the 'Enter' key to send the message
document.getElementById("message").addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
		event.preventDefault();	// Prevent default form submission
		sendMessage();			// Call the sendMessage function
	}
});

// Call scrollToBottom() on page load to ensure it starts at the bottom
window.onload = function() {
	scrollToBottom();
};

//setInterval(fetchData, 5000);




/* functions for telegram*/
function getTelegramChat(name,chatId,telegram_token='')
{
	//console.log(token);
	$('.modal-title').html(name);
	$('#chatTeleId').val(chatId);
	$('#telegram_token').val(telegram_token);

	$.ajax({
		type: "POST",
		url: "/crm/show-telegram-discuss.php",
		data: { chat_id: chatId,telegram_token: telegram_token }
	}).done(function(data) {
		//console.log(data);
		$('#telegram-message-container').html(data); // Update the content of the message container
		scrollToBottomTele(); // Scroll to the bottom if needed
	}).fail(function(jqXHR, textStatus, errorThrown) {
		//console.error("Failed to fetch data:", textStatus, errorThrown);
	});
	
}
// Function to send the message via AJAX
function sendTelegramMessage() {
	var message = document.getElementById("telegram_message").value;
	var chat_id = document.getElementById("chatTeleId").value;
	var telegram_token = document.getElementById("telegram_token").value;

	var staff_id = '<?php echo $_SESSION['staff_user_id'];?>' //1; // You can change this to dynamically fetch the staff_id, based on your system

	// Check if the message is not empty
	if (message) {
		// Prepare data to be sent to PHP file
		var data = {
			chat_id: chat_id,
			message: message,
			staff_id: staff_id,
			telegram_token: telegram_token
		};

		// Perform AJAX request to send the message
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "/crm/show-telegram-discuss.php", true); // The PHP file is called show-telegram-discuss.php
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		// When the request is completed
		xhr.onload = function() {
			if (xhr.status === 200) {
				// If message was sent successfully, clear the input and scroll to bottom
				$('#telegram-message-container').html(xhr.responseText);
			//	console.log("Message sent successfully:", xhr.responseText);
				document.getElementById("telegram_message").value = "";	// Clear the input box
				scrollToBottomTele();	// Scroll to the bottom
			} else {
				// Handle any error
				alert("Error sending message.");
			}
		};

		// Sending data to the PHP file (using URL-encoded form data)
		xhr.send("chat_id=" + encodeURIComponent(data.chat_id) + "&telegram_message=" + encodeURIComponent(data.message) + "&staff_id=" + encodeURIComponent(data.staff_id) + "&telegram_token=" + encodeURIComponent(data.telegram_token));
	} else {
		alert("Please type a message before sending.");
	}
}

// Function to scroll to the bottom of the message container
function scrollToBottomTele() {
	var messageContainer = document.getElementById("telegram-message-container");
	messageContainer.scrollTop = messageContainer.scrollHeight;
}

// Event listener for the 'Enter' key to send the message
document.getElementById("telegram_message").addEventListener("keypress", function(event) {
	if (event.key === "Enter") {
		event.preventDefault();	// Prevent default form submission
		sendTelegramMessage();			// Call the sendTelegramMessage function
	}
});

// Call scrollToBottomTele() on page load to ensure it starts at the bottom
window.onload = function() {
	scrollToBottomTele();
};

</script>