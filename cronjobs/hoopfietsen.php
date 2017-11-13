<?php
define("_DEVELOPMENT_ENVIRONMENT", true);
	
require_once("/var/www/vhosts/justinharings.nl/merchant.justinharings.nl/library/php/classes/database.php");
$db = new database();


function searchArray($array, $field, $value)
{
   foreach($array AS $key => $values)
   {
	   if(isset($values[$field]) && $values[$field] == $value)
	   {
			return $array[$key];
	   }
   }
   
   return false;
}

function sortArray($data, $field) 
{
    $field = (array) $field;
	
    uasort($data,
		function($a, $b) use($field) 
		{
			$retval = 0;
			
			foreach($field as $fieldname) 
			{
				if($retval == 0) $retval = strnatcmp($a[$fieldname], $b[$fieldname]);
			}
			
			return $retval;
		} 
	);
	
    return $data;
}



$array = file_get_contents("http://hoopfietsen.nl/index.php?user=Haringsvof&passw=Welkom0!&option=com_rsfiles&task=rsfiles.download&path=hoopfietsen_products_export.xml&Itemid=689");
$array = new SimpleXMLElement($array);


$stock = array();
$num = 0;


foreach($array as $element) 
{
	foreach($element as $key => $val) 
	{
		if($key == "EanNummer")
		{
			$barcode = $val;
		}
		
		if($key == "UitAssortiment")
		{
			$soldout = $val;
		}
		
		if($key == "Voorraad")
		{
			$stock_value = $val;
		}
	}
	
	if($barcode == "" || $soldout == "")
	{
		continue;
	}
	
	$stock[$num] = array();
	$stock[$num]['barcode'] = $barcode;
	$stock[$num]['stock'] = ($stock_value == 0 ? 0 : 1);
	
	if($stock[$num]['stock'] == 0 && $soldout == "J")
	{
		$stock[$num]['stock'] = 2;
	}

	$num++;
}


$stock = sortArray($stock, 'stock');
$stock = array_reverse($stock);



$query = sprintf(
	"	UPDATE		products
		SET			products.externalStock = 0
		WHERE		products.externalStockID = 4"
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
		WHERE		products.externalStockID = 4"
);
$result = $db->query($query);

while($row = $db->fetch_assoc($result))
{
	if(trim($row['barcode']) != "" && strlen($row['barcode']) > 5)
	{
		$row['supplier_code'] = $row['barcode'];
	}
	
	$values = "";
	$values = searchArray($stock, "barcode", $row['supplier_code']);
	
	if($row['supplier_code'] != "" && $values['barcode'] == $row['supplier_code'])
	{
		$query = sprintf(
			"	UPDATE		products
				SET			products.externalStock = %d
				WHERE		products.productID = %d",
			($values['stock'] == 2 ? 0 : $values['stock']),
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
		
		if($row['stock'] <= 0 && $values['stock'] == 0)
		{
			$query = sprintf(
				"	UPDATE		products
					SET			products.status = 3
					WHERE		products.productID = %d",
				$row['productID']
			);
			$db->query($query);
		}
		else if($values['stock'] == 2)
		{
			$query = sprintf(
				"	UPDATE		products
					SET			products.status = 4
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
				SET			products.status = 4
				WHERE		products.productID = %d",
			$row['productID']
		);
		$db->query($query);
	}
}

echo "done.";
?>