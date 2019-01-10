<?php
if(!isset($_SESSION))
{
	session_start();
}


define("_LANGUAGE_PACK", "nl");

$_SESSION['merchantID'] = 1;


$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

define("_DEVELOPMENT_ENVIRONMENT", (strpos($actual_link, "dev.") !== false ? true : false));
$_SESSION['_DEVELOPMENT_ENVIRONMENT'] = _DEVELOPMENT_ENVIRONMENT;


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");


$mb = new motherboard();


if(!isset($_GET['module']))
{
		// ##. Verwijderen van de 15 minuten uitstelregel.
		
		$query = sprintf(
			"	DELETE FROM 	ass2_postpone
				WHERE 			NOW() - INTERVAL 15 MINUTE > ass2_postpone.timestamp"
		);
		$mb->query($query);
		
		
		// ##. Verwijderen van opgehaalde orders.
		
		$query = sprintf(
			"	DELETE 			ass2_calendar
				FROM			ass2_calendar
				INNER JOIN		orders ON orders.orderID = ass2_calendar.orderID
				INNER JOIN		order_statuses ON order_statuses.statusID = orders.statusID
				WHERE			order_statuses.finished = 1"
		);
		$mb->query($query);
		
	
	// #1. Terugbelverzoek
	
	$query = sprintf(
		"	SELECT		ass2_callback.*
			FROM		ass2_callback
			LIMIT		0,1"
	);
	$result = $mb->query($query);
	
	if($mb->num_rows($result))
	{
		$row = $mb->fetch_assoc($result);


		header("location: /assistent2/?module=callback&callbackID=" . $row['callbackID']);
		exit;
	}
	
	
	// #2. Klaarzetten van order
	
	$query = sprintf(
		"	SELECT		ass2_calendar.*
			FROM		ass2_calendar
			LEFT JOIN	ass2_postpone ON ass2_postpone.orderID = ass2_calendar.orderID
			WHERE		DATE(ass2_calendar.date) <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)
				AND		ass2_postpone.orderID IS NULL
				AND		ass2_calendar.ready = 0
			LIMIT		0,1"
	);
	$result = $mb->query($query);
	
	if($mb->num_rows($result))
	{
		$row = $mb->fetch_assoc($result);
		
		header("location: /assistent2/?module=ready&orderID=" . $row['orderID']);
		exit;
	}
	
	
		// ##. Behandelen van niet opgehaalde bestellingen
		$query = sprintf(
			"	SELECT		ass2_calendar.*
				FROM		ass2_calendar
				WHERE		ass2_calendar.ready = 1
					AND		DATE(ass2_calendar.date) < CURDATE()
				LIMIT		0,1"
		);
		$result = $mb->query($query);
		
		if($mb->num_rows($result))
		{
			$row = $mb->fetch_assoc($result);
			
			header("location: /assistent2/?module=order&noshow=true&orderID=" . $row['orderID']);
			exit;
		}
		
	
	// #3. Nieuwe orders inplannen
	
	$orders = $mb->_runFunction("orders", "view", array(1, "", "orders.date_added DESC", "0,50", 1));
	
	if($mb->num_rows($orders))
	{
		foreach($orders AS $value)
		{
			$query = sprintf(
				"	SELECT		ass2_calendar.*
					FROM		ass2_calendar
					WHERE		ass2_calendar.orderID = %d
					LIMIT		0,1",
				$value['orderID']
			);
			$result = $mb->query($query);
			
			if(!$mb->num_rows($result))
			{
				header("location: /assistent2/?module=order&orderID=" . $value['orderID']);
				exit;
			}
		}
	}
	
	
	// #4. Voorraad onder de nul weergeven
	
	$query = sprintf(
		"	SELECT		products.productID
			FROM		products
			WHERE		(
							SELECT		SUM(products_stock.stock)
							FROM		products_stock
							WHERE		products_stock.productID = products.productID
						) < 0
				AND		products.price > 0
				AND		products.merchantID = 1
				AND		products.deleted = 0"
	);
	$result = $mb->query($query);
	
	if($mb->num_rows($result))
	{
		$row = $mb->fetch_assoc($result);
		
		header("location: /assistent2/?module=count&productID=" . $row['productID']);
		exit;
	}
	
	
	// #5. Verkochte producten
	
	$query = sprintf(
		"	SELECT		orders_product.*
			FROM		orders_product
			INNER JOIN	orders ON orders.orderID = orders_product.orderID
			INNER JOIN	products ON products.productID = orders_product.productID
			LEFT JOIN	ass2_order_products ON ass2_order_products.orderProductID = orders_product.orderProductID
			INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
			WHERE		YEAR(orders.date_added) = 2019
				AND		products.price > 0
				AND		ass2_order_products.orderProductID IS NULL
				AND		orders.merchantID = 1
				AND		products.deleted = 0
				AND		order_statuses.finished = 1
				AND		order_statuses.declined = 0"
	);
	$result = $mb->query($query);
	
	if($mb->num_rows($result))
	{
		$row = $mb->fetch_assoc($result);
		
		header("location: /assistent2/?module=product&productID=" . $row['productID'] . "&orderProductID=" . $row['orderProductID']);
		exit;
	}
	
	
		// ##. Voorraad notificaties
		
		$query = sprintf(
			"	SELECT		ass2_stock_alert.*
				FROM		ass2_stock_alert
				WHERE		(
								SELECT		SUM(products_stock.stock)
								FROM		products_stock
								WHERE		products_stock.productID = ass2_stock_alert.productID
							) > ass2_stock_alert.stock"
		);
		$result = $mb->query($query);
		
		if($mb->num_rows($result))
		{
			$row = $mb->fetch_assoc($result);
			
			$query = sprintf(
				"	DELETE FROM		ass2_stock_alert
					WHERE			ass2_stock_alert.productID = %d",
				$row['productID']
			);
			$mb->query($query);
			
			header("location: /assistent2/?module=stock&productID=" . $row['productID']);
			exit;
		}
		
		
		// ##. Watchlist notificatie
		
		$query = sprintf(
			"	SELECT		ass2_stock_watchlist.*
				FROM		ass2_stock_watchlist
				WHERE		DATEDIFF(NOW(), ass2_stock_watchlist.date) > 1"
		);
		$result = $mb->query($query);
		
		if($mb->num_rows($result))
		{
			$row = $mb->fetch_assoc($result);
			
			$query = sprintf(
				"	UPDATE		ass2_stock_watchlist
					SET			ass2_stock_watchlist.date = NOW()
					WHERE		ass2_stock_watchlist.productID = %d",
				$row['productID']
			);
			$result = $mb->query($query);
			
			header("location: /assistent2/?module=watchlist&productID=" . $row['productID']);
			exit;
		}
		
		
	// #6. Workorders
	
	$data = $mb->_runFunction("workorders", "view", array(1, "", "workorders.expiration_date ASC, workorders.priority DESC, workorders.date_added ASC", "0,100"));
	
	$workorderID = 0;
	
	if($mb->num_rows($data))
	{
		foreach($data AS $value)
		{
			if($workorderID == 0)
			{
				if($value['status'] == 2)
				{
					continue;
				}
				
				$start = $value['expiration_date_core'];
				$end = date("Y-m-d");
				
				$date1 = new DateTime($start);
				$date2 = new DateTime($end);
				
				$diff = $date2->diff($date1)->format("%a");
				
				$workorder = $value;
				
				if($diff > 7)
				{
					header("location: /assistent2/?module=workorder&workorderID=" . $value['workorderID']);
					exit;
				}
			}
		}
	}
	
	
	// #7. Reviews
	
	$query = sprintf(
		"	SELECT		reviews.reviewID
			FROM		reviews
			WHERE		reviews.approved = 0
				AND		reviews.merchantID = 1
			LIMIT		0,1"
	);
	$result = $mb->query($query);
	
	if($mb->num_rows($result))
	{
		$row = $mb->fetch_assoc($result);
		
		header("location: /assistent2/?module=review&reviewID=" . $row['reviewID']);
		exit;
	}
}
?>

<!DOCTYPE html>
<html lang="nl">
	<head>
		<title>Merchant Assistent</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="nl" />

		<meta name="robots" content="no-index, no-follow" />

		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/extensions/assistent2/library/css/assistent.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<script type="text/javascript" src="/extensions/assistent2/library/js/assistent.js"></script>
	</head>

	<body>
		<?php
		if(isset($_GET['module']) && file_exists(__DIR__ . "/modules/" . $_GET['module'] . ".php"))
		{
			require_once(__DIR__ . "/modules/" . $_GET['module'] . ".php");
		}
		else
		{
			?>
			<div class="filler"></div>
			
			<script type="text/javascript">
				$(document).ready(
					function($)
					{
						setTimeout(
							function()
							{
								document.location.reload();
							}, 30000
						);
					}
				);
			</script>
			<?php
		}
		?>
	</body>
</html>	