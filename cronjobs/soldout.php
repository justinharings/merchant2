<?php
define("_DEVELOPMENT_ENVIRONMENT", true);
	
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();



$query = sprintf(
	"	UPDATE		products
		INNER JOIN	products_stock ON products_stock.productID = products.productID
		SET			products.status = 4
		WHERE		products.status = 2
			AND		(
						products_stock.stock - (
							SELECT		SUM(orders_product.quantity)
							FROM		orders_product
							INNER JOIN	orders ON orders.orderID = orders_product.orderID
							INNER JOIN	order_statuses ON order_statuses.statusID = orders.statusID
							WHERE		orders_product.productID = products_stock.productID
								AND		order_statuses.finished = 0
								AND 	order_statuses.declined = 0
						)
					) <= 0"
);
$db->query($query);
?>