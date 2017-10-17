<?php

require_once dirname(__FILE__) . "/src/Mollie/API/Autoloader.php";

/*
 * Initialize the Mollie API library with your API key.
 *
 * See: https://www.mollie.nl/beheer/account/profielen/
 */

 
$mollie = new Mollie_API_Client;
$mollie->setApiKey($_api_key_1);


function database_write($order_id, $status, $dev)
{
	$order_id = intval($order_id);
	$database = "/var/www/vhosts/justinharings.nl/" . ($dev ? "dev" : "merchant") . ".justinharings.nl/library/third-party/payment-modules/systems/mollie/orders/order-{$order_id}.txt";

	file_put_contents($database, $status);
}