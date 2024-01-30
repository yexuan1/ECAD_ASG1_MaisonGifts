<?php 
    session_start();
    include("header.php");
    include_once("mysql_conn.php");
    $feedback = $_POST["feedback-content"];
    $ranking = $_POST["radio"];
    $subject = $_POST["subject"];
    $shopperID = $_SESSION["ShopperID"];
    $currentDateTime = date("Y-m-d H:i:s");

    $qry = "INSERT INTO Feedback (ShopperID, Subject,Content, Rank, DateTimeCreated) 
    VALUES (?, ?, ?, ?, ?)"; //??? are placeholders
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("issis", $shopperID, $subject, $feedback, $ranking, $currentDateTime);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    echo "</br>";
    echo "<h1>Thanks for the feedback</h1>";
    echo "<a href='index.php'>Continue Shopping...</a>";

    include("footer.php");
?>


