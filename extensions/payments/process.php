<?php
if(!isset($_SESSION))
{
	session_start();
}


// Get the DEV or LIVE environment.
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$dev = false;

if(strpos($actual_link, "dev.justin") !== false)
{
	$dev = true;
}

define("_DEVELOPMENT_ENVIRONMENT", $dev);


// Get the stored order DATA.
$orderID = intval($_GET['orderID']);
$mollie = false;

$database = "/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/third-party/payment-modules/systems/mollie/orders/order-" . $orderID . ".txt";

if(file_exists($database))
{
	$data = file_get_contents($database);
	$data = unserialize($data);
	
	// Reset the data from the stored file.
	$paymentID 		= $data[0];
	$orderID 		= $data[1];
	$grand_total 	= $data[2];
	$_api_key_1		= $data[3];
	$_api_key_2		= $data[4];
	$_language_pack = $data[5];
	
	if($paymentID != 0 || $paymentID != "")
	{
		$mollie = true;
	}
}


// Include the order data.
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/motherboard.php");

define("_LANGUAGE_PACK", "nl");

$mb = new motherboard();

$order = $mb->_runFunction("orders", "load", array($orderID));
$merchant = $mb->_runFunction("merchant", "load", array($order['merchantID']));

// $merchant['website_url'] = "https://websites.justinharings.nl/";

$_finish_url = $merchant['website_url'] . ($_language_pack != "" ? "/" . $_language_pack . "/" : "") . $merchant['webshop_success_url'];
$_finish_url = str_replace("//", "/", $_finish_url);
$_finish_url = str_replace("https:/", "https://", $_finish_url);

$_cancel_url = $merchant['website_url'] . $merchant['webshop_cancel_url'];
$_cancel_url = str_replace("//", "/", $_cancel_url);
$_cancel_url = str_replace("https:/", "https://", $_cancel_url);


$finished = false;

if($mollie)
{
	// Include the initialize files.
	require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/third-party/payment-modules/systems/mollie/initialize.php");
	
	$payment = $mollie->payments->get($paymentID);
	
	if($payment->isPaid())
	{
		$finished = true;
	}
}
else
{
	$finished = true;
}


if($finished)
{
	// The order is payed. Continue to add the payment to the DB and continue to the shop.
	// print "Finished. Return to " . $_finish_url;
	
	$array = array();
	$array[] = $orderID;
	$array[] = $grand_total;
	
	$mb->_runFunction("orders", "registerPayment", $array);
	
	header("location:" . $_finish_url);
}
else
{
	// The order isn't payed. Go back to the declined page from the webshop.
	//print "declined. Return to " . $_cancel_url; exit;
	
	header("location:" . $_cancel_url . "?orderID=" . $orderID);
}
?>