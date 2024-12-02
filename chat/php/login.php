<?php
session_start();
include_once "../../application/config/db.php";

$fullname= mysqli_real_escape_string($conn,$_POST['fullname']);
$email	= mysqli_real_escape_string($conn,$_POST['email']);
$mobile	= mysqli_real_escape_string($conn,$_POST['mobile']);

if(!empty($email)&&!empty($mobile))
{
	if(filter_var($email, FILTER_VALIDATE_EMAIL)){
		$sql = mysqli_query($conn, "SELECT *FROM `it_crm_leads` WHERE email = '{$email}'");
		if(mysqli_num_rows($sql)>0){
			$row=mysqli_fetch_assoc($sql);
			
			$status = 'Online';
			//$updateSql = mysqli_query($conn,"UPDATE `it_crm_leads` SET status='{$status}' WHERE unique_id = '{$row['unique_id']}'");
			
			//if($updateSql)
			{
				$_SESSION['fullname']=$row['name'];
				$_SESSION['unique_id']=$row['client_id'];
				$_SESSION['incoming_id']=$row['id'];
				$_SESSION['client_email']=$email;
				echo 'success';
			}
			//header("Location: users.php");
			//exit;
		
		}
		else
		{
			//insert query
			$ran_id = rand(time(),100000000);
			$status = '2';		//status 2 for test
			$source = 5;		//source 5 for web chat

//			$status = 'Online';

			$insert_query = mysqli_query($conn, "INSERT INTO `it_crm_leads` (`client_id`, `name`,`email`, `phonenumber`, `source`, `status`,`dateadded`) VALUES({$ran_id}, '{$fullname}','{$email}', '{$mobile}', '{$source}', '{$status}', NOW())");
			if($insert_query)
			{
				$_SESSION['incoming_id']=mysqli_insert_id($conn);
				$_SESSION['unique_id']	=$ran_id;
				$_SESSION['fullname']	=$fullname;
				echo 'success';
			}
		}
	}
	else
	{
		echo "$email is invalid!";
	}
}
else
{
	echo 'All input fields are required!';
}
?>
<?php mysqli_close($conn);?>