<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"shipment_methods",
	"load",
	array(
		$_POST['shipmentID']
	)
);

print json_encode($return);
?>