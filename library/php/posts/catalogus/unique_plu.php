<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"products",
	"loadFromCode",
	array(
		$_SESSION['merchantID'],
		$_POST['article_code']
	)
);

print json_encode($return);
?>