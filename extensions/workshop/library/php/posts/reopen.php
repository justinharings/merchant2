<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$_POST['workorderID'] = $_GET['workorderID'];
$_POST['status'] = 0;
	
$mb = new motherboard();
$mb->_runFunction("workorders", "saveWorkorderStatus", array($_SESSION['merchantID'], $_POST));

$return = "hold";

if(isset($_GET['return_closed']))
{
	$return = "done";
}

header("location: /workshop/modules/" . $return . "/");
?>