<head>
    <style>
        <?php session_start();
        include("css/site.css"); ?>
    </style>
</head>


<?php
include("header.php");
include_once("mysql_conn.php");

$qry = "SELECT Shopper.Name, Feedback.Subject, Feedback.Content, Feedback.Rank from Shopper inner join Feedback on Shopper.ShopperID = Feedback.ShopperID";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "</br>
            <div class='container-fluid review'>
                <div><h1>Feedback about Maison Gifts</h1></div>
            </div>";
    while ($row = $result->fetch_assoc()) {
        echo "<div class='container-fluid review-content'>
                    <div>Name: <span>$row[Name]</span></br> Subject: $row[Subject]: </br>Content: $row[Content]</div><hr>
                    <div>Gives a ranking of <span>$row[Rank]</span> out of 5 stars</div>
               </div>
               ";
    };
};

include("footer.php");
?>