<?php
require_once("/var/www/vhosts/justinharings.nl/httpdocs/merchant/library/php/classes/databaseConnector.php");
require_once(__DIR__ . "/library/php/classes/database.php");


define("_DEVELOPMENT_ENVIRONMENT", true);

$db_old = new databaseConnector();
$db_new = new database();


$merchantID = 1;

$query = sprintf(
	"	DELETE 			p
		FROM			products_filters p
		INNER JOIN		products ON products.productID = p.productID
		WHERE			products.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE 			p
		FROM			products_lang p
		INNER JOIN		products ON products.productID = p.productID
		WHERE			products.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE 			p
		FROM			products_media p
		INNER JOIN		products ON products.productID = p.productID
		WHERE			products.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE 			p
		FROM			products_properties p
		INNER JOIN		products ON products.productID = p.productID
		WHERE			products.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE 			p
		FROM			products_stock p
		INNER JOIN		products ON products.productID = p.productID
		WHERE			products.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE 			p
		FROM			categories_products p
		INNER JOIN		products ON products.productID = p.productID
		WHERE			products.merchantID = %d",
	$merchantID
);
$db_new->query($query);

$query = sprintf(
	"	DELETE FROM		products
		WHERE			products.merchantID = %d",
	$merchantID
);
$db_new->query($query);



$query = sprintf(
	"	SELECT		products.*
		FROM		products
		WHERE		products.merchantID = %d",
	$merchantID
);
$result = $db_old->query($query);

$round = 1;

while($row = $db_old->fetchAssoc($result))
{
	print "Round " . $round . "<br/>";
	
	$shipmentID = 0;
	$taxesID = 0;
	$groupID = 0;
	$brandID = 0;
	$statusID = 0;
	$visibility = 0;
	
	switch($row['shipmentID'])
	{
		// Levering koerier á 29 euro
		case 1:
			$shipmentID = 1;
		break;
		
		// Levering PostNL á 10 euro
		case 2:
			$shipmentID = 2;
		break;
		
		// Levering bakfiets á 50 euro
		case 3:
			$shipmentID = 3;
		break;
		
		// Afhalen in de winkel
		case 4:
			$shipmentID = 4;
		break;
		
		// Wegbrengen in de regio
		case 5:
			$shipmentID = 5;
		break;
		
		// Levering PostNL á 6 euro
		case 6:
			$shipmentID = 6;
		break;
		
		// Levering brievenbus á 2,50 euro
		case 44:
			$shipmentID = 44;
		break;
	}
	
	switch($row['taxesID'])
	{
		// 21 procent
		case 1:
			$taxesID = 1;
		break;
		
		// 6 procent
		case 2:
			$taxesID = 2;
		break;
		
		// 0 procent
		case 3:
			$taxesID = 3;
		break;
	}
	
	$query_group = sprintf(
		"	SELECT		products_groups.*
			FROM		products_groups
			WHERE		products_groups.productID = %d",
		$row['productID']
	);
	$result_group = $db_old->query($query_group);
	$row_group = $db_old->fetchAssoc($result_group);
	
	switch($row_group['groupID'])
	{
		// Nieuwe fietsen
		case 1:
			$groupID = 1;
		break;
		
		// Onderdelen en accessoires
		case 2:
			$groupID = 2;
		break;
		
		// Inruil/Inkoop
		case 3:
			$groupID = 3;
		break;
		
		// Tweedehands verkoop
		case 4:
			$groupID = 4;
		break;
		
		// Montage/Reparatie
		case 5:
			$groupID = 5;
		break;
	}
	
	$query_brand = sprintf(
		"	SELECT		products_brands.*
			FROM		products_brands
			WHERE		products_brands.productID = %d",
		$row['productID']
	);
	$result_brand = $db_old->query($query_brand);
	$row_brand = $db_old->fetchAssoc($result_brand);
	
	$brandID = $row_brand['brandID'];
	
	switch($row['productStatusID'])
	{
		case 0:
			$statusID = 1;
		break;
		
		case 1:
			$statusID = 2;
		break;
		
		case 2:
			$statusID = 3;
		break;
		
		case 3:
			$statusID = 4;
		break;
	}
	
	switch(strtolower($row['visibility']))
	{
		case "kassa":
			$visibility = 1;
		break;
		
		case "webwinkel":
			$visibility = 2;
		break;
		
		case "kassa, webwinkel":
			$visibility = 3;
		break;
	}
	
	$query_content = sprintf(
		"	SELECT		products_content.*
			FROM		products_content
			WHERE		products_content.productID = %d
				AND		products_content.language = 'nl'",
		$row['productID']
	);
	$result_content = $db_old->query($query_content);
	$row_content = $db_old->fetchAssoc($result_content);
	
	$query_insert = sprintf(
		"	INSERT INTO		products
			SET				products.productID = %d,
							products.merchantID = %d,
							products.shipmentID = %d,
							products.taxesID = %d,
							products.groupID = %d,
							products.brandID = %d,
							products.externalStockID = %d,
							products.deleted = %d,
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
		$merchantID,
		$shipmentID,
		$taxesID,
		$groupID,
		$brandID,
		$row['externStockID'],
		$row['deleted'],
		$row['bookmarks'],
		$row['order_days'],
		$statusID,
		$visibility,
		$row['maximum'],
		$db_new->real_escape_string($row_content['name']),
		$row['plu'],
		$row['sku'],
		($row['barcode'] == 0 ? "" : $row['barcode']),
		$db_new->real_escape_string($row_content['description']),
		$row['price'],
		$row['price_adviced'],
		$row['price_purchase'],
		$row['weight']
	);
	$db_new->query($query_insert);
	
	$query_media = sprintf(
		"	SELECT		products_media.*
			FROM		products_media
			WHERE		products_media.productID = %d",
		$row['productID']
	);
	$result_media = $db_old->query($query_media);
	
	while($row_media = $db_old->fetchAssoc($result_media))
	{
		if($row_media['type'] == "image")
		{
			$image = "https://www.justinharings.nl/merchant" . $row_media['url'];
			$thumb = $row_media['thumb'];
			
			$file_headers = @get_headers($image);
			
			if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') 
			{
			    $exists = false;
			}
			else 
			{
			    $content = file_get_contents($image);
			    
			    $query_insert_media = sprintf(
					"	INSERT INTO		products_media
						SET				products_media.productID = %d,
										products_media.type = 'image',
										products_media.thumb = %d",
					$row_media['productID'],
					$thumb
				);
				$db_new->query($query_insert_media);
				$insertID = $db_new->insert_id();
				
				$fp = fopen(__DIR__ . "/library/media/products/" . $insertID . ".png", "w");
				fwrite($fp, $content);
				fclose($fp);
			}
		}
	}
	
	$query_properties = sprintf(
		"	SELECT		products_properties.*
			FROM		products_properties
			WHERE		products_properties.productID = %d",
		$row['productID']
	);
	$result_properties = $db_old->query($query_properties);
	
	while($row_properties = $db_old->fetchAssoc($result_properties))
	{
		$query_insert_properties = sprintf(
			"	INSERT INTO		products_properties
				SET				products_properties.productID = %d,
								products_properties.language = 'nl',
								products_properties.key = '%s',
								products_properties.value = '%s'",
			$row['productID'],
			$db_new->real_escape_string($row_properties['title']),
			$db_new->real_escape_string($row_properties['propertie'])
		);
		$db_new->query($query_insert_properties);
	}
	
	$query_stock = sprintf(
		"	SELECT		products_stock.*
			FROM		products_stock
			WHERE		products_stock.productID = %d",
		$row['productID']
	);
	$result_stock = $db_old->query($query_stock);
	$row_stock = $db_old->fetchAssoc($result_stock);
	
	$query_stock = sprintf(
		"	INSERT INTO		products_stock
			SET				products_stock.productID = %d,
							products_stock.locationID = 3,
							products_stock.stock = %d",
		$row['productID'],
		$row_stock['stock']
	);
	$db_new->query($query_stock);
	
	$query_categories = sprintf(
		"	SELECT		categories_products.*
			FROM		categories_products
			WHERE		categories_products.productID = %d",
		$row['productID']
	);
	$result_categories = $db_old->query($query_categories);
	
	while($row_categories = $db_old->fetchAssoc($result_categories))
	{
		switch($row_categories['categoryID'])
		{
			default:
				$row_categories['categoryID'] = $row_categories['categoryID'];
			break;
			
			case 1:
				$row_categories['categoryID'] = 1;
			break;
			
			case 2:
				$row_categories['categoryID'] = 44;
			break;
			
			case 4:
				$row_categories['categoryID'] = 4;
			break;
			
			case 5:
				$row_categories['categoryID'] = 11;
			break;
			
			case 6:
				$row_categories['categoryID'] = 2;
			break;
			
			case 7:
				$row_categories['categoryID'] = 15;
			break;
			
			case 8:
				$row_categories['categoryID'] = 21;
			break;
			
			case 9:
				$row_categories['categoryID'] = 49;
			break;
			
			case 10:
				$row_categories['categoryID'] = 7;
			break;
			
			case 11:
				$row_categories['categoryID'] = 8;
			break;
			
			case 12:
				$row_categories['categoryID'] = 9;
			break;
			
			case 13:
				$row_categories['categoryID'] = 3;
			break;
			
			case 14:
				$row_categories['categoryID'] = 10;
			break;
			
			case 16:
				$row_categories['categoryID'] = 22;
			break;
			
			case 17:
				$row_categories['categoryID'] = 23;
			break;
			
			case 18:
				$row_categories['categoryID'] = 24;
			break;
			
			case 131:
				$row_categories['categoryID'] = 25;
			break;
			
			case 24:
				$row_categories['categoryID'] = 12;
			break;
			
			case 25:
				$row_categories['categoryID'] = 13;
			break;
			
			case 27:
				$row_categories['categoryID'] = 50;
			break;
			
			case 28:
				$row_categories['categoryID'] = 51;
			break;
			
			case 29:
				$row_categories['categoryID'] = 52;
			break;
			
			case 31:
				$row_categories['categoryID'] = 42;
			break;
			
			case 32:
				$row_categories['categoryID'] = 36;
			break;
			
			case 34:
				$row_categories['categoryID'] = 5;
			break;
			
			case 35:
				$row_categories['categoryID'] = 35;
			break;
			
			case 36:
				$row_categories['categoryID'] = 28;
			break;
			
			case 37:
				$row_categories['categoryID'] = 26;
			break;
			
			case 106:
				$row_categories['categoryID'] = 27;
			break;
			
			case 42:
				$row_categories['categoryID'] = 31;
			break;
			
			case 43:
				$row_categories['categoryID'] = 32;
			break;
			
			case 44:
				$row_categories['categoryID'] = 33;
			break;
			
			case 45:
				$row_categories['categoryID'] = 34;
			break;
			
			case 46:
				$row_categories['categoryID'] = 46;
			break;
			
			case 47:
				$row_categories['categoryID'] = 47;
			break;
			
			case 48:
				$row_categories['categoryID'] = 43;
			break;
			
			case 49:
				$row_categories['categoryID'] = 48;
			break;
			
			case 53:
				$row_categories['categoryID'] = 37;
			break;
			
			case 54:
				$row_categories['categoryID'] = 38;
			break;
			
			case 55:
				$row_categories['categoryID'] = 39;
			break;
			
			case 56:
				$row_categories['categoryID'] = 40;
			break;
			
			case 57:
				$row_categories['categoryID'] = 41;
			break;
			
			case 114:
				$row_categories['categoryID'] = 16;
			break;
			
			case 115:
				$row_categories['categoryID'] = 17;
			break;
			
			case 116:
				$row_categories['categoryID'] = 18;
			break;
			
			case 117:
				$row_categories['categoryID'] = 19;
			break;
			
			case 118:
				$row_categories['categoryID'] = 20;
			break;
		}
		
		if	(
				$row_categories['categoryID'] == 3
				|| $row_categories['categoryID'] == 120
				|| $row_categories['categoryID'] == 23
				|| $row_categories['categoryID'] == 26
				|| $row_categories['categoryID'] == 30
				|| $row_categories['categoryID'] == 33
				|| $row_categories['categoryID'] == 38
				|| $row_categories['categoryID'] == 39
				|| $row_categories['categoryID'] == 40
			)
		{
			continue;
		}
		
		$query_insert_categories = sprintf(
			"	INSERT INTO		categories_products
				SET				categories_products.categoryID = %d,
								categories_products.productID = %d",
			$row_categories['categoryID'],
			$row_categories['productID']
		);
		$db_new->query($query_insert_categories);
	}
	
	$round++;
}

print "done.";
?>
