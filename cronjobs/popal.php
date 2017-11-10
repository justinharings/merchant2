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



$array = file_get_contents("http://plm.popal.nl/webservice/channel/2");
$array = json_decode($array, true);

$stock = array();
$num = 0;

foreach($array AS $key => $values)
{
	foreach($values['products'] AS $key_p => $value_p)
	{
		foreach($value_p['properties'] AS $key_pr => $value_pr)
		{
			if($value_pr['name']['nl_nl'] == "Barcode")
			{		
				$barcode = $value_pr['value'];
			}

			if($value_pr['name']['nl_nl'] == "Voorraad aanwezig")
			{
				$stock_value = ($value_pr['value'] == 1 ? 1 : 0);
			}
		}

		if($barcode != "")
		{
			$stock[$num] = array();
			$stock[$num]['barcode'] = $barcode;
			$stock[$num]['stock'] = $stock_value;
	
			$num++;
		}
	}
}

$stock = sortArray($stock, 'stock');
$stock = array_reverse($stock);



$query = sprintf(
	"	UPDATE		products
		SET			products.externalStock = 0
		WHERE		products.externalStockID = 1"
);
$db->query($query);

$query = sprintf(
	"	SELECT		products.productID,
					products.supplier_code,
					products.barcode,
					products_stock.stock
		FROM		products
		LEFT JOIN	products_stock ON products_stock.productID = products.productID
		WHERE		products.externalStockID = 1"
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
			$values['stock'],
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
	}
}

echo "done.";
?>