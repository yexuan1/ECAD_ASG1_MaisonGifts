<html>

<head>
    <style>
        <?php session_start();
        include("css/site.css"); ?>
    </style>
</head>

<?php
// Detect the current session
include("header.php"); // Include the Page Layout header

if (isset($_SESSION["OrderID"])) {
    echo " </br> </br>";
    //echo "<div class='container-fluid title'>";
    echo "<h2 style='text-align:center;'><strong>Thank You For Your Purchase $_SESSION[ShipName]! </strong></h2> ";
    echo "<h3 style='text-align:center;'>Checkout Successful. Your Order Number is $_SESSION[OrderID]</h3>";
    echo "<div class='container text-center'>";
    echo "<form method='post' action='index.php'>";
    echo "<input type='submit' class='btn btn-primary' value='Continue Shopping' name='return'>";
    echo "</form>";
    echo "</div>";
    //echo "</div>";

    echo "<div class='container border border-dark rounded p-4' >";
echo "<p style='text-align:left; font-size:20px'>
    Receiver Name : " . $_SESSION["ShipName"] . "<br></p>";
echo "<p style='text-align:left; font-size:20px'>
    Receiver Address : " . $_SESSION["ShipAddress"] . "<br></p>";
echo "<p style='text-align:left; font-size:20px'>
    Email : " . $_SESSION["ShipEmail"] . "<br></p>";
echo "<p style='text-align:left; font-size:20px'>
    Message : " . $_SESSION["Message"] . "<br></p>";
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
        ";
    }
    echo "</tbody>
    </table>";
    $finalTotal = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
    echo "<br><p style='text-align:left; font-size:20px'>
    Delivery Mode : " . $_SESSION["DeliveryMode"] . "<br>";
    echo "<p style='text-align:left; font-size:20px'>
    Delivery Date : " . $_SESSION["deliveryDate"] . "<br>";
    echo "<p style='text-align:left; font-size:20px'>
    Delivery Time : " . $_SESSION["DeliveryTime"] . "<br>";
    echo "<p style='text-align:right; font-size:15px'>
    Subtotal = S$" . number_format($_SESSION["SubTotal"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:15px'>
    Total Tax Amount = S$" . number_format($_SESSION["Tax"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:15px'>
    Shipping Charges = S$" . number_format($_SESSION["ShipCharge"], 2) . "<br>";
    echo "<p style='text-align:right; font-size:20px'><strong>
    Final Amount = S$" . number_format($finalTotal, 2) . "</strong><br>";

    echo "<p style='text-align:center; color:red; font-size:15px;'>
	For Order Enquiries: maisongiftshelp@gmail.com" . "<br>";

    echo "<div class='cont-shop'>
			<a id='cont-shop' href='feedbackPage.php'>Send Feeback</a>
		 </div>";
}





include("footer.php"); // Include the Page Layout footer
?>

</html>