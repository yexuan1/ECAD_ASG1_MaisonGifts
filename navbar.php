<?php
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='register.php'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='login.php'>Login</a></li>";

if (isset($_SESSION["ShopperName"])) {
    //To Do 1 (Practical 2) - 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
    $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
    <a class = 'nav-link' href='changePassword.php'>Change Password</a></li>
    <li class='nav-item'>
    <a class='nav-link' href='Profile.php'>My Profile</a></li>
    <li class='nav-item'>
    <a class = 'nav-link' href='logout.php'>Logout</a></li>";

}
?>
<nav class="navbar navbar-expand-md navbar-light" style="background-color: #f5f5dc; padding-top: 1px; padding-bottom: 1px; border-radius: 35px;">
    <div class="container">

        <a href="index.php" class="navbar-brand d-flex align-items-center">
            <img src="Images/Logo.png" alt="Logo" class="img-fluid" style="height: 90px; width: auto;" />
        </a>
        <span class="navbar-text ms-md-2" style="max-width: 80%;">
            <?php echo $content1; ?>
        </span>

        
       

        <!-- Toggler Button for Small Screens -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Collapsible Navbar -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <!-- Centered menu items -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="category.php"><strong>Product Categories</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php"><strong>Product Search</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shoppingCart.php"><strong>Shopping Cart</strong></a>
                </li>
            </ul>

            <!-- Right-justified menu items -->
            <ul class="navbar-nav">
                <?php echo $content2; ?>
            </ul>
        </div>

    
    </div>
</nav>
