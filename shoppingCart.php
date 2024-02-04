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
	$qry = "SELECT p.ProductID, p.ProductImage, p.Offered, p.OfferedPrice, p.OfferStartDate, p.OfferEndDate , sci.ShopCartID, sci.ProductID, sci.Price, sci.Name, sci.Quantity, (sci.Price*sci.Quantity) AS Total
		FROM ShopCartItem sci INNER JOIN product p  on sci.ProductID=p.ProductID
		WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["Cart"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();


	if ($result->num_rows > 0) {

		echo "<div class='container'>";
		echo "<div class='row'>";
		echo "<div class='col-md-12'>";
		
		// Enclosing card div with border-radius
		echo "<div class='card text-center' style='border-radius: 25px; margin-top: 40px; background-color: #f7f7f2;' >";
		// the page header and header row of shopping cart page
		echo "<p class='page-title text-center'>Shopping Cart</p>";
		echo "<div class='table-responsive'>"; 
		echo "<table class='table table-hover'>";
		echo "<thead class='cart-header'>";
		echo "<tr>";
		echo "<th>Image</th>";
		echo "<th width='250px'>Item</th>";
		echo "<th width='90px'>Price (S$)</th>";
		echo "<th width='60px' style='text-align: right;'>Qty</th>";
		echo "<th width='120px'>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>";
		echo "</thead>";
		// To Do 5 (Practical 5):
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"]=array();
		
		// Display the shopping cart content
		$totalItems = 0; // Declare a variable to store the total number of items in the cart
		$subTotal = 0; // Declare a variable to compute subtotal before tax
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {

			$onOffer = $row["Offered"];
			if ($onOffer == 1 && (date("Y-m-d") >= $row["OfferStartDate"]) && (date("Y-m-d") <= $row["OfferEndDate"])) {
				$row["Total"] = $row["OfferedPrice"] * $row["Quantity"];
				echo "<tr>";
				echo "<td><img src='./Images/products/$row[ProductImage]' alt='Product Image' style='max-width: 100px;'></td>";
				echo "<td style='width:50%'>$row[Name]<br/>";
				echo "Product ID: $row[ProductID]</td>";
				$formattedPrice = number_format($row["OfferedPrice"], 2);
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

				$_SESSION["Items"][] = array(
					"productId" => $row["ProductID"],
					"name" => $row["Name"],
					"price" => $row["OfferedPrice"],
					"quantity" => $row["Quantity"]
				);
				
			}
			 else {
				// Calculate the total without the discount
				$row["Total"] = $row["Price"] * $row["Quantity"];
				echo "<tr>";
				echo "<td><img src='./Images/products/$row[ProductImage]' alt='Product Image' style='max-width: 100px;'></td>";
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

				$_SESSION["Items"][] = array(
					"productId" => $row["ProductID"],
					"name" => $row["Name"],
					"price" => $row["Price"],
					"quantity" => $row["Quantity"]
				);
			}

			// Store the shopping cart items in session variable as an associate array
			$subTotal += $row["Total"];
			// Update the total quantity
			$totalItems += $row["Quantity"];
		}
		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table
		// Display the total number of items in the cart
		echo "<p style=' text-align:right; font-size:20px; margin-right:15px;'>Total Items in Cart: $totalItems</p>";
    
		// Display the subtotal at the end of the shopping cart
		echo "<p style=' text-align:right; font-size:20px; margin-right:15px;'>Subtotal = S$" . number_format($subTotal, 2) . "<br>";
		$_SESSION["SubTotal"] = round($subTotal, 2);
		
		// Checkout form
		echo "<form method='post' action='checkoutDetails.php'>";
		echo "<input type='submit' value='Confirm' name='Confirm' class='btn btn-primary btn-sm' >";
		echo "</form>";
				
		echo "</div>"; // End of card div
		echo "</div>"; // End of single column
		echo "</div>"; // End of row
		echo "</div>"; // End of container
	} else {
		echo "<h3 style='text-align:center; color:red; margin-top: 20px;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection
} else {
	echo "<h3 style='text-align:center; color:red; margin-top: 20px;'>Empty shopping cart!</h3>";
}
echo "</div>"; // End of container
include("footer.php"); // Include the Page Layout footer
