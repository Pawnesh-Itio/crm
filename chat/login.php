<?php 
include_once "header.php";
include_once "../application/config/db.php";

if(isset($_SESSION['unique_id'])&&$_SESSION['unique_id'])
{
	header("Location:chat.php");
	exit;
}
?>
<body>

<div class="wrapper">
    <section class="form login">
		<header>ITIO WebChat</header>
        <form action="php/login.php" enctype="multipart/form-data" method="post" autocomplete="off">
			<div class="error-text"></div>

			<div class="field input">
				<label>Name</label>
				<input type="text" name="fullname" id="fullname" placeholder="Enter Your Name" required>
			</div>
			<div class="field input">
				<label>Email</label>
				<input type="email" name="email" id="email" placeholder="Enter Your Email" required>
			</div>
			<div class="field input">
				<label>WhatsApp Number</label>
				<input type="number" name="mobile" id="mobile" placeholder="Enter Your Mobile" required>
			</div>
			<div class="field button">
				<input type="submit" name="submit" id="submit" value="Continue to Chat" >
			</div>
		</form>
	</section>
</div>
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/login.js"></script>

<?php mysqli_close($conn);?>
</body>
</html>
