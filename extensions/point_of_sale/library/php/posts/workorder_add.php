<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$wokorderID = $mb->_runFunction("workorders", "saveWorkorder", array($_SESSION['merchantID'], $_POST));

unset($_SESSION['print_button_order']);
unset($_SESSION['print_button_workorder']);

$_SESSION['print_button_workorder'] = $wokorderID;

$popup = true;
require_once(__DIR__ . "/cart_reset.php");
?>