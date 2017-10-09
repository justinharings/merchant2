<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$mb->_runFunction("workorders", "saveWorkorder", array($_SESSION['merchantID'], $_POST));

$popup = true;
require_once(__DIR__ . "/cart_reset.php");
?>