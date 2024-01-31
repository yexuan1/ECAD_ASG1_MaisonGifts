<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        <?php session_start(); include("css/site.css"); ?>
    </style>
</head>
<body>

<?php
include("header.php");
include_once("mysql_conn.php");

$qry = "SELECT Shopper.Name, Feedback.Subject, Feedback.Content, Feedback.Rank from Shopper inner join Feedback on Shopper.ShopperID = Feedback.ShopperID";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    echo"</br>
            <table class='table table-dark table-striped feedback-head'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Content</th>
                        <th>Rank</th>
                    </tr>
                </thead>
            </table>";
    while($row = $result->fetch_assoc()){
        echo "
                <table class='table col-sm-12 feedback-body'>
                    <tbody>
                        <tr>
                            <td>$row[Name]</td>
                            <td>$row[Subject]</td>
                            <td>$row[Content]</td>
                            <td>$row[Rank]</td>
                        </tr>
                    </tbody>
               </table>";
    };

};

include("footer.php");
?>

</body>
</html>