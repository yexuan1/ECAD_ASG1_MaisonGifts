<?php 
session_start();
include("header.php");
include_once("mysql_conn.php");
//check if user logged in
if($_SESSION["ShopperID"]){
    $ShopperID= $_SESSION["ShopperID"];
}

$qry = "SELECT * from Shopper where ShopperID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $ShopperID);
$stmt->execute();
$result = $stmt->get_result();
$question;
$answer;
$password = 'password';
if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $question = $row["PwdQuestion"];
    $answer = $row["PwdAnswer"];
}

echo $sequrityQuestion = "<div class='col-sm-12' style='width:100%; margin-left:30%;'>
                        <form name='changePwd' method='post'>
                            <div class='mb-3 row mt-5'>
                                <div class='col-sm-5'>
                                    <input class='form-control' id='question' readonly value='$question'/> 
                                </div>
                            </div>
                            <div class='mb-3 row mt-5'>
                                    <div class='col-sm-5'>
                                        <input class='form-control' id='answer' name='answer' type='text' value=''/>
                                    </div>
                            </div>
                        <div class='mb-3 row mt-5'>
                            <div class='col-sm-9'>
                                <button type='submit' name='submit' style='border-radius:10px;'>Update</button>
                            </div>
                        </div>
                        </form>
                        </div>"; 

if(isset($_POST["submit"]))
    if($answer === $_POST['answer']){
        $qry = "UPDATE Shopper set Password=? where ShopperID=?";
        $stmt->prepare($qry);
        $stmt->bind_param("si", $password, $ShopperID);
        $stmt->execute();    
        
        echo $Message = "<div class='col-sm-5' style='margin-left:30%;'>Password Reset!</div>";
    }



include("footer.php"); 
?>

