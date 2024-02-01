<?php

// Detect the current session
session_start();

// Include the Page Layout header
include("header.php");
?>
<html>

<head>
    <link href="carousel.css" rel="stylesheet">
</head>

<body>
    <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel" style="margin-top:40px; ">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="bd-placeholder-img" width="100%" height="100%" src="Images\carousel1.jpg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                </svg>
                <div class="container">
                    <div class="carousel-caption text-start">
                        <h1>Celebrating a Special Occasion?</h1>
                        <p class="opacity-75">Find Your Perfect Gift With Us Today!</p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="bd-placeholder-img" width="100%" height="100%" src="Images\carousel2.jpg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                </svg>
                <div class="container">
                    <div class="carousel-caption">
                        <h1>Unsure Of What To Gift?</h1>
                        <p>Use our search feature to find the perfect gift!</p>
                        <p><a class="btn btn-lg btn-primary" href="search.php">Search</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="bd-placeholder-img" width="100%" height="100%" src="Images\carousel2.jpg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                </svg>
                <div class="container">
                    <div class="carousel-caption text-end">
                        <h1>Purchase Over $200 to Receive Free Express Shipping of Your Order</h1>
                        <p>Check Out Now!</p>
                        <p><a class="btn btn-lg btn-primary" href="category.php">Browse Products</a></p>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

</body>

</html>
<?php
// Include the PHP file that establishes database connection handle: $conn
include_once("mysql_conn.php");

// To Do:  Starting ....
// Form SQL to retrieve list of products associated to the Category ID
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity, p.Offered, p.OfferedPrice, p.OfferStartDate, p.OfferEndDate
        FROM CatProduct cp INNER JOIN product p on cp.ProductID=p.ProductID";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

echo "<div class='container-fluid'>"; // Use container-fluid for a full-width container
while ($row = $result->fetch_array()) {
    echo "<div class='row' style='padding:5px; margin-top: 20px;'>"; // Start a new row

    $onOffer = $row["Offered"];
    if ($onOffer == 1 && (date("Y-m-d") >= $row["OfferStartDate"]) && (date("Y-m-d") <= $row["OfferEndDate"])) {
        echo "<p style='font-size: 15px';>$row[ProductTitle] is on offer!</p>";
        $product = "productDetails.php?pid=$row[ProductID]";
        $formattedPrice = number_format($row["Price"], 2);
        echo "<div class='col-6'>"; //50% of row width
        $offerPrice = number_format($row["OfferedPrice"], 2);
        echo "<p><a href=$product>$row[ProductTitle]</a></p>";
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

    // Right column - display the product's image
    $img = "./Images/products/$row[ProductImage]";
    echo "<div class='col-md-4'>"; // Use col-md-4 for 33% of row width on medium and larger screens
    echo "<img src='$img' class='img-fluid'/>"; // Use img-fluid class for responsive images
    echo "</div>";

    echo "</div>"; // End of a row
}
echo "</div>"; // End of container


// To Do:  Ending ....

$conn->close(); // Close database connnection

?>


<?php
// Include the Page Layout footer
include("footer.php");
?>