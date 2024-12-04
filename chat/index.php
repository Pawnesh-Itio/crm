<?php 
ob_start(); // Start output buffering to prevent "headers already sent" error
include_once "header.php";
include_once "../application/config/db.php";

if(isset($_SESSION['unique_id'])&&$_SESSION['unique_id'])
{
	header("Location:chat.php");
	exit;
}
else
{
	header("Location:login.php");
	exit;
	ob_end_flush(); // End output buffering and flush the content
}?>