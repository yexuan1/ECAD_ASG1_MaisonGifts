<?php
// Include the code that contains shopping cart's functions.
// Current session is detected in "cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");
include("header.php"); // Include the Page Layout header

if (!isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header("Location: login.php");
	exit;
}

echo "<div id='myShopCart' style='margin:auto'>"; // Start a container
if (isset($_SESSION["Cart"])) {
	include_once("mysql_conn.php");

	// Retrieve from database and display shopping cart in a table
	$qry = "SELECT *, (Price*Quantity) AS Total
		FROM ShopCartItem WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["Cart"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();


	if ($result->num_rows > 0) {

		// the page header and header row of shopping cart page
		echo "<p class='page-title' style='text-align:center'>Shopping Cart</p>";
		echo "<div class='table-responsive' >"; // Bootstrap responsive table
		echo "<table class='table table-hover'>"; // Start of table
		echo "<thead class='cart-header'>";
		echo "<tr>";
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price (S$)</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<th width='120px'>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>";
		echo "</thead>";


		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"] = array();


		// Display the shopping cart content

		$totalItems = 0;// Declare a variable to store the total number of items in the cart
		$subTotal = 0; // Declare a variable to compute subtotal before tax
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width:50%'>$row[Name]<br/>";
			echo "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			echo "<td>$formattedPrice</td>";
			echo "<td>"; // Column for update quantity of purchase 
			echo "<form action = 'cartFunctions.php' method='post'>";
			echo "<select name='quantity' onChange='this.form.submit()'>";
			for ($i = 1; $i <= 10; $i++) { // To populate drop-down list from 1 to 10 
				if ($i == $row["Quantity"])
					// Select drop-down list item with value same as the quantity of purchase
					$selected = "selected";
				else
					$selected = ""; // No specific item is selected 
					echo "<option value='$i' $selected>$i</option>";
			}
			echo "</select>";
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "</form>";
			echo "</td>";
			$formattedTotal = number_format($row["Total"], 2);
			echo "<td>$formattedTotal</td>";
			echo "<td>"; // Column for remove item from shopping cart 
			echo "<form action = 'cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "<input type='image' src='images/trash-can.png' title='Remove Item'/>";
			echo "</form>";
			echo "</td>";
			echo "</tr>";


			// Store the shopping cart items in session variable as an associate array
			$_SESSION["Items"][] = array(
				"productId" => $row["ProductID"],
				"name" => $row["Name"],
				"price" => $row["Price"],
				"quantity" => $row["Quantity"]
			);

			// Accumulate the running sub-total
			$subTotal += $row["Total"];
			// Update the total quantity
			$totalItems += $row["Quantity"];

		}
		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table
		// Display the total number of items in the cart
		echo "<p style='text-align:right; font-size:20px'>Total Items in Cart: $totalItems</p>";		
		

		// Display the subtotal at the end of the shopping cart
		echo "<p style='text-align:right; font-size:20px'>
		Subtotal = S$" . number_format($subTotal, 2) . "<br>";
		$_SESSION["SubTotal"] = round($subTotal, 2);

		if ($_SESSION["SubTotal"] > 200)
		{
			
		}

		echo "<form method='POST' action='shoppingCart.php'>";
		echo "<input type='radio' name='deliveryOption' value='normal' /> ";
		echo "<label for='normal'>Normal Delivery $5 (Delivered within 2 working days after an order is placed)</label> <br> ";
		echo "<input type='radio' name='deliveryOption' value='express'/> ";
		echo "<label for='express'>Express Delivery $10 (Delivered within 24 hours after an order is placed)</label><br>";
		echo "<input type='submit' value='Confirm' name='Confirm'>";
		echo "</form>";

		$selectedDeliveryOption = "";

		if (isset($_POST['deliveryOption'])) {
			// Retrieve the selected delivery option
			$selectedDeliveryOption =$_POST['deliveryOption'] ;
	
			
			echo "<td>$selectedDeliveryOption</td>";
	
			// Add your additional processing logic here
		} 
		

		else {
			// Handle the case where deliveryOption is not set or empty
			echo "Please select a delivery option.";
		}


	   

		include_once("mysql_conn.php");

		// Retrieve from database and display shopping cart in a table
		$qry = "SELECT * FROM ShopCart WHERE ShopCartID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $_SESSION["Cart"]);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
	
		if ($result->num_rows > 0) {
			echo "Delivery Charge: S$" . $row["ShipCharge"] . "<br>";
		}


		// Check if the subtotal is more than S$200 and adjust the delivery charge accordingly
		/*
		$selectedDeliveryOption = $_POST['deliveryOption'];
		if (isset($_POST['deliveryOption']))
		{
			if ($selectedDeliveryOption == 'normal') {
				$shipCharge = 5;
			}
	
			else {
				$shipCharge = 10;
			}
	
		}
		
		 if ($_SESSION["SubTotal"] > 200) {
			$shipCharge = 0; // Waive the delivery charge
			echo "Delivery Charge: S$0.00<br>";
		} else {
			echo "Delivery Charge: S$" . number_format($shipCharge ,2 ) . "<br>";
		}
		*/

		// Add PayPal Checkout button on the shopping cart page
		echo "<form method='post' action='checkoutProcess.php'>";
		echo "<input type='image' style='float:right;'
					src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
		echo "</form></p>";
	} else {
		echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection
} 
else {
	echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
?>