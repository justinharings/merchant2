<?php
if(!isset($_SESSION))
{
	session_start();
}



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"customers",
	"saveCard",
	array(
		$_SESSION['merchantID'],
		$_POST
	)
);

// Replace the ID of the inserted or updated form.
$_POST['returnURL'] = str_replace("[dataID]", intval($return), $_POST['returnURL']);

if($return && isset($_POST['returnURL']))
{
	header("location: " . $_POST['returnURL']);
	exit;
}

$mb->_throwUserError();
?>