<?php
if(!isset($_SESSION))
{
	session_start();
}

if(isset($_GET['parkingID']))
{
	require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");
		
	$mb = new motherboard();
	$item = $mb->_runFunction("pos", "loadParked", array($_GET['parkingID'], true));
	
	require_once(__DIR__ . "/cart_reset.php");
	
	$_SESSION = unserialize($item['sessions']);
}

header("location: /pos/modules/register/");
?>