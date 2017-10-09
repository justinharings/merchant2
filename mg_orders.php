<?php
require_once("/var/www/vhosts/justinharings.nl/httpdocs/merchant/library/php/classes/databaseConnector.php");
require_once(__DIR__ . "/library/php/classes/database.php");


define("_DEVELOPMENT_ENVIRONMENT", true);

$db_old = new databaseConnector();
$db_new = new database();


$merchantID = 1;

$query = sprintf(
	"	DELETE 			p
		FROM			orders_payment p
		INNER JOIN		orders ON orders.orderID = p.orderID
		WHERE			orders.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE 			p
		FROM			orders_product p
		INNER JOIN		orders ON orders.orderID = p.orderID
		WHERE			orders.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE 			p
		FROM			orders_shipment p
		INNER JOIN		orders ON orders.orderID = p.orderID
		WHERE			orders.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE FROM		orders
		WHERE			orders.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	SELECT			orders.*,
						orders_status.statusID
		FROM			orders
		INNER JOIN		orders_status ON orders_status.orderID = orders.orderID
		WHERE			orders.merchantID = %d
		ORDER BY		orders.date_time DESC",
	$merchantID
);
$result = $db_old->query($query);

$cnt = 0;

while($row = $db_old->fetchAssoc($result))
{
	switch($row['statusID'])
	{
		case 1:
			$row['statusID'] = 1;
		break;
		
		case 2:
			$row['statusID'] = 2;
		break;
		
		case 3:
			$row['statusID'] = 2;
		break;
		
		case 4:
			$row['statusID'] = 3;
		break;
		
		case 5:
			$row['statusID'] = 4;
		break;
		
		case 6:
			$row['statusID'] = 6;
		break;
		
		case 14:
			$row['statusID'] = 1;
		break;
		
		case 16:
			$row['statusID'] = 7;
		break;
	}
	
	switch($row['userID'])
	{
		case 1:
			$row['userID'] = 4;
		break;
		
		case 3:
			$row['userID'] = 1;
		break;
		
		case 4:
			$row['userID'] = 2;
		break;
		
		case 100020:
			$row['userID'] = 3;
		break;
	}
	
	$query2 = sprintf(
		"	SELECT		orders_payment.*
			FROM		orders_payment
			WHERE		orders_payment.orderID = %d",
		$row['orderID']
	);
	$result2 = $db_old->query($query2);
	
	$total_payed = 0;
	
	while($row2 = $db_old->fetchAssoc($result2))
	{
		$total_payed += $row2['amount'];
		
		$paymentID = 0;
		
		switch($row2['paymentID'])
		{
			// Contant
			case 1:
				$paymentID = 9;
			break;
			
			// Pin
			case 2:
				$paymentID = 14;
			break;
			
			// Bankoverschrijving
			case 3:
				$paymentID = 15;
			break;
			
			// iDeal
			case 4:
				$paymentID = 4;
			break;
			
			// Creditcard
			case 5:
				$paymentID = 15;
			break;
			
			// Bancontact
			case 6:
				$paymentID = 6;
			break;
			
			// Sofort
			case 7:
				$paymentID = 7;
			break;
			
			// Afterpay
			case 10:
				$paymentID = 5;
			break;
		}
		
		$query3 = sprintf(
			"	INSERT INTO		orders_payment
				SET				orders_payment.orderID = %d,
								orders_payment.paymentID = %d,
								orders_payment.date = '%s',
								orders_payment.amount = '%.2f'",
			$row['orderID'],
			$paymentID,
			$row2['date'],
			$row2['amount']
		);
		$db_new->query($query3);
	}
	
	$query2 = sprintf(
		"	SELECT		orders_products.*,
						products_content.name,
						taxes.taxrate AS taxes
			FROM		orders_products
			INNER JOIN	products ON products.productID = orders_products.productID
			INNER JOIN	products_content ON products_content.productID = products.productID
				AND		products_content.language = 'nl'
			INNER JOIN	taxes ON taxes.taxesID = orders_products.taxesID
			WHERE		orders_products.orderID = %d",
		$row['orderID']
	);
	$result2 = $db_old->query($query2);
	
	while($row2 = $db_old->fetchAssoc($result2))
	{
		$query3 = sprintf(
			"	INSERT INTO		orders_product
				SET				orders_product.orderID = %d,
								orders_product.productID = %d,
								orders_product.name = '%s',
								orders_product.price = '%.2f',
								orders_product.taxrate = '%.2f',
								orders_product.quantity = %d",
			$row['orderID'],
			$row2['productID'],
			$row2['name'],
			$row2['price'],
			$row2['taxes'],
			$row2['quantity']
		);
		$db_new->query($query3);
	}
	
	$query2 = sprintf(
		"	SELECT		orders_shipment.*
			FROM		orders_shipment
			WHERE		orders_shipment.orderID = %d",
		$row['orderID']
	);
	$result2 = $db_old->query($query2);
	
	while($row2 = $db_old->fetchAssoc($result2))
	{
		$query3 = sprintf(
			"	INSERT INTO		orders_shipment
				SET				orders_shipment.orderID = %d,
								orders_shipment.shipmentID = %d,
								orders_shipment.courier = '%s',
								orders_shipment.price = '%.2f',
								orders_shipment.track_code = '%s'",
			$row['orderID'],
			$row2['shipmentID'],
			$row2['transporter'],
			$row2['price'],
			$row2['track_code']
		);
		$db_new->query($query3);
	}
	
	$query = sprintf(
		"	INSERT INTO		orders
			SET				orders.orderID = %d,
							orders.merchantID = %d,
							orders.customerID = %d,
							orders.statusID = %d,
							orders.employeeID = %d,
							orders.grand_total = '%.2f',
							orders.vat_total = '%.2f',
							orders.payed = '%.2f',
							orders.date_added = '%s'",
		$row['orderID'],
		$row['merchantID'],
		$row['customerID'],
		$row['statusID'],
		$row['userID'],
		$row['grand_total'],
		$row['taxes_total'],
		$total_payed,
		$row['date_time']
	);
	$db_new->query($query);
	
	$cnt++;
}
?>

Done <?= $cnt ?>.