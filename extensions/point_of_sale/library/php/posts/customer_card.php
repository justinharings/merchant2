<?php
if(!isset($_SESSION))
{
	session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$return = $mb->_runFunction(
	"customers",
	"saveCustomerCard",
	array(
		$_SESSION['merchantID'],
		$_POST
	)
);

$force = "customers/search/" . $_POST['customerID'];

header("location: /extensions/point_of_sale/modules/popup_close.php?force=" . $force);
?>