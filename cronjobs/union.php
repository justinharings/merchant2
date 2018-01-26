<?php
define("_DEVELOPMENT_ENVIRONMENT", true);
	
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();



$array = array(
	"89155451" => "https://www.union.nl/nl/fast-dark-blue.html?id=89155451", 	// 49 cm
	"89155463" => "https://www.union.nl/nl/fast-dark-blue.html?id=89155463", 	// 53 cm
	"89155475" => "https://www.union.nl/nl/fast-dark-blue.html?id=89155475", 	// 57 cm
	
	"89155928" => "https://www.union.nl/nl/fast-cool-grey.html?id=89155928", 	// 49 cm
	"89155940" => "https://www.union.nl/nl/fast-cool-grey.html?id=89155940", 	// 53 cm
	"89155952" => "https://www.union.nl/nl/fast-cool-grey.html?id=89155952", 	// 57 cm
	
	"104779322" => "https://www.union.nl/nl/lite-damesfiets-zilver.html?id=104779322", 	// 57 cm
	
	"87125603" => "https://www.union.nl/nl/flow-berry-blue-dames.html?id=87125603",		// 53cm

	"88193810" => "https://www.union.nl/nl/flow-pistache-green-heren.html?id=88193810"	// 57cm
);



$query = sprintf(
	"	UPDATE		products
		SET			products.externalStock = 0
		WHERE		products.externalStockID = 5"
);
$db->query($query);

$query = sprintf(
	"	SELECT		products.productID,
					products.supplier_code,
					products.barcode,
					(
						SELECT		SUM(products_stock.stock)
						FROM		products_stock
						WHERE		products_stock.productID = products.productID
					) AS stock
		FROM		products
		WHERE		products.externalStockID = 5"
);
$result = $db->query($query);

while($row = $db->fetch_assoc($result))
{
	if(isset($array[$row['supplier_code']]))
	{
		$url = $array[$row['supplier_code']];
		
		$content = file_get_contents($url);
		$content = explode('data-stock="', $content);
		
		$stock = $content[1];
		$stock = explode('"', $stock);
		$stock = $stock[0];
		
		$query = sprintf(
			"	UPDATE		products
				SET			products.externalStock = %d
				WHERE		products.productID = %d",
			$stock,
			$row['productID']
		);
		$db->query($query);
		
		$query = sprintf(
			"	UPDATE		products
				SET			products.status = 1
				WHERE		products.productID = %d",
			$row['productID']
		);
		$db->query($query);
		
		if($stock <= 0)
		{
			$query = sprintf(
				"	UPDATE		products
					SET			products.status = 3
					WHERE		products.productID = %d",
				$row['productID']
			);
			$db->query($query);
		}
	}
	else
	{
		$query = sprintf(
			"	UPDATE		products
				SET			products.status = 3
				WHERE		products.productID = %d",
			$row['productID']
		);
		$db->query($query);
	}
}

echo "done.";
?>