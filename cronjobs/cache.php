<?php
if(!isset($_SESSION))
{
	session_start();
}


define("_LANGUAGE_PACK", "nl");

$_SERVER['DOCUMENT_ROOT'] = "/var/www/vhosts/justinharings.nl/dev.justinharings.nl";

require_once("/var/www/vhosts/justinharings.nl/dev.justinharings.nl/library/php/classes/motherboard.php");

$mb = new motherboard();

$query = sprintf(
	"	SELECT		categories_products.productID,
					categories_products.categoryID,
					categories.stock_type,
					categories.merchantID,
					brands.name AS brand,
					products.status,
					products.article_code,
					products.barcode
		FROM		categories_products
		INNER JOIN	categories ON categories.categoryID = categories_products.categoryID
		INNER JOIN	products ON products.productID = categories_products.productID
		LEFT JOIN	brands ON brands.brandID = products.brandID
		WHERE		products.visibility IN(2, 3)
			AND		products.deleted = 0"
);
$result = $mb->query($query);

$insert = array();
$num = 0;

while($row = $mb->fetch_assoc($result))
{
	$_lang = $mb->_allLanguages();
	$languages = "";
	
	foreach($_lang AS $value)
	{
		$languages .= sprintf(
			"	(
					SELECT		products_lang.name
					FROM		products_lang
					WHERE		products_lang.productID = products.productID
						AND		products_lang.code = '%s'
				) AS %s_name,
				(
					SELECT		products_lang.price
					FROM		products_lang
					WHERE		products_lang.productID = products.productID
						AND		products_lang.code = '%s'
				) AS %s_price,
				(
					SELECT		products_lang.price_adviced
					FROM		products_lang
					WHERE		products_lang.productID = products.productID
						AND		products_lang.code = '%s'
				) AS %s_price_adviced,",
			$value['code'],
			strtolower($value['code']),
			$value['code'],
			strtolower($value['code']),
			$value['code'],
			strtolower($value['code'])
		);
	}
	
	$query2 = sprintf(
		"	SELECT		%s
						products.name,
						products.price,
						products.price_adviced
			FROM		products
			WHERE		products.productID = %d",
		$languages,
		$row['productID']
	);
	$result2 = $mb->query($query2);
	
	$name = array();
	$price = array();
	$price_adviced = array();
	$stock = array();
	
	while($row2 = $mb->fetch_assoc($result2))
	{
		$query3 = sprintf(
			"	SELECT		promotions_products.*
				FROM		promotions_products
				WHERE		promotions_products.productID = %d
				LIMIT		0,1",
			$row['productID']
		);
		$result3 = $mb->query($query3);
		
		if($mb->num_rows($result3))
		{
			$row3 = $mb->fetch_assoc($result3);
			
			if($row3['discount_type'] == 1 && $row3['discount'] > 0 && $row3['discount'] < 100)
			{
				$row2['price'] = $row2['price'] - number_format(($row2['price']/100*$row3['discount']), 2);
			}
			else if($row3['discount_type'] == 2 && ($row2['price']-$row3['discount']) > 0)
			{
				$row2['price'] = $row2['price']-$row3['discount'];
			}
			
			$row2['price'] = number_format($row2['price'], 2);
		}
		
		
		$name["nl"] = $row2['name'];
		$name_sort = $row2['name'];
		
		foreach($_lang AS $value)
		{
			$name[strtolower($value['code'])] = $row2[strtolower($value['code']) . '_name'];
		}
		
		$price["nl"] = $row2['price'];
		
		foreach($_lang AS $value)
		{
			$price[strtolower($value['code'])] = $row2[strtolower($value['code']) . '_price'];
		}
		
		$price_adviced["nl"] = $row2['price_adviced'];
		
		foreach($_lang AS $value)
		{
			$price_adviced[strtolower($value['code'])] = $row2[strtolower($value['code']) . '_price_adviced'];
		}
		
		if(!isset($name['en']) || $name['en'] == "")
		{
			$name['en'] = $name['nl'];
		}
	}
	
	
	
	$query2 = sprintf(
		"	SELECT		products_media.productMediaID
			FROM		products_media
			WHERE		products_media.productID = %d
				AND		products_media.thumb = 1",
		$row['productID']
	);
	$result2 = $mb->query($query2);
	$row2 = $mb->fetch_assoc($result2);
	
	$image = "";
	
	if(file_exists("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/media/products/" . $row2['productMediaID'] . ".png"))
	{
		$image = "https://merchant.justinharings.nl/library/media/products/" . $row2['productMediaID']  . ".png";
	}
	
	
	
	$query2 = sprintf(
		"	SELECT		SUM(reviews.stars) AS stars
			FROM		reviews
			WHERE		reviews.productID = %d",
		$row['productID']
	);
	$result2 = $mb->query($query2);
	$row2 = $mb->fetch_assoc($result2);
	
	$stars = $row2['stars'];
	
	
	
	$query2 = sprintf(
		"	SELECT		products_filters.*
			FROM		products_filters
			WHERE		products_filters.productID = %d",
		$row['productID']
	);
	$result2 = $mb->query($query2);
	
	$filters = array();
	
	while($row2 = $mb->fetch_assoc($result2))
	{
		$filters[$row2['filterID']][$row2['language']] = $row2['value'];
	}
	
	
	
	if($row['brand'] != "")
	{
		$filters[0]["NL"] = $row['brand'];
		
		foreach($_lang AS $value)
		{
			$filters[0][$value['code']] = $row['brand'];
		}
	}
	
	
	
	$insert[$num]['merchantID'] = $row['merchantID'];
	$insert[$num]['productID'] = $row['productID'];
	$insert[$num]['categoryID'] = $row['categoryID'];
	$insert[$num]['name'] = serialize($name);
	$insert[$num]['name_sort'] = $name_sort;
	$insert[$num]['price'] = serialize($price);
	$insert[$num]['price_adviced'] = serialize($price_adviced);
	$insert[$num]['image'] = $image;
	$insert[$num]['review_stars'] = $stars;
	$insert[$num]['stock'] = $row['stock_type'];
	$insert[$num]['sale'] = ($row['status'] == 2 ? 1 : 0);
	$insert[$num]['status'] = $row['status'];
	$insert[$num]['filters'] = serialize($filters);
	
	$num++;
}


$query = "TRUNCATE TABLE products_cache";
$mb->query($query);


foreach($insert AS $key => $value)
{
	$query = sprintf(
		"	INSERT INTO		products_cache
			SET				products_cache.merchantID = %d,
							products_cache.productID = %d,
							products_cache.categoryID = %d,	
							products_cache.name = '%s',
							products_cache.article_code = '%s',
							products_cache.barcode = '%s',
							products_cache.name_sort = '%s',
							products_cache.price = '%s',
							products_cache.price_adviced = '%s',
							products_cache.image = '%s',
							products_cache.review_stars = %d,
							products_cache.sale = %d,
							products_cache.status = %d,
							products_cache.stock_type = %d,
							products_cache.filters = '%s'",
		$value['merchantID'],
		$value['productID'],
		$value['categoryID'],
		$mb->real_escape_string($value['name']),
		$mb->real_escape_string($value['article_code']),
		$mb->real_escape_string($value['barcode']),
		$mb->real_escape_string($value['name_sort']),
		$value['price'],
		$value['price_adviced'],
		$mb->real_escape_string($value['image']),
		$value['review_stars'],
		$value['sale'],
		$value['status'],
		$value['stock'],
		$mb->real_escape_string($value['filters'])
	);
	$mb->query($query);
}
?>