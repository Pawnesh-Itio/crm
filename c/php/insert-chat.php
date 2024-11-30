<?php 
session_start();
include_once "../../application/config/db.php";
if(isset($_SESSION['unique_id']))
{
	$outgoing_id= $_SESSION['unique_id'];
	$incoming_id= mysqli_real_escape_string($conn, $_POST['incoming_id']);
	$message	= mysqli_real_escape_string($conn, $_POST['message']);

	if(!empty($message))
	{
		//$sqlStmt= "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES ('{$incoming_id}', '{$outgoing_id}', '{$message}')";
		$sqlStmt= "INSERT INTO `it_crm_messages` (`incoming_msg_id`, `outgoing_msg_id`, `msg`,`msg_type`) VALUES ('{$incoming_id}', '{$outgoing_id}', '{$message}',2)";
		
		$sql = mysqli_query($conn, $sqlStmt) or die();
	}
	
}
else
{
	//header("location: ../login.php");
}
?>
<?php mysqli_close($conn);?>