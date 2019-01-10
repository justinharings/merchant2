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
	
	foreach(unserialize($item['sessions']) AS $key => $value)
	{
		$_SESSION[$key] = $value;
	}
}

header("location: /pos/modules/register/");
?>