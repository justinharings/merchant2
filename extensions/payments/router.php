<?php
if(!isset($_SESSION))
{
	session_start();
}

$_api_key_1 = $_module_keys[0];
$_api_key_2 = $_module_keys[1];

$file = "/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/third-party/payment-modules/" . $_load_module . "/payment.php";

if(file_exists($file))
{
	$merchant = $this->_runFunction("merchant", "load", array($data[0]));
	
	// $merchant['website_url'] = "https://websites.justinharings.nl/";
	
	if(!isset($_POST['paylink']))
	{
		$_cancel_url = $merchant['website_url'] . $merchant['webshop_cancel_url'];
		$_cancel_url = str_replace("//", "/", $_cancel_url);
		$_cancel_url = str_replace("https:/", "https://", $_cancel_url);
		
		$_cancel_url = $_cancel_url . "?orderID=" . $orderID;
	}
	else
	{
		$_cancel_url = $merchant['website_url'] . "/paylink/" . base64_encode($orderID);
	}
	
	require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/third-party/payment-modules/" . $_load_module . "/payment.php");
}
else
{
	die("Loaded module not found.");
}
?>