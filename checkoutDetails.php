<?php
session_start();
include("header.php"); // Include the Page Layout header
include_once("myPayPal.php"); // Include the file that contains PayPal settings
include_once("mysql_conn.php");

if (!isset($_SESSION["ShopperID"])) { // Check if user logged in 
    // redirect to login page if the session variable shopperid is not set
    header("Location: login.php");
    exit;
}

if (isset($_SESSION["Cart"])) {
    $selectedDeliveryOption = "";
    $shipCharge = 0;

    if ($_SESSION["SubTotal"] > 200) {


    } 
    /*else {
        echo "<form method='POST' action='checkoutDetails.php'>";
        echo "<input type='radio' name='deliveryOption' value='normal' /> ";
        echo "<label for='normal'>Normal Delivery $5 (Delivered within 2 working days after an order is placed)</label> <br> ";
        echo "<input type='radio' name='deliveryOption' value='express'/> ";
        echo "<label for='express'>Express Delivery $10 (Delivered within 24 hours after an order is placed)</label><br>";
        echo "<input type='submit' value='Confirm' name='Confirm'>";
        echo "</form>";


        if (isset($_POST['deliveryOption'])) {
            // Retrieve the selected delivery option
            
            if ($_POST['deliveryOption'] = 'normal')
            {
                $shipCharge = 5;
                $_SESSION['deliveryOption'] = $_POST['deliveryOption'];
            }
            else if ($_POST['deliveryOption'] = 'express')
            {
                $shipCharge = 10;
                $_SESSION['deliveryOption'] = $_POST['deliveryOption'];
            }

            echo $shipCharge;
            echo $_POST['deliveryOption'];

            // Add your additional processing logic here
        } 
        else {
            // Handle the case where deliveryOption is not set or empty
            echo "Please select a delivery option.";
        }*/
        else {
			echo "<form method='POST' action='checkoutDetails.php'>";
			echo "<input type='radio' name='deliveryOption' value='normal' /> ";
			echo "<label for='normal'>Normal Delivery $5 (Delivered within 2 working days after an order is placed)</label> <br> ";
			echo "<input type='radio' name='deliveryOption' value='express'/> ";
			echo "<label for='express'>Express Delivery $10 (Delivered within 24 hours after an order is placed)</label><br>";
			echo "<input type='submit' value='Confirm' name='Confirm'>";
			echo "</form>";

			$selectedDeliveryOption = "";

			if (isset($_POST['deliveryOption'])) {
				// Retrieve the selected delivery option
				$selectedDeliveryOption = $_POST['deliveryOption'];
				$_SESSION['deliveryOption'] = $selectedDeliveryOption;


				echo "<td>$selectedDeliveryOption</td>";
                

				// Add your additional processing logic here
			} 
			else {
				// Handle the case where deliveryOption is not set or empty
				echo "Please select a delivery option.";
			}
			
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

    echo $_SESSION["SubTotal"];


    echo "<form method='post' action='checkoutProcess.php'>";
    echo "<input type='image' style='float:right;'
                        src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
    echo "</form></p>";
}

else {
    header("Location: shoppingCart.php");
    exit;
}







include("footer.php"); // Include the Page Layout footer
