<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-3">
				<h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
					<?php echo _l('als_webchat'); ?>
				</h4>
				<ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked">
				<?php
				// Get the chat_id from the URL (assuming it's the last segment of the URL)
				$chat_id = $this->uri->segment(4); // Adjust the segment number if needed
//				print_r($_SESSION);exit;
				$i = 0;
				if (isset($tabs)) {
					foreach ($tabs as $group) { 
						// Check if the current group client_id matches the chat_id from the URL
						$is_active = ($group['client_id']==$chat_id)?' active':''; 
						?>
						<li class="settings-discuss-<?php echo e($group['client_id']); ?><?php echo $is_active; ?>">
							<a href="<?php echo admin_url('leads/webchat/' . $group['client_id']); ?>" data-group="<?php echo e($group['client_id']); ?>">
								<i class="fa-solid fa-comment menu-icon"></i>
								<?php echo e($group['name']); ?>
							</a>
						</li>
						<?php 
						$i++;
					}
				}
				?>
				</ul>
			</div>

			<div class="col-md-9">
				<?php
				if(isset($chat_id)&&$chat_id)
				{
				?>
				<h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
					Chat ID: <?php echo e($chat_id); ?>
				</h4>
				<?php
				}?>
				<div class="panel_s">
					<div class="panel-body">
					<?php
					$response = '<div id="message-container" class="message-container">';

				//	if(isset($leads)&&$chat_id) {}
					$response .= '</div>';
					echo $response;

					if(isset($chat_id)&&$chat_id)
					{
					?>
						<!-- Input box with 'Send' button inside the border -->
						<div class="input-container">
							<input type="text" id="message" class="input-box" placeholder="Type your message">
							<button class="send-button" id="send-button" onclick="sendMessage()">Send</button>
						</div>
					<?php
					}
					?>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
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
	var chat_id = '<?=$chat_id;?>' // Get chat_id 

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