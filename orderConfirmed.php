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
	echo "</div>";


	echo "<div class='container mt-4'>
    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>";
	foreach ($_SESSION['Items'] as $key => $item) {
		$quantity = $item['quantity'];
		$price = $item['price'];
		$name = $item['name'];
		$pid = $item['productId'];
		$formattedPrice = number_format($price, 2);
		$formattedTotal = number_format($price * $quantity, 2);
		echo "<tr>
    	<td>$name<br/>Product ID: $pid</td>
        <td>S$$formattedPrice</td>
        <td>$quantity</td>
    	<td>S$$formattedTotal</td>
        </tr>
        </tbody>
    </table>";
	}
	$finalTotal = $_SESSION["SubTotal"] + $_SESSION["Tax"] +$_SESSION["ShipCharge"];
	echo "<br><p style='text-align:right; font-size:15px'>
    Delivery Mode : " . $_SESSION["DeliveryMode"] . "<br>";
	echo "<p style='text-align:right; font-size:15px'>
    Delivery Date : " . $_SESSION["deliveryDate"] . "<br>";
    echo "<p style='text-align:right; font-size:15px'>
    Delivery Time : " . $_SESSION["DeliveryTime"] . "<br>";
    echo "<p style='text-align:right; font-size:15px'>
    Subtotal = S$" . number_format($_SESSION["SubTotal"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:15px'>
    Total Tax Amount = S$" . number_format($_SESSION["Tax"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:15px'>
    Shipping Charges = S$" . number_format($_SESSION["ShipCharge"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:20px'>
    Final Amount = S$" . number_format($finalTotal, 2) . "<br>";

	echo "<h1 class='col-sm-7' >Rate your experience</h1>";
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
		</form> </div>";




}





include("footer.php"); // Include the Page Layout footer
?>