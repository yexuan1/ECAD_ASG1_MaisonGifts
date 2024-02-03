<?php 
session_start();
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
    $question = $row["PwdQuestion"];
    $answer = $row["PwdAnswer"];
}

$password = '123';


echo $sequrityQuestion = "<div class='col-sm-12' style='width:100%; margin-left:30%;'>
                        <form name='changePwd' action='checkSequrityAnswer.php' method='post'>
                            <input type='hidden' value='$_POST[email]' name='email'>
                            <div class='mb-3 row mt-5'>
                                <div class='col-sm-5'>
                                   Sequrity Question<input class='form-control' id='question' readonly value='$question'/> 
                                </div>
                            </div>
                            <div class='mb-3 row mt-5'>
                                <div class='col-sm-5'>
                                    Sequrity Answer<input class='form-control' id='answer' name='answer' type='text'>
                                </div>
                            </div>
                        <div class='mb-3 row mt-5'>
                            <div class='col-sm-5'>
                                <button type='submit' name='submit' style='border-radius:10px;'>Update</button>
                            </div>
                        </div>
                        </form>
                        </div>"; 

include("footer.php"); 
?>


