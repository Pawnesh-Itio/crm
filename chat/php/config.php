<?php
session_start();
$hostname	= 'localhost';
$dbname		= 'it_crm_db';
$username	= 'root';
$password	= '';

// Create a MySQLi database connection
$conn = mysqli_connect($hostname, $username, $password, $dbname);

// Check the connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set character set to UTF-8 (optional but recommended)
?>
