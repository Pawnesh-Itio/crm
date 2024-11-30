<?php 
include_once "application/config/db.php";
include_once "header.php";

?>
<body>
<div class="wrapper">
    <section class="chat-area">
		<header>
			<?php
			/*
			$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);

			$sql = mysqli_query($conn, "SELECT *FROM `users` WHERE `unique_id`='{$user_id}'");
			if(mysqli_num_rows($sql)>0)
			{
				$row=mysqli_fetch_assoc($sql);
				if(empty($row['img'])) $row['img']='no-image.png';
			}
			else{
				//header("location: users.php");
			}
			*/
			?>
			<!--<a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a> -->
			<div class="details">
				<span><?php echo @$_SESSION['fullname'];?></span>
				<p><?php echo @$row['status'];?></p>
			</div>
		</header>
		<div class="chat-box">
		</div>
		<form action="#" class="typing-area">
			<input type="text" name="incoming_id" class="incoming_id" value="<?php echo $chat_id;?>" hidden>
			<input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
			<button><i class="fas fa-arrow-circle-right"></i></button>
			<!--<i class="fas fa-angle-right"></i><i class="fas fa-angle-double-right"></i> -->
		</form>
	</section>
</div>
<script type="text/javascript" src="c/js/chat.js"></script>
<?php mysqli_close($conn);?>
</body>
</html>