<?php
    session_start();
    include_once('mysql_conn.php');
    include('header.php');

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
    
    $password = 'password123';

    if($answer === $_POST['answer']){
        $qry = "UPDATE Shopper set Password=? where Email=?";
        $stmt->prepare($qry);
        $stmt->bind_param("ss", $password, $_POST['email']);
        $stmt->execute();    
        
        echo $Message = "<script>alert('password reset!');
        window.location.href='/Maison_Gifts/ECAD_ASG1_MaisonGifts/login.php';                
        </script>";
    }

    include('footer.php');
?>