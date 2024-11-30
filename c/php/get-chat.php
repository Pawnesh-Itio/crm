<?php 
session_start();
include_once "../../application/config/db.php";

if(isset($_SESSION['unique_id']))
{
	$outgoing_id= $_SESSION['unique_id'];
	$incoming_id= mysqli_real_escape_string($conn, $_POST['incoming_id']);
	$output	= "";

//	$sqlStmt = "SELECT *FROM messages LEFT JOIN users ON users.unique_id=messages.outgoing_msg_id WHERE (outgoing_msg_id='{$outgoing_id}' AND incoming_msg_id='{$incoming_id}') OR (outgoing_msg_id='{$incoming_id}' AND incoming_msg_id='{$outgoing_id}') ORDER BY msg_id";

	$sqlStmt = "SELECT *FROM `it_crm_messages` LEFT JOIN it_crm_leads ON it_crm_leads.client_id=it_crm_messages.outgoing_msg_id WHERE (outgoing_msg_id='{$outgoing_id}' OR incoming_msg_id='{$outgoing_id}') ORDER BY msg_id";

	$query = mysqli_query($conn,$sqlStmt);
	
	if(mysqli_num_rows($query)>0)
	{
		while($row=mysqli_fetch_assoc($query))
		{
			//if(empty($row['img'])) $row['img']='no-image.png';
			
			if($row['msg_type']==2)
			{
				$output .= '<div class="chat outgoing">
					<div class="details">
					<span>'.date('H:i',strtotime($row['timestamp'])).'</span>
					<p>'.$row['msg'].'</p>
					</div>
				</div>';
			}
			else
			{
				if(empty($row['img'])) $row['img']='no-image.png';
//<img src="php/images/'.$row['img'].'" alt="">

				$output .= '<div class="chat incoming">
					<div class="details">
					<span>'.date('H:i',strtotime($row['timestamp'])).'</span>
					<p>'.$row['msg'].'</p>
					</div>
				</div>';
			}
		}
	}
	else
	{
//		$output .= '<div class="text">No message are available. Once you send message they will appear here</div>';
		
		$output .= '<div class="chat incoming">
			<div class="details">
			<p>Hi '.@$_SESSION['fullname'].'<br />
				How can I help you?</p>
			</div>
		</div>';
	}
	echo $output;
}
else
{
//	header("location: ../login.php");
}

?>