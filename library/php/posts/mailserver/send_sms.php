<?php
if(!isset($_SESSION))
{
	session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"mailserver",
	"sendSms",
	array(
		$_SESSION['merchantID'],
		$_POST['receiver'],
		$_POST['content'],
		$_POST['customerID'],
		$_POST['workorderID'],
		$_POST['orderID']
	)
);
?>