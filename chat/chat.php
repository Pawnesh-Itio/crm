<?php 
include_once "../application/config/db.php";
include_once "header.php";

if(!isset($_SESSION['unique_id']))
{
	header("location: login.php");
}

?>

<body>

<div class="wrapper">
	<section class="chat-area">
		<header>
			<div class="details">
				<span><?php echo @$_SESSION['fullname'];?></span>
				<p><?php echo @$row['status'];?></p>
			</div>
			<button id="closeBtn" class="close-btn">X</button>
		</header>

		<div class="chat-box"> </div>

		<form action="#" class="typing-area">
			<input type="text" name="incoming_id" class="incoming_id" value="<?php echo $_SESSION['incoming_id'];?>" hidden>
			<input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
			<button><i class="fas fa-arrow-circle-right"></i></button>
			<!--<i class="fas fa-angle-right"></i><i class="fas fa-angle-double-right"></i> -->
		</form>
	</section>
</div>

<!-- Modal -->
<div id="modal" class="modal">
	<div class="modal-content">
		<h3>What do you want to do?</h3>
		<button id="endChatBtn">End Chat</button>
		<button id="sendEmailBtn">Send Chat via Email</button>
	</div>
</div>

<script type="text/javascript" src="js/chat.js"></script>

<script>
// Get modal and button elements
const closeBtn		= document.getElementById('closeBtn');
const modal			= document.getElementById('modal');
const endChatBtn	= document.getElementById('endChatBtn');
const sendEmailBtn	= document.getElementById('sendEmailBtn');

// Show the modal when the close button is clicked
closeBtn.addEventListener('click', () => {
	modal.style.display = 'block';
});

// End chat action (simply close the chat)
endChatBtn.addEventListener('click', () => {
	window.close(); // Close the chat window
});

// Send chat via email action
sendEmailBtn.addEventListener('click', () => {
	sendChatEmail();
	window.close(); // Close the chat window after sending the email
});

// Function to send chat messages via email
function sendChatEmail() {
	// Collect chat messages
	const chatMessages = document.querySelector('.chat-box').innerText;

	// Create a form for sending an email
	const form	= document.createElement('form');
	form.method = 'POST';
	form.action = 'sendEmail.php'; // You need to create this PHP file

	// Create a hidden input field with the chat messages
	const input = document.createElement('input');
	input.type	= 'hidden';
	input.name	= 'chatMessages';
	input.value = chatMessages;
	form.appendChild(input);

	// Append the form to the body and submit it
	document.body.appendChild(form);
	form.submit();
}

// Close modal if user clicks anywhere outside the modal
window.onclick = function(event) {
	if (event.target === modal) {
		modal.style.display = 'none';
	}
}
</script>
<?php mysqli_close($conn);?>
</body>
</html>