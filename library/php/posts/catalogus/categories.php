<?php
if(!isset($_SESSION))
{
	session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$merchantID = $_SESSION['merchantID'];

if(isset($_POST['merchantID']))
{
	$merchantID = $_POST['merchantID'];
}

$return = $mb->_runFunction(
	"categories",
	"save",
	array(
		$merchantID,
		$_POST
	)
);

if($return && isset($_POST['returnURL']))
{
	header("location: " . $_POST['returnURL']);
	exit;
}

$mb->_throwUserError();
?>