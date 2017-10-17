<?php
require_once("/var/www/vhosts/justinharings.nl/httpdocs/merchant/library/php/classes/databaseConnector.php");
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");

$old_db = new databaseConnector();
$new_db = new database();


$query = sprintf(
	"	DELETE FROM		products
		WHERE			products.merchantID = 3"
);
$new_db->query($query);


$query = sprintf(
	"	SELECT		products.*,
					products_brands.brandID,
					products_groups.groupID,
					products_content.name,
					products_content.description,
					products_stock.stock
		FROM		products
		INNER JOIN	products_brands ON products_brands.productID = products.productID
		INNER JOIN	products_groups ON products_groups.productID = products.productID
		INNER JOIN	products_stock ON products_stock.productID = products.productID
		INNER JOIN	products_content ON products_content.productID = products.productID
			AND		products_content.language = 'nl'
		WHERE		products.merchantID = 2"
);
$result = $old_db->query($query);

while($row = $old_db->fetchAssoc($result))
{
	switch($row['taxesID'])
	{
		case 8:
			$row['taxesID'] = 11;
		break;
		
		case 7:
			$row['taxesID'] = 10;
		break;
		
		case 6:
			$row['taxesID'] = 9;
		break;
	}
	
	
	switch($row['groupID'])
	{
		case 12:
			$row['groupID'] = 13;
		break;
		
		case 7:
			$row['groupID'] = 14;
		break;
		
		case 11:
			$row['groupID'] = 20;
		break;
		
		case 10:
			$row['groupID'] = 21;
		break;
		
		case 9:
			$row['groupID'] = 2;
		break;
		
		case 8:
			$row['groupID'] = 23;
		break;
		
		case 6:
			$row['groupID'] = 24;
		break;
	}
	
	
	switch($row['brandID'])
	{
		case 25:
			$row['brandID'] = 56;
		break;
		
		case 24:
			$row['brandID'] = 57;
		break;
		
		case 27:
			$row['brandID'] = 58;
		break;
		
		case 22:
			$row['brandID'] = 59;
		break;
		
		case 23:
			$row['brandID'] = 60;
		break;
		
		case 21:
			$row['brandID'] = 61;
		break;
		
		case 26:
			$row['brandID'] = 62;
		break;
	}
	
	
	switch($row['productStatusID'])
	{
		case 0:
			$row['statusID'] = 1;
		break;
		
		case 1:
			$row['statusID'] = 2;
		break;
		
		case 2:
			$row['statusID'] = 3;
		break;
		
		case 3:
			$row['statusID'] = 4;
		break;
	}
	
	
	switch(strtolower($row['visibility']))
	{
		case "kassa":
			$row['visibility'] = 1;
		break;
		
		case "webwinkel":
			$row['visibility'] = 2;
		break;
		
		case "kassa, webwinkel":
			$row['visibility'] = 3;
		break;
	}
	
	
	$query2 = sprintf(
		"	INSERT INTO		products
			SET				products.productID = %d,
							products.merchantID = 3,
							products.shipmentID = %d,
							products.taxesID = %d,
							products.groupID = %d,
							products.brandID = %d,
							products.externalStockID = 0,
							products.deleted = 0,
							products.workorders_products = 0,
							products.workorders_manhours = 0,
							products.bookmarks = %d,
							products.delivery_days = %d,
							products.status = %d,
							products.visibility = %d,
							products.maximum = %d,
							products.name = '%s',
							products.article_code = '%s',
							products.supplier_code = '%s',
							products.barcode = '%s',
							products.description = '%s',
							products.price = '%.2f',
							products.price_adviced = '%.2f',
							products.price_purchase = '%.2f',
							products.weight = '%.2f',
							products.date_added = NOW()",
		$row['productID'],
		$row['shipmentID'],
		$row['taxesID'],
		$row['groupID'],
		$row['brandID'],
		$row['bookmarks'],
		$row['order_days'],
		$row['statusID'],
		$row['visibility'],
		$row['maximum'],
		$new_db->real_escape_string($row['name']),
		$new_db->real_escape_string($row['plu']),
		$new_db->real_escape_string($row['sku']),
		$new_db->real_escape_string($row['barcode']),
		$new_db->real_escape_string($row['description']),
		$new_db->floatvalue($row['price']),
		$new_db->floatvalue($row['price_adviced']),
		$new_db->floatvalue($row['price_purchase']),
		$new_db->floatvalue($row['weight'])
	);
	$new_db->query($query2);
	
	
	$query2 = sprintf(
		"	DELETE FROM		products_stock
			WHERE			products_stock.productID = %d",
		$row['productID']
	);
	$new_db->query($query2);
	
	
	$query2 = sprintf(
		"	INSERT INTO		products_stock
			SET				products_stock.productID = %d,
							products_stock.locationID = 16,
							products_stock.stock = %d",
		$row['productID'],
		$row['stock']
	);
	$new_db->query($query2);
	
	
	$query2 = sprintf(
		"	DELETE FROM		products_media
			WHERE			products_media.productID = %d",
		$row['productID']
	);
	$new_db->query($query2);
	
	
	$query2 = sprintf(
		"	SELECT		products_media.*
			FROM		products_media
			WHERE		products_media.productID = %d
				AND		products_media.type = 'image'",
		$row['productID']
	);
	$result2 = $old_db->query($query2);

	while($row2 = $old_db->fetchAssoc($result2))
	{
		$query3 = sprintf(
			"	INSERT INTO		products_media
				SET				products_media.productID = %d,
								products_media.type = 'image',
								products_media.thumb = %d",
			$row['productID'],
			$row2['thumb']
		);
		$new_db->query($query3);
		
		$productsMediaID = $new_db->insert_id();
		
		$url = "https://justinharings.nl/merchant";
		$url .= $row2['url'];
		
		$url = str_replace(".png", "_blank.png", $url);
		
		$content = file_get_contents($url);
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/library/media/products/" . $productsMediaID . ".png", "w");
		fwrite($fp, $content);
		fclose($fp);
	}
	
	
	$query2 = sprintf(
		"	INSERT INTO		products_stock
			SET				products_stock.productID = %d,
							products_stock.locationID = 16,
							products_stock.stock = %d",
		$row['productID'],
		$row['stock']
	);
	$new_db->query($query2);
	
	
	$query4 = sprintf(
		"	DELETE FROM		categories_products
			WHERE			categories_products.productID = %d",
		$row['productID']
	);
	$new_db->query($query4);
	
	
	$query3 = sprintf(
		"	SELECT		categories_products.*
			FROM		categories_products
			WHERE		categories_products.productID = %d",
		$row['productID']
	);
	$result3 = $old_db->query($query3);

	while($row3 = $old_db->fetchAssoc($result3))
	{
		switch($row3['categoryID'])
		{
			default:
				$categoryID = 0;
			break;
			
			case 84:
				$categoryID = 53;
			break;
			
			case 85:
				$categoryID = 57;
			break;
			
			case 85:
				$categoryID = 60;
			break;
			
			case 87:
				$categoryID = 65;
			break;
			
			case 88:
				$categoryID = 67;
			break;
			
			case 89:
				$categoryID = 53;
			break;
			
			case 90:
				$categoryID = 64;
			break;
			
			case 91:
				$categoryID = 61;
			break;
			
			case 92:
				$categoryID = 62;
			break;
			
			case 93:
				$categoryID = 63;
			break;
			
			case 95:
				$categoryID = 54;
			break;
			
			case 96:
				$categoryID = 55;
			break;
			
			case 97:
				$categoryID = 56;
			break;
			
			case 98:
				$categoryID = 58;
			break;
			
			case 99:
				$categoryID = 59;
			break;
		}

			
		if($categoryID > 0)
		{
			$query4 = sprintf(
				"	INSERT INTO		categories_products
					SET				categories_products.productID = %d,
									categories_products.categoryID = %d",
				$row['productID'],
				$categoryID
			);
			$new_db->query($query4);
		}
	}
}

print "Done.";
?>