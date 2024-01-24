<?php 
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if(isset($_SESSION["OrderID"])) {	
	echo " </br> </br>";
	echo "<h2>Checkout successful. Your order number is $_SESSION[OrderID]</h1>";
	echo "<h3>Thank you for your purchase!&nbsp;&nbsp;";
	echo '<a href="index.php">Continue Shopping</a></h3>';
} 

include("footer.php"); // Include the Page Layout footer
?>
