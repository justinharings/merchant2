<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");

$mb = new motherboard();
$mb->_runFunction("workorders", "saveWorkorderBattery", array($_SESSION['merchantID'], $_POST));

header("location: /extensions/workshop/modules/popup_close.php");
?>