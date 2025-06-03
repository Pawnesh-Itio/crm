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
						style="background: url('<?php echo base_url('assets/images/chatbackground3.jpg')?>');height:596px">
						<?php
						$response = '<div id="message-container" class="chat-container">';
						$response .= '</div>';
						echo $response; 
						if(isset($chat_id)&&$chat_id)
						{
						?>
						<div class="button">
							<div class="message-input">
								<div class="row">
									<div class="col-sm-10">
										<div class="input-group">
											<span class="input-group-btn">
												<div class="btn-group dropup">
													<button class="btn wa-drop-btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<i class="fa fa-paperclip" aria-hidden="true"></i>
													</button>
													<ul class="dropdown-menu">
														<li><a href="#" id="textMessageOption">Text Message</a></li>
														<li><a href="#" id="mediaMessageOption">Photo/Image</a></li>
														<li><a href="#" id="documentMessageOption">Documents</a></li>
													</ul>
												</div>
											</span>
											<!-- Container for the fields -->
											    <!-- Hidden Fields -->
												<input type="hidden" id="formType" value="1">
												<!-- Default Text Message Field (shown by default) -->
												<input type="text" class="form-control message-field message-input" id="textMessageField" placeholder="Type a message..." style="display: block;">
												<!-- Media Message Field (hidden by default) -->
												<input type="file" id="mediaMessageFileField" style="display:none">
												<input type="text" id="mediaMessageCaptionField" class="form-control message-field message-input"placeholder="Image Caption" style="display:none">
												<!-- Document Message Field (hidden by default) -->
												<!-- <input type="file" id="documentMessageFileField" style="display:none"> -->
										</div>
									</div>

									<div class="col-sm-2">
										<button type="submit" class="btn wa-btn" id="send-button" onclick="sendMessage()">
											<svg xmlns="http://www.w3.org/2000/svg" style="padding-top:3.5px" viewBox="0 0 50 25" width="50" height="24" fill="white"><path d="M2 21v-7l11-2-11-2V3l21 9-21 9z"/></svg>
										</button>	
									</div>
								</div>
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
	document.getElementById('send-button').disabled = true;
    const formType = document.getElementById('formType').value;
    const chat_id = window.location.pathname.split("/").pop();
    const staff_id = '<?php echo $_SESSION['staff_user_id'];?>';
    const telegram_token = '<?php echo $telegram_token;?>';

    const formData = new FormData();
    formData.append('chat_id', chat_id);
    formData.append('staff_id', staff_id);
    formData.append('telegram_token', telegram_token);

    if (formType === '1') {
        const message = document.getElementById("textMessageField").value;
        if (!message.trim()) {
            alert("Please type a message before sending.");
            return;
        }
        formData.append('telegram_message', message);
    } else if (formType === '2') {
        const fileInput = document.getElementById('mediaMessageFileField');
        const caption = document.getElementById('mediaMessageCaptionField').value;
        if (!fileInput.files.length) {
            alert("Please select an image to send.");
            return;
        }
        formData.append('media', fileInput.files[0]);
        formData.append('caption', caption);
    } else if (formType === '3') {
        const fileInput = document.getElementById('documentMessageFileField');
        if (!fileInput.files.length) {
            alert("Please select a document to send.");
            return;
        }
        formData.append('document', fileInput.files[0]);
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/crm/show-telegram-discuss.php", true);

    xhr.onload = function() {
		document.getElementById('send-button').disabled = false;
		clearInterval(intervalId); // Just in case
		intervalId = setInterval(fetchData, 30000);
        if (xhr.status === 200) {
            document.getElementById("message-container").innerHTML = xhr.responseText;
            document.getElementById("textMessageField").value = "";
            document.getElementById('mediaMessageFileField').value = "";
            document.getElementById('mediaMessageCaptionField').value = "";
            document.getElementById('documentMessageFileField').value = "";
            scrollToBottom();
        } else {
            alert("Error sending message.");
        }
    };

    xhr.send(formData);
}

// Function to scroll to the bottom of the message container
function scrollToBottom() {
	var messageContainer = document.getElementById("message-container");
	messageContainer.scrollTop = messageContainer.scrollHeight;
}

// Event listener for the 'Enter' key to send the message
document.getElementById("textMessageField").addEventListener("keypress", function(event) {
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
	let intervalId = setInterval(fetchData, 30000);
<?php
}
?>
// Change Message Field Visibility Based on Selection
document.getElementById('textMessageOption').addEventListener('click', function() {
    changeMessageField('textMessageField');
    $('#formType').val(1);
});

document.getElementById('mediaMessageOption').addEventListener('click', function() {
    $('#formType').val(2);
    changeMessageField('mediaMessageCaptionField');
    document.getElementById('mediaMessageFileField').click();
});
document.getElementById('documentMessageOption').addEventListener('click', function() {
    $('#formType').val(3);
    changeMessageField('documentMessageOption');
});


// Function to switch the visible field
function changeMessageField(fieldId) {
    // Hide all fields
    const fields = document.querySelectorAll('.message-field');
    fields.forEach(function(field) {
        field.style.display = 'none';
    });

    // Show the selected field
    document.getElementById(fieldId).style.display = 'block';
}
// Media Upload function
document.getElementById('mediaMessageFileField').addEventListener('change', function(event) {
	// Stop the periodic fetch
    clearInterval(intervalId);
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const wrapper = document.createElement('div');
        wrapper.className = 'sent-message';

        // Create image container
        const imageWrapper = document.createElement('div');
        imageWrapper.style.position = 'relative';
        imageWrapper.style.display = 'inline-block';
        imageWrapper.style.maxWidth = '300px';
        imageWrapper.style.margin = '10px 0';

        // Create image preview
        const preview = document.createElement('img');
        preview.src = e.target.result;
        preview.style.width = '100%';
        preview.style.display = 'block';

        // Create overlay text
        const overlay = document.createElement('div');
        overlay.textContent = 'Image is ready to Send click Send button';
        overlay.style.position = 'absolute';
        overlay.style.top = '50%';
        overlay.style.left = '50%';
        overlay.style.transform = 'translate(-50%, -50%)';
        overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.6)';
        overlay.style.color = 'white';
        overlay.style.padding = '10px 20px';
        overlay.style.borderRadius = '5px';
        overlay.style.fontWeight = 'bold';
        overlay.style.pointerEvents = 'none';

        imageWrapper.appendChild(preview);
        imageWrapper.appendChild(overlay);
        wrapper.appendChild(imageWrapper);

        const container = document.getElementById('message-container');
        container.appendChild(wrapper);

        scrollToBottom();
    };
    reader.readAsDataURL(file);
});


</script>
<?php //hooks()->do_action('settings_group_end', $tab); ?>
</body>

</html>