<?php
// Detect the current session 
session_start();
// Include the Page Layout header
include("header.php");
?>
<style>
    <?php include 'site.css'; ?>
</style>
<head>
    <script src="https://kit.fontawesome.com/fc8e0fb32a.js" crossorigin="anonymous"></script>
</head>

<!-- Create a centrally located container -->
<div style="width: 40%; margin: auto; margin-top: 5%;">

    <!-- Create an HTML form within the container -->
    <div class="card" style="border-radius:25px; background-color: #f5f5dc;">
        <div class="card-body">

            <form action="checkLogin.php" method="post">

                <!-- 1st row - Header Row -->
                <div class="mb-3 row">
                    <div class="col-sm-12 text-center">
                        <h5 class="card-title">Member Login</h5>
                    </div>
                </div>

                <!-- 2nd row - Entry of email address -->
                <div class="mb-3 row align-items-center">
                    <div class="col-sm-1">
                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    </div>
                    <div class="col-sm-11">
                        <input class="form-control" type="email" name="email" id="email" placeholder="Email Address" required />
                    </div>
                </div>

                <!-- 3rd row - Entry of password -->
                <div class='mb-3 row align-items-center'>
                    <div class="col-sm-1">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </div>
                    <div class="col-sm-11">
                        <input class="form-control" type="password" name="password" id="password" placeholder="Password" required />
                    </div>
                </div>


                <!-- 4th row - Login button -->
                <div class='mb-3 row'>
                    <div class='col-sm-12 text-center'>
                        <button type='submit' class='btn btn-primary'>Login</button>
                        <p style="font-size:15px; margin-top:20px;">Please sign up if you do not have an account.</p>
                        <p><a href="forgetPassword.php">Forget Password</a></p>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
// Include the Page Layout footer
include("footer.php");
?>