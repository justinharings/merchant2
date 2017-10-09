<?php
if(!isset($_SESSION))
{
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$mb->_runFunction("products", "saveBarcode", array($_SESSION['merchantID'], $_POST['key'], $_POST['barcode']));

header("location: /extensions/point_of_sale/modules/popup_close.php");
?>