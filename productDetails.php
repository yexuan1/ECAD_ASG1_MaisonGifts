<?php
session_start(); // detect session
include("Header.php"); // header layout
?>

<!-- container thats 80% of viewport -->
<div style='width:80%; margin:auto;'>

    <?php
    $pid = $_GET["pid"]; //read productID from query string

    include_once("mysql_conn.php"); //establishes connection handle $conn
    //query to select product
    $qry = "SELECT * FROM Product WHERE ProductID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $pid); //"i" is int for pid
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    //display product info
    while ($row = $result->fetch_array()) {
        //display product title
        echo "<div class='row'>";
        echo "<div class='col-sm-12' style='padding:10px'>";
        echo "<span class='page-title'>$row[ProductTitle]</span>";
        echo "</div>";
        echo "</div>";

        //quantity
        $quantity = $row["Quantity"];

        echo "<div class='row'>"; //start new row
        //left column displays product's desc
        echo "<div class='col-sm-9' style='padding:5px'>";
        echo "<p>$row[ProductDesc]</p>";

        //display product specifications
        $qry = "SELECT s.SpecName, ps.SpecVal FROM productspec ps
                INNER JOIN specification s on ps.SpecID=s.SpecID
                WHERE ps.ProductID=? ORDER BY ps.priority";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $pid); //i is int for pid
        $stmt->execute();
        $result2 = $stmt->get_result();
        $stmt->close();
        while ($row2 = $result2->fetch_array()) {
            echo $row2["SpecName"] . ": " . $row2["SpecVal"] . "<br />";
        }
        echo "</div>"; //end of left col

        //right column displays product's image
        $img = "./Images/Products/$row[ProductImage]";
        echo "<div class='col-sm-3' style='vertical-align: top; padding: 10px'>";
        echo "<img src='$img' width='250' height='250' />";

        //display product price
        $formattedPrice = number_format($row["Price"], 2); //OG Price

        $onOffer = $row["Offered"];
        if ($onOffer == 1 && (date("Y-m-d") >= $row["OfferStartDate"]) && (date("Y-m-d") <= $row["OfferEndDate"])){
            $offerPrice = number_format($row["OfferedPrice"], 2);
            echo "Price: <span style='font-weight:bold; color: salmon; text-decoration: line-through;'>
                S$ $formattedPrice</span>";
            echo "<span style='font-weight:bold; color: salmon;'>
			  S$ $offerPrice</span>"; //Discounted Price
        }
        else{
            echo "Price:<span style='font-weight:bold; color: salmon;'>
			  S$ $formattedPrice</span>";
        }
    }

    if ($quantity > 0) {
        //button for adding product to shopping cart
        echo "<form action='cartFunctions.php' method='post'>"; //for Checkout process to implement
        echo "<input type='hidden' name='action' value='add' />";
        echo "<input type='hidden' name='product_id' value='$pid' />";
        echo "Quantity: <input type='number' name='quantity' value='1'
                    min='1' max='10' style='width:40px; margin-bottom: 10px;' required />";
        echo "<br />";
        echo "<button type='submit' style='background: #0066A2; color: white;
        border-style: outset; border-color: #0066A2; width: 100%;'>Add to Cart</button>";
        echo "</form>";
        echo "</div>"; //end of right column
        echo "</div>"; //end of row
    } else {
        //button for adding product to shopping cart
        //make button GRAY; unclickable 
        echo "<form action='cartFunctions.php' method='post'>"; //for Checkout process to implement
        echo "<input type='hidden' name='action' value='add' />";
        echo "<input type='hidden' name='product_id' value='$pid' />";
        echo "Quantity: <input type='number' name='quantity' value='1'
                    min='1' max='10' style='width:40px; margin-bottom: 10px;' required />";
        echo "<button disabled type='submit' style='background: gray; color: white;
        border-style: outset; border-color: #0066A2; width: 100%;'>Add to Cart</button>";
        echo "<p style='color:red';>Out of stock!</p>";
        echo "</form>";
        echo "</div>"; //end of right column
        echo "</div>"; //end of row
    }

    $conn->close(); // Close database connnection
    echo "</div>"; // End of container
    include("footer.php"); // footer layout
    ?>