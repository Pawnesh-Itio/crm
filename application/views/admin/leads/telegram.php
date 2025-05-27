<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row card-wa-configuration ">
			<div class="col-md-3 wa-side-bar-col">
				<div class="whatsapp-side-bar">
					<div class="sidebar-header">
						<!-- Telegram Bot Selection Dropdown -->
                        <?php if (isset($bots) && is_array($bots) && count($bots) > 0) { ?>
                        <form id="bot-select-form" method="get" action="<?php echo admin_url('leads/telegram'); ?>">
                            <div class="form-group">
                                <label for="bot_id">Telegram Bot</label>
                                <select class="form-control" id="bot_id" name="bot_id" onchange="document.getElementById('bot-select-form').submit();">
                                    <?php foreach ($bots as $bot) { ?>
                                        <option value="<?php echo $bot['id']; ?>" <?php if ($selected_bot_id && $selected_bot_id == $bot['id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($bot['telegram_name']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </form>
                        <?php } ?>
                        <!-- End Bot Selection Dropdown -->
						<ul class="sidebar-head-item">
							<li>
							<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="60" height="60" viewBox="0 0 64 64">
								<path d="M 32 10 C 19.85 10 10 19.85 10 32 C 10 44.15 19.85 54 32 54 C 44.15 54 54 44.15 54 32 C 54 19.85 44.15 10 32 10 z M 32 14 C 41.941 14 50 22.059 50 32 C 50 41.941 41.941 50 32 50 C 22.059 50 14 41.941 14 32 C 14 22.059 22.059 14 32 14 z M 41.041016 23.337891 C 40.533078 23.279297 39.894891 23.418531 39.181641 23.675781 C 37.878641 24.145781 21.223719 31.217953 20.261719 31.626953 C 19.350719 32.014953 18.487328 32.437828 18.486328 33.048828 C 18.486328 33.478828 18.741312 33.721656 19.445312 33.972656 C 20.177313 34.234656 22.023281 34.79275 23.113281 35.09375 C 24.163281 35.38275 25.357344 35.130844 26.027344 34.714844 C 26.736344 34.273844 34.928625 28.7925 35.515625 28.3125 C 36.102625 27.8325 36.571797 28.448688 36.091797 28.929688 C 35.611797 29.410688 29.988094 34.865094 29.246094 35.621094 C 28.346094 36.539094 28.985844 37.490094 29.589844 37.871094 C 30.278844 38.306094 35.239328 41.632016 35.986328 42.166016 C 36.733328 42.700016 37.489594 42.941406 38.183594 42.941406 C 38.877594 42.941406 39.242891 42.026797 39.587891 40.966797 C 39.992891 39.725797 41.890047 27.352062 42.123047 24.914062 C 42.194047 24.175062 41.960906 23.683844 41.503906 23.464844 C 41.365656 23.398594 41.210328 23.357422 41.041016 23.337891 z"></path>
							</svg>
							</li>
							<li>
								<span class="sidebar-head-text">Telegram Chats</span>
							</li>
						</ul>
						<hr style="border: 1px solid #1e293b">
					</div>
					<div class="sidebar-body">
						<!-- Creating a list of navigation items (tabs) -->
						<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
							<?php
							// Get the 'chat_id' from the URL (assuming it's the fourth segment of the URL)
							// This is used to identify which chat is currently active
							$chat_id = $this->uri->segment(4); // Adjust the segment number if needed
					
							// Initialize a counter variable
							$i = 0;
					
							// Check if the 'tabs' variable is set (it should contain data to generate the tabs)
							if (isset($tabs)) 
							{
								// Loop through each 'group' in the 'tabs' array
								foreach ($tabs as $group) 
								{ 
									// Check if the current group's 'client_id' matches the 'chat_id' from the URL
									// If it matches, mark this tab as active by adding the 'active-chat' class
									$is_active = ($group['client_id'] == $chat_id) ? ' active-chat' : ''; 
									?>
									
									<!-- Create a clickable link for the chat item -->
									<a class="chat-link" href="<?php echo admin_url('leads/telegram/' . $group['client_id'] . '?bot_id=' . urlencode($selected_bot_id)); ?>" data-group="<?php echo e($group['client_id']); ?>">
										<!-- Create the list item with the appropriate class for styling and activation -->
										<li class="chat-item settings-discuss-<?php echo e($group['client_id']); ?><?php echo $is_active; ?>">
											<!-- Add a Telegram icon to represent the chat -->
											<i class="fa-brands fa-telegram menu-icon"></i>
											<!-- Display the name of the group -->
					                        <?php echo e($group['name']); ?>
										</li>
									</a>
					
									<?php 
									// Increment the counter
									$i++;
								}
							}
							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9 wa-chat-col">
				<div class="whatsapp-chat-interface">
					<?php
					if(isset($chat_id)&&$chat_id)
					{
						// Find the selected chat from the $tabs array
						$chat_name = ''; // Default value if chat is not found
						foreach ($tabs as $group) {
							if ($group['client_id'] == $chat_id) {
								$chat_name = $group['name']; // Set the chat name
								break;
							}
						}
					?>
					<div class="chat-top-bar">
						<span class="chat-back-btn"><a href="<?php echo base_url('admin/leads/telegram?bot_id=' . urlencode($selected_bot_id));?>" class="back-btn"><i class="fa-solid fa-arrow-left"></i></a></span><span class="chat-title"> <?php echo $chat_name;?></span>
					</div>
					<?php
					}?>
					<div class="chat-screen"
						style="background: url('<?php echo base_url('assets/images/chatbackground3.jpg')?>')">
						<?php
						$response = '<div id="message-container" class="chat-container">';
						$response .= '</div>';
						echo $response;
						if(isset($chat_id)&&$chat_id)
						{
						?>
						<div class="button">
							<div class="message-input">
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
			<!-- Input box with 'Send' button inside the border -->
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<div id="new_version"></div>
<?php init_tail(); ?>

<script>
	// Mobile View Adjustment
	if (window.innerWidth <= 768) {
		<?php if(isset($chat_id)&&$chat_id){ ?>
			$('.wa-side-bar-col').hide(); // Toggles between block and none
			$('.wa-chat-col').show();
		<?php }else{ ?>
			$('.wa-side-bar-col').show(); // Toggles between block and none
			$('.wa-chat-col').hide();
		<?php } ?>
	}
// Function to send the message via AJAX
function sendMessage() {
	var message = document.getElementById("message").value;
	var chat_id = window.location.pathname.split("/").pop(); // Get the last part of the URL as chat_id
	var staff_id = '<?php echo $_SESSION['staff_user_id'];?>' //1; // You can change this to dynamically fetch the staff_id, based on your system
	var telegram_token = '<?php echo $telegram_token;?>' // Get telegram token

	// Check if the message is not empty
	if (message) {
		// Prepare data to be sent to PHP file
		var data = {
			chat_id: chat_id,
			message: message,
			staff_id: staff_id,
			telegram_token: telegram_token
		};
		console.log("Data to be sent:", data); // Log the data being sent for debugging

		// Perform AJAX request to send the message
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "/crm/show-telegram-discuss.php", true); // The PHP file is called show-telegram-discuss.php
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
		xhr.send("chat_id=" + encodeURIComponent(data.chat_id) + "&telegram_message=" + encodeURIComponent(data.message) + "&staff_id=" + encodeURIComponent(data.staff_id) + "&telegram_token=" + encodeURIComponent(data.telegram_token));
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
	//$telegram_token
	var chat_id = '<?php echo $chat_id;?>' // Get telegram chat_id 
	//console.log("chat Id: "+chat_id);
	var telegram_token = '<?php echo $telegram_token;?>' // Get telegram token
	//console.log("telegram_token : "+telegram_token);

	// Function to fetch and update the message container
	function fetchData() {
		$.ajax({
			type: "POST",
			url: "/crm/show-telegram-discuss.php",
			data: { chat_id: chat_id,telegram_token: telegram_token }
		}).done(function(data) {
			$('#message-container').html(data); // Update the content of the message container
			scrollToBottom(); // Scroll to the bottom if needed
		}).fail(function(jqXHR, textStatus, errorThrown) {
			//console.error("Failed to fetch data:", textStatus, errorThrown);
		});
	}

	// Call the function immediately when the page loads
	fetchData();

	// Set an interval to run the fetchData function every 30 seconds (30000 milliseconds)
	setInterval(fetchData, 30000);
<?php
}
?>
</script>
<?php //hooks()->do_action('settings_group_end', $tab); ?>
</body>

</html>