<?php 
session_start();
include("header.php");

echo $emailInput = "<div class='container-fluid col-sm-12 email-input'>
                        <form name='changePwd' action='emailVerification.php' method='post'>
                            <div class='mb-3 row mt-5'>
                                    <h1>Email address</h1>
                                    <div class='col-sm-1 email-input'>
                                        <input class='form-control' id='email' name='email' type='text'/>
                                    </div>
                            </div>
                        <div class='mb-3 row mt-2'>
                            <div class='col-sm-1 email-btn'>
                                <button class='btn btn-primary' type='submit' name='submit'>Submit</button>
                            </div>
                        </div>
                        </form>
                        </div>"; 
include("footer.php"); 
?>

