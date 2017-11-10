<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

foreach($_POST['productIDs'] AS $key => $value)
{
	$action = $_POST['action_' . $value];
	
	if($action == 1)
	{
		$query = sprintf(
			"	UPDATE			products
				SET				products.status = 4,
								products.date_update = NOW()
				WHERE			products.productID = %d",
			$value
		);
		$mb->query($query);
	}
	else
	{
		$query = sprintf(
			"	UPDATE			products
				SET				products.date_update = NOW()
				WHERE			products.productID = %d",
			$value
		);
		$mb->query($query);
	}
}

header("location: /assistent/modules/articles/");
?>