<?php
session_start();
include("header.php"); // Include the Page Layout header
include_once("myPayPal.php"); // Include the file that contains PayPal settings
include_once("mysql_conn.php"); 

if($_POST) //Post Data received from Shopping cart page.
{
	
	foreach($_SESSION["Items"] as $items) {
		$selectPid = $items["productId"];
		$qry = "SELECT Quantity FROM product WHERE ProductID = ?";
		$stmt = $conn->prepare($qry);
		$stmt -> bind_param("i", $selectPid);
		$stmt -> execute();
		$result = $stmt -> get_result();
		$stmt ->close();
		$row = $result->fetch_assoc();
		$qty = $row["Quantity"];
		$availQty = (int)$qty;
		if ($availQty < $items["quantity"])
		{
			echo"<br></br>";
			echo "Product $items[productId] : $items[name] is out of stock!<br />";
			echo "Please return to shopping cart to amend your purchase.<br />";
			echo "<form method='post' action='shoppingCart.php'>";
			echo "<input type='submit' style='float:left;' value='Return To Shopping Cart' name='return'>";
			echo "</form></p>";
			echo "<br></br>";
			// Unset quantity from the session
			unset($_SESSION["Items"][$selectPid]["quantity"]);
			include("footer.php");
			exit;
		}
	}

	
	$paypal_data = '';
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach($_SESSION['Items'] as $key=>$item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}

	

	//Data to be sent to PayPal
	$padata = '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTACTION=Sale'.
			  '&ALLOWNOTE=1'.
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] +
				                                 $_SESSION["Tax"] + 
												 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]). 
			  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]). 
			  '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]). 	
			  '&BRANDNAME='.urlencode("Maison Gifts").
			  $paypal_data.				
			  '&RETURNURL='.urlencode($PayPalReturnURL).
			  '&CANCELURL='.urlencode($PayPalCancelURL);	
		
	//We need to execute the "SetExpressCheckOut" method to obtain paypal token
	$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
	                                   $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
	//Respond according to message we receive from Paypal
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
		if($PayPalMode=='sandbox')
			$paypalmode = '.sandbox';
		else
			$paypalmode = '';
				
		//Redirect user to PayPal store with Token received.
		$paypalurl ='https://www'.$paypalmode. 
		            '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.
					$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}
	else {
		//Show error message
		echo "<div style='color:red'><b>SetExpressCheckOut failed : </b>".
		      urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."</div>";
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"])) 
{	
	$_SESSION["ShipName"] = "";
	$_SESSION["ShipAddress"] = "";
	$_SESSION["ShipEmail"] = "";
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	$paypal_data = '';
	
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach($_SESSION['Items'] as $key=>$item) 
	{
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	
	//Data to be sent to PayPal
	$padata = '&TOKEN='.urlencode($token).
			  '&PAYERID='.urlencode($playerid).
			  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
			  $paypal_data.	
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]).
              '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]).
              '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] + 
			                                     $_SESSION["Tax"] + 
								                 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	//We need to execute the "DoExpressCheckoutPayment" at this point 
	//to receive payment from user.
	$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
	                                   $PayPalApiUsername, $PayPalApiPassword, 
									   $PayPalApiSignature, $PayPalMode);
	
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
		// To Do 5 (DIY): Update stock inventory in product table 
		//                after successful checkout
		// To retrieve items in the shopping cart
		$qry = "SELECT * FROM shopcartitem WHERE ShopCartID = ?";
		$stmt = $conn->prepare($qry);
		$stmt ->bind_param("i", $_SESSION["Cart"]);
		$stmt -> execute();
		$result2 = $stmt->get_result();
		$stmt->close();
		if($result2->num_rows > 0){
			while ($row = $result2->fetch_array()){
				$cartItem = $row["ProductID"];
				$cartQuantity = $row["Quantity"];
				$qry = "UPDATE product SET Quantity = Quantity - ?
				WHERE ProductID = $cartItem";
				$stmt = $conn->prepare($qry);
				$stmt-> bind_param("i", $row["Quantity"]);
				$stmt->execute();
				$stmt->close();
			}
		}

		
		// End of To Do 5
	
		// To Do 2: Update shopcart table, close the shopping cart (OrderPlaced=1)
		$total = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
		$qry = "UPDATE shopcart SET OrderPlaced = 1, Quantity = ?,
				SubTotal = ?, ShipCharge=?, Tax=?,Total=?
				WHERE ShopCartID=?";
		$stmt = $conn->prepare($qry);
		// "i" - integer, "d" - double
		$stmt-> bind_param("iddddi", $_SESSION["NumCartItem"],
							$_SESSION["SubTotal"], $_SESSION["ShipCharge"],
							$_SESSION["Tax"], $total,
							$_SESSION["Cart"]);
		$stmt->execute();
		$stmt->close();

		// End of To Do 2
		
		//We need to execute the "GetTransactionDetails" API Call at this point 
		//to get customer details
		$transactionID = urlencode(
		                 $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
		$nvpStr = "&TRANSACTIONID=".$transactionID;
		$httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
		                                   $PayPalApiUsername, $PayPalApiPassword, 
										   $PayPalApiSignature, $PayPalMode);

										   

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
		   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
		   {
			//gennerate order entry and feed back orderID information
			//You may have more information for the generated order entry 
			//if you set those information in the PayPal test accounts.
			
			$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));
			
			$ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
			if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
				$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
			if (isset($httpParsedResponseAr["SHIPTOCITY"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCITY"]);
			if (isset($httpParsedResponseAr["SHIPTOSTATE"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
			$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]). 
			                ' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]);
				
			$ShipCountry = urldecode(
			               $httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
			
			$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);		
			
			$_SESSION["deliveryDate"] = "";
			$deliveryDate = "";

			$message = $_SESSION['Message'];

			$cartId = $_SESSION["Cart"];
		

			$deliveryTime = $_SESSION["DeliveryTime"];

			function addWorkingDays($date, $days) {
				$currentDate = new DateTime($date);
			
				// Iterate through the days to add
				for ($i = 0; $i < $days; $i++) {
					// Check if the day is a weekend (Saturday or Sunday)
					while (in_array($currentDate->format('N'), [6, 7])) {
						$currentDate->modify('+1 day'); // Skip weekends
					}
			
					$currentDate->modify('+1 day'); // Move to the next day
				}
			
				return $currentDate->format('Y-m-d');
			}
			

			if ($_SESSION["DeliveryMode"] == 'Express')
			{
				$today = new DateTime();

				// Modify the date to get tomorrow's date
				$tomorrow = $today->modify('+1 day');

				// Format the date as per your requirement
				$deliveryDate = $tomorrow->format('Y-m-d');
			}

			else 
			{

				// Get the date 2 working days from today
				$today = date('Y-m-d');
				$deliveryDate = addWorkingDays($today, 2);
			}

			if ($deliveryDate != '')
			{
				$_SESSION["deliveryDate"] = $deliveryDate;
			}
			
			$_SESSION["ShipName"] = $ShipName;
			$_SESSION["ShipAddress"] = $ShipAddress;
			$_SESSION["ShipEmail"] = $ShipEmail;
	
			$qry = "INSERT INTO orderdata (ShipName, ShipAddress, ShipCountry,
											ShipEmail, Message, DeliveryMode, DeliveryTime , DeliveryDate ,ShopCartID)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $conn->prepare($qry);
			// "i" - integer, "s" - string
			$stmt -> bind_param("ssssssssi", $ShipName, $ShipAddress, $ShipCountry,
								$ShipEmail, $message, $_SESSION["DeliveryMode"] ,$deliveryTime, $deliveryDate, $_SESSION["Cart"]);
			$stmt->execute();
			$stmt->close();
			$qry = "SELECT LAST_INSERT_ID() AS OrderID";
			$result = $conn->query($qry);
			$row = $result ->fetch_array();
			$_SESSION["OrderID"] = $row["OrderID"];
			
			$conn->close();
				  
			//Reset the "Number of Items in Cart" session variable to zero.
			$_SESSION["NumCartItem"] = 0;
	  		
			//Clear the session variable that contains Shopping Cart ID.
			unset($_SESSION["Cart"]);
			
			//Redirect shopper to the order confirmed page.
			header("Location: orderConfirmed.php");
			exit;
		} 
		else 
		{
		    echo "<div style='color:red'><b>GetTransactionDetails failed:</b>".
			                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
			$conn->close();
		}
	}
	else {
		echo "<div style='color:red'><b>DoExpressCheckoutPayment failed : </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

include("footer.php"); // Include the Page Layout footer
?>