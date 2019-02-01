<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{
	define("_DEVELOPMENT_ENVIRONMENT", true);
	define("_LANGUAGE_PACK", "nl");
	define("_MERCHANT_ID", 1);
	
	require_once(__DIR__ . "/library/php/classes/motherboard.php");

	$mb = new motherboard();
	
	if($_POST['productID'] != "")
	{
		$query = sprintf(
	  		"	SELECT		products.name
	  			FROM		products
	  			WHERE		products.productID = %d",
	  		intval($_POST['productID'])
	  	);
	  	$result = $mb->query($query);
	  	$row = $mb->fetch_assoc($result);
  		
  		$names = array();
  		
  		if(strpos($row['name'], "heren") !== false)
  		{
	  		$current_color = explode(" heren", $row['name']);
	  		$current_color = $current_color[0];
	  		$current_color = explode(" ", $current_color);
	  		$current_color = $current_color[(count($current_color)-1)];
	  	}
	  	else if(strpos($row['name'], "dames") !== false)
  		{
	  		$current_color = explode(" dames", $row['name']);
	  		$current_color = $current_color[0];
	  		$current_color = explode(" ", $current_color);
	  		$current_color = $current_color[(count($current_color)-1)];
	  	}
	  	else if(strpos($row['name'], "unisex") !== false)
  		{
	  		$current_color = explode(" unisex", $row['name']);
	  		$current_color = $current_color[0];
	  		$current_color = explode(" ", $current_color);
	  		$current_color = $current_color[(count($current_color)-1)];
	  	}
	  	
	  	$names[$current_color] = $_POST['color_nl'];
  		
		$query = sprintf(
			"	INSERT INTO		products (products.merchantID, products.shipmentID, products.taxesID, products.groupID, products.brandID, products.externalStockID, products.deleted, products.workorders_products, products.workorders_manhours, products.bookmarks, products.delivery_days, products.status, products.visibility, products.stock_type, products.maximum, products.name, products.article_code, products.supplier_code, products.barcode, products.description, products.price, products.price_adviced, products.price_purchase, products.weight, products.date_added)
				SELECT 			products.merchantID, products.shipmentID, products.taxesID, products.groupID, products.brandID, products.externalStockID, products.deleted, products.workorders_products, products.workorders_manhours, products.bookmarks, products.delivery_days, products.status, products.visibility, products.stock_type, products.maximum, products.name, %d, '%s', '%s', products.description, products.price, products.price_adviced, products.price_purchase, products.weight, NOW()
				FROM	 		products
				WHERE 			products.productID = %d",
  			$mb->_runFunction("orders", "getNewArticleCode", array(1)),
  			$_POST['supplier_code'],
  			$_POST['barcode'],
  			intval($_POST['productID'])
  		);
  		$mb->query($query);
  		
  		$productID = $mb->insert_id();
  		
  		$query = sprintf(
	  		"	UPDATE		products
	  			SET			products.name = '%s'
	  			WHERE		products.productID = %d",
	  		str_replace($current_color, $_POST['color_nl'], $row['name']),
	  		$productID
	  	);
	  	$mb->query($query);
  		
  		
  		$query = sprintf(
	  		"	INSERT INTO		products_lang (productID, code, name, description, price, price_adviced)
	  			SELECT			%d, products_lang.code, products_lang.name, products_lang.description, products_lang.price, products_lang.price_adviced
	  			FROM			products_lang
	  			WHERE			products_lang.productID = %d",
	  		$productID,
	  		intval($_POST['productID'])
	  	);
	  	$mb->query($query);
	  	
	  	$query = sprintf(
	  		"	SELECT		products_lang.languageID,
	  						products_lang.name
	  			FROM		products_lang
	  			WHERE		products_lang.productID = %d",
	  		$productID
	  	);
	  	$result = $mb->query($query);
	  	
	  	while($row = $mb->fetch_assoc($result))
	  	{
		  	$lang = "";
		  	
		  	if(strpos($row['name'], "gentlemen") !== false)
	  		{
		  		$current_color_lang = explode(" gentlemen", $row['name']);
		  		$current_color_lang = $current_color_lang[0];
		  		$current_color_lang = explode(" ", $current_color_lang);
		  		$current_color_lang = $current_color_lang[(count($current_color_lang)-1)];
		  		
		  		$lang = "en";
		  	}
		  	else if(strpos($row['name'], "ladies") !== false)
	  		{
		  		$current_color_lang = explode(" ladies", $row['name']);
		  		$current_color_lang = $current_color_lang[0];
		  		$current_color_lang = explode(" ", $current_color_lang);
		  		$current_color_lang = $current_color_lang[(count($current_color_lang)-1)];
		  		
		  		$lang = "en";
		  	}
		  	else if(strpos($row['name'], "herren") !== false)
	  		{
		  		$current_color_lang = explode(" herren", $row['name']);
		  		$current_color_lang = $current_color_lang[0];
		  		$current_color_lang = explode(" ", $current_color_lang);
		  		$current_color_lang = $current_color_lang[(count($current_color_lang)-1)];
		  		
		  		$lang = "de";
		  	}
		  	else if(strpos($row['name'], "damen") !== false)
	  		{
		  		$current_color_lang = explode(" damen", $row['name']);
		  		$current_color_lang = $current_color_lang[0];
		  		$current_color_lang = explode(" ", $current_color_lang);
		  		$current_color_lang = $current_color_lang[(count($current_color_lang)-1)];
		  		
		  		$lang = "de";
		  	}
		  	
		  	$names[$current_color_lang] = $_POST['color_' . $lang];
		  	
		  	$query2 = sprintf(
		  		"	UPDATE		products_lang
		  			SET			products_lang.name = '%s'
		  			WHERE		products_lang.languageID = %d",
		  		str_replace($current_color_lang, $_POST['color_' . $lang], $row['name']),
		  		$row['languageID']
		  	);
		  	$mb->query($query2);
		}
	  	
	  	$query = sprintf(
		  	"	INSERT INTO		categories_products (categoryID, productID)
		  		SELECT			categories_products.categoryID, %d
		  		FROM			categories_products
		  		WHERE			categories_products.productID = %d",
		  	$productID,
	  		intval($_POST['productID'])
		);
		$mb->query($query);
		
		$query = sprintf(
			"	INSERT INTO		products_properties (productID, language, `key`, value)
				SELECT			%d, products_properties.language, products_properties.`key`, products_properties.value
				FROM			products_properties
				WHERE			products_properties.productID = %d",
			$productID,
	  		intval($_POST['productID'])
		);
		$mb->query($query);
				
		
		$query = sprintf(
			"	INSERT INTO		products_filters (productID, filterID, language, value)
				SELECT			%d, products_filters.filterID, products_filters.language, products_filters.value
				FROM			products_filters
				WHERE			products_filters.productID = %d",
			$productID,
	  		intval($_POST['productID'])
		);
		$mb->query($query);
		
		$query = sprintf(
			"	SELECT		products_filters.productFilterID,
							products_filters.value
				FROM		products_filters
				WHERE		products_filters.productID = %d",
			$productID
		);
	  	$result = $mb->query($query);
	  	
	  	while($row = $mb->fetch_assoc($result))
	  	{
		 	if(isset($names[strtolower($row['value'])]))
		 	{
			 	$name = $names[strtolower($row['value'])];
			 	
			 	if(strpos($name, "/") !== false)
			 	{
				 	$explode = explode("/", $name);
				 	$name = ucfirst($explode[0]) . "/" . ucfirst($explode[1]);
				}
				else
				{
					$name = ucfirst($name);
				}
			 	
			 	$query2 = sprintf(
					"	UPDATE		products_filters
				 		SET			products_filters.value = '%s'
				 		WHERE		products_filters.productFilterID = %d",
				 	$name,
				 	$row['productFilterID']
				);
				$mb->query($query2);
			}
		}
		
		$query = sprintf(
			"	INSERT INTO		products_pricecheck (productID, website, free_shipment, profit)
				SELECT			%d, products_pricecheck.website, products_pricecheck.free_shipment, products_pricecheck.profit
				FROM			products_pricecheck
				WHERE			products_pricecheck.productID = %d",
			$productID,
	  		intval($_POST['productID'])
		);
		$mb->query($query);
		
		$query = sprintf(
			"	INSERT INTO		products_media
				SET				products_media.productID = %d,
								products_media.type = 'image',
								products_media.youtube_url = '',
								products_media.thumb = 1",
			$productID
		);
		$result = $mb->query($query);
		
		$itemID = $mb->insert_id($result);
		
		$folder = $_SERVER['DOCUMENT_ROOT'] . "/library/media/products/";
		$file = $folder . $itemID . ".png";
		
		move_uploaded_file($_FILES['image']['tmp_name'], $file);
	}
}
?>

<form method="post" enctype="multipart/form-data">
	<input type="text" name="productID" id="productID" value="<?= isset($_POST['productID']) ? intval($_POST['productID']) : "" ?>" placeholder="productID" style="width: 100px; padding: 10px;" /><br/>
	<input type="text" name="supplier_code" id="supplier_code" value="" placeholder="Leverancier code" style="width: 200px; padding: 10px;" /><br/>
	<input type="text" name="barcode" id="barcode" value="" placeholder="EAN Code/Barcode" style="width: 200px; padding: 10px;" /><br/>
	<input type="text" name="color_nl" id="color_nl" value="" placeholder="Kleur NL" style="width: 200px; padding: 10px;" /><br/>
	<input type="text" name="color_en" id="color_en" value="" placeholder="Kleur EN" style="width: 200px; padding: 10px;" /><br/>
	<input type="text" name="color_de" id="color_de" value="" placeholder="Kleur DE" style="width: 200px; padding: 10px;" /><br/>
	<br/>
	<input type="file" name="image" id="image" value="" /><br/>
	<br/>
	<input type="submit" name="opslaan" id="opslaan" value="Opslaan" style="width: 200px; padding: 10px !important;" />
</form>