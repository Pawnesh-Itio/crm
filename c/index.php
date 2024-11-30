<?php 
include_once "header.php";
include_once "../application/config/db.php";
//$_SESSION['unique_id']='11';
if(isset($_SESSION['unique_id'])&&$_SESSION['unique_id'])
{
//	header("Location:php/users.php");
	header("Location:chat.php");
	exit;
}
else
{
	header("Location:login.php");
	exit;
}?>