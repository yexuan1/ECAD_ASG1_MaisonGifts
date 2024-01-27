<?php
session_start();
include_once("mysql_conn.php");
//update status to inactive of shopper when log out based on shopper id
$ActiveStatus = 0;
$qry = "UPDATE Shopper set ActiveStatus=? where ShopperID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("ii", $ActiveStatus,$_SESSION["ShopperID"]);
$stmt->execute();
$stmt->close();
$conn->close();

session_destroy();
session_unset();


header('Location:login.php');
exit();
?>