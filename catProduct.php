<?php
session_start(); //detect session
include("Header.php"); //include page layout header
?>

<!-- Container that's 60% width of viewport -->
<div style='width:60%; margin:auto'>
    <!-- Page header, Cat's name is read from query string from prev page -->
    <div class="row" style="padding: 5px;">
        <div class="col-12">
            <span class="page-title"><?php echo "$_GET[catName]";?></span>
        </div>
    </div>

    <?php
    include_once("mysql_conn.php"); //connection handle, $conn

    $cid = $_GET["cid"]; //read cat id from query string
    //sql query to retrieve products associated to the category
    $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity
            FROM CatProduct cp INNER JOIN product p on cp.ProductID=p.ProductID
            WHERE cp.CategoryID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i", $cid); //"i" is int for cid
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    //display each product in a row
    while ($row = $result->fetch_array()){
        echo "<div class='row' style='padding: 10px'>"; //new row

        //left column displays text link showing product's name
        //displays selling price in black
        $product = "productDetails.php?pid=$row[ProductID]";
        $formattedPrice = number_format($row["Price"], 2);
        echo "<div class='col-6'>"; //50% of row width
        echo "<p><a href=$product>$row[ProductTitle]</a></p>";
		echo "Price:<span style='font-weight:bold; color: salmon;'>
			  S$ $formattedPrice</span>";
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

