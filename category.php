<?php
session_start(); //detect current session
include("Header.php"); //include header layout
?>

<!-- create container that's 60% width of viewport -->
<div style="width:60%; margin:auto;">

<div class="row" style="padding:5px"> <!-- header row -->
        <div class="col-12">
            <span class="page-title">Product Categories</span>
            <p>Select a category listed below:</p>
        </div>
</div> <!-- end of header row -->

<?php
//include php file that establishes database connection handle, $conn
include_once("mysql_conn.php");

//query to retrieve all categories
$qry = "SELECT * FROM Category ORDER BY CatName ASC"; //in ascending order of category name
$result = $conn->query($qry);

//fetch results and display each category in a row
while ($row = $result->fetch_array()){
    echo "<div class='row' style='padding:10px'>"; //start new row

    //left column displays category's image
    $img = "./Images/Category/$row[CatImage]";
    echo "<div class='col-4'>"; //50% of row width
    echo "<img src='$img' />";
    echo "</div>";

    //right column displays a text link showing category name and desc
    $catname = urlencode($row["CatName"]);
    $catproduct = "catProduct.php?cid=$row[CategoryID]&catName=$catname";
    echo "<div class='col-4'>"; //50% of row width
    echo "<p><a href=$catproduct>$row[CatName]</a></p>";
    echo "$row[CatDesc]";
    echo "</div>";

    echo "</div>";
}

//close database connection
$conn->close();
echo "</div>"; //end of container
include ("footer.php"); //footer layout
?>


