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
	$query = sprintf(
		"	DELETE FROM		assistent_stock
			WHERE			assistent_stock.merchantID = 3
				AND			CURDATE() >= assistent_stock.delay + interval 1 day"
	);
	$mb->query($query);
	
	
	
	$query = sprintf(
		"	SELECT		products.productID,
						products.name,
						products.price
			FROM		products
			LEFT JOIN	assistent_stock ON assistent_stock.productID = products.productID
			WHERE		products.merchantID = 3
				AND		products.status < 3
				AND		assistent_stock.productID IS NULL
				AND		products.deleted = 0
			ORDER BY	LPAD(products.article_code, 5, 0) DESC"
	);
	$result = $mb->query($query);
	
	$products = array();
	
	while($row = $mb->fetch_assoc($result))
	{
		$query2 = sprintf(
			"	SELECT		SUM(orders_product.quantity) AS sold
				FROM		orders_product
				INNER JOIN	orders ON orders.orderID = orders_product.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		YEAR(orders.date_added) = YEAR(NOW())
					AND		order_statuses.declined = 0
					AND		orders_product.productID = %d",
			$row['productID']
		);
		$result2 = $mb->query($query2);
		$row2 = $mb->fetch_assoc($result2);
		
		$sold = $row2['sold'];
		
		$query3 = sprintf(
			"	SELECT		products_stock.stock
				FROM		products_stock
				WHERE		products_stock.productID = %d",
			$row['productID']
		);
		$result3 = $mb->query($query3);
		$row3 = $mb->fetch_assoc($result3);
		
		$stock = $row3['stock'];
		
		$query4 = sprintf(
			"	SELECT		SUM(orders_product.quantity) AS sold
				FROM		orders_product
				INNER JOIN	orders ON orders.orderID = orders_product.orderID
				INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
				WHERE		YEAR(orders.date_added) = YEAR(NOW())
					AND		order_statuses.finished = 0
					AND		order_statuses.declined = 0
					AND		orders_product.productID = %d",
			$row['productID']
		);
		$result4 = $mb->query($query4);
		$row4 = $mb->fetch_assoc($result4);
		
		$reserved = $row4['sold'];
		
		$products[$row['productID']]['name'] = $row['name'];
		$products[$row['productID']]['price'] = $row['price'];
		$products[$row['productID']]['sold'] = ($sold > 0 ? $sold : 0);
		$products[$row['productID']]['stock'] = ($stock != "" ? $stock : 0);
		$products[$row['productID']]['reserved'] = ($reserved > 0 ? $reserved : 0);
	}
	
	foreach($products AS $productID => $data)
	{
		if(strpos($data['name'], "[UA]") !== false)
		{
			continue;
		}
		
		if(($data['stock'] - $data['reserved']) < 0)
		{
			header("location: /vwass/?module=check&productID=" . $productID . "&sold=" . $data['sold'] . "&stock=" . $data['stock'] . "&reserved=" . $data['reserved']);
			exit;
		}
		
		$dflt = ceil($data['sold']/5);
		$dflt = ($dflt == 0 ? 1 : $dflt);
		
		if(($data['stock'] - $data['reserved']) < $dflt)
		{
			header("location: /vwass/?module=voorraad&productID=" . $productID . "&sold=" . $data['sold'] . "&stock=" . $data['stock'] . "&reserved=" . $data['reserved']);
			exit;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="nl">
	<head>
		<title>Vuurwerk assistent</title>

		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="/extensions/vuurwerkassistent/library/css/style.css" />

		<script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
		
		<script type="text/javascript">
			$(document).ready(
				function($)
				{
					setInterval(
						function()
						{
							var cnt = parseInt($("div.counter").find("span").html());
							cnt = cnt - 1;
							
							if(cnt == 0)
							{
								document.location.href = '/vwass/';
							}
							
							$("div.counter").find("span").html(cnt);
						}, 1000
					);
					
					$("div.button").on("click",
						function()
						{
							$("div.button, div.counter").hide();
						}
					);
					
					<?php
					if(!isset($_GET['module']))
					{
						?>
						$("*").on("click",
							function()
							{
								document.location.href = '/vwass/';
							}
						);
						<?php
					}
					?>
				}
			);
		</script>
		
		<?php
		if(!isset($_GET['module']))
		{
			?>
			<style type="text/css">
				body
				{
					background-color: #000 !important;
				}
				
				div.container
				{
					display: none;
				}
			</style>
			<?php
		}
		?>
	</head>

	<body>
		<div class="container">
			<?php
			if(isset($_GET['module']))
			{
				require_once(__DIR__ . "/modules/" . $_GET['module'] . ".php");
			}
			?>
			
			<div class="footer">
				<div class="counter">
					<span>120</span> seconden
				</div>
				
				<a href="/vwass/">
					<div class="button">
						<span class="fa fa-sync"></span>
					</div>
				</a>
				
				<a href="/assistent/">
					<div class="button">
						<span class="fa fa-bicycle"></span>
					</div>
				</a>
				
				<div class="spacer"></div>
				
				<div class="button">
					<span class="fa fa-arrow-up"></span>
				</div>
				
				<div class="button">
					<span class="fa fa-arrow-down"></span>
				</div>
			</div>
		</div>
	</body>
</html>	