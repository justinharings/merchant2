<?php
if(!isset($_SESSION))
{
	session_start();
}



define("_LANGUAGE_PACK", "nl");

$_SESSION['merchantID'] = 1;



$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

define("_DEVELOPMENT_ENVIRONMENT", (strpos($actual_link, "dev.") !== false ? true : false));
$_SESSION['_DEVELOPMENT_ENVIRONMENT'] = _DEVELOPMENT_ENVIRONMENT;



require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/arrays.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/floats.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/functions/text.php");

require_once($_SERVER['DOCUMENT_ROOT'] . "/library/php/classes/motherboard.php");



$mb = new motherboard();


if(isset($_POST['orderID']))
{
	$query = sprintf(
		"	DELETE FROM		ass2_calendar
			WHERE			ass2_calendar.orderID = %d",
		intval($_POST['orderID'])
	);
	$mb->query($query);
	
	$query = sprintf(
		"	INSERT INTO		ass2_calendar
			SET				ass2_calendar.orderID = %d,
							ass2_calendar.date = '%s',
							ass2_calendar.ready = 0",
		intval($_POST['orderID']),
		$_POST['date']
	);
	$mb->query($query);
}

header("location: /assistent2/");
?>