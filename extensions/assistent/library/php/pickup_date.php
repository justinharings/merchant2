<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();

$query = sprintf(
	"	REPLACE INTO	assistent_orders
		SET				assistent_orders.date = '%s',
						assistent_orders.ready = 0,
						assistent_orders.orderID = %d",
	$mb->datevalue($_POST['value']),
	$_POST['orderID']
);
$mb->query($query);

$query = sprintf(
	"	SELECT		orders_invoice_rules.*
		FROM		orders_invoice_rules
		WHERE		orders_invoice_rules.orderID = %d",
	intval($_POST['orderID'])
);
$result = $mb->query($query);

while($row = $mb->fetch_assoc($result))
{
	if($row['key'] == "Afhaalmoment")
	{
		$query2 = sprintf(
			"	DELETE FROM		orders_invoice_rules
				WHERE			orders_invoice_rules.invoiceRuleID = %d",
			$row['invoiceRuleID']
		);
		$mb->query($query2);
	}
}

$query2 = sprintf(
	"	INSERT INTO		orders_invoice_rules
		SET				orders_invoice_rules.orderID = %d,
						orders_invoice_rules.key = 'Afhaalmoment',
						orders_invoice_rules.value = '%s'",
	intval($_POST['orderID']),
	$_POST['value']
);
$mb->query($query2);

$query = sprintf(
	"	SELECT		COUNT(orders_invoice_rules.invoiceRuleID) AS cnt
		FROM		orders_invoice_rules
		WHERE		orders_invoice_rules.orderID = %d",
	intval($_POST['orderID'])
);
$result = $mb->query($query);
$row = $mb->fetch_assoc($result);

if($row['cnt'] > 4)
{
	$query2 = sprintf(
		"	DELETE FROM		orders_invoice_rules
			WHERE			orders_invoice_rules.key = ''
				AND			orders_invoice_rules.value = ''
			LIMIT			1"
	);
	$mb->query($query2);
}
?>

<script type="text/javascript">
	parent.document.location.href = '/assistent/';
</script>