<?php
define("_DEVELOPMENT_ENVIRONMENT", true);
	
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();



$ftp_server = "ftp.accell-group.com";
$ftp_user = "e-dst-harings";
$ftp_pass = "16d165bR";

$file = "/ArticlesandStock/ARTSTK.csv";

$filename = "ftp://" . $ftp_user . ":" . $ftp_pass . "@" . $ftp_server . $file;
$file = fopen($filename, "r");

$products = array();

while(($line = fgetcsv($file)) !== FALSE) 
{
	$expl = explode(";", $line[0]);
	$products[str_replace(" ", "", $expl[1])] = $expl[3];
}

fclose($file);


$query = sprintf(
	"	UPDATE		products
		SET			products.externalStock = 0,
					products.status = 1
		WHERE		products.externalStockID = 2"
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
		WHERE		products.externalStockID = 2"
);
$result = $db->query($query);

while($row = $db->fetch_assoc($result))
{
	if($row['barcode'] != "")
	{
		$row['supplier_code'] = $row['barcode'];
	}
	else if($row['barcode'] == "" && strlen($row['supplier_code']) > 8)
	{
		$query = sprintf(
			"	UPDATE		products
				SET			products.barcode = products.supplier_code
				WHERE		products.productID = %d",
			$row['productID']
		);
		$db->query($query);
	}
	
	if($row['supplier_code'] != "" && isset($products[$row['supplier_code']]))
	{
		switch($products[$row['supplier_code']])
		{
			case 1:
			case 2:
				$stock = 1;
			break;
			
			case 3:
			case 4:
			case 5:
				$stock = 0;
				
				if($products[$row['supplier_code']] == 3)
				{
					if($row['stock'] <= 0)
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
				else if($products[$row['supplier_code']] == 4 || $products[$row['supplier_code']] == 5)
				{
					if($row['stock'] <= 0)
					{
						$query = sprintf(
							"	UPDATE		products
								SET			products.deleted = 1
								WHERE		products.productID = %d",
							$row['productID']
						);
						$db->query($query);
					}
				}
			break;
			
		}
		
		$query = sprintf(
			"	UPDATE		products
				SET			products.externalStock = %d
				WHERE		products.productID = %d",
			$stock,
			$row['productID']
		);
		$db->query($query);
	}
	else
	{
		$query = sprintf(
			"	UPDATE		products
				SET			products.deleted = 1
				WHERE		products.productID = %d",
			$row['productID']
		);
		$db->query($query);
	}
}
?>