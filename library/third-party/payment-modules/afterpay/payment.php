<?php	
try
{
	// Load AfterPay Library
	require_once("/var/www/vhosts/justinharings.nl/" . ($dev ? "dev" : "merchant") . ".justinharings.nl/library/third-party/payment-modules/systems/vendor/autoload.php");
	
	// Create new AfterPay Object
	$Afterpay = new \Afterpay\Afterpay();
	
	
	
	// Load customer and order DATA
	
	$customerData = $this->_runFunction("customers", "load", array($data[2]));
	$orderDATA = $this->_runFunction("orders", "load", array($orderID));
	
	
	
	// After requires splitted values. Split address and check phone number.
	
	preg_match("/([^a-zA-Z]+)(.*)/",$customerData['housenumber'],$matches);
	
	unset($matches[0]);
	$customerData['housenumber'] = $matches[1];
	
	unset($matches[1]);
	$customerData['housenumber_additions'] = implode("-", $matches);
	
	
	$initials = explode(" ", $customerData['name']);
	$customerData['initials'] = $initials[0];
	
	unset($initials[0]);
	$customerData['name'] = implode(" ", $initials);
	
	if(strlen($customerData['phone']) < 10)
	{
		//$customerData['phone'] = "0" . $customerData['phone'];
	}
	
	
	
	// Set up address information for shipping and invoice
	$aporder['billtoaddress']['city'] = 							$customerData['city'];
	$aporder['billtoaddress']['housenumber'] = 						$customerData['housenumber'];
	$aporder['billtoaddress']['housenumberaddition'] = 				$customerData['housenumber_additions'];
	$aporder['billtoaddress']['isocountrycode'] = 					$this->_countryCodes($customerData['country']);
	$aporder['billtoaddress']['postalcode'] = 						$customerData['zip_code'];
	$aporder['billtoaddress']['referenceperson']['dob'] = 			'1980-12-12T00:00:00';
	$aporder['billtoaddress']['referenceperson']['email'] = 		$customerData['email_address'];
	$aporder['billtoaddress']['referenceperson']['gender'] = 		'';
	$aporder['billtoaddress']['referenceperson']['initials'] = 		$customerData['initials'];
	$aporder['billtoaddress']['referenceperson']['isolanguage'] = 	$this->_countryCodes($customerData['country']);
	$aporder['billtoaddress']['referenceperson']['lastname'] = 		$customerData['name'];
	$aporder['billtoaddress']['referenceperson']['phonenumber'] = 	$customerData['phone'];
	$aporder['billtoaddress']['streetname'] = 					 	$customerData['street'];
	
	$aporder['shiptoaddress'] = 									$aporder['billtoaddress'];
	
	
	
	// Set up the additional information
	$aporder['ordernumber'] = 			$orderDATA['order_reference'];
	$aporder['bankaccountnumber'] = 	'';
	$aporder['currency'] = 				'EUR';
	$aporder['ipaddress'] = 			$_SERVER['REMOTE_ADDR'];
	
	
	
	// Set up order lines, repeat for more order lines
	if(count($orderDATA['products']) > 0)
	{
		foreach($orderDATA['products'] AS $product)
		{
			$sku = $product['article_code'];
			$name = $product['name'];
			$qty = $product['quantity'];
			$price = $product['price']; // in cents
			$tax_category = 1; // 1 = high, 2 = low, 3, zero, 4 no tax
			
			$Afterpay->create_order_line($sku, $name, $qty, $price, $tax_category);
		}
	}
	
	
	
	// Create the order object for B2C or B2B
	$Afterpay->set_order($aporder, 'B2C');
	
	
	
	// Set up the AfterPay credentials and sent the order
	$authorisation['merchantid'] = $_api_key_1;
	$authorisation['portfolioid'] = '2';
	$authorisation['password'] = $_api_key_2;
	$modus = 'test'; // for production set to 'live'
	//$modus = 'live'; // for test set to 'test'
	
	
	// Request and process the data
	$Afterpay->do_request( $authorisation, $modus);
	$results = $Afterpay->order_result;
	$results = json_decode(json_encode($results), true);
	
	$urlSuccess = "https://" . ($dev ? "dev" : "merchant") . ".justinharings.nl/extensions/payments/process.php?orderID=" . $orderID;
	
	if(isset($results['return']['resultId']) && $results['return']['resultId'] == 0)
	{
		// Require the initialize function from Mollie.
		require_once("/var/www/vhosts/justinharings.nl/" . ($dev ? "dev" : "merchant") . ".justinharings.nl/library/third-party/payment-modules/systems/mollie/database.php");
		
		$data = array();
		$data[0] = 0;
		$data[1] = $orderID;
		$data[2] = $this->calcTotal($orderID);
		$data[3] = $_api_key_1;
		$data[4] = $_api_key_2;
		$data[5] = (isset($_GET['language_pack']) ? $_GET['language_pack'] : "");
	
		database_write($orderID, serialize($data), $dev);
		
		header("location: " . $urlSuccess);
	}
	else
	{
		header("location: " . $_cancel_url . "/error/afterpay/");
	}
}
catch (Exception $e)
{
	// Something failed, go back to the cancel page.
	header("location: " . $_cancel_url . "/error/afterpay/");
	//print $e->getMessage();
}
?>