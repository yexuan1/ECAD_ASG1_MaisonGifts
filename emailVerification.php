<?php 
session_start();
include("header.php");
include_once('mysql_conn.php');

$qry = "SELECT * from Shopper where Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $_POST["email"]);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $email = $row["Email"];

    $qry = "SELECT * from Shopper where Email=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("s", $_POST["email"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $question = $row["PwdQuestion"];
        $answer = $row["PwdAnswer"];
    }

    $password = 'ecader';

    if($email === $_POST['email']){
        echo $sequrityQuestion = "<div class='container-fluid col-sm-12 question-input'>
                        <form name='changePwd' action='SequrityQuestionVerification.php' method='post'>
                            <input type='hidden' value='$_POST[email]' name='email'>
                            <div class='mb-3 row mt-5'>
                                <div class='col-sm-9'>
                                   Sequrity Question<input class='form-control' id='question' readonly value='$question'/> 
                                </div>
                            </div>
                            <div class='mb-3 row mt-5'>
                                <div class='col-sm-9'>
                                    Sequrity Answer<input class='form-control' id='answer' name='answer' type='text'>
                                </div>
                            </div>
                        <div class='mb-3 row mt-2'>
                            <div class='col-sm-1 question-btn'>
                                <button class='btn btn-primary' type='submit' name='submit' style='border-radius:10px;'>Submit</button>
                            </div>
                        </div>
                        </form>
                        </div>"; 
    }
    
    
}
else{
    echo $emailInput = "<div class='col-sm-12' style='width:100%; margin-left:30%;'>
                        <form name='changePwd' method='post'>
                            <div class='mb-3 row mt-5'>
                                invalid email address please try again
                            </div>
                            <div class='mb-3 row mt-5'>
                                <div class='col-sm-9'>
                                    <a href='forgetPassword.php' style='border-radius:10px;'>Back</button>
                                </div>
                            </div>
                        </form>
                        </div>"; 

    include("footer.php"); 
}


?>

