<head>
<style>
    <?php session_start(); include("css/site.css") ?>
</style>
</head>

<?php 
include("header.php");
include_once("mysql_conn.php");
//check if user logged in
$qry = "SELECT * from Shopper where Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $_POST["email"]);
$stmt->execute();
$result = $stmt->get_result();
$question;

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $answer = $row["PwdAnswer"];

}

$password = 'ecader';

if($answer === $_POST['answer']){
    $qry = "UPDATE Shopper set Password=? where Email=?";
    $stmt->prepare($qry);
    $stmt->bind_param("ss", $password, $_POST['email']);
    $stmt->execute();    
    
    echo $Message = "<div class='col-sm-12 mt-5'>
                        <div class='container-fluid col-sm-6 pass-reset'>
                            <h1>Password Reset</h1>
                            <p class='pass-reset'>your password is: <span>$password</span></p>
                            <div>
                            <a class='form-control' href='/Maison_Gifts/ECAD_ASG1_MaisonGifts/login.php'>Back</a>
                            </div>
                        </div>
                    </div>";
}
else{
    echo $Message = "<div id='wrong-answer' class='mt-5'>wrong answer please try again</div>
                    <div class='col-sm-3 wrong-btn'>
                    <form action='emailVerification.php' method='post'>
                    <button class='btn btn-primary' name='email' type='submit' value='$_POST[email]'>Back</button>
                    </form>
                    </div>";
}


include("footer.php"); 
?>


