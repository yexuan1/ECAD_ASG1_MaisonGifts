<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
?>

<!-- form to collect keyword and search in the same page -->
<div style="width:80%; margin:auto;"> <!-- container -->
    <form name="frmSearch" method="get" action="">
        <div class="mb-3 row"> <!-- 1st row -->
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Product Search</span>
            </div>
        </div> <!-- end of 1st row -->
        <div class="mb-3 row"> <!-- 2nd row -->
            <label for="keywords" class="col-sm-3 col-form-label">Product Title:</label>
            <div class="col-sm-6">
                <input class="form-control" name="keywords" id="keywords" type="search" />
            </div>
            <div class="col-sm-3">
                <button type="submit">Search</button>
            </div>
        </div> <!-- end of 2nd row -->
    </form>

    <?php
    include_once("mysql_conn.php"); //database connection $conn
    $qry = "SELECT ProductTitle FROM Product";
    $result = $conn->query($qry);
    //keyword sent to server 
    //idea: for price range, ask user to input the start and end range, then compare
    //drop down list for occasion or other categories?
    if (isset($_GET["keywords"]) && trim($_GET["keywords"]) != "") {
        $qry = "SELECT ProductID, ProductTitle FROM Product
                WHERE ProductTitle LIKE '%$_GET[keywords]%' OR ProductDesc LIKE '%$_GET[keywords]%'
                ORDER BY ProductTitle ASC";
        $result = $conn->query($qry);

        echo "<div class='row' style='padding:5px; font-size:20px'>";
        echo "<strong>Search results for '$_GET[keywords]': </strong>";
        echo "</div>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {
                echo "<div class='row' style='padding:5px'>";
                $product = "productDetails.php?pid=$row[ProductID]";
                echo "<div class='col-8'>"; //67% of row width
                echo "<p><a href=$product>$row[ProductTitle]</a></p>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='row' style='padding:5px'>";
            echo "<div class='col-8'>"; //67% of row width
            echo "No results found.";
            echo "</div>";
            echo "</div>";
        }
    }

    echo "</div>"; // End of container
    include("footer.php"); // footer layout
    ?>