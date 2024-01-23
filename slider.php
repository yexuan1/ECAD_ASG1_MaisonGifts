<?php
include("mysql_conn.php");

$sql = "SELECT * FROM product";

$qry = mysqli_query($conn, $sql);

if (!$qry) {
    die("Error: " . mysqli_error($conn));
}

$li = "";
$i = 0;
$div = '';

while ($row = mysqli_fetch_array($qry)) {
    $productTitle = $row['ProductTitle'];
    $productImage = $row['ProductImage'];


    $imagePath = 'Images/Products/' . $productImage;

    if ($i == 0) {
        $li .= '<li data-target="#myCarousel" data-slide-to="' . $i . '" class="active"></li>';
        $div .= '
            <div class="item active">
                <img src="' . $imagePath . '" alt="' . $productTitle . '" style="width:100%; height: 600px;">
                <div class="carousel-caption">
                    <h3>' . $productTitle . '</h3>

                </div>
            </div>
        ';
    } else {
        $li .= '<li data-target="#myCarousel" data-slide-to="' . $i . '"></li>';
        $div .= '
            <div class="item">
                <img src="' . $imagePath . '" alt="' . $productTitle . '"style="width:100%; height:600px;">
                <div class="carousel-caption">
                    <h3>' . $productTitle . '</h3>

                </div>
            </div>
        ';
    }
    $i++;
}

$div .= '</div>'; // Move this line outside the loop

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php echo $li; ?>
        </ol>

        <div class="carousel-inner">
            <?php echo $div; ?>
        </div>

        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>

</body>
</html>
