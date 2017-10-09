<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$result = $mb->_runFunction("customers", "searchByCard", array($_POST));

print json_encode($result);
?>