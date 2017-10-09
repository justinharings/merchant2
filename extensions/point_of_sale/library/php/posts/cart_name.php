<?php
if(!isset($_SESSION))
{
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$product = $mb->_runFunction("categories", "returnProductBasedOnArticleCode", array($_SESSION['merchantID'], $_POST));

foreach($_SESSION['cart'] AS $key => $value)
{
	if($key == $_POST['key'])
	{
		$_SESSION['cart'][$key]['name'] = $mb->real_escape_string($_POST['value']);
	}
}

header("location: /extensions/point_of_sale/modules/popup_close.php");
?>