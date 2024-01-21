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
    <a class = 'nav-link' href='logout.php'>Logout</a></li>";

    //To Do 2 (Practical 4) - 
    //Display number of item in cart

}
?>
<nav class="navbar navbar-expand-md navbar-light" style="background-color: #f5f5dc; padding-top: 1px; padding-bottom: 1px; border-radius: 35px;">
    <div class="container-fluid">
        <!-- Logo in the middle of the navbar -->
        <div class="row w-100">
            <!-- Left-justified menu items -->
            <div class="col-md-4 d-flex align-items-center" style="font-size: 15px;">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="" style="Text-Decoration:none;" ><strong>Product Categories</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="" ><strong>Product Search</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="" ><strong>ShoppingCart</strong></a>
                    </li>
                </ul>
            </div>

            <!-- Logo in the middle -->
            <div class="col-md-4 d-flex align-items-center justify-content-center">
                <a href="#" class="navbar-brand">
                    <img src="Images/Logo.png" alt="Logo" class="img-fluid" style="height: 90px; width: auto;" />
                </a>
            </div>

            <!-- Right-justified menu items -->
            <div class="col-md-4 d-flex align-items-center justify-content-end">
                <ul class="navbar-nav" style="font-size: 15px;">
                    <?php echo $content2; ?>
                </ul>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>

        <!-- Collapsible Navbar -->
        <div class="collapse navbar-collapse" id="collapsibleNavbar">
            <!-- Additional menu items for collapsible navbar if needed -->
        </div>
    </div>
</nav>
