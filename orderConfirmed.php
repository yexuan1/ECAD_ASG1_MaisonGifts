<head>
	<link rel="stylesheet" href="css/site.css">
	<script src="https://kit.fontawesome.com/fc8e0fb32a.js" crossorigin="anonymous"></script>
</head>

<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header

if (isset($_SESSION["OrderID"])) {
	echo " </br> </br>";
	echo "<div class='container-fluid title'>";
	echo "<h3 class='col-sm-7' >Thank you for your purchase!&nbsp;&nbsp;</h3>";
	echo "<h2 class='col-sm-8'>Checkout successful. Your order number is $_SESSION[OrderID]</h2>";
	echo "<h1 class='col-sm-7' >Rate your experience</h1>";
	echo "</div>";
	echo "<form action='addFeedback.php' method='post'>
			<div class='star'>
					<input class='ranking-star' type='radio' name='radio' id='rate-5' value='5'>
					<label for='rate-5' class='fas fa-star star'></label>
					<input class='ranking-star' type='radio' name='radio' id='rate-4' value='4'>
					<label for='rate-4' class='fas fa-star'></label>
					<input class='ranking-star' type='radio' name='radio' id='rate-3' value='3'>
					<label for='rate-3' class='fas fa-star'></label>
					<input class='ranking-star' type='radio' name='radio' id='rate-2' value='2'>
					<label for='rate-2' class='fas fa-star'></label>
					<input class='ranking-star' type='radio' name='radio' id='rate-1' value='1'>
					<label for='rate-1' class='fas fa-star'></label>

					<header></header>

					<div class='form'>
						<div class='customer-feedback'>
							<input class='subject' name='subject' type='text' placeholder='subject title'> 
							<textarea cols='30' id='feedback-content' name='feedback-content' placeholder='Write your suggestions...'></textarea>
						</div>
						<div class='btn'>
							<button type='submit' name='submit'>Send</button>
						</div>
					</div>
				</div>
		</form>";
	echo $_SESSION["deliveryDate"];
}

include("footer.php"); // Include the Page Layout footer
?>