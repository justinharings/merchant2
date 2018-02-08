<?php
if(!isset($_SESSION))
{
	session_start();
}


define("_LANGUAGE_PACK", "nl");

$_SERVER['DOCUMENT_ROOT'] = "/var/www/vhosts/justinharings.nl/dev.justinharings.nl";

require_once("/var/www/vhosts/justinharings.nl/dev.justinharings.nl/library/php/classes/motherboard.php");

$mb = new motherboard();

$merchants = $mb->_runFunction("merchant", "view");


function _checkWorkorder($orderID)
{
	global $mb;
	
	$query = sprintf(
		"	SELECT		COUNT(orders_product.productID) AS cnt
			FROM		orders_product
			INNER JOIN	products ON products.productID = orders_product.productID
			WHERE		orders_product.orderID = %d
				AND		(
							products.workorders_products = 1
					OR		products.workorders_manhours = 1
						)",
		$orderID
	);
	$result = $mb->query($query);
	$row = $mb->fetch_assoc($result);
	
	return $row['cnt'];
}


foreach($merchants AS $value)
{
	// Orders - Na 7 dagen pauze
	$query = sprintf(
		"	SELECT		orders.*,
						customers.*
			FROM		orders
			INNER JOIN	customers ON customers.customerID = orders.customerID
			INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
			WHERE		DATE(orders.date_added) <= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
				AND		DATE(orders.date_added) > DATE_SUB(CURDATE(), INTERVAL 14 DAY)
				AND		orders.merchantID = %d
				AND		order_statuses.finished = 1
				AND		order_statuses.declined = 0",
		$value['merchantID']
	);
	$result = $mb->query($query);
	
	while($row = $mb->fetch_assoc($result))
	{
		if($row['email_address'] != "" && _checkWorkorder($row['orderID']) == 0)
		{
			$array = array();
			$array[] = $value['merchantID'];
			$array[] = 3;
			$array[] = $row['email_address'];
			$array[] = 0;
			$array[] = $row['orderID'];
			
			$mb->_runFunction("mailserver", "sendAllEmail", $array);
		}		
	}
	
	
	
	// Orders - Na 14 dagen pauze
	$query = sprintf(
		"	SELECT		orders.*,
						customers.*
			FROM		orders
			INNER JOIN	customers ON customers.customerID = orders.customerID
			INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
			WHERE		DATE(orders.date_added) <= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
				AND		DATE(orders.date_added) > DATE_SUB(CURDATE(), INTERVAL 30 DAY)
				AND		orders.merchantID = %d
				AND		order_statuses.finished = 1
				AND		order_statuses.declined = 0",
		$value['merchantID']
	);
	$result = $mb->query($query);
	
	while($row = $mb->fetch_assoc($result))
	{
		if($row['email_address'] != "" && _checkWorkorder($row['orderID']) == 0)
		{
			$array = array();
			$array[] = $value['merchantID'];
			$array[] = 4;
			$array[] = $row['email_address'];
			$array[] = 0;
			$array[] = $row['orderID'];
			
			$mb->_runFunction("mailserver", "sendAllEmail", $array);
		}
	}
	
	
	
	// Orders - Na 30 dagen pauze
	$query = sprintf(
		"	SELECT		orders.*,
						customers.*
			FROM		orders
			INNER JOIN	customers ON customers.customerID = orders.customerID
			INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
			WHERE		DATE(orders.date_added) <= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
				AND		DATE(orders.date_added) > DATE_SUB(CURDATE(), INTERVAL 40 DAY)
				AND		orders.merchantID = %d
				AND		order_statuses.finished = 1
				AND		order_statuses.declined = 0",
		$value['merchantID']
	);
	$result = $mb->query($query);
	
	while($row = $mb->fetch_assoc($result))
	{
		if($row['email_address'] != "" && _checkWorkorder($row['orderID']) == 0)
		{
			$array = array();
			$array[] = $value['merchantID'];
			$array[] = 5;
			$array[] = $row['email_address'];
			$array[] = 0;
			$array[] = $row['orderID'];
			
			$mb->_runFunction("mailserver", "sendAllEmail", $array);
		}
	}
}
?>