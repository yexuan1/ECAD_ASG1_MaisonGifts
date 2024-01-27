<?php
session_start(); // Detect the current session

//Read the data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = "(65) " . $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$shopperId = $_SESSION["ShopperID"];

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
//Define the select statement for email where the email in the database record matches the email enterd by the shopper
$qry = "UPDATE Shopper set Name=?, Address=?, Country=?, Phone=?, Email=?, Password=? where ShopperID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("ssssssi",$name,$address,$country, $phone,$email,$password,$shopperId);

if($stmt->execute()){
    include("header.php");
    echo "<br/>";
    echo $Message = "Update succesful!<br />";
    include("footer.php");
}

$stmt->close();
$conn->close();

?>