<?php
if(!isset($_SESSION))
{
	session_start();
}

$_SESSION['lastOrder'] = 108;

require_once('/var/www/vhosts/justinharings.nl/httpdocs' . "/lib/php/config.php");
require_once('/var/www/vhosts/justinharings.nl/httpdocs' . "/lib/php/functions.php");
	
require_once(_hec_domain_path . "/core/ordersOrders.php");


$ordersOrders = new ordersOrders();
$orderDetails = $ordersOrders->getOrderDetails($_GET['orderID']);

if($orderDetails['order']['orderID'] != $_SESSION['lastOrder'])
{
	header("location: /");
}


try
{
	include "initialize.php";

	$order_id = $_GET['orderID'];

	$protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
	$hostname = $_SERVER['HTTP_HOST'];
	$path     = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

	/*
	 * Payment parameters:
	 *   amount        Amount in EUROs. This example creates a € 10,- payment.
	 *   description   Description of the payment.
	 *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
	 *   metadata      Custom metadata that is stored with the payment.
	 */
	 
	$payment = $mollie->payments->create(
		array(
			"amount"       => $orderDetails['order']['orderGrandTotal'],
			"description"  => "Betaling voor bestelling #" . $orderDetails['order']['orderID'],
			"redirectUrl"  => "{$protocol}://{$hostname}{$path}//lib/gateways/handle.php?order_id={$order_id}",
			"metadata"     => array(
				"order_id" => $order_id,
			),
		)
	);

	database_write($order_id, $payment->status);

	header("Location: " . $payment->getPaymentUrl());
}
catch (Mollie_API_Exception $e)
{
	// echo "API call failed: " . htmlspecialchars($e->getMessage());
}

function database_write($order_id, $status)
{
	$order_id = intval($order_id);
	$database = dirname(__FILE__) . "/orders/order-{$order_id}.txt";

	file_put_contents($database, $status);
}
