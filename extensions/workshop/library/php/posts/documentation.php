<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$_POST['workorderID'] = $_GET['workorderID'];
$_POST['status'] = 1;
	
$mb = new motherboard();
$mb->_runFunction("workorders", "saveDocumentation", array($_SESSION['merchantID'], $_POST));

header("location: /extensions/workshop/modules/popup_close.php");
?>