<?php
if(!isset($_SESSION))
{
	session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"products",
	"save",
	array(
		$_SESSION['merchantID'],
		$_POST,
		$_FILES
	)
);

// Replace the ID of the inserted or updated form.
$_POST['returnURL'] = str_replace("[dataID]", intval($return), $_POST['returnURL']);

if(intval($return) >= 0 && isset($_POST['returnURL']))
{
	header("location: " . $_POST['returnURL']);
	exit;
}

$mb->_throwUserError();
?>