<?php
if(!isset($_SESSION))
{
	session_start();
}

define("_LANGUAGE_PACK", $_SESSION['_LANGUAGE_PACK']);

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$data = $mb->_runFunction("products", "view", array($_SESSION['merchantID'], "export", "LPAD(products.article_code, 5, 0)", "0,9999"));


$array = Array(
	0 => Array(
	        0 => "Product #",
	        1 => "Artikelcode",
	        2 => "Leverancier code",
	        3 => "Barcode",
	        4 => "Naam",
	        5 => "Prijs",
	        6 => "Adviesprijs",
	        7 => "Inkoopsprijs",
	        8 => "Voorraad",
	        9 => "Zichtbaar"
	)
);

$num = 1;

foreach($data AS $product)
{
	if($_POST['groupID'] > 0 && $product['groupID'] != $_POST['groupID'])
	{
		continue;
	}
	
	$pArray = array(
		0 => $product['productID'],
		1 => $product['article_code'],
		2 => $product['supplier_code'],
		3 => $product['barcode'],
		4 => strip_tags($product['name']),
		5 => $product['price'],
		6 => $product['price_adviced'],
		7 => $product['price_purchase'],
		8 => $product['stock'],
		9 => $mb->_runFunction("products", "translateVisibility", array($product['visibility']))
	);
	
	$pArray = array($num => $pArray);
	
	$array = array_merge($array, $pArray);
	$num++;
}

header("Content-Disposition: attachment; filename=\"export.xls\"");
header("Content-Type: application/vnd.ms-excel;");
header("Pragma: no-cache");
header("Expires: 0");

$out = fopen("php://output", 'w');

foreach ($array as $data)
{
    fputcsv($out, $data,"\t");
}

fclose($out);
?>