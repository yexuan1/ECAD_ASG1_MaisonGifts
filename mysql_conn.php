<?php
//Connection Parameters
$servername = 'localhost:3307';
$username = 'root';
$userpwd = '';
$dbname = 'maisongifts'; 

// Create connection
$conn = new mysqli($servername, $username, $userpwd, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);	
}
?>
