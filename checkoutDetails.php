<?php
session_start();
include("header.php"); // Include the Page Layout header
include_once("myPayPal.php"); // Include the file that contains PayPal settings

echo "<p class='page-title' style='text-align:center'>Checkout Details</p>";

if (!isset($_SESSION["ShopperID"])) { // Check if user logged in 
    // redirect to login page if the session variable shopperid is not set
    header("Location: login.php");
    exit;
}

if (isset($_SESSION["Cart"])) {
    $selectedDeliveryOption = "";
    $_SESSION["ShipCharge"] = 0;
    $_SESSION["DeliveryMode"] = "";
    $shipCharge = 0;
    $_SESSION["shippingItems"] = array();

    include_once("mysql_conn.php");

    $sid = $_SESSION["ShopperID"];

    $qry = "SELECT * FROM shopper WHERE ShopperID = ? ";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $sid);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            echo "<p>Shipping Details</p>";
            echo "<form method='POST' action = 'checkoutDetails.php'>";
            echo "<label for='name'> Name: </label>";
            echo "<input type='text' name='name' value='$row[Name]' /><br></br>";
            echo "<label for='address'> Address: </label>";
            echo "<input type='text' name='address' value='$row[Address]' /><br></br>";
            echo "<label for='country'> Country: </label>";
            echo "<input type='text' name='country' value='$row[Country]' /><br></br>";
            echo "<label for='phone'> Phone: </label>";
            echo "<input type='text' name='phone' value='$row[Phone]' /><br></br>";
            echo "<label for='email'> Email: </label>";
            echo "<input type='text' name='email' value='$row[Email]' /><br></br>";
            echo "<input type='submit' value='Confirm' name='Confirm'>";
            echo "</form>";

        }
       
    }

    if (
        isset($_POST["name"]) &&
        isset($_POST["phone"]) &&
        isset($_POST["address"]) &&
        isset($_POST["country"]) &&
        isset($_POST["email"])
    ) {
        // Add shipping information to the shippingItems array
        $_SESSION["shippingItems"][] = array(
            "shipName"    => $_POST["name"],
            "shipPhone"   => $_POST["phone"],
            "shipAddress" => $_POST["address"],
            "shipCountry" => $_POST["country"],
            "shipEmail"   => $_POST["email"]
        );

        echo $_SESSION["shippingItems"]["shipName"];
    } else {
        // Handle the case where some POST variables are not set
        echo "Error: Some required fields are missing.";
    }

  

    echo implode("", $_SESSION["shippingItems"]);


    if ($_SESSION["SubTotal"] > 200) {

        echo "<p> Your Order Total is over $200. You are Eligible for FREE Express Shipping</p>";
        $_SESSION["ShipCharge"] = 0;
        $_SESSION["DeliveryMode"] = "Express";
    } else {
        echo "<form method='POST' action='checkoutDetails.php'>";
        echo "<input type='radio' name='deliveryOption' value='Normal' /> ";
        echo "<label for='normal'>Normal Delivery $5 (Delivered within 2 working days after an order is placed)</label> <br> ";
        echo "<input type='radio' name='deliveryOption' value='Express'/> ";
        echo "<label for='express'>Express Delivery $10 (Delivered within 24 hours after an order is placed)</label><br>";
        echo "<input type='submit' value='Confirm' name='Confirm'>";
        echo "</form>";


        if (isset($_POST['deliveryOption'])) {
            // Retrieve the selected delivery option

            $selectedDeliveryOption = $_POST['deliveryOption'];

            if ($selectedDeliveryOption == 'Normal') {
                $shipCharge = 5;
                $_SESSION["DeliveryMode"] = "Normal";
            } else if ($selectedDeliveryOption == 'Express') {
                $shipCharge = 10;
                $_SESSION["DeliveryMode"] = "Express";
            }

            include_once("mysql_conn.php");

            // Retrieve from database and display shopping cart in a table
            $qry = "UPDATE ShopCart SET ShipCharge=? WHERE ShopCartID=?";
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("di", $shipCharge, $sid);
            $stmt->execute();
            $stmt->close();
            $_SESSION["ShipCharge"] = $shipCharge;

            // Add your additional processing logic here
        } else {
            // Handle the case where deliveryOption is not set or empty
            echo "Please Select a Delivery Option!";
        }

    }

    //calculate taxes 
    //retrieve taxes    
    $currentDate = date("Y-m-d");
    $_SESSION["Tax"] = 0;

    $qry = "SELECT * FROM GST WHERE EffectiveDate <= '$currentDate' ORDER BY EffectiveDate DESC LIMIT 1";
    $stmt = $conn->prepare($qry);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["Tax"] = ($row["TaxRate"] / 100) * $_SESSION["SubTotal"];
    }

    $finalTotal = $_SESSION["Tax"] + $_SESSION["SubTotal"] + $_SESSION["ShipCharge"];

    echo "<p style='text-align:right; font-size:20px'>
    Subtotal = S$" . number_format($_SESSION["SubTotal"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:20px'>
    Total Tax Amount = S$" . number_format($_SESSION["Tax"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:20px'>
    Delivery Mode : " . $_SESSION["DeliveryMode"] . "<br>";
    echo "<p style='text-align:right; font-size:20px'>
    Shipping Charges = S$" . number_format($_SESSION["ShipCharge"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:20px'>
    Final Amount = S$" . number_format($finalTotal, 2) . "<br>";

    if ($_SESSION["DeliveryMode"] != "") {

        echo "<form method='post' action='checkoutProcess.php'>";
        echo "<input type='image' style='float:right;'
                    src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
        echo "</form></p>";
    } else {
        echo "<h3 style='text-align:center; color:red;'>Please Select a Delivery Mode!</h3>";
    }

} else {
    header("Location: shoppingCart.php");
    exit;
}







include("footer.php"); // Include the Page Layout footer
