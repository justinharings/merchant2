<?php
// Start session

if(!isset($_SESSION))
{
	session_start();
}


/*
**	POS Only operates in the NL language pack.
*/

define("_LANGUAGE_PACK", "nl");



/*
**	Tell the classes and functions if the development
**	mode is activated or not. This will allow the classes
**	to display a user-friendly message or the real 
**	PHP exception for the developer.
*/

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

define("_DEVELOPMENT_ENVIRONMENT", (strpos($actual_link, "dev.") !== false ? true : false));
$_SESSION['_DEVELOPMENT_ENVIRONMENT'] = _DEVELOPMENT_ENVIRONMENT;



/*
**	Functions are added here. Used for quick access to all
**	of the extended special functions, all the files
**	are added to the core here.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");



/*
**	Classes are included here. We use a motherboard
**	class that is able to construct all the classes
**	and is able to run this class his function.
*/

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();
	
$_GET['dataID'] = 0;
$_GET['duplicate'] = true;

$stock = unserialize($_SESSION['stock']);

$data['barcode'] = $stock['barcode'];
$data['supplier_code'] = $stock['supplier_code'];
$data['price_purchase'] = $stock['price_purchase'];
$data['price_adviced'] = $stock['price_adviced'];
$data['externalStockID'] = $stock['externalStockID'];
$data['delivery_days'] = $stock['delivery_days'];
$data['visibility'] = 3;
$data['groupID'] = 1;
$data['taxesID'] = 1;
$data['stock_type'] = 6;

$data['brandID'] = 0;

switch($data['externalStockID'])
{
	case 1:
		$data['brandID'] = 8;
	break;
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/modules/catalogus/form-artikel.php");
?>