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
    <div id="myCarousel" class="carousel slide mb-6" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true"
                aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="bd-placeholder-img" width="100%" height="100%" src="Images\carousel1.jpg" aria-hidden="true"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                </svg>
                <div class="container">
                    <div class="carousel-caption text-start">
                        <h1>Example headline.</h1>
                        <p class="opacity-75">Some representative placeholder content for the first slide of the
                            carousel.</p>
                        <p><a class="btn btn-lg btn-primary" href="#">Sign up today</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="bd-placeholder-img" width="100%" height="100%" src="Images\carousel2.png" aria-hidden="true"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                </svg>
                <div class="container">
                    <div class="carousel-caption">
                        <h1>Another example headline.</h1>
                        <p>Some representative placeholder content for the second slide of the carousel.</p>
                        <p><a class="btn btn-lg btn-primary" href="#">Learn more</a></p>
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <img class="bd-placeholder-img" width="100%" height="100%" src="Images\carousel2.png" aria-hidden="true"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                </svg>
                <div class="container">
                    <div class="carousel-caption text-end">
                        <h1>One more for good measure.</h1>
                        <p>Some representative placeholder content for the third slide of this carousel.</p>
                        <p><a class="btn btn-lg btn-primary" href="#">Browse gallery</a></p>
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
$qry = "SELECT p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity
  FROM CatProduct cp INNER JOIN product p ON cp.ProductID=p.ProductID";
$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// Display each product in a row 
while ($row = $result->fetch_array()) {
  echo "<div class='row' style='padding:5px'>"; //start a new row

  //Left column - display a text link showing the product's name,
  //        display the selling price in red in a new paragraph
  $product = "productDetails.php?pid=$row[ProductID]";
  $formattedPrice = number_format($row["Price"], 2);
  echo "<div class='col-8'>"; //67% of row width 
  echo "<p><a href=$product>$row[ProductTitle]</a></p>";
  echo "Price:<span style='font-weight: bold; color: red;'>
    S$ $formattedPrice</span>";
  echo "</div>";

  //Right column - display the product's image
  $img = "./Images/products/$row[ProductImage]";
  echo "<div class = 'col-4'>"; //33% of row width
  echo "<img src = '$img'/>";
  echo "</div>";

  echo "</div>"; //End of a row
}


// To Do:  Ending ....

$conn->close(); // Close database connnection

?>


<?php
// Include the Page Layout footer
include("footer.php");
?>