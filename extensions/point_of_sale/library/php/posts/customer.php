<?php
if(!isset($_SESSION))
{
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$return = $mb->_runFunction(
	"customers",
	"save",
	array(
		$_SESSION['merchantID'],
		$_POST
	)
);

if(!$_POST['customerID'])
{
	$_SESSION['customer'] = $return;
	$force = "register";
}
else
{
	$force = "customers/search/" . $_POST['customerID'];
}

header("location: /extensions/point_of_sale/modules/popup_close.php?force=" . $force);
?>