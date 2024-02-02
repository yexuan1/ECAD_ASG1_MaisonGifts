<?php
    session_start();
    $feedback = $_POST["feedback-content"];
    echo $feedback;
    $ranking = $_POST["radio"];
    $subject = $_POST["subject"];
    echo $subject;
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
        
    echo "it works";
?>