<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();

$workorder = $mb->_runFunction("workorders", "loadWorkorder", array($_GET['workorderID']));
$card = $mb->_runFunction("workorders", "loadWorkorderCard", array($_GET['workorderID']));
$defaults = $mb->_runFunction("workorders", "defaultProductCodes", array($_SESSION['merchantID']));

$_SESSION['key_number'] = $workorder['key_number'];

if($workorder['customerID'] > 0)
{
	$_SESSION['customer'] = $workorder['customerID'];
}

if($defaults['products'] > 0 && $defaults['manhours'] > 0)
{
	$nmbr = count($_SESSION['cart']);
	$cart = array();
	
	foreach($card AS $value)
	{
		$productID = $defaults['products'];
		
		if($value['description'] == "Montage kosten")
		{
			$productID = $defaults['manhours'];
		}
		
		$cart[$nmbr]['code'] = $productID;
		$cart[$nmbr]['price'] = $value['price'];
		$cart[$nmbr]['name'] = $value['description'];
		$cart[$nmbr]['quantity'] = 1;
		
		$nmbr++;
	}
	
	$_SESSION['cart'] = $cart;
	
	$mb->_runFunction("workorders", "delete", array($_GET['workorderID'], $workorder['customerID']));
}

header("location: /pos/modules/register/");
?>