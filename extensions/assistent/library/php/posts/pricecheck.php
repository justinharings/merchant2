<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

foreach($_POST['productIDs'] AS $key => $value)
{
	$action = $_POST['action_' . $value];
	$price = $_POST['price_' . $value];
	
	if($action == 1)
	{
		$query = sprintf(
			"	SELECT		products.price
				FROM		products
				WHERE		products.productID = %d",
			$value
		);
		$result = $mb->query($query);
		$row = $mb->fetch_assoc($result);
		
		$old_price = $row['price'];
		
		$query = sprintf(
			"	UPDATE		products
				SET			products.price = '%.2f'
				WHERE		products.productID = %d",
			$price,
			$value
		);
		$mb->query($query);
		
		$query = sprintf(
			"	SELECT		products_lang.*
				FROM		products_lang
				WHERE		products_lang.productID = %d",
			$value
		);
		$result = $mb->query($query);
		
		while($row = $mb->fetch_assoc($result))
		{
			$new_price = $price + ($row['price'] - $old_price);
			
			$query2 = sprintf(
				"	UPDATE		products_lang
					SET			products_lang.price = '%.2f'
					WHERE		products_lang.languageID = %d",
				$new_price,
				$row['languageID']
			);
			$mb->query($query2);
		}
	}
}

header("location: /assistent/modules/pricecheck/");
?>