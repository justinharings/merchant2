<?php
if(!isset($_SESSION))
{
	session_start();
}


require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
	
$mb = new motherboard();
$mb->_runFunction("pos", "saveParking", array($_SESSION['merchantID'], serialize($_SESSION)));

require_once(__DIR__ . "/cart_reset.php");

header("location: /pos/modules/register/");
?>