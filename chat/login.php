<?php 
// Include the header file for the layout
include_once "header.php";

// Include the database configuration file for establishing database connection
include_once "../application/config/db.php";

// Check if the session is set for the 'unique_id' (to verify if the user is logged in)
// If logged in, redirect to the chat main page
if(isset($_SESSION['unique_id'])&&$_SESSION['unique_id'])
{
	header("Location:chat.php");
	exit;
}
?>
<body>

<div class="wrapper">
	<!-- Chat area section where the chat UI components will be rendered -->
    <section class="form login">
		<header>ITIO WebChat</header>
		<!-- Form for user detail-->
        <form action="php/login.php" enctype="multipart/form-data" method="post" autocomplete="off">
			<div class="error-text"></div>

			<div class="field input">
				<label>Name</label>
				<!-- Input field for typing the fullname-->
				<input type="text" name="fullname" id="fullname" placeholder="Enter Your Name" required>
			</div>
			<div class="field input">
				<label>Email</label>
				<!-- Input field for typing the email-id-->
				<input type="email" name="email" id="email" placeholder="Enter Your Email" required>
			</div>
			<div class="field input">
				<label>WhatsApp Number</label>
				<!-- Input field for typing the whatsapp number-->
				<input type="number" name="mobile" id="mobile" placeholder="Enter Your Mobile" required>
			</div>
			<div class="field button">
				<!-- button for submit form to start chat-->
				<input type="submit" name="submit" id="submit" value="Continue to Chat">
			</div>
		</form>
	</section>
</div>
<!-- include js files for form verification-->
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/login.js"></script>

<?php mysqli_close($conn);?>
</body>
</html>
