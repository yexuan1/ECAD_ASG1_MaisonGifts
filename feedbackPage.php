<!DOCTYPE html>
<html>
<head>
    <style>
        <?php session_start();
        include("css/site.css"); ?>
    </style>
	<script src="https://kit.fontawesome.com/fc8e0fb32a.js" crossorigin="anonymous"></script>
</head>
<body>
<?php 
    include('header.php');
    echo "<div class='container-fluid rate'>
                <h1 class='text-center'>Rate Your Experience</h1>
                <form class='feedback-form' action='addFeedback.php' method='post'>
                <div class='wrapper'>
                    <div class'text'>Thanks for your cooperation</div>
                    <a href='index.php'>Continue Shopping..</a>
                </div>
                <div class='star'>
                        <input class='ranking-star' type='radio' name='radio' id='rate-5' value='5'>
                        <label for='rate-5' class='fas fa-star star'></label>
                        <input class='ranking-star' type='radio' name='radio' id='rate-4' value='4'>
                        <label for='rate-4' class='fas fa-star'></label>
                        <input class='ranking-star' type='radio' name='radio' id='rate-3' value='3'>
                        <label for='rate-3' class='fas fa-star'></label>
                        <input class='ranking-star' type='radio' name='radio' id='rate-2' value='2'>
                        <label for='rate-2' class='fas fa-star'></label>
                        <input class='ranking-star' type='radio' name='radio' id='rate-1' value='1'>
                        <label for='rate-1' class='fas fa-star'></label>

                        <header></header>

                        <div class='form'>
                            <form action='addfeedBack.php' method='post'>
                            <div class='customer-feedback'>
                                <input class='subject' name='subject' type='text' placeholder='subject title'> 
                                <textarea cols='30' id='feedback-content' name='feedback-content' placeholder='Write your suggestions...'></textarea>
                            </div>
                            <div class='btn'>
                                <button type='button' name='submit'>Send</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </form> 
          </div>";
          
    include("footer.php");
?>
<script src="js/feedback.js"></script>
</body>
</html>





