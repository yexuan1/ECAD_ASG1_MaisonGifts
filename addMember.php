<?php
session_start(); // Detect the current session

//Read the data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$selectedQuestion = $_POST["security-question"];
$selectAnswer = $_POST["answer"];

$questionList = array(
    "what is your shopper name",
    "when was your last purchase date",
    "what is your email",
    "when was your last login"
);

$questionIndex = $questionList[$selectedQuestion];

// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

//Define the INSERT SQL statement
$qry = "INSERT INTO Shopper (Name, Address, Country, Phone, Email, Password, PwdQuestion, PwdAnswer) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"; //??? are placeholders
$stmt = $conn->prepare($qry);
// "ssssss" - 6 string parameters
$stmt->bind_param("ssssssss", $name, $address, $country, $phone, $email, $password, $questionIndex, $selectAnswer);

if ($stmt->execute()) { // SQL statement executed successfully
    // Retrieve the Shopper ID assigned to the new shopper
    $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
    $result = $conn->query($qry); //Execute the SQL and get the returned result
    while ($row = $result->fetch_array()) {
        $_SESSION["ShopperID"] = $row["ShopperID"];
    }

    //successful message and Shopper ID
    $Message = "Registration succesful!<br />
    Your ShopperID is $_SESSION[ShopperID]<br />";
    //Save the Shopper name in a session variable
    $_SESSION["ShopperName"] = $name;
} else { //Error message
    $Message = "<h3 style='color:red'>Error in inserting record</h3>";
}

//Release the resource allocated for prepared statement 
$stmt->close();
//close database connection
$conn->close();

//Display Page Layout header with updated session state and links 
include("header.php");
//Display message
echo $Message;
//Display Page Layout footer
include("footer.php");
