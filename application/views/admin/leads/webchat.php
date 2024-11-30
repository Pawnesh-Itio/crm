<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
/* Container for messages */
.chat-container {
    height:500px;
    display: flex;
    flex-direction: column;
    width: auto;
    padding: 10px;
    border-radius: 8px;
    overflow-y:auto;
}

/* Incoming message box */
.incoming-message {
    align-self: flex-start;
    max-width: 80%;
    padding: 10px;
    margin: 5px 0;
    background-color: #d3e3fc;
    color: #333;
    border-radius: 12px 12px 12px 0;
    font-size: 14px;
    box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
}

/* Send message box */
.sent-message {
    align-self: flex-end;
    max-width: 80%;
    padding: 10px;
    margin: 5px 0;
    background-color: #1e293b;
    color: white;
    border-radius: 12px 12px 0 12px;
    font-size: 14px;
    box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
}

/* Message input area */
.message-input {
    display: flex;
    flex-direction: row;
    border-top: 1px solid #ddd;
    background-color: #fff;
    padding-top: 10px;
}

.message-input input {
    border-radius: 20px;
    width: 90%;
    padding: 25px;
}

.message-input button {
	margin-top:22px;
	position:relative;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    background-color: #1e293b;
    color: white;
    border: none;
    margin-left: 10px;

}

.message-input button:hover {
    background-color: #465c80;
}
</style>
<style>
.card-wa-configuration {
    padding: 20px;
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}

.wa-lodder {
    position: absolute;
    top:45%;
    left: 45%;
    width: 80%;
    z-index: 9999; /* Ensure it's on top of everything */
}

.whatsapp-side-bar {
    box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
    height: 600px;
    overflow-y: auto;
}

.sidebar-head-text {
    font-size: 25px;
    text-align: center;
    position: absolute;
    top: 10px;
}

.sidebar-head-item {
    display: flex;
}

.chat-item {
    font-size: 16px;
    text-align: left;
    border-bottom: 2px solid #1e293b;
    padding: 5px;
}

.chat-link {
    color: #1e293b;
}

.chat-item:hover {
    background-color: #1e293b;
    color: #ffffff;
    cursor: pointer;
}
.active-chat{
    background-color: #1e293b;
    color: #ffffff;
    cursor: pointer;
}

.whatsapp-chat-interface {
    box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
    height: 550px;
}
</style>
<div id="wrapper">
	<div class="content">
		<div class="row card-wa-configuration">
			<div class="col-md-3">
				<div class="whatsapp-side-bar">
					<div class="sidebar-header">
						<ul class="sidebar-head-item">
							<li>
							<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="60" height="60" viewBox="0 0 64 64">
								<path d="M 32 10 C 19.85 10 10 19.85 10 32 C 10 44.15 19.85 54 32 54 C 44.15 54 54 44.15 54 32 C 54 19.85 44.15 10 32 10 z M 32 14 C 41.941 14 50 22.059 50 32 C 50 41.941 41.941 50 32 50 C 22.059 50 14 41.941 14 32 C 14 22.059 22.059 14 32 14 z M 41.041016 23.337891 C 40.533078 23.279297 39.894891 23.418531 39.181641 23.675781 C 37.878641 24.145781 21.223719 31.217953 20.261719 31.626953 C 19.350719 32.014953 18.487328 32.437828 18.486328 33.048828 C 18.486328 33.478828 18.741312 33.721656 19.445312 33.972656 C 20.177313 34.234656 22.023281 34.79275 23.113281 35.09375 C 24.163281 35.38275 25.357344 35.130844 26.027344 34.714844 C 26.736344 34.273844 34.928625 28.7925 35.515625 28.3125 C 36.102625 27.8325 36.571797 28.448688 36.091797 28.929688 C 35.611797 29.410688 29.988094 34.865094 29.246094 35.621094 C 28.346094 36.539094 28.985844 37.490094 29.589844 37.871094 C 30.278844 38.306094 35.239328 41.632016 35.986328 42.166016 C 36.733328 42.700016 37.489594 42.941406 38.183594 42.941406 C 38.877594 42.941406 39.242891 42.026797 39.587891 40.966797 C 39.992891 39.725797 41.890047 27.352062 42.123047 24.914062 C 42.194047 24.175062 41.960906 23.683844 41.503906 23.464844 C 41.365656 23.398594 41.210328 23.357422 41.041016 23.337891 z"></path>
							</svg>
							</li>
							<li>
								<span class="sidebar-head-text">WebChats</span>
							</li>
						</ul>
						<hr style="border: 1px solid #1e293b">
					</div>
					<div class="sidebar-body">
						<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
							<?php
								// Get the chat_id from the URL (assuming it's the last segment of the URL)
								$chat_id = $this->uri->segment(4); // Adjust the segment number if needed
								$i = 0;
								if (isset($tabs)) 
								{
									foreach ($tabs as $group) 
									{ 
										// Check if the current group client_id matches the chat_id from the URL
										$is_active = ($group['client_id']==$chat_id)?' active-chat':''; 
										?>
											<a class="chat-link" href="<?php echo admin_url('leads/webchat/' . $group['client_id']); ?>" data-group="<?php echo e($group['client_id']); ?>">
												<li class="chat-item settings-discuss-<?php echo e($group['client_id']); ?><?php echo $is_active; ?>">
													<i class="fa-solid fa-comment menu-icon"></i>
													<?php echo e($group['name']); ?>
												</li>
											</a>

										<?php 
										$i++;
									}
								}
							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="whatsapp-chat-interface">
				<?php
				if(isset($chat_id)&&$chat_id)
				{
				?>
				<h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
					Chat ID: <?php echo e($chat_id); ?>
				</h4>
				<?php
				}?>
				<div class="chat-screen"
					style="height:100% ;background: url('<?php echo base_url('assets/images/chatbackground3.jpg')?>">
					<?php
					$response = '<div id="message-container" class="message-container chat-container">';

				//	if(isset($leads)&&$chat_id) {}

					$response .= '</div>';
					echo $response;

					if(isset($chat_id)&&$chat_id)
					{
					?>
					<?php
					}
					?>
					</div>
				</div>
			</div>
			<!-- Input box with 'Send' button inside the border -->
			<div class="clearfix"></div>
			<?php
			if(isset($chat_id)&&$chat_id)
			{
			?>
			<div class="message-input">
				<input type="text" class="form-control input-box" id="message" placeholder="Type a message...">	
				<button type="submit" class="btn btn-primary send-button" id="send-button" onclick="sendMessage()">
					<svg xmlns="http://www.w3.org/2000/svg" style="padding-top:3.5px" viewBox="0 0 50 25" width="50" height="24" fill="white"><path d="M2 21v-7l11-2-11-2V3l21 9-21 9z"/></svg>
				</button>
            </div>
			<?php
			}
			?>

		</div>
	</div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>

<script>
// Function to send the message via AJAX
function sendMessage() {
	var message = document.getElementById("message").value;
	var chat_id = window.location.pathname.split("/").pop(); // Get the last part of the URL as chat_id
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

<?php
if(isset($chat_id)&&$chat_id)
{
?>
	var chat_id = '<?php echo $chat_id;?>' // Get chat_id 

	// Function to fetch and update the message container
	function fetchData() {

		$.ajax({
			type: "POST",
			url: "/crm/show-webchat-discuss.php",
			data: { chat_id: chat_id }
		}).done(function(data) {
//			alert(1111+data);

			$('#message-container').html(data); // Update the content of the message container
			scrollToBottom(); // Scroll to the bottom if needed
		}).fail(function(jqXHR, textStatus, errorThrown) {
    console.error("Failed to fetch data:", textStatus, errorThrown);
});
	}

	// Call the function immediately when the page loads
	fetchData();

	// Set an interval to run the fetchData function every 30 seconds (30000 milliseconds)
	setInterval(fetchData, 5000);
<?php
}
?>

</script>

<?php //hooks()->do_action('settings_group_end', $tab); ?>
</body>

</html>