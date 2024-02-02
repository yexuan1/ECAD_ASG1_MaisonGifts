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
                <div><label>Name</label></div>
                <div><label>Subject</label></div>
                <div><label>Content</label></div>
                <div><label>Rank</label></div>
            </div>";
    while ($row = $result->fetch_assoc()) {
        echo "
        

               <div class='container-fluid review-content'>
                    <div><p>$row[Name]</p></div>
                    <div><p>$row[Subject]</p></div>
                    <div><p>$row[Content]</p></div>
                    <div><p>$row[Rank]</p></div>
               </div>
               ";
    };
};

include("footer.php");
?>