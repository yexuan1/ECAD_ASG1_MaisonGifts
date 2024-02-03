<?php 
session_start();
include("header.php");

echo $emailInput = "<div class='col-sm-12' style='width:100%; margin-left:30%;'>
                        <form name='changePwd' action='sequrityQuestion.php' method='post'>
                            <div class='mb-3 row mt-5'>
                                    <h1>Email address</h1>
                                    <div class='col-sm-5'>
                                        <input class='form-control' id='email' name='email' type='text' placeholder='email'/>
                                    </div>
                            </div>
                        <div class='mb-3 row mt-5'>
                            <div class='col-sm-9'>
                                <button type='submit' name='submit' style='border-radius:10px;'>Submit</button>
                            </div>
                        </div>
                        </form>
                        </div>"; 

include("footer.php"); 
?>

