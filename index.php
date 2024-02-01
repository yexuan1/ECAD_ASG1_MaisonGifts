<?php

// Detect the current session
session_start();

// Include the Page Layout header
include("header.php");
?>
<html>

<head>
    <link href="carousel.css" rel="stylesheet">
    <link href="site.css" rel="stylesheet">
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
    
    <?php
    // Include the PHP file that establishes database connection handle: $conn
    include_once("mysql_conn.php");

    // To Do:  Starting ....
    // Form SQL to retrieve list of products associated to the Category ID
    $qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity
    FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID";
    $stmt = $conn->prepare($qry);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    echo "<div class='row' style='padding:5px; margin-top: 40px;'>"; // Start a new row

    while ($row = $result->fetch_array()) {
        // Display each product in a card
        $product = "productDetails.php?pid=$row[ProductID]";
        $formattedPrice = number_format($row["Price"], 2);
    
        echo "<div class='col-md-4' style='margin-bottom: 40px;'>";
        echo "<div class='card' style='border-radius: 35px; '>";
        echo "<img src='./Images/products/$row[ProductImage]' class='card-img-top img-fluid mx-auto' alt='Product Image' style='width: 80%;'>"; // Adjust the width as needed
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'><a href='$product' style='text-decoration: none; font-weight: semi-bold; color: black; font-size: 25px;'>$row[ProductTitle]</a></h5>";
        echo "<p class='card-text'> <span style='color: black; font-size: 20px;'>S$ $formattedPrice</span></p>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    
    echo "</div>"; // End of the row
    echo "</div>"; // End of container


// To Do:  Ending ....

$conn->close(); // Close database connnection

?>


</body>

</html>


<?php
// Include the Page Layout footer
include("footer.php");
?>