<?php
//Connection Parameters
$servername = '';
$username = 'root';
$userpwd = '';
$dbname = 'maisongifts';

// Create connection
$conn = new mysqli($servername, $username, $userpwd, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
