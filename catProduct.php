<?php
session_start(); //detect session
include("Header.php"); //include page layout header
?>

<!-- Container that's 60% width of viewport -->
<div style='width:60%; margin:auto'>
    <!-- Page header, Cat's name is read from query string from prev page -->
    <div class="row" style="padding: 5px;">
        <div class="col-12">
            <span class="page-title"><?php echo "$_GET[catName]"; ?></span>
        </div>
    </div>

    <?php
    include_once("mysql_conn.php"); //connection handle, $conn

    $cid = $_GET["cid"]; //read cat id from query string
    //sql query to retrieve products associated to the category
    $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity, p.Offered, p.OfferedPrice, p.OfferStartDate, p.OfferEndDate
            FROM CatProduct cp INNER JOIN product p on cp.ProductID=p.ProductID
            WHERE cp.CategoryID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $cid); //"i" is int for cid
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    //display each product in a row
    while ($row = $result->fetch_array()) {
        echo "<div class='row' style='padding: 10px'>"; //new row

        //left column displays text link showing product's name
        //displays selling price in salmon
        //logic: Product on sale--> display the IF. Not on sale --> display the else
        //onOffer must be == 1 and current date must be between offerStart and offerEnd date, i.e. more than or equal to start date, but less than or equal to end date
        $onOffer = $row["Offered"];
        if ($onOffer == 1 && (date("Y-m-d") >= $row["OfferStartDate"]) && (date("Y-m-d") <= $row["OfferEndDate"])) {
            $product = "productDetails.php?pid=$row[ProductID]";
            $formattedPrice = number_format($row["Price"], 2);
            echo "<div class='col-6'>"; //50% of row width
            $offerPrice = number_format($row["OfferedPrice"], 2);
            echo "<p><a href=$product>$row[ProductTitle]</a></p>";
            echo "<p style='font-size: 15px';>$row[ProductTitle] is on offer!</p>";
            echo "Price:<span style='font-weight:bold; color: salmon; text-decoration: line-through;'>
			  S$ $formattedPrice</span>"; //OG Price
            echo "<br />";
            echo "Offer Price:<span style='font-weight:bold; color: salmon;'>
			  S$ $offerPrice</span>"; //Discounted Price
        } else {
            $product = "productDetails.php?pid=$row[ProductID]";
            $formattedPrice = number_format($row["Price"], 2);
            echo "<div class='col-6'>"; //50% of row width
            echo "<p><a href=$product>$row[ProductTitle]</a></p>";
            echo "Price:<span style='font-weight:bold; color: salmon;'>
			  S$ $formattedPrice</span>";
        }

        //stock indicator
        $quantity = $row["Quantity"];
        if ($quantity > 0) {
            echo "<p style='color:darkgreen;'>In Stock!</p>"; //maybe add green color to the wording?
        } else {
            echo "<p style='color: red;'>Out of Stock!</p>"; //red color
        }
        echo "</div>";

        //right column displays product's image
        $img = "./Images/Products/$row[ProductImage]";
        echo "<div class='col-6'>"; //50% of row width
        echo "<img src='$img' width='300' height='300' />";
        echo "</div>";

        echo "</div>"; //end of row
    }

    $conn->close(); //close connection
    echo "</div>"; //close container
    include("footer.php"); //include footer layout
    ?>