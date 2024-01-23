<?php
// Detect the current session
session_start();
// Include the Page Layout header
include("header.php"); 

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

// validating login credentials with database
include_once("mysql_conn.php"); // sql connection string php file
$qry = "SELECT * FROM Shopper WHERE Email LIKE '%$email%' AND Password LIKE '%$pwd%'";
$result = $conn->query($qry);


// To Do 1 (Practical 2): Validate login credentials with database

if ($result->num_rows > 0) {
	// Save user's info in session variables
	while ($row = $result->fetch_array()){
		$_SESSION["ShopperName"] = $row["Name"];
		$_SESSION["ShopperID"] = $row["ShopperID"];
		$sid = $_SESSION["ShopperID"];
			$qry = "SELECT sc.ShopCartID, COUNT(sci.ProductID) AS NumItems FROM ShopCart sc LEFT JOIN ShopCartItem sci ON sc.ShopCartID = sci.ShopCartID WHERE sc.ShopperID=$sid AND sc.OrderPlaced = 0";
			$result = $conn->query($qry);

			if ($result->num_rows > 0) {
				while ($row = $result->fetch_array()){
					$_SESSION["Cart"] = $row["ShopCartID"];
					$_SESSION["NumCartItem"] = $row["NumItems"];
				}
	}
}
	// To Do 2 (Practical 4): Get active shopping cart
	
	// Redirect to home page
	header("Location: index.php");
	exit;
}
else {
	echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
}
$conn->close();
	
// Include the Page Layout footer
include("footer.php");

//if (($email == "ecader@np.edu.sg") && ($pwd == "password"))
?>
