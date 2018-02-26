<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();

$query = sprintf(
	"	UPDATE		assistent_orders
		SET			assistent_orders.ready = 1
		WHERE		assistent_orders.orderID = %d",
	$_POST['orderID']
);
$mb->query($query);
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>