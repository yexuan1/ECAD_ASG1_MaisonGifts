<?php
    session_start();
    include('header.php');
    $feedback = $_POST["feedback-content"];
    $ranking = $_POST["radio"];
    $subject = $_POST["subject"];
    $shopperID = $_SESSION["ShopperID"];
    $currentDateTime = date("Y-m-d H:i:s");

    include_once("mysql_conn.php");

    $qry = "INSERT INTO Feedback (ShopperID, Subject,Content, Rank, DateTimeCreated) 
    VALUES (?, ?, ?, ?, ?)"; //??? are placeholders
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("issis", $shopperID, $subject, $feedback, $ranking, $currentDateTime);
    $stmt->execute();
    $stmt->close();
    $conn->close();   
        
    echo "<h1>Thanks for the Feedback</h1>";
    echo "</br>";
    echo "<a id='cont-shop' href='index.php'>Continue Shopping..</a>";
    include('footer.php');

?>