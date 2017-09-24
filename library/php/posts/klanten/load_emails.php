<?php
if(!isset($_SESSION))
{
	session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"customers",
	"loadEmails",
	array(
		$_SESSION['merchantID'],
		$_POST
	)
);

print json_encode($return);
?>