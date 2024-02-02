<?php
session_start(); // Detect the current session
include("header.php"); // Include the Page Layout header
include_once("mysql_conn.php");
?>

<!-- form to collect keyword and search in the same page -->
<div style="width:80%; margin:auto;"> <!-- container -->
    <form name="frmSearch" method="get" action="">
        <br />
        <div class="mb-3 row">
            <!-- Product Title Search -->
            <p style="font-size: 20px;">Search</p> <br />
            <label for="keywords" class="col-sm-3 col-form-label">Name/Description/Occasion/Size/Weight:</label>
            <div class="col-sm-3">
                <input class="form-control" name="keywords" id="keywords" type="search" />
            </div>
        </div> <!-- end of 1st row -->

        <div class="mb-3 row"> <!-- 2nd row -->
            <!-- Price Range Search -->
            <p style="font-size: 20px;">Price Range</p> <br />
            <label for="minPrice" class="col-sm-3 col-form-label">Min ($):</label>
            <div class="col-sm-3">
                <input class="form-control" name="minPrice" id="minPrice" type="search" />
            </div>

            <label for="maxPrice" class="col-sm-3 col-form-label">Max ($):</label>
            <div class="col-sm-3">
                <input class="form-control" name="maxPrice" id="maxPrice" type="search" />
            </div>

            <div class="col-sm-3">
                <button type='submit' style='background: #0066A2; color: white; border-style: outset; border-color: #0066A2;'>Search</button>
            </div>
        </div> <!-- end of 2nd row -->

    </form>

    <?php
    //keyword sent to server 
    //idea: for price range, ask user to input the start and end range, then compare
    //check for if min and max is set?

    if (isset($_GET["keywords"]) && trim($_GET["keywords"]) != "") {
        // if both min and max prices are set
        echo "<div class='row' style='padding:5px; font-size:20px'>";

        if (trim($_GET["minPrice"]) != "" && trim($_GET["maxPrice"]) != "") {
            $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductDesc, MAX(p.Price) AS MaxPrice,
                    CASE WHEN MAX(p.Offered) = 1 AND CURDATE() BETWEEN MAX(p.OfferStartDate) AND MAX(p.OfferEndDate)
                    THEN MAX(p.OfferedPrice) ELSE MAX(p.Price) END AS OfferedPrice, MAX(ps.SpecVal) AS SpecVal
                    FROM Product p
                    INNER JOIN ProductSpec ps ON p.ProductID = ps.ProductID
                    WHERE (ProductTitle LIKE '%$_GET[keywords]%' OR ProductDesc LIKE '%$_GET[keywords]%' OR SpecVal LIKE '%$_GET[keywords]%')  
                    GROUP BY p.ProductID
                    HAVING OfferedPrice BETWEEN $_GET[minPrice] AND $_GET[maxPrice]
                    ORDER BY ProductTitle ASC";

            echo "<strong>Search results for $_GET[keywords] between $$_GET[minPrice] and $$_GET[maxPrice]: </strong>";
        }
        // if only min price is set
        else if (trim($_GET["minPrice"]) != "" && trim($_GET["maxPrice"]) == "") {

            $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductDesc, MAX(p.Price) AS MaxPrice,
                    CASE WHEN MAX(p.Offered) = 1 AND CURDATE() BETWEEN MAX(p.OfferStartDate) AND MAX(p.OfferEndDate)
                    THEN MAX(p.OfferedPrice) ELSE MAX(p.Price) END AS OfferedPrice, MAX(ps.SpecVal) AS SpecVal
                    FROM Product p
                    INNER JOIN ProductSpec ps ON p.ProductID = ps.ProductID
                    WHERE (ProductTitle LIKE '%$_GET[keywords]%' OR ProductDesc LIKE '%$_GET[keywords]%' OR SpecVal LIKE '%$_GET[keywords]%')  
                    GROUP BY p.ProductID
                    HAVING OfferedPrice BETWEEN $_GET[minPrice] AND (SELECT MAX(Price) FROM Product)
                    ORDER BY ProductTitle ASC";

            echo "<strong>Search results for $_GET[keywords] between $$_GET[minPrice] and the Maximum Price: </strong>";
        }
        // if only max price is set
        else if (trim($_GET["minPrice"]) == "" && trim($_GET["maxPrice"]) != "") {

            $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductDesc, MAX(p.Price) AS MaxPrice,
                    CASE WHEN MAX(p.Offered) = 1 AND CURDATE() BETWEEN MAX(p.OfferStartDate) AND MAX(p.OfferEndDate)
                    THEN MAX(p.OfferedPrice) ELSE MAX(p.Price) END AS OfferedPrice, MAX(ps.SpecVal) AS SpecVal
                    FROM Product p
                    INNER JOIN ProductSpec ps ON p.ProductID = ps.ProductID
                    WHERE (ProductTitle LIKE '%$_GET[keywords]%' OR ProductDesc LIKE '%$_GET[keywords]%' OR SpecVal LIKE '%$_GET[keywords]%')  
                    GROUP BY p.ProductID
                    HAVING OfferedPrice BETWEEN 0 AND $_GET[maxPrice]
                    ORDER BY ProductTitle ASC";

            echo "<strong>Search results for $_GET[keywords] between $0 and $_GET[maxPrice]: </strong>";
        }
        // if neither min or max prices are set
        else {
            $qry = "SELECT DISTINCT p.ProductID, p.ProductTitle, p.ProductDesc, p.Price 
                FROM Product p
                INNER JOIN ProductSpec ps ON p.ProductID = ps.ProductID
                WHERE (ProductTitle LIKE '%$_GET[keywords]%' OR ProductDesc LIKE '%$_GET[keywords]%' OR SpecVal LIKE '%$_GET[keywords]%')  
                ORDER BY ProductTitle ASC";
            echo "<strong>Search results for $_GET[keywords]: </strong>";
        }

        $result = $conn->query($qry);
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


    //stops here

    else if ((!isset($_GET["keywords"]) || trim($_GET["keywords"]) == "") && (isset($_GET["minPrice"]) != "" && trim($_GET["minPrice"]) != "") && (trim($_GET["maxPrice"]) == "")) {
        // if min price is set but max price is not
        $qry = "SELECT DISTINCT p.ProductID, p.ProductTitle, p.ProductDesc, p.Price, 
                CASE WHEN p.Offered = 1 AND CURDATE() BETWEEN p.OfferStartDate AND p.OfferEndDate
                     THEN p.OfferedPrice ELSE p.Price END AS DisplayPrice
                FROM Product p
                WHERE (CASE WHEN p.Offered = 1 AND CURDATE() BETWEEN p.OfferStartDate AND p.OfferEndDate
                           THEN p.OfferedPrice ELSE p.Price END) BETWEEN $_GET[minPrice] AND (SELECT MAX(Price) FROM Product)
                ORDER BY ProductTitle ASC";

        $result = $conn->query($qry);

        echo "<div class='row' style='padding:5px; font-size:20px'>";
        echo "<strong>Search results for price between $$_GET[minPrice] and Maximum Price: </strong>";
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
    } else if ((!isset($_GET["keywords"]) || trim($_GET["keywords"]) == "") && (isset($_GET["maxPrice"]) != "" && trim($_GET["maxPrice"]) != "") && (trim($_GET["minPrice"]) == "")) {
        // if max price is set but min price is not
        $qry = "SELECT DISTINCT p.ProductID, p.ProductTitle, p.ProductDesc, p.Price, 
                CASE WHEN p.Offered = 1 AND CURDATE() BETWEEN p.OfferStartDate AND p.OfferEndDate
                     THEN p.OfferedPrice ELSE p.Price END AS DisplayPrice
                FROM Product p
                WHERE (CASE WHEN p.Offered = 1 AND CURDATE() BETWEEN p.OfferStartDate AND p.OfferEndDate
                           THEN p.OfferedPrice ELSE p.Price END) BETWEEN 0 AND $_GET[maxPrice]
                ORDER BY ProductTitle ASC";

        $result = $conn->query($qry);

        echo "<div class='row' style='padding:5px; font-size:20px'>";
        echo "<strong>Search results for price between $0 and $$_GET[maxPrice]: </strong>";
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
    } else if ((!isset($_GET["keywords"]) || trim($_GET["keywords"]) == "") && (isset($_GET["maxPrice"]) != "" && trim($_GET["maxPrice"]) != "") && (isset($_GET["minPrice"]) != "" && trim($_GET["minPrice"]) != "")) {
        // if both are set
        $qry = "SELECT DISTINCT p.ProductID, p.ProductTitle, p.ProductDesc, p.Price, 
                CASE WHEN p.Offered = 1 AND CURDATE() BETWEEN p.OfferStartDate AND p.OfferEndDate
                     THEN p.OfferedPrice ELSE p.Price END AS DisplayPrice
                FROM Product p
                WHERE (CASE WHEN p.Offered = 1 AND CURDATE() BETWEEN p.OfferStartDate AND p.OfferEndDate
                           THEN p.OfferedPrice ELSE p.Price END) BETWEEN $_GET[minPrice] AND $_GET[maxPrice]
                ORDER BY ProductTitle ASC";

        $result = $conn->query($qry);

        echo "<div class='row' style='padding:5px; font-size:20px'>";
        echo "<strong>Search results for price between $$_GET[minPrice] and $$_GET[maxPrice]: </strong>";
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
    } else {
        echo "<p>Please enter a search query!</p>";
    }

    echo "</div>"; // End of container
    include("footer.php"); // footer layout
    ?>