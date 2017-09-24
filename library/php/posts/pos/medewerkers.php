<?php
if(!isset($_SESSION))
{
	session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"pos",
	"saveEmployeeSettings",
	array(
		$_SESSION['merchantID'],
		$_POST,
		$_FILES
	)
);

if($return && isset($_POST['returnURL']))
{
	header("location: " . $_POST['returnURL']);
	exit;
}

$mb->_throwUserError();
?>