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
		if($row['email_address'] != "")
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
		if($row['email_address'] != "")
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
		if($row['email_address'] != "")
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