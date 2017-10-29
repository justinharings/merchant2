<?php
function database_write($order_id, $status, $dev)
{
	$order_id = intval($order_id);
	$database = "/var/www/vhosts/justinharings.nl/" . ($dev ? "dev" : "merchant") . ".justinharings.nl/library/third-party/payment-modules/systems/mollie/orders/order-{$order_id}.txt";

	file_put_contents($database, $status);
}
?>