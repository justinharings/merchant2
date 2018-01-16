<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();

$return = $mb->_runFunction(
	"categories",
	"loadDescription",
	array(
		$_POST['descriptionID']
	)
);

print json_encode($return);
?>