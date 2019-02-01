<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$_POST['workorderID'] = $_GET['workorderID'];
$_POST['status'] = 1;
	
$mb = new motherboard();
$workorder = $mb->_runFunction("workorders", "loadWorkorder", array($_POST['workorderID']));

$mb->_runFunction("workorders", "saveWorkorderStatus", array($_SESSION['merchantID'], $_POST));

$mb->_runFunction("mailserver", "sendAllSMS", array($_SESSION['merchantID'], 1, $_GET['phone_number'], $_GET['workorderID'], 0));

header("location: /workshop/modules/" . ($workorder['used_product'] == 1 ? "used" : "open") . "/");
?>