<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();


$query = sprintf(
	"	SELECT		products.productID
		FROM		products
		WHERE		products.article_code = '%s'
			AND		products.merchantID = %d",
	intval($_POST['article_code']),
	$_SESSION['merchantID']
);
$result = $mb->query($query);
$row = $mb->fetch_assoc($result);

$query2 = sprintf(
	"	SELECT		locations.locationID
		FROM		locations
		WHERE		locations.webshop = 1
			AND		locations.merchantID = %d",
	intval($_SESSION['merchantID'])
);
$result2 = $mb->query($query2);
$row2 = $mb->fetch_assoc($result2);


$opt = array(
	$row['productID'],
	$row2['locationID'],
	$_POST['stock']
);

$mb->_runFunction("stock", "updateStock", $opt);

header("location: /extensions/point_of_sale/modules/popup_close.php?force=products/");
?>