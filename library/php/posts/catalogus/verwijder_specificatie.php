<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"products",
	"deleteProperty",
	array(
		$_SESSION['merchantID'],
		$_GET
	)
);

if($return && isset($_GET['returnURL']))
{
	header("location: " . $_GET['returnURL']);
	exit;
}

$mb->_throwUserError();
?>