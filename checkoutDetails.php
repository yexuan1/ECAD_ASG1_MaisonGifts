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
    $_SESSION["DeliveryTime"] = "";
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
            echo "
        <div class='col-md-7 col-lg-8'>
        <h4 class='mb-3'>Billing address</h4>
        <form class='needs-validation' novalidate>
            <div class='row g-3'>
                <div class='col-sm-6'>
                    <label for='firstName' class='form-label'>First name</label>
                    <input type='text' class='form-control' id='firstName' placeholder='' value='' required>
                    <div class='invalid-feedback'>
                        Valid first name is required.
                    </div>
                </div>
        
                <div class='col-sm-6'>
                    <label for='lastName' class='form-label'>Last name</label>
                    <input type='text' class='form-control' id='lastName' placeholder='' value='' required>
                    <div class='invalid-feedback'>
                        Valid last name is required.
                    </div>
                </div>
        
                <div class='col-12'>
                    <label for='username' class='form-label'>Username</label>
                    <div class='input-group has-validation'>
                        <span class='input-group-text'>@</span>
                        <input type='text' class='form-control' id='username' placeholder='Username' required>
                        <div class='invalid-feedback'>
                            Your username is required.
                        </div>
                    </div>
                </div>
        
                <div class='col-12'>
                    <label for='email' class='form-label'>Email <span class='text-body-secondary'>(Optional)</span></label>
                    <input type='email' class='form-control' id='email' placeholder='you@example.com'>
                    <div class='invalid-feedback'>
                        Please enter a valid email address for shipping updates.
                    </div>
                </div>
        
                <div class='col-12'>
                    <label for='address' class='form-label'>Address</label>
                    <input type='text' class='form-control' id='address' placeholder='1234 Main St' required>
                    <div class='invalid-feedback'>
                        Please enter your shipping address.
                    </div>
                </div>
        
                <div class='col-12'>
                    <label for='address2' class='form-label'>Address 2 <span class='text-body-secondary'>(Optional)</span></label>
                    <input type='text' class='form-control' id='address2' placeholder='Apartment or suite'>
                </div>
        
                <div class='col-md-5'>
                    <label for='country' class='form-label'>Country</label>
                    <select class='form-select' id='country' required>
                        <option value=''>Choose...</option>
                        <option>United States</option>
                    </select>
                    <div class='invalid-feedback'>
                        Please select a valid country.
                    </div>
                </div>
        
                <div class='col-md-4'>
                    <label for='state' class='form-label'>State</label>
                    <select class='form-select' id='state' required>
                        <option value=''>Choose...</option>
                        <option>California</option>
                    </select>
                    <div class='invalid-feedback'>
                        Please provide a valid state.
                    </div>
                </div>
        
                <div class='col-md-3'>
                    <label for='zip' class='form-label'>Zip</label>
                    <input type='text' class='form-control' id='zip' placeholder='' required>
                    <div class='invalid-feedback'>
                        Zip code required.
                    </div>
                </div>
            </div>
        </form>
        </div>";

            echo "<p>Shipping Details</p>";
            echo "<form method='POST' action = 'checkoutDetails.php'>";
            echo "<label for='name'> Name: </label>&nbsp";
            echo "<input type='text' name='name' value='$row[Name]' required /><br></br>";
            echo "<label for='address'> Address: </label>&nbsp";
            echo "<input type='text' name='address' value='$row[Address]' required /><br></br>";
            echo "<label for='country'> Country: </label>&nbsp";
            echo "<input type='text' name='country' value='$row[Country]' required  /><br></br>";
            echo "<label for='phone'> Phone: </label>&nbsp";
            echo "<input type='text' name='phone' value='$row[Phone]' required /><br></br>";
            echo "<label for='email'> Email: </label>&nbsp";
            echo "<input type='text' name='email' value='$row[Email]' required /><br></br>";
            echo "<label for='message'> Message: </label>&nbsp";
            echo "<input type='text' name='message' value='' /><br></br>";

            //delivery option

            if ($_SESSION["SubTotal"] > 200) {

                echo "<p> Your Order Total is over $200. You are Eligible for FREE Express Shipping</p>";
                $_SESSION["ShipCharge"] = 0;
                $_SESSION["DeliveryMode"] = "Express";
            } else {
                echo "<p>Select Preferred Delivery Mode:</p><br>";
                echo "<form method='POST' action='checkoutDetails.php'>";
                echo "<label for='normal'>Normal Delivery $5 (Delivered within 2 working days after an order is placed)</label>&nbsp";
                echo "<input type='radio' name='deliveryOption' value='Normal' /> <br></br>";
                echo "<label for='express'>Express Delivery $10 (Delivered within 24 hours after an order is placed)</label>&nbsp";
                echo "<input type='radio' name='deliveryOption' value='Express'/> <br></br>";

            }

            //delivery date
            echo "<label for='dateDropdown'>Preferred Delivery Date:</label>&nbsp";
            echo "<select name='dateDropdown'>";

            $today = new DateTime('tomorrow');
            $endDate = new DateTime();
            $endDate->add(new DateInterval('P14D')); // Add 14 days to today's date

            while ($today <= $endDate) {
                $dateString = $today->format('Y-m-d');

                if ($dateString == $selectedDate) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }

                echo "<option value='$dateString' $selected>$dateString</option>";

                $today->add(new DateInterval('P1D')); // Move to the next day
            }

            echo "</select><br></br>";

            //delivery time

            echo "<p>Preferred Delivery Time:</p>";
            echo "<label for='3pm-6pm'>3pm - 6pm </label> ";
            echo "<input type='radio' name='deliveryTime' value='9am-12pm'/> &nbsp&nbsp";
            echo "<label for='12pm-3pm'>12pm - 3pm </label> ";
            echo "<input type='radio' name='deliveryTime' value='12pm-3pm'/> &nbsp&nbsp ";
            echo "<label for='3pm-6pm'>3pm - 6pm </label> ";
            echo "<input type='radio' name='deliveryTime' value='3pm-6pm'/> <br></br>";

            echo "<input type='submit' value='Confirm' name='Confirm'>";
            echo "</form>";

        }

    }

    if (
        isset($_POST["name"]) &&
        isset($_POST["phone"]) &&
        isset($_POST["address"]) &&
        isset($_POST["country"]) &&
        isset($_POST["email"]) &&
        isset($_POST["message"]) &&
        isset($_POST["dateDropdown"])
    ) {
        // Add shipping information to the shippingItems array
        $_SESSION["shippingItems"][] = array(
            "shipName" => $_POST["name"],
            "shipPhone" => $_POST["phone"],
            "shipAddress" => $_POST["address"],
            "shipCountry" => $_POST["country"],
            "shipEmail" => $_POST["email"],
            "message" => $_POST["message"],
            "deliveryDate" => $_POST["dateDropdown"],
        );

        foreach ($_SESSION["shippingItems"] as $shippingItem) {
            echo $shippingItem["shipName"];
        }

    } else {
        // Handle the case where some POST variables are not set
        echo "Error: Some required fields are missing.</br>";
    }





    /* if ($_SESSION["SubTotal"] > 200) {

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
 */

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

    echo "<p style='text-align:right; font-size:15px'>
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




    if ($_SESSION["DeliveryMode"] != "" && $_SESSION["DeliveryTime"] != "" && isset($_POST['deliveryTime'])) {

        echo "<form method='post' action='checkoutProcess.php'>";
        echo "<input type='image' style='float:right;'
                    src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>";
        echo "</form></p>";
        echo "<br></br>";
    } else {
        echo "<h3 style='text-align:center; color:red;'>Please Select a Delivery Mode and Time!</h3>";
    }
    echo "<form method='post' action='shoppingCart.php'>";
    echo "<input type='submit' style='float:left;'
            value='Return To Shopping Cart' name='return'";
    echo "</form></p>";
    echo "<br></br>";

} else {
    header("Location: shoppingCart.php");
    exit;
}







include("footer.php"); // Include the Page Layout footer
