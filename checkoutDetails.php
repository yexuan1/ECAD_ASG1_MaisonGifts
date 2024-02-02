<head>
    <link rel="stylesheet" href="css/checkout.css">
    <script src="https://kit.fontawesome.com/fc8e0fb32a.js" crossorigin="anonymous"></script>
    <script src="checkout.js"></script>
</head>

<?php
session_start();
include("header.php"); // Include the Page Layout header
include_once("myPayPal.php"); // Include the file that contains PayPal settings

echo "<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>";
echo "<br><p class='page-title' style='text-align:center'>Checkout Details</p>";


if (!isset($_SESSION["ShopperID"])) { // Check if user logged in 
    // redirect to login page if the session variable shopperid is not set
    header("Location: login.php");
    exit;
}



if (isset($_SESSION["Cart"])) {

    $selectedDeliveryOption = "";
    $_SESSION["ShipCharge"] = 0;
    $_SESSION["DeliveryMode"] = "";
    $_SESSION["DeliveryTime"] = "";
    $_SESSION["Message"] = "";
    $shipCharge = 0;

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
            echo "
            
        <div class='col-md-7 col-lg-8'>
        <form method='POST' action='checkoutDetails.php'>
            <div class='row g-3'>
              
        
                <div class='col-12'>
                    <label for='message' class='form-label'>Message <span class='text-body-secondary'>(Optional)</span></label>
                    <input type='text' class='form-control' name='message' placeholder='Your Message Here:' value=''>
                </div>";

                if ($_SESSION["SubTotal"] > 200) {

                    echo "<p> Your Order Total is over $200. You are Eligible for FREE Express Shipping</p>";
                    $_SESSION["ShipCharge"] = 0;
                    $_SESSION["DeliveryMode"] = "Express";
                } else {
                    echo "<div class='col-12'>
                    <label for='deliveryOption' class='form-label'>Select Preferred Delivery Mode:</label>
                    <select class='form-select' name='deliveryOption' required>
                        <option name='deliveryOption' value='Normal'>Normal Delivery $5 (Delivered within 2 working days after an order is placed)</option>
                        <option name='deliveryOption' value='Express'>Express Delivery $10 (Delivered within 24 hours after an order is placed)</option>
                    </select>
                    <div class='invalid-feedback'>
                        Please provide a valid state.
                    </div>
                </div>";
    
                }
        
            echo "</div> <br> 

            <div class='col-sm-6'>
                    <label for='deliveryTime' class='form-label'>Select Preferred Delivery Time:</label>
                    <select class='form-select' name='deliveryTime' required>
                        <option name='deliveryTime' value='9am-12pm'>9am - 12pm </option>
                        <option name='deliveryTime' value='12pm-3pm'>12pm - 3pm </option>
                        <option name='deliveryTime' value='3pm-6pm'>3pm - 6pm </option>
                    </select>
                    <div class='invalid-feedback'>
                        Please provide a valid state.
                    </div>
                </div> <br> </br>";

            echo "<button class='w-100 btn btn-primary btn-lg' name='Confirm' value='Confirm'>Confirm</button>";
            echo "</form>";
            echo "</div> ";

        }

    }
 
        if (isset($_POST['message'])) 
        {
            $_SESSION['Message'] = $_POST['message'];
        }


    if (isset($_POST['deliveryTime'])) {
        // Retrieve the selected delivery option

        $selectedDeliveryTime = $_POST['deliveryTime'];

        if ($selectedDeliveryTime == '9am-12pm') {
            $_SESSION["DeliveryTime"] = "9am-12pm";
        } else if ($selectedDeliveryTime == '12pm-3pm') {
            $_SESSION["DeliveryTime"] = "12pm-3pm";
        } else if ($selectedDeliveryTime == "3pm-6pm") {
            $_SESSION["DeliveryTime"] = "3pm-6pm";
        }

    }

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

    echo "<br><p style='text-align:right; font-size:15px'>Message : " . $_SESSION["Message"] ;
    echo "<br><p style='text-align:right; font-size:15px'>
    Delivery Mode : " . $_SESSION["DeliveryMode"] . "<br>";
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




    if ($_SESSION["DeliveryMode"] != "" && $_SESSION["DeliveryTime"] != "") {

        echo "<form method='post' action='checkoutProcess.php'>";
        echo "<input type='image' style='float:right;'
                    src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
        echo "</form></p>";
        echo "<br></br>";
    } else {
        echo "<h3 style='text-align:center; color:red;'>Please Select a Delivery Mode and Time!</h3>";
    }
    echo "<form method='post' action='shoppingCart.php'>";
    echo "<input type='submit' style='float:left;' value='Return To Shopping Cart' name='return'>";
    echo "</form></p>";
    echo "<br></br>";

} else {
    header("Location: shoppingCart.php");
    exit;
}







include("footer.php"); // Include the Page Layout footer
