<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$_POST['workorderID'] = $_GET['workorderID'];
$_POST['status'] = 2;
	
$mb = new motherboard();
$mb->_runFunction("workorders", "saveWorkorderStatus", array($_SESSION['merchantID'], $_POST));

$mb->_runFunction("mailserver", "sendAllSMS", array($_SESSION['merchantID'], 2, $_GET['phone_number'], $_GET['workorderID'], 0));

header("location: /workshop/modules/open/");
?>