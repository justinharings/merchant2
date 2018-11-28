<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", "nl");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
	
foreach($_POST['productID'] AS $key => $productID)
{
	$status = $_POST['status'][$key];
	
	if($status == 0)
	{
		$query = sprintf(
			"	UPDATE		products
				SET			products.status = 1
				WHERE		products.productID = %d",
			$productID
		);
		$mb->query($query);
	}
	else if($status == 1)
	{
		$query = sprintf(
			"	UPDATE		products
				SET			products.status = 4
				WHERE		products.productID = %d",
			$productID
		);
		$mb->query($query);
	}
	else if($status == 2)
	{
		$query = sprintf(
			"	UPDATE		products
				SET			products.deleted = 1
				WHERE		products.productID = %d",
			$productID
		);
		$mb->query($query);
	}
}

$query = sprintf(
	"	UPDATE		assistent
		SET			assistent.timer = NOW()"
);
$mb->query($query);

header("location: /assistent/?module=cleanup");
?>