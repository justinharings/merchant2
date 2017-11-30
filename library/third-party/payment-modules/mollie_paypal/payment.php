<?php	
try
{
	// Require the initialize function from Mollie.
	require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/third-party/payment-modules/systems/mollie/initialize.php");

	// Calculate the grand total for this order.
	$grand_total = $this->calcTotal($orderID);
	 
	// Setup a Mollie payment.
	$payment = $mollie->payments->create(
		array(
			"amount"       => $grand_total,
			"description"  => "Betaling voor order #" . $orderID,
			"redirectUrl"  => "https://merchant.justinharings.nl/extensions/payments/process.php?orderID=" . $orderID,
			"method"	   => "paypal",
			"metadata"     => array(
				"order_id" => $orderID,
			),
		)
	);

	// Store data that can be used later on.
	$data = array();
	$data[0] = $payment->id;
	$data[1] = $orderID;
	$data[2] = $grand_total;
	$data[3] = $_api_key_1;
	$data[4] = $_api_key_2;
	$data[5] = (isset($_GET['language_pack']) ? $_GET['language_pack'] : "");

	database_write($orderID, serialize($data), _DEVELOPMENT_ENVIRONMENT);

	
	// Go to the Mollie session that has been started.
	header("Location: " . $payment->getPaymentUrl());
}
catch (Mollie_API_Exception $e)
{
	// Something failed, go back to the cancel page.
	header("location: " . $_cancel_url);
}
?>