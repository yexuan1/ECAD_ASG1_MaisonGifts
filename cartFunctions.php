<?php 
session_start();
if (isset($_POST['action'])) {
 	switch ($_POST['action']) {
    	case 'add':
        	addItem();
            break;
        case 'update':
            updateItem();
            break;
		case 'remove':
            removeItem();
            break;
    }
}

function addItem() {
	// Check if user logged in 
	if (! isset($_SESSION["ShopperID"])) {
		// redirect to login page if the session variable shopperid is not set
		header ("Location: login.php");
		exit;
	}

	// Write code to implement: if a user clicks on "Add to Cart" button, insert/update the 
	// database and also the session variable for counting number of items in shopping cart.
	include_once("mysql_conn.php"); // Establish database connection handle: $conn
	// Check if a shopping cart exist, if not create a new shopping cart
	if (! isset($_SESSION["Cart"])) {
		// Createa shopping cart for the shopper
		$qry = "INSERT INTO shopcart(ShopperID) VALUES(?)";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i",$_SESSION["ShopperID"]);
		$stmt->execute();
		$stmt->close();
		$qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
		$result = $conn->query($qry);
		$row = $result->fetch_array();
		$_SESSION["Cart"] = $row["ShopCartID"];

	}
  	// If the ProductID exists in the shopping cart, 
  	// update the quantity, else add the item to the Shopping Cart.
  	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	$qry = "SELECT * FROM ShopCartItem WHERE ShopCartID=? AND ProductID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("ii",$_SESSION["Cart"], $pid);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	$addNewItem = 0;
	if ($result->num_rows > 0){ // Selected product exists in shopping cart
		// increase the quantity of purchase
		$qry = "UPDATE ShopCartItem SET Quantity=LEAST(Quantity+?,10)
				WHERE ShopCartID=? AND ProductID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("iii", $quantity, $_SESSION["Cart"], $pid);
		$stmt->execute();
		$stmt->close();
	}
	else { //Selected product has yet to be added to shopping cart
		$qry = "INSERT INTO ShopCartItem(ShopCartID, ProductID, Price, Name, Quantity)
				SELECT ?, ?, Price, ProductTitle, ? FROM Product WHERE ProductID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("iiii",$_SESSION["Cart"], $pid, $quantity, $pid);
		$stmt->execute();
		$stmt->close();
		$addNewItem = 1;
	}
	
	
  	$conn->close();
  	// Update session variable used for counting number of items in the shopping cart.
	if (isset($_SESSION["NumCartItem"])) {
		$_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] + $addNewItem;
	}
	else {
		$_SESSION["NumCartItem"] = 1;
	}
	// Redirect shopper to shopping cart page
	header ("Location: shoppingCart.php");
	exit;
}

function updateItem() {
	// Check if shopping cart exists 
	if (! isset($_SESSION["Cart"])) {
		// redirect to login page if the session variable cart is not set
		header ("Location: login.php");
		exit;
	}

	// Write code to implement: if a user clicks on "Update" button, update the database
	// and also the session variable for counting number of items in shopping cart.
	$cartid = $_SESSION["Cart"];
	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	include_once("mysql_conn.php"); // Establish database connection handle: $conn
	$qry = "UPDATE ShopCartItem SET Quantity=? WHERE ProductID=? AND ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("iii", $quantity, $pid, $cartid);
	$stmt->execute();
	$stmt->close();
	$conn->close();



	header ("Location: shoppingCart.php");
	exit;



}


function removeItem() {
    // Check if shopping cart exists 
    if (!isset($_SESSION["Cart"])) {
        // redirect to login page if the session variable cart is not set
        header("Location: login.php");
        exit;
    }

    // Write code to implement: if a user clicks on "Remove" button, update the database
    // and also the session variable for counting the number of items in the shopping cart.

    $cartid = $_SESSION["Cart"];
    $pid = $_POST["product_id"];

    include_once("mysql_conn.php"); // Establish database connection handle: $conn

    // Delete the item from the database
    $qry = "DELETE FROM ShopCartItem WHERE ProductID=? AND ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("ii", $pid, $cartid);
    $stmt->execute();
    $stmt->close();

    // Update the session variable for counting the number of items in the shopping cart
    if (isset($_SESSION["NumCartItem"])) {
        $_SESSION["NumCartItem"] = $_SESSION["NumCartItem"] - 1;
        if ($_SESSION["NumCartItem"] < 0) {
            $_SESSION["NumCartItem"] = 0;
        }
    }

    $conn->close();
    header("Location: shoppingCart.php");
    exit;
}

function shippingRate() {
	    // Check if shopping cart exists 
	if (!isset($_SESSION["Cart"])) {
        // redirect to login page if the session variable cart is not set
        header("Location: login.php");
        exit;
    }

	$shipOption = $_POST['deliveryOption'];

	$cartid = $_SESSION["Cart"];
	if ($_SESSION["SubTotal"] > 200) {
		$shipCharge = 0; // Waive the delivery charge
		$shipOption = "express";
	} 

	else if ($shipOption = 'express')
	{
		$shipCharge = 10;
	}

	else if ($shipOption = 'normal')
	{
		$shipCharge = 5;
	}


	include_once("mysql_conn.php"); // Establish database connection handle: $conn


    $qry = "UPDATE ShopCart SET ShipCharge=? WHERE ShopCartID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("di", $shipCharge, $cartid);
    $stmt->execute();
    $stmt->close();


	header("Location: shoppingCart.php");
    exit;
}


?>
