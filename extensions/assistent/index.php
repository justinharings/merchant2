<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}


/*
**	POS Only operates in the NL language pack.
*/

define("_LANGUAGE_PACK", "nl");



/*
**	Tell the classes and functions if the development
**	mode is activated or not. This will allow the classes
**	to display a user-friendly message or the real 
**	PHP exception for the developer.
*/

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

define("_DEVELOPMENT_ENVIRONMENT", (strpos($actual_link, "dev.") !== false ? true : false));
$_SESSION['_DEVELOPMENT_ENVIRONMENT'] = _DEVELOPMENT_ENVIRONMENT;



/*
**	Functions are added here. Used for quick access to all
**	of the extended special functions, all the files
**	are added to the core here.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");



/*
**	Classes are included here. We use a motherboard
**	class that is able to construct all the classes
**	and is able to run this class his function.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();


function checkStock($order)
{
	require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

	$mb = new motherboard();
	
	$stockCheck = sprintf(
		"	SELECT		COUNT(assistent_stock.stockID) AS cnt
			FROM		assistent_stock
			WHERE		assistent_stock.orderID = %d",
		intval($order['orderID'])
	);
	$resultCheck = $mb->query($stockCheck);
	$rowCheck = $mb->fetch_assoc($resultCheck);
	
	if($rowCheck['cnt'] == 0)
	{
		if(count($order['products']) > 0)
		{
			foreach($order['products'] AS $product)
			{
				if($product['productID'] == 132 || $product['productID'] == 131 || $product['productID'] == 134)
				{
					continue;
				}
				
				$queryAddStock = sprintf(
					"	INSERT INTO		assistent_stock
						SET				assistent_stock.orderID = %d,
										assistent_stock.productID = %d",
					intval($order['orderID']),
					$product['productID']
				);
				$mb->query($queryAddStock);
			}
		}
	}
}



/*
**	Check the last time we've cleaned up
**	the article database.
*/

$query = sprintf(
	"	DELETE FROM 	assistent_hold
		WHERE 			NOW() - INTERVAL 15 MINUTE > assistent_hold.date_time"
);
$mb->query($query);

if(!isset($_GET['module']))
{
	$query = sprintf(
		"	SELECT		assistent_callback.*
			FROM		assistent_callback
			LIMIT		0,1"
	);
	$result = $mb->query($query);
	
	if($mb->num_rows($result))
	{
		$row = $mb->fetch_assoc($result);
		
		header("location: /assistent/?module=callback&callbackID=" . $row['callbackID'] . "&productID=" . $row['productID'] . "&number=" . $row['number']);
		exit;
	}
	
	$query = sprintf(
		"	SELECT		assistent.timer
			FROM		assistent"
	);
	$result = $mb->query($query);
	$row = $mb->fetch_assoc($result);
	
	$start = $row['timer'];
	$end = date("Y-m-d G:i:s");
	
	$date1 = new DateTime($start);
	$date2 = new DateTime($end);
	
	$diff = $date1->diff($date2);
	
	$hours = $diff->days * 24;
	$hours += $diff->h;
	
	if($hours > 7 && true == false)
	{
		header("location: /assistent/?module=cleanup");
		exit;
	}
	else
	{
		$query = sprintf(
			"	SELECT		assistent_orders.orderID
				FROM		assistent_orders
				INNER JOIN	orders ON orders.orderID = assistent_orders.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				LEFT JOIN	assistent_hold ON assistent_hold.orderID = orders.orderID
				WHERE		DATE(assistent_orders.date) <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)
					AND		assistent_orders.ready = 0
					AND		order_statuses.finished = 0
					AND 	order_statuses.declined = 0
					AND		assistent_hold.orderID IS NULL
				LIMIT		1"
		);
		$result = $mb->query($query);
		
		if($mb->num_rows($result) > 0)
		{
			$row = $mb->fetch_assoc($result);
			
			header("location: /assistent/?module=pickup&orderID=" . $row['orderID']);
			exit;
		}
		else
		{
			$orders = $mb->_runFunction("orders", "view", array(1, "", "orders.date_added DESC", "0,50", 1));
			
			if($mb->num_rows($orders))
			{
				foreach($orders AS $value)
				{
					$query = sprintf(
						"	SELECT		assistent_orders.*
							FROM		assistent_orders
							INNER JOIN	orders ON orders.orderID = assistent_orders.orderID
							INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
							WHERE		assistent_orders.orderID = %d
								AND		order_statuses.finished = 0
								AND 	order_statuses.declined = 0",
						$value['orderID']
					);
					$result = $mb->query($query);
					$row = $mb->fetch_assoc($result);
					
					$date = new DateTime($row['date']);
					$now = new DateTime(date("Y-m-d"));
					
					if($mb->num_rows($result) == 0 || (($date < $now) && $row['ready'] == 1))
					{
						//print  $row['date'] . "<br/>" . $row['orderID'] . "<br/>" . $row['ready']; exit;
						header("location: /assistent/?module=order&orderID=" . $value['orderID'] . "&ready=" . $row['ready']);
						exit;
					}
				}
			}
		}
	}
	
	$stockCheck = sprintf(
		"	SELECT		assistent_stock.*
			FROM		assistent_stock
			WHERE		(
							assistent_stock.deleted = 0
					OR		(	
								assistent_stock.deleted = 1
						AND		DATEDIFF(NOW(), assistent_stock.delay) > 21
							)
						)
			LIMIT		0,1"
	);
	$resultCheck = $mb->query($stockCheck);
	$rowCheck = $mb->fetch_assoc($resultCheck);
	
	if($rowCheck['stockID'] > 0)
	{
		if	(
				$rowCheck['deleted'] == 0
				|| $rowCheck['deleted'] == 1 && $rowCheck['delay'] != "0000-00-00"
			)
		{
			header("location: /assistent/?module=stock&stockID=" . $rowCheck['stockID']);
			exit;
		}
	}
	
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
				
				if($diff < 7)
				{
					continue;
				}
				else
				{
					$workorderID = $value['workorderID'];
					$workorder = $value;
					
					header("location: /assistent/?module=workorder&workorderID=" . $workorderID);
					exit;
				}
			}
		}
	}
	
	$data = $mb->_runFunction("reviews", "view", array(1, "reviews.approved", "0,50", "= 0"));
	
	if($mb->num_rows($data))
	{
		foreach($data AS $value)
		{
			header("location: /assistent/?module=review&reviewID=" . $value['reviewID']);
			exit;
		}
	}
}

$color = "black";

switch($_GET['module'])
{
	case "cleanup":
		$color = "";
	break;
	
	case "pickup":
		$color = "green";
	break;
	
	case "order":
		$color = "blue";
	break;
	
	case "workorder":
		$color = "orange";
	break;
	
	case "review":
		$color = "red";
	break;
	
	case "callback":
		$color = "purple";
	break;
	
	case "calendar":
		$color = "white";
	break;
		
	case "stock":
		$color = "white";
	break;
}
?>

<!DOCTYPE html>
<html lang="<?= _LANGUAGE_PACK ?>">
	<head>
		<title>Winkelassistent</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="Content-Language" content="<?= _LANGUAGE_PACK ?>" />
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
		
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
		<meta name="format-detection" content="telephone=no" />
		
		<meta name="robots" content="no-index, no-follow" />

		<link rel="apple-touch-icon" href="/library/media/apple-icon.png" />
		<link type="image/x-icon" rel="icon" href="/library/media/favicon.png" />
		<link type="image/x-icon" rel="shortcut icon" href="/library/media/favicon.png" />
		
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/motherboard.minified.css" />
		<link rel="stylesheet" type="text/css" href="/library/css/winkelassistent.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		
		<script type="text/javascript" src="/library/js/dashboard.minified.js"></script>
		<script type="text/javascript" src="/library/js/datepicker.minified.js"></script>
		<script type="text/javascript" src="/library/js/emails.minified.js"></script>
		<script type="text/javascript" src="/library/js/framework.minified.js"></script>
		<script type="text/javascript" src="/library/js/input.minified.js"></script>
		<script type="text/javascript" src="/library/js/multiselect.minified.js"></script>
		<script type="text/javascript" src="/library/js/notes.minified.js"></script>
		<script type="text/javascript" src="/library/js/sms.minified.js"></script>
		
		<script type="text/javascript" src="/library/js/assistent.js"></script>
		<script type="text/javascript" src="/library/js/pos.js"></script>
	</head>

	<body class="<?= $color ?>">
		<div class="loader"><span class="fa fa-spinner fa-spin"></span></div>
		
		<div class="popup-overlay"></div>
		
		<div class="popup-container">
			<div class="closer"><span class="fa fa-times"></span></div>
			<iframe src="about:blank"></iframe>
		</div>
		
		<div class="total-calendar">
			<a href="/assistent/?module=calendar">
				<span class="fa fa-calendar"></span>
			</a>
		</div>
		
		<div class="container">
			<?php
			if(isset($_GET['module']) && $_GET['module'] == "cleanup")
			{
				$array = array();
				$array[] = 1;
				$array[] = "";
				$array[] = "categories.name";
				$array[] = 9999;
				$array[] = 1;
				
				$use = array();
				
				$return = $mb->_runFunction("categories", "view", $array);
				
				foreach($return AS $key => $value)
				{
					$sArray = array();
					$sArray[] = 1;
					$sArray[] = "";
					$sArray[] = "categories.name";
					$sArray[] = 9999;
					$sArray[] = $value['categoryID'];
					
					$sReturn = $mb->_runFunction("categories", "view", $sArray);
					
					foreach($sReturn AS $sKey => $sValue)
					{						
						$use[] = $sValue['categoryID'];
					}
				}
				
				$categoryID = array_rand($use);
				$categoryID = $use[$categoryID];
				
				$sArray = array();
				$sArray[] = 1;
				$sArray[] = "";
				$sArray[] = "categories.name";
				$sArray[] = 9999;
				$sArray[] = $categoryID;
				
				$sReturn = $mb->_runFunction("categories", "view", $sArray);
				
				foreach($sReturn AS $sKey => $sValue)
				{
					$use[] = $sValue['categoryID'];
				}
				
				$categoryID = array_rand($use);
				$categoryID = $use[$categoryID];
				
				$category = $mb->_runFunction("categories", "load", array($categoryID));
				$products = $mb->_runFunction("products", "front_loadProducts", array(1, $categoryID, ""));
				?>
				
				<form method="post" id="post" action="/extensions/assistent/library/php/cleanup.php">
					<div class="save-button post">
						<span class="fa fa-save"></span>
					</div>
					
					<h1>Categorie <em><?= $category['name'] ?></em> opruimen</h1>
					
					<div class="content-assistent">
						<?php
						$cnt = 0;
						$num = 0;
						
						foreach($products AS $product)
						{
							if($product['stock'] > 0)
							{
								continue;
							}
							
							$product['name'] = unserialize($product['name']);
							?>
							
							<div class="product-tile <?= $cnt == 0 ? "first" : "" ?>">
								<div class="overlay <?= $product['status'] == 4 ? "white" : "" ?>"></div>
								
								<img src="<?= $product['image'] != "" ? $product['image'] : "https://www.haringstweewielers.com/library/media/no-image.png" ?>" />
								<span><?= $product['name']['nl'] ?></span>
								
								<input type="hidden" name="productID[]" id="productID" value="<?= $product['productID'] ?>" />
								<input type="hidden" name="status[]" id="status" value="<?= ($product['status'] == 4 ? 1 : 0) ?>" class="status" />
							</div>
							
							<?php
								
							$cnt++;
							$num++;
							
							if($cnt == 4)
							{
								$cnt = 0;
							}
						}
						?>
					</div>
				</form>
				<?php
				
				if($num == 0)
				{
					?>
					<script type="text/javascript">
						document.location.href = document.location.href;
					</script>
					<?php
				}	
			}
			else if(isset($_GET['module']) && $_GET['module'] == "order")
			{
				$order = $mb->_runFunction("orders", "load", array(intval($_GET['orderID'])));
				checkStock($order);
				?>
				<div class="save-button calendar" orderID="<?= intval($_GET['orderID']) ?>">
					<span class="fa fa-calendar"></span>
				</div>
				
				<h1>Datum toevoegen aan order #<?= $order['order_reference'] . ($_GET['ready'] == 1 ? " (NO SHOW EERDERE DATUM)" : "") ?></h1>
				
				<div class="content-assistent flexible first">
					<table class="form-table">
						<thead>
							<tr>
								<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "barcode") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "quantity") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "price") ?></td>
							</tr>
						</thead>
						
						<tbody>
							<?php
							if(count($order['products']) > 0)
							{
								foreach($order['products'] AS $product)
								{
									$calc_vat = ($product['taxrate'] / 100) + 1;
									$product['price_ex_vat'] = ($product['price'] / $calc_vat);
									?>
									<tr id="<?= $product['orderProductID'] ?>">
										<td><?= $product['article_code'] ?></td>
										<td><?= ($product['barcode'] != "" ? $product['barcode'] : "Onbekend") ?></td>
										<td><?= $product['quantity'] ?> stuk(s)</td>
										<td><?= $product['name'] ?></td>
										<td><?= $product['price'] ?></td>
									</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
				
				<div class="content-assistent flexible">
					<table class="form-table">
						<thead>
							<tr>
								<td><?= $mb->_translateReturn("table-headers", "shipment-method") ?></td>
								<td width="110"><?= $mb->_translateReturn("table-headers", "price") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "courier") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "track-trace-code") ?></td>
							</tr>
						</thead>
						
						<tbody>
							<?php
							$data_shipments = $mb->_runFunction("shipment_methods", "view", array(1, "", "shipment_methods.name", "0,50"));
								
							if(count($order['shipments']) > 0)
							{
								foreach($order['shipments'] AS $shipment)
								{
									?>
									<tr>
										<td>
											<?php
											foreach($data_shipments AS $method)
											{
												print ($shipment['shipmentID'] == $method['shipmentID'] ? $method['name'] : "");
											}
											?>
										</td>
										<td>&euro;&nbsp;<?= _frontend_float($shipment['price']) ?></td>
										<td><?= $shipment['courier'] ?></td>
										<td><?= ($shipment['track_code'] == "" ? "Onbekend" : $shipment['track_code']) ?></td>
									</tr>
									<?php
								}
							}
							?>
						</tbody>
					</table>
				</div>
				
				<div class="content-assistent flexible">
					<table class="form-table">
						<thead>
							<tr>
								<td width="250"><?= $mb->_translateReturn("table-headers", "title") ?></td>
								<td><?= $mb->_translateReturn("table-headers", "content-assistent") ?></td>
							</tr>
						</thead>
						
						<tbody>
							<?php
							for($i = 1; $i <= 4; $i++)
							{
								if($order['invoice_rules'][$i-1]['key'] == "")
								{
									continue;
								}
								?>
								<tr>
									<td><?= $order['invoice_rules'][$i-1]['key'] ?></td>
									<td><?= $order['invoice_rules'][$i-1]['value'] ?></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
				
				<div class="content-assistent flexible">
					<table>
						<tr>
							<td style="padding: 0px 50px 0px 0px;" valign="top">
								<?php
								if($order['customer']['name'] == "")
								{
									print "Dit betreft een kassa<br/>verkoop zonder<br/>klantgegvens. Toegevoegd<br/>via een point of sale.";
								}
								else
								{
									?>
									<?= $order['customer']['name'] ?><br/>
									<?= $order['customer']['address'] ?><br/>
									<?= $order['customer']['zip_code'] ?> <?= $order['customer']['city'] ?><br/>
									<?= $order['customer']['country'] ?><br/>
									<br/>
									<?php
									if($order['customer']['email_address'] != "")
									{
										?>
										<?= $order['customer']['email_address'] ?><br/>
										<?php
									}
									else
									{
										print "Geen e-mail adres.<br/>";
									}
									
									if($order['customer']['phone'] == "")
									{
										$order['customer']['phone'] = $order['customer']['mobile_phone'];	
									}
									
									if($order['customer']['phone'] != "")
									{
										print "Tel: ". $order['customer']['phone'];
									}
									else
									{
										print "Geen telefoonnummer.";
									}
								}
								?>
							</td>
							
							<td valign="top" width="200">
								<?php
								if($order['customer']['name'] == "")
								{
									?>
									Er zijn <strong><?= $order['customer']['count_orders'] ?> andere bestelling(en)</strong> met een <br/>
									totaal besteed bedrag<br/>
									van <strong>&euro; <?= _frontend_float($order['customer']['total_orders']) ?></strong>.
									<?php
								}
								else
								{
									if($order['customer']['count_orders'] > 0)
									{
										?>
										Er zijn <span style="font-weight: bold;"><?= $order['customer']['count_orders'] ?> andere bestelling(en)</span> met een
										totaal besteed bedrag van <span style="font-weight: bold;">&euro; <?= _frontend_float($order['customer']['total_orders']) ?></span>.
										Alle bestellingen van deze klant zijn inzichtelijk via het account.<br/>
										<Br/>
										<?php
									}
									else
									{
										?>
										Dit is de eerste bestelling op dit account. Wanneer de klant straks meerdere bestellingen heeft
										staat er hier een samenvatting daarover.<br/>
										<br/>
										
										<?php
									}
								}
								?>
							</td>
						</tr>
					</table>
				</div>
				<?php
			}
			else if(isset($_GET['module']) && $_GET['module'] == "pickup")
			{
				$order = $mb->_runFunction("orders", "load", array(intval($_GET['orderID'])));
				checkStock($order);
				?>
				<form method="post" id="post" action="/extensions/assistent/library/php/ready.php">
					<input type="hidden" name="orderID" id="orderID" value="<?= intval($_GET['orderID']) ?>" />
					<div class="save-button calendar" orderID="<?= intval($_GET['orderID']) ?>">
						<span class="fa fa-calendar"></span>
					</div>
					
					<div class="save-button second post">
						<span class="fa fa-check"></span>
					</div>
					
					<h1>Order #<?= $order['order_reference'] ?> klaarzetten</h1>
					
					<div class="content-assistent flexible first">
						<table class="form-table">
							<thead>
								<tr>
									<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "barcode") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "quantity") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "price") ?></td>
								</tr>
							</thead>
							
							<tbody>
								<?php
								if(count($order['products']) > 0)
								{
									foreach($order['products'] AS $product)
									{
										$calc_vat = ($product['taxrate'] / 100) + 1;
										$product['price_ex_vat'] = ($product['price'] / $calc_vat);
										?>
										<tr id="<?= $product['orderProductID'] ?>">
											<td><?= $product['article_code'] ?></td>
											<td><?= ($product['barcode'] != "" ? $product['barcode'] : "Onbekend") ?></td>
											<td><?= $product['quantity'] ?> stuk(s)</td>
											<td><?= $product['name'] ?></td>
											<td><?= $product['price'] ?></td>
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
					</div>
					
					<div class="content-assistent flexible">
						<table class="form-table">
							<thead>
								<tr>
									<td><?= $mb->_translateReturn("table-headers", "shipment-method") ?></td>
									<td width="110"><?= $mb->_translateReturn("table-headers", "price") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "courier") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "track-trace-code") ?></td>
								</tr>
							</thead>
							
							<tbody>
								<?php
								$data_shipments = $mb->_runFunction("shipment_methods", "view", array(1, "", "shipment_methods.name", "0,50"));
									
								if(count($order['shipments']) > 0)
								{
									foreach($order['shipments'] AS $shipment)
									{
										?>
										<tr>
											<td>
												<?php
												foreach($data_shipments AS $method)
												{
													print ($shipment['shipmentID'] == $method['shipmentID'] ? $method['name'] : "");
												}
												?>
											</td>
											<td>&euro;&nbsp;<?= _frontend_float($shipment['price']) ?></td>
											<td><?= $shipment['courier'] ?></td>
											<td><?= ($shipment['track_code'] == "" ? "Onbekend" : $shipment['track_code']) ?></td>
										</tr>
										<?php
									}
								}
								?>
							</tbody>
						</table>
					</div>
					
					<div class="content-assistent flexible">
						<table class="form-table">
							<thead>
								<tr>
									<td width="250"><?= $mb->_translateReturn("table-headers", "title") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "content-assistent") ?></td>
								</tr>
							</thead>
							
							<tbody>
								<?php
								for($i = 1; $i <= 4; $i++)
								{
									if($order['invoice_rules'][$i-1]['key'] == "")
									{
										continue;
									}
									?>
									<tr>
										<td><?= $order['invoice_rules'][$i-1]['key'] ?></td>
										<td><?= $order['invoice_rules'][$i-1]['value'] ?></td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
					</div>
					
					<div class="content-assistent flexible">
					<table>
						<tr>
							<td style="padding: 0px 50px 0px 0px;" valign="top">
								<?php
								if($order['customer']['name'] == "")
								{
									print "Dit betreft een kassa<br/>verkoop zonder<br/>klantgegvens. Toegevoegd<br/>via een point of sale.";
								}
								else
								{
									?>
									<?= $order['customer']['name'] ?><br/>
									<?= $order['customer']['address'] ?><br/>
									<?= $order['customer']['zip_code'] ?> <?= $order['customer']['city'] ?><br/>
									<?= $order['customer']['country'] ?><br/>
									<br/>
									<?php
									if($order['customer']['email_address'] != "")
									{
										?>
										<?= $order['customer']['email_address'] ?><br/>
										<?php
									}
									else
									{
										print "Geen e-mail adres.<br/>";
									}
									
									if($order['customer']['phone'] == "")
									{
										$order['customer']['phone'] = $order['customer']['mobile_phone'];	
									}
									
									if($order['customer']['phone'] != "")
									{
										print "Tel: ". $order['customer']['phone'];
									}
									else
									{
										print "Geen telefoonnummer.";
									}
								}
								?>
							</td>
							
							<td valign="top" width="200">
								<?php
								if($order['customer']['name'] == "")
								{
									?>
									Er zijn <strong><?= $order['customer']['count_orders'] ?> andere bestelling(en)</strong> met een <br/>
									totaal besteed bedrag<br/>
									van <strong>&euro; <?= _frontend_float($order['customer']['total_orders']) ?></strong>.
									<?php
								}
								else
								{
									if($order['customer']['count_orders'] > 0)
									{
										?>
										Er zijn <span style="font-weight: bold;"><?= $order['customer']['count_orders'] ?> andere bestelling(en)</span> met een
										totaal besteed bedrag van <span style="font-weight: bold;">&euro; <?= _frontend_float($order['customer']['total_orders']) ?></span>.
										Alle bestellingen van deze klant zijn inzichtelijk via het account.<br/>
										<Br/>
										<?php
									}
									else
									{
										?>
										Dit is de eerste bestelling op dit account. Wanneer de klant straks meerdere bestellingen heeft
										staat er hier een samenvatting daarover.<br/>
										<br/>
										
										<?php
									}
								}
								?>
							</td>
						</tr>
					</table>
				</div>
				</form>
				<?php
			}
			else if(isset($_GET['module']) && $_GET['module'] == "workorder")
			{		
				$workorder = $mb->_runFunction("workorders", "loadWorkorder", array(intval($_GET['workorderID'])));		
				?>
				<form method="post" id="post" action="">
					<input type="hidden" name="workorderID" id="workorderID" value="<?= intval($_GET['workorderID']) ?>" />
					
					<div class="save-button post" form-action="/extensions/assistent/library/php/workorder_delete.php">
						<span class="fa fa-trash"></span>
					</div>
					
					<div class="save-button second post" form-action="/extensions/assistent/library/php/workorder_resend.php">
						<span class="fa fa-refresh"></span>
					</div>
					
					<div class="save-button third post" form-action="/extensions/assistent/library/php/workorder_postpone.php">
						<span class="fa fa-calendar"></span>
					</div>
					
					<h1>Werkorder #<?= $_GET['workorderID'] ?></h1>
					
					<div class="content-assistent flexible first">
						<?= $workorder['expiration_date'] ?> - 
						<?= $workorder['workorder'] ?>
					</div>
				</form>
				<?php
			}
			else if(isset($_GET['module']) && $_GET['module'] == "review")
			{		
				$review = $mb->_runFunction("reviews", "load", array(intval($_GET['reviewID'])));
				?>
				<form method="post" id="post" action="">
					<input type="hidden" name="reviewID" id="reviewID" value="<?= intval($_GET['reviewID']) ?>" />
					
					<div class="save-button post" form-action="/extensions/assistent/library/php/review_delete.php">
						<span class="fa fa-trash"></span>
					</div>
					
					<div class="save-button second post" form-action="/extensions/assistent/library/php/review_approve.php">
						<span class="fa fa-check"></span>
					</div>
					
					<h1>Review van <?= $review['name'] ?> controleren</h1>
					
					<div class="content-assistent flexible first">
						<center>
							<?php
							for($i = 1; $i < 6; $i++)
							{
								if($review['stars'] >= $i)
								{
									print '<span class="fa fa-star review-large"></span>';
								}
								else
								{
									print '<span class="fa fa-star-o review-large"></span>';
								}
							}
							
							print "<br/><br/>" . $review['description'];
							?>
						</center>
					</div>
				</form>
				<?php
			}
			else if(isset($_GET['module']) && $_GET['module'] == "stock")
			{		
				$queryStock = sprintf(
					"	SELECT		assistent_stock.*
						FROM		assistent_stock
						WHERE		assistent_stock.stockID = %d",
					intval($_GET['stockID'])
				);
				$resultStock = $mb->query($queryStock);
				$rowStock = $mb->fetch_assoc($resultStock);
				
				$data = $mb->_runFunction("products", "load", array($rowStock['productID']));
				?>
				<form method="post" id="post" action="">
					<input type="hidden" name="stockID" id="stockID" value="<?= intval($_GET['stockID']) ?>" />
					
					<div class="save-button post" form-action="/extensions/assistent/library/php/stock_delete.php">
						<span class="fa fa-trash"></span>
					</div>
					
					<div class="save-button second post" form-action="/extensions/assistent/library/php/stock_delay.php">
						<span class="fa fa-refresh"></span>
					</div>
					
					<h1>Verkocht artikel bestellen</h1>
					
					<div class="content-assistent flexible first">
						<center>
							<?php
							$thumb = "https://haringstweewielers.com/library/media/no-image.png";

							foreach($data['images'] AS $media)
							{
								if($media['thumb'])
								{
									$thumb = "https://merchant.justinharings.nl/library/media/products/" . $media['productMediaID'] . ".png";
								}
							}
							?>
							
							<img height="300" itemprop="image" src="<?= $thumb ?>" /><br/>
							<Br/>
							<span style="font-weight: bold;"><?= $data['name'] ?></span><br/><br/><br/>
							
							<?php
							$data_locations = $mb->_runFunction("stock", "viewLocations", array(1, "", "locations.name", "0,50"));

							foreach($data_locations AS $location)
							{
								$stock = $mb->_runFunction("stock", "getStock", array($_GET['dataID'], $location['locationID']));
								?>

								<div class="form-content">
									Voorraden &#187; <?= $location['name'] ?><br/>
									<br/>
									<table>
										<tr>
											<td width="130">Voorraad:</td>
											<td><?= $stock['stock'] ?> <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
										</tr>

										<tr>
											<td><span style="font-weight: bold;">Gereserveerd:</span></td>
											<td><?= $stock['reserved'] ?> <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
										</tr>

										<tr>
											<td>Economisch:</td>
											<td><?= ($stock['stock']-$stock['reserved']) ?> <?= $mb->_translateReturn("forms", "legend-stocks-inline") ?></td>
										</tr>
									</table>
								</div>
								<?php
							}
							?>
						</center>
					</div>
				</form>
				<?php
			}
			else if(isset($_GET['module']) && $_GET['module'] == "callback")
			{		
				$product = $mb->_runFunction("products", "load", array(intval($_GET['productID'])));
				?>
				<form method="post" id="post" action="/extensions/assistent/library/php/callback_delete.php">
					<input type="hidden" name="callbackID" id="callbackID" value="<?= intval($_GET['callbackID']) ?>" />
					
					<div class="save-button post">
						<span class="fa fa-trash"></span>
					</div>
					
					<h1>Openstaand terugbel verzoek</h1>
					
					<div class="content-assistent flexible first">
						<center>
							<span style="font-size: 50px;"><?= $_GET['number'] ?></span><br/>
							<br/><br/>
							Deze klant wil graag meer informatie over het volgende product:<br/>
							<br/>
							<span style="font-weight: bold; font-size: 18px;"><?= $product['name'] ?></span><br/>
							Dit product kost <span style="font-weight: bold; font-size: 18px;"><?= $product['price'] ?> euro</span> en wordt
							<?= (count($product['pricecheck']) == 0 ? "niet" : "") ?> vergeleken met andere webshops.
						</center>
					</div>
					
					<?php
					if(count($product['pricecheck']) > 0)
					{
						?>
						<div class="content-assistent flexible">
							<table class="form-table">
								<thead>
									<tr>
										<td><?= $mb->_translateReturn("forms", "form-products-pricecheck-website") ?></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
								</thead>
								
								<tbody>
									<?php
									foreach($product['pricecheck'] AS $value)
									{
										?>
										<tr>
											<td><?= $value['website'] ?></td>
											<td>
												<?php
												if($value['price'] == 0)
												{
													print "Mislukt";
												}
												else
												{
													print "&euro;&nbsp;". _frontend_float($value['price']);
												}
												?>
											</td>
											<td><?= $value['date_update'] ?></td>
										</tr>
										<?php
									}
									?>
								</tbody>
							</table>
						</div>
						<?php
					}
					?>
					
					<div class="content-assistent flexible">
						<table class="form-table">
							<thead>
								<tr>
									<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
									<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "sc") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
									<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "visible") ?></td>
									<td><?= $mb->_translateReturn("table-headers", "price") ?></td>
									<td class="hide-mobile"><?= $mb->_translateReturn("table-headers", "added") ?></td>
								</tr>
							</thead>
							
							<tbody>
								<tr>
									<td><?= $product['article_code'] ?></td>
									<td><?= $product['supplier_code'] ?></td>
									<td><?= $product['name'] ?></td>
									<td><?= $mb->_runFunction("products", "translateVisibility", array($product['visibility'])) ?></td>
									<td>&euro;&nbsp;<?= $product['price'] ?></td>
									<td><?= _dutchDate($product['date_added'], "date") . " om " . _dutchDate($product['date_added'], "time-short") ?> uur</td>
								</tr>
							</tbody>
						</table>
					</div>
				</form>
				<?php
			}
			else if(isset($_GET['module']) && $_GET['module'] == "calendar")
			{	
				$query = sprintf(
					"	SELECT		assistent_orders.*
						FROM		assistent_orders
						INNER JOIN	orders ON orders.orderID = assistent_orders.orderID
						INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
						WHERE		assistent_orders.ready = 0
							AND		order_statuses.finished = 0
							AND 	order_statuses.declined = 0
						ORDER BY	assistent_orders.date"
				);
				$result = $mb->query($query);
				
				while($row = $mb->fetch_assoc($result))
				{
					$order = $mb->_runFunction("orders", "load", array(intval($row['orderID'])));
					?>
					<a href="/assistent/?module=order&orderID=<?= $row['orderID'] ?>&ready=<?= $row['ready'] ?>">
						<div class="content-assistent flexible">
							<strong><?= _dutchDate($row['date'], "date") ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $order['customer']['name'] ?></strong>
							
							<hr/>
							
							<table class="form-table">
								<thead>
									<tr>
										<td><?= $mb->_translateReturn("table-headers", "ac") ?></td>
										<td><?= $mb->_translateReturn("table-headers", "barcode") ?></td>
										<td><?= $mb->_translateReturn("table-headers", "quantity") ?></td>
										<td><?= $mb->_translateReturn("table-headers", "product") ?></td>
										<td><?= $mb->_translateReturn("table-headers", "price") ?></td>
									</tr>
								</thead>
								
								<tbody>
									<?php
									if(count($order['products']) > 0)
									{
										foreach($order['products'] AS $product)
										{
											$calc_vat = ($product['taxrate'] / 100) + 1;
											$product['price_ex_vat'] = ($product['price'] / $calc_vat);
											?>
											<tr id="<?= $product['orderProductID'] ?>">
												<td><?= $product['article_code'] ?></td>
												<td><?= ($product['barcode'] != "" ? $product['barcode'] : "Onbekend") ?></td>
												<td><?= $product['quantity'] ?> stuk(s)</td>
												<td><?= $product['name'] ?></td>
												<td><?= $product['price'] ?></td>
											</tr>
											<?php
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</a>
					<?php
				}
				
				print "<br/><br/><br/><br/><br/><br/>";
			}
			?>
		</div>
		
		<?php
		if(isset($_GET['module']))
		{
			?>
			<div class="timeblock">
				<div class="date">
					<?= date("d-m-Y G:i") ?> uur
				</div>
				
				<?php
				if(isset($_GET['module']) && $_GET['module'] == "pickup")
				{
					?>
					<div class="fa fa-history" onclick="document.location.href = '/extensions/assistent/library/php/pickup_park.php?orderID=<?= $_GET['orderID'] ?>';"></div>
					<?php
				}
				?>
				
				<div class="scroll up fa fa-caret-up"></div>
				<div class="scroll down fa fa-caret-down"></div>
				
				<div class="refresh fa fa-refresh" onclick="document.location.href = '/assistent/';"></div>
			</div>
			<?php
		}
		?>
	</body>
</html>