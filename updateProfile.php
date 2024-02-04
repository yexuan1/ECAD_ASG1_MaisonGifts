<head>
    <style>
        <?php session_start(); include('css/site.css'); ?>
    </style>
</head>

<?php
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");
//Read the data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = "(65) " . $_POST["phone"];
$email = $_POST["email"];
$password = $_POST["password"];
$shopperId = $_SESSION["ShopperID"];

$qry = "SELECT Email from Shopper where Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
if($row = $result->fetch_assoc()){
    include('header.php');
    if($row['Email'] === $email){
        echo $Message = "<div class='container-fluid mt-4 validate-email text-center'>Existing email please try again
                        <form action='Profile.php' method='post' class='container-fluid col-sm-3' id='validate-email-input'>
                        <button class='btn btn-primary'>Click here to try again</button>
                        </form>
                        </div>";
    }
    include("footer.php");
}
else{
        //Define the select statement for email where the email in the database record matches the email enterd by the shopper
        $qry = "UPDATE Shopper set Name=?, Address=?, Country=?, Phone=?, Email=?, Password=? where ShopperID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("ssssssi",$name,$address,$country, $phone,$email,$password,$shopperId);

        if($stmt->execute()){
            include("header.php");
            echo "<br/>";
            echo $Message = "<div id='update' class='col-sm-12'>Update succesful!<br />
                            <div class='col-sm-1 update-btn'>
                            <a class='btn btn-primary' href='index.php'>Back</a> 
                            </div>";
            include("footer.php");
        }

        $stmt->close();
        $conn->close();
        }


?>