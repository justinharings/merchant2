<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();
$mb->_runFunction("workorders", "saveWorkorderBatteryTest", array($_SESSION['merchantID'], $_POST, $_SESSION['employeeID']));

header("location: /extensions/workshop/modules/popup_close.php");
?>